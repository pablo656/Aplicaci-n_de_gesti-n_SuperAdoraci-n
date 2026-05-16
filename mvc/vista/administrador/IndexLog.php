
<?php
define('ACCESO_PERMITIDO', true);
//Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
//Este archivo se usara para Home, Log in, Sing in y Perfil
require_once("../../controller/controller_user.php");
require_once("../../controller/productoController.php");
$controller=new Controller_user();
$controller_producto=new ProductoController();
$titulo="Iniciar sesión";
$css="<link rel='stylesheet' href='../css/log_in.css'>";
$action=$_GET["action"] ?? "home";
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
    if($action=="log_fallido"){
        $controller->log_admin();
        echo "<script>alert('Usuario o contraseña incorrecto')</script>";
    }else if($action=="log_bloqueado"){
        $minutos = (int)($_GET["min"] ?? 15);
        $controller->log_admin();
        echo "<script>alert('Cuenta bloqueada por demasiados intentos fallidos. Inténtalo de nuevo en $minutos minuto" . ($minutos === 1 ? "" : "s") . ".')</script>";
    }else if($action=="comprobar"){
        $nombre=$_POST["user"];
        $pass=$_POST["pass"];
        $controller->loginar_admin($nombre,$pass);
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