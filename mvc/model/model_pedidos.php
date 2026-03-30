<?php

require_once __DIR__ . "/../bd/bd.php";

class model_pedidos{
    private $conn;

    public function __construct(){
        $base = new bd();
        $this->conn = $base->conectar();
    }
    public function mostrar_pedidos_user($id_usuario){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM pedidos WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $pedidos = [];
        if ($stmt === false) {
            return null;
        }
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
            return $pedidos;
        } else {
            return null;
        }
    }
    public function mostrar_pedidos_order(){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM pedidos ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $pedidos = [];
        if ($stmt === false) {
            return null;
        }
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
            return $pedidos;
        } else {
            return null;
        }
    }
    public function mostrar_pedido_nombre($nombre){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM pedidos WHERE nombre LIKE CONCAT('%', ?, '%')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $pedidos = [];
        if ($stmt === false) {
            return null;
        }
        if (!$stmt->execute()) {
            return null;
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = $row;
            }
            return $pedidos;
        } else {
            return null;
        }
    }
    public function contar_pedidos_pendientes() {
        $sql = "SELECT COUNT(*) AS total FROM pedidos WHERE realizado = 0";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function mostrar_pedidos_por_estado($realizado) {
        $sql = "SELECT * FROM pedidos WHERE realizado = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $realizado);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos ?: null;
    }
    public function crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje) {
        $sql = "INSERT INTO pedidos (id_usuario, id_comida, cantidad, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", $id_usuario, $id_comida, $cantidad, $mensaje);
        return $stmt->execute();
    }

}
?>