<?php
define('ACCESO_PERMITIDO', true);
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    require_once("../controller/productoController.php");
    require_once("../controller/Controller_pedidos.php");
    require_once("../controller/controller_reservas.php");
    $controller = new ProductoController();
    $controller_pedidos = new Controller_pedidos();
    $controller_reservas=new Controller_reservas();
    $action = $_GET["action"] ?? "list";
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
        if($action == "actualizar_cantidad"){
            $id_producto = $_POST["id_producto"];
            $cantidad = (float)$_POST["cantidad"];
            $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
            foreach($reservas as &$reserva){
                if($reserva["id"] == $id_producto){
                    $reserva["cantidad"] = $cantidad;
                    break;
                }
            }
            setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
            exit();

        }else if($action == "borrar_reserva"){
            $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
            $id_producto = $_POST["id_producto"];
            foreach($reservas as $indice => $reserva){
                if($reserva["id"] == $id_producto){
                    unset($reservas[$indice]);
                    setcookie("reservas", json_encode(array_values($reservas)), time() + (60 * 60 * 24), "/");
                    exit();
                }
            }
            exit();

        }else if($action == "borrar_pedido"){
            $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
            $id_comida = $_POST["id_comida"];
            foreach($pedidos as $indice => $pedido){
                if($pedido["id"] == $id_comida){
                    unset($pedidos[$indice]);
                    setcookie("pedidos", json_encode(array_values($pedidos)), time() + (60 * 60 * 24), "/");
                    exit();
                }
            }
            exit();

        }else if($action == "actualizar_cantidad_pedido"){
            $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
            $id_comida = $_POST["id_comida"];
            $cantidad = min(30, max(1, (int)$_POST["cantidad"]));
            foreach($pedidos as &$pedido){
                if($pedido["id"] == $id_comida){
                    $pedido["cantidad"] = $cantidad;
                    break;
                }
            }
            setcookie("pedidos", json_encode($pedidos), time() + (60 * 60 * 24), "/");
            exit();

        }else if($action == "comprobar_stock"){
            $id_producto = (int)$_POST["id_producto"];
            $cantidad = (float)$_POST["cantidad"];
            if($controller->comprobar_stock($id_producto, $cantidad)){
                echo json_encode(["ok" => true]);
            }else{
                echo json_encode(["ok" => false]);
            }
            exit();

        }else if($action == "confirmar_reservas"){
            $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
            $usuario = $_SESSION["id"];
            foreach($reservas as $reserva){
                $controller_reservas->crear_reserva($usuario, $reserva["id"], $reserva["cantidad"]);
            }
            setcookie("reservas", json_encode([]), time() + (60 * 60 * 24), "/");
            $_SESSION["reserva_ok"] = true;
            header("Location: /carrito");
            exit();

        }else if($action == "confirmar_pedidos"){
            $pedidos_cookie = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
            $usuario = $_SESSION["id"];
            foreach($pedidos_cookie as $pedido){
                $controller_pedidos->crear_pedido(
                    $usuario,
                    $pedido["id"],
                    $pedido["cantidad"],
                    $pedido["mensaje"]       ?? "",
                    $pedido["fecha_entrega"] ?? null
                );
            }
            setcookie("pedidos", json_encode([]), time() + (60 * 60 * 24), "/");
            $_SESSION["pedidos_confirmados"] = true;
            header("Location: /perfil");
            exit();
        }

    $titulo = "Carrito";
    $css = "<link rel='stylesheet' href='/mvc/vista/css/carrito.css'>";
    require("../vista/layerHeader.php");
        if(true){
            $reservas_cookie = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
            $pedidos_cookie  = isset($_COOKIE["pedidos"])  ? json_decode($_COOKIE["pedidos"],  true) : [];
            $pedidos_carrito = $controller_pedidos->buscar_pedidos_cookie($pedidos_cookie) ?: [];
            $controller->buscar_reservas_incompletas($reservas_cookie, $pedidos_carrito);
        }
    require_once "/mvc/helpers/protect.php";
    require("../vista/footer.html");
?>