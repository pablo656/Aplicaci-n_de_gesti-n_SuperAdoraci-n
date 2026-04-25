<?php
    session_start();
    require_once("../../controller/controller_reservas.php");
    $controller=new Controller_reservas();
    $action = $_GET["action"] ?? "list";
    $titulo="Reservas" ;
    $css="<link rel='stylesheet' href='../css/reservas-administrador.css'>" ;
    require("layerHeader-administrador.php");
    if($action=="eliminar"){

    }else{
        $controller->consultar_reservas();
    }
    
?>