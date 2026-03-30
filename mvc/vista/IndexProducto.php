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
    
    }else if($action == "reservar"){
        
        // Obtener la cookie actual o array vacío si no existe

        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
        
        $id_producto = $_POST["id_producto"];
        
        // Comprobar que no esté ya en el carrito
        $existe = false;
        foreach($reservas as $reserva){
            if($reserva["id"] == $id_producto){
                $existe = true;
                break;
            }
        }
        
        // Solo añadir si no existe ya
        if(!$existe){
            $reservas[] = ["id" => $id_producto, "cantidad" => 1];
        }
         
        
        // Guardar la cookie actualizada
        setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
        header("Location: " . $_SERVER['HTTP_REFERER']. "#$id_producto");
        exit();

    }else if($action == "actualizar_cantidad"){
        $id_producto = $_POST["id_producto"];
        $cantidad = (int)$_POST["cantidad"];

        $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];

        // Buscar el producto y actualizar su cantidad
        foreach($reservas as &$reserva){
            if($reserva["id"] == $id_producto){
                $reserva["cantidad"] = $cantidad;
                break;
            }
        }

        setcookie("reservas", json_encode($reservas), time() + (60 * 60 * 24), "/");
        exit();
    }else if($action=="borrar_reserva"){
        echo "dsfsd";
         $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
          $id_producto = $_POST["id_producto"];
         foreach($reservas as $indice=>$reserva){
            if($reserva["id"]==$id_producto){
                unset($reservas[$indice]);
                $valor_cookie = json_encode($reservas);
                setcookie("reservas", $valor_cookie, time() + (60 * 60 * 24), "/");
                
                exit();
            }
         }
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