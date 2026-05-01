<?php
    session_start();
     require_once("../../controller/productoController.php");
    $controller=new Controller_user();
    $action = $_GET["action"] ?? "list";
    $titulo="Administración-Inicio" ;
    $css="<link rel='stylesheet' href='../'>" ;
    require("layerHeader-administrador.php");
    
?>