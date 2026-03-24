<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>

<form method="post" id="sing" action="?action=crear">
    <div class="form-header">
        <h1>Sign in</h1>
        <p>Crea tu cuenta nueva</p>
    </div>
    <div class="form-body">
        <div class="field">
            <label for="user">Usuario</label>
            <input type="text" id="user" name="user" placeholder="Elige un nombre de usuario">
        </div>
        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="tu@email.com">
        </div>
        <div class="field">
            <label for="pass">Contraseña</label>
            <input type="password" id="pass" name="pass" placeholder="Crea una contraseña">
        </div>
        <input type="submit" name="sign" value="Crear cuenta">
        <div class="enlaces">
            <a href="?action=log">Log in</a>
            <a href="?action=Home">Volver atrás</a>
        </div>
    </div>
</form>
<script src="js/verificacion_sing_in.js"></script>
</body>
</html>
