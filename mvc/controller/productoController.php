<?php
    require_once("../model/model_productos.php");
    class ProductoController{
        private $model;
        public function __construct(){
            $this->model=new model_productos();
        }

        public function mostrar_productos(){
            $productos=$this->mostrar_productos();
            //require "vista" 
        }
        function buscar_producto($nombre){
            $productos_buscar=$this->buscar_producto($nombre);
            //Descomentar linea de abajo en acso de que se valla a realizar de manera simultanea
            //$productos=$this->mostrar_productos();

            //require "vista" 
        }
        public function add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$imagen,$porcentaje_descuento){
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
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $mime = mime_content_type($imagen);

            if(empty($imagen)){
                $errores[]="La categoria es obligatoria";
            }else if(!in_array($mime, $tiposPermitidos)){
                $errores[]="Solo se permiten archivos jpeg, png y jpg";
            }
            if(!empty($errores)){
                $this->model->add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$imagen,$porcentaje_descuento);
            }
            //Para mostrar $errores
            //require "vista"
        }
        public function del_producto($id){
            return $this->model->del_producto();
        }
        public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $imagen=null, $porcentaje_descuento=null){
            return $this->update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $imagen=null, $porcentaje_descuento=null);
        }

    }
?>