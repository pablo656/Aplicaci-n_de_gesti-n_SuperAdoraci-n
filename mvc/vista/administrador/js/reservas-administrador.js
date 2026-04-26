function mostrarModalEliminar(id) {
    // 1. Mostramos el modal
    document.getElementById('modal-eliminar').style.display = 'flex';
    
    // 2. CORRECCIÓN: Asegúrate de que el ID coincida con el del HTML del modal
    let input = document.getElementById('input-id-reserva-modal');
    
    if(input) {
        input.value = id;
    } else {
        console.error("No se encontró el input con ID 'input-id-reserva-modal'");
    }
}
function cerrarModalEliminar() {
    document.getElementById('modal-eliminar').style.display = 'none';
}

// Cerrar si hacen clic fuera de la caja blanca
window.onclick = function(event) {
    let modal = document.getElementById('modal-eliminar');
    if (event.target == modal) {
        cerrarModalEliminar();
    }
}
