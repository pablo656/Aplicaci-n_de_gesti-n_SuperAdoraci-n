<?php
    session_start();
    require_once("../controller/productoController.php");
    $titulo="Carrito";
    $css="<link rel='stylesheet' href='css/carrito.css'>";
    $controller=new ProductoController();
    $action=$_GET["action"] ?? "list";
    require("../vista/layerHeader.php");
    if($action=="reservar"){

      }else if($action == "actualizar_cantidad"){
        $id_producto = $_POST["id_producto"];
        $cantidad = (int)$_POST["cantidad"];

        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];

        // Buscar el producto y actualizar su cantidad
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
    }else{
        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $controller->buscar_reservas_incompletas($reservas);
    }
?>