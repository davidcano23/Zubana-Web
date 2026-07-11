<?php
    $auth = $_SESSION['login'] ?? null;
    $inicio = $inicio ?? false;
    $footer = $footer ?? false;
    $contenido = $contenido ?? '';
    $propiedad = $propiedad ?? null;
    $imagenes  = $imagenes  ?? [];
?>



<!DOCTYPE html>
<html lang="en">
        <?php
    // Dominio + URL actual
    $dominio = "https://" . $_SERVER['HTTP_HOST'];
    $url_actual = $dominio . $_SERVER['REQUEST_URI'];
    
    // Título (usa nombre si existe)
    $metaTitle = !empty($propiedad->nombre)
        ? $propiedad->nombre . " | Zubana BienRaíz"
        : "Zubana BienRaíz";
    
    // Descripción limpia (sin HTML)
    $rawDesc = $propiedad->descripcion ?? "Encuentra propiedades únicas con Zubana BienRaíz";
    $rawDesc = trim(strip_tags($rawDesc));
    $metaDesc = mb_substr($rawDesc, 0, 200);
    
    // Imagen principal (prioridad: primera de $imagenes -> propiedad->imagen -> default)
    $imgRel = null;
    
    // Si en tu vista llega $imagenes como array
    if (isset($imagenes) && is_array($imagenes) && !empty($imagenes[0]->nombre)) {
        $imgRel = urlImagen($imagenes[0]->nombre);
    } elseif (!empty($propiedad->imagen)) {
        $imgRel = urlImagen($propiedad->imagen);
    } else {
        $imgRel = "/img/preview-default.jpg";
    }
    
    $metaImage = $dominio . $imgRel;
    $metaUrl   = $url_actual;
    ?>
    
    <?php
    $imagen_principal = null;

    if (!empty($imagenes)) {
        $imagen_principal = $dominio . urlImagen($imagenes[0]->nombre);
    } else {
        $imagen_principal = $dominio . "/img/icono_pestanapng.png"; // fallback
    }
    ?>

<?php
// Cargar configuración de apariencia del sitio
$_siteSettingsFile = __DIR__ . '/../includes/config/site_settings.json';
$_siteSettings = [
    'color_fondo'        => '#1C1C1E',
    'color_header'       => '#343434',
    'color_texto'        => '#F5F1EA',
    'color_acento'       => '#C1442E',
    'color_filtros'      => '#1C1C1E',
    'fuente_titulos'     => 'Playfair Display',
    'fuente_cuerpo'      => 'Lato',
    'fuente_titulos_url' => '',
    'fuente_cuerpo_url'  => '',
    'logo_principal'     => '',
    'logo_secundario'    => '',
];
if (file_exists($_siteSettingsFile)) {
    $_saved = json_decode(file_get_contents($_siteSettingsFile), true) ?? [];
    $_siteSettings = array_merge($_siteSettings, $_saved);
}

// Fuentes predefinidas que no vienen en el CSS compilado (Lato, Playfair Display, Roboto ya están)
$_fontsMap = [
    'Lora'               => 'Lora:ital,wght@0,400;0,700;1,400',
    'Merriweather'       => 'Merriweather:ital,wght@0,300;0,400;0,700;1,400',
    'Cormorant Garamond' => 'Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400',
    'Open Sans'          => 'Open+Sans:ital,wght@0,300;0,400;0,700;1,400',
    'Raleway'            => 'Raleway:ital,wght@0,300;0,400;0,700;1,400',
    'Nunito'             => 'Nunito:ital,wght@0,300;0,400;0,700;1,400',
    'Inter'              => 'Inter:wght@300;400;500;700',
    'Poppins'            => 'Poppins:wght@300;400;600;700',
    'Source Sans 3'      => 'Source+Sans+3:ital,wght@0,300;0,400;0,700;1,400',
    'DM Sans'            => 'DM+Sans:ital,wght@0,300;0,400;0,700;1,400',
    'Montserrat'         => 'Montserrat:ital,wght@0,300;0,400;0,700;1,400',
    'Outfit'             => 'Outfit:wght@300;400;600;700',
    'Figtree'            => 'Figtree:ital,wght@0,300;0,400;0,700;1,400',
    'Plus Jakarta Sans'  => 'Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,700;1,400',
    'Cinzel'             => 'Cinzel:wght@400;600;700',
    'Libre Baskerville'  => 'Libre+Baskerville:ital,wght@0,400;0,700;1,400',
    'DM Serif Display'   => 'DM+Serif+Display:ital,wght@0,400;1,400',
    'Abril Fatface'      => 'Abril+Fatface',
    'Josefin Sans'       => 'Josefin+Sans:ital,wght@0,300;0,400;0,700;1,400',
    'Bebas Neue'         => 'Bebas+Neue',
];
$_alreadyLoaded = ['Playfair Display', 'Roboto', 'Lato'];

