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
            <p>Mostrando 1 - 26 Propiedades</p>
            <p>Más de 400 Propiedades Disponibles</p>
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

    <div class="paginacion">
        <?php if ($paginaActual > 1): ?>
            <a href="?<?= $queryBase ?>pagina=<?= $paginaActual - 1 ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a class="<?= ($i === $paginaActual) ? 'pagina-actual' : '' ?>" href="?<?= $queryBase ?>pagina=<?= $i ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($paginaActual < $totalPaginas): ?>
            <a href="?<?= $queryBase ?>pagina=<?= $paginaActual + 1 ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>
</main>
