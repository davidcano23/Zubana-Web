<?php
    // Valores por defecto para que la vista nunca reviente
    $porEstado = $porEstado ?? [];
    $porTipo   = $porTipo ?? [];
    $porTenant = $porTenant ?? [];
    $recientes = $recientes ?? [];
?>
<main class="sa-contenido">
    <h1>Hola, <?php echo htmlspecialchars($nombre ?? 'Superadmin'); ?></h1>
    <p style="color: var(--sa-suave); margin-top: .4rem;">
        Resumen general del SaaS. Gráficas y mapa llegan en las Tareas 5 y 7.
    </p>

    <!-- Fila 1: totales generales -->
    <div class="sa-grid">
        <div class="sa-card sa-metrica"><h3>Inmobiliarias</h3><p><?php echo (int) ($totalTenants ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Activas</h3><p><?php echo (int) ($porEstado['activo'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>En prueba</h3><p><?php echo (int) ($porEstado['prueba'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Suspendidas</h3><p><?php echo (int) ($porEstado['suspendido'] ?? 0); ?></p></div>
    </div>

    <!-- Fila 2: propiedades y usuarios -->
    <div class="sa-grid">
        <div class="sa-card sa-metrica"><h3>Propiedades totales</h3><p><?php echo (int) ($totalPropiedades ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Casas / Fincas</h3><p><?php echo (int) ($porTipo['casas'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Apartamentos</h3><p><?php echo (int) ($porTipo['apartamentos'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Locales</h3><p><?php echo (int) ($porTipo['locales'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Lotes</h3><p><?php echo (int) ($porTipo['lotes'] ?? 0); ?></p></div>
        <div class="sa-card sa-metrica"><h3>Usuarios</h3><p><?php echo (int) ($totalUsuarios ?? 0); ?></p></div>
    </div>

    <!-- Gráficas (Tarea 5): se alimentan del endpoint JSON -->
    <section class="sa-seccion">
        <h2>Gráficas</h2>
        <div class="sa-graficas">
            <div class="sa-card">
                <h3 style="color: var(--sa-suave); font-size: .95rem; font-weight: 500; margin-bottom: .8rem;">Crecimiento de inmobiliarias</h3>
                <div class="sa-grafica"><canvas id="graficaCrecimiento"></canvas></div>
            </div>
            <div class="sa-card">
                <h3 style="color: var(--sa-suave); font-size: .95rem; font-weight: 500; margin-bottom: .8rem;">Propiedades por tipo</h3>
                <div class="sa-grafica"><canvas id="graficaTipos"></canvas></div>
            </div>
            <div class="sa-card ancho-completo">
                <h3 style="color: var(--sa-suave); font-size: .95rem; font-weight: 500; margin-bottom: .8rem;">Propiedades por inmobiliaria (top 10)</h3>
                <div class="sa-grafica"><canvas id="graficaTenants"></canvas></div>
            </div>
        </div>
    </section>

    <!-- Tabla: detalle por inmobiliaria -->
    <section class="sa-seccion">
        <h2>Inmobiliarias</h2>
        <div class="sa-card" style="padding: .8rem 1.2rem;">
            <table class="sa-tabla">
                <thead>
                    <tr>
                        <th>Inmobiliaria</th><th>Subdominio</th><th>Estado</th>
                        <th class="num">Casas</th><th class="num">Aptos</th>
                        <th class="num">Locales</th><th class="num">Lotes</th>
                        <th class="num">Total</th><th class="num">Usuarios</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($porTenant)): ?>
                    <tr><td colspan="9" style="color: var(--sa-suave);">Sin inmobiliarias registradas todavía.</td></tr>
                <?php endif; ?>
                <?php foreach ($porTenant as $t): ?>
                    <?php $estadoT = Model\Tenant::normalizarEstado($t->estado); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t->nombre); ?></td>
                        <td style="color: var(--sa-suave);"><?php echo htmlspecialchars($t->subdominio); ?></td>
                        <td><span class="sa-badge <?php echo $estadoT; ?>"><?php echo $estadoT; ?></span></td>
                        <td class="num"><?php echo (int) $t->casas; ?></td>
                        <td class="num"><?php echo (int) $t->apartamentos; ?></td>
                        <td class="num"><?php echo (int) $t->locales; ?></td>
                        <td class="num"><?php echo (int) $t->lotes; ?></td>
                        <td class="num"><strong><?php echo (int) $t->total_propiedades; ?></strong></td>
                        <td class="num"><?php echo (int) $t->usuarios; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Registros recientes -->
    <section class="sa-seccion">
        <h2>Registros recientes</h2>
        <div class="sa-card" style="padding: .8rem 1.2rem;">
            <table class="sa-tabla">
                <thead><tr><th>Inmobiliaria</th><th>Subdominio</th><th>Estado</th><th>Fecha de registro</th></tr></thead>
                <tbody>
                <?php if (empty($recientes)): ?>
                    <tr><td colspan="4" style="color: var(--sa-suave);">Sin registros todavía.</td></tr>
                <?php endif; ?>
                <?php foreach ($recientes as $t): ?>
                    <?php $estadoR = Model\Tenant::normalizarEstado($t->estado); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t->nombre); ?></td>
                        <td style="color: var(--sa-suave);"><?php echo htmlspecialchars($t->subdominio); ?></td>
                        <td><span class="sa-badge <?php echo $estadoR; ?>"><?php echo $estadoR; ?></span></td>
                        <td><?php echo htmlspecialchars($t->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ============ Gráficas del panel (Tarea 5) ============
// Los datos vienen del endpoint JSON /superadmin/api/metricas,
// el mismo que armamos en la Tarea 3. Nada se recalcula aquí.

// Colores del tema, leídos de las variables CSS del layout
const css = getComputedStyle(document.documentElement);
const COLOR = {
    acento: css.getPropertyValue('--sa-acento').trim(),
    verde:  css.getPropertyValue('--sa-verde').trim(),
    rojo:   css.getPropertyValue('--sa-rojo').trim(),
    suave:  css.getPropertyValue('--sa-suave').trim(),
    borde:  css.getPropertyValue('--sa-borde').trim(),
    azul:   css.getPropertyValue('--sa-azul').trim(),   // azul niebla de la paleta Zubana
};
Chart.defaults.color = COLOR.suave;
Chart.defaults.borderColor = COLOR.borde;
Chart.defaults.font.family = "'Segoe UI', system-ui, sans-serif";

fetch('/superadmin/api/metricas')
    .then(r => r.json())
    .then(m => {
        // ---- 1) Crecimiento de inmobiliarias (línea) ----
        // 'crecimiento' trae registros POR mes; aquí calculamos también
        // el acumulado para ver la curva total del negocio.
        const meses = m.crecimiento.map(x => x.mes);
        const porMes = m.crecimiento.map(x => Number(x.total));
        let suma = 0;
        const acumulado = porMes.map(v => (suma += v));

        new Chart(document.getElementById('graficaCrecimiento'), {
            type: 'line',
            data: {
                labels: meses,
                datasets: [
                    { label: 'Total acumulado', data: acumulado, borderColor: COLOR.acento,
                      backgroundColor: COLOR.acento + '33', fill: true, tension: .3 },
                    { label: 'Nuevas en el mes', data: porMes, borderColor: COLOR.verde,
                      backgroundColor: COLOR.verde, tension: .3 },
                ],
            },
            options: { maintainAspectRatio: false,
                       scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });

        // ---- 2) Propiedades por tipo (dona) ----
        new Chart(document.getElementById('graficaTipos'), {
            type: 'doughnut',
            data: {
                labels: ['Casas / Fincas', 'Apartamentos', 'Locales', 'Lotes'],
                datasets: [{
                    data: [m.porTipo.casas, m.porTipo.apartamentos, m.porTipo.locales, m.porTipo.lotes],
                    backgroundColor: [COLOR.acento, COLOR.verde, COLOR.azul, COLOR.rojo],
                    borderColor: 'transparent',
                }],
            },
            options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
        });

        // ---- 3) Propiedades por inmobiliaria, apiladas por tipo (barras) ----
        // porTenant ya viene ordenado de mayor a menor; tomamos el top 10.
        const top = m.porTenant.slice(0, 10);
        new Chart(document.getElementById('graficaTenants'), {
            type: 'bar',
            data: {
                labels: top.map(t => t.nombre),
                datasets: [
                    { label: 'Casas / Fincas', data: top.map(t => Number(t.casas)),        backgroundColor: COLOR.acento },
                    { label: 'Apartamentos',   data: top.map(t => Number(t.apartamentos)), backgroundColor: COLOR.verde },
                    { label: 'Locales',        data: top.map(t => Number(t.locales)),      backgroundColor: COLOR.azul },
                    { label: 'Lotes',          data: top.map(t => Number(t.lotes)),        backgroundColor: COLOR.rojo },
                ],
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true },
                    y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });
    })
    .catch(err => console.error('No se pudieron cargar las métricas:', err));
</script>
