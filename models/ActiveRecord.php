<?php

namespace Model;

use mysqli;

class ActiveRecord {

    protected static $db;
    protected static $columnasDB = [];
    protected static $tabla = '';

    public static $errores = [];

    public static function setDB($database): void {
        self::$db = $database;
    }

    public static function escapeString(string $value): string {
        return self::$db->escape_string($value);
    }

    protected static $tenantId = null;

    // Se llama una vez al arrancar la app, para decir "ahora trabajamos con el tenant X"
    public static function setTenant(?int $tenantId): void {
        self::$tenantId = $tenantId;
    }

    // Devuelve el tenant actual; si nadie lo definió, LANZA UN ERROR (no consulta sin filtro)
    protected static function tid(): int {
        if (self::$tenantId === null) {
            throw new \RuntimeException('Tenant no resuelto');
        }
        return self::$tenantId;
    }

    public function guardar(): void {
        if (!is_null($this->{'id'})) {
            $this->actualizar();
        } else {
            $this->crear();
        }
    }

    public function crear(): void {
        $atributos = $this->sanetizarAtributos();

        // Quitar columnas gestionadas por la BD para que actúe su DEFAULT
        unset($atributos['created_at']);

        // Forzar el tenant: siempre nace con el dueño correcto, sin importar lo que envíe el formulario
        $atributos['tenant_id'] = self::tid();

        $query  = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES (";
        $query .= join(', ', array_map(
            fn($v) => $v === null ? "NULL" : "'$v'",
            array_values($atributos)
        ));
        $query .= ")";

        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->{'id'} = self::$db->{'insert_id'};
            header('location: /?Resultado=1');
        }
    }

    public function actualizar(): void {
        $atributos = $this->sanetizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = $value === null ? "{$key}=NULL" : "{$key}='{$value}'";
        }

