let form = document.getElementById("sing");
let usuario_input = document.getElementsByName("user")[0];
let email_input = document.getElementsByName("email")[0];
let pass_input = document.getElementsByName("pass")[0];

form.addEventListener("submit", verificar, true);

function verificar(event) {
    document.querySelectorAll(".error").forEach(e => e.remove());
    event.preventDefault();

    let usuario = usuario_input.value;
    let email = email_input.value;
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
    if (email.length == 0) {
        let error_email = document.createElement("p");
        error_email.textContent = "El campo email es obligatorio";
        error_email.setAttribute("class", "error");
        email_input.after(error_email);
        correcto = false;
    } else {
        if (!patron_email.test(email)) {
            let error_email = document.createElement("p");
            error_email.textContent = "El campo email debe ser un correo válido";
            error_email.setAttribute("class", "error");
            email_input.after(error_email);
            correcto = false;
        }
    }
    if (pass.length == 0) {
        let error_pass = document.createElement("p");
        error_pass.textContent = "La contraseña es obligatoria";
        error_pass.setAttribute("class", "error");
        pass_input.after(error_pass);
        correcto = false;
    } else {
        if (pass.length < 8) {
            let error_pass = document.createElement("p");
            error_pass.textContent = "La contraseña debe tener 8 caracteres como mínimo";
            error_pass.setAttribute("class", "error");
            pass_input.after(error_pass);
            correcto = false;
        }
    }
    if (correcto) {
        form.submit();
    }
}