<main class="contenedor crear">

    <h1>actualizar Lotes</h1>

    <?php foreach($errores as $error): ?>
            
        <div class="alerta error">

            <?php echo $error; ?>

    </div> <?php endforeach; ?>

    <a href="/admin" class="bt-regresar">Regresar</a>

    <form action="" class="formulario" method="POST" enctype="multipart/form-data">

        <?php include __DIR__ . '/formulario.php'; ?>

    <fieldset>
    <legend>Tipo de Propiedad</legend>

        <label for="tipo-propiedad">Tipo de Propiedad</label>
        <select id="tipo-propiedad" class="select-estilizado" name="propiedad[tipo]">
            <option value="" disabled <?php echo empty($propiedad?->tipo) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
<<<<<<< HEAD
            <option value="Lote Campestre" <?php echo ($propiedad?->tipo === 'Lote Campestre') ? 'selected' : ''; ?>>Lote Campestre</option>
=======
            <option value="Lote" <?php echo ($propiedad?->tipo === 'Lote') ? 'selected' : ''; ?>>Lote</option>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
            <option value="Lote Urbanizable" <?php echo ($propiedad?->tipo === 'Lote Urbanizable') ? 'selected' : ''; ?>>Lote Urbanizable</option>
            <option value="Lote Rural" <?php echo ($propiedad?->tipo === 'Lote Rural') ? 'selected' : ''; ?>>Lote Rural</option>
            <option value="Lote Bodega" <?php echo ($propiedad?->tipo === 'Lote Bodega') ? 'selected' : ''; ?>>Lote Bodega</option>
        </select>

        <input type="hidden" name="propiedad[actualizacion]" value="lote">

    </fieldset>

    <fieldset>
        <legend>Información Lote urbanizable</legend>

    <label for="area_total">Área Total</label>
    <input 
        type="text" 
        placeholder="En m2 el valor" 
        id="area_total" 
        name="propiedad[area_total]" 
        value="<?php echo trim(s($propiedad?->area_total ?? '')); ?>"
    >

    <label for="estrato">Estrato</label>
    <input 
        type="number" 
        placeholder="Ej: 3 - Solo el Valor" 
        id="estrato" 
        name="propiedad[estrato]" 
        value="<?php echo is_numeric($propiedad?->estrato) ? $propiedad->estrato : ''; ?>"
    >

    <label for="tipo-unidad">Tipo de Unidad</label>
    <select id="tipo-unidad" class="select-estilizado" name="propiedad[tipo_unidad]">
        <option value="" disabled <?php echo empty($propiedad?->tipo_unidad) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
        <option value="abierta" <?php echo ($propiedad?->tipo_unidad === 'abierta') ? 'selected' : ''; ?>>Abierta</option>
        <option value="cerrada" <?php echo ($propiedad?->tipo_unidad === 'cerrada') ? 'selected' : ''; ?>>Cerrada</option>
        <option value="independiente" <?php echo ($propiedad?->tipo_unidad === 'independiente') ? 'selected' : ''; ?>>Independiente</option>
    </select>
    </fieldset>

    <fieldset>
<<<<<<< HEAD
        <legend>Descripcion de la Propiedad</legend>
=======
        <legend>Descripcion de la Unidad</legend>
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
        <textarea name="propiedad[descripcion]" id="descripcion"> <?php echo s($propiedad->descripcion); ?> </textarea>
    </fieldset>


    <input type="submit" value="Actualizar" class="boton-verde">
    </form>

</main>