        $query  = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->{'id'}) . "' AND tenant_id = " . self::tid() . " LIMIT 1";

        $resultado = self::$db->query($query);

        if ($resultado) {
            header('location: /?Resultado=2');
        }
    }

    public function eliminar(): void {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " AND tenant_id = " . self::tid() . " LIMIT 1";

        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
            header('location: /?Resultado=3');
        }
    }

    public function eliminarImagenes(): bool {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " AND tenant_id = " . self::tid();

        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
        }

        return $resultado;
    }

    public function eliminarImg(): bool {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->{'id'}) . " AND tenant_id = " . self::tid() . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
        }

        return $resultado;
    }

    public function atributos(): array {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanetizarAtributos(): array {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = $value === null ? null : self::$db->escape_string(trim((string)$value));
        }

        return $sanitizado;
    }

    public static function getErrores(): array {
        return static::$errores;
    }

    public function validar(): array {
        static::$errores = [];
        return static::$errores;
    }

    public function setImagen($imagen): void {
        if (!is_null($this->{'id'})) {
            $this->borrarImagen();
        }

        if ($imagen) {
            $this->{'imagen'} = $imagen;
        }
    }

    public function borrarImagen(): void {
        if (file_exists(CARPETA_IMAGENES . $this->{'imagen'})) {
            unlink(CARPETA_IMAGENES . $this->{'imagen'});
        }
    }

    public static function filtrar(array $filtros): array {
        $t = self::tid();
        $query = "SELECT * FROM " . static::$tabla . " WHERE tenant_id = {$t}";

        if (!empty($filtros['ciudad'])) {
            $ciudad = self::$db->escape_string($filtros['ciudad']);
            $query .= " AND ciudad LIKE '%$ciudad%'";
        }

        if (!empty($filtros['tipos_array'])) {
            $tipos = array_map(fn($t) => "'" . self::$db->escape_string($t) . "'", $filtros['tipos_array']);
            $query .= " AND tipo IN (" . implode(',', $tipos) . ")";
        } elseif (!empty($filtros['tipo'])) {
            $tipo = self::$db->escape_string($filtros['tipo']);
            $query .= " AND tipo = '$tipo'";
        }

        if (!empty($filtros['precio_min'])) {
            $query .= " AND precio >= " . (int)$filtros['precio_min'];
        }
        if (!empty($filtros['precio_max'])) {
            $query .= " AND precio <= " . (int)$filtros['precio_max'];
        }
        if (!empty($filtros['banos'])) {
            $query .= " AND banos >= " . (int)$filtros['banos'];
        }
        if (!empty($filtros['habitaciones'])) {
            $query .= " AND habitaciones >= " . (int)$filtros['habitaciones'];
        }
        if (!empty($filtros['area_minima'])) {
            $query .= " AND area >= " . (int)$filtros['area_minima'];
        }
        if (!empty($filtros['barrio'])) {
            $barrio = self::$db->escape_string($filtros['barrio']);
            $query .= " AND barrio LIKE '%$barrio%'";
        }

        return self::consultarSQL($query);
    }

    public static function ordenarResultados(array $propiedades, string $criterio): array {
        $parsePrecio = static function ($p): int {
            if ($p === null) return 0;
            if (is_int($p)) return $p;
            $num = preg_replace('/\D+/', '', (string)$p);
            return $num !== '' ? (int)$num : 0;
        };

        $getArea = static function ($obj): float {
            return isset($obj->area_total) && $obj->area_total !== null ? (float)$obj->area_total : 0;
        };

        $cmpIdDesc = static fn($a, $b) => ($b->id ?? 0) <=> ($a->id ?? 0);

        $cmpFechaDesc = static function ($a, $b) use ($cmpIdDesc) {
            $fa = isset($a->created_at) ? strtotime($a->created_at) : 0;
            $fb = isset($b->created_at) ? strtotime($b->created_at) : 0;
            $r  = $fb <=> $fa;
            return $r !== 0 ? $r : $cmpIdDesc($a, $b);
        };

        switch ($criterio) {
            case 'mayor_precio':
                usort($propiedades, function ($a, $b) use ($parsePrecio, $cmpIdDesc) {
                    $r = $parsePrecio($b->precio ?? null) <=> $parsePrecio($a->precio ?? null);
                    return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                });
                break;

            case 'menor_precio':
                usort($propiedades, function ($a, $b) use ($parsePrecio, $cmpIdDesc) {
                    $r = $parsePrecio($a->precio ?? null) <=> $parsePrecio($b->precio ?? null);
                    return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                });
                break;

            case 'mayor_m2':
                usort($propiedades, function ($a, $b) use ($getArea, $cmpIdDesc) {
                    $r = $getArea($b) <=> $getArea($a);
                    return $r !== 0 ? $r : $cmpIdDesc($a, $b);
                });
                break;

            case 'mas_recientes':
            default:
                usort($propiedades, $cmpFechaDesc);
                break;
        }

        return $propiedades;
    }

    public static function todas(): array {
        return self::consultarSQL("SELECT * FROM " . static::$tabla . " WHERE tenant_id = " . self::tid());
    }

    public static function get(int $cantidad): array {
        $t = self::tid();
        $query = "SELECT * FROM " . static::$tabla . " WHERE tenant_id = {$t} ORDER BY RAND() LIMIT " . $cantidad;
        return self::consultarSQL($query);
    }

    public static function getRecomendadas(string $ubicacion, int $idExcluir, int $cantidad): array {
        $t = self::tid();
        $ubicacion = self::$db->escape_string($ubicacion);
        $query = "SELECT * FROM " . static::$tabla . "
                  WHERE tenant_id = {$t}
                  AND ubicacion = '$ubicacion'
                  AND id != $idExcluir
                  ORDER BY RAND()
                  LIMIT $cantidad";
        return self::consultarSQL($query);
    }

    public static function where(string $columna, $valor): array {
        $t = self::tid();
        $columna = self::$db->escape_string($columna);
        $valor   = self::$db->escape_string($valor);
        return self::consultarSQL("SELECT * FROM " . static::$tabla . " WHERE tenant_id = {$t} AND {$columna} = '{$valor}'");
    }

    public static function contar(): int {
        $t = self::tid();
        $resultado = self::$db->query("SELECT COUNT(*) as total FROM " . static::$tabla . " WHERE tenant_id = {$t}");
        return (int)$resultado->fetch_assoc()['total'];
    }

    public static function getPaginadas(int $limite, int $offset, ?string $ordenar = null): array {
        $t = self::tid();
        $orderSQL = match ($ordenar) {
            'mayor_precio' => "ORDER BY precio DESC",
            'menor_precio' => "ORDER BY precio ASC",
            'mayor_m2'     => "ORDER BY area_total DESC",
            'menor_m2'     => "ORDER BY area_total ASC",
            default        => "ORDER BY id DESC",
        };

        $query = "SELECT * FROM " . static::$tabla . " WHERE tenant_id = {$t} $orderSQL LIMIT $limite OFFSET $offset";
        return self::consultarSQL($query);
    }

    public static function find(int $id): ?object {
        $t = self::tid();
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id} AND tenant_id = {$t} LIMIT 1";
        $resultados = self::consultarSQL($query);
        return array_shift($resultados);
    }

    public static function consultarSQL(string $query): array {
        $resultado = self::$db->query($query);
        $array = [];

        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        $resultado->free();

        return $array;
    }

    protected static function crearObjeto(array $registro): static {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    public function sincronizar(array $args = []): void {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
