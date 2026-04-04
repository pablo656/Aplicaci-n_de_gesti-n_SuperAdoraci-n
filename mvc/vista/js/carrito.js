let formulario_reservas = document.getElementById("confirmar_reservas");
formulario_reservas.addEventListener("submit", confirmar,true);

function confirmar(event){
    event.preventDefault();
    if(confirm("¿Deseas realizar esta reserva?")){
        event.target.submit();
    }
}
async function cambiarCantidad(id, cambio, precio){
    const span = document.getElementById("cantidad-" + id);
    let cantidad = parseInt(span.textContent) + cambio;
    let boton_restar = document.getElementById("restar-" + id);
    let precio_item = document.getElementById("precio-" + id);
    let precio_reserva_aside = document.getElementById("precio-reserva-" + id);
    let subtotal_reservas = document.getElementById("subtotal_reservas");
    let contenedor_total = document.getElementById("contenedor_precio");

    if(cambio == 1){
        const ok = await comprobarStock(id, cantidad);
        if(!ok) return false;
    }

    if(cantidad == 1){
        boton_restar.style.display = "none";
    }else{
        boton_restar.style.display = "block";
    }

    span.textContent = cantidad;

    precio_item.textContent = (cantidad * precio).toFixed(2) + " €";
    precio_reserva_aside.textContent = (cantidad * precio).toFixed(2) + " €";

    let subtotal = parseFloat(subtotal_reservas.textContent);
    subtotal += cambio * precio;
    subtotal_reservas.textContent = subtotal.toFixed(2) + " €";

    let total = parseFloat(contenedor_total.textContent);
    total += cambio * precio;
    contenedor_total.textContent = total.toFixed(2) + " €";

    fetch("?action=actualizar_cantidad", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id + "&cantidad=" + cantidad
    });
}

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
    let precio_item = document.getElementById("precio-p-" + id);
    let precio_pedido_aside = document.getElementById("precio-pedido-" + id);
    let subtotal_pedidos = document.getElementById("subtotal_pedidos");
    let contenedor_total = document.getElementById("contenedor_precio");

    if(nuevo === parseInt(span.textContent)) return;

    span.textContent = nuevo;
    document.getElementById("restar-p-" + id).style.display = nuevo <= 1 ? "none" : "block";

    precio_item.textContent = (nuevo * precio).toFixed(2) + " €";
    precio_pedido_aside.textContent = (nuevo * precio).toFixed(2) + " €";

    let subtotal = parseFloat(subtotal_pedidos.textContent);
    subtotal += cambio * precio;
    subtotal_pedidos.textContent = subtotal.toFixed(2) + " €";

    let total = parseFloat(contenedor_total.textContent);
    total += cambio * precio;
    contenedor_total.textContent = total.toFixed(2) + " €";

    fetch("?action=actualizar_cantidad_pedido", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_comida=" + id + "&cantidad=" + nuevo
    });
}

function borrarReserva(id){
    fetch("?action=borrar_reserva", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id_producto=" + id
    }).then(() => {
        window.location.reload();
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