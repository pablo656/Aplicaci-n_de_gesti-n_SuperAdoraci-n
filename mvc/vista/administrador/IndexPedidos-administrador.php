<?php
define('ACCESO_PERMITIDO', true);
    session_start();
    require_once("../../controller/Controller_pedidos.php");
    $controller = new Controller_pedidos();
    $action = $_GET["action"] ?? "list";
    $titulo = "Administración de pedidos";
    $css = "<link rel='stylesheet' href='../css/pedidos-administrador.css'>";
    require("layerHeader-administrador.php");

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
