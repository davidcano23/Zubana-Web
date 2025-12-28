<?php 
    $auth = $_SESSION['login'] ?? null;
    $inicio = $inicio ?? false;
    $footer = $footer ?? false;
?>


<!DOCTYPE html>
<html lang="en">
    <?php
    $dominio = "https://" . $_SERVER['HTTP_HOST'];
    $url_actual = $dominio . $_SERVER['REQUEST_URI'];
    ?>

    <?php
    $imagen_principal = null;

    if (!empty($imagenes)) {
        $imagen_principal = $dominio . "/imagenes/" . $imagenes[0]->nombre;
    } else {
        $imagen_principal = $dominio . "/img/icono_pestanapng.png"; // fallback
    }
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($propiedad->titulo ?? 'Zubana BienRaíz'); ?></title>

    <meta name="description"
          content="<?php echo htmlspecialchars(substr($propiedad->descripcion ?? 'Encuentra propiedades únicas con Zubana BienRaíz', 0, 160)); ?>">

    <!-- Open Graph -->
    <meta property="og:title"
          content="<?php echo htmlspecialchars($propiedad->titulo ?? 'Zubana BienRaíz'); ?>">

    <meta property="og:description"
          content="<?php echo htmlspecialchars(substr($propiedad->descripcion ?? '', 0, 160)); ?>">

    <meta property="og:image"
          content="<?php echo $imagen_principal; ?>">

    <meta property="og:url"
          content="<?php echo $url_actual; ?>">

    <meta property="og:type" content="website">

    <link rel="icon" href="<?php echo $dominio; ?>/img/icono_pestanapng.png" type="image/png">
    <link rel="stylesheet" href="../build/css/app.css">
</head>

<body>
    <header class="header">
        <div class="superior">

        <div class="nombre-logo">
        <a href="/">
            <img src="/img/logo_ZB.png" alt="" class="logo-principal">
            <img src="/img/logo_header_horizontal.png" alt="" class="logo-secundario">
            
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
                        
                    <a href="/tipo-propiedad" class="admin_movil">
                        <img src="/img/admin_header.png" loading="lazy" alt="Imagen Admin">    
                        <p>Crear</p>
                    </a>

                    <a href="/logout" class="admin_movil">
                        <img src="/img/cerrar_sesion.png" loading="lazy" alt="Imagen Admin">    
                        <p>Salir</p>
                    </a>
                    
            <?php endif; ?>

            </div>

        </div>

        
            <?php if($inicio): ?>

                    <div class="linea-blanca"></div>

                    <div class="contenedor filtros_header">
                        
                        <form method="get" class="form_busqueda" autocomplete="off">

                            <div class="bloque_buscador_static">
                                <div class="filtros_computadora_header">
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
                                </div>
                                <div class="resultados_busqueda"></div>
                            </div>

                            <div class="filtros_scroller">

                                <div class="filtro_tipo">
                                    <?php
                                        $tiposValidos = ['casa','apartamento','casa campestre','finca','lote campestre','lote urbano','lote bodega','local','apartaestudio','apartaoficina'];
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
                                    $estratoGet = isset($_GET['estrato']) ? (int)$_GET['estrato'] : 0;
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
                                        </div>
                                        <div class="mas_actions">
                                            <button type="button" class="mas_clear">Limpiar filtros</button>
                                            <button type="button" class="mas_apply">Ver propiedades</button>
                                        </div>
                                        <input type="hidden" name="pagina" class="mas_pagina_hidden" value="1">
                                    </div>
                                </div>

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
            <img class="zf-brand__logo" src="/img/logo_ZB.png" alt="Z Bien Raíz" width="36" height="36" loading="lazy">
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
            <p class="zf-copy">© 2025 Z Bien Raíz</p>
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


    <script src="../build/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>