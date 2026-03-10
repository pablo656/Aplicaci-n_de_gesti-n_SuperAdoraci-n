<?php
//Este archivo se usara para Home, Log in, Sing in y Perfil
require_once("../controller/controller_user.php");
$controller=new Controller_user();
$action=$_GET["action"] ?? "home";
if($action=="log"||$action=="comprobar"){
    $titulo="Log in";
    $css="<link rel='stylesheet' href='css/log_in.css'>";
}else if($action=="sing"||$action=="crear"){
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
    $controller->sing();
}else if($action=="comprobar"){

}else if($action=="sing"){
    $controller->sing();

}else if($action=="crear"){

}else if($action=="perfil"){
    $controller->perfil();
}else{
    $controller->home();
}
if($action=="home"){
    require("../vista/footer.html");
}

?>