<?php 
    $auth = $_SESSION['login'] ?? null;
    $inicio = $inicio ?? false;
    $footer = $footer ?? false;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zubana BienRaiz</title>
    <link rel="stylesheet" href="../build/css/app.css">

    <!-- CSS de Choices -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- JS de Choices -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGYd7mmhUfywh_3txsmLhg81OcjLqu3so&libraries=places"></script>
    

    <!-- Favicon (ícono de pestaña) -->
    <link rel="icon" href="/img/icono_pestanapng.png" type="image/png">


    <!-- Meta descripción (SEO y vista previa en Google) -->
    <meta name="description" content="Zubana BienRaíz - Encuentra casas, apartamentos y propiedades únicas en el Oriente Antioqueño.">

    <!-- (Opcional) Meta para redes sociales -->
    <meta property="og:title" content="Zubana BienRaíz">
    <meta property="og:description" content="Encuentra casas y apartamentos en el Oriente Antioqueño con Zubana BienRaíz.">
    <meta property="og:image" content="/img/logo_ZB">
    <meta property="og:type" content="website">


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
                <div class="filtros_scroller">
                <form method="get" class="form_busqueda" autocomplete="off">

                    <div class="filtros_computadora_header">
                        <input 
                        class="barra_por_ubicaciones" 
                        type="text" 
                        name="busqueda"
                        placeholder="Buscar por ubicacion o direccion"  
                        value="<?php echo htmlspecialchars($_GET['busqueda'] ?? '', ENT_QUOTES); ?>"
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="false"
                        inputmode="search"/>
                    </div>
                    <div class="resultados_busqueda"></div>



                    <!-- TIPOS DE PROPIEDAD (multiselección) -->
                    <div class="filtro_tipo">
                    <!-- Botón/trigger que muestra el estado actual -->
                    <?php
                        $tiposValidos = [
                        'casa','apartamento','casa campestre','finca',
                        'lote campestre','lote urbano','lote bodega',
                        'local','apartaestudio','apartaoficina'
                        ];
                        $seleccionados = isset($_GET['tipo']) && is_array($_GET['tipo']) ? $_GET['tipo'] : [];
                        $hasTipos = !empty($seleccionados);
                        $labelTipos = $hasTipos ? implode(', ', array_map('ucfirst', $seleccionados)) : 'Tipo de propiedad';
                    ?>
                    <button type="button" class="tipo_trigger" aria-haspopup="listbox" aria-expanded="false">
                        <span class="tipo_trigger__text"><?= htmlspecialchars($labelTipos, ENT_QUOTES) ?></span>
                        <span class="tipo_trigger__badge" <?= $hasTipos ? '' : 'style="display:none;"' ?>>
                        <?= count($seleccionados) ?>
                        </span>
                        <svg class="tipo_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>

                    <!-- Panel desplegable con las opciones -->
                    <div class="tipo_panel" role="listbox" tabindex="-1" aria-label="Tipo de propiedad">
                        <label class="tipo_opcion">
                        <input type="checkbox" id="tipo_todas" <?= empty($_GET['tipo']) ? 'checked' : '' ?>>
                        <span>Todos</span>
                        </label>

                        <?php foreach ($tiposValidos as $t): ?>
                        <label class="tipo_opcion">
                            <input
                            type="checkbox"
                            name="tipo[]"
                            value="<?= htmlspecialchars($t, ENT_QUOTES); ?>"
                            <?= in_array($t, $seleccionados, true) ? 'checked' : '' ?>
                            >
                            <span><?= ucfirst($t) ?></span>
                        </label>
                        <?php endforeach; ?>

                        <!-- Para resetear paginación a 1 al cambiar -->
                        <input type="hidden" name="pagina" id="pagina_hidden" value="1">
                    </div>
                    </div>


                    <?php
                        // valores actuales (si vienen por GET, para mantenerlos)
                        $precioMinGet = isset($_GET['precio_min']) ? (string)$_GET['precio_min'] : '';
                        $precioMaxGet = isset($_GET['precio_max']) ? (string)$_GET['precio_max'] : '';

                        // Texto del trigger (si hay rango activo)
                        $hasPrecio = ($precioMinGet !== '' || $precioMaxGet !== '');
                        $labelPrecio = 'Precio';
                        if ($hasPrecio) {
                            $pmin = $precioMinGet !== '' ? '$' . $precioMinGet : '—';
                            $pmax = $precioMaxGet !== '' ? '$' . $precioMaxGet : '—';
                            $labelPrecio = "$pmin — $pmax";
                        }
                        ?>

                        <!-- FILTRO DE PRECIO -->
                        <div class="filtro_precio">
                        <button type="button" class="precio_trigger" aria-haspopup="dialog" aria-expanded="false">
                            <span class="precio_trigger__text"><?= htmlspecialchars($labelPrecio, ENT_QUOTES) ?></span>
                            <svg class="precio_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div class="precio_panel" role="dialog" aria-label="Rango de precio" tabindex="-1">
                            <div class="precio_inputs">
                            <label>
                                <span>Min $</span>
                                <input type="text" inputmode="numeric" pattern="[0-9.]*" name="precio_min" class="precio_min"
                                    value="<?= htmlspecialchars($precioMinGet, ENT_QUOTES) ?>">
                            </label>

                            <label>
                                <span>Max $</span>
                                <input type="text" inputmode="numeric" pattern="[0-9.]*" name="precio_max" class="precio_max"
                                    value="<?= htmlspecialchars($precioMaxGet, ENT_QUOTES) ?>">
                            </label>
                            </div>

                            <div class="precio_acciones">
                            <button type="button" class="precio_limpiar">Limpiar</button>
                            <button type="button" class="precio_filtrar">Filtrar</button>
                            </div>

                            <!-- Reiniciar paginación al aplicar -->
                            <input type="hidden" name="pagina" class="precio_pagina_hidden" value="1">
                        </div>
                        </div>


                        <?php
                            // valores actuales desde GET
                            $habGet        = isset($_GET['hab']) ? (int)$_GET['hab'] : 0;
                            $banosGet      = isset($_GET['banos']) ? (int)$_GET['banos'] : 0;
                            $habExactGet   = !empty($_GET['hab_exact']);   // 1 => exacto
                            $banosExactGet = !empty($_GET['banos_exact']);

                            // texto del trigger (resumen)
                            $parts = [];
                            if ($habGet > 0)   $parts[]   = 'Habs: ' . $habGet . ($habExactGet ? '' : '+');
                            if ($banosGet > 0) $parts[]   = 'Baños: ' . $banosGet . ($banosExactGet ? '' : '+');
                            $labelHB = $parts ? implode(', ', $parts) : 'Habs. y baños';
                            ?>

                            <!-- FILTRO HABS & BAÑOS -->
                            <div class="filtro_hb">
                            <button type="button" class="hb_trigger" aria-haspopup="dialog" aria-expanded="false">
                                <span class="hb_trigger__text"><?= htmlspecialchars($labelHB, ENT_QUOTES) ?></span>
                                <svg class="hb_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </button>

                            <div class="hb_panel" role="dialog" aria-label="Habitaciones y baños" tabindex="-1">
                                <!-- HABITACIONES -->
                                <div class="hb_section">
                                <p class="hb_title">Habitaciones</p>
                                <div class="hb_group" data-kind="hab">
                                    <?php
                                    $habOptions = [0 => 'Todos', 1, 2, 3, 4, 5]; // 5 = 5 o más (si no es exacto)
                                    foreach ($habOptions as $val => $label):
                                        $isActive = ($habGet === (int)$val);
                                    ?>
                                    <button type="button"
                                            class="hb_opt <?= $isActive ? 'active' : '' ?>"
                                            data-kind="hab"
                                            data-val="<?= (int)$val ?>">
                                        <?= is_numeric($label) ? $label : $label ?>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <label class="hb_exact_label">
                                    <input type="checkbox" class="hb_exact" name="hab_exact" value="1" <?= $habExactGet ? 'checked' : '' ?>>
                                    <span>Número exacto de habitaciones</span>
                                </label>

                                <!-- inputs ocultos que viajan por GET -->
                                <input type="hidden" name="hab" class="hb_hidden_hab" value="<?= (int)$habGet ?>">
                                </div>

                                <hr class="hb_divider">

                                <!-- BAÑOS -->
                                <div class="hb_section">
                                <p class="hb_title">Baños</p>
                                <div class="hb_group" data-kind="banos">
                                    <?php
                                    $banosOptions = [0 => 'Todos', 1, 2, 3, 4, 5];
                                    foreach ($banosOptions as $val => $label):
                                        $isActive = ($banosGet === (int)$val);
                                    ?>
                                    <button type="button"
                                            class="hb_opt <?= $isActive ? 'active' : '' ?>"
                                            data-kind="banos"
                                            data-val="<?= (int)$val ?>">
                                        <?= is_numeric($label) ? $label : $label ?>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                                <label class="hb_exact_label">
                                    <input type="checkbox" class="hb_exact" name="banos_exact" value="1" <?= $banosExactGet ? 'checked' : '' ?>>
                                    <span>Número exacto de baños</span>
                                </label>

                                <input type="hidden" name="banos" class="hb_hidden_banos" value="<?= (int)$banosGet ?>">
                                </div>

                                <div class="hb_actions">
                                <button type="button" class="hb_clear">Limpiar</button>
                                <button type="button" class="hb_apply">Aplicar</button>
                                </div>

                                <!-- reset paginación -->
                                <input type="hidden" name="pagina" class="hb_pagina_hidden" value="1">
                            </div>
                            </div>

                            <?php
                            $estratoGet = isset($_GET['estrato']) ? (int)$_GET['estrato'] : 0; // 0 = Todos
                            $labelMas = $estratoGet > 0 ? "Más filtros (Estrato {$estratoGet})" : "Más filtros";
                            ?>

                            <?php
                                // Lee estado actual desde GET (para hidratar el modal)
                                $areaTipoGet = $_GET['area_tipo'] ?? ''; // '' | 'privada' | 'construida'
                                $areaMinGet  = isset($_GET['area_min']) ? (int)preg_replace('/\D+/', '', $_GET['area_min']) : '';
                                $areaMaxGet  = isset($_GET['area_max']) ? (int)preg_replace('/\D+/', '', $_GET['area_max']) : '';

                                $isPrivada     = ($areaTipoGet === 'privada');
                                $isConstruida  = ($areaTipoGet === 'construida');
                                ?>
                            <!-- MÁS FILTROS (trigger + modal) -->
                            <div class="filtro_mas">
                            <button type="button" class="mas_trigger" aria-haspopup="dialog" aria-expanded="false">
                                <span class="mas_trigger__text"><?= htmlspecialchars($labelMas, ENT_QUOTES) ?></span>
                                <svg class="mas_trigger__chev" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </button>

                            <!-- Overlay + Modal -->
                            <div class="mas_overlay" hidden></div>

                            <div class="mas_modal" role="dialog" aria-modal="true" aria-label="Más filtros" hidden>
                                <div class="mas_head">
                                <h3>Más Filtros</h3>
                                <button type="button" class="mas_close" aria-label="Cerrar">✕</button>
                                </div>

                                <div class="mas_body">

                                <!-- Estrato -->
                                <section class="mf_section">
                                    <p class="mf_title">Estrato</p>
                                    <div class="mf_group" data-kind="estrato">
                                    <?php
                                        // 0 = Todos, 1..6
                                        $estratos = [0 => 'Todos', 1,2,3,4,5,6];
                                        foreach ($estratos as $val => $label):
                                        $isActive = ($estratoGet === (int)$val);
                                    ?>
                                        <button type="button"
                                                class="mf_opt <?= $isActive ? 'active' : '' ?>"
                                                data-val="<?= (int)$val ?>">
                                        <?= is_numeric($label) ? $label : $label ?>
                                        </button>
                                    <?php endforeach; ?>
                                    </div>

                                    <!-- input oculto que viaja por GET -->
                                    <input type="hidden" name="estrato" class="mf_hidden_estrato" value="<?= (int)$estratoGet ?>">
                                </section>
                                            

                                </div>

                                <div class="mas_actions">
                                <button type="button" class="mas_clear">Limpiar filtros</button>
                                <button type="button" class="mas_apply">Ver propiedades</button>
                                </div>

                                <!-- reset paginación al aplicar -->
                                <input type="hidden" name="pagina" class="mas_pagina_hidden" value="1">
                            </div>
                        </div>

                </form>
                </div>
            </div>

    <?php endif; ?>
    </header>

    <?php echo $contenido; ?>


    <div class="linea-blanca"></div>
    <footer class="footer">

        <?php if($footer) { ?>

        <div class="contenedo">
            <nav class="navegacion">
                <div class="propiedades-venta">
                        <div class="card-footer">
                            <img src="/img/logo_ZB.png" alt="Logo de la Empresa" loading="lazy">
                            <p class="parrafo_img">"Conecta con tu casa, directo y fácil."</p>
                        </div>


                        <div class="card-footer">
                            <p>Nosotros</p>
                            <p class="nosotros_text">"Inmobiliaria especializada en la venta de propiedades en el Oriente Antioqueño. Brindamos acompañamiento profesional para encontrar la mejor inversión o el hogar ideal."</p>
                            
                        </div>
                        <div class="card-footer contacto_card_footer">
                            <p>Contáctanos</p>
                                <div class="sub_hijo_card_footer">
                                    <img src="/img/whatsapp_Contacto.webp" alt="Logo de Contacto" loading="lazy">
                                    <p class="parrafo_footer">57+ 311 785 6360</p>
                                </div>

                                <div class="sub_hijo_card_footer">
                                    <img src="/img/contacto.png" alt="Logo de Contacto" loading="lazy">
                                    <p class="parrafo_footer">57+ 314 791 9932</p>
                                </div>
                        </div>
                </div>

                <div class="card-footer">
                    <p class="titulo-redes">Redes Sociales</p>

                    <div class="redes-footer">
                        <img src="/img/Instagram_Contacto.webp" alt="Icono Instagram" loading="lazy">
                        <p>@zubanabienraiz</p>
                    </div>
                    <div class="redes-footer">
                        <img src="/img/Facebook_Contacto.webp" alt="Icono Facebook" loading="lazy">
                        <p>@zubanabienraiz</p>
                    </div>
                    <div class="redes-footer">
                        <img src="/img/Tiktok_Contacto.webp" alt="Icono Tik Tok" loading="lazy">
                        <p>@zubanabienraiz</p>
                    </div>

                </div>
            </nav>
        </div>

        <?php if(!$auth): ?>
            <a class="admin_movil" href="/login">
                <div class="contenido-a">
                <p>Admin</p>
                </div>
            </a>
            <?php endif; ?>

        <?php }; ?>

        <p class="copyright">© 2025 Z Bien Raíz. Todos los derechos reservados. | Aviso Legal | Política de Privacidad | Sitio web &copy;</p>
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