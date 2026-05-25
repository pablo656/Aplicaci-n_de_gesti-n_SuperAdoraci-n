<?php
define('ACCESO_PERMITIDO', true);
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
     require_once("../../controller/productoController.php");
    $controller=new ProductoController();
    $titulo="Administración-Inicio" ;
    $css="<link rel='stylesheet' href='../css/inicio-administrador.css'>";
     $action= $_GET["action"] ?? "list";
    require("layerHeader-administrador.php");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // En lugar de un die() con texto, devolvemos un JSON de error
            header('Content-Type: application/json');
            echo json_encode([
                "ok" => false, 
                "error" => "CSRF_FAIL", 
                "msg" => "Sesión caducada, recarga la página."
            ]);
            exit();
        }
    }
        if($action=="quitar"){
            $id=$_POST["id"];
            $controller->quitarInicio($id);
            header("Location: IndexInicio-administrador.php");
        }else{
            $controller->buscar_productos_inicio_administrador();
        }
?>