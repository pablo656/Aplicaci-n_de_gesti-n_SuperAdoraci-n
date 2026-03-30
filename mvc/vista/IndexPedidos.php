<?php
session_start();

// Redirigir al login si no hay sesión iniciada
if (!isset($_SESSION["id"])) {
    header("Location: IndexHome.php?action=log");
    exit();
}

require_once("../controller/Controller_pedidos.php");
$controller = new Controller_pedidos();
$action = $_GET["action"] ?? "list";

$titulo = "Pedidos";
$css = "<link rel='stylesheet' href='css/pedidos.css'>";
require("../vista/layerHeader.php");

if ($action === "list") {
    $controller->mostrar_catalogo();

} else if ($action === "crear") {
    $id_comida = $_POST["id_comida"] ?? null;
    $cantidad  = $_POST["cantidad"]  ?? null;
    $mensaje   = $_POST["mensaje"]   ?? null;
    $controller->crear_pedido($_SESSION["id"], $id_comida, $cantidad, $mensaje);

} else {
    header("Location: IndexPedidos.php?action=list");
    exit();
}

require("../vista/footer.html");
?>
