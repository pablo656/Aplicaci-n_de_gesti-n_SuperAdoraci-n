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
        $sql = "SELECT * FROM reservas";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }
    public function crear_reserva($id_usuario, $id_producto, $cantidad){
        if (!$this->conn) {
            return false;
        }
        if (!$this->consultar_reserva($id_producto)) {
            return false;
        }
        $sql = "INSERT INTO reservas (id_usuario, id_producto,$cantidad) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ss", $id_usuario, $id_producto);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    private function consultar_reserva($id_producto){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("s", $id_producto);
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            
        } else {
            return null;
        }
    }
    public function eliminar_reserva($id_reserva){
        if (!$this->conn) {
            return false;
        }
        $sql = "DELETE FROM reservas WHERE id_reserva = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("s", $id_reserva);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>