<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
</head>
<body>
<header>
    <img src="imagenes/Logo.jpeg" alt="Logo">
</header>

<nav>
    <div>
        <a href="#">Inicio</a>
        <a href="#">Catalogo</a>
        <a href="#">Pedidos</a>
    </div>
    <div>
        <?php
        if(isset($_SESSION["id"]) && isset($_SESSION["nombre"])&&isset($_SESSION["email"])){?>
            <a href="#">Perfil <i class="fi fi-sr-user" style="color: white; font-size: 1.2em;"></i></a>
            <?php
        }else{?>
        <a href="#">Log in</a>
        <a href="#">Sing in</a>
        <?php
        }?>
    </div>
</nav>
