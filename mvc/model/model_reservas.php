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
            return $result->fetch_all();
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
        $sql = "INSERT INTO reservas (id_usuario, id_producto,cantidad) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("iii", $id_usuario, $id_producto, $cantidad);
        $result = $stmt->execute();
        $stmt->close();
        return $result;

    }

    public function eliminar_reserva($id_reserva){
        if (!$this->conn) {
            return false;
        }
        if (!$this->agregar_stock($id_reserva)) {
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
        $stmt->bind_param("ii", $id_usuario);
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
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("i", $id_usuario);
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_all();
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