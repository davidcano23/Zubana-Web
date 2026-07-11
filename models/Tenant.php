<?php
namespace Model;

class Tenant extends ActiveRecord {
    protected static $tabla = 'tenants';
    protected static $columnasDB = ['id', 'nombre', 'subdominio', 'estado', 'plan_id', 'created_at'];

    // Estados canónicos = los del ENUM real de la tabla tenants:
    // enum('activo','suspendido','prueba'). NUNCA escribir otros valores:
    // MySQL los rechaza o guarda '' en silencio.
    public const ESTADOS = ['prueba', 'activo', 'suspendido'];

    // Tolera variantes escritas a mano ('Activa', 'ACTIVE', etc.)
    public static function normalizarEstado(?string $estado): string {
        $e = strtolower(trim((string) $estado));
        $mapa = ['activa' => 'activo', 'active' => 'activo', 'suspendida' => 'suspendido'];
        return $mapa[$e] ?? $e;
    }

    public $id;
    public $nombre;
    public $subdominio;
    public $estado;
    public $plan_id;
    public $created_at;

    // Busca un tenant por su subdominio (ej. 'zubana')
    public static function porSubdominio(string $sub): ?object {
        // 1) Sanear: solo letras, números y guion (el Host lo controla el visitante)
        $sub = preg_replace('/[^a-z0-9-]/', '', strtolower($sub));

        // 2) Consulta DIRECTA, sin filtro de tenant
        $query = "SELECT * FROM tenants WHERE subdominio = '{$sub}' LIMIT 1";
        $resultados = self::consultarSQL($query);
        return array_shift($resultados);
    }

    // Busca un tenant por id (consulta global de bootstrap, igual que
    // porSubdominio: se usa ANTES de fijar el contexto de tenant)
    public static function porId(int $id): ?object {
        $query = "SELECT * FROM tenants WHERE id = " . (int) $id . " LIMIT 1";
        $resultados = self::consultarSQL($query);
        return array_shift($resultados);
    }

    // ===== Métodos para el panel superadmin (Fase 7 · Tarea 2) =====
    // Todos pasan por consultaGlobal(), que exige sesión superadmin.

    // Lista TODAS las inmobiliarias (para el listado del panel)
    public static function todasGlobal(): array {
        return self::consultaGlobal(
            "SELECT * FROM tenants ORDER BY created_at DESC"
        );
    }

    // Conteo por estado: ['prueba' => 3, 'activa' => 10, 'suspendido' => 1]
    public static function contarPorEstado(): array {
        $filas = self::consultaGlobal(
            "SELECT estado, COUNT(*) AS total FROM tenants GROUP BY estado"
        );
        $conteo = [];
        foreach ($filas as $fila) {
            $estado = self::normalizarEstado($fila->estado);
            $conteo[$estado] = ($conteo[$estado] ?? 0) + (int) $fila->total;
        }
        return $conteo;
    }

