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