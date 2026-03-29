<!DOCTYPE html>
<html lang="en">
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
        <a href="IndexHome.php">Inicio</a>
        <a href="IndexProducto.php">Catalogo</a>
        <a href="#">Pedidos</a>
        <?php if(isset($_SESSION["id"]) && isset($_SESSION["nombre"])&&isset($_SESSION["email"])&&isset($_SESSION["rol"])):?>
        <a href="#"><i class="fi fi-sr-shopping-cart"></i></a>
        <?php endif;?>
    </div>
    <div>
        <?php
        if(isset($_SESSION["id"]) && isset($_SESSION["nombre"])&&isset($_SESSION["email"])&&isset($_SESSION["rol"])){?>
            <a href="#">Perfil <i class="fi fi-sr-user" style="color: white; font-size: 1.2em;"></i></a>
            <a href="IndexHome.php?action=log_out">Log out</i></a>
            <?php
        }else{?>
        <a href="IndexHome.php?action=log">Iniciar session</a>
        <a href="IndexHome.php?action=sing">Registrarse</a>
        <?php
        }?>
    </div>
</nav>
