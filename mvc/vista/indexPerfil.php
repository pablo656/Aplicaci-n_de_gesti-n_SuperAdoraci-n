<?php
define('ACCESO_PERMITIDO', true);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION["id"])) {
    header("Location: /?action=log");
    exit();
}
require_once("../controller/controller_user.php");
require_once("../controller/controller_reservas.php");
require_once("../controller/Controller_pedidos.php");
$controller = new Controller_user();
$controller_reservas = new Controller_reservas();
$controller_pedidos = new Controller_pedidos();
$action = $_GET["action"] ?? "list";

// Procesar acciones POST antes de cualquier output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: /perfil");
        exit();
    }
    if ($action == "enviar_feedback") {
        require_once("../helpers/Mailer.php");
        $mensaje = trim($_POST["mensaje"] ?? "");
        if (!empty($mensaje)) {
            $mailer = new Mailer();
            $nombre = htmlspecialchars($_SESSION["nombre"]);
            $email  = htmlspecialchars($_SESSION["email"]);
            $cuerpo = "<p><strong>De:</strong> $nombre ($email)</p><p>" . nl2br(htmlspecialchars($mensaje)) . "</p>";
            $ok = $mailer->enviar("superadoracionpruebas@gmail.com", "Sugerencia de $nombre", $cuerpo);
            header("Location: /perfil?" . ($ok ? "feedback_ok=1" : "feedback_error=1"));
        } else {
            header("Location: /perfil");
        }
        exit();

    } else if ($action == "borrar_reserva") {
        $id_reserva = $_POST["id_reserva"];
        $controller_reservas->eliminar_reserva($id_reserva);
        header("Location: /perfil");
        exit();

    } else if ($action == "borrar_pedido") {
        $id_pedido  = (int)($_POST["id_pedido"] ?? 0);
        $id_usuario = $_SESSION["id"];
        $ok = $controller_pedidos->eliminar_pedido_usuario($id_pedido, $id_usuario);
        header("Location: " . ($ok ? "/perfil?eliminado=1" : "/perfil?error_eliminar=1"));
        exit();

    } else if ($action == "actualizar_nombre") {
        $controller->actualizar_nombre();
        exit();

    } else if ($action == "solicitar_cambio_contrasena") {
        require_once("../helpers/Mailer.php");
        $controller->solicitar_cambio_contrasena();
        exit();
    }
}

// A partir de aquí solo GET — ya es seguro emitir HTML
$titulo = "Perfil";
$css = "<link rel='stylesheet' href='/mvc/vista/css/perfil.css'>";
require("../vista/layerHeader.php");

$id       = $_SESSION["id"];
$pedidos  = $controller_pedidos->mostrar_pedidos_user($id);
$reservas = $controller_reservas->consultar_reservas_user($id);
require __DIR__ . "/../vista/perfil.php";
require_once "../helpers/protect.php";
require("../vista/footer.html");
?>
