<?php

namespace Model;

class ImagenCasa extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad';
    protected static $columnasDB = ['id', 'casa_id', 'nombre', 'tenant_id'];

    public $id;
    public $casa_id;
    public $nombre;
    public $tenant_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->casa_id = $args['casa_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->casa_id;
    }
}

