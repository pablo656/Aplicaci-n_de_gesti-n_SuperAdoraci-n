<?php
    require_once __DIR__ . "/../db/db.php";
    class model_userser{
        private $conn;
        public function __construct()
        {
            $base = new db();
            $this->conn = $base->conectar();
        }
        public function iniciousuario($user, $password){
            if (!$this->conn) {
                return null;
            }
            $sql = "SELECT * FROM usuarios WHERE nombre = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return null;
            }
            $stmt->bind_param("s", $user);
            if (!$stmt->execute()) {
                return null;
            }
            $result = $stmt->get_result();
            if ($result->num_rows > 0 && password_verify($password, $result->fetch_assoc()['contrasena'])) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        }
        public function crearusuario($user, $password, $email){
            if (!$this->conn) {
                return false;
            }
            if ($this->comprobarusuario_crear($user, $email)) {
                return false;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, contrasena, email) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return false;
            }
            $stmt->bind_param("sss", $user, $hash, $email);
            $result = $stmt->execute();
            $stmt->close();
            return $result;


        }

        public function comprobarusuario_crear($user, $email){
            if (!$this->conn) {
                return null;
            }
            $sql = "SELECT * FROM usuarios WHERE nombre = ? OR email = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return null;
            }
            $stmt->bind_param("ss", $user, $email);
            if (!$stmt->execute()) {
                return null;
            }
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return true;
            } else {
                return false;
            }

        }

    }

?>