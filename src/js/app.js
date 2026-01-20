// ------------------------- IMPORTACIONES -------------------------
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

// ------------------------- INICIALIZACIÓN -------------------------
document.addEventListener('DOMContentLoaded', function () {
  initGestorFiltrosDesktop(); 
  initFiltrosResponsive();
  mapDinamicoComoFincaRaiz();
  
  // 🔥 ESTE ES EL ARREGLO CLAVE PARA MÓVIL
  initHackSafari();

  initBuscadorPorTipo();
  initFiltroPrecio();
  initFiltroHB();
  initMasFiltros();

  initPrecioMiles();
  buscadorUbicacion();
  initOrdenar();
  
  confirmarEliminacionPropiedad();
  initSwiperRecomendados();
  initGalerias();
  initLoginModalSubmit();
  initLoginModal();

  const imagenPrincipal = document.getElementById('imagen');
  const imagenesExtras = document.getElementById('imagenes');

  if (imagenPrincipal) {
      imagenPrincipal.addEventListener('change', function () {
          convertirHEIC(this);
      });
  }

  if (imagenesExtras) {
      imagenesExtras.addEventListener('change', function () {
          convertirHEIC(this);
      });
  }
});


function convertirHEIC(input) {
    // Seguridad: si no existe el input o no hay archivos
    if (!input || !input.files || input.files.length === 0) {
        return;
    }

    const archivos = Array.from(input.files);
    const archivosConvertidos = [];

    let requiereConversion = false;

    archivos.forEach(file => {
        if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')) {
            requiereConversion = true;
        }
    });

    // Si no hay HEIC, no hacemos nada
    if (!requiereConversion) {
        return;
    }

    Promise.all(
        archivos.map(file => {
            if (file.type === 'image/heic' || file.name.toLowerCase().endsWith('.heic')) {
                return heic2any({
                    blob: file,
                    toType: "image/jpeg",
                    quality: 0.9
                }).then(converted => {
                    return new File(
                        [converted],
                        file.name.replace(/\.heic$/i, '.jpg'),
                        { type: 'image/jpeg' }
                    );
                });
            } else {
                return file;
            }
        })
    ).then(filesFinales => {
        const dataTransfer = new DataTransfer();
        filesFinales.forEach(f => dataTransfer.items.add(f));
        input.files = dataTransfer.files;
    }).catch(error => {
        console.error('Error convirtiendo HEIC:', error);
        alert('Error al convertir imagen HEIC');
    });
  }



function mapDinamicoComoFincaRaiz() {
  // 1. Obtener coordenadas iniciales (Si editamos, vienen de PHP. Si es nuevo, usamos default)
    // Default: Medellín (6.24, -75.57). Cámbialo a tu ciudad principal.
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    if(!latInput | !lngInput) return;
    
    // Convertimos el valor a número
    let latVal = parseFloat(latInput.value);
    let lngVal = parseFloat(lngInput.value);

    // CORRECCIÓN: Si el valor es 0 o no existe, usamos Rionegro.
    // Si es un número real diferente de 0, usamos ese.
    let lat = (latVal && latVal !== 0) ? latVal : 6.1551; 
    let lng = (lngVal && lngVal !== 0) ? lngVal : -75.3737;
    
    let zoomLevel = (latVal && latVal !== 0) ? 16 : 13; // Menos zoom si es la ubicación por defecto

    // 2. Inicializar el mapa
    const map = L.map('mapa-formulario').setView([lat, lng], zoomLevel);

    // 3. Cargar las capas de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // 4. Agregar el PIN arrastrable (Draggable)
    const marker = L.marker([lat, lng], {
        draggable: true, // ¡Importante! Permite moverlo
        autoPan: true
    }).addTo(map);

    // Función para llenar los inputs ocultos cuando cargue la página por primera vez
    // Solo si estamos creando una nueva propiedad para que no vaya vacía
    if(!latInput.value) {
        latInput.value = lat;
        lngInput.value = lng;
    }

    // 5. EVENTO: Detectar cuando el usuario mueve el pin
    marker.on('moveend', function(e) {
        const position = marker.getLatLng();
        
        // Asignar los nuevos valores a los inputs ocultos
        latInput.value = position.lat;
        lngInput.value = position.lng;
        
        // Opcional: Centrar el mapa en el nuevo punto
        map.panTo(new L.LatLng(position.lat, position.lng));
        
        console.log("Nueva ubicación guardada:", position.lat, position.lng);
    });
  }

