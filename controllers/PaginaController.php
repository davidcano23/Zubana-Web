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

        // --- PAGINACIÓN ---
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 33;
        $offset = ($paginaActual - 1) * $porPagina;

        $link = conectarDB();

        // --- FILTROS ---
        $busqueda = $_GET['busqueda'] ?? null;
        if (is_array($busqueda)) {
            // Elige: a) quedarte con el primero, b) unirlos, c) vaciar
            $busqueda = implode(' ', $busqueda); // o $busqueda = reset($busqueda);
        }
        $tipos = $_GET['tipo'] ?? [];

        // 2) lista blanca para evitar inyecciones
        $TIPOS_VALIDOS = [
        'casa','apartamento','casa campestre','finca',
        'lote campestre','lote urbano','lote bodega',
        'local','apartaestudio','apartaoficina'
        ];

        // Normaliza a array y cruza con la whitelist
        if (!is_array($tipos)) $tipos = [];
        $tipos = array_values(array_intersect($tipos, $TIPOS_VALIDOS));

        // 3) Construimos condiciones dinámicamente (independientes y combinables)
        $condiciones = [];

        // --- CONSULTAS BASE ---
        if ($busqueda) {
        $safe = mysqli_real_escape_string($link, trim($busqueda));
        $condiciones[] = "(ubicacion LIKE '%$safe%' OR barrio LIKE '%$safe%')";
        }

        if (!empty($tipos)) {
        $tiposEsc = array_map(fn($t) => "'" . mysqli_real_escape_string($link, $t) . "'", $tipos);
        $condiciones[] = "tipo IN (" . implode(',', $tiposEsc) . ")";
        }

        $where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';

        // --- CONSULTAS POR TABLA ---
        $casas = Casa::consultarSQL("SELECT * FROM casa $where ORDER BY id DESC");
        $apartamentos = Apartamento::consultarSQL("SELECT * FROM apartamento $where ORDER BY id DESC");
        $locales = Local::consultarSQL("SELECT * FROM local $where ORDER BY id DESC");
        $lotes = Lote::consultarSQL("SELECT * FROM lotes $where ORDER BY id DESC");

        // --- COMBINAR Y PAGINAR ---
        $todas = array_merge($casas, $apartamentos, $locales, $lotes);
        usort($todas, fn($a, $b) => $b->id <=> $a->id);

        $totalPropiedades = count($todas);
        $totalPaginas = ceil($totalPropiedades / $porPagina);
        if ($totalPaginas > 0 && $paginaActual > $totalPaginas) {
            $paginaActual = $totalPaginas;
        }

        $propiedades = array_slice($todas, $offset, $porPagina);

        // --- IMÁGENES ---
        $imagenesTodas = array_merge(
            ImagenCasa::todas(),
            ImagenApart::todas(),
            ImagenLocal::todas(),
            ImagenLotes::todas()
        );

        $imagenesPorCasa = [];
        foreach ($imagenesTodas as $imagen) {
            $id = $imagen->getPropiedadId();
            $imagenesPorCasa[$id][] = $imagen->nombre;
        }

        // --- Construir la base de la query ---
        $params = $_GET;

        // siempre quita pagina para reconstruirla en los links
        unset($params['pagina']);

        $queryBase = http_build_query($params);
        $queryBase = $queryBase ? $queryBase . '&' : '';

        // --- RENDERIZAR ---
        $router->render('paginas/index', [
            'inicio' => $inicio,
            'footer' => $footer,
            'propiedades' => $propiedades,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'imagenesPorCasa' => $imagenesPorCasa,
            'busqueda' => $busqueda, // 👈 importante para conservarla en la vista
            'queryBase' => $queryBase
        ]);
    }




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


    case 'lote campestre':
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