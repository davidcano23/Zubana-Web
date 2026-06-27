<?php

namespace Model;

class Cliente extends ActiveRecord {

    protected static $tabla     = 'clientes';
    protected static $columnasDB = [
        'nombre', 'apellido', 'telefono', 'email', 'ciudad',
        'presupuesto_min', 'presupuesto_max', 'tipo_busqueda',
        'tipo_propiedad', 'fuente', 'estado', 'notas',
    ];

    public ?int    $id             = null;
    public string  $nombre         = '';
    public string  $apellido       = '';
    public string  $telefono       = '';
    public string  $email          = '';
    public string  $ciudad         = '';
    public int     $presupuesto_min = 0;
    public int     $presupuesto_max = 0;
    public string  $tipo_busqueda  = 'Compra';
    public string  $tipo_propiedad = '';
    public string  $fuente         = 'Otro';
    public string  $estado         = 'nuevo';
    public ?string $notas          = null;
    public ?string $created_at     = null;
    public ?string $updated_at     = null;

    // ── Datos de dominio ─────────────────────────────────────

    public static function etapas(): array {
        return [
            'nuevo'       => ['label' => 'Nuevo Lead',     'color' => '#F59E0B'],
            'contactado'  => ['label' => 'Contactado',     'color' => '#3B82F6'],
            'interesado'  => ['label' => 'Interesado',     'color' => '#8B5CF6'],
            'negociacion' => ['label' => 'En Negociación', 'color' => '#F97316'],
            'cerrado'     => ['label' => 'Cerrado',        'color' => '#10B981'],
            'perdido'     => ['label' => 'Perdido',        'color' => '#6B7280'],
        ];
    }

    public static function fuentes(): array {
        return ['Web', 'WhatsApp', 'Instagram', 'Facebook', 'TikTok', 'Referido', 'Llamada', 'Otro'];
    }

    public static function tiposBusqueda(): array {
        return ['Compra', 'Arriendo', 'Ambos'];
    }

    public static function tiposPropiedad(): array {
        return ['Casa', 'Apartamento', 'Finca', 'Lote', 'Local', 'Bodega', 'Otro'];
    }

    // ── Escritura sin redirección (bypass ActiveRecord::crear/actualizar) ──

    public function crearCliente(): bool {
        $attrs = $this->sanetizarAtributos();
        $cols  = join(', ', array_keys($attrs));
        $vals  = join(', ', array_map(fn($v) => $v === null ? 'NULL' : "'$v'", array_values($attrs)));
        $r = self::$db->query("INSERT INTO " . static::$tabla . " ($cols) VALUES ($vals)");
        if ($r) $this->id = (int) self::$db->insert_id;
        return (bool) $r;
    }

    public function actualizarCliente(): bool {
        $attrs = $this->sanetizarAtributos();
        $pares = [];
        foreach ($attrs as $k => $v) {
            $pares[] = $v === null ? "{$k}=NULL" : "{$k}='{$v}'";
        }
        $id = (int) $this->id;
        return (bool) self::$db->query(
            "UPDATE " . static::$tabla . " SET " . join(', ', $pares) . " WHERE id = $id LIMIT 1"
        );
    }

    public function eliminarCliente(): bool {
        $id = (int) $this->id;
        return (bool) self::$db->query(
            "DELETE FROM " . static::$tabla . " WHERE id = $id LIMIT 1"
        );
    }

    public static function cambiarEstado(int $id, string $estado): bool {
        if (!array_key_exists($estado, self::etapas())) return false;
        $estado = self::$db->escape_string($estado);
        return (bool) self::$db->query(
            "UPDATE " . static::$tabla . " SET estado = '$estado' WHERE id = $id LIMIT 1"
        );
    }

    // ── Consultas ─────────────────────────────────────────────

    public static function buscar(string $q = '', string $estado = ''): array {
        $sql = "SELECT * FROM " . static::$tabla . " WHERE 1=1";
        if ($q !== '') {
            $q   = self::$db->escape_string($q);
            $sql .= " AND (nombre LIKE '%$q%' OR apellido LIKE '%$q%'"
                  . " OR telefono LIKE '%$q%' OR email LIKE '%$q%')";
        }
        if ($estado !== '' && array_key_exists($estado, self::etapas())) {
            $estado = self::$db->escape_string($estado);
            $sql   .= " AND estado = '$estado'";
        }
        $sql .= " ORDER BY updated_at DESC";
        return self::consultarSQL($sql);
    }

    public static function porEstado(): array {
        $todos  = self::consultarSQL("SELECT * FROM " . static::$tabla . " ORDER BY updated_at DESC");
        $grupos = array_fill_keys(array_keys(self::etapas()), []);
        foreach ($todos as $c) {
            if (isset($grupos[$c->estado])) {
                $grupos[$c->estado][] = $c;
            }
        }
        return $grupos;
    }

    public static function contarPorEstado(): array {
        $r   = self::$db->query("SELECT estado, COUNT(*) AS total FROM " . static::$tabla . " GROUP BY estado");
        $res = [];
        while ($row = $r->fetch_assoc()) {
            $res[$row['estado']] = (int) $row['total'];
        }
        return $res;
    }

    // ── Helpers de presentación ───────────────────────────────

    public function nombreCompleto(): string {
        return trim($this->nombre . ' ' . $this->apellido);
    }

    public function iniciales(): string {
        return strtoupper(($this->nombre[0] ?? '?') . ($this->apellido[0] ?? ''));
    }

    public function presupuestoFormateado(): string {
        $min = (int) $this->presupuesto_min;
        $max = (int) $this->presupuesto_max;
        if (!$min && !$max) return 'Sin definir';
        $fmt = fn(int $n) => '$' . number_format($n, 0, ',', '.');
        if ($min && $max) return $fmt($min) . ' – ' . $fmt($max);
        return $max ? 'Hasta ' . $fmt($max) : 'Desde ' . $fmt($min);
    }

    public function diasDesdeActualizacion(): int {
        if (!$this->updated_at) return 0;
        return (int) round((time() - strtotime($this->updated_at)) / 86400);
    }

    public function validar(): array {
        static::$errores = [];
        if (!trim($this->nombre)) {
            static::$errores[] = 'El nombre del cliente es obligatorio.';
        }
        return static::$errores;
    }
}