// ==========================================================================
// 1. GESTOR FILTROS DESKTOP
// ==========================================================================
function initGestorFiltrosDesktop() {
  const filtros = [
    { trigger: '.tipo_trigger',   root: '.filtro_tipo' },
    { trigger: '.precio_trigger', root: '.filtro_precio' },
    { trigger: '.hb_trigger',     root: '.filtro_hb' },
    { trigger: '.mas_trigger',    root: '.filtro_mas' } 
  ];

  document.addEventListener('click', (e) => {
    if (window.matchMedia('(max-width: 1024px)').matches) return;

    const target = e.target;
    const config = filtros.find(f => target.closest(f.trigger));

    if (config) {
      e.preventDefault();
      e.stopPropagation();
      const clickedTrigger = target.closest(config.trigger);
      const specificRoot = clickedTrigger.closest(config.root);
      if (!specificRoot) return;

      const estaAbierto = specificRoot.classList.contains('is-open');
      cerrarTodosLosFiltrosDesktop();

      if (!estaAbierto) {
        specificRoot.classList.add('is-open');
        clickedTrigger.setAttribute('aria-expanded', 'true');
      }
      return;
    }

    const rootAbierto = target.closest('.is-open'); 
    if (rootAbierto) {
        if (target.closest('.mas_close')) {
            cerrarTodosLosFiltrosDesktop();
        }
        return;
    }
    cerrarTodosLosFiltrosDesktop();
  });

  function cerrarTodosLosFiltrosDesktop() {
    document.querySelectorAll('.is-open').forEach(el => {
        if (el.matches('.filtro_tipo, .filtro_precio, .filtro_hb, .filtro_mas')) {
            el.classList.remove('is-open');
            const trigger = el.querySelector('[aria-expanded="true"]');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        }
    });
  }
}

/* ---- Reemplaza tu función initFiltrosResponsive por esta ---- */

function initFiltrosResponsive() {
  const isMobile = () => window.matchMedia('(max-width: 1024px)').matches;
  const scroller = document.querySelector('.filtros_scroller'); // Referencia al padre

  // Crear overlay si no existe
  let overlay = document.querySelector('.filtros_overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'filtros_overlay';
    document.body.appendChild(overlay);
  }

  // Función para cerrar todo
  const closeAll = () => {
    if (!isMobile()) return;
    document.querySelectorAll('.filtro_tipo.is-open, .filtro_precio.is-open, .filtro_hb.is-open, .filtro_mas.is-open')
      .forEach(el => el.classList.remove('is-open'));
    
    overlay.classList.remove('is-open');
    document.body.classList.remove('bsheet-lock');

    // 🔥 RESTAURAR SCROLL DEL PADRE
    if (scroller) {
        scroller.classList.remove('padre-visible');
    }
  };

  // Función para abrir uno específico
  const openRoot = ($root) => {
    $root.classList.add('is-open');
    overlay.classList.add('is-open');
    document.body.classList.add('bsheet-lock');

    // 🔥 TRUCO MAGICO: Quitamos el overflow del padre para que el hijo fijo se vea
    if (scroller) {
        scroller.classList.add('padre-visible');
    }
  };

  const selectors = ['.filtro_tipo', '.filtro_precio', '.filtro_hb', '.filtro_mas'];
  
  selectors.forEach(sel => {
    document.querySelectorAll(sel).forEach($root => {
        const $trg = $root.querySelector('button[aria-haspopup]'); 
        if (!$trg) return;

        $trg.addEventListener('click', (e) => {
            if (!isMobile()) return; 
            e.preventDefault();
            e.stopPropagation();
            
            const yaAbierto = $root.classList.contains('is-open');
            // Primero cerramos cualquier otro que esté abierto
            closeAll(); 

            // Si no estaba abierto, lo abrimos (con un mini delay para asegurar renderizado)
            if (!yaAbierto) {
                setTimeout(() => openRoot($root), 10);
            }
        });
    });
  });

  // Cerrar al dar click en el fondo oscuro
  overlay.addEventListener('click', (e) => {
    if (!isMobile()) return;
    e.preventDefault();
    e.stopPropagation();
    closeAll();
  });
}

