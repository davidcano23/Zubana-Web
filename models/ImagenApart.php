<?php

namespace Model;

class ImagenApart extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad_apartamento';
    protected static $columnasDB = ['id', 'apartamento_id', 'nombre'];

    public $id;
    public $apartamento_id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->apartamento_id = $args['apartamento_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->apartamento_id;
    }

    public static function eliminarTodasDeApartamento($apartamentoId) {
        $imagenes = self::whereAll('apartamento_id', $apartamentoId);

        foreach ($imagenes as $imagen) {
            $ruta = CARPETA_IMAGENES . $imagen->{'nombre'};
            if (file_exists($ruta)) {
                unlink($ruta);
            }
            $imagen->eliminarImagenes();
        }

        
    }

    public function borrarImagen() {
        $rutaImagen = CARPETA_IMAGENES . $this->nombre;
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }



}

