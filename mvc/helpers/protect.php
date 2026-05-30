<?php

    require_once __DIR__ . "/../controller/controller_user.php";
    $controller=new Controller_user();
    if(isset($_SESSION["id"])&& $controller->bloqueado($_SESSION["id"])){
        
        session_destroy();
         echo "<script>window.location.href='/?action=log';</script>";
        exit();
    }
?>