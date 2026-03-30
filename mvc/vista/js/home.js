const pista            = document.querySelector('.pista');
const diapositivas     = [...pista.children];
const anterior         = document.querySelector('.anterior');
const siguiente        = document.querySelector('.siguiente');
const envoltura        = document.querySelector('.envoltura');
const contenedorPuntos = document.querySelector('.indicadores');
let i         = 0;
let intervalo = null;

/* ── Indicadores (generados dinámicamente) ── */
diapositivas.forEach((_, idx) => {
    const punto = document.createElement('button');
    punto.classList.add('indicador');
    punto.setAttribute('aria-label', `Diapositiva ${idx + 1}`);
    punto.addEventListener('click', () => { const prev = i; i = idx; actualizar(prev); reiniciarAutoplay(); });
    contenedorPuntos.appendChild(punto);
});

/* ── Helpers ── */
function vistasVisibles() {
    return 1;
}

function anchoDiapositiva() {
    return envoltura.offsetWidth / vistasVisibles();
}

function actualizarPuntos() {
    contenedorPuntos.querySelectorAll('.indicador').forEach((p, idx) => {
        p.classList.toggle('activo', idx === i);
    });
}

function actualizar(anterior_i) {
    const vistas = vistasVisibles();
    const ancho  = envoltura.offsetWidth / vistas;
    const max    = diapositivas.length - vistas;

    if (i > max) i = max;
    if (i < 0)   i = 0;

    /* Asignar ancho exacto en px a cada diapositiva */
    diapositivas.forEach(d => { d.style.width = ancho + 'px'; });

    /* Fade: marcar la que sale y la que entra */
    if (anterior_i !== undefined && anterior_i !== i) {
        const sale  = diapositivas[anterior_i];
        const entra = diapositivas[i];
        if (sale)  { sale.classList.add('saliendo');  sale.classList.remove('entrando'); }
        if (entra) { entra.classList.add('entrando'); entra.classList.remove('saliendo'); }
        setTimeout(() => {
            if (sale)  sale.classList.remove('saliendo');
        }, 700);
    }

    /* Desplazar en px — así el % no depende del ancho de .pista */
    pista.style.transform = `translateX(-${i * ancho}px)`;
    actualizarPuntos();
}

/* ── Autoplay ── */
function avanzar() {
    const max  = diapositivas.length - vistasVisibles();
    const prev = i;
    i = i >= max ? 0 : i + 1;
    actualizar(prev);
}

function iniciarAutoplay()   { intervalo = setInterval(avanzar, 7000); }
function detenerAutoplay()   { clearInterval(intervalo); intervalo = null; }
function reiniciarAutoplay() { detenerAutoplay(); iniciarAutoplay(); }

/* ── Botones ── */
anterior.addEventListener('click', () => {
    const max  = diapositivas.length - vistasVisibles();
    const prev = i;
    i = i <= 0 ? max : i - 1;
    actualizar(prev);
    reiniciarAutoplay();
});

siguiente.addEventListener('click', () => {
    const max  = diapositivas.length - vistasVisibles();
    const prev = i;
    i = i >= max ? 0 : i + 1;
    actualizar(prev);
    reiniciarAutoplay();
});

/* ── Pausa al pasar el ratón ── */
envoltura.addEventListener('mouseenter', detenerAutoplay);
envoltura.addEventListener('mouseleave', iniciarAutoplay);

/* ── Soporte táctil ── */
let inicioX = 0;

envoltura.addEventListener('touchstart', e => {
    inicioX = e.touches[0].clientX;
}, { passive: true });

envoltura.addEventListener('touchend', e => {
    const diff = inicioX - e.changedTouches[0].clientX;
    if (Math.abs(diff) < 50) return;
    const max = diapositivas.length - vistasVisibles();
    const prev = i;
    if (diff > 0) { i = i >= max ? 0   : i + 1; }
    else          { i = i <= 0   ? max : i - 1; }
    actualizar(prev);
    reiniciarAutoplay();
}, { passive: true });

/* ── Responsive: recalcular al cambiar tamaño ── */
window.addEventListener('resize', actualizar);

/* ── Inicio ── */
actualizar();
iniciarAutoplay();
