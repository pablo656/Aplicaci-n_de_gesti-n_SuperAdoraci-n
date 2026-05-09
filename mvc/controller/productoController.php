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
    // Añadimos image/webp a la lista
    $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $errores = [];
    
    $nombreImagenFinal = "../imagenes/foto_defecto.jpg";

    if (isset($imagen['tmp_name']) && $imagen['error'] === UPLOAD_ERR_OK) {
        $mime = mime_content_type($imagen['tmp_name']);

        if (!in_array($mime, $tiposPermitidos)) {
            $errores[] = "Solo se permiten archivos jpeg, png, jpg y webp";
        } else {
            $directorioMVC = dirname(__DIR__) . DIRECTORY_SEPARATOR;
            $carpetaDestino = "imagenes" . DIRECTORY_SEPARATOR;
            $rutaAbsolutaServidor = $directorioMVC . $carpetaDestino;

            if (!is_dir($rutaAbsolutaServidor) && !mkdir($rutaAbsolutaServidor, 0755, true)) {
                $errores[] = "No se pudo crear el directorio de imágenes en el servidor.";
            } else {
                $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
                $nombreArchivoUnico = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
                $destinoFinal = $rutaAbsolutaServidor . $nombreArchivoUnico;
                $nombreImagenFinal = "../imagenes/" . $nombreArchivoUnico;

                if (!move_uploaded_file($imagen['tmp_name'], $destinoFinal)) {
                    $errores[] = "Error al guardar la imagen. Ruta: " . htmlspecialchars($destinoFinal) . " | PHP error: " . $imagen['error'];
                }
            }
        }
    } elseif (isset($imagen['error']) && $imagen['error'] !== UPLOAD_ERR_NO_FILE && $imagen['error'] !== UPLOAD_ERR_OK) {
        $codigosError = [
            UPLOAD_ERR_INI_SIZE   => "La imagen supera el tamaño máximo permitido por el servidor (upload_max_filesize).",
            UPLOAD_ERR_FORM_SIZE  => "La imagen supera el tamaño máximo indicado en el formulario.",
            UPLOAD_ERR_PARTIAL    => "La imagen se subió parcialmente.",
            UPLOAD_ERR_NO_TMP_DIR => "Falta la carpeta temporal en el servidor.",
            UPLOAD_ERR_CANT_WRITE => "No se pudo escribir la imagen en el disco.",
            UPLOAD_ERR_EXTENSION  => "Una extensión de PHP detuvo la subida.",
        ];
        $errores[] = $codigosError[$imagen['error']] ?? "Error desconocido al subir la imagen (código: " . $imagen['error'] . ").";
    }

    if (empty($errores)) {
        $resultado = $this->model->add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $nombreImagenFinal, $porcentaje_descuento);
        header("Location: IndexProducto-administrador.php?success=1");
        exit();
    } else {
        // IMPORTANTE: Devolvemos los errores para capturarlos en la vista
        return $errores;
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
        public function aniadirInicio($id){
            $this->model->aniadirInicio($id);
        }
        public function quitarInicio($id){
            $this->model->quitarInicio($id);
        }
        public function home(){
            $productos=$this->model->buscar_productos_inicio();
            require("../vista/Inicio.php");
        }
    }
?>