async function cambiarCantidad(id, cambio){ // ← async
    const span = document.getElementById("cantidad-" + id);
    let cantidad = parseInt(span.textContent) + cambio;
    let boton_restar = document.getElementById("restar-" + id);
    let boton_borar = document.getElementById("borrar-" + id);

    if(cambio == 1){
        const ok = await comprobarStock(id, cantidad); // ← await
        if(!ok) return false;
    }

    if(cantidad == 1){
        boton_restar.style.display = "none";
        boton_borar.style.display = "block";
    }else{
        boton_restar.style.display = "block";
        boton_borar.style.display = "none";
    }

    span.textContent = cantidad;

    fetch("?action=actualizar_cantidad", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id + "&cantidad=" + cantidad
    });
}
//async significa asincrono, se hace de esta manera para no tener que cargar la página y await sirve para esperar a los datos que llegan de manera asincrona
async function comprobarStock(id, cantidad){
    const r = await fetch("?action=comprobar_stock", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id + "&cantidad=" + cantidad
    });
    const data = await r.json();
    if(data.ok){
        return true;
    }else{
        alert("No queda más stock de este producto");
        return false;
    }
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