// 🔥 EL HACK DE SAFARI/MÓVIL MEJORADO 🔥
// Esto fuerza a que el contenedor permita que el modal fijo se vea
function initHackSafari() {
  const scroller = document.querySelector('.filtros_scroller');
  if(!scroller) return;

  // Usamos MutationObserver para detectar cuando se añade la clase 'is-open'
  const observer = new MutationObserver((mutations) => {
    if (!window.matchMedia('(max-width: 1024px)').matches) return;

    const algunAbierto = document.querySelector('.filtro_tipo.is-open, .filtro_precio.is-open, .filtro_hb.is-open, .filtro_mas.is-open');
    
    if (algunAbierto) {
        // Truco: Hacer visible el overflow para que el hijo fixed se vea
        scroller.style.setProperty('overflow-x', 'visible', 'important');
        scroller.style.transform = 'none'; 
    } else {
        // Restaurar scroll
        scroller.style.removeProperty('overflow-x');
        scroller.style.removeProperty('transform');
    }
  });

  // Observamos cambios en los filtros
  document.querySelectorAll('.filtro_tipo, .filtro_precio, .filtro_hb, .filtro_mas').forEach(el => {
      observer.observe(el, { attributes: true, attributeFilter: ['class'] });
  });
}

// ==========================================================================
// 3. LÓGICA DE DATOS (Aplicada a todas las instancias)
// ==========================================================================

function initBuscadorPorTipo() {
  // Aplicamos lógica a CADA instancia de .filtro_tipo que encuentre en la página
  document.querySelectorAll('.filtro_tipo').forEach(root => {
    const form    = root.closest('form') || document.querySelector('.form_busqueda');
    const badge   = root.querySelector('.tipo_trigger__badge');
    const textEl  = root.querySelector('.tipo_trigger__text');
    const todas   = root.querySelector('#tipo_todas'); // Ojo: IDs únicos pueden dar problema si duplicas HTML idéntico, mejor usar clases si duplicas.
    // Si duplicas el ID 'tipo_todas', el JS solo afectará al primero. 
    // Asumiremos que el HTML es único o usas clases. Si falla, cambia #tipo_todas por .tipo_todas en HTML y JS.
    
    // Checkboxes dentro de ESTE panel
    const checks  = Array.from(root.querySelectorAll('input[name="tipo[]"]'));
    const pagina  = form ? form.querySelector('input[name="pagina"]') : null; // Buscamos input genérico de página

    if (!form || !checks.length) return;

    function updateTriggerLabel() {
        if (!textEl) return;
        const activos = checks.filter(c => c.checked).map(c => c.value);
        if (activos.length === 0) {
            textEl.textContent = 'Tipo de propiedad';
            if(badge) badge.style.display = 'none';
        } else {
            textEl.textContent = activos.map(t => t.charAt(0).toUpperCase() + t.slice(1)).join(', ');
            if(badge) {
                badge.textContent = activos.length;
                badge.style.display = 'inline-flex';
            }
        }
    }
    updateTriggerLabel();

    if (todas) {
        todas.addEventListener('change', () => {
            if (todas.checked) {
                checks.forEach(c => c.checked = false);
                updateTriggerLabel();
                // Reset URL
                const url = new URL(window.location.href);
                url.searchParams.forEach((v, k) => { if (k === 'tipo[]') url.searchParams.delete(k); });
                url.searchParams.set('pagina', '1');
                const busq = form.querySelector('input[name="busqueda"]')?.value?.trim();
                if (busq) url.searchParams.set('busqueda', busq);
                window.location.href = url.toString();
            }
        });
    }

    checks.forEach(c => {
        c.addEventListener('change', () => {
            if (c.checked && todas) todas.checked = false;
            const alguno = checks.some(x => x.checked);
            
            if (!alguno && todas) {
                todas.checked = true;
                updateTriggerLabel();
                const url = new URL(window.location.href);
                url.searchParams.forEach((v, k) => { if (k === 'tipo[]') url.searchParams.delete(k); });
                url.searchParams.set('pagina', '1');
                const busq = form.querySelector('input[name="busqueda"]')?.value?.trim();
                if (busq) url.searchParams.set('busqueda', busq);
                window.location.href = url.toString();
                return;
            }
            updateTriggerLabel();
            if(pagina) pagina.value = '1';
            form.submit();
        });
    });
  });
}