    /**
     * Resumen completo por inmobiliaria (Tarea 3): una fila por tenant con
     * su conteo de propiedades por tipo, total y usuarios.
     *
     * Se usan LEFT JOIN contra subconsultas agrupadas para que una
     * inmobiliaria SIN propiedades igual aparezca (con ceros).
     */
    public static function resumenPorTenant(): array {
        return self::consultaGlobal("
            SELECT
                t.id, t.nombre, t.subdominio, t.estado, t.created_at,
                COALESCE(c.n, 0)  AS casas,
                COALESCE(a.n, 0)  AS apartamentos,
                COALESCE(l.n, 0)  AS locales,
                COALESCE(lo.n, 0) AS lotes,
                COALESCE(c.n, 0) + COALESCE(a.n, 0) + COALESCE(l.n, 0) + COALESCE(lo.n, 0) AS total_propiedades,
                COALESCE(u.n, 0)  AS usuarios
            FROM tenants t
            LEFT JOIN (SELECT tenant_id, COUNT(*) n FROM casa         GROUP BY tenant_id) c  ON c.tenant_id  = t.id
            LEFT JOIN (SELECT tenant_id, COUNT(*) n FROM apartamento  GROUP BY tenant_id) a  ON a.tenant_id  = t.id
            LEFT JOIN (SELECT tenant_id, COUNT(*) n FROM local        GROUP BY tenant_id) l  ON l.tenant_id  = t.id
            LEFT JOIN (SELECT tenant_id, COUNT(*) n FROM lotes        GROUP BY tenant_id) lo ON lo.tenant_id = t.id
            LEFT JOIN (SELECT tenant_id, COUNT(*) n FROM usuarios     GROUP BY tenant_id) u  ON u.tenant_id  = t.id
            ORDER BY total_propiedades DESC, t.created_at DESC
        ");
    }

    // Registros de inmobiliarias agrupados por mes (Tarea 5: gráfica de
    // crecimiento). Devuelve [{mes: '2026-06', total: 2}, ...] en orden.
    public static function crecimientoPorMes(): array {
        return self::consultaGlobal("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS mes, COUNT(*) AS total
            FROM tenants
            WHERE created_at IS NOT NULL
            GROUP BY mes
            ORDER BY mes ASC
        ");
    }

    // ============ Tarea 6: agregación de propiedades por zona ============

    // Las 4 tablas de propiedades unificadas en una sola "vista" con las
    // columnas que el mapa necesita. UNION ALL (no filtra duplicados: son
    // tablas distintas y es más rápido).
    private static function subconsultaPropiedades(): string {
        return "SELECT TRIM(ubicacion) AS ubicacion, modalidad, latitud, longitud, 'casa' AS tipo FROM casa
                UNION ALL SELECT TRIM(ubicacion), modalidad, latitud, longitud, 'apartamento' FROM apartamento
                UNION ALL SELECT TRIM(ubicacion), modalidad, latitud, longitud, 'local' FROM local
                UNION ALL SELECT TRIM(ubicacion), modalidad, latitud, longitud, 'lote' FROM lotes";
    }

    /**
     * Propiedades de TODAS las inmobiliarias agrupadas por ubicación
     * ("Rionegro, Antioquia", etc.), con desglose por tipo y coordenadas
     * promedio para ubicar la zona en el mapa.
     *
     * Filtros opcionales: $tipo (whitelist) y $modalidad (escapada).
     */
    public static function propiedadesPorZona(?string $tipo = null, ?string $modalidad = null): array {
        $condiciones = ["ubicacion IS NOT NULL", "ubicacion != ''"];

        $tiposValidos = ['casa', 'apartamento', 'local', 'lote'];
        if ($tipo !== null && in_array($tipo, $tiposValidos)) {
            $condiciones[] = "tipo = '{$tipo}'";
        }
        if ($modalidad !== null && $modalidad !== '') {
            $m = self::$db->escape_string($modalidad);
            $condiciones[] = "modalidad = '{$m}'";
        }
        $where = implode(' AND ', $condiciones);

        $filas = self::consultaGlobal("
            SELECT ubicacion, tipo, COUNT(*) AS total,
                   AVG(latitud) AS lat, AVG(longitud) AS lng
            FROM (" . self::subconsultaPropiedades() . ") p
            WHERE {$where}
            GROUP BY ubicacion, tipo
        ");

        // Reagrupar por zona: una entrada por ubicación con desglose por tipo
        $zonas = [];
        foreach ($filas as $f) {
            $u = $f->ubicacion;
            if (!isset($zonas[$u])) {
                $zonas[$u] = (object) [
                    'ubicacion' => $u, 'total' => 0,
                    'casa' => 0, 'apartamento' => 0, 'local' => 0, 'lote' => 0,
                    'lat' => null, 'lng' => null,
                ];
            }
            $zonas[$u]->{$f->tipo} += (int) $f->total;
            $zonas[$u]->total     += (int) $f->total;
            // Coordenadas: primer promedio disponible (misma ciudad ≈ mismo punto)
            if ($f->lat !== null && $zonas[$u]->lat === null) {
                $zonas[$u]->lat = round((float) $f->lat, 6);
                $zonas[$u]->lng = round((float) $f->lng, 6);
            }
        }

        $zonas = array_values($zonas);
        usort($zonas, fn($a, $b) => $b->total <=> $a->total);
        return $zonas;
    }

    // Valores de modalidad que existen en los datos (para el filtro del mapa)
    public static function modalidadesDisponibles(): array {
        $filas = self::consultaGlobal("
            SELECT DISTINCT modalidad
            FROM (" . self::subconsultaPropiedades() . ") p
            WHERE modalidad IS NOT NULL AND modalidad != ''
            ORDER BY modalidad
        ");
        return array_map(fn($f) => $f->modalidad, $filas);
    }

    // ===== Tarea 4: lógica de alta y estado, compartida =====

    /**
     * Validaciones de alta de inmobiliaria. Las usan /registro (público)
     * y el panel superadmin, así nunca se desincronizan las reglas.
     */
    public static function validarAlta(string $nombre, string $subdominio, string $email, string $password): array {
        $errores = [];

        if (!preg_match('/^[a-z0-9](?:[a-z0-9-]{1,61}[a-z0-9])$/', $subdominio)) {
            $errores[] = 'El subdominio solo permite minúsculas, números y guiones (3–63 caracteres).';
        }

        $reservados = ['www','admin','api','app','mail','ftp','panel','soporte','blog','superadmin','registro','login'];
        if (in_array($subdominio, $reservados)) {
            $errores[] = 'Ese subdominio no está disponible.';
        }

        if (self::porSubdominio($subdominio)) {
            $errores[] = 'Ese subdominio ya está en uso, elige otro.';
        }

        if ($nombre === '') {
            $errores[] = 'El nombre de la inmobiliaria es obligatorio.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido.';
        }
        if (strlen($password) < 6) {
            $errores[] = 'El password debe tener al menos 6 caracteres.';
        }

        return $errores;
    }

    /**
     * Alta completa: crea el tenant + su primer usuario admin en UNA
     * transacción. Si algo falla, rollback y relanza la excepción.
     * Devuelve el id del nuevo tenant.
     *
     * NO exige superadmin: también la usa el registro público.
     */
    public static function altaConAdmin(string $nombre, string $subdominio, string $email, string $passwordPlano, string $estado = 'prueba'): int {
        $db = self::getDB();
        $db->begin_transaction();
        try {
            $tenant = new self();
            $tenant->nombre     = $nombre;
            $tenant->subdominio = $subdominio;
            $tenant->estado     = in_array($estado, self::ESTADOS) ? $estado : 'prueba';
            $tenant->crear();
            $nuevoId = (int) $db->insert_id;

            if (!$nuevoId) {
                throw new \RuntimeException('No se pudo crear el tenant');
            }

            // Crear el primer admin CON el tenant nuevo, restaurando
            // SIEMPRE el contexto anterior (finally), incluso si falla.
            $anterior = self::$tenantId;
            self::setTenant($nuevoId);
            try {
                $admin = new Admin();
                $admin->email    = $email;
                $admin->password = password_hash($passwordPlano, PASSWORD_DEFAULT);
                $admin->crear();
                if (!$admin->id) {
                    throw new \RuntimeException('No se pudo crear el usuario admin');
                }
            } finally {
                self::setTenant($anterior);
            }

            $db->commit();
            return $nuevoId;

        } catch (\Throwable $e) {
            $db->rollback();
            throw $e;
        }
    }

    /**
     * Cambia el estado de una inmobiliaria (activar / suspender / prueba).
     * SOLO superadmin. Prepared statement + estado validado contra la
     * lista canónica: imposible inyectar valores raros.
     */
    public static function cambiarEstado(int $id, string $estado): bool {
        self::exigirSuperadmin();

        if (!in_array($estado, self::ESTADOS)) {
            throw new \InvalidArgumentException('Estado no válido');
        }

        $stmt = self::$db->prepare("UPDATE tenants SET estado = ? WHERE id = ? LIMIT 1");
        if (!$stmt) return false;
        $stmt->bind_param('si', $estado, $id);
        return $stmt->execute();
    }

    public function crear(): void {
        $atributos = $this->sanetizarAtributos();   // sin tocar tenant_id

        // Quitar nulls y created_at: que la BD aplique sus DEFAULT
        // (plan_id NULL, created_at CURRENT_TIMESTAMP). Antes se pasaba
        // null a escape_string (deprecated) y se insertaba '' en ambos.
        unset($atributos['created_at']);
        $atributos = array_filter($atributos, fn($v) => $v !== null);

        $columnas  = implode(', ', array_keys($atributos));
        $valores   = implode("', '", array_values(array_map(
            fn($v) => self::$db->escape_string((string) $v), $atributos
        )));
        self::$db->query("INSERT INTO tenants ($columnas) VALUES ('$valores')");
    }
}