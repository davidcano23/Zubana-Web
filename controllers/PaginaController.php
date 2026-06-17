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

    private static function construirMapaImagenes(): array {
        $mapa = [];
        foreach (ImagenCasa::todas() as $img) {
            $mapa['casa_' . $img->casa_id][] = $img->nombre;
        }
        foreach (ImagenApart::todas() as $img) {
            $mapa['apartamento_' . $img->apartamento_id][] = $img->nombre;
        }
        foreach (ImagenLocal::todas() as $img) {
            $mapa['local_' . $img->local_id][] = $img->nombre;
        }
        foreach (ImagenLotes::todas() as $img) {
            $mapa['lote_' . $img->lotes_id][] = $img->nombre;
        }
        return $mapa;
    }

    public static function index(Router $router): void {
        $resultado = $_GET['Resultado'] ?? null;
        $inicio    = true;
        $footer    = true;

        $porPagina    = 33;
        $paginaActual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

        $busqueda = $_GET['busqueda'] ?? null;
        if (is_array($busqueda)) $busqueda = implode(' ', $busqueda);

        $TIPOS_VALIDOS = [
            'casa','apartamento','casa campestre','finca',
            'lote campestre','lote urbano','lote bodega','lote urbanizable',
            'local','apartaestudio','apartaoficina'
        ];
        $tipos = $_GET['tipo'] ?? [];
        if (!is_array($tipos)) $tipos = [];
        $tipos = array_values(array_intersect($tipos, $TIPOS_VALIDOS));

        $precioMin    = $_GET['precio_min'] ?? '';
        $precioMax    = $_GET['precio_max'] ?? '';
        $precioMinNum = $precioMin !== '' ? (int)preg_replace('/\D+/', '', $precioMin) : null;
        $precioMaxNum = $precioMax !== '' ? (int)preg_replace('/\D+/', '', $precioMax) : null;
        if (!is_null($precioMinNum) && !is_null($precioMaxNum) && $precioMinNum > $precioMaxNum) {
            [$precioMinNum, $precioMaxNum] = [$precioMaxNum, $precioMinNum];
        }

        $hab        = isset($_GET['hab'])   ? (int)$_GET['hab']   : 0;
        $banos      = isset($_GET['banos']) ? (int)$_GET['banos'] : 0;
        $habExact   = !empty($_GET['hab_exact']);
        $banosExact = !empty($_GET['banos_exact']);
        $estrato    = isset($_GET['estrato']) ? (int)$_GET['estrato'] : 0;

        $MODALIDAD_VALIDOS  = ['Todos', 'Directo', 'Colegaje'];
        $modalidad = $_GET['modalidad'] ?? 'Todos';
        if (!in_array($modalidad, $MODALIDAD_VALIDOS, true)) $modalidad = 'Todos';

        $GARAJE_VALIDOS = ['Todos', 'Si', 'No'];
        $garaje = $_GET['garaje'] ?? 'Todos';
        if (!in_array($garaje, $GARAJE_VALIDOS, true)) $garaje = 'Todos';

        $TIPO_UNIDAD_VALIDOS = ['Todos', 'Abierta', 'Cerrada', 'Independiente'];
        $tipoUnidad = $_GET['tipo_unidad'] ?? 'Todos';
        if (!in_array($tipoUnidad, $TIPO_UNIDAD_VALIDOS, true)) $tipoUnidad = 'Todos';

        $areaTipo = $_GET['area_tipo'] ?? '';
        $areaMin  = isset($_GET['area_min']) ? (int)preg_replace('/\D+/', '', $_GET['area_min']) : null;
        $areaMax  = isset($_GET['area_max']) ? (int)preg_replace('/\D+/', '', $_GET['area_max']) : null;
        if (!is_null($areaMin) && !is_null($areaMax) && $areaMin > $areaMax) {
            [$areaMin, $areaMax] = [$areaMax, $areaMin];
        }

        $ORDENES_VALIDOS = ['mayor_precio','menor_precio','mas_recientes','mayor_m2'];
        $ordenar = $_GET['ordenar'] ?? 'mas_recientes';
        if (!in_array($ordenar, $ORDENES_VALIDOS, true)) $ordenar = 'mas_recientes';

        $condBase = [];
        if ($busqueda) {
            $safe = ModelActiveRecord::escapeString(trim($busqueda));
            $condBase[] = "(ubicacion LIKE '%{$safe}%' OR barrio LIKE '%{$safe}%' OR corregimiento LIKE '%{$safe}%' OR palabra_clave LIKE '%{$safe}%')";
        }
        if (!empty($tipos)) {
            $tiposEsc = array_map(fn($t) => "'" . ModelActiveRecord::escapeString($t) . "'", $tipos);
            $condBase[] = "tipo IN (" . implode(',', $tiposEsc) . ")";
        }
        if (!is_null($precioMinNum)) $condBase[] = "precio >= {$precioMinNum}";
        if (!is_null($precioMaxNum)) $condBase[] = "precio <= {$precioMaxNum}";
        if ($estrato > 0)            $condBase[] = "estrato = {$estrato}";

        if ($modalidad !== 'Todos') {
            $modalidadEsc = ModelActiveRecord::escapeString($modalidad);
            $condBase[] = "modalidad = '{$modalidadEsc}'";
        }

        $buildWhere = static fn(array $conds): string => $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

        $includeCasa  = true;
        $includeApart = true;
        $includeLocal = true;
        $includeLote  = true;
        if ($hab > 0)   { $includeLocal = false; $includeLote = false; }
        if ($banos > 0) { $includeLote  = false; }

        $condCasa  = $condBase;
        $condApart = $condBase;
        $condLocal = $condBase;
        $condLotes = $condBase;

        if ($tipoUnidad !== 'Todos') {
            $tipoUnidadEsc = ModelActiveRecord::escapeString($tipoUnidad);
            $condCasa[]  = "tipo_unidad = '{$tipoUnidadEsc}'";
            $condApart[] = "tipo_unidad = '{$tipoUnidadEsc}'";
            $condLotes[] = "tipo_unidad = '{$tipoUnidadEsc}'";
        }

        if ($garaje !== 'Todos') {
            $garajeEsc = ModelActiveRecord::escapeString($garaje);
            $condCasa[]  = "garaje = '{$garajeEsc}'";
            $condApart[] = "garaje = '{$garajeEsc}'";
        }

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
        $whereLotes = $buildWhere($condLotes);

        $casas        = $includeCasa  ? Casa::consultarSQL("SELECT * FROM casa {$whereCasa} ORDER BY id DESC")             : [];
        $apartamentos = $includeApart ? Apartamento::consultarSQL("SELECT * FROM apartamento {$whereApart} ORDER BY id DESC") : [];
        $locales      = $includeLocal ? Local::consultarSQL("SELECT * FROM local {$whereLocal} ORDER BY id DESC")          : [];
        $lotes        = $includeLote  ? Lote::consultarSQL("SELECT * FROM lotes {$whereLotes} ORDER BY id DESC")           : [];

        $todas = ModelActiveRecord::ordenarResultados(array_merge($casas, $apartamentos, $locales, $lotes), $ordenar);

        $totalPropiedades = count($todas);
        $totalPaginas     = max(1, (int)ceil($totalPropiedades / $porPagina));
        if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;
        $offset       = ($paginaActual - 1) * $porPagina;
        $propiedades  = array_slice($todas, $offset, $porPagina);
        $mostrandoDesde   = $totalPropiedades === 0 ? 0 : ($offset + 1);
        $mostrandoHasta   = $offset + count($propiedades);
        $masDeDisponibles = $totalPropiedades;

        $params = $_GET;
        unset($params['pagina']);
        foreach ($params as $k => $v) if (is_array($v)) $params[$k] = array_values($v);
        $queryBase = http_build_query($params);
        $queryBase = $queryBase ? $queryBase . '&' : '';

        $router->render('paginas/index', [
            'inicio'            => $inicio,
            'footer'            => $footer,
            'propiedades'       => $propiedades,
            'paginaActual'      => $paginaActual,
            'totalPaginas'      => $totalPaginas,
            'imagenesPorCasa'   => self::construirMapaImagenes(),
            'busqueda'          => $busqueda,
            'queryBase'         => $queryBase,
            'resultado'         => $resultado,
            'ordenar'           => $ordenar,
            'totalPropiedades'  => $totalPropiedades,
            'mostrandoDesde'    => $mostrandoDesde,
            'mostrandoHasta'    => $mostrandoHasta,
            'masDeDisponibles'  => $masDeDisponibles,
            'tipoUnidadGet'     => $tipoUnidad,
            'modalidadGet'      => $modalidad,
            'garajeGet'         => $garaje,
            'estratoGet'        => $estrato
        ]);
    }

    public static function propiedad(Router $router): void {
        $footer = true;

        $id   = $_GET['id']   ?? null;
        $tipo = $_GET['tipo'] ?? null;

        if (!$id || !$tipo) {
            header('Location: /');
            exit;
        }

        switch ($tipo) {
            case 'casa':
            case 'finca':
            case 'casa campestre':
                $propiedad   = Casa::find($id);
                $propiedades = Casa::getRecomendadas($propiedad->ubicacion, $id, 3);
                $imagenes    = ImagenCasa::where('casa_id', $id);
                break;

            case 'apartamento':
            case 'apartaestudio':
            case 'apartaoficina':
                $propiedad   = Apartamento::find($id);
                $propiedades = Apartamento::getRecomendadas($propiedad->ubicacion, $id, 3);
                $imagenes    = ImagenApart::where('apartamento_id', $id);
                break;

            case 'local':
                $propiedad   = Local::find($id);
                $propiedades = Local::getRecomendadas($propiedad->ubicacion, $id, 3);
                $imagenes    = ImagenLocal::where('local_id', $id);
                break;

            case 'lote campestre':
            case 'lote urbano':
            case 'lote bodega':
            case 'lote urbanizable':
                $propiedad   = Lote::find($id);
                $propiedades = Lote::getRecomendadas($propiedad->ubicacion, $id, 3);
                $imagenes    = ImagenLotes::where('lotes_id', $id);
                break;

            default:
                header('Location: /');
                exit;
        }

        if (!$propiedad) {
            header('Location: /');
            exit;
        }

        $router->render('paginas/propiedad', [
            'footer'          => $footer,
            'propiedad'       => $propiedad,
            'propiedades'     => $propiedades,
            'tipo'            => $tipo,
            'imagenes'        => $imagenes,
            'imagenesPorCasa' => self::construirMapaImagenes()
        ]);
    }
}
