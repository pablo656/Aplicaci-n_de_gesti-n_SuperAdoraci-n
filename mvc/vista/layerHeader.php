<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexHome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo?></title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <!--Poner el campo link del CSS en el INDEX para que sea distinto dependiendo de la página--> 
    <?php echo $css ?>

</head>
<body>
<header>
    <picture>
        <source media="(max-width: 768px)" srcset="imagenes/banner_M.png">
        <img src="imagenes/banner.png" alt="Logo">
    </picture>
    
</header>
<nav>
    <div>
        <a href="/">Inicio</a>
        <a href="/catalogo">Catálogo</a>
        <a href="/pedidos">Pedidos</a>
    </div>
    <div>
        <?php if(isset($_SESSION["id"]) && isset($_SESSION["nombre"]) && isset($_SESSION["email"]) && isset($_SESSION["rol"])): ?>
        <a href="/carrito" aria-label="Ir al carrito de compras">
            <i class="fi fi-sr-shopping-cart"></i>
        </a>
        <?php endif; ?>
        
        <?php if(isset($_SESSION["id"]) && isset($_SESSION["nombre"]) && isset($_SESSION["email"]) && isset($_SESSION["rol"])): ?>
            <a href="/perfil">Perfil <i class="fi fi-sr-user" style="color: white; font-size: 1.2em;"></i></a>
            <a href="/?action=log_out">Cerrar sesión</a>
        <?php else: ?>
            <a href="/?action=log">Iniciar sesión</a>
            <a href="/?action=sing">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>
<input type="hidden" id="csrf_token_global" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
