<?php
define('ACCESO_PERMITIDO', true);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION["id"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
}


require_once __DIR__ . "/../../controller/controller_reservas.php";
require_once __DIR__ . "/../../controller/Controller_pedidos.php";
require_once __DIR__ . "/../../model/model_user.php";

$controller_reservas = new Controller_reservas();
$controller_pedidos  = new Controller_pedidos();
$model_user          = new model_user();

$titulo = "Perfil";
$css    = "<link rel='stylesheet' href='../css/perfil.css'>";
 $action = $_GET["action"] ?? "list";

require __DIR__ . "/layerHeader-administrador.php";
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
    if ($action === "borrar_reserva") {
        $id_reserva = (int)($_POST["id_reserva"] ?? 0);
        $controller_reservas->eliminar_reserva($id_reserva);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();

    } else if ($action === "borrar_pedido") {
        $id_pedido  = (int)($_POST["id_pedido"] ?? 0);
        $id_usuario = $_SESSION["id"];
        $ok = $controller_pedidos->eliminar_pedido_usuario($id_pedido, $id_usuario);
        header("Location: IndexPerfil.php?" . ($ok ? "eliminado=1" : "error_eliminar=1"));
        exit();

    } else if ($action === "actualizar_nombre") {
        $nuevo_nombre = trim($_POST["nombre"] ?? "");
        if (empty($nuevo_nombre)) {
            header("Location: IndexPerfil.php?error=nombre_vacio");
            exit();
        }
        $resultado = $model_user->actualizar_nombre($_SESSION["id"], $nuevo_nombre);
        if ($resultado === "nombre_duplicado") {
            header("Location: IndexPerfil.php?error=nombre_duplicado");
            exit();
        }
        if ($resultado === false) {
            header("Location: IndexPerfil.php?error=error_guardado");
            exit();
        }
        $_SESSION["nombre"] = $nuevo_nombre;
        header("Location: IndexPerfil.php?ok=1");
        exit();

    } else {
        $id      = $_SESSION["id"];
        $pedidos  = $controller_pedidos->mostrar_pedidos_user($id);
        $reservas = $controller_reservas->consultar_reservas_user($id);

        $perfil_url = 'IndexPerfil.php';
        $home_url   = 'IndexPerfil.php';
        $img_base   = '../';

        require __DIR__ . "/../../vista/perfil.php";
    }
?>
