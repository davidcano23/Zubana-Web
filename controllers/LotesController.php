<?php

namespace Controllers;

use MVC\Router;
use Model\Lote;
use Intervention\Image\Drivers\Gd\Driver;
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
            if (isset($datos['precio'])) {
                $datos['precio'] = intval(str_replace('.', '', $datos['precio']));
            }

            $propiedad = new Lote($datos);

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
                        $imagenExtra = new ImagenLotes([
                            'lotes_id' => $idPropiedad,
                            'nombre' => $nombreImagenAdicional
                        ]);
                        $imagenExtra->guardar();
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

         $propiedad = validarORedireccion('/admin');
        $id = validarORedireccion('/admin');
        $propiedad = Lote::find($id);

       //Arreglo mensaje de errores
            $errores = Lote::getErrores();

            if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $args = $_POST['propiedad'];

        // Convertir precio con puntos a número entero
        if (isset($args['precio'])) {
            $args['precio'] = intval(str_replace('.', '', $args['precio']));
        }

        $propiedad->sincronizar($args);
        ;

        $errores = $propiedad->validar();

        

        if($_FILES['propiedad']['tmp_name']['imagen']) {

        //Subida de archivos
        $nombreImagen = md5(uniqid(rand(),true) ).".webp";

            $manager = new ImageManager(Driver::class);
            $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200,800); 
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