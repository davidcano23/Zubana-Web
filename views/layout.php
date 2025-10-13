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

            <div class="contenedor filtros_header">
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
                </form>
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