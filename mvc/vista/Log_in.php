<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>

<form method="post" id="log"action="?action=comprobar">
    <h1>Log in</h1>
    <label>Usuario</label><br>
    <input type="text" name="user"><br>
    <label>Contraseña</label><br>
    <input type="password" name="pass"><br><br>
    <input type="submit" name="log" value="Log in">
    <div class="enlaces">
        <a href="?action=sing">Sign in</a>
        <a href="?action=Home">Volver atrás</a>
    </div>
</form>
<script src="js/verificacion_log_in.js"></script>
</body>
</html>