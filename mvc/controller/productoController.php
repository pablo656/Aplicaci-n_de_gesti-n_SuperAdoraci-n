<?php
    // Ruta absoluta para el modelo
    require_once __DIR__ . "/../model/model_productos.php";

    // Controlador que gestiona el catálogo de productos
    class ProductoController{
        private $model;

        public function __construct(){
            $this->model = new model_productos();
        }

        // Obtiene todos los productos y carga la vista del catálogo
        public function mostrar_productos(){
            $productos = $this->model->mostrar_productos();
            require __DIR__ . "/../vista/catalogo.php";
        }

        public function mostrar_productos_admin(){
            $productos = $this->model->mostrar_productos();
            require __DIR__ . "/../vista/administrador/productos-administrador.php";
        }

        public function comprobar_stock($id, $cantidad){
            return $this->model->comprobar_stock($id, $cantidad);
        }

        public function buscar_reservas_incompletas($ids, $pedidos_carrito=[]){
            $reservas = $this->model->buscar_reservas_incompletas($ids);
            require __DIR__ . "/../vista/carrito.php";
        }

        // Busca productos por nombre
        public function buscar_producto($nombre){
            $productos = $this->model->buscar_producto($nombre);
            require __DIR__ . "/../vista/catalogo.php";
        }

        // Filtra productos por categoría
        public function buscar_por_categorias($categoria){
            $productos = $this->model->buscar_por_categoria($categoria);
            require __DIR__ . "/../vista/catalogo.php";
        }

        // Filtra productos por subcategoría
        public function buscar_por_subcategoria($subcategoria){
            $productos = $this->model->buscar_por_subcategoria($subcategoria);
            require __DIR__ . "/../vista/catalogo.php";
        }

        // Valida campos y añade producto
        public function add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $porcentaje_descuento){
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $errores = [];

            if(empty($nombre)) $errores[] = "El campo nombre es obligatorio";
            
            if(empty($stock)){
                $errores[] = "El campo stock es obligatorio";
            } else if($stock < 0){
                $errores[] = "El stock no puede ser negativo";
            }

            if(empty($precio)){
                $errores[] = "El precio es obligatorio";
            } else if($precio < 0){
                $errores[] = "El precio no puede ser negativo";
            }

            if(empty($categoria)) $errores[] = "La categoria es obligatoria";
            
            if(empty($subcategoria)) $subcategoria = null;

            if(!empty($imagen)){
                $mime = mime_content_type($imagen);
                if(!in_array($mime, $tiposPermitidos)){
                    $errores[] = "Solo se permiten archivos jpeg, png y jpg";
                }
            }

            if(empty($errores)){
                $this->model->add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $imagen, $porcentaje_descuento);
            }

            require __DIR__ . "/../vista/catalogo.php";
        }

        public function del_producto($id){
            return $this->model->del_producto($id);
        }

        public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $subcategoria=null, $imagen=null, $porcentaje_descuento=null){
            // Nota: Aquí corregí para que use las variables pasadas y no solo nulls
            return $this->model->update_producto($id, $nombre, $stock, $precio, $precio_por_peso, $categoria, $imagen, $porcentaje_descuento);
        }
    }
?>