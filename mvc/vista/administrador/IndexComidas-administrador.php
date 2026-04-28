<?php
session_start();
require_once("../../controller/controller_comidas.php");

$controller = new Controller_comidas();
$action     = $_GET["action"] ?? "list";
$titulo     = "Administración de comidas";
$css        = "<link rel='stylesheet' href='../css/administrador-catalogo.css'><link rel='stylesheet' href='../css/comidas-administrador.css'>";
require("layerHeader-administrador.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($action === "insertar") {
        $controller->insertar($_POST, $_FILES['nueva_imagen'] ?? []);
        header("Location: IndexComidas-administrador.php");
        exit();
    } elseif ($action === "modificar") {
        $controller->modificar($_POST['id'], $_POST, $_FILES['nueva_imagen'] ?? []);
        header("Location: IndexComidas-administrador.php");
        exit();
    } elseif ($action === "delete") {
        $controller->eliminar($_POST['id_comida']);
        header("Location: IndexComidas-administrador.php");
        exit();
    }
}

$controller->listar();
?>
