<?php
    //Esta linea se debe poner en todos los Index para que todas las páginas puedan acceder a la sessión
    session_start();
    require_once("../controller/productoController.php");
    $controller=new ProductoController();
    $action=$_GET["action"] ?? "list";
    $subcategoria = $_GET["subcategoria"] ?? null;
    $titulo="Catalo";
    $css="<link rel='stylesheet' href='css/catalogo_style.css'>";
    $categorias=["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    $subcategorias = [
    "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
    "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
    "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
    "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
    "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
    "Salud_bienestar"   => []
    ];
    require("../vista/layerHeader.php");
    if($action=="add"){
    
    }else if(in_array($action,$categorias)){
        if($subcategoria!=null){
            $controller=$controller->buscar_por_subcategoria($subcategoria);
        }else{
             $controller->buscar_por_categorias($action);
        }  
    }else{
        $controller->mostrar_productos();
    }
    require("../vista/footer.html");
?>