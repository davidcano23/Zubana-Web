// ------------------------- IMPORTACIONES -------------------------
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
// ------------------------- INICIALIZACIÓN -------------------------
document.addEventListener('DOMContentLoaded', function () {
  confirmarEliminacionPropiedad();
  initSwiperRecomendados();
  initGalerias();
  buscadorUbicacion();
  buscadorPorTipo();
  initFiltroPrecio();
  initPrecioMiles();
  initFiltroHB();
  initMasFiltros();
  initLoginModalSubmit();
  initLoginModal();
  initOrdenar();
  initFiltrosResponsive();
  
});

/* ---- Filtros: bottom-sheet + cierre a 1 toque ---- */
function initFiltrosResponsive() {
  const isMobile = () => window.matchMedia('(max-width: 1024px)').matches;

  let overlay = document.querySelector('.filtros_overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'filtros_overlay';
    document.body.appendChild(overlay);
  }

  const lock   = () => document.body.classList.add('bsheet-lock');
  const unlock = () => document.body.classList.remove('bsheet-lock');

  // ✅ FIX — Declarar SIEMPRE antes de usar
  const ubicInput = document.querySelector('.barra_por_ubicaciones');
  const ubicList  = document.querySelector('.resultados_busqueda');

  function positionUbicacionesDropdown() {
    if (!ubicInput || !ubicList) return;

    const visible = getComputedStyle(ubicList).display !== 'none';
    if (!visible) return;

    if (!isMobile()) {
      ubicList.classList.remove('is-floating');
      ubicList.style.removeProperty('--ub-left');
      ubicList.style.removeProperty('--ub-top');
      ubicList.style.removeProperty('--ub-width');
      return;
    }

    const r = ubicInput.getBoundingClientRect();
    ubicList.classList.add('is-floating');
    ubicList.style.setProperty('--ub-left',  `${Math.round(r.left)}px`);
    ubicList.style.setProperty('--ub-top',   `${Math.round(r.bottom + 6)}px`);
    ubicList.style.setProperty('--ub-width', `${Math.round(r.width)}px`);
  }

  function hideUbicacionesDropdown() {
    if (!ubicList) return;
    ubicList.classList.remove('is-floating');
  }

  const closeAll = () => {
    document
      .querySelectorAll('.filtro_tipo.is-open, .filtro_precio.is-open, .filtro_hb.is-open, .filtro_mas.is-open')
      .forEach(el => el.classList.remove('is-open'));

    overlay.classList.remove('is-open');
    unlock();
    hideUbicacionesDropdown(); // ✅ cerrar si estaba flotando
  };

  const openRoot = ($root) => {
    $root.classList.add('is-open');
    overlay.classList.add('is-open');
    lock();
  };

  const pairs = [
    { root: '.filtro_tipo',   trigger: '.tipo_trigger'   },
    { root: '.filtro_precio', trigger: '.precio_trigger' },
    { root: '.filtro_hb',     trigger: '.hb_trigger'     },
    { root: '.filtro_mas',    trigger: '.mas_trigger'    },
  ];

  let isToggling = false;
  const withGuard = (fn) => {
    if (isToggling) return;
    isToggling = true;
    try { fn(); } finally { setTimeout(() => { isToggling = false; }, 120); }
  };

  pairs.forEach(({ root, trigger }) => {
    const $root = document.querySelector(root);
    const $trg  = $root?.querySelector(trigger);
    if (!$root || !$trg) return;

    $trg.addEventListener('click', (e) => {
      if (!isMobile()) return;
      e.preventDefault();
      e.stopPropagation();
      withGuard(() => {
        const opened = $root.classList.contains('is-open');
        closeAll();
        if (!opened) openRoot($root);
      });
    });
  });

  overlay.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    closeAll();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAll();
  });
  window.addEventListener('resize', () => {
    if (!isMobile()) closeAll();
  });

  const scroller = document.querySelector('.filtros_scroller');
  if (scroller) {
    const reset = () => { scroller.scrollLeft = 0; };
    reset();
    window.addEventListener('resize', reset);
  }

  // ==============================
  // ✅ WIRING Ubicaciones
  // ==============================
  if (ubicInput && ubicList) {
    ['focus', 'input', 'click'].forEach(evt =>
      ubicInput.addEventListener(evt, positionUbicacionesDropdown, { passive: true })
    );

    ubicList.addEventListener('click', () => {
      hideUbicacionesDropdown();
    });

    document.addEventListener('click', (e) => {
      if (!ubicList.classList.contains('is-floating')) return;
      const inside = ubicList.contains(e.target) || ubicInput.contains(e.target);
      if (!inside) hideUbicacionesDropdown();
    });

    const reflow = () => {
      if (ubicList.classList.contains('is-floating')) positionUbicacionesDropdown();
    };
    window.addEventListener('resize', reflow, { passive: true });
    window.addEventListener('scroll', reflow, { passive: true });
  }
}


