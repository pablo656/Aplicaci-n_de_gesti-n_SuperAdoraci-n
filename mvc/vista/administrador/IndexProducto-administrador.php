<?php
    session_start();
    require_once("../../controller/productoController.php");
    $controller=new ProductoController();
    $action = $_GET["action"] ?? "list";
    $titulo = "Administración de catalogo";
    $css = "<link rel='stylesheet' href='../../css/administrador-catalogo.css'>";
    $categorias = ["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
    ];
    //require __DIR__ . "/../layerHeader.php";
    if($action=="add"){

    }else{
        $controller->mostrar_productos_admin();
    }
    //require __DIR__ ."/../footer.html";
?>