function cambiarCantidad(id, cambio){
    const span = document.getElementById("cantidad-" + id);
    let cantidad = parseInt(span.textContent) + cambio;
    let boton_restar=document.getElementById("restar-"+id);
    let boton_borar=document.getElementById("borrar-"+id);
    if(cantidad == 1){
       boton_restar.style.display="none";
       boton_borar.style.display="block";
    }else{
        boton_restar.style.display="block";
        boton_borar.style.display="none";
    }

    span.textContent = cantidad;

    // Enviar al servidor via fetch
    fetch("?action=actualizar_cantidad", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id + "&cantidad=" + cantidad
    });
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
    let contadores=document.getElementsByClassName("contador");
    for(let i=0;i<contadores.length;i++){
        let texto = contadores[i].querySelector("span").textContent.trim();
        let cantidad = parseInt(texto);
        if(cantidad==1){
            let boton_borrar=contadores[i].children[0];
            let boton_restar=contadores[i].children[1];
            boton_restar.style.display="none";
            boton_borrar.style.display="block";
        }
    }
}
window.onload=inicializar();
