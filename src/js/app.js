// ------------------------- IMPORTACIONES -------------------------
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

<<<<<<< HEAD
// ------------------------- INICIALIZACIÓN -------------------------
document.addEventListener('DOMContentLoaded', function () {
  initAutocomplete();
  iniciarChoicesConDelay();
  aplicarFormatoMonedaPorID();
  confirmarEliminacionPropiedad();
  initModalFiltros();
  initBusqueda();
  initSwiperRecomendados();
  initGalerias();
});

// ------------------------- MODAL FILTROS -------------------------
function initModalFiltros() {
  const btnAbrir = document.getElementById("btnFiltros");
  const modal = document.getElementById("modalFiltros");
  const cerrar = document.getElementById("cerrarModal");
  const guardar = document.getElementById("guardarBtn");

  function cerrarModal() {
    modal.classList.remove("mostrar");
    document.body.classList.remove("no-scroll");
  }

  if (btnAbrir && modal && cerrar && guardar) {
    btnAbrir.addEventListener("click", () => {
      modal.classList.add("mostrar");
      document.body.classList.add("no-scroll");
    });

    cerrar.addEventListener("click", cerrarModal);

    // Guardar => aplica filtros + cierra modal
    guardar.addEventListener("click", () => {
      if (document.activeElement) document.activeElement.blur();
      ejecutarBusqueda();      // <--- ahora es global
      cerrarModal();
    });

    window.addEventListener("click", (e) => {
      if (e.target === modal) cerrarModal();
    });
  }
}

// ------------------------- BÚSQUEDA Y LIMPIAR FILTROS -------------------------
function initBusqueda() {
  const inputCiudad = document.getElementById("buscador");
  const btnBuscar = document.getElementById("buscarBtn");
  const btnLimpiar = document.getElementById("limpiarFiltros");

  // Autocomplete de ciudades
  if (inputCiudad) {
    const autocomplete = new google.maps.places.Autocomplete(inputCiudad, {
      types: ["(cities)"],
      componentRestrictions: { country: "co" }
    });

    autocomplete.addListener("place_changed", function () {
      const place = autocomplete.getPlace();
      let ciudad = "", departamento = "";
      place.address_components.forEach(component => {
        if (component.types.includes("locality")) ciudad = component.long_name;
        if (component.types.includes("administrative_area_level_1")) departamento = component.long_name;
      });
      inputCiudad.value = ciudad && departamento ? `${ciudad}, ${departamento}` : (ciudad || departamento);
    });

    inputCiudad.addEventListener("keypress", e => { if (e.key === "Enter") ejecutarBusqueda(); });
  }

  if (btnBuscar) btnBuscar.addEventListener("click", ejecutarBusqueda);

  if (btnLimpiar) {
    btnLimpiar.addEventListener("click", () => {
        // Limpia la URL y recarga sin parámetros
        window.location.href = window.location.pathname;
    });
    }


}

