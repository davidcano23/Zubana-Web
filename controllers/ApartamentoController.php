<?php

namespace Controllers;

use MVC\Router;
use Model\Apartamento;
use Model\ImagenApart;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ApartamentoController {

    public static function crearApartamento(Router $router) {
         $propiedad = new Apartamento();

        //Arreglo mensaje de errores
            $errores = Apartamento::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = $_POST['propiedad'];

            // Convertir precio con puntos a número entero
            if (isset($datos['precio'])) {
                $datos['precio'] = intval(str_replace('.', '', $datos['precio']));
            }

            $propiedad = new Apartamento($datos);

        //Gnerar un nombre unico
        $nombreImagen = md5(uniqid(rand(),true) ).".webp";
        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $manager = new ImageManager(Driver::class);
            $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200,800); 
            $propiedad->setImagen($nombreImagen);
        }

        $errores = $propiedad->validar();

        
        //Revisar el arreglo de errores
        if(empty($errores)) {

            //SUBIDA DE ARCHIVOS

            if(!is_dir(CARPETA_IMAGENES)) {
                mkdir(CARPETA_IMAGENES);
            }

            //Guardar la imagen en el servidor
            $imagen->save(CARPETA_IMAGENES . $nombreImagen);

            $resultado = $propiedad->guardar();

            // Obtener ID insertado
            $idPropiedad = $propiedad->{'id'};

            // Guardar imágenes adicionales si existen
            if (!empty($_FILES['imagenes']['name'][0])) {
                foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
                    if ($tmpName) {
                        // Generar nombre único
                        $nombreImagenAdicional = md5(uniqid(rand(), true)) . ".webp";

                        // Procesar imagen
                        $imagenAdicional = $manager->read($tmpName)->cover(1200,800);

                        // Guardarla en el servidor
                        $imagenAdicional->save(CARPETA_IMAGENES . $nombreImagenAdicional);

                        // Guardarla en la DB
                        $imagenExtra = new ImagenApart([
                            'apartamento_id' => $idPropiedad,
                            'nombre' => $nombreImagenAdicional
                        ]);
                        $imagenExtra->guardar();
                    }
                }
            }
        }
        }

        $router->render('crear/crear-apartamento', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
    }


    public static function actualizarApartamento(Router $router) {

    $propiedad = validarORedireccion('/admin');
    $id = validarORedireccion('/admin');
    $propiedad = Apartamento::find($id);

    $errores = Apartamento::getErrores();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $args = $_POST['propiedad'];

        if (isset($args['precio'])) {
            $args['precio'] = intval(str_replace('.', '', $args['precio']));
        }

        $propiedad->sincronizar($args);
        $errores = $propiedad->validar();

        $manager = new ImageManager(Driver::class); // Instanciar una sola vez

        // Imagen principal
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            $nombreImagen = md5(uniqid(rand(), true)) . ".webp";
            $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200, 800);
            $propiedad->setImagen($nombreImagen);
        }

        if (empty($errores)) {
            // Guardar nueva imagen principal
            if (isset($imagen)) {
                $imagen->save(CARPETA_IMAGENES . $nombreImagen);
            }

            // Imágenes adicionales
            if (!empty($_FILES['imagenes']['name'][0])) {
                // Eliminar anteriores 
                ImagenApart::eliminarTodasDeApartamento($propiedad->{'id'});



                // Subir nuevas
                foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
                    if ($tmpName) {
                        $nombreImagenAdicional = md5(uniqid(rand(), true)) . ".webp";
                        $imagenAdicional = $manager->read($tmpName)->cover(1200, 800);
                        $imagenAdicional->save(CARPETA_IMAGENES . $nombreImagenAdicional);

                        $imagenExtra = new ImagenApart([
                            'apartamento_id' => $propiedad->{'id'},
                            'nombre' => $nombreImagenAdicional
                        ]);
                        $imagenExtra->guardar();
                    }
                }
            }

            // Guardar propiedad
            $propiedad->guardar();
        }
    }

    $router->render('crear/actualizar-apartamento', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
}

}