function initFiltroPrecio() {
  document.querySelectorAll('.precio_panel').forEach(panel => {
      const form = panel.closest('form') || document.querySelector('.form_busqueda');
      if (!form) return;

      const root = panel.closest('.filtro_precio');
      const triggerText = root ? root.querySelector('.precio_trigger__text') : null;

      const inputMin = panel.querySelector('.precio_min');
      const inputMax = panel.querySelector('.precio_max');
      const btnFiltrar = panel.querySelector('.precio_filtrar');
      const btnLimpiar = panel.querySelector('.precio_limpiar');
      const paginaHidden = panel.querySelector('.precio_pagina_hidden');

      if (!inputMin || !inputMax) return;

      function onlyDigits(str) { return (str || '').replace(/\D+/g, ''); }

      // Actualizar texto trigger
      const currentMin = onlyDigits(inputMin.value);
      const currentMax = onlyDigits(inputMax.value);
      if (triggerText) {
          if (currentMin || currentMax) {
            const minTxt = currentMin ? '$' + currentMin : '—';
            const maxTxt = currentMax ? '$' + currentMax : '—';
            triggerText.textContent = `${minTxt} — ${maxTxt}`;
          } else {
            triggerText.textContent = 'Precio';
          }
      }

      if (btnFiltrar) {
          btnFiltrar.addEventListener('click', (e) => {
            e.preventDefault();
            const rawMin = onlyDigits(inputMin.value);
            const rawMax = onlyDigits(inputMax.value);
            inputMin.value = rawMin;
            inputMax.value = rawMax;

            if (rawMin && rawMax && Number(rawMin) > Number(rawMax)) {
                inputMin.value = rawMax;
                inputMax.value = rawMin;
            }
            if (paginaHidden) paginaHidden.value = '1';
            form.submit();
          });
      }

      if (btnLimpiar) {
          btnLimpiar.addEventListener('click', () => {
            inputMin.value = '';
            inputMax.value = '';
            const url = new URL(window.location.href);
            url.searchParams.delete('precio_min');
            url.searchParams.delete('precio_max');
            url.searchParams.set('pagina', '1');
            window.location.href = url.toString();
          });
      }

      [inputMin, inputMax].forEach(inp => {
          inp.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if(btnFiltrar) btnFiltrar.click();
            }
          });
      });
  });
}

function initFiltroHB() {
  document.querySelectorAll('.hb_panel').forEach(panel => {
      const form = panel.closest('form') || document.querySelector('.form_busqueda');
      if(!form) return;

      const root = panel.closest('.filtro_hb');
      const labelText = root ? root.querySelector('.hb_trigger__text') : null;

      const hiddenHab   = panel.querySelector('.hb_hidden_hab');
      const hiddenBanos = panel.querySelector('.hb_hidden_banos');
      const exactBoxes  = panel.querySelectorAll('.hb_exact');
      const btnApply    = panel.querySelector('.hb_apply');
      const btnClear    = panel.querySelector('.hb_clear');
      const paginaHid   = panel.querySelector('.hb_pagina_hidden');

      function updateTriggerText() {
        if (!labelText) return;
        const hab   = parseInt(hiddenHab.value || '0', 10);
        const banos = parseInt(hiddenBanos.value || '0', 10);
        const habExact   = panel.querySelector('input[name="hab_exact"]')?.checked;
        const banosExact = panel.querySelector('input[name="banos_exact"]')?.checked;
        
        const parts = [];
        if (hab > 0)   parts.push(`Habs: ${hab}${habExact ? '' : '+'}`);
        if (banos > 0) parts.push(`Baños: ${banos}${banosExact ? '' : '+'}`);
        labelText.textContent = parts.length ? parts.join(', ') : 'Habs. y baños';
      }
      updateTriggerText();

      panel.querySelectorAll('.hb_group').forEach(group => {
        group.addEventListener('click', (e) => {
          const btn = e.target.closest('.hb_opt');
          if (!btn) return;
          const kind = btn.dataset.kind;         
          const val  = parseInt(btn.dataset.val, 10) || 0;
          group.querySelectorAll('.hb_opt').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          if (kind === 'hab')   hiddenHab.value = String(val);
          if (kind === 'banos') hiddenBanos.value = String(val);
          updateTriggerText();
        });
      });

      if(btnApply) {
          btnApply.addEventListener('click', (e) => {
            e.preventDefault();
            if (paginaHid) paginaHid.value = '1';
            form.submit();
          });
      }

      if(btnClear) {
          btnClear.addEventListener('click', () => {
            hiddenHab.value = '0';
            hiddenBanos.value = '0';
            exactBoxes.forEach(cb => { cb.checked = false; });
            panel.querySelectorAll('.hb_group').forEach(group => {
              group.querySelectorAll('.hb_opt').forEach(b => b.classList.remove('active'));
              const first = group.querySelector('.hb_opt[data-val="0"]'); 
              first && first.classList.add('active');
            });
            updateTriggerText();
            const url = new URL(window.location.href);
            url.searchParams.delete('hab');
            url.searchParams.delete('banos');
            url.searchParams.delete('hab_exact');
            url.searchParams.delete('banos_exact');
            url.searchParams.set('pagina', '1');
            window.location.href = url.toString();
          });
      }
  });
}

