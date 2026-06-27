<?php

namespace Model;

class CrmActividad extends ActiveRecord {

    protected static $tabla      = 'crm_actividades';
    protected static $columnasDB = ['cliente_id', 'tipo', 'descripcion'];

    public ?int    $id          = null;
    public ?int    $cliente_id  = null;
    public string  $tipo        = 'nota';
    public string  $descripcion = '';
    public ?string $created_at  = null;

    public static function tipos(): array {
        return [
            'nota'     => ['label' => 'Nota',      'ico' => 'N'],
            'llamada'  => ['label' => 'Llamada',   'ico' => 'L'],
            'whatsapp' => ['label' => 'WhatsApp',  'ico' => 'W'],
            'visita'   => ['label' => 'Visita',    'ico' => 'V'],
            'email'    => ['label' => 'Email',     'ico' => 'E'],
            'otro'     => ['label' => 'Otro',      'ico' => 'O'],
        ];
    }

    public function crearActividad(): bool {
        $attrs = $this->sanetizarAtributos();
        $cols  = join(', ', array_keys($attrs));
        $vals  = join(', ', array_map(fn($v) => $v === null ? 'NULL' : "'$v'", array_values($attrs)));
        $r = self::$db->query("INSERT INTO " . static::$tabla . " ($cols) VALUES ($vals)");
        if ($r) $this->id = (int) self::$db->insert_id;
        return (bool) $r;
    }

    public static function getByCliente(int $clienteId): array {
        $id = (int) $clienteId;
        return self::consultarSQL(
            "SELECT * FROM " . static::$tabla .
            " WHERE cliente_id = $id ORDER BY created_at DESC"
        );
    }
}
