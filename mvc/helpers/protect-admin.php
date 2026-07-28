<?php

    require_once __DIR__ . "/../controller/controller_user.php";
    $controller=new Controller_user();
    if(isset($_SESSION["id"]) && $controller->bloqueado($_SESSION["id"])){
        session_destroy();
        header("Location: /administrador/log?action=blocked");
        exit();
    }
?>