// === Ubicaciones: posicionamiento seguro en móvil/tablet ===
function positionUbicacionesDropdown() {
  const input = document.querySelector('.barra_por_ubicaciones');
  const list  = document.querySelector('.resultados_busqueda');
  if (!input || !list) return;

  const isMobile = () => window.matchMedia('(max-width: 1024px)').matches;

  // Asegura que exista y esté visible
  list.style.display = 'block';

  if (!isMobile()) {
    // Desktop: comportamiento normal (absoluto relativo al form)
    list.classList.remove('is-floating');
    list.style.removeProperty('--ub-left');
    list.style.removeProperty('--ub-top');
    list.style.removeProperty('--ub-width');
    return;
  }

  // Móvil/Tablet: anclar al viewport
  const r = input.getBoundingClientRect();
  list.classList.add('is-floating');
  list.style.setProperty('--ub-left',  `${Math.round(r.left)}px`);
  list.style.setProperty('--ub-top',   `${Math.round(r.bottom + 6)}px`);
  list.style.setProperty('--ub-width', `${Math.round(r.width)}px`);
}

function hideUbicacionesDropdown() {
  const list = document.querySelector('.resultados_busqueda');
  if (!list) return;
  list.classList.remove('is-floating');
  list.style.display = 'none';
}

// Recalcular al rotar/resize/scroll (mientras esté abierto)
(function wireUbicacionesReflow() {
  const reflow = () => {
    const list = document.querySelector('.resultados_busqueda');
    if (list && list.classList.contains('is-floating')) positionUbicacionesDropdown();
  };
  window.addEventListener('resize', reflow, { passive: true });
  window.addEventListener('scroll', reflow, { passive: true });
})();


/* ---- Ordenar: tarjeta centrada en móvil + overlay + 1 toque para cerrar ---- */
function initOrdenar() {
  const BP = 1024; // ajusta a tu v.$tablet si cambia
  const isMobile = () => window.innerWidth <= BP;

  const form   = document.getElementById('formOrdenar');
  const select = document.getElementById('ordenarPor');
  const toggle = form?.querySelector('.ordenar__toggle');
  const menu   = form?.querySelector('.ordenar__menu');
  if (!form || !select || !toggle || !menu) return;

  // overlay para ordenar en móvil
  let overlay = document.querySelector('.ui_overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'ui_overlay';
    document.body.appendChild(overlay);
  }

  // Desktop: onchange del select envía
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

  // Cerrar con overlay
  overlay.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); close(); });

  // Cerrar con Escape
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

  // ✅ Cerrar al tocar cualquier parte fuera del botón/menú
  document.addEventListener('click', (e) => {
    if (!form.classList.contains('is-open')) return;
    const target = e.target;
    const clickedInside = form.contains(target);
    if (!clickedInside) close();
  });

  // Si cambian a desktop, cerramos
  window.addEventListener('resize', () => { if (!isMobile()) close(); });
}





function initLoginModal() {
  const btnOpen = document.querySelector('.js-open-login');
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

  btnOpen?.addEventListener('click', (e) => {
    e.preventDefault();
    open();
  });

  btnClose?.addEventListener('click', close);
  overlay?.addEventListener('click', close);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
  });

  // auto-abrir si el backend lo pide (clase is-open ya la pone PHP)
  if (modal.classList.contains('is-open')) {
    overlay.hidden = false;
    modal.hidden   = false;
    document.body.style.overflow = 'hidden';
  }
}


