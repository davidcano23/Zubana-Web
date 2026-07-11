
<main class="contenedor crear">

<?php $errores = $errores ?? []; ?>
<?php foreach($errores as $error): ?>
            
        <div class="alerta error">

            <?php echo $error; ?>

    </div> <?php endforeach; ?>

    <h1>Registro Inmobiliaria</h1>

    <a href="/" class="bt-regresar">Regresar</a>

<form action="" class="formulario" method="POST" enctype="multipart/form-data">

<fieldset>

<legend>Registro Inmobiliaria</legend>

    <label for="nombre_registro">Nombre</label>
    <input type="text" name="nombre" placeholder="Nombre de la inmobiliaria" class="nombre_registro" id="nombre_registro" value="<?php echo htmlspecialchars($nombre ?? ''); ?>">

    <label for="subdominio_registro">Subdominio</label>
    <input type="text" name="subdominio" placeholder="Subdominio de la inmobiliaria" class="subdominio_registro" id="subdominio_registro" value="<?php echo htmlspecialchars($subdominio ?? ''); ?>">

    <label for="email_registro">Email</label>
    <input type="email" name="email" placeholder="Email de la inmobiliaria" class="email_registro" id="email_registro" value="<?php echo htmlspecialchars($email ?? ''); ?>">

    <label for="password_registro">Password</label>
    <input type="password" name="password" placeholder="Password de la inmobiliaria" class="password_registro" id="password_registro">


</fieldset>

    <input type="submit" value="Crear" class="boton-verde">
</form>

</main>