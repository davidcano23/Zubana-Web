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
            $resultado = $_GET['Resultado'] ?? null;
            $inicio = true;
            $footer = true;

            // --- PAGINACIÓN ---
            $porPagina    = 33;
            $paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

            $link = conectarDB();

            // --- FILTROS BASE (tu código tal cual) ---
            $busqueda = $_GET['busqueda'] ?? null;
            if (is_array($busqueda)) $busqueda = implode(' ', $busqueda);

            $TIPOS_VALIDOS = [
                'casa','apartamento','casa campestre','finca',
                'lote campestre','lote urbano','lote bodega',
                'local','apartaestudio','apartaoficina'
            ];
            $tipos = $_GET['tipo'] ?? [];
            if (!is_array($tipos)) $tipos = [];
            $tipos = array_values(array_intersect($tipos, $TIPOS_VALIDOS));

            // precio
            $precioMin = $_GET['precio_min'] ?? '';
            $precioMax = $_GET['precio_max'] ?? '';
            $precioMinNum = $precioMin !== '' ? (int)preg_replace('/\D+/', '', $precioMin) : null;
            $precioMaxNum = $precioMax !== '' ? (int)preg_replace('/\D+/', '', $precioMax) : null;
            if (!is_null($precioMinNum) && !is_null($precioMaxNum) && $precioMinNum > $precioMaxNum) {
                [$precioMinNum, $precioMaxNum] = [$precioMaxNum, $precioMinNum];
            }

            $hab        = isset($_GET['hab']) ? (int)$_GET['hab'] : 0;
            $banos      = isset($_GET['banos']) ? (int)$_GET['banos'] : 0;
            $habExact   = !empty($_GET['hab_exact']);
            $banosExact = !empty($_GET['banos_exact']);

            $estrato = isset($_GET['estrato']) ? (int)$_GET['estrato'] : 0;

            // Áreas (si las usas en otros filtros)
            $areaTipo = $_GET['area_tipo'] ?? '';
            $areaMin  = isset($_GET['area_min']) ? (int)preg_replace('/\D+/', '', $_GET['area_min']) : null;
            $areaMax  = isset($_GET['area_max']) ? (int)preg_replace('/\D+/', '', $_GET['area_max']) : null;
            if (!is_null($areaMin) && !is_null($areaMax) && $areaMin > $areaMax) {
                [$areaMin, $areaMax] = [$areaMax, $areaMin];
            }

            // === ORDENAMIENTO (leer parámetro y validar) ===
            $ORDENES_VALIDOS = ['mayor_precio','menor_precio','mas_recientes','mayor_m2'];
            $ordenar = $_GET['ordenar'] ?? 'mas_recientes';
            if (!in_array($ordenar, $ORDENES_VALIDOS, true)) {
                $ordenar = 'mas_recientes';
            }

            // --- CONDICIONES COMUNES ---
            $condBase = [];
            if ($busqueda) {
                $safe = mysqli_real_escape_string($link, trim($busqueda));
                $condBase[] = "(ubicacion LIKE '%{$safe}%' OR barrio LIKE '%{$safe}%' OR corregimiento LIKE '%{$safe}%' OR palabra_clave LIKE '%{$safe}%')";
            }
            if (!empty($tipos)) {
                $tiposEsc = array_map(fn($t) => "'" . mysqli_real_escape_string($link, $t) . "'", $tipos);
                $condBase[] = "tipo IN (" . implode(',', $tiposEsc) . ")";
            }
            if (!is_null($precioMinNum)) $condBase[] = "precio >= {$precioMinNum}";
            if (!is_null($precioMaxNum)) $condBase[] = "precio <= {$precioMaxNum}";
            if ($estrato > 0)           $condBase[] = "estrato = {$estrato}";

            $buildWhere = static function(array $conds): string {
                return $conds ? 'WHERE ' . implode(' AND ', $conds) : '';
            };

            // --- INCLUSIÓN POR TABLA SEGÚN HABS/BAÑOS ---
            $includeCasa   = true;
            $includeApart  = true;
            $includeLocal  = true;
            $includeLote   = true;
            if ($hab > 0) { $includeLocal = false; $includeLote  = false; }
            if ($banos > 0) { $includeLote  = false; }

            // --- CONDICIONES POR TABLA ---
            $condCasa  = $condBase;
            $condApart = $condBase;
            $condLocal = $condBase;

            if ($hab > 0) {
                $condCasa[]  = $habExact   ? "habitaciones = {$hab}" : "habitaciones >= {$hab}";
                $condApart[] = $habExact   ? "habitaciones = {$hab}" : "habitaciones >= {$hab}";
            }
            if ($banos > 0) {
                $condCasa[]  = $banosExact ? "banos = {$banos}" : "banos >= {$banos}";
                $condApart[] = $banosExact ? "banos = {$banos}" : "banos >= {$banos}";
                $condLocal[] = $banosExact ? "banos = {$banos}" : "banos >= {$banos}";
            }

            $whereCasa  = $buildWhere($condCasa);
            $whereApart = $buildWhere($condApart);
            $whereLocal = $buildWhere($condLocal);
            $whereLotes = $buildWhere($condBase);

            // --- CONSULTAS POR TABLA (pueden venir ya ORDER BY id DESC, da igual porque re-ordenamos luego) ---
            $casas        = $includeCasa  ? Casa::consultarSQL("SELECT * FROM casa {$whereCasa} ORDER BY id DESC") : [];
            $apartamentos = $includeApart ? Apartamento::consultarSQL("SELECT * FROM apartamento {$whereApart} ORDER BY id DESC") : [];
            $locales      = $includeLocal ? Local::consultarSQL("SELECT * FROM local {$whereLocal} ORDER BY id DESC") : [];
            $lotes        = $includeLote  ? Lote::consultarSQL("SELECT * FROM lotes {$whereLotes} ORDER BY id DESC") : [];

            // --- COMBINAR Y ORDENAR (global, antes de paginar) ---
            $todas = array_merge($casas, $apartamentos, $locales, $lotes);

            // Usa tu función del ActiveRecord para ordenar (ver sección 2 de abajo)
            $todas = ModelActiveRecord::ordenarResultados($todas, $ordenar);

            // --- PAGINAR DESPUÉS DE ORDENAR (consistencia entre páginas) ---
            $totalPropiedades = count($todas);
            $totalPaginas     = max(1, (int)ceil($totalPropiedades / $porPagina));
            if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;
            $offset = ($paginaActual - 1) * $porPagina;
            $propiedades = array_slice($todas, $porPagina ? $offset : 0, $porPagina);
            $mostrandoDesde   = $totalPropiedades === 0 ? 0 : ($offset + 1);
            $mostrandoHasta   = $offset + count($propiedades); // ya respeta cuando hay menos de 33
            $masDeDisponibles = $totalPropiedades; // tu regla de "resta 1"

            // --- IMÁGENES (igual) ---
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

            // --- QUERY BASE PARA PAGINACIÓN (conservar filtros y 'ordenar') ---
            $params = $_GET;
            unset($params['pagina']);
            foreach ($params as $k => $v) if (is_array($v)) $params[$k] = array_values($v);
            $queryBase = http_build_query($params);
            $queryBase = $queryBase ? $queryBase . '&' : '';

            // --- RENDER ---
            $router->render('paginas/index', [
                'inicio'          => $inicio,
                'footer'          => $footer,
                'propiedades'     => $propiedades,
                'paginaActual'    => $paginaActual,
                'totalPaginas'    => $totalPaginas,
                'imagenesPorCasa' => $imagenesPorCasa,
                'busqueda'        => $busqueda,
                'queryBase'       => $queryBase,
                'resultado'       => $resultado,
                // por si lo quieres en la vista
                'ordenar'         => $ordenar,
                'totalPropiedades'  => $totalPropiedades,
                'mostrandoDesde'    => $mostrandoDesde,
                'mostrandoHasta'    => $mostrandoHasta,
                'masDeDisponibles'  => $masDeDisponibles,
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
    case 'casa campestre':
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
    case 'lote urbano':
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