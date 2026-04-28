<?php
    session_start();
    require_once("../controller/controller_reservas.php");
    require_once("../controller/Controller_pedidos.php");
    $controller_reservas=new Controller_reservas();
    $controller_pedidos=new Controller_pedidos();
    $titulo="Perfil";
    $css = "<link rel='stylesheet' href='css/perfil.css'>";
    $action = $_GET["action"] ?? "list";
    require("../vista/layerHeader.php");
    if($action=="borrar_reserva"){
        $id_reserva=$_POST["id_reserva"];
        $controller_reservas->eliminar_reserva($id_reserva);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }else{
        $id=$_SESSION["id"];
        //Debido a que es necesario usar dos funciones de dos controllers distintos, se han tenido que usar metodos drasticos para hacer posible que la visat alcance la información
        $pedidos=$controller_pedidos->mostrar_pedidos_user($id);
        $reservas=$controller_reservas->consultar_reservas_user($id);
        require __DIR__ . "/../vista/perfil.php";
    }
    require("../vista/footer.html");
?>