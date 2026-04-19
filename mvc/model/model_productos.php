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
    public function add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$subcategoria,$imagen,$porcentaje_descuento){
        //Crear imagen he indicar ruta
        $carpeta = "../imagenes";
        $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
        $ruta = $carpeta . $nombre_archivo;
        //Enviar la imagen a el archivo de imagenes
        if(move_uploaded_file($imagen['tmp_name'], $ruta)){
            $stmt=$this->conn->prepare("INSERT INTO productos (nombre,stock,precio,precio_por_peso,categoria,subcategoria,url_imagen,porcentaje_descuento) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("siiissi",$nombre,$stock,$precio,$precio_por_peso,$categoria,$subcategoria,$ruta,$porcentaje_descuento);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
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
    //Eliminar producto
    public function del_producto($id){
        $stmt=$this->conn->prepare("SELECT * FROM productos WHERE id=?");
        $stmt->bind_param("i",$id);
        if($stmt->execute()){
            $result=$stmt->get_result();
            while($row=$result->fetch_assoc()){
                $producto=$row;
            }
            //Eliminar imagen del directorio
            unlink($producto["url_imagen"]);
            $stmt=$this->conn->prepare("DELETE FROM productos WHERE id=?");
            $stmt->bind_param("i",$id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
       

    }
    //Actualizar un producto
    /*IMPORTANTE!!!: Para llamar ha esta función se debe de hacer de esta manera
        $this->update_producto(id: 1,nombre:patata ,porcentaje_descuento: 50); 
        Se debe hacer asi para que la función sepa que datos en especifico estas mandando*/
   public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $subcategoria=null, $imagen=null, $porcentaje_descuento=null) {
        $sql = "UPDATE productos SET ";
        $tipos = "";       
        $valores = [];    

        if($nombre !== null) { $sql .= "nombre=?, "; $tipos .= "s"; $valores[] = $nombre; }
        if($stock !== null) { $sql .= "stock=?, "; $tipos .= "d"; $valores[] = $stock; }
        if($precio !== null) { $sql .= "precio=?, "; $tipos .= "d"; $valores[] = $precio; }
        if($precio_por_peso !== null) { $sql .= "precio_por_peso=?, "; $tipos .= "i"; $valores[] = $precio_por_peso; }
        if($categoria !== null) { $sql .= "categoria=?, "; $tipos .= "s"; $valores[] = $categoria; }
        if($subcategoria !== null) { $sql .= "subcategoria=?, "; $tipos .= "s"; $valores[] = $subcategoria; }
        
        // GESTIÓN DE IMAGEN
        if(is_array($imagen) && isset($imagen['tmp_name']) && $imagen['tmp_name'] != "") {
            
            // 1. Obtener la imagen antigua para borrarla
            $stmt_old = $this->conn->prepare("SELECT url_imagen FROM productos WHERE id=?");
            $stmt_old->bind_param("i", $id);
            $stmt_old->execute();
            $res = $stmt_old->get_result()->fetch_assoc();
            
            if($res && !empty($res['url_imagen'])){
                // Si la URL guardada es "../imagenes/foto.jpg", desde el administrador
                // el archivo físico real está en "../../imagenes/foto.jpg"
                $ruta_fisica_vieja = "../" . $res['url_imagen']; 
                if(file_exists($ruta_fisica_vieja)){
                    unlink($ruta_fisica_vieja);
                }
            }

            // 2. Definir las rutas
            // Esta es la ruta para PHP (moverse por las carpetas del servidor)
            $carpeta_fisica = "../../imagenes/"; 
            // Esta es la ruta para la BASE DE DATOS (lo que entenderá el navegador)
            $ruta_base_datos = "../imagenes/";

            if (!file_exists($carpeta_fisica)) {
                mkdir($carpeta_fisica, 0777, true);
            }

            $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
            
            // 3. Mover el archivo usando la ruta física (sube 2 niveles)
            if(move_uploaded_file($imagen['tmp_name'], $carpeta_fisica . $nombre_archivo)) {
                $sql .= "url_imagen=?, ";
                $tipos .= "s";
                // Guardamos la ruta que te funciona visualmente (sube 1 nivel)
                $valores[] = $ruta_base_datos . $nombre_archivo; 
            }
        }

        if($porcentaje_descuento !== null) { $sql .= "porcentaje_descuento=?, "; $tipos .= "d"; $valores[] = $porcentaje_descuento; }

        if(empty($valores)) return false;

        $sql = rtrim($sql, ", ") . " WHERE id=?";
        $tipos .= "i";
        $valores[] = $id;

        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($tipos, ...$valores);
            return $stmt->execute();
        }
        return false;
    }
    

}
?>