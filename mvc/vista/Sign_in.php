<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexHome.php?action=sing");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="css/log_in.css">
</head>
<body>
<main>
<form method="post" id="sing" action="?action=crear">
    <div class="form-header">
        <h1>Registrarse</h1>
        <p>Crea tu cuenta nueva</p>
    </div>
    <div class="form-body">
        <?php if (!empty($_SESSION["confirm_error"])): ?>
            <p class="error"><?= htmlspecialchars($_SESSION["confirm_error"]) ?></p>
            <?php unset($_SESSION["confirm_error"]); ?>
        <?php endif; ?>
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
            <a href="?action=log">Iniciar sesión</a>
            <a href="?action=Home">Volver atrás</a>
        </div>
    </div>
</form>
<script src="js/verificacion_sing_in.js"></script>
</main>
</body>
</html>
