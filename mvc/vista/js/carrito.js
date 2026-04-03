function cambiarCantidad(id, cambio,precio){
    const span = document.getElementById("cantidad-" + id);
    let cantidad = parseInt(span.textContent) + cambio;
    let boton_restar=document.getElementById("restar-"+id);
    let boton_borar=document.getElementById("borrar-"+id);
    let contenedor_precio=document.getElementById("contenedor_precio");
    if(cantidad == 1){
       boton_restar.style.display="none";
       
    }else{
        boton_restar.style.display="block";
        
    }

    span.textContent = cantidad;

    // Enviar al servidor via fetch
    fetch("?action=actualizar_cantidad", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id + "&cantidad=" + cantidad
    });
    let total=parseInt(contenedor_precio.textContent);
    if(cambio==1){
        total+=precio;
    }else{
        total-=precio;
    }
    contenedor_precio.textContent = (total).toFixed(2) + " €";
}
function borrarPedido(id){
    fetch("?action=borrar_pedido", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_comida=" + id
    }).then(() => {
        window.location.reload();
    });
}

function cambiarCantidadPedido(id, cambio, precio){
    const span = document.getElementById("cantidad-p-" + id);
    const nuevo = Math.max(1, parseInt(span.textContent) + cambio);
    span.textContent = nuevo;
    document.getElementById("restar-p-" + id).style.display = nuevo <= 1 ? "none" : "block";

    fetch("?action=actualizar_cantidad_pedido", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_comida=" + id + "&cantidad=" + nuevo
    });

    const contenedor = document.getElementById("contenedor_precio");
    let total = parseFloat(contenedor.textContent);
    contenedor.textContent = (total + cambio * precio).toFixed(2) + " €";
}

function borrarReserva(id){
    fetch("?action=borrar_reserva", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id
    }).then(() => {
        window.location.reload(); // ← recargar la página tras borrar
    });

}
function inicializar(){
    let contadores = document.getElementsByClassName("contador");
    for(let i = 0; i < contadores.length; i++){
        let span = contadores[i].querySelector("span");
        let cantidad = parseInt(span.textContent.trim());
        let id = span.id.replace("cantidad-", "");
        let boton_restar = document.getElementById("restar-" + id);
        if(cantidad == 1){
            boton_restar.style.display = "none";
        }
    }
}
window.onload = inicializar;  
