<?php
    define('ACCESO_PERMITIDO', true);
    session_start();
    require_once("../../controller/controller_user.php");
    $controller=new Controller_user();
    $action = $_GET["action"] ?? "list";
    $titulo="Usuarios" ;
    $css="<link rel='stylesheet' href='../css/usuario-administrador.css'>" ;
    require("layerHeader-administrador.php");
    if($action=="add"){
        $nombre=$_POST["user"];
        $pass=$_POST["pass"];
        $email=$_POST["email"];
        $rol=$_POST["rol"];
        $controller->crearUsuario($nombre,$pass,$email,$rol);
        //header("Location: IndexUsuarios-administrador.php");
        exit(); 
    }elseif($action=="modificar"){
        $id=$_POST["id_usuario"];
        $rol=$_POST["rol"];
        $controller->cambiarRol($id,$rol);
        header("Location: IndexUsuarios-administrador.php");
        exit(); 
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