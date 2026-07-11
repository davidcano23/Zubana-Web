<main class="sa-form">
    <img src="/img/logo_ZB.png" alt="Zubana" class="sa-logo-login">
    <h1>Acceso Superadmin</h1>

    <?php foreach (($errores ?? []) as $error): ?>
        <div class="sa-alerta"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <div class="sa-card">
        <form method="POST" action="/superadmin/login">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required
                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" class="sa-boton" value="Ingresar">
        </form>
    </div>
</main>
