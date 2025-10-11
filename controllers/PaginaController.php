<?php

namespace Controllers;

use Model\ActiveRecord as ModelActiveRecord;
use Model\Casa;
use Model\Apartamento;
use Model\ImagenApart;
use Model\ImagenCasa;
use Model\ImagenLocal;
use Model\ImagenLotes;
use Model\Local;
use Model\Lote;
use MVC\Router;
use PHPMailer\PHPMailer\PHPMailer;

class PaginaController {
    public static function index(Router $router) {
    $inicio = true;
    $footer = true;

    $filtros = $_GET ?? [];

<<<<<<< HEAD
    // Página actual
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $porPagina = 33; // 🔹 Mostramos 33 propiedades en total por página
    $offset = ($paginaActual - 1) * $porPagina;

    // Normalizar tipos múltiples
    if (!empty($filtros['tipo'])) {
        $tipos = explode(',', $filtros['tipo']);
        $filtros['tipos_array'] = array_map('trim', $tipos);
    } else {
        $filtros['tipos_array'] = [];
    }

    // Detectar si hay filtros activos
=======
    // Siempre definir la página actual
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $porPagina = 28; // Total combinado
    $porModelo = 7;  // Para repartir entre modelos

>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    $hayFiltros = !empty($filtros['ciudad']) 
        || !empty($filtros['tipo']) 
        || !empty($filtros['precio_min']) 
        || !empty($filtros['precio_max']) 
        || !empty($filtros['banos']) 
        || !empty($filtros['habitaciones'])
        || !empty($filtros['area_minima'])
        || !empty($filtros['modalidad_filtros'])
        || !empty($filtros['tipo_unidad_filtros'])
        || !empty($filtros['tipo_movil_tablet'])
        || !empty($filtros['barrio']);

<<<<<<< HEAD
    $criterioOrden = $_GET['ordenar'] ?? 'recientes';

    // Arrays de resultados
    $propiedades = [];

    if ($hayFiltros) {
        // --- FILTROS ACTIVOS ---
        $modelos = [
            'Casa' => Casa::class,
            'Apartamento' => Apartamento::class,
            'Local' => Local::class,
            'Lote' => Lote::class
        ];

        // Si hay tipos seleccionados, filtramos solo esos
        $tiposSeleccionados = !empty($filtros['tipos_array'])
            ? $filtros['tipos_array']
            : array_keys($modelos);

        foreach ($modelos as $nombre => $modelo) {
            if (in_array($nombre, $tiposSeleccionados)) {
                $propiedades = array_merge($propiedades, $modelo::filtrar($filtros));
            }
        }

        // Sin paginación cuando hay filtros
        $totalPropiedades = count($propiedades);
        $totalPaginas = 1;

    } else {
        // --- SIN FILTROS: paginación combinada global ---
        $casas = Casa::consultarSQL("SELECT * FROM casa ORDER BY id DESC");
        $apartamentos = Apartamento::consultarSQL("SELECT * FROM apartamento ORDER BY id DESC");
        $locales = Local::consultarSQL("SELECT * FROM local ORDER BY id DESC");
        $lotes = Lote::consultarSQL("SELECT * FROM lotes ORDER BY id DESC");

        // Mezclamos todos los modelos en un solo array
        $todas = array_merge($casas, $apartamentos, $locales, $lotes);

        // Ordenar globalmente por ID DESC (más recientes primero)
        usort($todas, fn($a, $b) => $b->id <=> $a->id);

        // Total propiedades
        $totalPropiedades = count($todas);
        $totalPaginas = ceil($totalPropiedades / $porPagina);

        // Cortamos el bloque correspondiente a la página actual
        $propiedades = array_slice($todas, $offset, $porPagina);
    }

    // --- IMÁGENES RELACIONADAS ---
    $casaImg = ImagenCasa::todas();
    $apartaImg = ImagenApart::todas();
    $localImg = ImagenLocal::todas();
    $loteImg = ImagenLotes::todas();

    $imagenesTodas = array_merge($casaImg, $apartaImg, $localImg, $loteImg);
    $imagenesPorCasa = [];

    foreach ($imagenesTodas as $imagen) {
        $id = $imagen->getPropiedadId();
        $imagenesPorCasa[$id][] = $imagen->nombre;
    }

    // Renderizar vista
=======
    $casas = [];
    $apartamentos = [];
    $locales = [];
    $lotes = [];

    $criterioOrden = $_GET['ordenar'] ?? null;

    if ($hayFiltros) {
        // Filtros aplicados
        $usaBanos = !empty($filtros['banos']);
        $usaHabitaciones = !empty($filtros['habitaciones']);
        $usaTipoUnidad = !empty($filtros['tipo_unidad_filtros']);

        if ($usaBanos || $usaHabitaciones) {
            $casas = Casa::filtrar($filtros);
            $apartamentos = Apartamento::filtrar($filtros);
            if (!$usaHabitaciones) {
                $locales = Local::filtrar($filtros);
            }
        } elseif ($usaTipoUnidad) {
            $casas = Casa::filtrar($filtros);
            $apartamentos = Apartamento::filtrar($filtros);
            $lotes = Lote::filtrar($filtros);
        } else {
            $casas = Casa::filtrar($filtros);
            $apartamentos = Apartamento::filtrar($filtros);
            $locales = Local::filtrar($filtros);
            $lotes = Lote::filtrar($filtros);
        }

        // Puedes contar cuántas hay con filtros si quieres paginar también resultados filtrados
        $totalPropiedades = count($casas) + count($apartamentos) + count($locales) + count($lotes);
        $totalPaginas = 1; // Puedes omitir o poner 1 si no estás paginando los filtrados

    } else {
        // SIN filtros, sí hacemos paginación y ordenamiento
        
        $offset = ($paginaActual - 1) * $porModelo;

        $casas = Casa::getPaginadas($porModelo, $offset, $criterioOrden);
        $apartamentos = Apartamento::getPaginadas($porModelo, $offset, $criterioOrden);
        $locales = Local::getPaginadas($porModelo, $offset, $criterioOrden);
        $lotes = Lote::getPaginadas($porModelo, $offset, $criterioOrden);
        

        $totalCasas = Casa::contar();
        $totalApartamentos = Apartamento::contar();
        $totalLocales = Local::contar();
        $totalLotes = Lote::contar();

        $totalPropiedades = $totalCasas + $totalApartamentos + $totalLocales + $totalLotes;
        $totalPaginas = ceil($totalPropiedades / $porPagina);
    }


        // AHORA sí hacemos merge aquí 👇
        $propiedades = array_merge($casas, $apartamentos, $locales, $lotes);     
        $propiedades = ModelActiveRecord::ordenarResultados($propiedades, $criterioOrden);
   

        $casaImg = ImagenCasa::todas();
        $apartaImg = ImagenApart::todas();
        $localImg = ImagenLocal::todas();
        $loteImg = ImagenLotes::todas();

        $imagenesTodas = array_merge($casaImg, $apartaImg, $localImg, $loteImg);
        $imagenesPorCasa = [];

        foreach ($imagenesTodas as $imagen) {
            $id = $imagen->getPropiedadId();  // Aquí se llama de forma genérica
            $imagenesPorCasa[$id][] = $imagen->nombre;
        }





>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    $router->render('paginas/index', [
        'inicio' => $inicio,
        'footer' => $footer,
        'propiedades' => $propiedades,
        'paginaActual' => $paginaActual,
        'totalPaginas' => $totalPaginas,
        'imagenesPorCasa' => $imagenesPorCasa
    ]);
}




<<<<<<< HEAD

=======
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    public static function propiedad(Router $router) {
        $footer = true;

        $id = $_GET['id'] ?? null;
        $tipo = $_GET['tipo'] ?? null;

    if (!$id || !$tipo) {
        header('Location: /');
        exit;
    }

    switch ($tipo) {
    case 'casa':
    case 'finca':
        $propiedad = Casa::find($id);
        $propiedades = Casa::getRecomendadas($propiedad->{'ubicacion'}, $id, 3);
        $imagenes = ImagenCasa::where('casa_id', $id);
        break;

    case 'apartamento':
    case 'apartaestudio':
    case 'apartaoficina':
        $propiedad = Apartamento::find($id);
        $propiedades = Apartamento::getRecomendadas($propiedad->{'ubicacion'}, $id, 3);
        $imagenes = ImagenApart::where('apartamento_id', $id);
        break;

    case 'local':
        $propiedad = Local::find($id);
        $propiedades = Local::getRecomendadas($propiedad->{'ubicacion'}, $id, 3);
        $imagenes = ImagenLocal::where('local_id', $id);
        break;

<<<<<<< HEAD
    case 'lote campestre':
=======
    case 'lote':
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
    case 'lote urbanizable':
    case 'lote rural':
    case 'lote bodega':
        $propiedad = Lote::find($id);
        $propiedades = Lote::getRecomendadas($propiedad->{'ubicacion'}, $id, 3);
        $imagenes = ImagenLotes::where('lotes_id', $id);
        break;
        default:
            header('Location: /');
            exit;
    }

    if (!$propiedad) {
        header('Location: /');
        exit;
    }

    $casaImg = ImagenCasa::todas();
        $apartaImg = ImagenApart::todas();
        $localImg = ImagenLocal::todas();
        $loteImg = ImagenLotes::todas();

        $imagenesTodas = array_merge($casaImg, $apartaImg, $localImg, $loteImg);
        $imagenesPorCasa = [];

        foreach ($imagenesTodas as $imagen) {
            $id = $imagen->getPropiedadId();  // Aquí se llama de forma genérica
            $imagenesPorCasa[$id][] = $imagen->nombre;
        }



        $router->render('paginas/propiedad', [
            'footer' => $footer,
            'propiedad' => $propiedad,
            'propiedades' => $propiedades,
            'tipo' => $tipo,
            'imagenes' => $imagenes,
            'imagenesPorCasa' => $imagenesPorCasa

        ]);
    }

    }