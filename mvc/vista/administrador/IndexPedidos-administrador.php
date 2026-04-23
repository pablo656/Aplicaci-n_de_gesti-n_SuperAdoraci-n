<?php
    session_start();
    $controller;
    $action = $_GET["action"] ?? "list";
    $titulo ;
    $css ;
    require("layerHeader-administrador.php");
    
?>