function initMasFiltros() {
  // Aplicar a TODAS las instancias de mas_modal
  document.querySelectorAll('.mas_modal').forEach(modal => {
      const form = modal.closest('form') || document.querySelector('.form_busqueda');
      if(!form) return;

      const root = modal.closest('.filtro_mas');
      const trigger = root ? root.querySelector('.mas_trigger') : null;
      const labelText = trigger ? trigger.querySelector('.mas_trigger__text') : null;
      
      const closeBtn  = modal.querySelector('.mas_close');
      const btnApply  = modal.querySelector('.mas_apply');
      const btnClear  = modal.querySelector('.mas_clear');
      const pageInput = modal.querySelector('.mas_pagina_hidden');
      
      const estratoHidden = modal.querySelector('.mf_hidden_estrato');
      const estratoGroup  = modal.querySelector('.mf_group[data-kind="estrato"]');

      // Funcionalidad cerrar con X
      if(closeBtn) {
          closeBtn.addEventListener('click', (e) => {
             e.preventDefault();
             // Cerramos buscando el root padre y quitando la clase
             if(root) root.classList.remove('is-open');
          });
      }

      function updateTriggerText() {
        if (!labelText || !estratoHidden) return;
        const eVal = parseInt(estratoHidden.value || '0', 10);
        labelText.textContent = eVal > 0 ? `Más filtros (Estrato ${eVal})` : 'Más filtros';
      }
      updateTriggerText();

      if(estratoGroup) {
          estratoGroup.addEventListener('click', (e) => {
            const btn = e.target.closest('.mf_opt');
            if (!btn) return;
            estratoGroup.querySelectorAll('.mf_opt').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if(estratoHidden) estratoHidden.value = String(parseInt(btn.dataset.val || '0', 10) || 0);
            updateTriggerText();
          });
      }

      if(btnApply) {
          btnApply.addEventListener('click', (e) => {
            e.preventDefault();
            if (pageInput) pageInput.value = '1';
            form.submit();
          });
      }

      if(btnClear) {
          btnClear.addEventListener('click', () => {
            if(estratoHidden) estratoHidden.value = '0';
            if(estratoGroup) {
                estratoGroup.querySelectorAll('.mf_opt').forEach(b => b.classList.remove('active'));
                const first = estratoGroup.querySelector('.mf_opt[data-val="0"]');
                first && first.classList.add('active');
            }
            updateTriggerText();
            const url = new URL(window.location.href);
            url.searchParams.delete('estrato');
            url.searchParams.set('pagina', '1');
            window.location.href = url.toString();
          });
      }
  });
}

// ------------------------- UTILIDADES Y OTROS -------------------------

function initPrecioMiles() {
  document.querySelectorAll('.precio_min, .precio_max, .precio, .administracion').forEach(el => bindMilesMask(el));
}

