<?php

namespace Controllers;

use MVC\Router;
use Model\Lote;
use Model\ImagenLotes;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class LotesController {

    public static function crearLotes(Router $router): void {
        $propiedad = new Lote();
        $errores   = Lote::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $_POST['propiedad'];

            if (isset($datos['precio'], $datos['administracion'], $datos['area_total'])) {
                $datos['precio']         = intval(str_replace('.', '', $datos['precio']));
                $datos['administracion'] = intval(str_replace('.', '', $datos['administracion']));
                $datos['area_total']     = intval(str_replace('.', '', $datos['area_total']));
            }

            $propiedad    = new Lote($datos);
            $manager      = new ImageManager(Driver::class);
            $nombreImagen = '';

            if (!empty($_FILES['propiedad']['tmp_name']['imagen'])) {
                $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
                try {
                    $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200, 800);
                    $propiedad->setImagen($nombreImagen);
                } catch (\Throwable) {
                    $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
                }
            }

            $errores = array_merge($errores, $propiedad->validar());

            if (empty($errores)) {
                if (isset($imagen)) {
                    $imagen->save(carpetaImagenes() . $nombreImagen);
                }

                $propiedad->guardar();
                $idPropiedad = $propiedad->id;

                if (!empty($_FILES['imagenes']['name'][0])) {
                    foreach ($_FILES['imagenes']['tmp_name'] as $tmpName) {
                        if (!$tmpName) continue;
                        try {
                            $nombreAdicional = md5(uniqid(rand(), true)) . ".webp";
                            $manager->read($tmpName)->cover(1200, 800)->save(carpetaImagenes() . $nombreAdicional);
                            (new ImagenLotes(['lotes_id' => $idPropiedad, 'nombre' => $nombreAdicional]))->guardar();
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }

        $router->render('crear/crear-lote', ['propiedad' => $propiedad, 'errores' => $errores]);
    }

    public static function actualizarLotes(Router $router): void {
        $id        = validarORedireccion('/');
        $propiedad = Lote::find($id);
        if (!$propiedad) { header('Location: /'); exit; }
        $errores   = Lote::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['propiedad'];

            if (isset($args['precio'], $args['administracion'], $args['area_total'])) {
                $args['precio']         = intval(str_replace('.', '', $args['precio']));
                $args['administracion'] = intval(str_replace('.', '', $args['administracion']));
                $args['area_total']     = intval(str_replace('.', '', $args['area_total']));
            }

            $propiedad->sincronizar($args);
            $errores = $propiedad->validar();

            $nombreImagen = '';
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
                try {
                    $imagen = (new ImageManager(Driver::class))
                        ->read($_FILES['propiedad']['tmp_name']['imagen'])
                        ->cover(1200, 800);
                } catch (\Throwable) {
                    $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
                }
                $propiedad->setImagen($nombreImagen);
            }

            if (empty($errores)) {
                if (isset($imagen)) {
                    $imagen->save(carpetaImagenes() . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render('crear/actualizar-lote', ['propiedad' => $propiedad, 'errores' => $errores]);
    }
}