function initLoginModalSubmit() {
  const form = document.getElementById('loginForm'); // <form id="loginForm">
  if (!form) return;

  const errorsBox = document.getElementById('auth-errors'); // <div id="auth-errors">

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
    e.preventDefault(); // ← evita navegación a /login
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
        // éxito -> redirige (o reload)
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

// Llama las 2 funciones al cargar
document.addEventListener('DOMContentLoaded', () => {
  initLoginModal();
  initLoginModalSubmit();
});


// ------------------------- SWIPER RECOMENDADOS -------------------------
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

// ------------------------- GALERÍAS PRINCIPAL Y MODAL -------------------------
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

  cerrar?.addEventListener('click', () => {
    modal.classList.add('oculto');
    document.body.style.overflow = '';
  });

  modal?.addEventListener('click', e => {
    if (e.target === modal) {
      modal.classList.add('oculto');
      document.body.style.overflow = '';
    }
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !modal.classList.contains('oculto')) {
      modal.classList.add('oculto');
      document.body.style.overflow = '';
    }
  });
}


// ------------------------- CONFIRMACIÓN ELIMINAR -------------------------
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

// ----------------- BARRA DE BUSQUEDA POR UBICACION Y BARRIO SUGERENCIA Y BUSQUEDA AUTOMATICA ------------------------------//
function buscadorUbicacion() {
  // 1) Selecciona un ÚNICO input y un ÚNICO contenedor
  const input = document.querySelector('input.barra_por_ubicaciones');
  const box   = document.querySelector('.resultados_busqueda');

  // 2) Si no existen en esta página, sal sin romper
  if (!(input instanceof HTMLInputElement) || !box) return;

  // 3) Estado local del autocompletado
  const state = { items: [], activeIndex: -1 };

  // 4) Utils
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

  // 5) Consulta al endpoint con debounce
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

  // 6) Listeners principales
  input.addEventListener('input', (e) => consultar(e.target.value));
  input.addEventListener('blur', () => setTimeout(hideBox, 150));

  // 👉 NECESARIO: detectar clic en una sugerencia (mousedown para no perder foco antes)
  box.addEventListener('mousedown', (e) => {
    const item = e.target.closest('.resultado-item');
    if (!item || item.classList.contains('muted')) return;
    const idx = Number(item.dataset.index);
    const texto = state.items[idx]?.texto || '';
    if (texto) seleccionarYBuscar(texto);
  });

  // 7) Navegación con teclado
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
        if (!abierta) return; // sin dropdown: deja enviar un form normal si lo hubiera
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

  // 👉 NECESARIO: redirigir con GET al seleccionar
  function seleccionarYBuscar(valor) {
    input.value = valor;
    const url = new URL(window.location.href);
    url.searchParams.set('busqueda', valor);
    url.searchParams.set('pagina', '1'); // reset paginación al seleccionar
    window.location.href = url.toString();
  }
}


