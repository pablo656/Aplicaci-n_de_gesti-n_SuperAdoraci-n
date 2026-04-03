function abrirModal(idComida, nombre) {
    document.getElementById('modalIdComida').value = idComida;
    document.getElementById('modalTitulo').textContent = 'Pedir: ' + nombre;
    document.getElementById('cantidad').value = 1;
    document.getElementById('cantidadDisplay').textContent = 1;
    document.getElementById('mensaje').value = '';
    document.querySelector('.btn-menos').style.visibility = 'hidden';
    document.getElementById('modalPedir').classList.add('activo');
}

function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    const display = document.getElementById('cantidadDisplay');
    const nuevo = Math.max(1, parseInt(input.value) + delta);
    input.value = nuevo;
    display.textContent = nuevo;
    document.querySelector('.btn-menos').style.visibility = nuevo <= 1 ? 'hidden' : 'visible';
}

function cerrarModal() {
    document.getElementById('modalPedir').classList.remove('activo');
}

document.getElementById('modalPedir').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

function ajustarPedido(idComida, cambio) {
    fetch('IndexPedidos.php?action=actualizar_cantidad_cookie', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_comida=' + encodeURIComponent(idComida) + '&cambio=' + encodeURIComponent(cambio)
    })
    .then(response => response.json())
    .then(() => {
        const elemento = document.getElementById('cantidad-pedido-' + idComida);
        if (elemento) {
            let valor = parseInt(elemento.textContent) || 0;
            valor = Math.max(0, valor + cambio);
            elemento.textContent = valor;
        }
    });
}
