<?php
define('ACCESO_PERMITIDO', true);
    session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    require_once("../../controller/controller_reservas.php");
    $controller=new Controller_reservas();
    $titulo="Administración de reservas";
    $css="<link rel='stylesheet' href='../css/reservas-administrador.css'>" ;
     $action = $_GET["action"] ?? "list";
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