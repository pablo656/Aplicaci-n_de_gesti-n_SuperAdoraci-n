/**
 * Helper para obtener el token de forma segura
 */
const getCSRFToken = () => {
    const globalToken = document.getElementById("csrf_token_global");
    if (globalToken) return globalToken.value;
    
    const formToken = document.querySelector('input[name="csrf_token"]');
    return formToken ? formToken.value : "";
};

function actualizarBadge() {
    const badge = document.getElementById('badge-total');
    if (!badge) return;
    const contadores = document.querySelectorAll('[id^="cantidad-pedido-"]');
    let total = 0;
    contadores.forEach(el => total += parseInt(el.textContent) || 0);
    if (total > 0) {
        badge.textContent = total + (total === 1 ? ' plato' : ' platos');
        badge.classList.add('visible');
    } else {
        badge.classList.remove('visible');
    }
}

function abrirModal(idComida, nombre) {
    document.getElementById('modalIdComida').value = idComida;
    document.getElementById('modalTitulo').textContent = 'Pedir: ' + nombre;
    document.getElementById('cantidad').value = 1;
    document.getElementById('cantidadDisplay').textContent = 1;
    document.getElementById('mensaje').value = '';

    const inputFecha = document.getElementById('fecha_entrega');
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 3);
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 6);
    inputFecha.min = minDate.toISOString().split('T')[0];
    inputFecha.max = maxDate.toISOString().split('T')[0];
    inputFecha.value = '';

    document.querySelector('.btn-menos').style.visibility = 'hidden';
    document.getElementById('modalPedir').classList.add('activo');
}

function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    const display = document.getElementById('cantidadDisplay');
    const nuevo = Math.min(30, Math.max(1, parseInt(input.value) + delta));
    input.value = nuevo;
    display.textContent = nuevo;
    document.querySelector('.btn-menos').style.visibility = nuevo <= 1 ? 'hidden' : 'visible';
    document.querySelector('.btn-mas').style.visibility = nuevo >= 30 ? 'hidden' : 'visible';
}

function cerrarModal() {
    document.getElementById('modalPedir').classList.remove('activo');
}

document.getElementById('modalPedir').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

/**
 * Función corregida con Token CSRF
 */
function ajustarPedido(idComida, cambio) {
    const token = getCSRFToken();

    fetch('IndexPedidos.php?action=actualizar_cantidad_cookie', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_comida=${encodeURIComponent(idComida)}&cambio=${encodeURIComponent(cambio)}&csrf_token=${encodeURIComponent(token)}`
    })
    .then(response => response.json())
    .then(data => {
        // Si el servidor detecta fallo de CSRF, recargamos
        if (data && data.error === "CSRF_FAIL") {
            window.location.reload();
            return;
        }

        const elemento = document.getElementById('cantidad-pedido-' + idComida);
        if (!elemento) return;
        const actual = parseInt(elemento.textContent) || 0;
        const valor = Math.min(30, Math.max(0, actual + cambio));
        
        if (valor <= 0) {
            const accion = document.getElementById('accion-' + idComida);
            const nombre = accion.dataset.nombre;
            const precioHTML = accion.querySelector('.precio').outerHTML;
            // Al reconstruir el botón, nos aseguramos de que mantenga la funcionalidad de abrirModal
            accion.innerHTML = precioHTML + `<button class="btn-pedir" onclick='abrirModal(${idComida}, ${JSON.stringify(nombre)})'>&#43;</button>`;
        } else {
            elemento.textContent = valor;
        }
        actualizarBadge();
    })
    .catch(error => {
        console.error("Error en la petición:", error);
    });
}

// Inicialización
actualizarBadge();