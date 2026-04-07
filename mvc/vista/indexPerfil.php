<?php
    session_start();
    require_once("../controller/controller_reservas.php");
    $controller=new Controller_reservas();
    $titulo="Perfil";
    $css = "<link rel='stylesheet' href='css/perfil.css'>";
    $action = $_GET["action"] ?? "list";
    require("../vista/layerHeader.php");
    if($action=="sadas"){

    }else{
        $controller->consultar_reservas();
    }
    require("../vista/footer.html");
?>