/**
 * 1. OBJETO DE DATOS
 */
const subcategorias = {
    "Comida": ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
    "Bebidas": ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
    "Limpieza_hogar": ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
    "Mascotas": ["Gatos", "Perros", "Pájaros"],
    "Papeleria_oficina": ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
    "Salud_bienestar": []
};

/**
 * 2. FUNCIONES DEL MODAL
 */

function abrirModal(id, categoria, subcategoriaSeleccionada) {
    const modal = document.getElementById("modal");
    if (!modal) return;
    
    modal.style.display = "flex";

    const selectSub = document.getElementById("subcategorias");
    if (selectSub) {
        selectSub.innerHTML = ""; 
        const lista = subcategorias[categoria]; 
        
        if (lista) {
            lista.forEach(nombreSub => {
                const option = document.createElement("option");
                option.value = nombreSub;
                option.textContent = nombreSub;
                if (nombreSub === subcategoriaSeleccionada) {
                    option.selected = true;
                }
                selectSub.appendChild(option);
            });
        }
    }

    actualizarPrecioFinal();

    const form = document.getElementById("form-modal");
    if (form) form.onsubmit = comprobar; 
}

function cerrarModal() {
    const modal = document.getElementById("modal");
    if (modal) modal.style.display = "none";
}

function selectSubcategotia() {
    const categoria = document.getElementById("categoria").value;
    const selectSub = document.getElementById("subcategorias");
    if (!selectSub) return;

    selectSub.innerHTML = "";
    const lista = subcategorias[categoria];
    if (lista) {
        lista.forEach(nombreSub => {
            const option = document.createElement("option");
            option.value = nombreSub;
            option.textContent = nombreSub;
            selectSub.appendChild(option);
        });
    }
}

/**
 * 3. LÓGICA DE CÁLCULO Y PREVIEW
 */

function actualizarPrecioFinal() {
    const precioInput = document.getElementById("precio");
    const descuentoInput = document.getElementById("descuento");
    const visor = document.getElementById("precio_descontado");

    if (precioInput && descuentoInput && visor) {
        const precioBase = parseFloat(precioInput.value) || 0;
        const descuento = parseFloat(descuentoInput.value) || 0;
        const final = precioBase - (precioBase * (descuento / 100));
        visor.textContent = final.toFixed(2);
    }
}

/**
 * 4. EVENTOS (DELEGACIÓN)
 */

document.addEventListener("change", function(e) {
    if (e.target && e.target.id === "precio_por_peso") {
        const inputStock = document.getElementById("stock");
        if (!inputStock) return;

        if (e.target.checked) {
            inputStock.step = "0.1";
        } else {
            inputStock.step = "1";
            if (inputStock.value) {
                inputStock.value = Math.floor(parseFloat(inputStock.value));
            }
        }
    }

    if (e.target && e.target.id === "input_imagen") {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById("preview").src = event.target.result;
        };
        if (e.target.files[0]) {
            reader.readAsDataURL(e.target.files[0]);
        }
    }
});

document.addEventListener("input", function(e) {
    const target = e.target;

    if (target.id === "precio" || target.id === "descuento") {
        actualizarPrecioFinal();
    }

    if (target.id === "stock" || target.id === "precio") {
        // Permitir que el usuario escriba el punto o la coma sin transformarlo a 0
        if (target.value.includes(",")) {
            target.value = target.value.replace(",", ".");
        }
        
        // Solo limitamos decimales si el valor es un número válido y no termina en punto
        if (target.id === "stock" && !target.value.endsWith(".")) {
            const checkPeso = document.getElementById("precio_por_peso");
            if (checkPeso && checkPeso.checked) {
                let valor = target.value;
                if (valor.includes(".") && valor.split(".")[1].length > 1) {
                    target.value = valor.substring(0, valor.indexOf(".") + 2);
                }
            }
        }
    }

    if (target.id === "categoria") {
        selectSubcategotia();
    }

    // ELIMINADO: Ya no forzamos el 0 aquí para permitir borrar caracteres
});

document.addEventListener("focusout", function(e) {
    const target = e.target;
    
    // Si el campo queda vacío al salir, le ponemos 0
    if (["stock", "descuento", "precio"].includes(target.id)) {
        if (target.value === "" || target.value === ".") target.value = 0;
    }

    if (target.id === "precio") {
        target.value = parseFloat(target.value).toFixed(2);
    }
    
    if (target.id === "stock") {
        const checkPeso = document.getElementById("precio_por_peso");
        if (checkPeso && checkPeso.checked) {
            target.value = parseFloat(target.value).toFixed(1);
        } else {
            target.value = parseInt(target.value);
        }
    }
});

/**
 * 5. VALIDACIÓN FINAL
 */
function comprobar(event) {
    let errores = [];
    const nombre = document.getElementById("nombre").value.trim();
    const stock = document.getElementById("stock").value;
    const precio = document.getElementById("precio").value;
    const descuento = document.getElementById("descuento").value;
    const checkPeso = document.getElementById("precio_por_peso");

    if (nombre === "") errores.push("El nombre es obligatorio.");

    const regexStock = (checkPeso && checkPeso.checked) ? /^\d+([.,]\d{1})?$/ : /^\d+$/;
    if (!regexStock.test(stock)) errores.push("Stock no válido.");

    const regexPrecio = /^\d+([.,]\d{1,2})?$/;
    if (!regexPrecio.test(precio) || parseFloat(precio.replace(',','.')) <= 0) {
        errores.push("Precio no válido.");
    }

    const regexDesc = /^(0|[1-9][0-9]?)$/;
    if (!regexDesc.test(descuento)) errores.push("Descuento (0-99).");

    if (errores.length > 0) {
        event.preventDefault();
        alert("Errores:\n- " + errores.join("\n- "));
    } else if (!confirm("¿Deseas guardar los cambios?")) {
        event.preventDefault();
    }
}