<?php $modalidades = $modalidades ?? []; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

<main class="sa-contenido" style="max-width: 1400px;">
    <h1>Mapa de propiedades</h1>
    <p style="color: var(--sa-suave); margin-top: .4rem;">
        Todas las propiedades del sistema agrupadas por zona. El tamaño de cada burbuja es la cantidad de propiedades.
    </p>

    <!-- Filtros -->
    <div class="sa-barra" style="margin-top: 1.2rem;">
        <div class="sa-acciones">
            <select id="filtroTipo" class="sa-select">
                <option value="">Todos los tipos</option>
                <option value="casa">Casas / Fincas</option>
                <option value="apartamento">Apartamentos</option>
                <option value="local">Locales</option>
                <option value="lote">Lotes</option>
            </select>
            <select id="filtroModalidad" class="sa-select">
                <option value="">Todas las modalidades</option>
                <?php foreach ($modalidades as $m): ?>
                    <option value="<?php echo htmlspecialchars($m); ?>"><?php echo htmlspecialchars($m); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <span id="resumenMapa" style="color: var(--sa-suave); font-size: .95rem;"></span>
    </div>

    <div class="sa-mapa-grid">
        <!-- El mapa -->
        <div class="sa-card" style="padding: .6rem;">
            <div id="mapa" style="height: 560px; border-radius: 8px;"></div>
        </div>

        <!-- Ranking de zonas -->
        <div class="sa-card" style="padding: .8rem 1.2rem; max-height: 580px; overflow-y: auto;">
            <h3 style="color: var(--sa-suave); font-size: .95rem; font-weight: 500; margin-bottom: .6rem;">Zonas con más propiedades</h3>
            <table class="sa-tabla" id="tablaZonas">
                <thead><tr><th>Zona</th><th class="num">Propiedades</th></tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
// ============ Mapa de Colombia (Tarea 7) ============
// Consume /superadmin/api/mapa (Tarea 6). Al cambiar un filtro se vuelve
// a pedir al servidor y se redibujan las burbujas.

const css = getComputedStyle(document.documentElement);
const ACENTO = css.getPropertyValue('--sa-acento').trim();

// Mapa centrado en Colombia
const mapa = L.map('mapa').setView([4.7, -74.1], 6);

// Fondo oscuro (CARTO) acorde al panel
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; OpenStreetMap &copy; CARTO',
    maxZoom: 18,
}).addTo(mapa);

let capaBurbujas = L.layerGroup().addTo(mapa);

function radioBurbuja(total, maximo) {
    // Escala por raíz cuadrada: el ÁREA de la burbuja es proporcional a
    // la cantidad (una zona con 4x propiedades se ve 2x más ancha)
    return 10 + 34 * Math.sqrt(total / maximo);
}

function cargarMapa() {
    const tipo      = document.getElementById('filtroTipo').value;
    const modalidad = document.getElementById('filtroModalidad').value;
    const params    = new URLSearchParams();
    if (tipo)      params.set('tipo', tipo);
    if (modalidad) params.set('modalidad', modalidad);

    fetch('/superadmin/api/mapa?' + params.toString())
        .then(r => r.json())
        .then(({ zonas }) => {
            capaBurbujas.clearLayers();

            const conCoords  = zonas.filter(z => z.lat !== null);
            const totalProps = zonas.reduce((s, z) => s + z.total, 0);
            const maximo     = Math.max(1, ...conCoords.map(z => z.total));

            document.getElementById('resumenMapa').textContent =
                totalProps + ' propiedades en ' + zonas.length + ' zonas';

            // Burbujas en el mapa
            conCoords.forEach(z => {
                L.circleMarker([z.lat, z.lng], {
                    radius: radioBurbuja(z.total, maximo),
                    color: ACENTO, weight: 1.5,
                    fillColor: ACENTO, fillOpacity: .35,
                })
                .bindPopup(
                    '<strong>' + z.ubicacion + '</strong><br>' +
                    'Total: <strong>' + z.total + '</strong><br>' +
                    'Casas/Fincas: ' + z.casa + '<br>' +
                    'Apartamentos: ' + z.apartamento + '<br>' +
                    'Locales: ' + z.local + '<br>' +
                    'Lotes: ' + z.lote
                )
                .addTo(capaBurbujas);
            });

            // Ranking lateral (clic en una zona → volar a ella)
            const tbody = document.querySelector('#tablaZonas tbody');
            tbody.innerHTML = '';
            zonas.forEach(z => {
                const fila = document.createElement('tr');
                fila.innerHTML =
                    '<td>' + z.ubicacion + (z.lat === null ? ' <small style="color:var(--sa-rojo)">(sin coords)</small>' : '') + '</td>' +
                    '<td class="num"><strong>' + z.total + '</strong></td>';
                if (z.lat !== null) {
                    fila.style.cursor = 'pointer';
                    fila.addEventListener('click', () => mapa.flyTo([z.lat, z.lng], 11));
                }
                tbody.appendChild(fila);
            });
            if (!zonas.length) {
                tbody.innerHTML = '<tr><td colspan="2" style="color:var(--sa-suave)">Sin propiedades con estos filtros.</td></tr>';
            }
        })
        .catch(err => console.error('No se pudo cargar el mapa:', err));
}

document.getElementById('filtroTipo').addEventListener('change', cargarMapa);
document.getElementById('filtroModalidad').addEventListener('change', cargarMapa);
cargarMapa();
</script>
