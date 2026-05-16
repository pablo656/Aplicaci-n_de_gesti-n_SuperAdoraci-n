/**
 * Función auxiliar para obtener el token CSRF de forma segura
 */
const getCSRFToken = () => {
    // Buscamos el token maestro o el de cualquier input
    const token = document.getElementById("csrf_token_global") || document.querySelector('input[name="csrf_token"]');
    return token ? token.value : "";
};

// --- GESTIÓN DE FORMULARIOS ---
let formulario_reservas = document.getElementById("confirmar_reservas");
if(formulario_reservas) formulario_reservas.addEventListener("submit", confirmar, true);

let formulario_pedidos = document.getElementById("confirmar_pedidos");
if(formulario_pedidos) formulario_pedidos.addEventListener("submit", confirmarPedidos, true);

function confirmar(event){
    event.preventDefault();
    if(confirm("¿Deseas realizar esta reserva?")){
        event.target.submit();
    }
}

function confirmarPedidos(event){
    event.preventDefault();
    if(confirm("¿Deseas confirmar los pedidos?")){
        event.target.submit();
    }
}

// --- GESTIÓN DE RESERVAS (PRODUCTOS) ---
async function cambiarCantidad(id, cambio, precio){
    const span = document.getElementById("cantidad-" + id);
    let cantidadActual = parseInt(span.textContent);
    let nuevaCantidad = cantidadActual + cambio;
    
    let boton_restar = document.getElementById("restar-" + id);
    let precio_item = document.getElementById("precio-" + id);
    let precio_reserva_aside = document.getElementById("precio-reserva-" + id);
    let subtotal_reservas = document.getElementById("subtotal_reservas");
    let contenedor_total = document.getElementById("contenedor_precio");

    if(cambio == 1){
        const ok = await comprobarStock(id, nuevaCantidad);
        if(!ok) return false;
    }

    if(nuevaCantidad <= 1){
        nuevaCantidad = 1;
        if(boton_restar) boton_restar.style.display = "none";
    }else{
        if(boton_restar) boton_restar.style.display = "block";
    }

    span.textContent = nuevaCantidad;

    // Actualización visual de precios
    if(precio_item) precio_item.textContent = (nuevaCantidad * precio).toFixed(2) + " €";
    if(precio_reserva_aside) precio_reserva_aside.textContent = (nuevaCantidad * precio).toFixed(2) + " €";

    actualizarTotalesGlobales(cambio * precio, subtotal_reservas, contenedor_total);
    
    ejecutarFetch("actualizar_cantidad", `id_producto=${id}&cantidad=${nuevaCantidad}`);
}

/**
 * CORRECCIÓN: Gestión de peso con validación de stock asíncrona
 */
async function cambiarCantidadPeso(id, valor, precio){
    let cantidad = parseFloat(valor);
    if(isNaN(cantidad) || cantidad <= 0) return;
    cantidad = parseFloat(cantidad.toFixed(1));

    const input = document.getElementById("cantidad-" + id);
    const cantidadAnterior = parseFloat(input.getAttribute("data-anterior") || valor);
    
    // 1. Validar stock antes de tocar nada
    const ok = await comprobarStock(id, cantidad);
    
    if(ok) {
        let diff = cantidad - cantidadAnterior;
        let precio_item = document.getElementById("precio-" + id);
        let precio_reserva_aside = document.getElementById("precio-reserva-" + id);
        let subtotal_reservas = document.getElementById("subtotal_reservas");
        let contenedor_total = document.getElementById("contenedor_precio");

        if(precio_item) precio_item.textContent = (cantidad * precio).toFixed(2) + " €";
        if(precio_reserva_aside) precio_reserva_aside.textContent = (cantidad * precio).toFixed(2) + " €";

        actualizarTotalesGlobales(diff * precio, subtotal_reservas, contenedor_total);

        input.setAttribute("data-anterior", cantidad);
        ejecutarFetch("actualizar_cantidad", `id_producto=${id}&cantidad=${cantidad}`);
    } else {
        // Revertir el input al valor anterior si no hay stock
        input.value = cantidadAnterior.toFixed(1);
    }
}