// ------------------------- FUNCIÓN GLOBAL DE BÚSQUEDA -------------------------
function ejecutarBusqueda() {
  const val = (id) => document.getElementById(id)?.value?.trim() || "";
  const moneyFrom = (mainId, modalId) => {
    const modal = (modalId && val(modalId)) ? val(modalId).replace(/\D/g, "") : "";
    const main  = val(mainId).replace(/\D/g, "");
    return modal || main;
  };

  const ciudad = val("buscador");

  // 🔹 NUEVO: leer valores del select múltiple desde Choices
  const tipoSelect = document.getElementById("tipo");
  let tipo = "";

  if (tipoSelect) {
    // Si es una instancia de Choices
    if (tipoSelect.choices && typeof tipoSelect.choices.getValue === "function") {
      const valores = tipoSelect.choices.getValue(true); // devuelve array de valores seleccionados
      if (Array.isArray(valores)) tipo = valores.join(",");
    } else if (tipoSelect.selectedOptions.length > 0) {
      // fallback por si Choices no está cargado
      const valores = Array.from(tipoSelect.selectedOptions).map(opt => opt.value);
      tipo = valores.join(",");
    }
  }

  const tipoMovil    = val("tipo_movil_tablet");
  const precioMin    = moneyFrom("precio_min", "precio_min_modal");
  const precioMax    = moneyFrom("precio_max", "precio_max_modal");
  const banos        = val("banos");
  const habitaciones = val("habitaciones");
  const areaMinima   = val("area_minima").replace(/\D/g, "");
  const modalidad    = val("modalidad_filtros");
  const tipoUnidad   = val("tipo_unidad_filtros");
  const propietario  = val("nombre_propietario");
  const barrio       = val("barrio");
  const codigo       = val("codigo_filtro");

  // 🔹 Construcción de la URL
  const params = new URLSearchParams();
  if (ciudad)       params.set("ciudad", ciudad);
  if (tipo)         params.set("tipo", tipo);
  if (tipoMovil)    params.set("tipo_movil_tablet", tipoMovil);
  if (precioMin)    params.set("precio_min", precioMin);
  if (precioMax)    params.set("precio_max", precioMax);
  if (banos)        params.set("banos", banos);
  if (habitaciones) params.set("habitaciones", habitaciones);
  if (areaMinima)   params.set("area_minima", areaMinima);
  if (modalidad)    params.set("modalidad_filtros", modalidad);
  if (tipoUnidad)   params.set("tipo_unidad_filtros", tipoUnidad);
  if (codigo)       params.set("codigo_filtro", codigo);
  if (propietario)  params.set("nombre_propietario", propietario);
  if (barrio)       params.set("barrio", barrio);

  const base = window.location.pathname;
  const url = params.toString() ? `${base}?${params.toString()}` : base;
  window.location.href = url;
}


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

// ------------------------- AUTOCOMPLETE GOOGLE (detalle) -------------------------
function initAutocomplete() {
  const input = document.getElementById('ubicacion');
  if (!input) return;
  const autocomplete = new google.maps.places.Autocomplete(input, {
    types: ['geocode'],
    componentRestrictions: { country: "co" }
  });

  autocomplete.addListener('place_changed', function () {
    const place = autocomplete.getPlace();
    let ciudad = "", municipio = "", departamento = "";
    place.address_components.forEach(component => {
      if (component.types.includes("locality")) ciudad = component.long_name;
      if (component.types.includes("administrative_area_level_2")) municipio = component.long_name;
      if (component.types.includes("administrative_area_level_1")) departamento = component.long_name;
    });
    input.value = ciudad ? `${ciudad}, ${departamento}` : `${municipio}, ${departamento}`;
  });
}

// ------------------------- CHOICES SELECT -------------------------
function iniciarChoicesConDelay() {
  const SELECT_IDS = ['tipo', 'ordenarPor', 'modalidad_filtros', 'tipo_unidad_filtros', 'tipo_movil_tablet'];
  const MAX_RETRIES = 20;
  let tries = 0;

  const boot = () => {
    const ChoicesLib = window.Choices;
    if (!ChoicesLib) {
      if (tries++ < MAX_RETRIES) return setTimeout(boot, 100);
      console.error('[Choices] No se pudo cargar la librería.');
      return;
    }

    SELECT_IDS.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;

      // Evita reinicializar
      if (el.dataset.choices === 'true' && el.choices) {
        try { el.choices.destroy(); } catch (_) {}
      }

      const isMultiple = !!el.multiple;

      const choices = new ChoicesLib(el, {
        removeItemButton: isMultiple,
        searchEnabled: false,
        itemSelectText: '',
        shouldSort: false,
        allowHTML: false,
        position: 'bottom',
        placeholder: true,
        classNames: { containerOuter: 'choices' }
      });

      el.dataset.choices = 'true';
      el.choices = choices;

      // 🔹 CORRECCIÓN CLAVE: diferir búsqueda hasta que Choices actualice el valor completo
      if (id === 'tipo' && isMultiple) {
        let timeoutBusqueda;

        const lanzarBusqueda = () => {
          clearTimeout(timeoutBusqueda);
          timeoutBusqueda = setTimeout(() => ejecutarBusqueda(), 300);
        };

        // Cuando se agrega o elimina un item, espera que Choices actualice
        el.addEventListener('addItem', lanzarBusqueda);
        el.addEventListener('removeItem', lanzarBusqueda);

        // Mantener abierto el menú al seleccionar múltiples
        el.addEventListener('hideDropdown', () => {
          if (isMultiple) choices.showDropdown();
        });
      }
    });
  };

  setTimeout(boot, 100);
}

