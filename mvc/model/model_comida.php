<?php

require_once __DIR__ . "/../bd/bd.php";

class model_comida {
    private $conn;

    public function __construct() {
        $base = new bd();
        $this->conn = $base->conectar();
    }

    // Mostrar todas las comidas
    public function mostrar_comidas() {
        $stmt = $this->conn->prepare("SELECT * FROM comidas");
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

    // Añadir una comida
    public function añadir_comida($nombre, $descripcion, $precio, $disponible, $imagen) {
        $carpeta = "../imagenes/";
        $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
        $ruta = $carpeta . $nombre_archivo;
        if (move_uploaded_file($imagen['tmp_name'], $ruta)) {
            $stmt = $this->conn->prepare("INSERT INTO comidas (nombre, descripcion, precio, disponible, url_imagen) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdis", $nombre, $descripcion, $precio, $disponible, $ruta);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
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
            if ($comida && file_exists($comida['url_imagen'])) {
                unlink($comida['url_imagen']);
            }
            $carpeta = "../imagenes/";
            $nombre_archivo = uniqid() . "_" . basename($imagen['name']);
            $ruta = $carpeta . $nombre_archivo;
            move_uploaded_file($imagen['tmp_name'], $ruta);
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
