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
        $sql = "SELECT
            p.id AS id_pedido,
            p.cantidad,
            p.mensaje,
            p.fecha,
            p.fecha_entrega,
            c.nombre AS nombre_comida,
            c.descripcion,
            c.precio,
            c.url_imagen
        FROM pedidos p
        INNER JOIN comidas c ON p.id_comida = c.id
        WHERE p.id_usuario = ?
        ORDER BY p.realizado ASC, p.fecha DESC";
        /*$sql="SELECT * FROM pedidos WHERE id_usuario = ?";*/
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
    public function crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje, $fecha_entrega = null) {
        $sql = "INSERT INTO pedidos (id_usuario, id_comida, cantidad, mensaje, fecha_entrega) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiiss", $id_usuario, $id_comida, $cantidad, $mensaje, $fecha_entrega);
        return $stmt->execute();
    }

    public function consultar_pedidos_admin() {
        if (!$this->conn) return null;
        $sql = "SELECT
            p.id AS id_pedido,
            p.cantidad,
            p.mensaje,
            p.fecha,
            p.fecha_entrega,
            p.realizado,
            c.nombre AS nombre_comida,
            c.descripcion,
            c.precio,
            c.url_imagen,
            u.id AS id_usuario,
            u.nombre AS nombre_usuario,
            u.email AS email_usuario
        FROM pedidos p
        INNER JOIN comidas c ON p.id_comida = c.id
        INNER JOIN usuarios u ON p.id_usuario = u.id
        ORDER BY
            (p.realizado = 0 AND p.fecha_entrega < CURDATE()) DESC,
            p.realizado ASC,
            p.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) return null;
        if (!$stmt->execute()) return null;
        $result = $stmt->get_result();
        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }
        return $pedidos ?: null;
    }

    public function eliminar_pedido($id) {
        $sql = "DELETE FROM pedidos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function eliminar_pedido_usuario($id_pedido, $id_usuario) {
        $sql = "DELETE FROM pedidos
                WHERE id = ? AND id_usuario = ?
                AND fecha_entrega > DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) return false;
        $stmt->bind_param("ii", $id_pedido, $id_usuario);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function marcar_realizado($id) {
        $sql = "UPDATE pedidos SET realizado = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) return false;
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
?>