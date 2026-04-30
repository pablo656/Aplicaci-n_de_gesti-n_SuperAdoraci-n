function abrirModal(id,nombre,email,rol){
     const modal = document.getElementById("modal");
    if (!modal) return;
    modal.style.display = "flex";
   let hiden=document.getElementById("id_hidden");
   hiden.value=id;
    let opciones=document.getElementsByName("rol")[0].children;
    for(let i=0;i<opciones.length;i++){
        if(opciones[i].value==rol){
            opciones[i].selected = true;
        }
    }

}
function cerrarModal() {
    const modal = document.getElementById("modal");
    if (modal) modal.style.display = "none";
}
document.addEventListener("DOMContentLoaded", function() {
    // Analizamos los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const resultado = urlParams.get('res');
    if (resultado === 'updated') {
        alert("¡Éxito! El rol del usuario ha sido actualizado correctamente.");            
        window.history.replaceState({}, document.title, window.location.pathname);
    } 
    else if (resultado === 'error') {
        alert("Error: No se pudo actualizar el rol. Inténtalo de nuevo.");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
let form=document.getElementById("add");
form.addEventListener("submit",verificar,true);
function verificar(event) {
    // 1. Prevenir el envío automático
    event.preventDefault();

    // 2. Limpiar errores previos
    document.querySelectorAll(".error").forEach(e => e.remove());

    // 3. Obtener los elementos y valores
    const form = document.getElementById("add");
    const usuario_input = document.getElementById("user");
    const email_input = document.getElementById("email");
    const pass_input = document.getElementById("pass");

    let patron_email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let correcto = true;

    // --- Validación Usuario ---
    if (usuario_input.value.trim().length === 0) {
        mostrarError(usuario_input, "El campo usuario es obligatorio");
        correcto = false;
    }

    // --- Validación Email ---
    if (email_input.value.trim().length === 0) {
        mostrarError(email_input, "El campo email es obligatorio");
        correcto = false;
    } else if (!patron_email.test(email_input.value)) {
        mostrarError(email_input, "El campo email debe ser un correo válido");
        correcto = false;
    }

    // --- Validación Contraseña ---
    if (pass_input.value.length === 0) {
        mostrarError(pass_input, "La contraseña es obligatoria");
        correcto = false;
    } else if (pass_input.value.length < 8) {
        mostrarError(pass_input, "La contraseña debe tener 8 caracteres como mínimo");
        correcto = false;
    }

    // 4. Si todo es correcto, enviar formulario
    if (correcto) {
        form.submit();
    }
}

// Función auxiliar para no repetir código al crear errores
function mostrarError(elemento, mensaje) {
    let error_p = document.createElement("p");
    error_p.textContent = mensaje;
    error_p.setAttribute("class", "error");
    // Estilo rápido para los errores si no los tienes en CSS
    error_p.style.color = "#e31b23";
    error_p.style.fontSize = "0.8rem";
    error_p.style.marginTop = "4px";
    elemento.after(error_p);
}