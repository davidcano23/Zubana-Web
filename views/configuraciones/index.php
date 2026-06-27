<?php
$s               = $settings      ?? [];
$titulosOpciones = $titulosOpciones ?? [];
$cuerpoOpciones  = $cuerpoOpciones  ?? [];

$logoPrincipalActual  = !empty($s['logo_principal'])  ? $s['logo_principal']  : '/img/logo_ZB.png';
$logoSecundarioActual = !empty($s['logo_secundario']) ? $s['logo_secundario'] : '/img/logo_header_horizontal.png';

$isTitCustom   = !in_array($s['fuente_titulos'], $titulosOpciones, true);
$isCuerpoCustom = !in_array($s['fuente_cuerpo'], $cuerpoOpciones,  true);

// Plantillas predefinidas
$plantillas = [
    'zubana' => [
        'nombre'         => 'Zubana',
        'descripcion'    => 'Tema original del sistema',
        'color_fondo'    => '#1C1C1E',
        'color_header'   => '#343434',
        'color_texto'    => '#F5F1EA',
        'color_acento'   => '#C1442E',
        'color_filtros'  => '#1C1C1E',
        'fuente_titulos' => 'Playfair Display',
        'fuente_cuerpo'  => 'Lato',
    ],
    'clara'  => [
        'nombre'         => 'Clara',
        'descripcion'    => 'Tema claro y luminoso',
        'color_fondo'    => '#F7F4EE',
        'color_header'   => '#FFFFFF',
        'color_texto'    => '#1C1A17',
        'color_acento'   => '#2B6CB0',
        'color_filtros'  => '#EDE9E0',
        'fuente_titulos' => 'Lora',
        'fuente_cuerpo'  => 'Inter',
    ],
    'oscura' => [
        'nombre'         => 'Noche',
        'descripcion'    => 'Tema oscuro moderno',
        'color_fondo'    => '#0D0D11',
        'color_header'   => '#16161F',
        'color_texto'    => '#E2DDD6',
        'color_acento'   => '#7C5CBF',
        'color_filtros'  => '#0D0D11',
        'fuente_titulos' => 'Cinzel',
        'fuente_cuerpo'  => 'DM Sans',
    ],
];
?>

