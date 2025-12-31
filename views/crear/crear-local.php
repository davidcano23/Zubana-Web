<?php
    $actualizar = true;
?>

<main class="contenedor crear">

    <h1>Crear Local</h1>

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
        <select id="tipo-propiedad" class="select-estilizado" name="propiedad[tipo]">
            <option value="" disabled <?php echo empty($propiedad?->tipo) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="Local" <?php echo ($propiedad?->tipo === 'Local') ? 'selected' : ''; ?>>Local</option>
        </select>

        <input type="hidden" name="propiedad[actualizacion]" value="local">

    </fieldset>

    <fieldset>
        <legend>Información Local Comercial</legend>

        <label for="area_total">Área Total</label>
        <input type="text" placeholder="En m2 el valor" id="area_total" name="propiedad[area_total]" value="<?php echo trim(s($propiedad?->area_total ?? '')); ?>">

        <label for="area_construida">Área Construida</label>
        <input type="text" placeholder="En m2 el valor" id="area_construida" name="propiedad[area_construida]" value="<?php echo trim(s($propiedad?->area_construida ?? '')); ?>">

        <label for="wc">Baños</label>
        <input type="number" placeholder="Ej: 5 - Solo el Valor" id="wc" name="propiedad[banos]" value="<?php echo is_numeric($propiedad?->banos) ? $propiedad->banos : ''; ?>">

        <label for="estrato">Estrato</label>
        <input type="number" placeholder="Ej: 3 - Solo el Valor" id="estrato" name="propiedad[estrato]" value="<?php echo is_numeric($propiedad?->estrato) ? $propiedad->estrato : ''; ?>">

        <label for="tipo-local">Tipo de Local*</label>
        <select id="tipo-local" class="select-estilizado" name="propiedad[tipo_local]">
            <option value="" disabled <?php echo empty($propiedad?->tipo_local) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
            <option value="centro comercial" <?php echo ($propiedad?->tipo_local === 'centro comercial') ? 'selected' : ''; ?>>Centro Comercial</option>
            <option value="independiente" <?php echo ($propiedad?->tipo_local === 'independiente') ? 'selected' : ''; ?>>Independiente</option>
        </select>
    </fieldset>

    <fieldset>

        <legend>Descripcion de la Propiedad*</legend>
        <textarea name="propiedad[descripcion]" id="descripcion"> <?php echo s($propiedad->descripcion); ?> </textarea>
    </fieldset>


    <input type="submit" value="Crear" class="boton-verde">
    </form>

</main>