// ------------------------- FORMATO MONEDA -------------------------
function aplicarFormatoMonedaPorID() {
  const ids = ["precio_min", "precio_max", "precio_min_modal", "precio_max_modal", "precio"];
  ids.forEach(id => {
    const input = document.getElementById(id);
    if (input) {
      input.addEventListener("input", e => {
        let valor = e.target.value.replace(/\D/g, "");
        e.target.value = valor ? new Intl.NumberFormat('es-CO').format(valor) : "";
      });
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
=======
// -------------- CARGAR HTML Y DESPUES JS ---------------------
document.addEventListener('DOMContentLoaded', function() {
    initAutocomplete();
    iniciarChoices();
    aplicarFormatoMonedaPorID();
    confirmarEliminacionPropiedad();
});







// ------------------------- MODAL FILTROS -------------------------
document.addEventListener("DOMContentLoaded", function () {
    const btnAbrir = document.getElementById("btnFiltros");
    const modal = document.getElementById("modalFiltros");
    const cerrar = document.getElementById("cerrarModal");
    const guardar = document.getElementById("guardarBtn");

    function cerrarModal() {
        modal.classList.remove("mostrar");
        document.body.classList.remove("no-scroll");
    }

    if (btnAbrir && modal && cerrar && guardar) {
        btnAbrir.addEventListener("click", () => {
            modal.classList.add("mostrar");
            document.body.classList.add("no-scroll");
        });

        cerrar.addEventListener("click", cerrarModal);
        guardar.addEventListener("click", () => {
            if (document.activeElement) {
                document.activeElement.blur();
            }
            cerrarModal();
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) {
                cerrarModal();
            }
        });
    }
});


// ------------------------- CARRUSELES SWIPER RECOMENDADOS -------------------------
document.addEventListener('DOMContentLoaded', () => {
    const recomendados = document.querySelectorAll('.card-propiedad .swiper');

    recomendados.forEach((swiperElement) => {
        new Swiper(swiperElement, {
            loop: true,
            navigation: {
                nextEl: swiperElement.querySelector('.swiper-button-next'),
                prevEl: swiperElement.querySelector('.swiper-button-prev'),
            },
        });
    });
});

// ------------------------- GALERÍA PRINCIPAL Y MINIATURAS -------------------------
let swiperMiniaturas, swiperPrincipal, swiperModal;

document.addEventListener('DOMContentLoaded', () => {
    const miniaturas = document.querySelector('.galeria-miniaturas');
    const principal = document.querySelector('.galeria-principal');

    if (miniaturas && principal) {
        swiperMiniaturas = new Swiper(miniaturas, {
            spaceBetween: 10,
            slidesPerView: 'auto',
            watchSlidesProgress: true,
            watchSlidesVisibility: true,
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
            thumbs: {
                swiper: swiperMiniaturas,
            },
        });

        swiperPrincipal.on('slideChange', () => {
            swiperMiniaturas.slideToLoop(swiperPrincipal.realIndex);
        });
    }

    // ------------------------- MODAL GALERÍA -------------------------
    const modal = document.getElementById('galeriaModal');
    const cerrar = modal?.querySelector('.cerrar-modal');

    const modalSwiperContainer = document.querySelector('.galeria-principal-modal');
    const miniaturasModalContainer = document.querySelector('.galeria-miniaturas-modal');

    if (modalSwiperContainer && miniaturasModalContainer) {
        const swiperMiniaturasModal = new Swiper(miniaturasModalContainer, {
            spaceBetween: 10,
            slidesPerView: 'auto',
            watchSlidesProgress: true,
            watchSlidesVisibility: true,
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
            thumbs: {
                swiper: swiperMiniaturasModal,
            },
        });

        swiperModal.on('slideChange', () => {
            swiperMiniaturasModal.slideToLoop(swiperModal.realIndex);
        });
    }

    // ---------------- ABRIR MODAL desde imagen principal ----------------
    const imagenesPrincipales = principal?.querySelectorAll('.swiper-slide img');

    if (imagenesPrincipales) {
        imagenesPrincipales.forEach((img) => {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', () => {
                modal.classList.remove('oculto');
                document.body.style.overflow = 'hidden';
                swiperModal.slideToLoop(swiperPrincipal.realIndex, 0);
                setTimeout(() => {
                    swiperModal.update();
                }, 100);
            });
        });
    }

    // ---------------- CERRAR MODAL ----------------
    cerrar?.addEventListener('click', () => {
        modal.classList.add('oculto');
        document.body.style.overflow = '';
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('oculto');
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('oculto')) {
            modal.classList.add('oculto');
            document.body.style.overflow = '';
        }
    });
});


