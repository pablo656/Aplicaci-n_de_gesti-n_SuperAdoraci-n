<?php
require_once __DIR__ . "/../bd/bd.php";
class model_productos{
    private $conn;
    public function __construct(){
        $base = new bd();
        $this->conn = $base->conectar();
    }
    //Mostrar todos los productos
    public function mostrar_productos(){
        $stmt=$this->conn->prepare("SELECT * FROM productos");
        $productos=[];
        if($stmt->execute()){
            $result=$stmt->get_result();
            while($row=$result->fetch_assoc()){
                $productos[]=$row;
            }
        }else{
           return false;
        }
        return $productos; 
    }
    //Añadir un producto 
    public function add_productos($nombre, $stock, $precio, $precio_por_peso, $categoria, $subcategoria, $imagen, $porcentaje_descuento) {
        // La variable $imagen ya contiene la ruta final (ej: "imagenes/12345.jpg")
        // enviada desde el controlador, así que no hace falta usar move_uploaded_file aquí.
        
        $stmt = $this->conn->prepare("INSERT INTO productos (nombre, stock, precio, precio_por_peso, categoria, subcategoria, url_imagen, porcentaje_descuento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Ajustamos los tipos de bind_param:
        // s = string, i = integer, d = double/decimal
        // nombre(s), stock(d/i), precio(d), peso(i), cat(s), subcat(s), img(s), desc(i)
        $stmt->bind_param("sddisssi", 
            $nombre, 
            $stock, 
            $precio, 
            $precio_por_peso, 
            $categoria, 
            $subcategoria, 
            $imagen, 
            $porcentaje_descuento
        );
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
    public function comprobar_stock($id,$cantidad){
        $stmt=$this->conn->prepare("SELECT * FROM productos WHERE id=?");
        $stmt->bind_param("i",$id);
         if(!$stmt->execute()){
                return false;
         }
         $producto=null;
         $result=$stmt->get_result();
         while($row=$result->fetch_assoc()){
            $producto=$row;
         }
         if($producto["stock"]<$cantidad){
            return false;
         }else{
            return true;
         }

    }
    //Esta función sirve para mostrar todos los productos reservados de los cuales aun no se confirmado la reservas
    public function buscar_reservas_incompletas($reservas_cookie){
        $reservas=[];

        foreach($reservas_cookie as $reserva_cookie){
            
            $stmt=$this->conn->prepare("SELECT * FROM productos WHERE id=? ORDER BY nombre");
            $stmt->bind_param("i",$reserva_cookie["id"]);
            if(!$stmt->execute()){
                return false;
            }
            $resutado=$stmt->get_result();
            while($row=$resutado->fetch_assoc()){
                $reservas[]=$row;
            }
        }
        return $reservas;
    }
    //Buscar producto por nombre
    //IMPORTANTE!!!:Esta función se aplicara para un buscador el cual en el caso de escribir "pa" te devolvera pan,patata y todas las productos que empiecen por "pa"
    public function buscar_producto($nombre){
        $stmt=$this->conn->prepare("SELECT * FROM productos WHERE nombre LIKE ? ORDER BY nombre");
        $stmt->bind_param("s",$nombre);
        if(!$stmt->execute()){
            return false;
        }
        $resutado=$stmt->get_result();
        $productos=[];
        while($row=$resutado->fetch_assoc()){
            $productos[]=$row;
        }
        return $productos;
    }
    public function buscar_por_categoria($categoria){
        $stmt=$this->conn->prepare("SELECT * FROM productos WHERE categoria=? ORDER BY nombre");
        $stmt->bind_param("s",$categoria);
        if(!$stmt->execute()){
            return false;
        }
        $resutado=$stmt->get_result();
        $productos=[];
        while($row=$resutado->fetch_assoc()){
            $productos[]=$row;
        }
        return $productos;
    }
    public function buscar_por_subcategoria($subcategoria){
        $stmt=$this->conn->prepare("SELECT * FROM productos WHERE subcategoria=? ORDER BY nombre");
        $stmt->bind_param("s",$subcategoria);
        if(!$stmt->execute()){
            return false;
        }
        $resutado=$stmt->get_result();
        $productos=[];
        while($row=$resutado->fetch_assoc()){
            $productos[]=$row;
        }
        return $productos;
    }
    public function buscar_productos_inicio(){
        $sql="SELECT * FROM productos WHERE inicio=1";
        $stmt=$this->conn->prepare($sql);
        if(!$stmt->execute()){
            return null;
        }
        $result=$stmt->get_result();
        $productos=[];
        while($row=$result->fetch_assoc()){
            $productos[]=$row;
        }
        return $productos;
    }
    public function aniadirInicio($id){
        $sql="UPDATE productos SET inicio = 1 WHERE id = ?";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        if(!$stmt->execute()){
            return false;
        }
    }
    public function quitarInicio($id){
        
        $sql="UPDATE productos SET inicio = 0 WHERE id = ?";
        $stmt=$this->conn->prepare($sql);
        $stmt->bind_param("i",$id);
        if(!$stmt->execute()){
            return false;
        }
    }
    //Eliminar producto
    public function del_producto($id) {
        // 1. Obtener la ruta de la imagen
        $stmt = $this->conn->prepare("SELECT url_imagen FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $producto = $result->fetch_assoc();

            if ($producto) {
                $rutaBD = $producto["url_imagen"]; // Ejemplo: "../imagenes/imagen_prueba2.png"
                
                /**
                 * 2. CONSTRUIR RUTA FÍSICA
                 * Si la BD tiene "../imagenes/...", significa que la ruta ya intenta subir un nivel.
                 * Como el modelo está en mvc/model/, "../imagenes" apunta correctamente a mvc/imagenes.
                 */
                $rutaFisica = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rutaBD);

                // 3. Borrar el archivo (Protegiendo la imagen por defecto)
                // Comparamos con las dos variantes posibles de la imagen por defecto
                if (!empty($rutaBD) && 
                    $rutaBD !== "imagenes/foto_defecto.jpg" && 
                    $rutaBD !== "../imagenes/foto_defecto.jpg") {
                    
                    if (file_exists($rutaFisica)) {
                        unlink($rutaFisica);
                    }
                }

                // 4. Eliminar de la base de datos
                $stmtDel = $this->conn->prepare("DELETE FROM productos WHERE id = ?");
                $stmtDel->bind_param("i", $id);
                
                return $stmtDel->execute();
            }
        }
        
        return false;
    }
    //Actualizar un producto
    /*IMPORTANTE!!!: Para llamar ha esta función se debe de hacer de esta manera
        $this->update_producto(id: 1,nombre:patata ,porcentaje_descuento: 50); 
        Se debe hacer asi para que la función sepa que datos en especifico estas mandando*/
   public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $subcategoria=null, $imagen=null, $porcentaje_descuento=null) {
    
    // 1. VALIDACIÓN DE SEGURIDAD PARA LA IMAGEN
    if(is_array($imagen) && isset($imagen['tmp_name']) && $imagen['tmp_name'] != "") {
        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        
        // Verificamos el tipo MIME real del archivo
        $mime = mime_content_type($imagen['tmp_name']);
        
        if (!in_array($mime, $tiposPermitidos)) {
            // Retornamos el mensaje de error para que el controlador lo use en el alert
            return "Error: Solo se permiten imágenes JPG, PNG y WebP.";
        }
    }

    $sql = "UPDATE productos SET ";
    $tipos = "";       
    $valores = [];    

    // Construcción dinámica de la consulta
    if($nombre !== null) { $sql .= "nombre=?, "; $tipos .= "s"; $valores[] = $nombre; }
    if($stock !== null) { $sql .= "stock=?, "; $tipos .= "d"; $valores[] = $stock; }
    if($precio !== null) { $sql .= "precio=?, "; $tipos .= "d"; $valores[] = $precio; }
    if($precio_por_peso !== null) { $sql .= "precio_por_peso=?, "; $tipos .= "i"; $valores[] = $precio_por_peso; }
    if($categoria !== null) { $sql .= "categoria=?, "; $tipos .= "s"; $valores[] = $categoria; }
    if($subcategoria !== null) { $sql .= "subcategoria=?, "; $tipos .= "s"; $valores[] = $subcategoria; }
    
    // 2. GESTIÓN FÍSICA DE LA IMAGEN
    if(is_array($imagen) && isset($imagen['tmp_name']) && $imagen['tmp_name'] != "") {
        
        // A. Obtener la imagen antigua para borrarla y no llenar el servidor de basura
        $stmt_old = $this->conn->prepare("SELECT url_imagen FROM productos WHERE id=?");
        $stmt_old->bind_param("i", $id);
        $stmt_old->execute();
        $res = $stmt_old->get_result()->fetch_assoc();
        
        if($res && !empty($res['url_imagen'])){
            // Ajuste de ruta para borrar desde la ubicación del controlador/modelo
            $ruta_fisica_vieja = "../" . $res['url_imagen']; 
            if(file_exists($ruta_fisica_vieja)){
                unlink($ruta_fisica_vieja);
            }
        }

        // B. Configurar nuevas rutas
        $carpeta_fisica = "../../imagenes/"; 
        $ruta_base_datos = "../imagenes/";

        if (!file_exists($carpeta_fisica)) {
            mkdir($carpeta_fisica, 0777, true);
        }

        // Generar nombre único para evitar sobrescribir archivos con el mismo nombre
        $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
        
        // C. Mover el archivo al destino final
        if(move_uploaded_file($imagen['tmp_name'], $carpeta_fisica . $nombre_archivo)) {
            $sql .= "url_imagen=?, ";
            $tipos .= "s";
            $valores[] = $ruta_base_datos . $nombre_archivo; 
        } else {
            return "Error: No se pudo guardar la imagen en el servidor.";
        }
    }

    if($porcentaje_descuento !== null) { 
        $sql .= "porcentaje_descuento=?, "; 
        $tipos .= "d"; 
        $valores[] = $porcentaje_descuento; 
    }

    // Si no hay campos para actualizar, salimos
    if(empty($valores)) return false;

    // Finalizar la consulta SQL
    $sql = rtrim($sql, ", ") . " WHERE id=?";
    $tipos .= "i";
    $valores[] = $id;

    // 3. EJECUCIÓN EN LA BASE DE DATOS
    $stmt = $this->conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($tipos, ...$valores);
        $ejecucion = $stmt->execute();
        
        // Si todo sale bien, devolvemos true, si no, el error de la base de datos
        return $ejecucion ? true : "Error en la base de datos al actualizar.";
    }
    
    return "Error al preparar la consulta.";
}
    

}
?>