<?php
    require_once __DIR__ . "/../bd/bd.php";
    class model_user{
        private $conn;
        public function __construct(){
            $base = new bd();
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
        //Rol predeterminado cliente, para cambiar el rol, se tendra que crear una una función y página para el admin y dueño que permita cambiar el rol.
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
            //Linea posiblemente obsoleta borrar si llega a ser inutil
            //$result = $stmt->execute();
            if(!$stmt->execute()){
                return false;
            }
            //Obtener ID del usuario creado
            $id=$this->conn->insert_id;
            $stmt->close();
            $stmt=$this->conn->prepare("SELECT * FROM usuarios WHERE id=?");
            $stmt->bind_param("i",$id);
            if(!$stmt->execute()){
                return false;
            }
            $resultado=$stmt->get_result();
            $usuario=null;
            while($row=$resultado->fetch_assoc()){
                $usuario=$row;
            }
            $stmt->close();
            //Se devuelve al usuario para crear una sessión en el controller
            return $usuario;


        }

        private function comprobarusuario_crear($user, $email){
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