function initAutocomplete() {
    const input = document.getElementById('ubicacion');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode'],  // Solo direcciones
        componentRestrictions: { country: "co" }  // Colombia
    });

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        let ciudad = "";
        let municipio = "";
        let departamento = "";

        place.address_components.forEach(component => {
            if (component.types.includes("locality")) {
                ciudad = component.long_name;
            }
            if (component.types.includes("administrative_area_level_2")) {
                municipio = component.long_name;
            }
            if (component.types.includes("administrative_area_level_1")) {
                departamento = component.long_name;
            }
        });

        // Si tiene ciudad, mostrar ciudad + departamento, si no, municipio + departamento
        const resultado = ciudad
            ? `${ciudad}, ${departamento}`
            : `${municipio}, ${departamento}`;

        input.value = resultado;
    });
}



document.addEventListener("DOMContentLoaded", function () {
    const inputCiudad = document.getElementById("buscador");
    const inputCodigo = document.getElementById("codigo_filtro");
    const boton = document.getElementById("buscarBtn");
    let ciudadSeleccionada = "";

    // Autocomplete solo para ciudades (vista index)
    if (inputCiudad) {
        const autocomplete = new google.maps.places.Autocomplete(inputCiudad, {
            types: ["(cities)"],
            componentRestrictions: { country: "co" }
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            let ciudad = "";
            let departamento = "";

            place.address_components.forEach(component => {
                if (component.types.includes("locality")) {
                    ciudad = component.long_name;
                }
                if (component.types.includes("administrative_area_level_1")) {
                    departamento = component.long_name;
                }
            });

            // Combina ciudad y departamento, omitiendo "Colombia"
            let resultado = ciudad && departamento ? `${ciudad}, ${departamento}` : ciudad || departamento;
            inputCiudad.value = resultado;
        });

        inputCiudad.addEventListener("keypress", function (e) {
            if (e.key === "Enter") buscar();
        });
    }
    // Validar existencia del botón de búsqueda
    if (boton) {
        boton.addEventListener("click", buscar);
    }

    function buscar() {
        const tipo = document.getElementById("tipo")?.value || "";
        const tipo_movil_tablet = document.getElementById("tipo_movil_tablet")?.value || "";
        const ciudad = ciudadSeleccionada || inputCiudad?.value?.trim() || "";
        const precioMin = document.getElementById("precio_min")?.value || "";
        const precioMax = document.getElementById("precio_max")?.value || "";
        const banos = document.getElementById("banos")?.value || "";
        const habitaciones = document.getElementById("habitaciones")?.value || "";
        const area_minima = document.getElementById("area_minima")?.value || "";
        const modalidad_filtros = document.getElementById("modalidad_filtros")?.value || "";
        const tipo_unidad_filtros = document.getElementById("tipo_unidad_filtros")?.value || "";
        const nombre_propietario = document.getElementById("nombre_propietario")?.value || "";
        const barrio = document.getElementById("barrio")?.value || "";
        const codigo_filtro = inputCodigo?.value?.trim() || "";

        let url = window.location.pathname + "?";

        if (ciudad) url += `ciudad=${encodeURIComponent(ciudad)}&`;
        if (tipo) url += `tipo=${encodeURIComponent(tipo)}&`;
        if (tipo_movil_tablet) url += `tipo_movil_tablet=${encodeURIComponent(tipo_movil_tablet)}&`;
        if (precioMin) url += `precio_min=${encodeURIComponent(precioMin)}&`;
        if (precioMax) url += `precio_max=${encodeURIComponent(precioMax)}&`;
        if (banos) url += `banos=${encodeURIComponent(banos)}&`;
        if (habitaciones) url += `habitaciones=${encodeURIComponent(habitaciones)}&`;
        if (area_minima) url += `area_minima=${encodeURIComponent(area_minima)}&`;
        if (modalidad_filtros) url += `modalidad_filtros=${encodeURIComponent(modalidad_filtros)}&`;
        if (tipo_unidad_filtros) url += `tipo_unidad_filtros=${encodeURIComponent(tipo_unidad_filtros)}&`;
        if (codigo_filtro) url += `codigo_filtro=${encodeURIComponent(codigo_filtro)}&`;
        if (nombre_propietario) url += `nombre_propietario=${encodeURIComponent(nombre_propietario)}&`;
        if (barrio) url += `barrio=${encodeURIComponent(barrio)}&`;

        url = url.replace(/&$/, "");
        window.location.href = url;
    }
});


