<main class="contenedor panel-admin">

    <h3 class="titulo-panel">Panel Administrativo</h3>

    <?php
    if ($resultado) {
        $mensaje = mostrarNotificacion(intval($resultado)); 

            if($mensaje) {?>
                <p class="alerta exito"> <?php echo s($mensaje); ?>  </p>
   <?php }
            
        } ?>

    <div class="botones">

        <a class="boton-propiedad" href="/tipo-propiedad">Nueva Propiedad</a>

    </div>

    <div class="barra-busqueda">

        <div class="icono-tex">
            <!-- <img src="" alt=""> -->
            <p>Buscar Propiedad</p>
        </div>

        <div class="barra-ubicacion">

            <input id="codigo_filtro" type="text" name="codigo_filtro" placeholder="<?php echo $_GET['codigo_filtro'] ?? "Codigo de la propiedad..."; ?>" value="<?php echo $_GET['codigo_filtro'] ?? null;?>" >

                <!-- <select id="modalidad_filtros">
                        <option value="">Modalidad</option>
                        <option value="Colegaje">Colegaje</option>
                        <option value="Directo">Directo</option>
                </select> -->

                <select id="tipo" class="libreria-select">
                        <option value="">Selecciona un Tipo</option>
                        <option value="Casa">Casa</option>
                        <option value="Finca">Finca</option>
                        <option value="apartamento">Apartamento</option>
                        <option value="apartaestudio">Apartaestudio</option>
                        <option value="apartaoficina">Apartaoficina</option>
                        <option value="Local">Local</option>
<<<<<<< HEAD
                        <option value="Lote">Lote Campestre</option>
=======
                        <option value="Lote">Lote</option>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
                        <option value="Lote Urbanizable">Lote Urbanizable</option>
                        <option value="Lote Rural">Lote Rural</option>
                        <option value="Lote Bodega">Lote Bodega</option>
                </select>


                <input id="nombre_propietario" type="text" name="nombre_propietario" placeholder="<?php echo $_GET['nombre_propietario'] ?? "Nombre del Propietario..."; ?>" value="<?php echo $_GET['nombre_propietario'] ?? null;?>" >

                <input type="text" id="barrio" name="barrio" value="<?php echo htmlspecialchars($_GET['barrio'] ?? ''); ?>" placeholder="Ej: El Porvenir">
                
                <button class="button_admin" id="buscarBtn">Buscar</button>
        </div>

       
       


        
    </div>

    <table class="propiedades-tabla contenedor">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Propietario</th>
                    <th>Modalidad</th>
                    <th>Codigo</th>
                    <th>Imagen</th>
                    <th>Visitar</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <?php foreach($propiedades as $propiedad): ?>
            <tbody>
                

                <tr>
                    <th><?php echo $propiedad->{'id'};?></th>
                    <th> <?php echo $propiedad->{'propietario'}; ?> </th>
                    <th> <?php echo $propiedad->{'modalidad'}; ?> </th>
                    <th> <?php echo $propiedad->{'codigo'}; ?> </th>
                    <th><img src="../imagenes/<?php echo $propiedad->{'imagen'};?>" alt="Imagen Principal de la Propiedad" loading="lazy"></th>
                    <th>
                        <a class="boton-verpropiedad" href="/propiedad?id=<?php echo $propiedad->id; ?>&tipo=<?php echo strtolower($propiedad->tipo); ?>" class="boton boton-amarillo-block">Ver propiedad</a>
                    </th>
                    <th class="acciones">

                        <a class="boton-propiedad" href="/propiedades/actualizar-<?php echo trim($propiedad->actualizacion);?>?id=<?php echo $propiedad->id; ?>&tipo=<?php echo trim($propiedad->tipo); ?>">Actualizar</a>


                        <form method="POST" class="w-100" action="/propiedades/eliminar">
                            <input type="hidden" name="id" value="<?php echo $propiedad->{'id'}; ?>">
                            <input type="hidden" name="tipo" value="<?php echo $propiedad->{'tipo'}; ?>">
                            <input type="submit" class="boton-vendedor" value="Eliminar">
                        </form>

                    </th>
                </tr>

                
            </tbody>

            <?php endforeach; ?>
            
    </table>

    <!-- <table class="info-tabla contenedor">
            
    </table> -->

</main>