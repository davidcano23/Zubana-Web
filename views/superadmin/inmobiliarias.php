<?php
    $inmobiliarias = $inmobiliarias ?? [];
    $exito = $exito ?? null;
    $mensajes = [
        'creada' => 'Inmobiliaria creada correctamente. Su subdominio ya está listo.',
        'estado' => 'Estado actualizado.',
    ];
?>
<main class="sa-contenido">
    <h1>Inmobiliarias</h1>

    <?php if ($exito && isset($mensajes[$exito])): ?>
        <div class="sa-alerta-exito" style="margin-top: 1rem;"><?php echo $mensajes[$exito]; ?></div>
    <?php endif; ?>

    <div class="sa-barra" style="margin-top: 1.2rem;">
        <input type="text" id="buscador" class="sa-buscar" placeholder="Buscar por nombre o subdominio...">
        <a href="/superadmin/inmobiliarias/crear" class="sa-boton-sec">+ Nueva inmobiliaria</a>
    </div>

    <div class="sa-card" style="padding: .8rem 1.2rem;">
        <table class="sa-tabla" id="tabla-inmobiliarias">
            <thead>
                <tr>
                    <th>Inmobiliaria</th><th>Subdominio</th><th>Estado</th>
                    <th class="num">Propiedades</th><th class="num">Usuarios</th>
                    <th>Registro</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($inmobiliarias)): ?>
                <tr><td colspan="7" style="color: var(--sa-suave);">Sin inmobiliarias registradas todavía.</td></tr>
            <?php endif; ?>
            <?php foreach ($inmobiliarias as $t): ?>
                <?php $estado = Model\Tenant::normalizarEstado($t->estado); ?>
                <tr>
                    <td><?php echo htmlspecialchars($t->nombre); ?></td>
                    <td style="color: var(--sa-suave);"><?php echo htmlspecialchars($t->subdominio); ?></td>
                    <td><span class="sa-badge <?php echo $estado; ?>"><?php echo $estado; ?></span></td>
                    <td class="num"><strong><?php echo (int) $t->total_propiedades; ?></strong></td>
                    <td class="num"><?php echo (int) $t->usuarios; ?></td>
                    <td style="color: var(--sa-suave);"><?php echo htmlspecialchars(substr($t->created_at, 0, 10)); ?></td>
                    <td>
                        <form method="POST" action="/superadmin/inmobiliarias/estado" class="sa-acciones">
                            <input type="hidden" name="id" value="<?php echo (int) $t->id; ?>">
                            <select name="estado" class="sa-select">
                                <?php foreach (Model\Tenant::ESTADOS as $op): ?>
                                    <?php if ($op === $estado) continue; // no ofrecer el estado actual ?>
                                    <option value="<?php echo $op; ?>"><?php echo $op; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="sa-boton-mini">Cambiar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
// Búsqueda en vivo: filtra las filas de la tabla sin recargar la página
document.getElementById('buscador').addEventListener('input', function () {
    const texto = this.value.toLowerCase();
    document.querySelectorAll('#tabla-inmobiliarias tbody tr').forEach(function (fila) {
        const nombre     = (fila.cells[0]?.textContent || '').toLowerCase();
        const subdominio = (fila.cells[1]?.textContent || '').toLowerCase();
        fila.style.display = (nombre.includes(texto) || subdominio.includes(texto)) ? '' : 'none';
    });
});
</script>
