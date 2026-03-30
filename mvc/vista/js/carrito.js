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
