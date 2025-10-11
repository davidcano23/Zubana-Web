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
<<<<<<< HEAD
    <link rel="icon" href="/img/icono_pestanapng.png" type="image/png">
=======
    <link rel="icon" href="/img/icono_pestaña.jpg" type="image/jpg">
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189

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
<<<<<<< HEAD
            <img src="/img/logo_header_horizontal.png" alt="" class="logo-secundario">
            
=======
            <h1><span>Zubana </span> Bien Raiz</h1>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
        </a>
        </div>


            <div class="botones-login">

            <!-- <?php if(!$auth): ?>
            <a class="admin_movil" href="/login">
                <div class="contenido-a">
                    <img src="/img/admin_header.png" loading="lazy" alt="Imagen Admin">
                <p>Admin</p>
                </div>
            </a>
            <?php endif; ?> -->


            <?php if($auth):?>
                        
                    <a href="/admin" class="admin_movil">
                        <img src="/img/admin_header.png" loading="lazy" alt="Imagen Admin">    
                        <p>Admin</p>
                    </a>

                    <a href="/logout" class="admin_movil">
                        <img src="/img/cerrar_sesion.png" loading="lazy" alt="Imagen Admin">    
                        <p>Cerrar Sesión</p>
                    </a>
                    
            <?php endif; ?>

            </div>

        </div>

        

    <?php if($inicio): ?>

        <div class="linea-blanca"></div>

        <div class="filtros-busqueda contenedor">

            <div class="barra-ubicacion">
                <input id="buscador" name="ciudad" type="text" placeholder="<?php echo $_GET['ciudad'] ?? "Escribe una ciudad..."; ?>">
            </div>
            
            <div class="demas-filtros">

                <div class="filtros-trio">
<<<<<<< HEAD
                    <?php
                        $tiposSeleccionados = [];
                        if (!empty($_GET['tipo'])) {
                        $tiposSeleccionados = array_map('trim', explode(',', $_GET['tipo']));
                        }
                        $sel = fn($v) => in_array($v, $tiposSeleccionados) ? 'selected' : '';
                        ?>

                            <select id="tipo" class="libreria-select" multiple>
                            <option value="">Selecciona un Tipo</option>
                            <option value="Casa" <?= $sel('Casa') ?>>Casa</option>
                            <option value="Finca" <?= $sel('Finca') ?>>Finca</option>
                            <option value="Apartamento" <?= $sel('Apartamento') ?>>Apartamento</option>
                            <option value="Apartaestudio" <?= $sel('Apartaestudio') ?>>Apartaestudio</option>
                            <option value="Apartaoficina" <?= $sel('Apartaoficina') ?>>Apartaoficina</option>
                            <option value="Local" <?= $sel('Local') ?>>Local</option>
                            <option value="Lote Campestre" <?= $sel('Lote Campestre') ?>>Lote Campestre</option>
                            <option value="Lote Urbanizable" <?= $sel('Lote Urbanizable') ?>>Lote Urbanizable</option>
                            <option value="Lote Rural" <?= $sel('Lote Rural') ?>>Lote Rural</option>
                            <option value="Lote Bodega" <?= $sel('Lote Bodega') ?>>Lote Bodega</option>
                            </select>


=======
                    <select id="tipo" class="libreria-select">
                        <option value="">Selecciona un Tipo</option>
                        <option value="Casa">Casa</option>
                        <option value="Finca">Finca</option>
                        <option value="apartamento">Apartamento</option>
                        <option value="apartaestudio">Apartaestudio</option>
                        <option value="apartaoficina">Apartaoficina</option>
                        <option value="Local">Local</option>
                        <option value="Lote">Lote</option>
                        <option value="Lote Urbanizable">Lote Urbanizable</option>
                        <option value="Lote Rural">Lote Rural</option>
                        <option value="Lote Bodega">Lote Bodega</option>
                    </select>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
                    
                    <input 
                    type="text" 
                    id="precio_min" 
                    name="precio_min"
                    placeholder="Precio Mínimo" 
                    value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>"
                    >

                    <input 
                    type="text" 
                    id="precio_max" 
                    name="precio_max"
                    placeholder="Precio Máximo" 
                    value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>"
                    >

                </div>

            
            <button id="btnFiltros" class="btn-filtros">Más filtros</button>

                <div class="filtro-modal" id="modalFiltros">
                    <div class="filtro-contenido">
                        <span class="cerrar-modal" id="cerrarModal">&times;</span>
                        <h3>Filtros adicionales</h3>

