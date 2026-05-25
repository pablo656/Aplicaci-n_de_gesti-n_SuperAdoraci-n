<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexInicio-administrador.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo?></title>
    <link rel="stylesheet" href="../css/header_style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <!--Poner el campo link del CSS en el INDEX para que sea distinto dependiendo de la página--> 
    <?php echo $css ?>

</head>
<body>
<header>
    <picture>
        <source media="(max-width: 768px)" srcset="../imagenes/banner_M.png">
        <img src="../imagenes/banner.png" alt="Logo">
    </picture>
    
</header>
<nav>
    <div>
        <a href="/administrador/inicio">Inicio</a>
        <a href="/administrador/productos">Productos</a>
        <a href="/administrador/reservas">Reservas</a>
        <a href="/administrador/comidas">Comidas</a>
        <a href="/administrador/pedidos">Pedidos</a>
    </div>
    <div>
        <a href="/administrador/usuarios">Usuarios</a>
        <a href="/perfil">Perfil <i class="fi fi-sr-user" style="color: white; font-size: 1.2em;"></i></a>
        <a href="/?action=log_out">Cerrar sesión</a>
    </div>
</nav>
<input type="hidden" id="csrf_token_global" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
