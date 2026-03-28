<?php
    require_once("../model/model_productos.php");

    // Controlador que gestiona el catálogo de productos (mostrar, buscar, añadir, editar y eliminar)
    class ProductoController{
        private $model;

        // Instancia el modelo de productos
        public function __construct(){
            $this->model=new model_productos();
        }

        // Obtiene todos los productos y carga la vista del catálogo
        public function mostrar_productos(){
            $productos=$this->model->mostrar_productos();
            require("../vista/catalogo.php");
        }

        // Busca productos por nombre y carga el catálogo con los resultados
        function buscar_producto($nombre){
            $productos=$this->model->buscar_producto($nombre);
            //Descomentar linea de abajo en caso de que se valla a realizar de manera simultanea
            //$productos=$this->model->mostrar_productos();

            require("../vista/catalogo.php");
        }

        // Filtra productos por categoría y carga el catálogo
        function buscar_por_categorias($categoria){
            $productos=$this->model->buscar_por_categoria($categoria);
            //header("Location:catalogo.php?action=$categoria");

            require("../vista/catalogo.php");
        }

        // Filtra productos por subcategoría y carga el catálogo
        function buscar_por_subcategoria($subcategoria){
            $productos=$this->model->buscar_por_subcategoria($subcategoria);
            require("../vista/catalogo.php");
        }

        // Valida los campos del formulario y, si no hay errores, delega el alta al modelo
        public function add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$subcategoria,$imagen,$porcentaje_descuento){
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $errores=[];
            if(empty($nombre)){
                $errores[]="El campo nombre es obligatorio";
            }
            if(empty($stock)){
                $errores[]="El campo stock es obligatorio";
            }else if($stock<0){
                $errores[]="El stock no puede ser negativo";
            }else if(!is_int($stock)){
                $errores[]="El stock debe ser un numero entero (los decimales no cuentan)";
            }
            if(empty($precio)){
                $errores[]="El precio es obligatorio";
            }else if($precio<0){
                $errores[]="El precio no puede ser negativo";
            }else if(!preg_match('/^\d+(\.\d{1,2})?$/', $precio)){
                $errores[]="El precio debe tener 2 decimales como máximo";
            }
            if(empty($categoria)){
                $errores[]="La categoria es obligatoria";
            }
            // Si no se indica subcategoría se guarda como null
            if(emty($subcategoria)){
                $subcategoria=null;
            }
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $mime = mime_content_type($imagen);

            if(empty($imagen)){
                $errores[]="La categoria es obligatoria";
            }else if(!in_array($mime, $tiposPermitidos)){
                $errores[]="Solo se permiten archivos jpeg, png y jpg";
            }
            if(empty($errores)){
                $this->model->add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$imagen,$porcentaje_descuento);
            }
            //Para mostrar $errores
            require("../vista/catalogo.php");
        }

        // Elimina un producto por su ID
        public function del_producto($id){
            return $this->model->del_producto($id);
        }

        // Actualiza los campos de un producto; los parámetros no indicados se dejan como null
        // NOTA: actualmente pasa null en vez de las variables — los cambios no se guardan
        public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null,$subcategoria=null, $imagen=null, $porcentaje_descuento=null){
            return $this->model->update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $imagen=null, $porcentaje_descuento=null);
        }

    }
?>