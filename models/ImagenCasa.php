<?php

namespace Model;

class ImagenCasa extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad';
    protected static $columnasDB = ['id', 'casa_id', 'nombre'];

    public $id;
    public $casa_id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->casa_id = $args['casa_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->casa_id;
    }
}

