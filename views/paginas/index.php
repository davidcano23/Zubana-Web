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

        <form method="GET" id="formOrdenar">
            <div class="filtrar">
                <select name="ordenar" id="ordenarPor" onchange="document.getElementById('formOrdenar').submit();">
                    <option value="">Ordenar Por</option>
                    <option value="mayor_precio" <?= ($_GET['ordenar'] ?? '') === 'mayor_precio' ? 'selected' : '' ?>>Mayor precio</option>
                    <option value="menor_precio" <?= ($_GET['ordenar'] ?? '') === 'menor_precio' ? 'selected' : '' ?>>Menor precio</option>
                    <option value="menor_m2" <?= ($_GET['ordenar'] ?? '') === 'menor_m2' ? 'selected' : '' ?>>Menor m²</option>
                    <option value="mayor_m2" <?= ($_GET['ordenar'] ?? '') === 'mayor_m2' ? 'selected' : '' ?>>Mayor m²</option>
                </select>

                <?php 
                    // Mantenemos los otros filtros ocultos (soporta arrays tipo tipo[])
                    foreach ($_GET as $clave => $valor) {
                        if ($clave === 'ordenar' || $clave === 'pagina') continue;

                        if (is_array($valor)) {
                            // Ej: tipo[] = ['casa','apartamento']
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

            </div>
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
