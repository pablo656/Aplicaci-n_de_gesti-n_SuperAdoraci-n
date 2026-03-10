<?php
    
    require_once("../controller/productoController.php");
    $controller=new ProductoController();
    $action=$_GET["actión"] ?? "list";
    $titulo="Catalo";
    $css="<link rel='stylesheet' href='css/catalogo_style.css'>";
    require("../vista/layerHeader.php");
    if($action=="add"){

    }else{
        $controller->mostrar_productos();
    }
    require("../vista/footer.html");
?>