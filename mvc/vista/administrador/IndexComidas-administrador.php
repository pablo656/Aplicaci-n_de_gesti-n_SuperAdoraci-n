<?php
define('ACCESO_PERMITIDO', true);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once("../../controller/controller_comidas.php");

$controller = new Controller_comidas();
$titulo     = "Administración de comidas";
$css        = "<link rel='stylesheet' href='/mvc/vista/css/administrador-catalogo.css'><link rel='stylesheet' href='/mvc/vista/css/comidas-administrador.css'>";
require("layerHeader-administrador.php");
 $action= $_GET["action"] ?? "list";
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
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($action === "insertar") {
            $controller->insertar($_POST, $_FILES['nueva_imagen'] ?? []);
            header("Location: /administrador/comidas");
            exit();
        } elseif ($action === "modificar") {
            $controller->modificar($_POST['id'], $_POST, $_FILES['nueva_imagen'] ?? []);
            header("Location: /administrador/comidas");
            exit();
        } elseif ($action === "delete") {
            $controller->eliminar($_POST['id_comida']);
            header("Location: /administrador/comidas");
            exit();
        }
    }

$controller->listar();
require_once "/mvc/helpers/protect-admin.php";
?>