function bindMilesMask(input) {
  const fmt = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 });

  input.addEventListener('input', () => {
    const selStart = input.selectionStart ?? input.value.length;
    const raw = input.value;
    const digits = raw.replace(/\D+/g, '').replace(/^0+/, '');
    if (digits === '') {
      input.value = '';
      return;
    }
    const leftRaw = raw.slice(0, selStart);
    const leftDigitsCount = (leftRaw.match(/\d/g) || []).length;
    const formatted = fmt.format(Number(digits));
    let newCaret = 0, seen = 0;
    for (let i = 0; i < formatted.length; i++) {
      if (/\d/.test(formatted[i])) {
        seen++;
        if (seen === leftDigitsCount) { newCaret = i + 1; break; }
      }
      if (i === formatted.length - 1) newCaret = formatted.length;
    }
    input.value = formatted;
    try { input.setSelectionRange(newCaret, newCaret); } catch {}
  });

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      const panel = input.closest('.precio_panel');
      if(panel) panel.querySelector('.precio_filtrar')?.click();
    }
  });

  input.addEventListener('blur', () => {
    const digits = input.value.replace(/\D+/g, '').replace(/^0+/, '');
    input.value = digits ? new Intl.NumberFormat('es-CO').format(Number(digits)) : '';
  });
}

function initOrdenar() {
  const BP = 1024;
  const isMobile = () => window.innerWidth <= BP;

  const form   = document.getElementById('formOrdenar');
  const select = document.getElementById('ordenarPor');
  const toggle = form?.querySelector('.ordenar__toggle');
  const menu   = form?.querySelector('.ordenar__menu');
  if (!form || !select || !toggle || !menu) return;

  let overlay = document.querySelector('.ui_overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'ui_overlay';
    document.body.appendChild(overlay);
  }

  select.addEventListener('change', () => form.submit());

  const markActive = () => {
    const current = select.value || 'mas_recientes';
    menu.querySelectorAll('.ordenar__opt').forEach(btn => {
      btn.classList.toggle('is-active', btn.dataset.value === current);
    });
  };

  const open = () => {
    form.classList.add('is-open');
    toggle.setAttribute('aria-expanded', 'true');
    markActive();
    if (isMobile()) {
      overlay.classList.add('is-open');
      document.body.classList.add('ui-lock');
    }
  };
  const close = () => {
    form.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
    overlay.classList.remove('is-open');
    document.body.classList.remove('ui-lock');
  };

  let isToggling = false;
  const safeToggle = () => {
    if (isToggling) return;
    isToggling = true;
    form.classList.contains('is-open') ? close() : open();
    setTimeout(() => { isToggling = false; }, 120);
  };

  toggle.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); safeToggle(); });

  menu.addEventListener('click', (e) => {
    const btn = e.target.closest('.ordenar__opt');
    if (!btn) return;
    const val = btn.dataset.value;
    if (val) {
      select.value = val;
      close();
      form.submit();
    }
  });

  overlay.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); close(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
  document.addEventListener('click', (e) => {
    if (!form.classList.contains('is-open')) return;
    const target = e.target;
    const clickedInside = form.contains(target);
    if (!clickedInside) close();
  });
  window.addEventListener('resize', () => { if (!isMobile()) close(); });
}

function initLoginModal() {
  const btnOpen = document.querySelectorAll('.js-open-login'); // querySelectorAll por si hay varios
  const overlay = document.getElementById('loginOverlay');
  const modal   = document.getElementById('loginModal');
  const btnClose= document.getElementById('loginClose');
  const email   = document.getElementById('login_email');

  if (!overlay || !modal) return;

  const open = () => {
    overlay.hidden = false;
    modal.hidden   = false;
    overlay.classList.add('is-open');
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => email?.focus(), 50);
  };

  const close = () => {
    overlay.classList.remove('is-open');
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
    overlay.hidden = true;
    modal.hidden   = true;
  };

  btnOpen.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        open();
      });
  });

  if(btnClose) btnClose.addEventListener('click', close);
  if(overlay) overlay.addEventListener('click', close);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
  });

  if (modal.classList.contains('is-open')) {
    overlay.hidden = false;
    modal.hidden   = false;
    document.body.style.overflow = 'hidden';
  }
}

