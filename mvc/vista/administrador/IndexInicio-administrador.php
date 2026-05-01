<?php
    session_start();
     require_once("../../controller/productoController.php");
    $controller=new ProductoController();
    $action = $_GET["action"] ?? "list";
    $titulo="Administración-Inicio" ;
    $css="<link rel='stylesheet' href='../css/inicio-administrador.css'>";
    require("layerHeader-administrador.php");
    if($action=="add"){

    }else{
        $controller->buscar_productos_inicio_administrador();
    }
?>