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


require_once __DIR__ . "/../../controller/controller_reservas.php";
require_once __DIR__ . "/../../controller/Controller_pedidos.php";
require_once __DIR__ . "/../../model/model_user.php";

$controller_reservas = new Controller_reservas();
$controller_pedidos  = new Controller_pedidos();
$model_user          = new model_user();

$titulo = "Perfil";
$css    = "<link rel='stylesheet' href='/mvc/vista/css/perfil.css'>";
$action = $_GET["action"] ?? "list";
$self   = strtok($_SERVER['REQUEST_URI'], '?');

// --- Manejo POST (antes de cualquier output) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header("Location: $self");
        exit();
    }

    if ($action === "borrar_reserva") {
        $id_reserva = (int)($_POST["id_reserva"] ?? 0);
        $controller_reservas->eliminar_reserva($id_reserva);
        header("Location: $self");
        exit();

    } elseif ($action === "borrar_pedido") {
        $id_pedido  = (int)($_POST["id_pedido"] ?? 0);
        $id_usuario = $_SESSION["id"];
        $ok = $controller_pedidos->eliminar_pedido_usuario($id_pedido, $id_usuario);
        header("Location: $self?" . ($ok ? "eliminado=1" : "error_eliminar=1"));
        exit();

    } elseif ($action === "actualizar_nombre") {
        $nuevo_nombre = trim($_POST["nombre"] ?? "");
        if (empty($nuevo_nombre)) {
            header("Location: $self?error=nombre_vacio");
            exit();
        }
        $resultado = $model_user->actualizar_nombre($_SESSION["id"], $nuevo_nombre);
        if ($resultado === "nombre_duplicado") {
            header("Location: $self?error=nombre_duplicado");
            exit();
        }
        if ($resultado === false) {
            header("Location: $self?error=error_guardado");
            exit();
        }
        $_SESSION["nombre"] = $nuevo_nombre;
        header("Location: $self?ok=1");
        exit();

    } elseif ($action === "enviar_feedback") {
        require_once __DIR__ . "/../../helpers/Mailer.php";
        $mensaje = trim($_POST["mensaje"] ?? "");
        if (!empty($mensaje)) {
            $mailer = new Mailer();
            $nombre = htmlspecialchars($_SESSION["nombre"]);
            $email  = htmlspecialchars($_SESSION["email"]);
            $cuerpo = "<p><strong>De:</strong> $nombre ($email)</p><p>" . nl2br(htmlspecialchars($mensaje)) . "</p>";
            $ok = $mailer->enviar("superadoracionpruebas@gmail.com", "Sugerencia de $nombre", $cuerpo);
            header("Location: $self?" . ($ok ? "feedback_ok=1" : "feedback_error=1"));
        } else {
            header("Location: $self");
        }
        exit();
    }
}

// --- Renderizado GET ---
$id      = $_SESSION["id"];
$pedidos  = $controller_pedidos->mostrar_pedidos_user($id);
$reservas = $controller_reservas->consultar_reservas_user($id);

$perfil_url = $self;
$home_url   = $self;
$img_base   = '../';

require __DIR__ . "/layerHeader-administrador.php";
require __DIR__ . "/../../vista/perfil.php";
?>
