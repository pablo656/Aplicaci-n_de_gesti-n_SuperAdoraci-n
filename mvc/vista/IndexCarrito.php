<?php
    session_start();
    require_once("../controller/productoController.php");
    require_once("../controller/Controller_pedidos.php");
    $titulo="Carrito";
    $css="<link rel='stylesheet' href='css/carrito.css'>";
    $controller=new ProductoController();
    $controller_pedidos=new Controller_pedidos();
    $action=$_GET["action"] ?? "list";
    require("../vista/layerHeader.php");
    if($action=="reservar"){

      }else if($action == "actualizar_cantidad"){
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
    }else if($action=="borrar_reserva"){
         $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
          $id_producto = $_POST["id_producto"];
         foreach($reservas as $indice=>$reserva){
            if($reserva["id"]==$id_producto){
                unset($reservas[$indice]);
                $valor_cookie = json_encode($reservas);
                setcookie("reservas", $valor_cookie, time() + (60 * 60 * 24), "/");
                exit();
            }
         }
    }else if($action=="borrar_pedido"){
        $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
        $id_comida = $_POST["id_comida"];
        foreach($pedidos as $indice=>$pedido){
            if($pedido["id"]==$id_comida){
                unset($pedidos[$indice]);
                setcookie("pedidos", json_encode(array_values($pedidos)), time() + (60 * 60 * 24), "/");
                exit();
            }
        }
    }else if($action=="actualizar_cantidad_pedido"){
        $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
        $id_comida = $_POST["id_comida"];
        $cantidad = (int)$_POST["cantidad"];
        foreach($pedidos as &$pedido){
            if($pedido["id"]==$id_comida){
                $pedido["cantidad"] = $cantidad;
                break;
            }
        }
        setcookie("pedidos", json_encode($pedidos), time() + (60 * 60 * 24), "/");
        exit();
    }else{
        $reservas_cookie = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $pedidos_cookie  = isset($_COOKIE["pedidos"])  ? json_decode($_COOKIE["pedidos"],  true) : [];
        $pedidos_carrito = $controller_pedidos->buscar_pedidos_cookie($pedidos_cookie) ?: [];
        $controller->buscar_reservas_incompletas($reservas_cookie, $pedidos_carrito);
        require("../vista/footer.html");
    }
?>