function buscadorPorTipo() {
  const form   = document.querySelector('.form_busqueda');
  const trigger = document.querySelector('.tipo_trigger');
  const panel   = document.querySelector('.tipo_panel');
  const badge   = document.querySelector('.tipo_trigger__badge');
  const textEl  = document.querySelector('.tipo_trigger__text');
  const todas   = document.querySelector('#tipo_todas');
  const checks  = form ? Array.from(form.querySelectorAll('input[name="tipo[]"]')) : [];
  const pagina  = form ? form.querySelector('#pagina_hidden') : null;

  if (!form || !trigger || !panel || !todas || !checks.length || !pagina) return;

  // Abrir/cerrar panel
  function openPanel() {
    panel.style.display = 'block';
    trigger.setAttribute('aria-expanded', 'true');
    // cerrar al hacer click fuera
    setTimeout(() => {
      document.addEventListener('mousedown', onDocClick);
      document.addEventListener('keydown', onEsc);
    }, 0);
  }
  function closePanel() {
    panel.style.display = 'none';
    trigger.setAttribute('aria-expanded', 'false');
    document.removeEventListener('mousedown', onDocClick);
    document.removeEventListener('keydown', onEsc);
  }
  function onDocClick(e) {
    if (!panel.contains(e.target) && !trigger.contains(e.target)) closePanel();
  }
  function onEsc(e) { if (e.key === 'Escape') closePanel(); }

  trigger.addEventListener('click', () => {
    const isOpen = trigger.getAttribute('aria-expanded') === 'true';
    isOpen ? closePanel() : openPanel();
  });

  // Actualiza texto/badge del trigger
  function updateTriggerLabel() {
    const activos = checks.filter(c => c.checked).map(c => c.value);
    if (activos.length === 0) {
      textEl.textContent = 'Tipo de propiedad';
      badge.style.display = 'none';
    } else {
      textEl.textContent = activos.map(t => t.charAt(0).toUpperCase() + t.slice(1)).join(', ');
      badge.textContent = activos.length;
      badge.style.display = 'inline-flex';
    }
  }
  updateTriggerLabel();

  // Lógica “Todas”
  todas.addEventListener('change', () => {
    if (todas.checked) {
      checks.forEach(c => c.checked = false);
      updateTriggerLabel();
      // limpia tipo[] de la URL y reinicia página
      const url = new URL(window.location.href);
      url.searchParams.forEach((v, k) => { if (k === 'tipo[]') url.searchParams.delete(k); });
      url.searchParams.set('pagina', '1');
      // conserva busqueda si existe
      const busq = form.querySelector('input[name="busqueda"]')?.value?.trim();
      if (busq) url.searchParams.set('busqueda', busq);
      window.location.href = url.toString();
    }
  });

  // Cambios en cada checkbox → desmarca “Todas”, actualiza label y envía
  checks.forEach(c => {
    c.addEventListener('change', () => {
      if (c.checked) todas.checked = false;
      const alguno = checks.some(x => x.checked);
      if (!alguno) {
        // si no queda ninguno, activa “Todas” y limpia
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
      pagina.value = '1';
      form.submit(); // GET con filtros activos
    });
  });
}


function initFiltroPrecio() {
  const form    = document.querySelector('.form_busqueda');
  const trigger = document.querySelector('.precio_trigger');
  const panel   = document.querySelector('.precio_panel');
  if (!form || !trigger || !panel) return;

  const inputMin = panel.querySelector('.precio_min');
  const inputMax = panel.querySelector('.precio_max');
  const btnFiltrar = panel.querySelector('.precio_filtrar');
  const btnLimpiar = panel.querySelector('.precio_limpiar');
  const paginaHidden = panel.querySelector('.precio_pagina_hidden');
  const labelText = trigger.querySelector('.precio_trigger__text');

  // Abrir/cerrar
  function openPanel() {
    panel.style.display = 'block';
    trigger.setAttribute('aria-expanded', 'true');
    setTimeout(() => {
      document.addEventListener('mousedown', onDocClick);
      document.addEventListener('keydown', onEsc);
    }, 0);
  }
  function closePanel() {
    panel.style.display = 'none';
    trigger.setAttribute('aria-expanded', 'false');
    document.removeEventListener('mousedown', onDocClick);
    document.removeEventListener('keydown', onEsc);
  }
  function onDocClick(e) {
    if (!panel.contains(e.target) && !trigger.contains(e.target)) closePanel();
  }
  function onEsc(e) { if (e.key === 'Escape') closePanel(); }

  trigger.addEventListener('click', () => {
    const open = trigger.getAttribute('aria-expanded') === 'true';
    open ? closePanel() : openPanel();
  });

  // Helper: limpia no-dígitos (solo deja números)
  function onlyDigits(str) {
    return (str || '').replace(/\D+/g, '');
  }

  // Botón Filtrar → normaliza, corrige rango y envía
  btnFiltrar.addEventListener('click', () => {
    const rawMin = onlyDigits(inputMin.value);
    const rawMax = onlyDigits(inputMax.value);

    // setea los values “limpios” en los inputs del form (GET)
    inputMin.value = rawMin;
    inputMax.value = rawMax;

    // swap si ambos están y min > max
    if (rawMin && rawMax && Number(rawMin) > Number(rawMax)) {
      inputMin.value = rawMax;
      inputMax.value = rawMin;
    }

    // Reset a página 1
    if (paginaHidden) paginaHidden.value = '1';

    // Envía GET con todos los filtros activos
    form.submit();
  });

  // Botón Limpiar → elimina precio_min / precio_max de la URL y recarga
  btnLimpiar.addEventListener('click', () => {
    inputMin.value = '';
    inputMax.value = '';

    const url = new URL(window.location.href);
    url.searchParams.delete('precio_min');
    url.searchParams.delete('precio_max');
    url.searchParams.set('pagina', '1'); // reset

    window.location.href = url.toString();
  });

  // Enter en inputs = Filtrar
  [inputMin, inputMax].forEach(inp => {
    inp.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        btnFiltrar.click();
      }
    });
  });

  // Actualiza el texto del trigger con el rango actual (si existe)
  const currentMin = onlyDigits(inputMin.value);
  const currentMax = onlyDigits(inputMax.value);
  if (currentMin || currentMax) {
    const minTxt = currentMin ? '$' + currentMin : '—';
    const maxTxt = currentMax ? '$' + currentMax : '—';
    labelText.textContent = `${minTxt} — ${maxTxt}`;
  } else {
    labelText.textContent = 'Precio';
  }
}

