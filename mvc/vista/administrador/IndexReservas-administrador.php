<?php
    session_start();
    require_once("../../controller/controller_reservas.php");
    $controller=new Controller_reservas();
    $action = $_GET["action"] ?? "list";
    $titulo="Administración de reservas";
    $css="<link rel='stylesheet' href='../css/reservas-administrador.css'>" ;
    require("layerHeader-administrador.php");
    if($action=="delete"){
        $id=$_POST["id_reserva"];
        //Cuando tengamos un dominio
        $mensaje=$_POST["nota_administrador"];
        $controller->eliminar_reserva($id);
        header("Location:IndexReservas-administrador.php");
    }else{
        $controller->consultar_reservas();
    }
    
?>