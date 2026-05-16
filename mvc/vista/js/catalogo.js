/**
 * Helper para obtener el token CSRF de forma segura.
 * Busca primero el input global que añadimos al inicio del body.
 */
const getCSRFToken = () => {
    const globalToken = document.getElementById("csrf_token_global");
    if (globalToken && globalToken.value) return globalToken.value;

    const genericToken = document.querySelector('input[name="csrf_token"]');
    return genericToken ? genericToken.value : "";
};

/**
 * Función principal para botones + / - (Productos por unidad)
 */
async function cambiarCantidad(id, cambio) {
    const span = document.getElementById("cantidad-" + id);
    const btnRestar = document.getElementById("restar-" + id);
    const btnBorrar = document.getElementById("borrar-" + id);
    
    if (!span) return;

    let cantidadActual = parseInt(span.textContent);
    let nuevaCantidad = cantidadActual + cambio;

    // Validación de stock si sumamos
    if (cambio === 1) {
        const tieneStock = await comprobarStock(id, nuevaCantidad);
        if (!tieneStock) return;
    }

    // UI: Alternar entre el botón "-" y la papelera si la cantidad es 1
    if (nuevaCantidad <= 1) {
        nuevaCantidad = 1;
        if (btnRestar) btnRestar.style.display = "none";
        if (btnBorrar) btnBorrar.style.display = "block";
    } else {
        if (btnRestar) btnRestar.style.display = "block";
        if (btnBorrar) btnBorrar.style.display = "none";
    }

    span.textContent = nuevaCantidad;
    ejecutarFetch("actualizar_cantidad", `id_producto=${id}&cantidad=${nuevaCantidad}`);
}

/**
 * Lógica para productos vendidos por PESO (Kg)
 */
function validarPeso(input) {
    let cant = parseFloat(input.value);
    if (isNaN(cant) || cant <= 0) {
        input.value = "0.1";
    } else {
        input.value = cant.toFixed(1);
    }
}

async function cambiarCantidadPeso(id, valor) {
    let cant = parseFloat(valor);
    if (isNaN(cant) || cant < 0.1) cant = 0.1;
    
    const cantidadFormateada = cant.toFixed(1);

    // Validamos stock antes de procesar el cambio de peso
    const tieneStock = await comprobarStock(id, cantidadFormateada);
    
    if (tieneStock) {
        ejecutarFetch("actualizar_cantidad", `id_producto=${id}&cantidad=${cantidadFormateada}`);
        // Actualizamos el respaldo del valor correcto
        const input = document.getElementById("cantidad-" + id);
        if (input) input.setAttribute('data-anterior', cantidadFormateada);
    } else {
        // Si no hay stock, revertimos el input al último valor válido
        const input = document.getElementById("cantidad-" + id);
        if (input && input.hasAttribute('data-anterior')) {
            input.value = input.getAttribute('data-anterior');
        } else {
            window.location.reload(); 
        }
    }
}

/**
 * Función genérica para enviar datos al Index vía POST
 */
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
                console.warn("Sesión expirada, recargando...");
                window.location.reload();
            }
            return data;
        } catch (e) {
            return text;
        }
    });
}

/**
 * Comprobar disponibilidad en servidor
 */
async function comprobarStock(id, cantidad) {
    const token = getCSRFToken();
    try {
        const response = await fetch("?action=comprobar_stock", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id_producto=${id}&cantidad=${cantidad}&csrf_token=${token}`
        });

        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            alert("Error de sesión. La página se recargará.");
            window.location.reload();
            return false;
        }

        if (data.ok) return true;

        if (data.error === "CSRF_FAIL") {
            window.location.reload();
            return false;
        }

        alert("No queda más stock de este producto");
        return false;
    } catch (error) {
        console.error("Error de conexión:", error);
        return false;
    }
}

/**
 * Eliminar una reserva por completo
 */
function borrarReserva(id) {
    ejecutarFetch("borrar_reserva", `id_producto=${id}`)
    .then(() => {
        window.location.reload();
    });
}

/**
 * Configuración inicial al cargar la página
 */
function inicializar() {
    // 1. Configurar visibilidad inicial de botones +/-
    document.querySelectorAll(".contador").forEach(cont => {
        const span = cont.querySelector("span[id^='cantidad-']");
        if (span) {
            const id = span.id.replace("cantidad-", "");
            const cant = parseInt(span.textContent);
            const btnRestar = document.getElementById("restar-" + id);
            const btnBorrar = document.getElementById("borrar-" + id);
            
            if (cant <= 1) {
                if (btnRestar) btnRestar.style.display = "none";
                if (btnBorrar) btnBorrar.style.display = "block";
            }
        }
    });

    // 2. Guardar valores iniciales de inputs de peso para poder revertir si falla el stock
    document.querySelectorAll(".input-peso").forEach(input => {
        input.setAttribute('data-anterior', input.value);
    });
}

window.onload = inicializar;