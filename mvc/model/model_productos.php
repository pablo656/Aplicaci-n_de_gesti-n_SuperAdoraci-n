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
            die("Error al obtener los productos");
        }
        return $productos; 
    }
    //Añadir un producto 
    public function add_productos($nombre,$stock,$precio,$precio_por_peso,$categoria,$imagen,$porcentaje_descuento){
        //Crear imagen he indicar ruta
        $carpeta = "../imagenes";
        $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
        $ruta = $carpeta . $nombre_archivo;
        //Enviar la imagen a el archivo de imagenes
        if(move_uploaded_file($imagen['tmp_name'], $ruta)){
            $stmt=$this->conn->prepare("INSERT INTO (nombre,stock,precio,precio_por_peso,categoria,url_imagen,porcentaje_descuento) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("siiissi",$nombre,$stock,$precio,$precio_por_peso,$categoria,$ruta,$porcentaje_descuento);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
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
    public function update_producto($id, $nombre=null, $stock=null, $precio=null, $precio_por_peso=null, $categoria=null, $imagen=null, $porcentaje_descuento=null){
        $sql = "UPDATE productos SET ";
        $tipos = "";       
        $valores = [];    

        if($nombre != null){
            $sql .= "nombre=?, ";
            $tipos .= "s";
            $valores[] = $nombre;
        }
        if($stock != null){
            $sql .= "stock=?, ";
            $tipos .= "i";
            $valores[] = $stock;
        }
        if($precio != null){
            $sql .= "precio=?, ";
            $tipos .= "d";
            $valores[] = $precio;
        }
        if($precio_por_peso != null){
            $sql .= "precio_por_peso=?, ";
            $tipos .= "d";
            $valores[] = $precio_por_peso;
        }
        if($categoria != null){
            $sql .= "categoria=?, ";
            $tipos .= "s";
            $valores[] = $categoria;
        }
        if($imagen != null){
            // Obtener imagen antigua y reemplazarla
            $stmt = $this->conn->prepare("SELECT url_imagen FROM productos WHERE id=?");
            $stmt->bind_param("i", $id);
            if(!$stmt->execute()){
                return false;
            }
            $producto = $stmt->get_result()->fetch_assoc();
            
            if(file_exists($producto['url_imagen'])){
                unlink($producto['url_imagen']);
            }

            $carpeta = "../imagenes/";
            $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
            $ruta = $carpeta . $nombre_archivo;
            move_uploaded_file($imagen['tmp_name'], $ruta);

            $sql .= "url_imagen=?, ";
            $tipos .= "s";
            $valores[] = $ruta;
        }
        if($porcentaje_descuento != null){
            $sql .= "porcentaje_descuento=?, ";
            $tipos .= "d";
            $valores[] = $porcentaje_descuento;
        }

    //Quitar la ultima coma 
        $sql = rtrim($sql, ", ") . " WHERE id=?";
        $tipos .= "i";
        $valores[] = $id;

        
        $stmt = $this->conn->prepare($sql);
        //Hacer bind_param con todos los valores necesarios
        $stmt->bind_param($tipos, ...$valores);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
       
    }

}
?>