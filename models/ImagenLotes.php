<?php

namespace Model;

class ImagenLotes extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad_lotes';
    protected static $columnasDB = ['id', 'lotes_id', 'nombre', 'tenant_id'];

    public $id;
    public $lotes_id;
    public $nombre;
    public $tenant_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->lotes_id = $args['lotes_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->lotes_id;
    }
}