// Logos dinámicos
$_logoPrincipal  = !empty($_siteSettings['logo_principal'])  ? $_siteSettings['logo_principal']  : '/img/logo_ZB.png';
$_logoSecundario = !empty($_siteSettings['logo_secundario']) ? $_siteSettings['logo_secundario'] : '/img/logo_header_horizontal.png';

// Links de fuentes extra (predefinidas o personalizadas via URL)
$_extraFonts = []; // slug => href

foreach (['fuente_titulos', 'fuente_cuerpo'] as $_fk) {
    $_f   = $_siteSettings[$_fk];
    $_url = $_siteSettings[$_fk . '_url'] ?? '';

    if ($_url && str_starts_with($_url, 'https://fonts.googleapis.com/')) {
        // Fuente personalizada con URL propia
        $_extraFonts['custom-' . $_fk] = htmlspecialchars($_url, ENT_QUOTES);
    } elseif (!in_array($_f, $_alreadyLoaded, true) && isset($_fontsMap[$_f])) {
        // Fuente predefinida no incluida en el CSS compilado
        $_slug = 'family=' . $_fontsMap[$_f];
        $_extraFonts[$_slug] = 'https://fonts.googleapis.com/css2?' . $_slug . '&display=swap';
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title><?= htmlspecialchars($metaTitle ?? 'Zubana BienRaíz') ?></title>

    <meta name="description" content="<?= htmlspecialchars($metaDesc ?? 'Encuentra propiedades únicas con Zubana BienRaíz') ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($metaUrl ?? '') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? 'Zubana BienRaíz') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? '') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($metaImage ?? '') ?>">
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($metaImage ?? '') ?>">

    <!-- Twitter (opcional pero recomendado) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle ?? 'Zubana BienRaíz') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDesc ?? '') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($metaImage ?? '') ?>">

    <link rel="icon" href="<?= htmlspecialchars(($dominio ?? '') . '/img/icono_pestanapng.png') ?>" type="image/png"> <!-- Icono Pestaña -->
    <link rel="stylesheet" href="/build/css/app.css">

    <?php foreach ($_extraFonts as $_fontHref): ?>
    <link rel="stylesheet" href="<?= $_fontHref ?>">
    <?php endforeach; ?>

    <style>
    :root {
        --color-fondo:   <?= htmlspecialchars($_siteSettings['color_fondo'],   ENT_QUOTES) ?>;
        --color-header:  <?= htmlspecialchars($_siteSettings['color_header'],  ENT_QUOTES) ?>;
        --color-texto:   <?= htmlspecialchars($_siteSettings['color_texto'],   ENT_QUOTES) ?>;
        --color-acento:  <?= htmlspecialchars($_siteSettings['color_acento'],  ENT_QUOTES) ?>;
        --color-filtros: <?= htmlspecialchars($_siteSettings['color_filtros'], ENT_QUOTES) ?>;
        --fuente-titulos: "<?= htmlspecialchars($_siteSettings['fuente_titulos'], ENT_QUOTES) ?>", serif;
        --fuente-cuerpo:  "<?= htmlspecialchars($_siteSettings['fuente_cuerpo'],  ENT_QUOTES) ?>", sans-serif;
    }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>


