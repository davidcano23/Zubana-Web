<?php

namespace Model;

class ImagenApart extends ActiveRecord {
    protected static $tabla = 'imagenes_propiedad_apartamento';
    protected static $columnasDB = ['id', 'apartamento_id', 'nombre', 'tenant_id'];

    public $id;
    public $apartamento_id;
    public $nombre;
    public $tenant_id;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->apartamento_id = $args['apartamento_id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }

    public function getPropiedadId() {
        return $this->apartamento_id;
    }

    public static function eliminarTodasDeApartamento(int $apartamentoId): void {
        $imagenes = self::where('apartamento_id', $apartamentoId);

        foreach ($imagenes as $imagen) {
            $ruta = carpetaImagenes() . $imagen->nombre;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
            $imagen->eliminarImagenes();
        }
    }

    public function borrarImagen(): void {
        $rutaImagen = carpetaImagenes() . $this->nombre;
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }



}

