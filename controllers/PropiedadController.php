<?php

namespace Controllers;

use MVC\Router;
use Model\Casa;
use Model\Apartamento;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Model\ImagenCasa;
use Model\Local;
use Model\Lote;

class PropiedadController {

    public static function admin(Router $router) {
    $resultado = $_GET['Resultado'] ?? null;

        $filtros = $_GET ?? [];

        $hayFiltros =   !empty($filtros['codigo_filtro'])
                        || !empty($filtros['modalidad_filtros'])
                        || !empty($filtros['nombre_propietario'])
                        || !empty($filtros['tipo'])
                        || !empty($filtros['barrio']);

        if($hayFiltros) {
            $casas = Casa::filtrar($filtros);
            $apartamentos = Apartamento::filtrar($filtros);
            $local = Local::filtrar($filtros);
            $lotes = Lote::filtrar($filtros);
        } else {
            // Consultar cada tipo de propiedad
            $casas = Casa::get(5);
            $apartamentos = Apartamento::get(5);
            $lotes = Lote::get(5);
            $local = Local::get(5);
        }

    // Unir todas en un solo array
    $propiedades = array_merge($casas, $apartamentos,$lotes,$local);

    // Renderizar vista
    $router->render('propiedades/admin', [
        'propiedades' => $propiedades,
        'resultado' => $resultado
    ]);
}

    public static function tipoPropiedad(Router $router) {
        // Renderizar vista
    $router->render('propiedades/tipo-propiedad', [
        
    ]);
    }

    public static function crearCasa(Router $router) {
    $propiedad = new Casa();

    // Arreglo mensaje de errores
    $errores = Casa::getErrores();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $_POST['propiedad'];

            // Convertir precio con puntos a número entero
            if (isset($datos['precio'])) {
                $datos['precio'] = intval(str_replace('.', '', $datos['precio']));
            }

            $propiedad = new Casa($datos);

        // Generar un nombre único para la imagen
        $nombreImagen = md5(uniqid(rand(), true)) . ".webp";

        if($_FILES['propiedad']['tmp_name']['imagen']) {
            $manager = new ImageManager(Driver::class);
            $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(1200,800); 
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

                        // Procesar imagen
                        $imagenAdicional = $manager->read($tmpName)->cover(1200,800);

                        // Guardarla en el servidor
                        $imagenAdicional->save(CARPETA_IMAGENES . $nombreImagenAdicional);

                        // Guardarla en la DB
                        $imagenExtra = new ImagenCasa([
                            'casa_id' => $idPropiedad,
                            'nombre' => $nombreImagenAdicional
                        ]);
                        $imagenExtra->guardar();
                    }
                }
            }
        }

    }

    // Renderizar vista
    $router->render('crear/crear-casa', [
        'propiedad' => $propiedad,
        'errores' => $errores
    ]);
}

    public static function actualizarCasa(Router $router) {

            $propiedad = validarORedireccion('/admin');
            $id = validarORedireccion('/admin');
            $propiedad = Casa::find($id);

        //Arreglo mensaje de errores
                $errores = Casa::getErrores();

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


        // Renderizar vista
        $router->render('crear/actualizar-casa', [
            'propiedad' => $propiedad,
            'errores' => $errores
        ]);
        }



    public static function eliminar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        $tipo = trim($_POST['tipo']); // 👈 Limpia espacios

        $mapaModelos = [
            'Casa' => 'Casa',
            'Apartamento' => 'Apartamento',
            'Finca' => 'Casa',
            'Lote Rural' => 'Lote',
            'Lote Bodega' => 'Lote',
            'Lote Urbanizable' => 'Lote',
            'Local' => 'Local',
            'Apartaestudio' => 'Apartamento',
            'Apartaoficina' => 'Apartamento',
            'Oficina' => 'Oficina'
        ];

        if ($id && isset($mapaModelos[$tipo])) {
            $claseModelo = "Model\\" . $mapaModelos[$tipo];

            if (class_exists($claseModelo)) {
                $propiedad = $claseModelo::find($id);
                if ($propiedad) {
                    $propiedad->eliminar();
                }
            }
        }
    }
    } //Fin Eliminar



}