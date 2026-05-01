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
        <a href="IndexInicio-administrador.php">Inicio</a>
        <a href="IndexProducto-administrador.php">Productos</a>
        <a href="IndexReservas-administrador.php">Reservas</a>
        <a href="IndexComidas-administrador.php">Comidas</a>
        <a href="IndexPedidos-administrador.php">Pedidos</a>

    </div>
    <div>
        <a href="IndexUsuarios-administrador.php">Usuarios</a>
        <a href="IndexPerfil.php">Perfil <i class="fi fi-sr-user" style="color: white; font-size: 1.2em;"></i></a>
        <a href="../IndexHome.php?action=log_out">Cerrar sesión</a>

    </div>
</nav>