function initPrecioMiles() {
  const minEl = document.querySelector('.precio_min');
  const maxEl = document.querySelector('.precio_max');
  [minEl, maxEl].forEach(el => el && bindMilesMask(el));
}

function bindMilesMask(input) {
  const fmt = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 });

  input.addEventListener('input', () => {
    const selStart = input.selectionStart ?? input.value.length;
    const raw = input.value;

    // 1) Mantén solo dígitos
    const digits = raw.replace(/\D+/g, '').replace(/^0+/, '');
    if (digits === '') {
      input.value = '';
      return;
    }

    // 2) Cuenta cuántos dígitos había a la izquierda del caret
    const leftRaw = raw.slice(0, selStart);
    const leftDigitsCount = (leftRaw.match(/\d/g) || []).length;

    // 3) Formatea con puntos de miles
    const formatted = fmt.format(Number(digits));

    // 4) Coloca el caret donde queden esos mismos dígitos
    let newCaret = 0, seen = 0;
    for (let i = 0; i < formatted.length; i++) {
      if (/\d/.test(formatted[i])) {
        seen++;
        if (seen === leftDigitsCount) { newCaret = i + 1; break; }
      }
      // si no alcanza, queda al final
      if (i === formatted.length - 1) newCaret = formatted.length;
    }

    input.value = formatted;
    // Reaplica caret (en móviles algunos navegadores lo ignoran, no pasa nada)
    try { input.setSelectionRange(newCaret, newCaret); } catch {}
  });

  // Enter aplica filtro (por si no lo tienes ya en initFiltroPrecio)
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      const panel = input.closest('.precio_panel');
      panel?.querySelector('.precio_filtrar')?.click();
    }
  });

  // Limpia ceros sobrantes al perder foco (opcional)
  input.addEventListener('blur', () => {
    const digits = input.value.replace(/\D+/g, '').replace(/^0+/, '');
    input.value = digits ? new Intl.NumberFormat('es-CO').format(Number(digits)) : '';
  });
}


function initFiltroHB() {
  const form    = document.querySelector('.form_busqueda');
  const trigger = document.querySelector('.hb_trigger');
  const panel   = document.querySelector('.hb_panel');
  if (!form || !trigger || !panel) return;

  const hiddenHab   = panel.querySelector('.hb_hidden_hab');
  const hiddenBanos = panel.querySelector('.hb_hidden_banos');
  const exactBoxes  = panel.querySelectorAll('.hb_exact');
  const btnApply    = panel.querySelector('.hb_apply');
  const btnClear    = panel.querySelector('.hb_clear');
  const paginaHid   = panel.querySelector('.hb_pagina_hidden');
  const labelText   = trigger.querySelector('.hb_trigger__text');

  // abrir/cerrar
  function openPanel() {
    panel.style.display = 'block';
    trigger.setAttribute('aria-expanded', 'true');
    setTimeout(() => {
      document.addEventListener('mousedown', docClose);
      document.addEventListener('keydown', onEsc);
    }, 0);
  }
  function closePanel() {
    panel.style.display = 'none';
    trigger.setAttribute('aria-expanded', 'false');
    document.removeEventListener('mousedown', docClose);
    document.removeEventListener('keydown', onEsc);
  }
  function docClose(e) {
    if (!panel.contains(e.target) && !trigger.contains(e.target)) closePanel();
  }
  function onEsc(e) { if (e.key === 'Escape') closePanel(); }

  trigger.addEventListener('click', () => {
    const isOpen = trigger.getAttribute('aria-expanded') === 'true';
    isOpen ? closePanel() : openPanel();
  });

  // seleccionar opción (hab o baños)
  panel.querySelectorAll('.hb_group').forEach(group => {
    group.addEventListener('click', (e) => {
      const btn = e.target.closest('.hb_opt');
      if (!btn) return;
      const kind = btn.dataset.kind;         // 'hab' | 'banos'
      const val  = parseInt(btn.dataset.val, 10) || 0;

      // activar visualmente
      group.querySelectorAll('.hb_opt').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // setear hidden
      if (kind === 'hab')   hiddenHab.value = String(val);
      if (kind === 'banos') hiddenBanos.value = String(val);

      // actualizar etiqueta del trigger (preview)
      updateTriggerText();
    });
  });

  // actualizar texto del trigger
  function updateTriggerText() {
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

  // aplicar
  btnApply.addEventListener('click', () => {
    // Si ambos están en 0 y los exact en false → limpiar parámetros
    const hab   = parseInt(hiddenHab.value || '0', 10);
    const banos = parseInt(hiddenBanos.value || '0', 10);

    // reset paginación
    if (paginaHid) paginaHid.value = '1';

    form.submit();
  });

  // limpiar
  btnClear.addEventListener('click', () => {
    hiddenHab.value = '0';
    hiddenBanos.value = '0';
    exactBoxes.forEach(cb => { cb.checked = false; });

    // limpiar visual de botones
    panel.querySelectorAll('.hb_group').forEach(group => {
      group.querySelectorAll('.hb_opt').forEach(b => b.classList.remove('active'));
      const first = group.querySelector('.hb_opt[data-val="0"]'); // "Todos"
      first && first.classList.add('active');
    });

    updateTriggerText();

    // quitar params de la URL y reset a página 1
    const url = new URL(window.location.href);
    url.searchParams.delete('hab');
    url.searchParams.delete('banos');
    url.searchParams.delete('hab_exact');
    url.searchParams.delete('banos_exact');
    url.searchParams.set('pagina', '1');
    window.location.href = url.toString();
  });

  // Enter dentro del panel => aplicar
  panel.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); btnApply.click(); }
  });
}

