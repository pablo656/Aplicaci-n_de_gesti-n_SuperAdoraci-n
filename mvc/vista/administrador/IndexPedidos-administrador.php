<?php
define('ACCESO_PERMITIDO', true);
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    require_once("../../controller/Controller_pedidos.php");
    $controller = new Controller_pedidos();
    $titulo = "Administración de pedidos";
    $css = "<link rel='stylesheet' href='../css/pedidos-administrador.css'>";
     $action = $_GET["action"] ?? "list";
    require("layerHeader-administrador.php");
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
        if ($action == "delete") {
            $id = $_POST["id_pedido"];
            $controller->eliminar_pedido($id);
            header("Location: IndexPedidos-administrador.php");
            exit();
        } elseif ($action == "completar") {
            $id = $_POST["id_pedido"];
            $controller->marcar_realizado($id);
            header("Location: IndexPedidos-administrador.php");
            exit();
        } else {
            $controller->consultar_pedidos_admin();
        }
    
?>
