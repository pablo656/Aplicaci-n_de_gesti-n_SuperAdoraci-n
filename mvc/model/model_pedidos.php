<?php

require_once __DIR__ . "/../bd/bd.php";

class model_pedidos{
    private $conn;

    public function __construct()
    {
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
    public function mostrar_pedidos(){
        if (!$this->conn) {
            return null;
        }
        $sql = "SELECT * FROM pedidos";
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
        $sql = "SELECT * FROM pedidos WHERE nombre = ?";
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

}
?>