function initMasFiltros() {
  const form    = document.querySelector('.form_busqueda');
  const trigger = document.querySelector('.mas_trigger');
  const overlay = document.querySelector('.mas_overlay');
  const modal   = document.querySelector('.mas_modal');
  if (!form || !trigger || !overlay || !modal) return;

  const closeBtn  = modal.querySelector('.mas_close');
  const btnApply  = modal.querySelector('.mas_apply');
  const btnClear  = modal.querySelector('.mas_clear');
  const pageInput = modal.querySelector('.mas_pagina_hidden');
  const labelText = trigger.querySelector('.mas_trigger__text');

  const estratoHidden = modal.querySelector('.mf_hidden_estrato');
  const estratoGroup  = modal.querySelector('.mf_group[data-kind="estrato"]');

  function openModal() {
    overlay.hidden = false;
    modal.hidden = false;
    overlay.classList.add('is-open');
    modal.classList.add('is-open');
    trigger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';

    // listeners para cerrar
    setTimeout(() => {
      document.addEventListener('keydown', onEsc);
      overlay.addEventListener('click', closeModal, { once: true });
    }, 0);
  }

  function closeModal() {
    overlay.classList.remove('is-open');
    modal.classList.remove('is-open');
    overlay.hidden = true;
    modal.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    document.removeEventListener('keydown', onEsc);
  }

  // Al cargar, fuerza cerrado por si venías de una recarga
  (function forceClosedOnLoad(){
    overlay.classList.remove('is-open');
    modal.classList.remove('is-open');
    overlay.hidden = true;
    modal.hidden = true;
    trigger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  })();

  function onEsc(e) { if (e.key === 'Escape') closeModal(); }

  trigger.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);

  // Selección de Estrato (chips)
  estratoGroup.addEventListener('click', (e) => {
    const btn = e.target.closest('.mf_opt');
    if (!btn) return;
    estratoGroup.querySelectorAll('.mf_opt').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    estratoHidden.value = String(parseInt(btn.dataset.val || '0', 10) || 0);
    updateTriggerText();
  });

  // Actualiza el texto del trigger según estado
  function updateTriggerText() {
    const eVal = parseInt(estratoHidden.value || '0', 10);
    labelText.textContent = eVal > 0 ? `Más filtros (Estrato ${eVal})` : 'Más filtros';
  }
  updateTriggerText();

  // Aplicar → submit GET conservando demás filtros
  btnApply.addEventListener('click', () => {
    if (pageInput) pageInput.value = '1';
    form.submit();
  });

  // Limpiar → quitar estrato de la URL y reset a pág 1
  btnClear.addEventListener('click', () => {
    estratoHidden.value = '0';
    estratoGroup.querySelectorAll('.mf_opt').forEach(b => b.classList.remove('active'));
    const first = estratoGroup.querySelector('.mf_opt[data-val="0"]');
    first && first.classList.add('active');
    updateTriggerText();

    const url = new URL(window.location.href);
    url.searchParams.delete('estrato');
    url.searchParams.set('pagina', '1');
    window.location.href = url.toString();
  });
}



