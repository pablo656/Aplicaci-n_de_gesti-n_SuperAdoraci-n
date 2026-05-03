<?php
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: IndexHome.php?action=log");
    exit();
}
require_once("../controller/Controller_pedidos.php");
$controller = new Controller_pedidos();
$action = $_GET["action"] ?? "list";

if ($action === "list") {
    $titulo = "Pedidos";
    $css = "<link rel='stylesheet' href='css/pedidos.css'>";
    require("../vista/layerHeader.php");
    $controller->mostrar_catalogo();
    require("../vista/footer.html");

} else if ($action === "crear") {
    $id_comida     = $_POST["id_comida"]     ?? null;
    $cantidad      = $_POST["cantidad"]      ?? null;
    $mensaje       = $_POST["mensaje"]       ?? "";
    $fecha_entrega = $_POST["fecha_entrega"] ?? "";

    if (empty($id_comida) || !is_numeric($id_comida) || empty($cantidad) || $cantidad < 1 || $cantidad > 30) {
        header("Location: IndexPedidos.php?action=list");
        exit();
    }

    $min = date('Y-m-d', strtotime('+3 days'));
    $max = date('Y-m-d', strtotime('+6 months'));
    if (empty($fecha_entrega) || $fecha_entrega < $min || $fecha_entrega > $max) {
        $_SESSION['pedido_errores'] = ["La fecha de entrega debe estar entre 3 días y 6 meses desde hoy."];
        header("Location: IndexPedidos.php?action=list");
        exit();
    }

    $controller->guardar_en_cookie($id_comida, $cantidad, $mensaje, $fecha_entrega);
    $_SESSION['pedido_ok'] = true;
    header("Location: IndexPedidos.php?action=list");
    exit();

} else if ($action === "actualizar_cantidad_cookie") {
    $id_comida = $_POST["id_comida"] ?? null;
    $cambio = (int)($_POST["cambio"] ?? 0);

    $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
    foreach ($pedidos as $idx => $pedido) {
        if ($pedido["id"] == $id_comida) {
            $nueva = min(30, max(0, (int)$pedido["cantidad"] + $cambio));
            if ($nueva <= 0) {
                unset($pedidos[$idx]);
            } else {
                $pedidos[$idx]["cantidad"] = $nueva;
            }
            break;
        }
    }
    setcookie("pedidos", json_encode(array_values($pedidos)), time() + (60 * 60 * 24), "/");
    echo json_encode(["success" => true]);
    exit();

} else if ($action === "eliminar_pedido_cookie") {
    $id_comida = $_POST["id_comida"] ?? null;
    $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
    $pedidos = array_filter($pedidos, fn($p) => $p["id"] != $id_comida);
    setcookie("pedidos", json_encode(array_values($pedidos)), time() + (60 * 60 * 24), "/");
    echo json_encode(["success" => true]);
    exit();

} else {
    header("Location: IndexPedidos.php?action=list");
    exit();
}
?>