<main class="config-page">
<div class="config-container">

    <h1>Configuraciones</h1>
    <p class="config-subtitle">Personaliza la apariencia del sitio</p>

    <?php if (!empty($exito)): ?>
    <div class="config-alert config-alert--success">
        <?= htmlspecialchars($exito, ENT_QUOTES) ?>
    </div>
    <?php endif; ?>

    <!-- ══ Plantillas ══ -->
    <section class="config-section config-section--plantillas">
        <h3>Plantillas</h3>
        <div class="tmpl-grid">
            <?php foreach ($plantillas as $key => $p): ?>
            <button type="button" class="tmpl-btn" data-template="<?= $key ?>"
                    data-values='<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>'>
                <div class="tmpl-swatches">
                    <div class="tmpl-swatch" style="background:<?= $p['color_header'] ?>">
                        <span class="tmpl-swatch__dot" style="background:<?= $p['color_acento'] ?>"></span>
                    </div>
                    <div class="tmpl-body-preview" style="background:<?= $p['color_fondo'] ?>">
                        <div class="tmpl-line tmpl-line--title"
                             style="background:<?= $p['color_texto'] ?>; opacity:.85"></div>
                        <div class="tmpl-line"
                             style="background:<?= $p['color_texto'] ?>; opacity:.4"></div>
                        <div class="tmpl-chip"
                             style="background:<?= $p['color_filtros'] ?>; border-color:<?= $p['color_texto'] ?>22"></div>
                        <div class="tmpl-line tmpl-line--btn"
                             style="background:<?= $p['color_acento'] ?>"></div>
                    </div>
                </div>
                <span class="tmpl-name"><?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?></span>
                <span class="tmpl-desc"><?= htmlspecialchars($p['descripcion'], ENT_QUOTES) ?></span>
            </button>
            <?php endforeach; ?>
        </div>
    </section>

    <div class="config-layout">

        <!-- ══ Formulario ══ -->
        <form method="POST" action="/configuraciones/guardar"
              enctype="multipart/form-data" class="config-form">

            <!-- Colores -->
            <section class="config-section">
                <h3>Colores</h3>
                <div class="config-grid">

                    <div class="config-field">
                        <label for="color_fondo">Fondo del sitio</label>
                        <div class="color-input-wrap">
                            <input type="color" id="color_fondo" name="color_fondo"
                                   value="<?= htmlspecialchars($s['color_fondo'], ENT_QUOTES) ?>">
                            <span class="color-value"><?= htmlspecialchars($s['color_fondo'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                    <div class="config-field">
                        <label for="color_header">Fondo del header</label>
                        <div class="color-input-wrap">
                            <input type="color" id="color_header" name="color_header"
                                   value="<?= htmlspecialchars($s['color_header'], ENT_QUOTES) ?>">
                            <span class="color-value"><?= htmlspecialchars($s['color_header'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                    <div class="config-field">
                        <label for="color_texto">Color del texto</label>
                        <div class="color-input-wrap">
                            <input type="color" id="color_texto" name="color_texto"
                                   value="<?= htmlspecialchars($s['color_texto'], ENT_QUOTES) ?>">
                            <span class="color-value"><?= htmlspecialchars($s['color_texto'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                    <div class="config-field">
                        <label for="color_acento">Color de acento <span class="config-hint">(botones)</span></label>
                        <div class="color-input-wrap">
                            <input type="color" id="color_acento" name="color_acento"
                                   value="<?= htmlspecialchars($s['color_acento'], ENT_QUOTES) ?>">
                            <span class="color-value"><?= htmlspecialchars($s['color_acento'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                    <div class="config-field">
                        <label for="color_filtros">Fondo de filtros <span class="config-hint">(cajas de búsqueda)</span></label>
                        <div class="color-input-wrap">
                            <input type="color" id="color_filtros" name="color_filtros"
                                   value="<?= htmlspecialchars($s['color_filtros'], ENT_QUOTES) ?>">
                            <span class="color-value"><?= htmlspecialchars($s['color_filtros'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Tipografía -->
            <section class="config-section">
                <h3>Tipografía</h3>

                <div class="config-font-group">
                    <div class="config-field">
                        <label for="sel_fuente_titulos">Fuente de títulos</label>
                        <select id="sel_fuente_titulos" name="fuente_titulos" class="config-select">
                            <optgroup label="Serif / Display">
                                <?php foreach (array_slice($titulosOpciones, 0, 8) as $font): ?>
                                <option value="<?= htmlspecialchars($font, ENT_QUOTES) ?>"
                                        <?= ($s['fuente_titulos'] === $font && !$isTitCustom) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($font, ENT_QUOTES) ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Sans-serif">
                                <?php foreach (array_slice($titulosOpciones, 8) as $font): ?>
                                <option value="<?= htmlspecialchars($font, ENT_QUOTES) ?>"
                                        <?= ($s['fuente_titulos'] === $font && !$isTitCustom) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($font, ENT_QUOTES) ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <option value="__custom__" <?= $isTitCustom ? 'selected' : '' ?>>
                                Personalizada…
                            </option>
                        </select>
                    </div>

                    <div class="config-custom-font" id="customTitulosWrap"
                         style="<?= $isTitCustom ? '' : 'display:none' ?>">
                        <div class="config-field">
                            <label for="inp_fuente_titulos">Nombre exacto de la fuente</label>
                            <input type="text" id="inp_fuente_titulos"
                                   name="<?= $isTitCustom ? 'fuente_titulos' : '' ?>"
                                   class="config-text-input"
                                   placeholder="Ej: Dancing Script"
                                   value="<?= $isTitCustom ? htmlspecialchars($s['fuente_titulos'], ENT_QUOTES) : '' ?>">
                        </div>
                        <div class="config-field">
                            <label for="fuente_titulos_url">URL de Google Fonts</label>
                            <input type="url" id="fuente_titulos_url" name="fuente_titulos_url"
                                   class="config-text-input"
                                   placeholder="https://fonts.googleapis.com/css2?family=…"
                                   value="<?= htmlspecialchars($s['fuente_titulos_url'] ?? '', ENT_QUOTES) ?>">
                            <p class="config-url-hint">Copia la URL del &lt;link&gt; de <strong>fonts.google.com</strong></p>
                        </div>
                    </div>
                </div>

                <div class="config-font-group" style="margin-top:1.6rem">
                    <div class="config-field">
                        <label for="sel_fuente_cuerpo">Fuente del texto</label>
                        <select id="sel_fuente_cuerpo" name="fuente_cuerpo" class="config-select">
                            <optgroup label="Populares">
                                <?php foreach (array_slice($cuerpoOpciones, 0, 5) as $font): ?>
                                <option value="<?= htmlspecialchars($font, ENT_QUOTES) ?>"
                                        <?= ($s['fuente_cuerpo'] === $font && !$isCuerpoCustom) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($font, ENT_QUOTES) ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="Modernos">
                                <?php foreach (array_slice($cuerpoOpciones, 5) as $font): ?>
                                <option value="<?= htmlspecialchars($font, ENT_QUOTES) ?>"
                                        <?= ($s['fuente_cuerpo'] === $font && !$isCuerpoCustom) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($font, ENT_QUOTES) ?>
                                </option>
                                <?php endforeach; ?>
                            </optgroup>
                            <option value="__custom__" <?= $isCuerpoCustom ? 'selected' : '' ?>>
                                Personalizada…
                            </option>
                        </select>
                    </div>

                    <div class="config-custom-font" id="customCuerpoWrap"
                         style="<?= $isCuerpoCustom ? '' : 'display:none' ?>">
                        <div class="config-field">
                            <label for="inp_fuente_cuerpo">Nombre exacto de la fuente</label>
                            <input type="text" id="inp_fuente_cuerpo"
                                   name="<?= $isCuerpoCustom ? 'fuente_cuerpo' : '' ?>"
                                   class="config-text-input"
                                   placeholder="Ej: Nunito Sans"
                                   value="<?= $isCuerpoCustom ? htmlspecialchars($s['fuente_cuerpo'], ENT_QUOTES) : '' ?>">
                        </div>
                        <div class="config-field">
                            <label for="fuente_cuerpo_url">URL de Google Fonts</label>
                            <input type="url" id="fuente_cuerpo_url" name="fuente_cuerpo_url"
                                   class="config-text-input"
                                   placeholder="https://fonts.googleapis.com/css2?family=…"
                                   value="<?= htmlspecialchars($s['fuente_cuerpo_url'] ?? '', ENT_QUOTES) ?>">
                            <p class="config-url-hint">Copia la URL del &lt;link&gt; de <strong>fonts.google.com</strong></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Logos -->
            <section class="config-section">
                <h3>Logos</h3>
                <p class="config-url-hint" style="margin-bottom:1.4rem">El logo principal se usa en móvil y en el footer. El logo horizontal se usa en escritorio.</p>
                <div class="config-grid">

                    <div class="config-field">
                        <label>Logo principal <span class="config-hint">(móvil · footer)</span></label>
                        <div class="logo-upload-wrap">
                            <div class="logo-current-preview" id="previewLogoPrincipal">
                                <img src="<?= htmlspecialchars($logoPrincipalActual, ENT_QUOTES) ?>" alt="Logo actual">
                            </div>
                            <label class="logo-upload-btn" for="logo_principal_input">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Subir imagen
                            </label>
                            <input type="file" id="logo_principal_input" name="logo_principal"
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                   class="logo-file-input">
                            <p class="config-url-hint">PNG, JPG, WebP o SVG</p>
                        </div>
                    </div>

                    <div class="config-field">
                        <label>Logo horizontal <span class="config-hint">(escritorio · header)</span></label>
                        <div class="logo-upload-wrap">
                            <div class="logo-current-preview logo-current-preview--wide" id="previewLogoSecundario">
                                <img src="<?= htmlspecialchars($logoSecundarioActual, ENT_QUOTES) ?>" alt="Logo horizontal actual">
                            </div>
                            <label class="logo-upload-btn" for="logo_secundario_input">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Subir imagen
                            </label>
                            <input type="file" id="logo_secundario_input" name="logo_secundario"
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                   class="logo-file-input">
                            <p class="config-url-hint">PNG, JPG, WebP o SVG — formato horizontal</p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Acciones -->
            <div class="config-actions">
                <button type="submit" class="btn-config-save">Guardar cambios</button>
                <button type="button" class="btn-config-reset" id="btnReset">Restaurar predeterminados</button>
            </div>

        </form>

        <!-- ══ Vista previa en tiempo real ══ -->
        <div class="config-preview-panel">

            <div class="config-preview-card" id="livePreview">

                <!-- Header simulado -->
                <div class="preview-header" id="pvHeader">
                    <div class="preview-header-inner">
                        <span class="preview-logo" id="pvLogo">Zubana</span>
                        <span class="preview-nav-dot" id="pvNavDot"></span>
                    </div>
                </div>

                <!-- Barra de búsqueda simulada -->
                <div class="preview-search-area" id="pvSearchArea">
                    <div class="preview-search-bar" id="pvSearchBar">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="pv-search-ico"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        <span class="pv-search-placeholder" id="pvSearchPlaceholder">Buscar por ubicación…</span>
                    </div>
                    <div class="preview-chips" id="pvChips">
                        <span class="preview-chip" id="pvChip1">Tipo</span>
                        <span class="preview-chip preview-chip--active" id="pvChipActive">Precio</span>
                        <span class="preview-chip" id="pvChip2">Filtros</span>
                    </div>
                </div>

                <!-- Cuerpo simulado -->
                <div class="preview-body" id="pvBody">
                    <h2 class="preview-title" id="pvTitle">Casa en el Oriente</h2>
                    <p class="preview-text" id="pvText">Hermosa propiedad con vista panorámica y acabados de primera calidad.</p>
                    <div class="preview-footer-row">
                        <button class="preview-btn" id="pvBtn">Ver propiedad</button>
                        <span class="preview-badge" id="pvBadge">Destacado</span>
                    </div>
                </div>

            </div>
            <p class="preview-label">Vista previa en tiempo real</p>
        </div>

    </div>
</div>
</main>

<!-- Form oculto para reset -->
<form action="/configuraciones/reset" method="POST" id="formReset" style="display:none"></form>

<script>
(function () {
    /* ── Datos de plantillas ── */
    const PLANTILLAS = <?= json_encode($plantillas) ?>;

    /* ── Inputs de color ── */
    const C = {
        fondo:   document.getElementById('color_fondo'),
        header:  document.getElementById('color_header'),
        texto:   document.getElementById('color_texto'),
        acento:  document.getElementById('color_acento'),
        filtros: document.getElementById('color_filtros'),
    };

    /* ── Inputs de fuente ── */
    const selTit    = document.getElementById('sel_fuente_titulos');
    const selCuerpo = document.getElementById('sel_fuente_cuerpo');
    const wrapTit   = document.getElementById('customTitulosWrap');
    const wrapCuerpo= document.getElementById('customCuerpoWrap');
    const inpTit    = document.getElementById('inp_fuente_titulos');
    const inpCuerpo = document.getElementById('inp_fuente_cuerpo');

    /* ── Elementos del preview ── */
    const pv = {
        header:          document.getElementById('pvHeader'),
        logo:            document.getElementById('pvLogo'),
        navDot:          document.getElementById('pvNavDot'),
        searchArea:      document.getElementById('pvSearchArea'),
        searchBar:       document.getElementById('pvSearchBar'),
        searchPh:        document.getElementById('pvSearchPlaceholder'),
        chips:           document.getElementById('pvChips'),
        chip1:           document.getElementById('pvChip1'),
        chipActive:      document.getElementById('pvChipActive'),
        chip2:           document.getElementById('pvChip2'),
        body:            document.getElementById('pvBody'),
        title:           document.getElementById('pvTitle'),
        text:            document.getElementById('pvText'),
        btn:             document.getElementById('pvBtn'),
        badge:           document.getElementById('pvBadge'),
    };

    /* ── Carga dinámica de Google Font ── */
    function loadFont(family, url) {
        if (!family) return;
        const safe = ['Playfair Display','Roboto','Lato'];
        if (safe.includes(family)) return;
        const id = 'gf-' + family.replace(/\s+/g, '-').toLowerCase();
        if (document.getElementById(id)) return;
        const href = url || 'https://fonts.googleapis.com/css2?family='
                          + encodeURIComponent(family).replace(/%20/g, '+')
                          + ':wght@300;400;700&display=swap';
        const link = Object.assign(document.createElement('link'), { id, rel:'stylesheet', href });
        document.head.appendChild(link);
    }

    /* ── Fuente activa según select / input custom ── */
    function activeFont(sel, inp) {
        return sel.value === '__custom__' ? (inp ? inp.value.trim() : '') : sel.value;
    }

    /* ── Aplica un color con opacidad al estilo ── */
    function hex2rgba(hex, a) {
        const r = parseInt(hex.slice(1,3),16);
        const g = parseInt(hex.slice(3,5),16);
        const b = parseInt(hex.slice(5,7),16);
        return `rgba(${r},${g},${b},${a})`;
    }

    /* ── Actualiza el preview ── */
    function updatePreview() {
        const fondo  = C.fondo.value;
        const header = C.header.value;
        const texto  = C.texto.value;
        const acento = C.acento.value;
        const filtros= C.filtros.value;
        const fTit   = activeFont(selTit,    inpTit);
        const fCuerpo= activeFont(selCuerpo, inpCuerpo);
        const urlTit  = document.getElementById('fuente_titulos_url')?.value || '';
        const urlCuerpo= document.getElementById('fuente_cuerpo_url')?.value  || '';

        loadFont(fTit,    urlTit   || null);
        loadFont(fCuerpo, urlCuerpo|| null);

        /* Header */
        pv.header.style.backgroundColor = header;
        pv.logo.style.color             = texto;
        pv.logo.style.fontFamily        = fTit ? `"${fTit}", serif` : '';
        pv.navDot.style.backgroundColor = acento;

        /* Barra búsqueda */
        pv.searchArea.style.backgroundColor = header;
        pv.searchBar.style.backgroundColor  = filtros;
        pv.searchBar.style.borderColor      = hex2rgba(texto, 0.18);
        pv.searchPh.style.color             = hex2rgba(texto, 0.45);
        pv.searchBar.querySelector('.pv-search-ico').style.stroke = hex2rgba(texto, 0.4);

        [pv.chip1, pv.chip2].forEach(ch => {
            ch.style.backgroundColor = filtros;
            ch.style.borderColor     = hex2rgba(texto, 0.15);
            ch.style.color           = hex2rgba(texto, 0.7);
        });
        pv.chipActive.style.backgroundColor = acento;
        pv.chipActive.style.borderColor     = acento;
        pv.chipActive.style.color           = '#fff';

        /* Cuerpo */
        pv.body.style.backgroundColor = fondo;
        pv.title.style.color          = texto;
        pv.title.style.fontFamily     = fTit    ? `"${fTit}", serif`     : '';
        pv.text.style.color           = hex2rgba(texto, 0.75);
        pv.text.style.fontFamily      = fCuerpo ? `"${fCuerpo}", sans-serif` : '';
        pv.btn.style.color            = '#fff';
        pv.btn.style.backgroundColor  = acento;
        pv.badge.style.backgroundColor= hex2rgba(acento, 0.2);
        pv.badge.style.color          = acento;
    }

    /* ── Actualiza spans hex ── */
    document.querySelectorAll('.color-input-wrap input[type="color"]').forEach(inp => {
        inp.addEventListener('input', function () {
            this.closest('.color-input-wrap').querySelector('.color-value').textContent = this.value;
        });
    });

    /* ── Muestra/oculta campos personalizados ── */
    function toggleCustom(sel, wrap, inp, nameKey) {
        const custom = sel.value === '__custom__';
        wrap.style.display = custom ? 'block' : 'none';
        if (inp) {
            inp.name     = custom ? nameKey   : '';
            inp.disabled = !custom;
        }
        if (!custom) sel.name = nameKey; else sel.name = '';
    }

    selTit.addEventListener('change', () => {
        toggleCustom(selTit,    wrapTit,    inpTit,    'fuente_titulos');
        updatePreview();
    });
    selCuerpo.addEventListener('change', () => {
        toggleCustom(selCuerpo, wrapCuerpo, inpCuerpo, 'fuente_cuerpo');
        updatePreview();
    });

    Object.values(C).forEach(el => el.addEventListener('input', updatePreview));
    [inpTit, inpCuerpo].forEach(el => { if (el) el.addEventListener('input', updatePreview); });

    /* ── Plantillas ── */
    document.querySelectorAll('.tmpl-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const p = JSON.parse(this.dataset.values);

            C.fondo.value   = p.color_fondo;
            C.header.value  = p.color_header;
            C.texto.value   = p.color_texto;
            C.acento.value  = p.color_acento;
            C.filtros.value = p.color_filtros;

            // Actualiza spans hex
            Object.entries(C).forEach(([k, el]) => {
                const map = { fondo:'color_fondo', header:'color_header', texto:'color_texto', acento:'color_acento', filtros:'color_filtros' };
                const wrap = document.querySelector(`.color-input-wrap input#${map[k]}`)?.closest('.color-input-wrap');
                if (wrap) wrap.querySelector('.color-value').textContent = el.value;
            });

            // Fuentes: si el select tiene la opción, seleccionarla
            function setFont(sel, wrap, inp, nameKey, fontName) {
                const opt = [...sel.options].find(o => o.value === fontName);
                if (opt) {
                    sel.value = fontName;
                    sel.name  = nameKey;
                    wrap.style.display = 'none';
                    if (inp) { inp.disabled = true; inp.name = ''; }
                } else {
                    sel.value = '__custom__';
                    sel.name  = '';
                    wrap.style.display = 'block';
                    if (inp) { inp.value = fontName; inp.disabled = false; inp.name = nameKey; }
                }
            }
            setFont(selTit,    wrapTit,    inpTit,    'fuente_titulos', p.fuente_titulos);
            setFont(selCuerpo, wrapCuerpo, inpCuerpo, 'fuente_cuerpo',  p.fuente_cuerpo);

            updatePreview();
        });
    });

    /* ── Preview de logo al seleccionar archivo ── */
    function wireLogoPreview(inputId, previewId) {
        const inp  = document.getElementById(inputId);
        const prev = document.getElementById(previewId);
        if (!inp || !prev) return;
        inp.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            const reader = new FileReader();
            reader.onload = e => { prev.querySelector('img').src = e.target.result; };
            reader.readAsDataURL(this.files[0]);
        });
    }
    wireLogoPreview('logo_principal_input',  'previewLogoPrincipal');
    wireLogoPreview('logo_secundario_input', 'previewLogoSecundario');

    /* ── Reset ── */
    document.getElementById('btnReset').addEventListener('click', () => {
        if (confirm('¿Restaurar todos los ajustes a los valores predeterminados?')) {
            document.getElementById('formReset').submit();
        }
    });

    /* ── Init ── */
    toggleCustom(selTit,    wrapTit,    inpTit,    'fuente_titulos');
    toggleCustom(selCuerpo, wrapCuerpo, inpCuerpo, 'fuente_cuerpo');
    updatePreview();
})();
</script>
