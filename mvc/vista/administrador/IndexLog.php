<?php
//Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
session_start();
//Este archivo se usara para Home, Log in, Sing in y Perfil
require_once("../../controller/controller_user.php");
require_once("../../controller/productoController.php");
$controller=new Controller_user();
$controller_producto=new ProductoController();
$action=$_GET["action"] ?? "home";
$titulo="Iniciar sesión";
$css="<link rel='stylesheet' href='../css/log_in.css'>";

if($action=="log_fallido"){
    $controller->log();
    echo "<script>alert('Usuario o contraseña incorrecto')</script>";
}else if($action=="comprobar"){
    $nombre=$_POST["user"];
    $pass=$_POST["pass"];
    $controller->loginar($nombre,$pass);
}else if($action=="actualizar_nombre"){
    $controller->actualizar_nombre();
}else if($action=="confirmar_email"){
    $token=$_GET["token"] ?? "";
    $controller->confirmar_email($token);
}else if($action=="log_out"){
    session_destroy();
    header("Location:indexHome.php?action=home");
}else{
    $controller->log_admin();
}

?>