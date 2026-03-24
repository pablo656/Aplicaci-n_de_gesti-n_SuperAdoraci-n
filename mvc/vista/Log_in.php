<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>

<form method="post" id="log" action="?action=comprobar">
    <div class="form-header">
        <h1>Log in</h1>
    </div>
    <div class="form-body">
        <div class="field">
            <label for="user">Usuario</label>
            <input type="text" id="user" name="user" placeholder="Tu nombre de usuario">
        </div>
        <div class="field">
            <label for="pass">Contraseña</label>
            <input type="password" id="pass" name="pass" placeholder="Tu contraseña">
        </div>
        <input type="submit" name="log" value="Entrar">
        <div class="enlaces">
            <a href="?action=sing">Sign in</a>
            <a href="?action=Home">Volver atrás</a>
        </div>
    </div>
</form>
<script src="js/verificacion_log_in.js"></script>
</body>
</html>
