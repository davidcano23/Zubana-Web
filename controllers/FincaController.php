<?php

namespace Controllers;

use MVC\Router;
use Model\Casa;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Model\ImagenCasa;

class FincaController {

    public static function crearFinca(Router $router) {
         $propiedad = new Casa();

    // Arreglo mensaje de errores
    $errores = Casa::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $_POST['propiedad'];

            // Convertir precio con puntos a número entero
            if (isset($datos['precio']) && isset($datos['administracion'])) {
                $datos['precio'] = intval(str_replace('.', '', $datos['precio']));
                $datos['administracion'] = intval(str_replace('.', '', $datos['administracion']));
            }

            $propiedad = new Casa($datos);

        // Generar un nombre único para la imagen
        $nombreImagen = md5(uniqid(rand(), true)) . ".webp";

        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $manager = new ImageManager(Driver::class);
            try {
                $imagen = $manager
                    ->read($_FILES['propiedad']['tmp_name']['imagen'])
                    ->cover(1200,800);
            } catch (\Throwable $e) {
                $errores[] = 'La imagen principal no es un formato soportado (usa JPG o PNG).';
            }

            $propiedad->setImagen($nombreImagen); // Esto sigue para la imagen principal
        }

        $errores = $propiedad->validar();

        // Revisar errores
        if (empty($errores)) {
            // Crear carpeta si no existe
            if (!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            // Guardar imagen principal en el servidor
            $imagen->save(CARPETA_IMAGENES . $nombreImagen);

            // Guardar propiedad (casa) en DB
            $resultado = $propiedad->guardar();

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

                            $imagenExtra = new ImagenCasa([
                                'casa_id' => $idPropiedad,
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

    // Renderizar vista
    $router->render('crear/crear-finca', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
}


    public static function actualizarFinca(Router $router) {

         $propiedad = validarORedireccion('/');
        $id = validarORedireccion('/');
        $propiedad = Casa::find($id);

       //Arreglo mensaje de errores
            $errores = Casa::getErrores();

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


        $router->render('crear/actualizar-finca', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
    }

}