function iniciarChoices() {
    const tipoSelect = document.getElementById('tipo');
    const ordenarPor = document.getElementById('ordenarPor');
    const modalidad_filtros = document.getElementById('modalidad_filtros');
    const tipo_unidad_filtros = document.getElementById('tipo_unidad_filtros');
    if (tipoSelect) {
        new Choices(tipoSelect, {
            searchEnabled: false,
            itemSelectText: '',
        });
    }

    if (ordenarPor) {
        new Choices(ordenarPor, {
            searchEnabled: false,
            itemSelectText: '',
        });
    }

    if (modalidad_filtros) {
        new Choices(modalidad_filtros, {
            searchEnabled: false,
            itemSelectText: '',
        });
    }

    if (tipo_unidad_filtros) {
        new Choices(tipo_unidad_filtros, {
            searchEnabled: false,
            itemSelectText: '',
        });
    }
}


//------------FORMATO PRECIO AUTOMATICO---------//
function aplicarFormatoMonedaPorID() {
    const ids = ["precio_min", "precio_max", "precio"]; // Agrega aquí todos los IDs que necesites

    ids.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", function (e) {
                let valor = e.target.value.replace(/\D/g, ""); // quitar todo lo que no sea número
                if (valor) {
                    e.target.value = new Intl.NumberFormat('es-CO').format(valor);
                } else {
                    e.target.value = "";
                }
            });
        }
    });
}


//------------ALERTA ANTES DE ELIMINAR------------//
function confirmarEliminacionPropiedad() {
    const formulariosEliminar = document.querySelectorAll('form[action="/propiedades/eliminar"]');

    formulariosEliminar.forEach(formulario => {
        formulario.addEventListener('submit', function(e) {
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
                customClass: {
                    popup: 'mi-alerta',
                    confirmButton: 'mi-boton-confirmar',
                    cancelButton: 'mi-boton-cancelar'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    formulario.submit();
                }
            });
        });
    });
}













>>>>>>> 72a07a4c28173280a46861e54708ada0f935a189
