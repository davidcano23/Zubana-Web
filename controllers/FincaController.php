<?php

namespace Controllers;

use MVC\Router;
use Model\Casa;
use Model\ImagenCasa;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FincaController {

    public static function crearFinca(Router $router): void {
        $propiedad = new Casa();
        $errores   = Casa::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $_POST['propiedad'];

            if (isset($datos['precio'], $datos['administracion'], $datos['area_total'], $datos['area_construida'])) {
                $datos['precio']          = intval(str_replace('.', '', $datos['precio']));
                $datos['administracion']  = intval(str_replace('.', '', $datos['administracion']));
                $datos['area_total']      = intval(str_replace('.', '', $datos['area_total']));
                $datos['area_construida'] = intval(str_replace('.', '', $datos['area_construida']));
            }

            $propiedad    = new Casa($datos);
            $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
            $manager      = new ImageManager(Driver::class);

            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                try {
                    $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200, 800);
                } catch (\Throwable) {
                    $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
                }
                $propiedad->setImagen($nombreImagen);
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
                            (new ImagenCasa(['casa_id' => $idPropiedad, 'nombre' => $nombreAdicional]))->guardar();
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }

        $router->render('crear/crear-finca', ['propiedad' => $propiedad, 'errores' => $errores]);
    }

    public static function actualizarFinca(Router $router): void {
        $id        = validarORedireccion('/');
        $propiedad = Casa::find($id);
        if (!$propiedad) { header('Location: /'); exit; }
        $errores   = Casa::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['propiedad'];

            if (isset($args['precio'], $args['administracion'], $args['area_total'], $args['area_construida'])) {
                $args['precio']          = intval(str_replace('.', '', $args['precio']));
                $args['administracion']  = intval(str_replace('.', '', $args['administracion']));
                $args['area_total']      = intval(str_replace('.', '', $args['area_total']));
                $args['area_construida'] = intval(str_replace('.', '', $args['area_construida']));
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

        $router->render('crear/actualizar-finca', ['propiedad' => $propiedad, 'errores' => $errores]);
    }
}
