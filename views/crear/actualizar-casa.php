<?php
    $actualizar = false;
?>

<main class="contenedor crear">

    <h1>Actualizar Casa</h1>

    <?php foreach($errores as $error): ?>
            
        <div class="alerta error">

            <?php echo $error; ?>

    </div> <?php endforeach; ?>

    <a href="/tipo-propiedad" class="bt-regresar">Regresar</a>

    <form action="" class="formulario" method="POST" enctype="multipart/form-data">

        <?php include __DIR__ . '/formulario.php'; ?>

    <fieldset>
    <legend>Tipo de Propiedad</legend>

        <label for="tipo-propiedad">Tipo de Propiedad*</label>
        <select id="tipo-propiedad" class="select-estilizado" name="propiedad[tipo]" required>
            <option value="" disabled <?php echo empty($propiedad?->tipo) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Casa" <?php echo ($propiedad?->tipo === 'Casa') ? 'selected' : ''; ?> >Casa</option>
            <option value="Casa Campestre" <?php echo ($propiedad?->tipo === 'Casa Campestre') ? 'selected' : ''; ?> >Casa Campestre</option>
        </select>
        <input type="hidden" name="propiedad[actualizacion]" value="casa">


    </fieldset>

    <fieldset>
        <legend>Información Casa</legend>

        <label for="area_total">Área Total</label>
        <input type="text" placeholder="En m2 el valor" id="area_total" class="areas" name="propiedad[area_total]" value="<?php echo trim(s($propiedad?->area_total ?? '')); ?>">

        <label for="area_construida">Área Construida</label>
        <input type="text" placeholder="En m2 el valor" id="area_construida" class="areac" name="propiedad[area_construida]" value="<?php echo trim(s($propiedad?->area_construida ?? '')); ?>">

        <label for="wc">Baños</label>
        <input type="number" placeholder="Ej: 5 - Solo el Valor" id="wc" name="propiedad[banos]" value="<?php echo is_numeric($propiedad?->banos) ? $propiedad->banos : ''; ?>">

        <label for="habitaciones">Habitaciones</label>
        <input type="number" placeholder="Ej: 4 - Solo el Valor" id="habitaciones" name="propiedad[habitaciones]" value="<?php echo is_numeric($propiedad?->habitaciones) ? $propiedad->habitaciones : ''; ?>">

        <label for="estrato">Estrato</label>
        <input type="number" placeholder="Ej: 3 - Solo el Valor" id="estrato" name="propiedad[estrato]" value="<?php echo is_numeric($propiedad?->estrato) ? $propiedad->estrato : ''; ?>">

        <label for="zona_ropa">Zona de Ropa*</label>
        <select id="zona_ropa" class="select-estilizado" name="propiedad[zona_ropa]">
            <option value="" disabled <?php echo empty($propiedad?->zona_ropa) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->zona_ropa === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->zona_ropa === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="cocina">Cocina*</label>
        <select id="cocina" class="select-estilizado" name="propiedad[cocina]">
            <option value="" disabled <?php echo empty($propiedad?->cocina) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->cocina === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->cocina === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="sala">Sala*</label>
        <select id="sala" class="select-estilizado" name="propiedad[sala]">
            <option value="" disabled <?php echo empty($propiedad?->sala) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->sala === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->sala === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="garaje">Garaje*</label>
        <select id="garaje" class="select-estilizado" name="propiedad[garaje]">
            <option value="" disabled <?php echo empty($propiedad?->garaje) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->garaje === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->garaje === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="jacuzzi">Jacuzzi*</label>
        <select id="jacuzzi" class="select-estilizado" name="propiedad[jacuzzi]">
            <option value="" disabled <?php echo empty($propiedad?->jacuzzi) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->jacuzzi === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->jacuzzi === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="turco">Turco*</label>
        <select id="turco" class="select-estilizado" name="propiedad[turco]">
            <option value="" disabled <?php echo empty($propiedad?->turco) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->turco === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->turco === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="tipo-unidad">Tipo de Unidad*</label>
        <select id="tipo-unidad" class="select-estilizado" name="propiedad[tipo_unidad]">
            <option value="" disabled <?php echo empty($propiedad?->tipo_unidad) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="abierta" <?php echo ($propiedad?->tipo_unidad === 'abierta') ? 'selected' : ''; ?>>Abierta</option>
            <option value="cerrada" <?php echo ($propiedad?->tipo_unidad === 'cerrada') ? 'selected' : ''; ?>>Cerrada</option>
            <option value="Independiente" <?php echo ($propiedad?->tipo_unidad === 'Independiente') ? 'selected' : ''; ?>>Independiente</option>
        </select>
    </fieldset>

    <fieldset>
        <legend>Información de la Unidad</legend>

        <label for="vigilancia">Vigilancia 24/7*</label>
        <select id="vigilancia" class="select-estilizado" name="propiedad[vigilancia]">
            <option value="" disabled <?php echo empty($propiedad?->vigilancia) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->vigilancia === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->vigilancia === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="zonas_verdes">Zonas Verdes*</label>
        <select id="zonas_verdes" class="select-estilizado" name="propiedad[zonas_verdes]">
            <option value="" disabled <?php echo empty($propiedad?->zonas_verdes) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->zonas_verdes === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->zonas_verdes === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="juegos">Juegos Infantiles*</label>
        <select id="juegos" class="select-estilizado" name="propiedad[juegos]">
            <option value="" disabled <?php echo empty($propiedad?->juegos) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->juegos === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->juegos === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="coworking">Coworking*</label>
        <select id="coworking" class="select-estilizado" name="propiedad[coworking]">
            <option value="" disabled <?php echo empty($propiedad?->coworking) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->coworking === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->coworking === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="gimnasio">Gimnasio*</label>
        <select id="gimnasio" class="select-estilizado" name="propiedad[gimnasio]">
            <option value="" disabled <?php echo empty($propiedad?->gimnasio) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->gimnasio === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->gimnasio === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="piscina">Piscina*</label>
        <select id="piscina" class="select-estilizado" name="propiedad[piscina]">
            <option value="" disabled <?php echo empty($propiedad?->piscina) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->piscina === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->piscina === 'No') ? 'selected' : ''; ?>>No</option>
        </select>

        <label for="cancha">Canchas Deportivas*</label>
        <select id="cancha" class="select-estilizado" name="propiedad[cancha]">
            <option value="" disabled <?php echo empty($propiedad?->cancha) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Si" <?php echo ($propiedad?->cancha === 'Si') ? 'selected' : ''; ?>>Si</option>
            <option value="No" <?php echo ($propiedad?->cancha === 'No') ? 'selected' : ''; ?>>No</option>
        </select>
    </fieldset>

    <fieldset>
        
        <legend>Descripcion de la Propiedad*</legend>
        <textarea name="propiedad[descripcion]" id="descripcion"> <?php echo s($propiedad->descripcion); ?> </textarea>
    </fieldset>


    <input type="submit" value="Actualizar" class="boton-verde">
    </form>

</main>