<?php
    session_start();
    require_once("../controller/productoController.php");
    require_once("../controller/Controller_pedidos.php");
    require_once("../controller/controller_reservas.php");
    $controller = new ProductoController();
    $controller_pedidos = new Controller_pedidos();
    $controller_reservas=new Controller_reservas();
    $action = $_GET["action"] ?? "list";

    // ← Acciones AJAX antes del header
    if($action == "actualizar_cantidad"){
        $id_producto = $_POST["id_producto"];
        $cantidad = (int)$_POST["cantidad"];
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
        $cantidad = (int)$_POST["cantidad"];
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
        $cantidad = (int)$_POST["cantidad"];
        if($controller->comprobar_stock($id_producto, $cantidad)){
            echo json_encode(["ok" => true]);
        }else{
            echo json_encode(["ok" => false]);
        }
        exit();
    }

    // ← Header después de las acciones AJAX
    $titulo = "Carrito";
    $css = "<link rel='stylesheet' href='css/carrito.css'>";
    require("../vista/layerHeader.php");

    if($action == "confirmar_reservas"){
        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $usuario=$_SESSION["id"];
        foreach($reservas as $reserva){
            $id_producto=$reserva["id"];
            $cantidad=$reserva["cantidad"];
            $controller_reservas->crear_reserva($usuario,$id_producto,$cantidad);
        }
        $reservas=[];
        setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }else if($action == "confirmar_pedidos"){
        $pedidos_cookie = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
        $usuario = $_SESSION["id"];
        foreach($pedidos_cookie as $pedido){
            $id_comida = $pedido["id"];
            $cantidad  = $pedido["cantidad"];
            $controller_pedidos->crear_pedido($usuario, $id_comida, $cantidad, "");
        }
        setcookie("pedidos", json_encode([]), time() + (60 * 60 * 24), "/");
        $_SESSION["pedidos_ok"] = true;
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }else{
        $reservas_cookie = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $pedidos_cookie  = isset($_COOKIE["pedidos"])  ? json_decode($_COOKIE["pedidos"],  true) : [];
        $pedidos_carrito = $controller_pedidos->buscar_pedidos_cookie($pedidos_cookie) ?: [];
        $controller->buscar_reservas_incompletas($reservas_cookie, $pedidos_carrito);
    }
    require("../vista/footer.html");
?>