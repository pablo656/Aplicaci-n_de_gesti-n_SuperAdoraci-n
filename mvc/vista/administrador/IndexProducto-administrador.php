<?php
    session_start();
    require_once("../../controller/productoController.php");
    $controller=new ProductoController();
    $action = $_GET["action"] ?? "list";
    $titulo = "Administración de catálogo";
    $css = "<link rel='stylesheet' href='../css/administrador-catalogo.css'>";
    $categorias = ["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    $subcategoria = $_GET["subcategoria"] ?? null;
    $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
    ];
    require("layerHeader-administrador.php");
    
    if($action=="inicio"){
        $id=$_POST["id"];
        $controller->aniadirInicio($id);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#$id");
    }else if($action=="quitar_inicio"){
        $id=$_POST["id"];
        $controller->quitarInicio($id);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#$id");
    }else if($action=="delete"){
        $id=$_POST["id_producto"];
        $controller->del_producto($id);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }else if($action == "modificar") {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $stock = $_POST["stock"];
        $precio = $_POST["precio"];
        $descuento = $_POST["descuento"];
        $categoria = $_POST["categoria"];
        $subcategoria = isset($_POST["subcategoria"])? $_POST["subcategoria"]: null;
        $precio_por_peso = isset($_POST["precio_por_peso"]) ? 1 : 0;

        $imagen = (isset($_FILES["nueva_imagen"]) && $_FILES["nueva_imagen"]["error"] == 0) ? $_FILES["nueva_imagen"] : null;

        $resultado=$controller->update_producto($id, $nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $descuento);
        
        if ($resultado !== true) {
            echo "<script>
                    alert('$resultado');
                    window.history.back();
                </script>";
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "#$id");
        }
    }else if ($action == "insertar") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $stock = $_POST["stock"];
            $precio = $_POST["precio"];
            $descuento = $_POST["descuento"];
            $categoria = $_POST["categoria"];
            $subcategoria = isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : null;
            $precio_por_peso = isset($_POST["precio_por_peso"]) ? 1 : 0;

            $imagen = (isset($_FILES["nueva_imagen"]) && $_FILES["nueva_imagen"]["error"] == 0) ? $_FILES["nueva_imagen"] : null;

            $resultado=$controller->add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $descuento);
            
            if (isset($resultado) && is_array($resultado)) {
                $errorString = implode("\\n- ", $resultado);
                echo "<script>
                        alert('No se pudo insertar el producto:\\n- $errorString');
                        window.history.back(); 
                    </script>";
                exit();
            } else {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        }
    }else if(in_array($action, $categorias)){
        if($subcategoria != null){
            $controller->buscar_por_subcategoria_admin($subcategoria);
        }else{
            $controller->buscar_por_categorias_admin($action);
        }
    }else{
        $controller->mostrar_productos_admin();
    }
?>