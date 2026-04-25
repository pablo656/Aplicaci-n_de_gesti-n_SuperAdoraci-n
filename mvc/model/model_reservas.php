<?php
require_once __DIR__ . "/../bd/bd.php";
class model_reservas{
    private $conn;
    public function __construct(){
        $base = new bd();
        $this->conn = $base->conectar();
    }
    public function consultar_reservas(){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT 
            r.id AS id_reserva,
            r.cantidad,
            r.fecha,
            p.nombre AS nombre_producto,
            p.precio,
            p.precio_por_peso,
            p.categoria,
            p.url_imagen,
            p.porcentaje_descuento,
            p.subcategoria,
            u.id AS id_usuario,
            u.nombre AS nombre_usuario, 
            u.email AS email_usuario
        FROM reservas r
        INNER JOIN productos p ON r.id_producto = p.id
        INNER JOIN usuarios u ON r.id_usuario = u.id;";
        $stmt = $this->conn->prepare($sql);
        $reservas = [];
        if ($stmt === false) {
            return null;
        }
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservas[] = $row;
            }
            return $reservas;
        } else {
            return null;
        }
    }
    public function consultar_reservas_user($id){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT 
                r.id AS id_reserva, 
                r.cantidad, 
                r.fecha, 
                p.nombre AS nombre_producto, 
                p.precio, 
                p.categoria, 
                p.url_imagen, 
                p.porcentaje_descuento, 
                p.subcategoria 
            FROM reservas r 
            INNER JOIN productos p ON r.id_producto = p.id 
            WHERE r.id_usuario = ?;";
        $stmt = $this->conn->prepare($sql);
        $reservas = [];
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("i",$id);
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservas[] = $row;
            }
            return $reservas;
        } else {
            return null;
        }
    }
    public function crear_reserva($id_usuario, $id_producto, $cantidad){
        if (!$this->conn) {
            return false;
        }

        if (!$this->consultar_reserva($id_producto, $cantidad)) {
            return false;
        }
        //Comprobar que haya alguna reserva del mismo producto y usuario, para en vez de crear una nueva, actualizar la anterior
        //IMPORTANTE CAMBIAR en cado de añadir campo, de completado en reservas
        $sql="SELECT * FROM reservas WHERE id_usuario=? AND id_producto=?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ii",$id_usuario,$id_producto);
        $stmt->execute();
        $result=$stmt->get_result();
        $reservas=[];
        while($row=$result->fetch_assoc()){
            $reservas[]=$row;
        }
        
        $stmt->close();
        if(empty($reservas)){
            $sql = "INSERT INTO reservas (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) return false;
            $stmt->bind_param("iid", $id_usuario, $id_producto, $cantidad); // ← ya estaba bien
            $result = $stmt->execute();
            $stmt->close();
        }else{
            $sql = "UPDATE reservas SET cantidad=cantidad+? WHERE id_usuario=? AND id_producto=?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) return false;
            $stmt->bind_param("dii", $cantidad, $id_usuario, $id_producto); // ← "d" en lugar de "i"
            $result = $stmt->execute();
            $stmt->close();
        }

        $sql = "UPDATE productos SET stock=stock-? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) return false;
        $stmt->bind_param("di", $cantidad, $id_producto); // ← "d" en lugar de "i"
        $result = $stmt->execute();
        $stmt->close();
        return $result;

    }

    public function eliminar_reserva($id_reserva){
        if (!$this->conn) {
            return false;
        }
        $sql = "DELETE FROM reservas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("i", $id_reserva);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function eliminar_reserva_por_id_usuario($id_usuario){
        if (!$this->conn) {
            return false;
        }
        $sql = "DELETE FROM reservas WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("i", $id_usuario);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    //podría ser útil para mostrar las reservas de un usuario específico
    public function mostrar_reservas_usuario($id_usuario){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM reservas WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $reservas_usuario = [];
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reservas_usuario[] = $row;
            }
            return $reservas_usuario;
        } else {
            return null;
        }
    }

    //funciones de clase

    private function consultar_reserva($id, $cantidad){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM productos WHERE id = ? and stock >= ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("ss", $id, $cantidad);
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return null;
        }
    }

}
?>