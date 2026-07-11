<?php

namespace Controllers;

use MVC\Router;
use Model\Apartamento;
use Model\ImagenApart;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ApartamentoController {

    public static function crearApartamento(Router $router): void {
        $propiedad = new Apartamento();
        $errores   = Apartamento::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $_POST['propiedad'];

            if (isset($datos['precio'], $datos['administracion'], $datos['area_total'])) {
                $datos['precio']         = intval(str_replace('.', '', $datos['precio']));
                $datos['administracion'] = intval(str_replace('.', '', $datos['administracion']));
                $datos['area_total']     = intval(str_replace('.', '', $datos['area_total']));
            }

            $propiedad    = new Apartamento($datos);
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
                            (new ImagenApart(['apartamento_id' => $idPropiedad, 'nombre' => $nombreAdicional]))->guardar();
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }

        $router->render('crear/crear-apartamento', ['propiedad' => $propiedad, 'errores' => $errores]);
    }

    public static function actualizarApartamento(Router $router): void {
        $id        = validarORedireccion('/');
        $propiedad = Apartamento::find($id);
        if (!$propiedad) { header('Location: /'); exit; }
        $errores   = Apartamento::getErrores();

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

        $router->render('crear/actualizar-apartamento', ['propiedad' => $propiedad, 'errores' => $errores]);
    }
}
