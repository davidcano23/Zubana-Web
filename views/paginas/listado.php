<?php 
    $auth = $_SESSION['login'] ?? null;
?>

<?php foreach($propiedades as $recomendada){ ?>
    
        <div class="card-propiedad">
            <a href="/propiedad?id=<?php echo $recomendada->id; ?>&tipo=<?php echo strtolower($recomendada->tipo); ?>">
            <div class="swiper img-container">
            <div class="swiper-wrapper">
                
                <div class="swiper-slide">
                    <img src="/imagenes/<?php echo $recomendada->imagen; ?>" alt="Imagen propiedad" loading="lazy">
                </div>

                <?php 
                    // --- LÓGICA DE IDENTIFICACIÓN ---
                    // Determinamos qué tipo es para generar la llave correcta (ej: casa_20)
                    $tipo_clave = '';
                    $t = strtolower($recomendada->tipo);

                    if(in_array($t, ['casa', 'finca', 'casa campestre'])) {
                        $tipo_clave = 'casa';
                    } elseif(in_array($t, ['apartamento', 'apartaestudio', 'apartaoficina'])) {
                        $tipo_clave = 'apartamento';
                    } elseif(in_array($t, ['local'])) {
                        $tipo_clave = 'local';
                    } elseif(str_contains($t, 'lote')) { 
                        $tipo_clave = 'lote';
                    }

                    // Generamos la llave única
                    $llaveUnica = $tipo_clave . '_' . $recomendada->id;
                ?>

                <?php if (!empty($imagenesPorCasa[$llaveUnica])): ?>
                    <?php foreach($imagenesPorCasa[$llaveUnica] as $imagenNombre): ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/<?php echo $imagenNombre; ?>" alt="Imagen Extra" loading="lazy">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div> <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div> 

            <div class="contenedor_padre_computador_informacion_card">
            <h3>$<?php echo number_format((int)str_replace('.', '', $recomendada->{'precio'}), 0, ',', '.'); ?> </h3>
            
            <?php if($recomendada->{'administracion'} !=0): ?>
            <p class="texto_computador_informacion_tipo_ubicacion_barrio">+ $<?php echo number_format((int)str_replace('.', '', $recomendada->{'administracion'}), 0, ',', '.'); ?> Administracion </p>
            <?php endif; ?>

            <?php if(!$recomendada->{'barrio'} === 'N/A'): ?>
            <p class="texto_computador_informacion_tipo_ubicacion_barrio"> <?php echo $recomendada->{'tipo'} . ' en ' . $recomendada->{'barrio'} . ', ' . $recomendada->{'ubicacion'}; ?> </p>
            <?php else: ?>
                <p class="texto_computador_informacion_tipo_ubicacion_barrio"> <?php echo $recomendada->{'tipo'} . ' en ' . $recomendada->{'ubicacion'}; ?> </p>
            <?php endif; ?>


        <?php if ($recomendada->{'tipo'} === 'Casa' || $recomendada->{'tipo'} === 'Casa Campestre' || $recomendada->{'tipo'} === 'Finca' || $recomendada->{'tipo'} === 'Apartamento' || $recomendada->{'tipo'} === 'Apartaestudio' || $recomendada->{'tipo'} === 'Apartaoficina') : ?>  
            <div class="carac">
                <?php if($recomendada->{'banos'} != 0): ?>
                <?php if($recomendada->{'banos'} == 1): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $recomendada->{'banos'};?> Baño</p>
                </div>
                <?php else: ?>
                    <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $recomendada->{'banos'};?> Baños</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if($recomendada->{'habitaciones'} != 0): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/dormitorio.png" alt="">
                    <p><?php echo $recomendada->{'habitaciones'};?> Habs</p>
                </div>
                <?php endif; ?>

                <?php if($recomendada->estrato != 0): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/estrato.png" alt="">
                <p><?php echo $recomendada->{'estrato'};?> Estrato</p>
                </div>
                <?php endif; ?>

                <?php if (!empty($recomendada->area_total) || $recomendada->area_total != 0) { ?>
                    <div class="contenedor_caracteristicas area_computador_total">
                        <img src="/img/area.png" alt="">
                        <p><?php echo $recomendada->{'area_total'}; ?>m²</p>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>

        <?php if ($recomendada->{'tipo'} === 'Lote Campestre' || $recomendada->{'tipo'} === 'Lote Urbano' || $recomendada->{'tipo'} === 'Lote Bodega') : ?>  
            <div class="carac">
                <?php if($recomendada->estrato != 0): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/estrato.png" alt="">
                <p><?php echo $recomendada->{'estrato'};?> Estrato</p>
                </div>
                <?php endif; ?>

                <?php if($recomendada->{'area_total'} != 0): ?>
                <div class="contenedor_caracteristicas area_computador_total">
                        <img src="/img/area.png" alt="">
                        <p><?php echo $recomendada->{'area_total'}; ?>m²</p>
                </div>
                <?php endif; ?>
                
            </div>
        <?php endif; ?>

        <?php if ($recomendada->{'tipo'} === 'Local') : ?>  
            <div class="carac">

            <?php if($recomendada->{'banos'} != 0): ?>
                <?php if($recomendada->{'banos'} == 1): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $recomendada->{'banos'};?> Baño</p>
                </div>
                <?php else: ?>
                    <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $recomendada->{'banos'};?> Baños</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if($recomendada->estrato != 0): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/estrato.png" alt="">
                <p><?php echo $recomendada->{'estrato'};?> Estrato</p>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

            <div class="metros-descripcion">

                
                <?php if (!empty($recomendada->area_total) || $recomendada->area_total != 0) { ?>
                    <p class="area_movil">Area: <?php echo $recomendada->{'area_total'}; ?>m²</p>
                <?php } ?>
                
            </div>

            <div class="linea-blanca"></div>

            <div class="descripcion_computador_informacion_card">
                <h4 class="titulo_descripcion_computador">Informacion</h4>
                <p class="parrafo_descripcion_computador"> <?php echo $recomendada->{'descripcion'}; ?> </p>
            </div>

            <div class="botones_contacto_actualizacion_eliminar_propiedad">

            <?php
            $dominio = "https://" . $_SERVER['HTTP_HOST'];
            $url_actual = $dominio . $_SERVER['REQUEST_URI'];

            $mensaje  = "Hola, estoy interesado en esta propiedad:\n\n";
            $mensaje .= $url_actual . "\n\n";

            // Datos comunes
            if ($recomendada->area_total > 0)
                $mensaje .= "° Área Total: {$recomendada->area_total} m²\n";

            if (isset($recomendada->area_construida) && $recomendada->area_construida > 0)
                $mensaje .= "° Área Construida: {$recomendada->area_construida} m²\n";

            if (isset($recomendada->banos) && $recomendada->banos > 0)
                $mensaje .= "° Baños: {$recomendada->banos}\n";

            if (isset($recomendada->habitaciones) && $recomendada->habitaciones > 0)
                $mensaje .= "° Habitaciones: {$recomendada->habitaciones}\n";

            if (isset($recomendada->estrato) && $recomendada->estrato > 0)
                $mensaje .= "° Estrato: {$recomendada->estrato}\n";

            if (!empty($recomendada->tipo_unidad))
                $mensaje .= "° Tipo de Unidad: {$recomendada->tipo_unidad}\n";

            $mensaje .= "° Tipo de Propiedad: {$recomendada->tipo}\n";

            // Extras
            if (isset($recomendada->sala) && $recomendada->sala === 'Si')
                $mensaje .= "° Sala Comedor\n";

            if (isset($recomendada->cocina) && $recomendada->cocina === 'Si')
                $mensaje .= "° Cocina Integral\n";

            if (isset($recomendada->zona_ropa) && $recomendada->zona_ropa === 'Si')
                $mensaje .= "° Zona de Ropa\n";

            if (isset($recomendada->garaje) && $recomendada->garaje === 'Si')
                $mensaje .= "° Garaje\n";

            $mensaje_url = urlencode($mensaje);
            ?>

                <a href="https://wa.me/573117856360?text=<?php echo $mensaje_url; ?>" class="boton_whatsapp_card">
                    <img src="https://img.icons8.com/ios-filled/50/25D366/whatsapp.png" alt="WhatsApp">
                    Whatsapp
                </a>

                <?php if($auth): ?>

                <a class="boton_whatsapp_card" href="/propiedades/actualizar-<?php echo trim($recomendada->actualizacion);?>?id=<?php echo $recomendada->id; ?>&tipo=<?php echo trim($recomendada->tipo); ?>">
                    Actualizar
                </a>

                <form method="POST" class="w-100" action="/propiedades/eliminar">
                    <input type="hidden" name="id" value="<?php echo $recomendada->{'id'}; ?>">
                    <input type="hidden" name="tipo" value="<?php echo $recomendada->{'tipo'}; ?>">
                    <input type="submit" class="boton_whatsapp_card boton_eliminacion" value="Eliminar">
                </form>

                <?php endif; ?>
            </div>
                    </a>
        </div>
            
                
            

        </div>

    <?php } ?>