function initLoginModalSubmit() {
  const form = document.getElementById('loginForm');
  if (!form) return;

  const errorsBox = document.getElementById('auth-errors');

  function renderErrors(errs) {
    if (!errorsBox) return;
    if (!Array.isArray(errs) || errs.length === 0) {
      errorsBox.innerHTML = '';
      errorsBox.style.display = 'none';
      return;
    }
    errorsBox.innerHTML = errs.map(e => `<div class="alerta error" role="alert">${String(e)}</div>`).join('');
    errorsBox.style.display = 'block';
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    renderErrors([]);

    const btn = form.querySelector('.login-submit') || form.querySelector('button[type="submit"]');
    const original = btn ? btn.textContent : null;
    if (btn) { btn.disabled = true; btn.textContent = 'Ingresando…'; }

    try {
      const fd = new FormData(form);
      const resp = await fetch('/login', {
        method: 'POST',
        body: fd,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin'
      });

      const text = await resp.text();
      let data = null;
      try { data = JSON.parse(text); } catch {}

      if (!resp.ok || !data) {
        renderErrors(['Error inesperado. Intenta nuevamente.']);
        return;
      }

      if (data.ok) {
        window.location.href = data.redirect || '/';
      } else {
        renderErrors(data.errors || ['Credenciales inválidas']);
      }
    } catch (err) {
      console.error(err);
      renderErrors(['No se pudo conectar con el servidor.']);
    } finally {
      if (btn) { btn.disabled = false; btn.textContent = original || 'Continuar'; }
    }
  });
}

function initSwiperRecomendados() {
  const recomendados = document.querySelectorAll('.card-propiedad .swiper');
  recomendados.forEach(swiperElement => {
    new Swiper(swiperElement, {
      loop: true,
      navigation: {
        nextEl: swiperElement.querySelector('.swiper-button-next'),
        prevEl: swiperElement.querySelector('.swiper-button-prev'),
      },
    });
  });
}

function initGalerias() {
  let swiperMiniaturas, swiperPrincipal, swiperModal;
  const miniaturas = document.querySelector('.galeria-miniaturas');
  const principal = document.querySelector('.galeria-principal');

  if (miniaturas && principal) {
    swiperMiniaturas = new Swiper(miniaturas, {
      spaceBetween: 10,
      slidesPerView: 'auto',
      watchSlidesProgress: true,
      slideToClickedSlide: true,
    });

    swiperPrincipal = new Swiper(principal, {
      loop: true,
      spaceBetween: 10,
      slidesPerView: 1,
      navigation: {
        nextEl: '.galeria-principal .swiper-button-next',
        prevEl: '.galeria-principal .swiper-button-prev',
      },
      thumbs: { swiper: swiperMiniaturas },
    });

    swiperPrincipal.on('slideChange', () => {
      swiperMiniaturas.slideToLoop(swiperPrincipal.realIndex);
    });
  }

  const modal = document.getElementById('galeriaModal');
  const cerrar = modal?.querySelector('.cerrar-modal');
  const modalSwiperContainer = document.querySelector('.galeria-principal-modal');
  const miniaturasModalContainer = document.querySelector('.galeria-miniaturas-modal');

  if (modalSwiperContainer && miniaturasModalContainer) {
    const swiperMiniaturasModal = new Swiper(miniaturasModalContainer, {
      spaceBetween: 10,
      slidesPerView: 'auto',
      watchSlidesProgress: true,
      slideToClickedSlide: true,
    });

    swiperModal = new Swiper(modalSwiperContainer, {
      loop: true,
      spaceBetween: 10,
      slidesPerView: 1,
      navigation: {
        nextEl: '.galeria-principal-modal .swiper-button-next',
        prevEl: '.galeria-principal-modal .swiper-button-prev',
      },
      thumbs: { swiper: swiperMiniaturasModal },
    });

    swiperModal.on('slideChange', () => {
      swiperMiniaturasModal.slideToLoop(swiperModal.realIndex);
    });
  }

  const imagenesPrincipales = principal?.querySelectorAll('.swiper-slide img');
  if (imagenesPrincipales) {
    imagenesPrincipales.forEach(img => {
      img.style.cursor = 'zoom-in';
      img.addEventListener('click', () => {
        modal.classList.remove('oculto');
        document.body.style.overflow = 'hidden';
        swiperModal.slideToLoop(swiperPrincipal.realIndex, 0);
        setTimeout(() => swiperModal.update(), 100);
      });
    });
  }

  if(cerrar) {
      cerrar.addEventListener('click', () => {
        modal.classList.add('oculto');
        document.body.style.overflow = '';
      });
  }

  if(modal) {
      modal.addEventListener('click', e => {
        if (e.target === modal) {
          modal.classList.add('oculto');
          document.body.style.overflow = '';
        }
      });
  }

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal && !modal.classList.contains('oculto')) {
      modal.classList.add('oculto');
      document.body.style.overflow = '';
    }
  });
}

