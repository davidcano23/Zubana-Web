<?php foreach($propiedades as $propiedad){ ?>

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

            <h3>$<?php echo number_format((int)str_replace('.', '', $propiedad->{'precio'}), 0, ',', '.'); ?> COP</h3>


        <?php if ($propiedad->{'tipo'} === 'Casa' || $propiedad->{'tipo'} === 'Finca' || $propiedad->{'tipo'} === 'Apartamento' || $propiedad->{'tipo'} === 'Apartaestudio' || $propiedad->{'tipo'} === 'Apartaoficina') : ?>  
            <div class="carac">
                <p>Baños: <?php echo $propiedad->{'banos'};?></p>
                <p>Habitaciones: <?php echo $propiedad->{'habitaciones'};?></p>
                <?php if($propiedad->estrato != 0): ?>
                <p>Estrato: <?php echo $propiedad->{'estrato'};?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($propiedad->{'tipo'} === 'Lote' || $propiedad->{'tipo'} === 'Lote Urbanizable' || $propiedad->{'tipo'} === 'Lote Rural' || $propiedad->{'tipo'} === 'Lote Bodega') : ?>  
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
                    <p>Area: <?php echo $propiedad->{'area_total'}; ?>m²</p>
                <?php } ?>


                <p>Tipo de Propiedad: <?php echo $propiedad->{'tipo'};?></p>
            </div>

            <div class="linea-blanca"></div>

            <div class="ubicacion">
                <p> <?php echo $propiedad->{'ubicacion'}; ?> </p>
                <?php if(!empty($propiedad->barrio)): ?>
                <p> <?php echo $propiedad->{'barrio'}; ?> </p>
                <?php endif; ?>
            </div>

            <a href="/propiedad?id=<?php echo $propiedad->id; ?>&tipo=<?php echo strtolower($propiedad->tipo); ?>" class="boton boton-amarillo-block">
    Ver propiedad
</a>

        </div>

    <?php } ?>