<?php
define('ACCESO_PERMITIDO', true);
//Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
//Este archivo se usara para Home, Log in, Sing in y Perfil
require_once("../controller/controller_user.php");
require_once("../controller/productoController.php");
$controller=new Controller_user();
$controller_producto=new ProductoController();
$action=$_GET["action"] ?? "home";
if($action=="log"||$action=="comprobar"||$action=="log_fallido"||$action=="log_bloqueado"){
    $titulo="Iniciar sesión";
    $css="<link rel='stylesheet' href='css/log_in.css'>";
}else if($action=="sing"||$action=="crear"||$action=="sing_fallido"){
    $titulo="Registrarse";
    $css="<link rel='stylesheet' href='css/log_in.css'>";
}elseif($action=="perfil"){
    $titulo="Perfil";
    $css="<link rel='stylesheet' href='css/perfil.css'>";
}else{
    $titulo="Home";
    $css="<link rel='stylesheet' href='css/inicio.css'>";
    require("../vista/layerHeader.php");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // En lugar de un die() con texto, devolvemos un JSON de error
        header('Content-Type: application/json');
        echo json_encode([
            "ok" => false, 
            "error" => "CSRF_FAIL", 
            "msg" => "Sesión caducada, recarga la página."
        ]);
        exit();
    }
}
if($action=="log"){
    $controller->log();
}else if($action=="log_fallido"){
    $controller->log();
    echo "<script>alert('Usuario o contraseña incorrecto')</script>";
}else if($action=="log_bloqueado"){
    $minutos = (int)($_GET["min"] ?? 15);
    $controller->log();
    echo "<script>alert('Cuenta bloqueada por demasiados intentos fallidos. Inténtalo de nuevo en $minutos minuto" . ($minutos === 1 ? "" : "s") . ".')</script>";
}else if($action=="comprobar"){
    $nombre=$_POST["user"];
    $pass=$_POST["pass"];
    $controller->loginar($nombre,$pass);
}else if($action=="sing"){
    $controller->sing();
}else if($action=="sing_fallido"){
    //En caso de que falle el sing en el servidor
     $controller->sing();
     echo "<script>alert('Error: El usuario ya existe')</script>";
}else if($action=="crear"){
    $nombre=$_POST["user"];
    $email=$_POST["email"];
    $pass=$_POST["pass"];
    $controller->register($nombre,$pass,$email);
}else if($action=="perfil"){
    $controller->perfil();
}else if($action=="confirmar_email"){
    $token=$_GET["token"] ?? "";
    $controller->confirmar_email($token);
}else if($action=="log_out"){
    session_destroy();
    header("Location:IndexHome.php?action=home");
}else{
    $controller_producto->home();
}
if($action=="home"){
    require("../vista/footer.html");
}

?>