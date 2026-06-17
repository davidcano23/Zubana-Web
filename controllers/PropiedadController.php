<?php

namespace Controllers;

use MVC\Router;
use Model\Casa;
use Model\ImagenCasa;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PropiedadController {

    public static function tipoPropiedad(Router $router): void {
        $router->render('propiedades/tipo-propiedad', []);
    }

    public static function crearCasa(Router $router): void {
        $propiedad = new Casa();
        $errores = Casa::getErrores();

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
                if (!is_dir(CARPETA_IMAGENES)) mkdir(CARPETA_IMAGENES);

                if (isset($imagen)) {
                    $imagen->save(CARPETA_IMAGENES . $nombreImagen);
                }

                $propiedad->guardar();
                $idPropiedad = $propiedad->id;

                if (!empty($_FILES['imagenes']['name'][0])) {
                    foreach ($_FILES['imagenes']['tmp_name'] as $tmpName) {
                        if (!$tmpName) continue;
                        try {
                            $nombreAdicional = md5(uniqid(rand(), true)) . ".webp";
                            $manager->read($tmpName)->cover(1200, 800)->save(CARPETA_IMAGENES . $nombreAdicional);
                            (new ImagenCasa(['casa_id' => $idPropiedad, 'nombre' => $nombreAdicional]))->guardar();
                        } catch (\Throwable) {
                            continue;
                        }
                    }
                }
            }
        }

        $router->render('crear/crear-casa', ['propiedad' => $propiedad, 'errores' => $errores]);
    }

    public static function actualizarCasa(Router $router): void {
        $id        = validarORedireccion('/');
        $propiedad = Casa::find($id);
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
                    $imagen->save(CARPETA_IMAGENES . $nombreImagen);
                }
                $propiedad->guardar();
            }
        }

        $router->render('crear/actualizar-casa', ['propiedad' => $propiedad, 'errores' => $errores]);
    }

    public static function eliminar(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id   = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $tipo = trim($_POST['tipo']);

        $mapaModelos = [
            'Casa'              => 'Casa',
            'Casa Campestre'    => 'Casa',
            'Finca'             => 'Casa',
            'Apartamento'       => 'Apartamento',
            'Apartaestudio'     => 'Apartamento',
            'Apartaoficina'     => 'Apartamento',
            'Lote Urbano'       => 'Lote',
            'Lote Bodega'       => 'Lote',
            'Lote Campestre'    => 'Lote',
            'Lote Urbanizable'  => 'Lote',
            'Local'             => 'Local',
        ];

        if ($id && isset($mapaModelos[$tipo])) {
            $clase = "Model\\" . $mapaModelos[$tipo];
            if (class_exists($clase)) {
                $propiedad = $clase::find($id);
                if ($propiedad) $propiedad->eliminar();
            }
        }
    }
}
