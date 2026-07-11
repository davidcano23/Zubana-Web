<main class="sa-form">
    <h1>Nueva inmobiliaria</h1>

    <?php foreach (($errores ?? []) as $error): ?>
        <div class="sa-alerta"><?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <div class="sa-card">
        <form method="POST" action="/superadmin/inmobiliarias/crear">
            <label for="nombre">Nombre de la inmobiliaria</label>
            <input type="text" name="nombre" id="nombre" required
                   value="<?php echo htmlspecialchars($nombre ?? ''); ?>">

            <label for="subdominio">Subdominio</label>
            <input type="text" name="subdominio" id="subdominio" required
                   placeholder="ej: casasdelvalle"
                   value="<?php echo htmlspecialchars($subdominio ?? ''); ?>">

            <label for="email">Email del primer usuario admin</label>
            <input type="email" name="email" id="email" required
                   value="<?php echo htmlspecialchars($email ?? ''); ?>">

            <label for="password">Password inicial</label>
            <input type="password" name="password" id="password" required minlength="6">

            <label for="estado">Estado inicial</label>
            <select name="estado" id="estado" class="sa-select" style="width: 100%; padding: .7rem .9rem; font-size: 1rem;">
                <option value="activo">activo (cliente que ya pagó)</option>
                <option value="prueba">prueba</option>
            </select>

            <input type="submit" class="sa-boton" value="Crear inmobiliaria">
        </form>
    </div>

    <p style="text-align:center; margin-top: 1.2rem;">
        <a href="/superadmin/inmobiliarias">&larr; Volver al listado</a>
    </p>
</main>
