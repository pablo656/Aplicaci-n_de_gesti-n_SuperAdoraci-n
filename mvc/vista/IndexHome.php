<?php
//Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
session_start();
//Este archivo se usara para Home, Log in, Sing in y Perfil
require_once("../controller/controller_user.php");
$controller=new Controller_user();
$action=$_GET["action"] ?? "home";
if($action=="log"||$action=="comprobar"||$action=="log_fallido"){
    $titulo="Log in";
    $css="<link rel='stylesheet' href='css/log_in.css'>";
}else if($action=="sing"||$action=="crear"||$action=="sing_fallido"){
    $titulo="Sing in";
    $css="<link rel='stylesheet' href='css/log_in.css'>";
}elseif($action=="perfil"){
    $titulo="Perfil";
    $css=null;
}else{
    $titulo="Home";
    $css=null;
    require("../vista/layerHeader.php");
}

if($action=="log"){
    $controller->log();
}else if($action=="log_fallido"){
    $controller->log();
    //CAMBIAR!!!:Cambiar mensaje a uno más apropiado proximamente
    echo "<script>alert('Error iniciar sessión')</script>";
}else if($action=="comprobar"){

}else if($action=="sing"){
    $controller->sing();
}else if($action=="sing_fallido"){
    //En caso de que falle el sing en el servidor
     $controller->sing();
     //CAMBIAR!!!:Cambiar mensaje a uno más apropiado proximamente
     echo "<script>alert('Error: El usuario ya existe')</script>";
}else if($action=="crear"){
    $nombre=$_POST["user"];
    $email=$_POST["email"];
    $pass=$_POST["pass"];
    $controller->register($nombre,$pass,$email);
}else if($action=="perfil"){
    $controller->perfil();
}else if($action=="log_out"){
    session_destroy();
    header("Location:indexHome.php?action=home");
}else{
    $controller->home();
}
if($action=="home"){
    require("../vista/footer.html");
}

?>