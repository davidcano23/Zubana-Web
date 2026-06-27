<?php
$q            = $q           ?? '';
$estadoFiltro = $estadoFiltro ?? '';
$vista        = $vista        ?? 'pipeline';
$clientes     = $clientes     ?? [];
$porEstado    = $porEstado    ?? [];
$conteo       = $conteo       ?? [];
$totalClientes= $totalClientes?? 0;
$etapas       = $etapas       ?? [];
?>

<main class="crm-page">
<div class="crm-wrap">

    <!-- Cabecera -->
    <div class="crm-header">
        <div>
            <h1>CRM</h1>
            <p class="crm-subtitle">Gestión de clientes y seguimiento de leads</p>
        </div>
        <div class="crm-header-actions">
            <a href="/crm/whatsapp" class="crm-btn-wa" title="Integración WhatsApp">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <a href="/crm/crear" class="crm-btn-new">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo cliente
            </a>
        </div>
    </div>

    <?php if (!empty($exito)): ?>
    <div class="crm-alert crm-alert--ok"><?= htmlspecialchars($exito, ENT_QUOTES) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
    <div class="crm-alert crm-alert--err"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="crm-stats">
        <div class="crm-stat">
            <span class="crm-stat__num"><?= $totalClientes ?></span>
            <span class="crm-stat__lbl">Total clientes</span>
        </div>
        <?php foreach ($etapas as $k => $e): ?>
        <div class="crm-stat crm-stat--stage" style="--stage-color:<?= $e['color'] ?>">
            <span class="crm-stat__num"><?= $conteo[$k] ?? 0 ?></span>
            <span class="crm-stat__lbl"><?= $e['label'] ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Barra de búsqueda + vistas -->
    <div class="crm-toolbar">
        <form method="GET" action="/crm" class="crm-search-form">
            <input type="hidden" name="vista" value="<?= htmlspecialchars($vista, ENT_QUOTES) ?>">
            <div class="crm-search-wrap">
                <svg class="crm-search-ico" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" name="q" placeholder="Buscar por nombre, teléfono o email…"
                       value="<?= htmlspecialchars($q, ENT_QUOTES) ?>" class="crm-search-input" autocomplete="off">
                <?php if ($q): ?>
                <a href="/crm?vista=<?= $vista ?>" class="crm-search-clear" title="Limpiar">✕</a>
                <?php endif; ?>
            </div>
            <div class="crm-stage-filters">
                <a href="/crm?q=<?= urlencode($q) ?>&vista=<?= $vista ?>"
                   class="crm-stage-filter <?= $estadoFiltro === '' ? 'active' : '' ?>">Todos</a>
                <?php foreach ($etapas as $k => $e): ?>
                <a href="/crm?q=<?= urlencode($q) ?>&estado=<?= $k ?>&vista=<?= $vista ?>"
                   class="crm-stage-filter <?= $estadoFiltro === $k ? 'active' : '' ?>"
                   style="--stage-color:<?= $e['color'] ?>">
                    <?= $e['label'] ?>
                </a>
                <?php endforeach; ?>
            </div>
        </form>

        <div class="crm-view-toggle">
            <a href="?q=<?= urlencode($q) ?>&estado=<?= urlencode($estadoFiltro) ?>&vista=pipeline"
               class="crm-view-btn <?= $vista === 'pipeline' ? 'active' : '' ?>" title="Pipeline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="18" rx="1"/><rect x="10" y="3" width="5" height="18" rx="1"/><rect x="17" y="3" width="5" height="18" rx="1"/></svg>
            </a>
            <a href="?q=<?= urlencode($q) ?>&estado=<?= urlencode($estadoFiltro) ?>&vista=lista"
               class="crm-view-btn <?= $vista === 'lista' ? 'active' : '' ?>" title="Lista">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            </a>
        </div>
    </div>

    <!-- ══ VISTA PIPELINE ══ -->
    <?php if ($vista === 'pipeline'): ?>
    <div class="crm-pipeline">
        <?php foreach ($etapas as $key => $etapa):
            $cards = $estadoFiltro === '' ? ($porEstado[$key] ?? []) : ($key === $estadoFiltro ? ($porEstado[$key] ?? []) : []);
            if ($estadoFiltro !== '' && $key !== $estadoFiltro) continue;
        ?>
        <div class="crm-col">
            <div class="crm-col-head" style="--stage-color:<?= $etapa['color'] ?>">
                <span class="crm-col-head__dot"></span>
                <span class="crm-col-head__name"><?= $etapa['label'] ?></span>
                <span class="crm-col-head__count"><?= count($cards) ?></span>
            </div>

            <div class="crm-col-body">
                <?php if (empty($cards)): ?>
                <p class="crm-col-empty">Sin clientes</p>
                <?php else: foreach ($cards as $c): ?>
                <a href="/crm/cliente?id=<?= $c->id ?>" class="crm-card" style="--stage-color:<?= $etapa['color'] ?>">
                    <div class="crm-card__avatar"><?= htmlspecialchars($c->iniciales(), ENT_QUOTES) ?></div>
                    <div class="crm-card__body">
                        <span class="crm-card__name"><?= htmlspecialchars($c->nombreCompleto(), ENT_QUOTES) ?></span>
                        <?php if ($c->telefono): ?>
                        <span class="crm-card__phone"><?= htmlspecialchars($c->telefono, ENT_QUOTES) ?></span>
                        <?php endif; ?>
                        <span class="crm-card__budget"><?= $c->presupuestoFormateado() ?></span>
                        <div class="crm-card__meta">
                            <?php if ($c->tipo_propiedad): ?>
                            <span class="crm-card__tag"><?= htmlspecialchars($c->tipo_propiedad, ENT_QUOTES) ?></span>
                            <?php endif; ?>
                            <span class="crm-card__tag"><?= htmlspecialchars($c->tipo_busqueda, ENT_QUOTES) ?></span>
                        </div>
                    </div>
                    <div class="crm-card__side">
                        <span class="crm-card__source"><?= htmlspecialchars($c->fuente, ENT_QUOTES) ?></span>
                        <?php $dias = $c->diasDesdeActualizacion(); ?>
                        <span class="crm-card__age <?= $dias > 7 ? 'crm-card__age--warn' : '' ?>">
                            <?= $dias === 0 ? 'Hoy' : "{$dias}d" ?>
                        </span>
                    </div>
                </a>
                <?php endforeach; endif; ?>
            </div>

            <a href="/crm/crear?estado=<?= $key ?>" class="crm-col-add">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Agregar
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ══ VISTA LISTA ══ -->
    <?php else: ?>
    <div class="crm-list-wrap">
        <?php if (empty($clientes)): ?>
        <p class="crm-empty">No se encontraron clientes.</p>
        <?php else: ?>
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Presupuesto</th>
                    <th>Fuente</th>
                    <th>Actualizado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $c):
                    $etapa = $etapas[$c->estado] ?? ['label' => $c->estado, 'color' => '#888'];
                    $dias  = $c->diasDesdeActualizacion();
                ?>
                <tr onclick="location.href='/crm/cliente?id=<?= $c->id ?>'" style="cursor:pointer">
                    <td>
                        <div class="crm-table-name">
                            <span class="crm-table-avatar"><?= htmlspecialchars($c->iniciales(), ENT_QUOTES) ?></span>
                            <div>
                                <strong><?= htmlspecialchars($c->nombreCompleto(), ENT_QUOTES) ?></strong>
                                <?php if ($c->email): ?><small><?= htmlspecialchars($c->email, ENT_QUOTES) ?></small><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="crm-badge" style="--stage-color:<?= $etapa['color'] ?>">
                            <?= $etapa['label'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($c->telefono ?: '—', ENT_QUOTES) ?></td>
                    <td><?= htmlspecialchars($c->ciudad ?: '—', ENT_QUOTES) ?></td>
                    <td><?= $c->presupuestoFormateado() ?></td>
                    <td><?= htmlspecialchars($c->fuente, ENT_QUOTES) ?></td>
                    <td class="<?= $dias > 7 ? 'crm-warn' : '' ?>">
                        <?= $dias === 0 ? 'Hoy' : "hace {$dias}d" ?>
                    </td>
                    <td>
                        <a href="/crm/cliente?id=<?= $c->id ?>" class="crm-table-action" onclick="event.stopPropagation()">Ver</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>
</main>
