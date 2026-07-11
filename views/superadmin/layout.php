<?php
    $contenido = $contenido ?? '';
    $titulo    = $titulo ?? 'Superadmin';
    $esSuperadmin = ($_SESSION['superadmin'] ?? false) === true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow"> <!-- panel privado: fuera de buscadores -->
    <title><?php echo htmlspecialchars($titulo); ?> | Zubana Superadmin</title>
    <link rel="icon" href="/img/logo_ZB.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Identidad Zubana: paleta de src/scss/base/_variables.scss ===== */
        :root {
            --sa-bg: #0A0A0A;          /* negro carbón */
            --sa-panel: #1C1C1E;       /* gris grafito */
            --sa-borde: #343434;       /* gris niebla */
            --sa-texto: #F5F1EA;       /* arena claro */
            --sa-suave: #A8A29A;       /* arena atenuado */
            --sa-acento: #C1442E;      /* rojo tierra */
            --sa-acento-hover: #D95941;
            --sa-verde: #2E8B57;       /* verde esmeralda */
            --sa-azul: #A4C8D8;        /* azul niebla */
            --sa-rojo: #E2574C;        /* rojo suavizado para errores */
            --sa-titulos: 'Playfair Display', serif;
            --sa-cuerpo: 'Lato', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--sa-bg); color: var(--sa-texto); font-family: var(--sa-cuerpo); min-height: 100vh; }
        a { color: var(--sa-acento); text-decoration: none; transition: color .2s; }
        h1, h2, .sa-form h1 { font-family: var(--sa-titulos); font-weight: 600; letter-spacing: .02em; }

        /* ===== Header ===== */
        .sa-header { display: flex; justify-content: space-between; align-items: center; padding: 1.1rem 2.4rem;
                     background: linear-gradient(180deg, #161617 0%, var(--sa-panel) 100%);
                     border-bottom: 2px solid var(--sa-acento); }
        .sa-marca-grupo { display: flex; align-items: center; gap: .9rem; }
        .sa-marca-grupo img { height: 42px; width: auto; }
        .sa-header h2 { font-family: var(--sa-titulos); font-size: 1.5rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; }
        .sa-header h2 small { display: block; font-family: var(--sa-cuerpo); font-size: .68rem; font-weight: 300;
                              letter-spacing: .38em; color: var(--sa-suave); text-transform: uppercase; margin-top: .1rem; }
        .sa-nav { display: flex; gap: 2rem; align-items: center; }
        .sa-nav a { color: var(--sa-suave); font-size: .95rem; letter-spacing: .05em; text-transform: uppercase;
                    padding: .4rem 0; border-bottom: 2px solid transparent; }
        .sa-nav a:hover { color: var(--sa-texto); }
        .sa-nav a.activo { color: var(--sa-texto); border-bottom-color: var(--sa-acento); }
        .sa-nav .sa-salir { color: var(--sa-rojo); }

        /* ===== Contenido y tarjetas ===== */
        .sa-contenido { max-width: 1200px; margin: 0 auto; padding: 2.6rem 2.4rem; }
        .sa-contenido > h1 { font-size: 2rem; }
        .sa-card { background: var(--sa-panel); border: 1px solid var(--sa-borde); border-radius: 12px; padding: 2rem;
                   box-shadow: 0 8px 24px rgba(0,0,0,.35); }

        /* ===== Formularios ===== */
        .sa-form { max-width: 400px; margin: 7vh auto 0; padding: 0 1.5rem; }
        .sa-form h1 { font-size: 1.7rem; margin-bottom: 1.6rem; text-align: center; }
        .sa-form label { display: block; font-size: .9rem; color: var(--sa-suave); margin: 1.1rem 0 .35rem;
                         letter-spacing: .08em; text-transform: uppercase; }
        .sa-form input, .sa-form select { width: 100%; padding: .75rem .95rem; border-radius: 8px; border: 1px solid var(--sa-borde);
                          background: var(--sa-bg); color: var(--sa-texto); font-size: 1rem; font-family: var(--sa-cuerpo); }
        .sa-form input:focus, .sa-form select:focus { outline: none; border-color: var(--sa-acento); box-shadow: 0 0 0 3px rgba(193,68,46,.25); }
        .sa-boton { width: 100%; margin-top: 1.8rem; padding: .85rem; border: none; border-radius: 8px;
                    background: var(--sa-acento); color: #fff; font-size: .95rem; font-weight: 700; cursor: pointer;
                    letter-spacing: .12em; text-transform: uppercase; font-family: var(--sa-cuerpo); transition: background .2s; }
        .sa-boton:hover { background: var(--sa-acento-hover); }
        .sa-logo-login { display: block; margin: 0 auto 1.2rem; height: 74px; }

        /* ===== Alertas ===== */
        .sa-alerta { background: rgba(226,87,76,.1); border: 1px solid var(--sa-rojo); color: var(--sa-rojo);
                     border-radius: 8px; padding: .7rem 1rem; margin-bottom: .6rem; font-size: .95rem; }
        .sa-alerta-exito { background: rgba(46,139,87,.12); border: 1px solid var(--sa-verde); color: #6FBF92;
                           border-radius: 8px; padding: .7rem 1rem; margin-bottom: 1.2rem; font-size: .95rem; }

        /* ===== Métricas ===== */
        .sa-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.4rem; margin-top: 1.8rem; }
        .sa-metrica { border-top: 3px solid var(--sa-acento); }
        .sa-metrica h3 { font-size: .82rem; color: var(--sa-suave); font-weight: 400; letter-spacing: .12em; text-transform: uppercase; }
        .sa-metrica p { font-size: 2.4rem; font-weight: 300; margin-top: .5rem; font-family: var(--sa-titulos); }

        /* ===== Secciones y tablas ===== */
        .sa-seccion { margin-top: 2.8rem; }
        .sa-seccion h2 { font-size: 1.35rem; margin-bottom: 1rem; }
        .sa-tabla { width: 100%; border-collapse: collapse; font-size: .95rem; }
        .sa-tabla th { text-align: left; color: var(--sa-suave); font-weight: 400; font-size: .8rem;
                       letter-spacing: .1em; text-transform: uppercase; padding: .7rem .8rem; border-bottom: 1px solid var(--sa-borde); }
        .sa-tabla td { padding: .7rem .8rem; border-bottom: 1px solid rgba(52,52,52,.5); }
        .sa-tabla tbody tr { transition: background .15s; }
        .sa-tabla tbody tr:hover { background: rgba(245,241,234,.03); }
        .sa-tabla tr:last-child td { border-bottom: none; }
        .sa-tabla .num { text-align: right; font-variant-numeric: tabular-nums; }
        .sa-badge { display: inline-block; padding: .18rem .75rem; border-radius: 20px; font-size: .78rem; letter-spacing: .05em; }
        .sa-badge.activo { background: rgba(46,139,87,.16); color: #6FBF92; border: 1px solid rgba(46,139,87,.4); }
        .sa-badge.prueba { background: rgba(164,200,216,.12); color: var(--sa-azul); border: 1px solid rgba(164,200,216,.35); }
        .sa-badge.suspendido { background: rgba(226,87,76,.12); color: var(--sa-rojo); border: 1px solid rgba(226,87,76,.4); }

        /* ===== Barra de acciones, buscador, botones secundarios ===== */
        .sa-barra { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.2rem; flex-wrap: wrap; }
        .sa-buscar { flex: 1; max-width: 320px; padding: .6rem .95rem; border-radius: 8px; border: 1px solid var(--sa-borde);
                     background: var(--sa-panel); color: var(--sa-texto); font-size: .95rem; font-family: var(--sa-cuerpo); }
        .sa-buscar:focus { outline: none; border-color: var(--sa-acento); box-shadow: 0 0 0 3px rgba(193,68,46,.25); }
        .sa-boton-sec { display: inline-block; padding: .6rem 1.2rem; border-radius: 8px; background: var(--sa-acento);
                        color: #fff; font-size: .85rem; font-weight: 700; border: none; cursor: pointer;
                        letter-spacing: .1em; text-transform: uppercase; transition: background .2s; }
        .sa-boton-sec:hover { background: var(--sa-acento-hover); color: #fff; }
        .sa-acciones { display: flex; gap: .4rem; align-items: center; }
        .sa-select { padding: .4rem .55rem; border-radius: 6px; border: 1px solid var(--sa-borde); background: var(--sa-bg);
                     color: var(--sa-texto); font-size: .85rem; font-family: var(--sa-cuerpo); }
        .sa-boton-mini { padding: .38rem .85rem; border-radius: 6px; border: 1px solid var(--sa-acento); background: transparent;
                         color: var(--sa-acento); font-size: .82rem; cursor: pointer; letter-spacing: .04em; transition: all .2s; }
        .sa-boton-mini:hover { background: var(--sa-acento); color: #fff; }

        /* ===== Gráficas y mapa ===== */
        .sa-graficas { display: grid; grid-template-columns: 2fr 1fr; gap: 1.4rem; }
        .sa-graficas .ancho-completo { grid-column: 1 / -1; }
        .sa-grafica { position: relative; height: 300px; }
        .sa-mapa-grid { display: grid; grid-template-columns: 2.2fr 1fr; gap: 1.4rem; margin-top: .4rem; }
        @media (max-width: 900px) {
            .sa-graficas, .sa-mapa-grid { grid-template-columns: 1fr; }
            .sa-header { flex-direction: column; gap: 1rem; }
        }
    </style>
</head>
<body>
    <?php if ($esSuperadmin): ?>
    <?php $rutaNav = $_SERVER['PATH_INFO'] ?? '/superadmin'; ?>
    <header class="sa-header">
        <div class="sa-marca-grupo">
            <img src="/img/logo_ZB.png" alt="Zubana">
            <h2>Zubana <small>Panel Superadmin</small></h2>
        </div>
        <nav class="sa-nav">
            <a href="/superadmin" class="<?php echo $rutaNav === '/superadmin' ? 'activo' : ''; ?>">Dashboard</a>
            <a href="/superadmin/inmobiliarias" class="<?php echo strpos($rutaNav, '/superadmin/inmobiliarias') === 0 ? 'activo' : ''; ?>">Inmobiliarias</a>
            <a href="/superadmin/mapa" class="<?php echo $rutaNav === '/superadmin/mapa' ? 'activo' : ''; ?>">Mapa</a>
            <a href="/superadmin/logout" class="sa-salir">Salir (<?php echo htmlspecialchars($_SESSION['superadmin_nombre'] ?? ''); ?>)</a>
        </nav>
    </header>
    <?php endif; ?>

    <?php echo $contenido; ?>
</body>
</html>
