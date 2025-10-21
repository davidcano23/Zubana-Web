<?php foreach($propiedades as $propiedad){ ?>
    <!-- <a href="/propiedad?id=<?php echo $propiedad->id; ?>&tipo=<?php echo strtolower($propiedad->tipo); ?>" class="boton boton-amarillo-block"> -->
        <div class="card-propiedad">
            <div class="swiper img-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                                <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Imagen propiedad" loading="lazy">
                            </div>
                    <?php if (!empty($imagenesPorCasa[$propiedad->id])): ?>
                        <?php foreach($imagenesPorCasa[$propiedad->id] as $imagenNombre): ?>
                            <div class="swiper-slide">
                                <img src="/imagenes/<?php echo $imagenNombre; ?>" alt="Imagen propiedad" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="swiper-slide">
                            <img src="/imagenes/default.jpg" alt="Sin imagen">
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Flechas -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>

            <div class="contenedor_padre_computador_informacion_card">
            <h3>$<?php echo number_format((int)str_replace('.', '', $propiedad->{'precio'}), 0, ',', '.'); ?> COP</h3>


        <?php if ($propiedad->{'tipo'} === 'Casa' || $propiedad->{'tipo'} === 'Finca' || $propiedad->{'tipo'} === 'Apartamento' || $propiedad->{'tipo'} === 'Apartaestudio' || $propiedad->{'tipo'} === 'Apartaoficina') : ?>  
            <div class="carac">
                <?php if($propiedad->{'banos'} == 1): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $propiedad->{'banos'};?> Baño</p>
                </div>
                <?php else: ?>
                    <div class="contenedor_caracteristicas">
                    <img src="/img/inodoro.png" alt="">
                    <p><?php echo $propiedad->{'banos'};?> Baños</p>
                </div>
                <?php endif; ?>

                <div class="contenedor_caracteristicas">
                    <img src="/img/dormitorio.png" alt="">
                    <p><?php echo $propiedad->{'habitaciones'};?> Habs</p>
                </div>

                <?php if($propiedad->estrato != 0): ?>
                <div class="contenedor_caracteristicas">
                    <img src="/img/estrato.png" alt="">
                <p><?php echo $propiedad->{'estrato'};?> Estrato</p>
                </div>
                <?php endif; ?>

                <?php if (!empty($propiedad->area_total) || $propiedad->area_total != 0) { ?>
                    <div class="contenedor_caracteristicas">
                        <img src="/img/area.png" alt="">
                        <p><?php echo $propiedad->{'area_total'}; ?>m²</p>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>

        <?php if ($propiedad->{'tipo'} === 'Lote Campestre' || $propiedad->{'tipo'} === 'Lote Urbanizable' || $propiedad->{'tipo'} === 'Lote Rural' || $propiedad->{'tipo'} === 'Lote Bodega') : ?>  
            <div class="carac">
                <p>Estrato: <?php echo $propiedad->{'estrato'};?></p>
            </div>
        <?php endif; ?>

        <?php if ($propiedad->{'tipo'} === 'Local') : ?>  
            <div class="carac">
                <p>Baños: <?php echo $propiedad->{'banos'};?></p>
                <?php if($propiedad->estrato != 0): ?>
                <p>Estrato: <?php echo $propiedad->{'estrato'};?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

            <div class="metros-descripcion">

                

                <?php if (!empty($propiedad->area_total) || $propiedad->area_total != 0) { ?>
                    <p class="area_movil">Area: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                <?php } ?>


                <p class="texto_movil_informacion_tipo_ubicacion_barrio">Tipo de Propiedad: <?php echo $propiedad->{'tipo'};?></p>
                <p class="texto_computador_informacion_tipo_ubicacion_barrio"> <?php echo $propiedad->{'tipo'} . ' en ' . $propiedad->{'barrio'} . ', ' . $propiedad->{'ubicacion'}; ?> </p>
            </div>

            <div class="linea-blanca"></div>

            <div class="ubicacion">
                <p> <?php echo $propiedad->{'ubicacion'}; ?> </p>
                <?php if(!empty($propiedad->barrio)): ?>
                <p> <?php echo $propiedad->{'barrio'}; ?> </p>
                <?php endif; ?>
            </div>

        </div>
            
                
            <!-- </a> -->

        </div>

    <?php } ?>