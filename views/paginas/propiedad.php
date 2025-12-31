<?php 
    $auth = $_SESSION['login'] ?? null;
?>

<main class="contenedor propiedad-info">

    <div class="padre">

        <div class="informacion-propiedad">

        <!-- <h2><?php echo $propiedad->{'nombre'}; ?></h2> -->

  <div class="swiper galeria-principal">
                <div class="swiper-wrapper">
                    <?php foreach($imagenes as $imagen): ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/<?php echo $imagen->nombre; ?>" alt="Imagen de la propiedad" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>

            <div class="swiper galeria-miniaturas">
                <div class="swiper-wrapper">
                    <?php foreach($imagenes as $imagen): ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/<?php echo $imagen->nombre; ?>" alt="Miniaturas de las imagenes de la propiedad" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="galeriaModal" class="galeria-modal oculto">
            <div class="galeria-contenido">
                <span class="cerrar-modal">&times;</span>

                <!-- Galería principal del modal -->
                <div class="swiper galeria-principal-modal">
                <div class="swiper-wrapper">
                    <?php foreach($imagenes as $imagen): ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/<?php echo $imagen->nombre; ?>" alt="Imagenes de la propiedad" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                </div>

                <!-- Miniaturas del modal -->
                <div class="swiper galeria-miniaturas-modal">
                <div class="swiper-wrapper">
                    <?php foreach($imagenes as $imagen): ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/<?php echo $imagen->nombre; ?>" alt="Miniaturas de las imagenes de la propiedad" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
                </div>
            </div>
            </div>



            <div class="precio">
                <h3>Valor $<?php echo number_format((int)str_replace('.', '', $propiedad->{'precio'}), 0, ',', '.'); ?></h3>
                
                <?php if($propiedad->{'administracion'} != 0): ?>
                <p> + $<?php echo number_format((int)str_replace('.', '', $propiedad->{'administracion'}), 0, ',', '.'); ?> Administracion</p>
                <?php endif; ?>

                <p class="ubi"><?php echo $propiedad->{'ubicacion'}; ?></p>
            </div>

        <!-- INFORMACION PARA CASAS Y FINCAS -->
        <?php if ($tipo === 'casa' || $tipo === 'casa campestre' || $tipo === 'finca') : ?>
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion de la propiedad</h4>

                <?php if($propiedad->area_total != 0): ?>
                <div class="carac">
                <img src="/img/area.png" alt="Icono Area" loading="lazy">
                <p>Área Total: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->area_construida != 0): ?>
                <div class="carac">
                <img src="/img/area_construida.png" alt="Icono Area Construida" loading="lazy">
                <p>Área Construida: <?php echo $propiedad->{'area_construida'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->banos != 0): ?>
                <div class="carac">
                <img src="/img/ducha.png" alt="Icono Ducha" loading="lazy">
                <p>Baños: <?php echo $propiedad->{'banos'}; ?></p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->habitaciones != 0): ?>
                <div class="carac">
                <img src="/img/dormitorio.png" alt="Icono dormitorio" loading="lazy">
                <p>Habitaciones: <?php echo $propiedad->{'habitaciones'}; ?></p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->estrato != 0): ?>
                <div class="carac">
                <img src="/img/estrato.png" alt="Icono Estrato" loading="lazy">
                <p>Estrato: <?php echo $propiedad->{'estrato'}; ?></p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_unidad.png" alt="Icono Tipo de Unidad" loading="lazy">
                <p>Tipo de Unidad: <?php echo $propiedad->{'tipo_unidad'}; ?></p>
                </div>

                <div class="carac">
                <img src="/img/tipo_propiedad.png" alt="Icono Tipo de Propiedad" loading="lazy">
                <p>Tipo de Propiedad: <?php echo $propiedad->{'tipo'}; ?></p>
                </div>

                <?php if (isset($propiedad->{'sala'}) && $propiedad->{'sala'} === 'Si') : ?>
                    <div class="carac">
                        <img src="/img/comedor.png" alt="Icono Comedor" loading="lazy">
                        <p>Sala Comedor</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'cocina'}) && $propiedad->{'cocina'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/cocina.png" alt="Icono Cocina" loading="lazy">
                <p>Cocina Integral</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'zona_ropa'}) && $propiedad->{'zona_ropa'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/zona_lavado.png" alt="Icono Zona Lavado" loading="lazy">
                <p>Zona de Ropa</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'garaje'}) && $propiedad->{'garaje'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/garaje.png" alt="Icono Garaje" loading="lazy">
                <p>Garaje</p>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <!-- INFORMACION PARA APARTAMENTOS -->
         <?php if ($tipo === 'apartamento' || $tipo === 'apartaestudio' || $tipo === 'apartaoficina') : ?>
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion de la propiedad</h4>

                <?php if($propiedad->area_total != 0): ?>
                <div class="carac">
                <img src="/img/area_construida.png" alt="Icono Area Construida" loading="lazy">
                <p>Área Construida: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_propiedad.png" alt="Icono Tipo de Propieda" loading="lazy">
                <p>Tipo de Propiedad: <?php echo $propiedad->{'tipo'}; ?></p>
                </div>
                
                <?php if($propiedad->banos != 0): ?>
                <div class="carac">
                <img src="/img/ducha.png" alt="Icono Ducha" loading="lazy">
                <p>Baños: <?php echo $propiedad->{'banos'}; ?></p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->habitaciones != 0): ?>
                <div class="carac">
                <img src="/img/dormitorio.png" alt="Icono Dormitorio" loading="lazy">
                <p>Habitaciones: <?php echo $propiedad->{'habitaciones'}; ?></p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->estrato != 0): ?>
                <div class="carac">
                <img src="/img/estrato.png" alt="Icono Estrato" loading="lazy">
                <p>Estrato: <?php echo $propiedad->{'estrato'}; ?></p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_unidad.png" alt="Icono Tipo de Unidad">
                <p>Tipo de Unidad: <?php echo $propiedad->{'tipo_unidad'}; ?></p>
                </div>

                <?php if (isset($propiedad->{'sala_comedor'}) && $propiedad->{'sala_comedor'} === 'Si') : ?>
                    <div class="carac">
                        <img src="/img/comedor.png" alt="Icono Comedor" loading="lazy">
                        <p>Sala Comedor</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'cocina'}) && $propiedad->{'cocina'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/cocina.png" alt="Icono Cocina" loading="lazy">
                <p>Cocina Integral</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'zona_ropa'}) && $propiedad->{'zona_ropa'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/zona_lavado.png" alt="Icono Zona Lavado" loading="lazy">
                <p>Zona de Ropa</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'garaje'}) && $propiedad->{'garaje'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/garaje.png" alt="Icono Garaje" loading="lazy">
                <p>Garaje</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'balcon'}) && $propiedad->{'balcon'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/balcon.png" alt="Icono Balcon">
                <p>Balcon</p>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <!-- INFORMACION PARA LOTES -->
         <?php if (in_array($tipo, ['lote campestre', 'lote urbano', 'lote bodega'])) : ?>
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion de la propiedad</h4>

                <?php if($propiedad->area_total != 0): ?>
                <div class="carac">
                <img src="/img/area.png" alt="Icono Area" loading="lazy">
                <p>Área Total: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_propiedad.png" alt="Icono Tipo de Propiedad" loading="lazy">
                <p>Tipo de Propiedad: <?php echo $propiedad->{'tipo'}; ?></p>
                </div>

                <?php if($propiedad->estrato != 0): ?>
                <div class="carac">
                <img src="/img/estrato.png" alt="Icono Estrato" loading="lazy">
                <p>Estrato: <?php echo $propiedad->{'estrato'}; ?></p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_unidad.png" alt="Icono Tipo de Unidad" loading="lazy">
                <p>Tipo de Unidad: <?php echo $propiedad->{'tipo_unidad'}; ?></p>
                </div>

            </div>
        <?php endif; ?>

        <!-- INFORMACION PARA LOCAL -->
         <?php if ($tipo === 'local') : ?>
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion de la propiedad</h4>

                <?php if($propiedad->area_total != 0): ?>
                <div class="carac">
                <img src="/img/area.png" alt="Icono Area" loading="lazy">
                <p>Área Total: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->area_construida != 0): ?>
                <div class="carac">
                <img src="/img/area_construida.png" alt="Icono Area Construida" loading="lazy">
                <p>Área Construida: <?php echo $propiedad->{'area_construida'}; ?>m²</p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->banos != 0): ?>
                <div class="carac">
                <img src="/img/ducha.png" alt="Icono Ducha" loading="lazy">
                <p>Baños: <?php echo $propiedad->{'banos'}; ?></p>
                </div>
                <?php endif; ?>

                <?php if($propiedad->estrato != 0): ?>
                <div class="carac">
                <img src="/img/estrato.png" alt="Icono Estrato" loading="lazy">
                <p>Estrato: <?php echo $propiedad->{'estrato'}; ?></p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/tipo_propiedad.png" alt="icono Tipo de Propiedad" loading="lazy">
                <p>Tipo de Propiedad: <?php echo $propiedad->{'tipo'}; ?></p>
                </div>

                <div class="carac">
                <img src="/img/tipo_local.png" alt="Icono Tipo de Local" loading="lazy">
                <p>Tipo de Local: <?php echo $propiedad->{'tipo_local'}; ?></p>
                </div>

            </div>
        <?php endif; ?>


        <?php if (in_array($tipo, ['casa', 'casa campestre', 'finca', 'apartamento', 'apartaestudio','apartaoficina'])) : ?>            
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion de la Urbanizacion</h4>

                <?php if (isset($propiedad->{'vigilancia'}) && $propiedad->{'vigilancia'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/vigilancia.png" alt="Icono Vigilancia" loading="lazy">
                <p>Vigilancia 24/7</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'zonas_verdes'}) && $propiedad->{'zonas_verdes'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/zona_verdes.png" alt="Icono Zonas Verdes" loading="lazy">
                <p>Zonas Verdes</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'juegos'}) && $propiedad->{'juegos'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/juegos.png" alt="Icono Juegos Infantiles" loading="lazy">
                <p>Juegos Infantiles</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'coworking'}) && $propiedad->{'coworking'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/coworking.png" alt="Icono Coworking" loading="lazy">
                <p>Coworking</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'gimnasio'}) && $propiedad->{'gimnasio'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/gimnasio.png" alt="Icono Gimnasio" loading="lazy">
                <p>Gimnasio</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'piscina'}) && $propiedad->{'piscina'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/piscina.png" alt="Icono Piscina" loading="lazy">
                <p>Piscina</p>
                </div>
                <?php endif; ?>

                <?php if (isset($propiedad->{'cancha'}) && $propiedad->{'cancha'} === 'Si') : ?>
                <div class="carac">
                <img src="/img/cancha.png" alt="Icono Canchas Deportivas" loading="lazy">
                <p>Canchas Deportivas</p>
                </div>
                <?php endif; ?>


            </div>
        <?php endif; ?>

            <div class="descripcion precio">
                <h4>Descripcion</h4>
                <p><?php echo $propiedad->{'descripcion'}; ?></p>
            </div>

            <div class="descripcion precio">
                <h4>Ubicación Aproximada</h4>
                
                <div id="mapa-radar" style="height: 400px; width: 100%; border-radius: 10px; z-index: 1;"></div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // 1. Capturamos latitud y longitud que vienen del Modelo
                        // Si son 0 o null, ponemos una ubicación por defecto para que no se rompa
                        var lat = <?php echo !empty($propiedad->latitud) ? $propiedad->latitud : 6.1551; ?>;
                        var lng = <?php echo !empty($propiedad->longitud) ? $propiedad->longitud : -75.3737; ?>;

                        // 2. Iniciamos el mapa (Leaflet ya debe estar cargado en el head)
                        var map = L.map('mapa-radar').setView([lat, lng], 14); // Zoom 14 es bueno para barrios

                        // 3. Capa visual (OpenStreetMap)
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap'
                        }).addTo(map);

                        // 4. EL RADAR (Círculo Azul estilo FincaRaíz)
                        // No ponemos un "Marker" (pin) exacto, sino un círculo
                        var circle = L.circle([lat, lng], {
                            color: '#b31200b6',       // Color del borde (azul oscuro)
                            fillColor: '#ff0000ff',   // Color de relleno (azul zubana)
                            fillOpacity: 0.2,       // Transparencia (para que se vean las calles abajo)
                            radius: 400             // 400 metros de radio (Zona aproximada)
                        }).addTo(map);

                        // Opcional: Desactivar scroll para que no moleste al bajar en la página
                        map.scrollWheelZoom.disable();
                    });
                </script>
            </div>
                    
            <?php
            $dominio = "https://" . $_SERVER['HTTP_HOST'];
            $url_actual = $dominio . $_SERVER['REQUEST_URI'];

            $mensaje  = "Hola, estoy interesado en esta propiedad:\n\n";
            $mensaje .= $url_actual . "\n\n";

            // Datos comunes
            if ($propiedad->area_total > 0)
                $mensaje .= "° Área Total: {$propiedad->area_total} m²\n";

            if (isset($propiedad->area_construida) && $propiedad->area_construida > 0)
                $mensaje .= "° Área Construida: {$propiedad->area_construida} m²\n";

            if (isset($propiedad->banos) && $propiedad->banos > 0)
                $mensaje .= "° Baños: {$propiedad->banos}\n";

            if (isset($propiedad->habitaciones) && $propiedad->habitaciones > 0)
                $mensaje .= "° Habitaciones: {$propiedad->habitaciones}\n";

            if (isset($propiedad->estrato) && $propiedad->estrato > 0)
                $mensaje .= "° Estrato: {$propiedad->estrato}\n";

            if (!empty($propiedad->tipo_unidad))
                $mensaje .= "° Tipo de Unidad: {$propiedad->tipo_unidad}\n";

            $mensaje .= "° Tipo de Propiedad: {$propiedad->tipo}\n";

            // Extras
            if (isset($propiedad->sala) && $propiedad->sala === 'Si')
                $mensaje .= "° Sala Comedor\n";

            if (isset($propiedad->cocina) && $propiedad->cocina === 'Si')
                $mensaje .= "° Cocina Integral\n";

            if (isset($propiedad->zona_ropa) && $propiedad->zona_ropa === 'Si')
                $mensaje .= "° Zona de Ropa\n";

            if (isset($propiedad->garaje) && $propiedad->garaje === 'Si')
                $mensaje .= "° Garaje\n";

            $mensaje_url = urlencode($mensaje);
            ?>


        <a href="https://wa.me/573117856360?text=<?php echo $mensaje_url; ?>" target="_blank" class="whatsapp-button">

            <img src="https://img.icons8.com/ios-filled/50/25D366/whatsapp.png" alt="WhatsApp">
            ¿Estás interesado en esta propiedad? ¡No dudes en contactarnos!
        </a>



            <!-- INFORMACION PRIVADA -->
         <?php if ($auth) : ?>
            <div class="caracteristicas-propiedad precio">
                <h4>Informacion Privada de la Propiedad</h4>

                <div class="carac">
                <img src="/img/propietario.png" alt="Icono Propietario" loading="lazy">
                <p>Nombre del Propietario: <?php echo $propiedad->{'propietario'}; ?></p>
                </div>

                <div class="carac">
                <img src="/img/direccion.png" alt="Icono Direccion" loading="lazy">
                <p>Direccion Exacta: <?php echo $propiedad->{'direccion'}; ?></p>
                </div>

                <?php if($propiedad->contacto != 0): ?>
                <div class="carac">
                <img src="/img/contacto.png" alt="Icono Contacto" loading="lazy">
                <p>Contacto del Propietario: <?php echo $propiedad->{'contacto'}; ?></p>
                </div>
                <?php endif; ?>

                <div class="carac">
                <img src="/img/modalidad.png" alt="Icono Modalidad" loading="lazy">
                <p>Modalidad: <?php echo $propiedad->{'modalidad'}; ?></p>
                </div>

                <div class="carac">
                <img src="/img/codigo.png" alt="Icono Codigo" loading="lazy">
                <p>Codigo: <?php echo $propiedad->{'id'}; ?></p>
                </div>

            </div>
        <?php endif; ?>

        </div>

        <div class="contenedor card-propiedades cardpropiedad">

            <h2>Propiedades Recomendadas</h2>
            
            <div class="listado-recomendadas">
                    <?php 
                        include 'listado.php';
                    ?>
            </div>
            

        </div>

    </div>


</main>