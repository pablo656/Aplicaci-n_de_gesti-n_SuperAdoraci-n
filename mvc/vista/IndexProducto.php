<?php
    session_start();
    require_once("../controller/productoController.php");
    $controller = new ProductoController();
    $action = $_GET["action"] ?? "list";
    $subcategoria = $_GET["subcategoria"] ?? null;

    // Se debe poner esto aqui ya que la funcion asincrona debe estar antes de cargar el header
    if($action == "comprobar_stock"){
        $id_producto = (int)$_POST["id_producto"];
        $cantidad = (int)$_POST["cantidad"];
        if($controller->comprobar_stock($id_producto, $cantidad)){
            echo json_encode(["ok" => true]);
        }else{
            echo json_encode(["ok" => false]);
        }
        exit();
    }else if($action == "actualizar_cantidad"){
        $id_producto = $_POST["id_producto"];
        $cantidad = (int)$_POST["cantidad"];
        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        foreach($reservas as &$reserva){
            if($reserva["id"] == $id_producto){
                $reserva["cantidad"] = $cantidad;
                break;
            }
        }
        setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
        exit();
    }else if($action == "borrar_reserva"){
        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $id_producto = $_POST["id_producto"];
        foreach($reservas as $indice => $reserva){
            if($reserva["id"] == $id_producto){
                unset($reservas[$indice]);
                setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
                exit();
            }
        }
        exit();
    }

    $titulo = "Catalogo";
    $css = "<link rel='stylesheet' href='css/catalogo_style.css'>";
    $categorias = ["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
    $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
    ];
    require("../vista/layerHeader.php");

    if($action == "add"){

    }else if($action == "reservar"){
        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        $id_producto = $_POST["id_producto"];
        $existe = false;
        foreach($reservas as $reserva){
            if($reserva["id"] == $id_producto){
                $existe = true;
                break;
            }
        }
        if(!$existe){
            $reservas[] = ["id" => $id_producto, "cantidad" => 1];
        }
        setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
        header("Location: " . $_SERVER['HTTP_REFERER'] . "#$id_producto");
        exit();

    }else if(in_array($action, $categorias)){
        if($subcategoria != null){
            $controller->buscar_por_subcategoria($subcategoria);
        }else{
            $controller->buscar_por_categorias($action);
        }
    }else{
        $controller->mostrar_productos();
    }

    require("../vista/footer.html");
?>