<body>
    <header class="header">
        <div class="superior">

        <div class="nombre-logo">
        <a href="/">
            <img src="<?= htmlspecialchars($_logoPrincipal,  ENT_QUOTES) ?>" alt="" class="logo-principal">
            <img src="<?= htmlspecialchars($_logoSecundario, ENT_QUOTES) ?>" alt="" class="logo-secundario">
            
        </a>
        </div>


            <div class="botones-login">

            <?php if(!$auth): ?>
                <button type="button" class="admin_movil js-open-login">
                    <div class="contenido-a">
                        <p>Ingresar</p>
                    </div>
                </button>
            <?php endif; ?>



            <?php if($auth):?>

                    <div class="admin-menu">
                        <button type="button" class="admin-menu__toggle js-admin-menu" aria-haspopup="true" aria-expanded="false">
                            <img src="/img/admin_header.png" loading="lazy" alt="Panel admin" width="18" height="18">
                            <span>Admin</span>
                            <svg class="admin-menu__chev" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                        </button>

                        <div class="admin-menu__panel" aria-hidden="true">
                            <a href="/tipo-propiedad" class="admin-menu__item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                Crear Propiedad
                            </a>
                            <a href="/" class="admin-menu__item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                                Administrar
                            </a>
                            <a href="/crm" class="admin-menu__item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                                CRM
                            </a>
                            <a href="/configuraciones" class="admin-menu__item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                                Configuraciones
                            </a>
                            <div class="admin-menu__divider"></div>
                            <a href="/logout" class="admin-menu__item admin-menu__item--danger">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Salir
                            </a>
                        </div>
                    </div>

            <?php endif; ?>

            </div>

        </div>

        
            <?php if($inicio): ?>

                    <div class="linea-blanca"></div>

                    <div class="contenedor filtros_header">
                        
                        <form method="get" class="form_busqueda" autocomplete="off">

                            <div class="bloque_buscador_static">
                                <div class="filtros_computadora_header">
                                    <div class="input_clear_wrap">
                                        <input 
                                        class="barra_por_ubicaciones" 
                                        type="text" 
                                        name="busqueda"
                                        placeholder="Buscar por ubicación o dirección"  
                                        value="<?php echo htmlspecialchars($_GET['busqueda'] ?? '', ENT_QUOTES); ?>"
                                        autocomplete="off"
                                        autocapitalize="off"
                                        spellcheck="false"
                                        inputmode="search"/>

                                        <button type="button" class="btn_clear_busqueda" aria-label="Limpiar búsqueda">
                                        ×
                                        </button>
                                    </div>
                                    </div>
                                <div class="resultados_busqueda"></div>
                            </div>

                            <div class="filtros_scroller">

                                <div class="filtro_tipo">
                                    <?php
                                        $tiposValidos = ['casa','apartamento','casa campestre','finca','lote campestre','lote urbano','lote bodega','lote urbanizable','local','apartaestudio','apartaoficina'];
                                        $seleccionados = isset($_GET['tipo']) && is_array($_GET['tipo']) ? $_GET['tipo'] : [];
                                        $hasTipos = !empty($seleccionados);
                                        $labelTipos = $hasTipos ? implode(', ', array_map('ucfirst', $seleccionados)) : 'Tipo de propiedad';
                                    ?>
                                    <button type="button" class="tipo_trigger" aria-haspopup="listbox" aria-expanded="false">
                                        <span class="tipo_trigger__text"><?= htmlspecialchars($labelTipos, ENT_QUOTES) ?></span>
                                        <span class="tipo_trigger__badge" <?= $hasTipos ? '' : 'style="display:none;"' ?>><?= count($seleccionados) ?></span>
                                        <svg class="tipo_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
                                    </button>

                                    <div class="tipo_panel" role="listbox" tabindex="-1" aria-label="Tipo de propiedad">
                                        <label class="tipo_opcion">
                                            <input type="checkbox" id="tipo_todas" <?= empty($_GET['tipo']) ? 'checked' : '' ?>>
                                            <span>Todos</span>
                                        </label>
                                        <?php foreach ($tiposValidos as $t): ?>
                                        <label class="tipo_opcion">
                                            <input type="checkbox" name="tipo[]" value="<?= htmlspecialchars($t, ENT_QUOTES); ?>" <?= in_array($t, $seleccionados, true) ? 'checked' : '' ?>>
                                            <span><?= ucfirst($t) ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                        <input type="hidden" name="pagina" id="pagina_hidden" value="1">
                                    </div>
                                </div>

                                <?php
                                    $precioMinGet = isset($_GET['precio_min']) ? (string)$_GET['precio_min'] : '';
                                    $precioMaxGet = isset($_GET['precio_max']) ? (string)$_GET['precio_max'] : '';
                                    $hasPrecio = ($precioMinGet !== '' || $precioMaxGet !== '');
                                    $labelPrecio = $hasPrecio ? (($precioMinGet !== '' ? '$'.$precioMinGet : '—') . ' — ' . ($precioMaxGet !== '' ? '$'.$precioMaxGet : '—')) : 'Precio';
                                ?>
                                <div class="filtro_precio">
                                    <button type="button" class="precio_trigger" aria-haspopup="dialog" aria-expanded="false">
                                        <span class="precio_trigger__text"><?= htmlspecialchars($labelPrecio, ENT_QUOTES) ?></span>
                                        <svg class="precio_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
                                    </button>
                                    <div class="precio_panel" role="dialog" aria-label="Rango de precio" tabindex="-1">
                                        <div class="precio_inputs">
                                            <label><span>Min $</span><input type="text" inputmode="numeric" pattern="[0-9.]*" name="precio_min" class="precio_min" value="<?= htmlspecialchars($precioMinGet, ENT_QUOTES) ?>"></label>
                                            <label><span>Max $</span><input type="text" inputmode="numeric" pattern="[0-9.]*" name="precio_max" class="precio_max" value="<?= htmlspecialchars($precioMaxGet, ENT_QUOTES) ?>"></label>
                                        </div>
                                        <div class="precio_acciones">
                                            <button type="button" class="precio_limpiar">Limpiar</button>
                                            <button type="button" class="precio_filtrar">Filtrar</button>
                                        </div>
                                        <input type="hidden" name="pagina" class="precio_pagina_hidden" value="1">
                                    </div>
                                </div>

                                <?php
                                    $habGet = isset($_GET['hab']) ? (int)$_GET['hab'] : 0;
                                    $banosGet = isset($_GET['banos']) ? (int)$_GET['banos'] : 0;
                                    $parts = [];
                                    if ($habGet > 0) $parts[] = 'Habs: ' . $habGet . (!empty($_GET['hab_exact']) ? '' : '+');
                                    if ($banosGet > 0) $parts[] = 'Baños: ' . $banosGet . (!empty($_GET['banos_exact']) ? '' : '+');
                                    $labelHB = $parts ? implode(', ', $parts) : 'Habs. y baños';
                                ?>
                                <div class="filtro_hb">
                                    <button type="button" class="hb_trigger" aria-haspopup="dialog" aria-expanded="false">
                                        <span class="hb_trigger__text"><?= htmlspecialchars($labelHB, ENT_QUOTES) ?></span>
                                        <svg class="hb_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
                                    </button>
                                    <div class="hb_panel" role="dialog" aria-label="Habitaciones y baños" tabindex="-1">
                                        <div class="hb_section">
                                            <p class="hb_title">Habitaciones</p>
                                            <div class="hb_group" data-kind="hab">
                                                <?php foreach ([0 => 'Todos', 1, 2, 3, 4, 5] as $val => $label): ?>
                                                <button type="button" class="hb_opt <?= ($habGet === (int)$val) ? 'active' : '' ?>" data-kind="hab" data-val="<?= (int)$val ?>"><?= $label ?></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <label class="hb_exact_label">
                                                <input type="checkbox" class="hb_exact" name="hab_exact" value="1" <?= !empty($_GET['hab_exact']) ? 'checked' : '' ?>>
                                                <span>Número exacto</span>
                                            </label>
                                            <input type="hidden" name="hab" class="hb_hidden_hab" value="<?= (int)$habGet ?>">
                                        </div>
                                        <hr class="hb_divider">
                                        <div class="hb_section">
                                            <p class="hb_title">Baños</p>
                                            <div class="hb_group" data-kind="banos">
                                                <?php foreach ([0 => 'Todos', 1, 2, 3, 4, 5] as $val => $label): ?>
                                                <button type="button" class="hb_opt <?= ($banosGet === (int)$val) ? 'active' : '' ?>" data-kind="banos" data-val="<?= (int)$val ?>"><?= $label ?></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <label class="hb_exact_label">
                                                <input type="checkbox" class="hb_exact" name="banos_exact" value="1" <?= !empty($_GET['banos_exact']) ? 'checked' : '' ?>>
                                                <span>Número exacto</span>
                                            </label>
                                            <input type="hidden" name="banos" class="hb_hidden_banos" value="<?= (int)$banosGet ?>">
                                        </div>
                                        <div class="hb_actions">
                                            <button type="button" class="hb_clear">Limpiar</button>
                                            <button type="button" class="hb_apply">Aplicar</button>
                                        </div>
                                        <input type="hidden" name="pagina" class="hb_pagina_hidden" value="1">
                                    </div>
                                </div>

                                <?php
                                    $estratoGet     = isset($_GET['estrato'])     ? (int)$_GET['estrato']           : 0;
                                    $tipoUnidadGet  = isset($_GET['tipo_unidad']) ? $_GET['tipo_unidad']            : 'Todos';
                                    $modalidadGet   = isset($_GET['modalidad'])   ? $_GET['modalidad']              : 'Todos';
                                    $garajeGet      = isset($_GET['garaje'])      ? $_GET['garaje']                 : 'Todos';
                                    $labelMas = $estratoGet > 0 ? "Más filtros (Estrato {$estratoGet})" : "Más filtros";
                                ?>
                                <div class="filtro_mas">
                                    <button type="button" class="mas_trigger" aria-haspopup="dialog" aria-expanded="false">
                                        <span class="mas_trigger__text"><?= htmlspecialchars($labelMas, ENT_QUOTES) ?></span>
                                        <svg class="mas_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"/></svg>
                                    </button>
                                    <div class="mas_overlay" hidden></div>
                                    <div class="mas_modal" role="dialog" aria-modal="true" aria-label="Más filtros" hidden>
                                        <div class="mas_head">
                                            <h3>Más Filtros</h3>
                                            <button type="button" class="mas_close" aria-label="Cerrar">✕</button>
                                        </div>
                                        <div class="mas_body">
                                            <section class="mf_section">
                                                <p class="mf_title">Estrato</p>
                                                <div class="mf_group" data-kind="estrato">
                                                    <?php foreach ([0 => 'Todos', 1,2,3,4,5,6] as $val => $label): ?>
                                                    <button type="button" class="mf_opt <?= ($estratoGet === (int)$val) ? 'active' : '' ?>" data-val="<?= (int)$val ?>"><?= $label ?></button>
                                                    <?php endforeach; ?>
                                                </div>
                                                <input type="hidden" name="estrato" class="mf_hidden_estrato" value="<?= (int)$estratoGet ?>">
                                            </section>

                                            <section class="mf_section">
                                            <p class="mf_title">Tipo de unidad</p>
                                            <div class="mf_group" data-kind="tipo_unidad">
                                                <button type="button" class="mf_opt <?= ($tipoUnidadGet === 'Todos') ? 'active' : '' ?>" data-val="Todos">Todos</button>
                                                <button type="button" class="mf_opt <?= ($tipoUnidadGet === 'Abierta') ? 'active' : '' ?>" data-val="Abierta">Abierta</button>
                                                <button type="button" class="mf_opt <?= ($tipoUnidadGet === 'Cerrada') ? 'active' : '' ?>" data-val="Cerrada">Cerrada</button>
                                                <button type="button" class="mf_opt <?= ($tipoUnidadGet === 'Independiente') ? 'active' : '' ?>" data-val="Independiente">Independiente</button>
                                            </div>
                                            <input type="hidden" name="tipo_unidad" class="mf_hidden_tipo_unidad" value="<?= htmlspecialchars($tipoUnidadGet ?? 'Todos', ENT_QUOTES) ?>">
                                            </section>

                                        <?php if($auth): ?>

                                            <section class="mf_section">
                                            <p class="mf_title">Modalidad</p>
                                            <div class="mf_group" data-kind="modalidad">
                                                <button type="button" class="mf_opt <?= ($modalidadGet === 'Todos') ? 'active' : '' ?>" data-val="Todos">Todos</button>
                                                <button type="button" class="mf_opt <?= ($modalidadGet === 'Directo') ? 'active' : '' ?>" data-val="Directo">Directo</button>
                                                <button type="button" class="mf_opt <?= ($modalidadGet === 'Colegaje') ? 'active' : '' ?>" data-val="Colegaje">Colegaje</button>
                                            </div>
                                            <input type="hidden" name="modalidad" class="mf_hidden_modalidad" value="<?= htmlspecialchars($modalidadGet ?? 'Todos', ENT_QUOTES) ?>">
                                            </section>

                                        <?php endif; ?>
                                            
                                            <section class="mf_section">
                                            <p class="mf_title">Garaje</p>
                                            <div class="mf_group" data-kind="garaje">
                                                <button type="button" class="mf_opt <?= ($garajeGet === 'Todos') ? 'active' : '' ?>" data-val="Todos">Todos</button>
                                                <button type="button" class="mf_opt <?= ($garajeGet === 'Si') ? 'active' : '' ?>" data-val="Si">Sí</button>
                                                <button type="button" class="mf_opt <?= ($garajeGet === 'No') ? 'active' : '' ?>" data-val="No">No</button>
                                            </div>
                                            <input type="hidden" name="garaje" class="mf_hidden_garaje" value="<?= htmlspecialchars($garajeGet ?? 'Todos', ENT_QUOTES) ?>">
                                            </section>

                                        </div>
                                        <div class="mas_actions">
                                            <button type="button" class="mas_clear">Limpiar filtros</button>
                                            <button type="button" class="mas_apply">Ver propiedades</button>
                                        </div>
                                        <input type="hidden" name="pagina" class="mas_pagina_hidden" value="1">
                                    </div>
                                </div>

                                <button 
                                type="button"
                                class="btn_clear_refresh"
                                aria-label="Limpiar filtros"
                                onclick="window.location.href = window.location.pathname"
                                >
                                <img src="/img/clear.png" alt="" class="img_clear">
                                </button>

                            </div> </form>
                    </div>
                <?php endif; ?>
    </header>

    <?php echo $contenido; ?>


    <div class="linea-blanca"></div>
        <footer class="zf-footer" role="contentinfo">
        <?php if($footer) { ?>

        <!-- CINTA SUPERIOR -->
        <div class="zf-footer__bar">
            <a class="zf-brand" href="/" aria-label="Inicio Z Bien Raíz">
            <img class="zf-brand__logo" src="<?= htmlspecialchars($_logoPrincipal, ENT_QUOTES) ?>" alt="Z Bien Raíz" width="36" height="36" loading="lazy">
            </a>
            <p class="zf-tagline">Conecta con tu casa, directo y fácil.</p>
            <a class="zf-cta" href="https://wa.me/573117856360" target="_blank" rel="noopener">WhatsApp</a>
        </div>

        <!-- GRID PRINCIPAL -->
        <div class="zf-footer__grid" aria-label="Información del sitio">
            <!-- Columna 1: Nosotros (details = acordeón en móvil) -->
            <details class="zf-col" open>
            <summary class="zf-col__title">Nosotros</summary>
            <p class="zf-col__text">
                Inmobiliaria especializada en el Oriente Antioqueño. Acompañamos tu compra o inversión con asesoría profesional.
            </p>
            </details>

            <!-- Columna 2: Contacto -->
            <details class="zf-col" open>
            <summary class="zf-col__title">Contáctanos</summary>
            <ul class="zf-list">
                <li class="zf-list__item">
                <svg class="zf-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.6 15.6 0 006.6 6.6l2.2-2.2a1.2 1.2 0 011.2-.3 13.1 13.1 0 004 0 1.2 1.2 0 011 .9l.8 3.7a1.2 1.2 0 01-1.1 1.4A19.6 19.6 0 012 4.7 1.2 1.2 0 013.4 3.6l3.7.8a1.2 1.2 0 01.9 1 13.1 13.1 0 000 4 1.2 1.2 0 01-.3 1.2z"/></svg>
                <a href="tel:+573117856360">+57 311 785 6360</a>
                </li>
                <li class="zf-list__item">
                <svg class="zf-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.6 15.6 0 006.6 6.6l2.2-2.2a1.2 1.2 0 011.2-.3 13.1 13.1 0 004 0 1.2 1.2 0 011 .9l.8 3.7a1.2 1.2 0 01-1.1 1.4A19.6 19.6 0 012 4.7 1.2 1.2 0 013.4 3.6l3.7.8a1.2 1.2 0 01.9 1 13.1 13.1 0 000 4 1.2 1.2 0 01-.3 1.2z"/></svg>
                <a href="tel:+573147919932">+57 314 791 9932</a>
                </li>
            </ul>
            </details>

            <!-- Columna 4: Redes + ubicación -->
            <details class="zf-col" open>
            <summary class="zf-col__title">Síguenos</summary>
            <div class="zf-social">
                <a href="https://instagram.com/zubanabienraiz" target="_blank" rel="noopener" aria-label="Instagram">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm5 5a5 5 0 100 10 5 5 0 000-10zm6.5.9a1.1 1.1 0 10-2.2 0 1.1 1.1 0 002.2 0z"/></svg>
                </a>
                <a href="https://facebook.com/zubanabienraiz" target="_blank" rel="noopener" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12a10 10 0 10-11.6 9.9v-7h-2.4V12h2.4V9.7c0-2.4 1.4-3.7 3.5-3.7 1 0 2 .2 2 .2v2.2h-1.1c-1.1 0-1.5.7-1.5 1.4V12h2.6l-.4 2.9h-2.2v7A10 10 0 0022 12z"/></svg>
                </a>
                <a href="https://tiktok.com/@zubanabienraiz" target="_blank" rel="noopener" aria-label="TikTok">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.5 3a5.6 5.6 0 004.1 4v3.2a8.7 8.7 0 01-4.1-1.1v6.2a6.3 6.3 0 11-6.3-6.3c.3 0 .5 0 .8.1v3.3a3 3 0 00-.8-.1 3 3 0 103 3V3h3.3z"/></svg>
                </a>
            </div>
            <address class="zf-address">
                Oriente Antioqueño, Colombia
            </address>
            </details>
        </div>


        <?php }; ?>

        <!-- FRANJA LEGAL -->
        <div class="zf-legal">
            <nav class="zf-legal__links" aria-label="Legal">
            <a href="/aviso-legal">Aviso Legal</a>
            <span aria-hidden="true">•</span>
            <a href="/politica-de-privacidad">Política de Privacidad</a>
            </nav>
            <p class="zf-copy">© 2025 Z Bien Raíz</p> <!-- Nombre de la empresa y año -->
        </div>
        </footer>



    <?php
        // leer “flash” de errores si los puso el controlador
        // session_start();
        $loginErrors = $_SESSION['login_errors'] ?? [];
        unset($_SESSION['login_errors']); // consumirlos una vez

        // fuerza abrir el modal si vienen errores o ?login=open
        $shouldOpenLogin = !empty($loginErrors) || (isset($_GET['login']) && $_GET['login'] === 'open');
        ?>
        <div class="login-overlay <?= $shouldOpenLogin ? 'is-open' : '' ?>" id="loginOverlay" hidden></div>

        <div class="login-modal <?= $shouldOpenLogin ? 'is-open' : '' ?>" id="loginModal" role="dialog" aria-modal="true" aria-labelledby="loginTitle" hidden>
        <button type="button" class="login-close" id="loginClose" aria-label="Cerrar">×</button>

        <div class="login-header">
            <img src="/img/logo_ZB.png" alt="Zubana BienRaíz" class="login-logo">
            <h3 id="loginTitle">Ingresar</h3>
            <p class="login-sub">Accede para continuar</p>
        </div>

        <?php if(!empty($loginErrors)): ?>
            <div class="login-errors">
            <?php foreach($loginErrors as $err): ?>
                <div class="alerta error"><?= htmlspecialchars($err, ENT_QUOTES) ?></div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="login-form" id="loginForm" novalidate>
            <div class="form-row">
            <label for="login_email">Correo electrónico</label>
            <input type="email" id="login_email" name="email" placeholder="Ingresa tu correo" required autocomplete="username">
            </div>

            <div class="form-row">
            <label for="login_password">Contraseña</label>
            <input type="password" id="login_password" name="password" placeholder="Ingresa tu contraseña" required autocomplete="current-password">
            </div>

            <!-- opcional: volver a la misma URL tras iniciar sesión -->
            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/', ENT_QUOTES) ?>">
            <div class="auth-errors" id="auth-errors" aria-live="polite"></div>

            <button type="submit" class="login-submit">Continuar</button>
        </form>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/heic-to@1.3.0/dist/iife/heic-to.js"></script>
    <script src="../build/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


</body>
</html>