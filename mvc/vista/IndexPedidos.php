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
    $resultado = $controller->crear_pedido($_SESSION["id"], $id_comida, $cantidad, $mensaje);

    if (!$resultado) {
        $_SESSION['pedido_errores'] = ["No se pudo realizar el pedido. Comprueba los datos."];
        header("Location: IndexPedidos.php?action=list");
        exit();
    }

    $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
    $encontrado = false;
    foreach ($pedidos as &$pedido) {
        if ($pedido["id"] == $id_comida) {
            $pedido["cantidad"] = max(1, (int)$pedido["cantidad"] + (int)$cantidad);
            $encontrado = true;
            break;
        }
    }
    unset($pedido); // cleanup reference

    if (!$encontrado) {
        $pedidos[] = ["id" => $id_comida, "cantidad" => (int)$cantidad];
    }

    setcookie("pedidos", json_encode($pedidos), time() + (60 * 60 * 24), "/");
    $_SESSION['pedido_ok'] = true;
    header("Location: IndexPedidos.php?action=list");
    exit();

} else if ($action === "actualizar_cantidad_cookie") {
    $id_comida = $_POST["id_comida"] ?? null;
    $cambio = (int)($_POST["cambio"] ?? 0);

    $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
    foreach ($pedidos as $idx => $pedido) {
        if ($pedido["id"] == $id_comida) {
            $nueva = max(0, (int)$pedido["cantidad"] + $cambio);
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

require("../vista/footer.html");
?>
