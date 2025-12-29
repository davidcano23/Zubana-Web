<fieldset>

    <legend>Informacion General</legend>

    <label for="precio">Precio</label>
    <input type="text" placeholder="Precio de la propiedad" id="precio" name="propiedad[precio]" value="<?php echo trim(s($propiedad?->precio ?? '')); ?>">

    <label for="precio">Administración</label>
    <input type="text" placeholder="Si la propiedad no tiene administración, ingresa 0." id="administracion" name="propiedad[administracion]" value="<?php echo trim(s($propiedad?->administracion ?? '')); ?>">

    <label for="ubicacion">Municipio</label>
    <input type="text" placeholder="Ej: Llanogrande - Antioquia" id="ubicacion" name="propiedad[ubicacion]" value="<?php echo trim(s($propiedad?->ubicacion ?? '')); ?>">

    <label for="barrio">Corregimiento</label>
    <input type="text" placeholder="San antonio de pereira, Campo no obligatorio" id="corregimiento" name="propiedad[corregimiento]" value="<?php echo trim(s($propiedad?->corregimiento ?? '')); ?>">

    <label for="barrio">Barrio</label>
    <input type="text" placeholder="Ej: El Porvenir" id="barrio" name="propiedad[barrio]" value="<?php echo trim(s($propiedad?->barrio ?? '')); ?>">

    <label for="barrio">Palabra Clave</label>
    <input type="text" placeholder="No ingresar mas de una palabra clave, Campo no obligatorio" id="palabra_clave" name="propiedad[palabra_clave]" value="<?php echo trim(s($propiedad?->palabra_clave ?? '')); ?>">

    <label for="direccion">Direccion Exacta</label>
    <input type="text" placeholder="Calle 40 #73-21" id="direccion" name="propiedad[direccion]" value="<?php echo trim(s($propiedad?->direccion ?? '')); ?>">

    <input type="hidden" id="lat" name="propiedad[latitud]" value="<?php echo s($propiedad?->latitud ?? ''); ?>">
    <input type="hidden" id="lng" name="propiedad[longitud]" value="<?php echo s($propiedad?->longitud ?? ''); ?>">

    <label>Ubicación en el Mapa (Arrastra el Pin al punto exacto):</label>
    <div id="mapa-formulario" style="height: 400px; width: 100%; border: 1px solid #ccc; border-radius: 10px;"></div>

    <label for="propietario">Nombre del Propietario</label>
    <input type="text" placeholder="Propietario" id="propietario" name="propiedad[propietario]" value="<?php echo trim(s($propiedad?->propietario ?? '')); ?>">

    <label for="contacto">Contacto del Propietario</label>
    <input type="tel" placeholder="Contacto" id="contacto" name="propiedad[contacto]" value="<?php echo trim(s($propiedad?->contacto ?? '')); ?>">

    <label for="imagen" class="custom-file-label">Imagen Principal</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" class="custom-file-input" name="propiedad[imagen]">

    <?php if(!empty($propiedad?->imagen)) { ?>
        
    <img src="/imagenes/<?php echo $propiedad->imagen ?>" alt="" class="imagen-peque">

    <?php } ?>


    <?php if($actualizar): ?>
    <label for="imagenes">Imágenes Adicionales (hasta 15):</label>
    <input type="file" name="imagenes[]" id="imagenes" multiple accept="image/*" required>
    <?php endif; ?>


    <!-- <label for="imagenes" class="custom-file-label">Imágenes Secundarias</label>
    <input type="file" id="imagenes" accept="image/jpeg, image/png" multiple class="custom-file-input"> -->

    <label for="modalidad">Modalidad</label>
    <select id="modalidad" class="select-estilizado" name="propiedad[modalidad]" required>
    <option value="" disabled <?php echo empty($propiedad?->modalidad) ? 'selected' : ''; ?>>-- Selecciona una opción --</option>
    <option value="Directo" <?php echo ($propiedad?->modalidad ?? '') === 'Directo' ? 'selected' : ''; ?>>Directo</option>
    <option value="Colegaje" <?php echo ($propiedad?->modalidad ?? '') === 'Colegaje' ? 'selected' : ''; ?>>Colegaje</option>
    </select>

</fieldset>





