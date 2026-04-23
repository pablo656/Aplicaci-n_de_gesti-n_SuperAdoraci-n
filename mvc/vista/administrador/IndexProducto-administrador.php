<?php
    session_start();
    require_once("../../controller/productoController.php");
    $controller=new ProductoController();
    $action = $_GET["action"] ?? "list";
    $titulo = "Administración de catalogo";
    $css = "<link rel='stylesheet' href='../css/administrador-catalogo.css'>";
    $categorias = ["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
    ];
    require("layerHeader-administrador.php");
    
    if($action=="add"){
    
    }else if($action=="delete"){
        $id=$_POST["id_producto"];
        $controller->del_producto($id);
        header("Location:IndexProducto-administrador.php");
    }else if($action == "modificar") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $stock = $_POST["stock"];
    $precio = $_POST["precio"];
    $descuento = $_POST["descuento"];
    $categoria = $_POST["categoria"];
    $subcategoria = $_POST["subcategoria"];
    $precio_por_peso = isset($_POST["precio_por_peso"]) ? 1 : 0;

    // Obtener el array de la imagen desde $_FILES
    $imagen = (isset($_FILES["nueva_imagen"]) && $_FILES["nueva_imagen"]["error"] == 0) ? $_FILES["nueva_imagen"] : null;

    // ¡ORDEN CRÍTICO! Coincidiendo con el controlador:
    $controller->update_producto(
        $id, 
        $nombre, 
        $stock, 
        $precio, 
        $precio_por_peso, 
        $categoria, 
        $subcategoria, 
        $imagen,   // Imagen es el penúltimo
        $descuento // Descuento es el último
    );

    header("Location: IndexProducto-administrador.php");
    exit();
    }else{
        $controller->mostrar_productos_admin();
    }
    
?>