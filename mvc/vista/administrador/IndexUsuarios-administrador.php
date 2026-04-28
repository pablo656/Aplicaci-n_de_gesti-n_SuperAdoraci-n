<?php
    session_start();
    require_once("../../controller/controller_user.php");
    $controller=new Controller_user();
    $action = $_GET["action"] ?? "list";
    $titulo="Usuarios" ;
    $css="<link rel='stylesheet' href='../css/usuario-administrador.css'>" ;
    require("layerHeader-administrador.php");
    if($action=="add"){

    }elseif($action=="modificar"){

    }elseif ($action == "delete") {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $controller->borrarUsuarios($id);
        // Es vital redireccionar para limpiar el POST y que no se reenvíe al refrescar
        header("Location: IndexUsuarios-administrador.php");
        exit(); 
    }
    }else{
        $controller->mostrarUsuarios();
    }
    
    
?>