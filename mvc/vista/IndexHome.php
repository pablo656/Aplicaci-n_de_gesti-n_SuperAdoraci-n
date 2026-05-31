<?php
define('ACCESO_PERMITIDO', true);
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Configuración de la URL base para el servidor de producción (Raíz de tu dominio)
$base_url = "/";

require_once("../controller/controller_user.php");
require_once("../controller/productoController.php");

$controller = new Controller_user();
$controller_producto = new ProductoController();

$action = $_GET["action"] ?? "home";

if ($action == "log" || $action == "comprobar" || $action == "log_fallido" || $action == "log_bloqueado"||$action=="blocked") {
    $titulo = "Iniciar sesión";
    $css = "<link rel='stylesheet' href='/mvc/vista/css/log_in.css'>";
} else if ($action == "sing" || $action == "crear" || $action == "sing_fallido") {
    $titulo = "Registrarse";
    $css = "<link rel='stylesheet' href='/mvc/vista/css/log_in.css'>";
} elseif ($action == "perfil") {
    $params = $_SERVER['QUERY_STRING'] ? '?' . http_build_query(array_diff_key($_GET, ['action' => ''])) : '';
    header("Location: {$base_url}perfil" . $params);
    exit();
} else {
    $titulo = "Home";
    $css = "<link rel='stylesheet' href='/mvc/vista/css/inicio.css'>";
    require("../vista/layerHeader.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Corregido el acceso al token de sesión
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Content-Type: application/json');
        echo json_encode([
            "ok" => false, 
            "error" => "CSRF_FAIL", 
            "msg" => "Sesión caducada, recarga la página."
        ]);
        exit();
    }
}

if ($action == "log") {
    $controller->log();
} else if ($action == "log_fallido") {
    $controller->log();
    echo "<script>alert('Usuario o contraseña incorrecto')</script>";
} else if ($action == "log_bloqueado") {
    $minutos = (int)($_GET["min"] ?? 15);
    $controller->log();
    echo "<script>alert('Cuenta bloqueada por demasiados intentos fallidos. Inténtalo de nuevo en $minutos minuto" . ($minutos === 1 ? "" : "s") . ".')</script>";
}else if($action=="blocked"){
     $controller->log();
    echo "<script>alert('Un administrador a bloqueado tu cuenta')</script>";
} else if ($action == "comprobar") {
    $nombre = $_POST["user"];
    $pass = $_POST["pass"];
    $controller->loginar($nombre, $pass);
} else if ($action == "sing") {
    $controller->sing();
} else if ($action == "sing_fallido") {
    $controller->sing();
    echo "<script>alert('Error: El usuario ya existe')</script>";
} else if ($action == "crear") {
    $nombre = $_POST["user"];
    $email = $_POST["email"];
    $pass = $_POST["pass"];
    $controller->register($nombre, $pass, $email);
} else if ($action == "perfil") {
    $controller->perfil();
} else if ($action == "confirmar_email") {
    $token = $_GET["token"] ?? "";
    $controller->confirmar_email($token);
} else if ($action == "confirmar_contrasena") {
    $token = $_GET["token"] ?? "";
    $controller->confirmar_contrasena($token);
} else if ($action == "log_out") {
    session_destroy();
    header("Location: {$base_url}");
    exit();
} else {
    $controller_producto->home();
}

if ($action == "home" || $action == "perfil") {
    require_once "/mvc/helpers/protect.php";
    require("../vista/footer.html");
}
?>