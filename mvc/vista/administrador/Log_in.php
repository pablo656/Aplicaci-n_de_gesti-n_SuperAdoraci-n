<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: /administrador/log");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="/mvc/vista/css/log_in.css">
</head>
<body>
<main>
<form method="post" id="log" action="/admin/login?action=comprobar">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
    <div class="form-header">
        <h1>Iniciar sessión</h1>
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
            <a href="/administrador/inicio">Volver atrás</a>
        </div>
    </div>
</form>
<script src="/mvc/vista/js/verificacion_log_in.js"></script>
</main>
</body>
</html>