// --- GESTIÓN DE PEDIDOS ---
function cambiarCantidadPedido(id, cambio, precio){
    const span = document.getElementById("cantidad-p-" + id);
    const actual = parseInt(span.textContent);
    const nuevo = Math.min(30, Math.max(1, actual + cambio));
    
    if(nuevo === actual) return;

    let precio_item = document.getElementById("precio-p-" + id);
    let precio_pedido_aside = document.getElementById("precio-pedido-" + id);
    let subtotal_pedidos = document.getElementById("subtotal_pedidos");
    let contenedor_total = document.getElementById("contenedor_precio");

    span.textContent = nuevo;
    
    const btnRestar = document.getElementById("restar-p-" + id);
    if(btnRestar) btnRestar.style.display = nuevo <= 1 ? "none" : "block";
    
    if(precio_item) precio_item.textContent = (nuevo * precio).toFixed(2) + " €";
    if(precio_pedido_aside) precio_pedido_aside.textContent = (nuevo * precio).toFixed(2) + " €";

    actualizarTotalesGlobales(cambio * precio, subtotal_pedidos, contenedor_total);
    ejecutarFetch("actualizar_cantidad_pedido", `id_comida=${id}&cantidad=${nuevo}`);
}

// --- UTILIDADES ---
function ejecutarFetch(action, params) {
    const token = getCSRFToken();
    return fetch(`?action=${action}`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `${params}&csrf_token=${token}`
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (data.error === "CSRF_FAIL") {
                window.location.reload();
            }
            return data;
        } catch (e) { return text; }
    });
}

async function comprobarStock(id, cantidad){
    const token = getCSRFToken();
    try {
        const r = await fetch("?action=comprobar_stock", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_producto=${id}&cantidad=${cantidad}&csrf_token=${token}`
        });
        const text = await r.text();
        let data;
        try { data = JSON.parse(text); } catch(e) { window.location.reload(); return false; }

        if(data.ok) return true;
        
        if(data.error === "CSRF_FAIL") { window.location.reload(); return false; }

        alert("No queda más stock de este producto");
        return false;
    } catch (e) { return false; }
}

function borrarReserva(id){
    ejecutarFetch("borrar_reserva", `id_producto=${id}`).then(() => window.location.reload());
}

function borrarPedido(id){
    ejecutarFetch("borrar_pedido", `id_comida=${id}`).then(() => window.location.reload());
}

function actualizarTotalesGlobales(montoCambio, elSubtotal, elTotal) {
    if(elSubtotal) {
        let sub = parseFloat(elSubtotal.textContent) || 0;
        elSubtotal.textContent = (sub + montoCambio).toFixed(2) + " €";
    }
    if(elTotal) {
        let tot = parseFloat(elTotal.textContent) || 0;
        elTotal.textContent = (tot + montoCambio).toFixed(2) + " €";
    }
}

function validarPeso(input) {
    let cantidad = parseFloat(input.value);
    if (isNaN(cantidad) || cantidad <= 0) {
        input.value = "0.1";
    } else {
        input.value = cantidad.toFixed(1);
    }
}

function inicializar(){
    let contadores = document.querySelectorAll(".contador");
    contadores.forEach(cont => {
        let span = cont.querySelector("span[id^='cantidad-']");
        if(span) {
            let cant = parseInt(span.textContent);
            let id = span.id.replace("cantidad-", "");
            let btn = document.getElementById("restar-" + id);
            if(btn) btn.style.display = cant <= 1 ? "none" : "block";
        }
    });

    let inputs = document.querySelectorAll(".input-peso");
    inputs.forEach(inp => inp.setAttribute("data-anterior", inp.value));
}

window.onload = inicializar;