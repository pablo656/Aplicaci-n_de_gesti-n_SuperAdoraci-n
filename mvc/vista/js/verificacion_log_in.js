let form = document.getElementById("log");
let usuario_input = document.getElementsByName("user")[0];
let pass_input = document.getElementsByName("pass")[0];

form.addEventListener("submit", verificar, true);

function verificar(event) {
    document.querySelectorAll(".error").forEach(e => e.remove());
    event.preventDefault();

    let usuario = usuario_input.value;
    let pass = pass_input.value;
    let patron_email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    let correcto = true;

    if (usuario.length == 0) {
        let error_usuario = document.createElement("p");
        error_usuario.textContent = "El campo usuario es obligatorio";
        error_usuario.setAttribute("class", "error");
        usuario_input.after(error_usuario);
        correcto = false;
    }
    if (pass.length == 0) {
        let error_pass = document.createElement("p");
        error_pass.textContent = "La contraseña es obligatoria";
        error_pass.setAttribute("class", "error");
        pass_input.after(error_pass);
        correcto = false;
    } 
    if (correcto) {
        form.submit();
    }
}