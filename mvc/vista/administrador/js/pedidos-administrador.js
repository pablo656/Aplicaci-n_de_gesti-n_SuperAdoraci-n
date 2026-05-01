function mostrarModalEliminar(id) {
    document.getElementById('modal-eliminar').style.display = 'flex';
    document.getElementById('input-id-pedido-eliminar').value = id;
}

function cerrarModalEliminar() {
    document.getElementById('modal-eliminar').style.display = 'none';
}

function mostrarModalCompletar(id) {
    document.getElementById('modal-completar').style.display = 'flex';
    document.getElementById('input-id-pedido-completar').value = id;
}

function cerrarModalCompletar() {
    document.getElementById('modal-completar').style.display = 'none';
}

function mostrarModalNota(texto) {
    document.getElementById('modal-nota-texto').textContent = texto;
    document.getElementById('modal-nota').style.display = 'flex';
}

function cerrarModalNota() {
    document.getElementById('modal-nota').style.display = 'none';
}

window.onclick = function(event) {
    const modalEliminar = document.getElementById('modal-eliminar');
    const modalCompletar = document.getElementById('modal-completar');
    const modalNota = document.getElementById('modal-nota');
    if (event.target == modalEliminar) cerrarModalEliminar();
    if (event.target == modalCompletar) cerrarModalCompletar();
    if (event.target == modalNota) cerrarModalNota();
}
