<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>

<form method="post" id="sing" action="?action=crear">
    <h1>Sign in</h1>
    <label>Usuario</label><br>
    <input type="text" name="user"><br>
    <label>Email</label><br>
    <input type="email" name="email"><br>
    <label>Contraseña</label><br>
    <input type="password" name="pass"><br><br>
    <input type="submit" name="sign" value="Sign in"> 
    <div class="enlaces">
        <a href="?action=log">Log in</a>
        <a href="?action=Home">Volver atrás</a>
    </div>
</form>
<script src="js/verificacion_sing_in.js"></script>
</body>
</html>