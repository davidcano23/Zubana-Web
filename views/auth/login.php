<main class="contenedor seccion contenido-centrado">
    <h2>Login Panel Administrativo</h1>

    <?php foreach($errores as $error):?>

        <div class="alerta error">
            <?php echo $error;?>        
        </div>

    <?php endforeach; ?>

    <form action="/login" method="POST" class="formulario">
        <fieldset>

                 <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email">

                 <label for="telefono">Password</label>
                <input type="password" name="password" placeholder="Tu Password" id="password">

                <input type="submit" value="Iniciar Sesión" class="boton">

                
            </fieldset>
            
    </form>
</main>