function confirmarEliminacionPropiedad() {
  const formularios = document.querySelectorAll('form[action="/propiedades/eliminar"]');
  formularios.forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará la propiedad permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'mi-alerta', confirmButton: 'mi-boton-confirmar', cancelButton: 'mi-boton-cancelar' }
      }).then(result => { if (result.isConfirmed) form.submit(); });
    });
  });
}

function buscadorUbicacion() {
  const input = document.querySelector('input.barra_por_ubicaciones');
  const box   = document.querySelector('.resultados_busqueda');

  if (!(input instanceof HTMLInputElement) || !box) return;

  const state = { items: [], activeIndex: -1 };

  function debounce(fn, delay = 300) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
  }
  function showBox() { box.style.display = 'block'; }
  function hideBox() {
    box.style.display = 'none';
    box.innerHTML = '';
    state.items = [];
    state.activeIndex = -1;
  }
  function escapeHTML(str='') {
    return str
      .replaceAll('&','&amp;').replaceAll('<','&lt;')
      .replaceAll('>','&gt;').replaceAll('"','&quot;')
      .replaceAll("'",'&#39;');
  }
  function render(items) {
    if (!items || items.length === 0) {
      box.innerHTML = '<div class="resultado-item muted">Sin resultados</div>';
      showBox();
      return;
    }
    box.innerHTML = items.map((it, idx) => `
      <div class="resultado-item ${idx === state.activeIndex ? 'activo' : ''}"
           data-index="${idx}" role="option" aria-selected="${idx === state.activeIndex}">
        ${escapeHTML(it.texto)}
      </div>
    `).join('');
    showBox();
  }

  const consultar = debounce(async (termino) => {
    if (!termino || termino.trim().length < 2) { hideBox(); return; }
    try {
      const url  = `/api/buscar?busqueda=${encodeURIComponent(termino.trim())}`;
      const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!resp.ok) throw new Error('HTTP ' + resp.status);
      const data = await resp.json();
      state.items = Array.isArray(data) ? data : [];
      state.activeIndex = -1;
      render(state.items);
    } catch (err) {
      console.error('[buscador] Error consultando sugerencias:', err);
      state.items = []; state.activeIndex = -1; render(state.items);
    }
  }, 300);

  input.addEventListener('input', (e) => consultar(e.target.value));
  input.addEventListener('blur', () => setTimeout(hideBox, 150));

  box.addEventListener('mousedown', (e) => {
    const item = e.target.closest('.resultado-item');
    if (!item || item.classList.contains('muted')) return;
    const idx = Number(item.dataset.index);
    const texto = state.items[idx]?.texto || '';
    if (texto) seleccionarYBuscar(texto);
  });

  input.addEventListener('keydown', (e) => {
    const abierta = box.style.display !== 'none' && state.items.length > 0;
    switch (e.key) {
      case 'ArrowDown':
        if (!abierta) return;
        e.preventDefault();
        state.activeIndex = (state.activeIndex + 1) % state.items.length;
        render(state.items); scrollToActive(); break;
      case 'ArrowUp':
        if (!abierta) return;
        e.preventDefault();
        state.activeIndex = (state.activeIndex - 1 + state.items.length) % state.items.length;
        render(state.items); scrollToActive(); break;
      case 'Enter':
        if (!abierta) return;
        e.preventDefault();
        const elegido = state.activeIndex === -1
          ? (input.value.trim() || '')
          : (state.items[state.activeIndex]?.texto || '');
        if (elegido) seleccionarYBuscar(elegido);
        break;
      case 'Escape':
        hideBox(); break;
    }
  });

  function scrollToActive() {
    const activo = box.querySelector('.resultado-item.activo');
    if (!activo) return;
    const { offsetTop, offsetHeight } = activo;
    const visibleTop = box.scrollTop;
    const visibleBottom = visibleTop + box.clientHeight;
    if (offsetTop < visibleTop) {
      box.scrollTop = offsetTop;
    } else if (offsetTop + offsetHeight > visibleBottom) {
      box.scrollTop = offsetTop - box.clientHeight + offsetHeight;
    }
  }

  function seleccionarYBuscar(valor) {
    input.value = valor;
    const url = new URL(window.location.href);
    url.searchParams.set('busqueda', valor);
    url.searchParams.set('pagina', '1');
    window.location.href = url.toString();
  }

}