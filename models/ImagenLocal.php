<?php

namespace Model;

class ImagenLocal extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad_local';
    protected static $columnasDB = ['id', 'local_id', 'nombre'];

    public $id;
    public $local_id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->local_id = $args['local_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->local_id;
    }
}

