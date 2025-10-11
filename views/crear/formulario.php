<fieldset>

    <legend>Informacion General</legend>

<<<<<<< HEAD
    <!-- <label for="titulo">Nombre de la Propiedad</label>
    <input type="text" placeholder="Titulo de la propiedad" id="titulo" name="propiedad[nombre]" value="<?php echo trim(s($propiedad?->nombre ?? '')); ?>"> -->
=======
    <label for="titulo">Nombre de la Propiedad</label>
    <input type="text" placeholder="Titulo de la propiedad" id="titulo" name="propiedad[nombre]" value="<?php echo trim(s($propiedad?->nombre ?? '')); ?>">
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189

    <label for="precio">Precio</label>
    <input type="text" placeholder="Precio de la propiedad" id="precio" name="propiedad[precio]" value="<?php echo trim(s($propiedad?->precio ?? '')); ?>">

    <label for="ubicacion">Ubicacion</label>
    <input type="text" placeholder="Ej: Llanogrande - Antioquia" id="ubicacion" name="propiedad[ubicacion]" value="<?php echo trim(s($propiedad?->ubicacion ?? '')); ?>">

    <label for="direccion">Direccion Exacta</label>
    <input type="text" placeholder="Calle 40 #73-21" id="direccion" name="propiedad[direccion]" value="<?php echo trim(s($propiedad?->direccion ?? '')); ?>">

    <label for="barrio">Barrio</label>
    <input type="text" placeholder="Ej: El Porvenir" id="barrio" name="propiedad[barrio]" value="<?php echo trim(s($propiedad?->barrio ?? '')); ?>">

    <label for="propietario">Nombre del Propietario</label>
    <input type="text" placeholder="Propietario" id="propietario" name="propiedad[propietario]" value="<?php echo trim(s($propiedad?->propietario ?? '')); ?>">

    <label for="contacto">Contacto del Propietario</label>
    <input type="tel" placeholder="Contacto" id="contacto" name="propiedad[contacto]" value="<?php echo trim(s($propiedad?->contacto ?? '')); ?>">

<<<<<<< HEAD
    <!-- <label for="codigo">Codigo</label>
    <input type="text" placeholder="Ej: 0001" id="codigo" name="propiedad[codigo]" value="<?php echo trim(s($propiedad?->codigo ?? '')); ?>"> -->
=======
    <label for="codigo">Codigo</label>
    <input type="text" placeholder="Ej: 0001" id="codigo" name="propiedad[codigo]" value="<?php echo trim(s($propiedad?->codigo ?? '')); ?>">
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189

    <label for="imagen" class="custom-file-label">Imagen Principal</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" class="custom-file-input" name="propiedad[imagen]">

    <?php if(!empty($propiedad?->imagen)) { ?>
        
    <img src="/imagenes/<?php echo $propiedad->imagen ?>" alt="" class="imagen-peque">

    <?php } ?>

    <label for="imagenes">Imágenes Adicionales (hasta 15):</label>
<<<<<<< HEAD
    <input type="file" name="imagenes[]" id="imagenes" multiple accept="image/*" required>
=======
    <input type="file" name="imagenes[]" id="imagenes" multiple accept="image/*">
>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189



    <!-- <label for="imagenes" class="custom-file-label">Imágenes Secundarias</label>
    <input type="file" id="imagenes" accept="image/jpeg, image/png" multiple class="custom-file-input"> -->

    <label for="modalidad">Modalidad</label>
    <select id="modalidad" class="select-estilizado" name="propiedad[modalidad]" required>
    <option value="" disabled <?php echo empty($propiedad?->modalidad) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
    <option value="Directo" <?php echo ($propiedad?->modalidad ?? '') === 'Directo' ? 'selected' : ''; ?>>Directo</option>
    <option value="Colegaje" <?php echo ($propiedad?->modalidad ?? '') === 'Colegaje' ? 'selected' : ''; ?>>Colegaje</option>
    </select>

</fieldset>




