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
        public function buscar_por_categorias_admin($categoria){
            $productos = $this->model->buscar_por_categoria($categoria);
            require __DIR__ . "/../vista/administrador/productos-administrador.php";
        }
        // Filtra productos por subcategoría
        public function buscar_por_subcategoria($subcategoria){
            $productos = $this->model->buscar_por_subcategoria($subcategoria);
            require __DIR__ . "/../vista/catalogo.php";
        }
        public function buscar_por_subcategoria_admin($subcategoria){
            $productos = $this->model->buscar_por_subcategoria($subcategoria);
            require __DIR__ . "/../vista/administrador/productos-administrador.php";
        }

        // Valida campos y añade producto
        public function add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $porcentaje_descuento) {
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
            $errores = [];
            
            // Ruta por defecto si el usuario no sube ninguna imagen
            $nombreImagenFinal = "imagenes/foto_defecto.jpg"; 

            // 1. Validaciones de campos de texto
            if (empty($nombre)) {
                $errores[] = "El campo nombre es obligatorio";
            }
            
            if ($stock === "" || $stock === null) {
                $errores[] = "El campo stock es obligatorio";
            } else if ($stock < 0) {
                $errores[] = "El stock no puede ser negativo";
            }

            if (empty($precio)) {
                $errores[] = "El precio es obligatorio";
            } else if ($precio < 0) {
                $errores[] = "El precio no puede ser negativo";
            }

            if (empty($categoria)) {
                $errores[] = "La categoría es obligatoria";
            }
            
            if (empty($subcategoria)) {
                $subcategoria = null;
            }

            // 2. Validación y Procesamiento de la Imagen
            // Verificamos si existe el archivo en el array $_FILES y si no tiene errores
            if (isset($imagen['tmp_name']) && $imagen['error'] === UPLOAD_ERR_OK) {
                
                // Obtenemos el tipo MIME real del archivo temporal
                $mime = mime_content_type($imagen['tmp_name']);
                
                if (!in_array($mime, $tiposPermitidos)) {
                    $errores[] = "Solo se permiten archivos jpeg, png y jpg";
                } else {
                    // --- CONFIGURACIÓN DE RUTA ---
                    // dirname(__DIR__) nos sitúa en la carpeta "mvc" (sube un nivel desde "controller")
                    $directorioMVC = dirname(__DIR__) . DIRECTORY_SEPARATOR; 
                    $carpetaDestino = "imagenes" . DIRECTORY_SEPARATOR;
                    $rutaAbsolutaServidor = $directorioMVC . $carpetaDestino;

                    // Creamos la carpeta si no existe por algún motivo
                    if (!is_dir($rutaAbsolutaServidor)) {
                        mkdir($rutaAbsolutaServidor, 0777, true);
                    }

                    // Generamos un nombre único para la imagen
                    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
                    $nombreArchivoUnico = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
                    
                    // Ruta completa donde se escribirá el archivo en el disco
                    $destinoFinal = $rutaAbsolutaServidor . $nombreArchivoUnico;
                    
                    // Ruta relativa que guardaremos en la base de datos
                    $nombreImagenFinal = "../imagenes/" . $nombreArchivoUnico;

                    // Intentamos mover el archivo de la carpeta temporal a la carpeta mvc/imagenes/
                    if (!move_uploaded_file($imagen['tmp_name'], $destinoFinal)) {
                        $errores[] = "Error al guardar la imagen en el servidor. Revisa los permisos de la carpeta imagenes.";
                    }
                }
            }

            // 3. Ejecución final
            if (empty($errores)) {
                // Llamamos al modelo pasando la ruta (string) de la imagen
                $resultado = $this->model->add_productos(
                    $nombre, 
                    $stock, 
                    $precio, 
                    $precio_por_peso, 
                    $categoria, 
                    $subcategoria, 
                    $nombreImagenFinal, 
                    $porcentaje_descuento
                );
                
                // Redirigimos al índice para confirmar el éxito y evitar reenvíos de formulario
                header("Location: IndexProducto-administrador.php?success=1");
                exit();
            } else {
                // Si hubo errores, cargamos la vista del catálogo para mostrarlos
                // Asegúrate de que en tu vista recorres e imprimes el array $errores
                require __DIR__ . "/../vista/catalogo.php";
            }
        }

        public function del_producto($id){
            return $this->model->del_producto($id);
        }

        public function update_producto($id, $nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $descuento) {
            // LLAMADA AL MODELO: El orden debe ser id, nombre, stock, precio, peso, cat, sub, img, desc
            return $this->model->update_producto($id, $nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $descuento);
        }
        public function buscar_productos_inicio_administrador(){
            $productos=$this->model->buscar_productos_inicio();
            require __DIR__ . "/../vista/administrador/inicio-administrador.php";
        }
        public function buscar_productos_inicio(){
            $productos=$this->model->buscar_productos_inicio();
            require __DIR__ . "/../vista/administrador/inicio-administrador.php";
        }
    }
?>