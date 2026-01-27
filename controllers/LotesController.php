<?php

namespace Controllers;

use MVC\Router;
use Model\Lote;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Model\ImagenLotes;

class LotesController {

    public static function crearLotes(Router $router) {
         $propiedad = new Lote();

        //Arreglo mensaje de errores
            $errores = Lote::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $_POST['propiedad'];

            // Convertir precio con puntos a número entero
            if (isset($datos['precio']) && isset($datos['administracion'])) {
                $datos['precio'] = intval(str_replace('.', '', $datos['precio']));
                $datos['administracion'] = intval(str_replace('.', '', $datos['administracion']));
            }

            $propiedad = new Lote($datos);

       $manager = new ImageManager(Driver::class);

        if (!empty($_FILES['propiedad']['tmp_name']['imagen'])) {

            $nombreImagen = md5(uniqid(rand(), true)) . ".webp";

            try {
                $imagen = $manager
                    ->read($_FILES['propiedad']['tmp_name']['imagen'])
                    ->cover(1200, 800);

                $propiedad->setImagen($nombreImagen);

            } catch (\Throwable $e) {
                $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
            }
        }

        // ⛔ NO sobrescribir errores
        $errores = array_merge($errores, $propiedad->validar());

        if (empty($errores)) {

            if (!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            if (isset($imagen)) {
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);
            }

            $propiedad->guardar();


            // Obtener ID insertado
            $idPropiedad = $propiedad->{'id'};

            // Guardar imágenes adicionales si existen
            if (!empty($_FILES['imagenes']['name'][0])) {
                foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
                    if ($tmpName) {
                        // Generar nombre único
                        $nombreImagenAdicional = md5(uniqid(rand(), true)) . ".webp";

                        try {
                            $imagenAdicional = $manager
                                ->read($tmpName)
                                ->cover(1200, 800);

                            $imagenAdicional->save(CARPETA_IMAGENES . $nombreImagenAdicional);

                            $imagenExtra = new ImagenLotes([
                                'lotes_id' => $idPropiedad,
                                'nombre' => $nombreImagenAdicional
                            ]);
                            $imagenExtra->guardar();

                        } catch (\Throwable $e) {
                            // Saltar imagen inválida (HEIC u otra)
                            continue;
                        }
                    }
                }
            }
        }
        }

        $router->render('crear/crear-lote', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
    }


    public static function actualizarLotes(Router $router) {

         $propiedad = validarORedireccion('/');
        $id = validarORedireccion('/');
        $propiedad = Lote::find($id);

       //Arreglo mensaje de errores
            $errores = Lote::getErrores();

            if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $args = $_POST['propiedad'];

        // Convertir precio con puntos a número entero
        if (isset($args['precio']) && isset($args['administracion'])) {
            $args['precio'] = intval(str_replace('.', '', $args['precio']));
            $args['administracion'] = intval(str_replace('.', '', $args['administracion']));
        }

        $propiedad->sincronizar($args);
        ;

        $errores = $propiedad->validar();

        

        if($_FILES['propiedad']['tmp_name']['imagen']) {

        //Subida de archivos
        $nombreImagen = md5(uniqid(rand(),true) ).".webp";

            $manager = new ImageManager(Driver::class);
            try {
                $imagen = $manager
                    ->read($_FILES['propiedad']['tmp_name']['imagen'])
                    ->cover(1200,800);
            } catch (\Throwable $e) {
                $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
            } 
            $propiedad->setImagen($nombreImagen);
        }

    if (empty($errores)) {
    // Guardar imagen solo si existe
    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $imagen->save(CARPETA_IMAGENES . $nombreImagen); // usa la imagen ya seteada
    }

    $propiedad->guardar();
    }
    }


        $router->render('crear/actualizar-lote', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
    }

}