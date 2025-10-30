<?php
// Construir la query actual SIN 'pagina' para reutilizarla
$queryActual = $_GET;
unset($queryActual['pagina']); // eliminamos 'pagina' para reemplazarla luego

$queryString = http_build_query($queryActual); // convierte array en query string
$queryBase = $queryString ? $queryString . '&' : ''; // si hay otros filtros, agregamos '&'
?>


    <?php
    if ($resultado) {
        $mensaje = mostrarNotificacion(intval($resultado)); 

            if($mensaje) {?>
                <p class="alerta exito"> <?php echo s($mensaje); ?>  </p>
   <?php }
            
        } ?>

<main class="contenedor">
    <div class="superior-inicio">

        <div class="cantidad-propiedades">
            <h2>Propiedades en Venta</h2>

            <?php if (($totalPropiedades ?? 0) > 0): ?>
            <p>
                Mostrando <?= number_format($mostrandoDesde, 0, ',', '.') ?>
                - <?= number_format($mostrandoHasta, 0, ',', '.') ?> Propiedades
            </p>
            <?php else: ?>
            <p>Mostrando 0 Propiedades</p>
            <?php endif; ?>

            <p><?= number_format($masDeDisponibles ?? 0, 0, ',', '.') ?> Propiedades Disponibles</p>
        </div>

        <form method="GET" id="formOrdenar" class="ordenar">
            <!-- Botón responsive (icono + label desktop / solo icono móvil) -->
            <button type="button" class="ordenar__toggle" aria-haspopup="listbox" aria-expanded="false" aria-controls="ordenarMenu">
                <!-- SVG icono “ordenar” -->
                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" class="ordenar__icon">
                <path d="M3 6h14M3 12h10M3 18h6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="ordenar__label">Ordenar por</span>
            </button>

            <!-- Select: fuente de verdad (visible en desktop, oculto en móvil) -->
            <div class="ordenar__selectWrap">
                <select name="ordenar" id="ordenarPor">
                <option value="">Ordenar Por</option>
                <option value="mayor_precio" <?= ($_GET['ordenar'] ?? '') === 'mayor_precio' ? 'selected' : '' ?>>Mayor precio</option>
                <option value="menor_precio" <?= ($_GET['ordenar'] ?? '') === 'menor_precio' ? 'selected' : '' ?>>Menor precio</option>
                <option value="mas_recientes" <?= ($_GET['ordenar'] ?? 'mas_recientes') === 'mas_recientes' ? 'selected' : '' ?>>Más recientes</option>
                <option value="mayor_m2" <?= ($_GET['ordenar'] ?? '') === 'mayor_m2' ? 'selected' : '' ?>>Mayor m²</option>
                </select>
            </div>

            <!-- Menú overlay para tablet/móvil -->
            <div id="ordenarMenu" class="ordenar__menu" role="listbox" tabindex="-1">
                <button type="button" role="option" data-value="mayor_precio"  class="ordenar__opt">Mayor precio</button>
                <button type="button" role="option" data-value="menor_precio"  class="ordenar__opt">Menor precio</button>
                <button type="button" role="option" data-value="mas_recientes" class="ordenar__opt">Más recientes</button>
                <button type="button" role="option" data-value="mayor_m2"     class="ordenar__opt">Mayor m²</button>
            </div>

            <!-- Mantén tus inputs ocultos de filtros existentes -->
            <?php 
                foreach ($_GET as $clave => $valor) {
                if ($clave === 'ordenar' || $clave === 'pagina') continue;
                if (is_array($valor)) {
                    foreach ($valor as $v) {
                    echo '<input type="hidden" name="' . htmlspecialchars($clave, ENT_QUOTES) . '[]"
                            value="' . htmlspecialchars((string)$v, ENT_QUOTES) . '">';
                    }
                } else {
                    echo '<input type="hidden" name="' . htmlspecialchars($clave, ENT_QUOTES) . '"
                        value="' . htmlspecialchars((string)$valor, ENT_QUOTES) . '">';
                }
                }
            ?>
        </form>


    </div>

    <div class="contenedor card-propiedades">
        <?php include 'listado.php'; ?>
    </div>

    <?php
// helper para construir query conservando filtros
$base = '?'.$queryBase;

// Ventana de páginas alrededor de la actual
$window = 2;

// Render cuando hay 1+ páginas
?>
<div class="paginacion" role="navigation" aria-label="Paginación de resultados">
  <?php if ($paginaActual > 1): ?>
    <a href="<?= $base ?>pagina=<?= $paginaActual - 1 ?>" aria-label="Página anterior">&laquo;</a>
  <?php else: ?>
    <span class="disabled" aria-hidden="true">&laquo;</span>
  <?php endif; ?>

  <?php if ($totalPaginas <= 7): ?>
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
      <a class="<?= $i === $paginaActual ? 'pagina-actual' : '' ?>"
         href="<?= $base ?>pagina=<?= $i ?>"
         aria-current="<?= $i === $paginaActual ? 'page' : 'false' ?>">
         <?= $i ?>
      </a>
    <?php endfor; ?>
  <?php else: ?>
    <?php
      // siempre primera
      $pages = [1];

      // rango alrededor de la actual
      $start = max(2, $paginaActual - $window);
      $end   = min($totalPaginas - 1, $paginaActual + $window);

      if ($start > 2) { $pages[] = '...'; }
      for ($i = $start; $i <= $end; $i++) { $pages[] = $i; }
      if ($end < $totalPaginas - 1) { $pages[] = '...'; }

      // siempre última
      $pages[] = $totalPaginas;

      // evita duplicados tipo [1, '...', 1] en casos borde
      $clean = [];
      foreach ($pages as $p) {
        if ($p === '...' && end($clean) === '...') continue;
        if ($p === end($clean)) continue;
        $clean[] = $p;
      }
    ?>

    <?php foreach ($clean as $p): ?>
      <?php if ($p === '...'): ?>
        <span class="ellipsis" aria-hidden="true">…</span>
      <?php else: ?>
        <a class="<?= $p === $paginaActual ? 'pagina-actual' : '' ?>"
           href="<?= $base ?>pagina=<?= $p ?>"
           aria-current="<?= $p === $paginaActual ? 'page' : 'false' ?>">
           <?= $p ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if ($paginaActual < $totalPaginas): ?>
    <a href="<?= $base ?>pagina=<?= $paginaActual + 1 ?>" aria-label="Página siguiente">&raquo;</a>
  <?php else: ?>
    <span class="disabled" aria-hidden="true">&raquo;</span>
  <?php endif; ?>
</div>

</main>