<<<<<<< HEAD
                        <div class="filtros-trio-movil-tablet">

                            <label for="tipo_movil_tablet">Tipo Propiedad</label>
                            <select id="tipo_movil_tablet" class="libreria-select">
                                <option value="">Selecciona un Tipo</option>
                                <option value="Casa">Casa</option>
                                <option value="Finca">Finca</option>
                                <option value="apartamento">Apartamento</option>
                                <option value="apartaestudio">Apartaestudio</option>
                                <option value="apartaoficina">Apartaoficina</option>
                                <option value="Local">Local</option>
                                <option value="Lote">Lote</option>
                                <option value="Lote Urbanizable">Lote Urbanizable</option>
                                <option value="Lote Rural">Lote Rural</option>
                                <option value="Lote Bodega">Lote Bodega</option>
                            </select>

                        </div>

=======
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
                        <label for="barrio">Barrio</label>
                        <input type="text" id="barrio" name="barrio" value="<?php echo htmlspecialchars($_GET['barrio'] ?? ''); ?>" placeholder="Ej: El Porvenir">

                        <label for="banos">Baños</label>
                        <input type="number" id="banos" name="banos" value="<?php echo htmlspecialchars($_GET['banos'] ?? ''); ?>" placeholder="Ej: 2">

                        <label for="habitaciones">Habitaciones</label>
                        <input type="number" id="habitaciones" name="habitaciones" value="<?php echo htmlspecialchars($_GET['habitaciones'] ?? ''); ?>" placeholder="Ej: 3">

                        <label for="area_minima">Area Minima</label>
                        <input type="number" id="area_minima" name="area_minima" value="<?php echo htmlspecialchars($_GET['area_minima'] ?? ''); ?>" placeholder="Ej: 75">

                    <label for="modalidad_filtros">Modalidad</label>
                    <select id="modalidad_filtros">
                        <option value="">Tipo de Modalidad</option>
                        <option value="Colegaje">Colegaje</option>
                        <option value="Directo">Directo</option>
                    </select>

                    <label for="tipo_unidad_filtros">Unidad Residencial</label>
                    <select id="tipo_unidad_filtros">
                        <option value="">Tipo de Unidad</option>
                        <option value="Abierta">Abierta</option>
                        <option value="Cerrada">Cerrada</option>
                        <option value="Publica">Publica</option>
                    </select>

                    <div class="filtros-trio-movil-tablet">

<<<<<<< HEAD
                    
=======
                    <label for="tipo_movil_tablet">Tipo Propiedad</label>
                    <select id="tipo_movil_tablet" class="libreria-select">
                        <option value="">Selecciona un Tipo</option>
                        <option value="Casa">Casa</option>
                        <option value="Finca">Finca</option>
                        <option value="apartamento">Apartamento</option>
                        <option value="apartaestudio">Apartaestudio</option>
                        <option value="apartaoficina">Apartaoficina</option>
                        <option value="Local">Local</option>
                        <option value="Lote">Lote</option>
                        <option value="Lote Urbanizable">Lote Urbanizable</option>
                        <option value="Lote Rural">Lote Rural</option>
                        <option value="Lote Bodega">Lote Bodega</option>
                    </select>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
                    
                    <label for="precio_min">Precio Min</label>
                    <input 
                    type="number" 
                    id="precio_min" 
                    name="precio_min"
                    placeholder="Precio Mínimo" 
                    value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>"
                    >

                    <label for="precio_max">Precio Max</label>
                    <input 
                    type="number" 
                    id="precio_max" 
                    name="precio_max"
                    placeholder="Precio Máximo" 
                    value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>"
                    >

                </div>

                    <button id="guardarBtn" type="button" class="botonGuardar">Guardar</button>
                    </div>
                </div>

<<<<<<< HEAD
        <div class="opciones_resultados_filtros">
            <button id="limpiarFiltros">Limpiar</button>
            <button id="buscarBtn">Buscar</button>

        </div>
=======

            <button id="buscarBtn">Buscar</button>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
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

    <script src="../build/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>