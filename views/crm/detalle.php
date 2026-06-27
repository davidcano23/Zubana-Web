<?php
$etapas   = $etapas   ?? [];
$tiposAct = $tiposAct ?? [];
$etapa    = $etapas[$cliente->estado] ?? ['label' => $cliente->estado, 'color' => '#888'];
?>

<main class="crm-page">
<div class="crm-wrap">

    <!-- Breadcrumb -->
    <div class="crm-breadcrumb">
        <a href="/crm">CRM</a>
        <span>›</span>
        <span><?= htmlspecialchars($cliente->nombreCompleto(), ENT_QUOTES) ?></span>
    </div>

    <?php if (!empty($exito)): ?>
    <div class="crm-alert crm-alert--ok"><?= htmlspecialchars($exito, ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <div class="crm-alert crm-alert--err"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <div class="crm-detalle">

        <!-- ── Sidebar izquierdo ── -->
        <aside class="crm-perfil">

            <div class="crm-perfil__avatar"><?= htmlspecialchars($cliente->iniciales(), ENT_QUOTES) ?></div>
            <h2 class="crm-perfil__nombre"><?= htmlspecialchars($cliente->nombreCompleto(), ENT_QUOTES) ?></h2>

            <!-- Estado con cambio rápido -->
            <div class="crm-perfil__estado-wrap">
                <span class="crm-badge crm-badge--lg" id="estadoBadge"
                      style="--stage-color:<?= $etapa['color'] ?>">
                    <?= $etapa['label'] ?>
                </span>
                <button class="crm-perfil__change-btn" id="btnCambiarEstado">Cambiar</button>
            </div>

            <div class="crm-estado-picker" id="estadoPicker" style="display:none">
                <?php foreach ($etapas as $k => $e): ?>
                <button type="button" class="crm-estado-opt"
                        data-estado="<?= $k ?>" data-cliente="<?= $cliente->id ?>"
                        style="--stage-color:<?= $e['color'] ?>">
                    <span class="crm-estado-opt__dot"></span>
                    <?= $e['label'] ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Contacto -->
            <div class="crm-perfil__section">
                <h4>Contacto</h4>
                <ul class="crm-perfil__list">
                    <?php if ($cliente->telefono): ?>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        <a href="tel:<?= htmlspecialchars($cliente->telefono, ENT_QUOTES) ?>">
                            <?= htmlspecialchars($cliente->telefono, ENT_QUOTES) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($cliente->email): ?>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <a href="mailto:<?= htmlspecialchars($cliente->email, ENT_QUOTES) ?>">
                            <?= htmlspecialchars($cliente->email, ENT_QUOTES) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($cliente->ciudad): ?>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= htmlspecialchars($cliente->ciudad, ENT_QUOTES) ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Intereses -->
            <div class="crm-perfil__section">
                <h4>Intereses</h4>
                <ul class="crm-perfil__list">
                    <li><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <?= htmlspecialchars($cliente->tipo_busqueda, ENT_QUOTES) ?>
                        <?php if ($cliente->tipo_propiedad): ?> · <?= htmlspecialchars($cliente->tipo_propiedad, ENT_QUOTES) ?><?php endif; ?>
                    </li>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                        <?= $cliente->presupuestoFormateado() ?>
                    </li>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                        <?= htmlspecialchars($cliente->fuente, ENT_QUOTES) ?>
                    </li>
                </ul>
            </div>

            <!-- Notas -->
            <?php if ($cliente->notas): ?>
            <div class="crm-perfil__section">
                <h4>Notas</h4>
                <p class="crm-perfil__notas"><?= nl2br(htmlspecialchars($cliente->notas, ENT_QUOTES)) ?></p>
            </div>
            <?php endif; ?>

            <!-- Acciones -->
            <div class="crm-perfil__actions">
                <a href="/crm/editar?id=<?= $cliente->id ?>" class="crm-btn crm-btn--secondary">Editar</a>
                <button class="crm-btn crm-btn--danger" id="btnEliminar">Eliminar</button>
            </div>

            <p class="crm-perfil__created">
                Creado <?= date('d M Y', strtotime($cliente->created_at)) ?>
            </p>
        </aside>

        <!-- ── Panel derecho: actividades ── -->
        <section class="crm-actividades-panel">

            <h3 class="crm-actividades-panel__title">Actividad</h3>

            <!-- Formulario agregar actividad -->
            <form class="crm-add-act" id="formActividad">
                <input type="hidden" name="cliente_id" value="<?= $cliente->id ?>">
                <div class="crm-add-act__row">
                    <select name="tipo" class="crm-select crm-add-act__tipo">
                        <?php foreach ($tiposAct as $k => $t): ?>
                        <option value="<?= $k ?>"><?= htmlspecialchars($t['label'], ENT_QUOTES) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <textarea name="descripcion" class="crm-textarea" rows="3"
                          placeholder="Escribe lo que ocurrió…" required></textarea>
                <button type="submit" class="crm-btn crm-btn--primary">Registrar actividad</button>
            </form>

            <!-- Timeline -->
            <div class="crm-timeline" id="timeline">
                <?php if (empty($actividades)): ?>
                <p class="crm-timeline__empty">Aún no hay actividades registradas.</p>
                <?php else: foreach ($actividades as $act):
                    $tipoInfo = $tiposAct[$act->tipo] ?? ['label' => $act->tipo, 'ico' => 'O'];
                ?>
                <div class="crm-tl-item">
                    <div class="crm-tl-item__ico"><?= $tipoInfo['ico'] ?></div>
                    <div class="crm-tl-item__content">
                        <div class="crm-tl-item__head">
                            <span class="crm-tl-item__tipo"><?= $tipoInfo['label'] ?></span>
                            <span class="crm-tl-item__fecha">
                                <?= date('d M Y · H:i', strtotime($act->created_at)) ?>
                            </span>
                        </div>
                        <p class="crm-tl-item__desc"><?= nl2br(htmlspecialchars($act->descripcion, ENT_QUOTES)) ?></p>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>

        </section>
    </div>
</div>
</main>

<!-- Form oculto para eliminar -->
<form action="/crm/eliminar" method="POST" id="formEliminar" style="display:none">
    <input type="hidden" name="id" value="<?= $cliente->id ?>">
</form>

<script>
(function () {
    // ── Cambiar estado ──
    const badge   = document.getElementById('estadoBadge');
    const picker  = document.getElementById('estadoPicker');
    const btnCE   = document.getElementById('btnCambiarEstado');

    btnCE.addEventListener('click', () => {
        picker.style.display = picker.style.display === 'none' ? 'flex' : 'none';
    });

    document.querySelectorAll('.crm-estado-opt').forEach(btn => {
        btn.addEventListener('click', async function () {
            const estado    = this.dataset.estado;
            const clienteId = this.dataset.cliente;
            const color     = getComputedStyle(this).getPropertyValue('--stage-color').trim();
            const label     = this.textContent.trim();

            try {
                const fd = new FormData();
                fd.append('id', clienteId);
                fd.append('estado', estado);
                const r = await fetch('/crm/estado', { method: 'POST', body: fd });
                const j = await r.json();
                if (j.ok) {
                    badge.textContent = j.label;
                    badge.style.setProperty('--stage-color', j.color);
                    picker.style.display = 'none';
                }
            } catch (e) { /* silencio */ }
        });
    });

    // Cerrar picker al hacer clic fuera
    document.addEventListener('click', e => {
        if (!picker.contains(e.target) && e.target !== btnCE) {
            picker.style.display = 'none';
        }
    });

    // ── Agregar actividad (AJAX) ──
    const form     = document.getElementById('formActividad');
    const timeline = document.getElementById('timeline');
    const tipos    = <?= json_encode($tiposAct) ?>;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn  = this.querySelector('button[type="submit"]');
        const fd   = new FormData(this);
        btn.disabled = true;
        btn.textContent = 'Guardando…';

        try {
            const r = await fetch('/crm/actividad', { method: 'POST', body: fd });
            const j = await r.json();
            if (j.ok) {
                const emptyMsg = timeline.querySelector('.crm-timeline__empty');
                if (emptyMsg) emptyMsg.remove();

                const item = document.createElement('div');
                item.className = 'crm-tl-item crm-tl-item--new';
                item.innerHTML = `
                    <div class="crm-tl-item__ico">${j.tipoIco}</div>
                    <div class="crm-tl-item__content">
                        <div class="crm-tl-item__head">
                            <span class="crm-tl-item__tipo">${j.tipoLabel}</span>
                            <span class="crm-tl-item__fecha">${j.fecha}</span>
                        </div>
                        <p class="crm-tl-item__desc">${j.descripcion.replace(/\n/g, '<br>')}</p>
                    </div>`;
                timeline.prepend(item);
                this.querySelector('textarea').value = '';
            } else {
                alert(j.msg || 'Error al guardar.');
            }
        } catch (err) {
            alert('Error de conexión.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Registrar actividad';
        }
    });

    // ── Eliminar cliente ──
    document.getElementById('btnEliminar').addEventListener('click', () => {
        if (confirm('¿Eliminar a <?= htmlspecialchars($cliente->nombreCompleto(), ENT_QUOTES) ?>? Esta acción no se puede deshacer.')) {
            document.getElementById('formEliminar').submit();
        }
    });
})();
</script>
