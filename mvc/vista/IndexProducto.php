<?php
    //Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
    session_start();
    require_once("../controller/productoController.php");
    $controller=new ProductoController();
    $action=$_GET["action"] ?? "list";
    $titulo="Catalo";
    $css="<link rel='stylesheet' href='css/catalogo_style.css'>";
    require("../vista/layerHeader.php");
    $categorias=["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    if($action=="add"){
    
    }else if(in_array($action,$categorias)){
        $controller->buscar_por_categorias($action);
    }else{
        $controller->mostrar_productos();
    }
    require("../vista/footer.html");
?>