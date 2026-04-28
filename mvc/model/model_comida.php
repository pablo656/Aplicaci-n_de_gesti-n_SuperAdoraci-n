<?php

require_once __DIR__ . "/../bd/bd.php";

class model_comida {
    private $conn;

    public function __construct() {
        $base = new bd();
        $this->conn = $base->conectar();
    }

    // Mostrar comidas; $solo_disponibles=true filtra las no disponibles
    public function mostrar_comidas($solo_disponibles = false) {
        $sql  = $solo_disponibles
            ? "SELECT * FROM comidas WHERE disponible = 1"
            : "SELECT * FROM comidas";
        $stmt = $this->conn->prepare($sql);
        $comidas = [];
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $comidas[] = $row;
            }
        } else {
            return false;
        }
        return $comidas;
    }

    // Devuelve las comidas cuyos IDs están en el cookie de pedidos
    public function buscar_pedidos_cookie($pedidos_cookie) {
        $pedidos = [];
        foreach ($pedidos_cookie as $item) {
            $stmt = $this->conn->prepare("SELECT * FROM comidas WHERE id=?");
            $stmt->bind_param("i", $item["id"]);
            if (!$stmt->execute()) return false;
            $resultado = $stmt->get_result();
            while ($row = $resultado->fetch_assoc()) {
                $row['_uid']      = $item['uid'] ?? $item['id'];
                $row['_cantidad'] = $item['cantidad'] ?? 1;
                $pedidos[] = $row;
            }
        }
        return $pedidos;
    }

    // Añadir una comida
    public function añadir_comida($nombre, $descripcion, $precio, $disponible, $imagen) {
        $carpeta_fs  = __DIR__ . "/../imagenes/";
        $url_base    = "../imagenes/";
        $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
        if (move_uploaded_file($imagen['tmp_name'], $carpeta_fs . $nombre_archivo)) {
            $ruta = $url_base . $nombre_archivo;
            $stmt = $this->conn->prepare("INSERT INTO comidas (nombre, descripcion, precio, disponible, url_imagen) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $disponible, $ruta);
            return $stmt->execute();
        }
        return false;
    }

    // Borrar una comida
    public function borrar_comida($id) {
        $stmt = $this->conn->prepare("SELECT url_imagen FROM comidas WHERE id=?");
        $stmt->bind_param("i", $id);
        if (!$stmt->execute()) {
            return false;
        }
        $comida = $stmt->get_result()->fetch_assoc();
        if ($comida && file_exists($comida['url_imagen'])) {
            unlink($comida['url_imagen']);
        }
        $stmt = $this->conn->prepare("DELETE FROM comidas WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Actualizar una comida — pasar solo los campos que se quieren cambiar:
     */
    public function update_comida($id, $nombre=null, $descripcion=null, $precio=null, $disponible=null, $imagen=null) {
        $sql = "UPDATE comidas SET ";
        $tipos = "";
        $valores = [];

        if ($nombre !== null) {
            $sql .= "nombre=?, ";
            $tipos .= "s";
            $valores[] = $nombre;
        }
        if ($descripcion !== null) {
            $sql .= "descripcion=?, ";
            $tipos .= "s";
            $valores[] = $descripcion;
        }
        if ($precio !== null) {
            $sql .= "precio=?, ";
            $tipos .= "d";
            $valores[] = $precio;
        }
        if ($disponible !== null) {
            $sql .= "disponible=?, ";
            $tipos .= "i";
            $valores[] = $disponible;
        }
        if ($imagen !== null) {
            $stmt = $this->conn->prepare("SELECT url_imagen FROM comidas WHERE id=?");
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                return false;
            }
            $comida = $stmt->get_result()->fetch_assoc();
            $fs_old = __DIR__ . "/../imagenes/" . basename($comida['url_imagen'] ?? '');
            if ($comida && file_exists($fs_old)) {
                unlink($fs_old);
            }
            $carpeta_fs = __DIR__ . "/../imagenes/";
            $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
            $ruta = "../imagenes/" . $nombre_archivo;
            move_uploaded_file($imagen['tmp_name'], $carpeta_fs . $nombre_archivo);
            $sql .= "url_imagen=?, ";
            $tipos .= "s";
            $valores[] = $ruta;
        }

        $sql = rtrim($sql, ", ") . " WHERE id=?";
        $tipos .= "i";
        $valores[] = $id;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($tipos, ...$valores);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
