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
            //Cambiado el código ya que en al versión anterior se hacian dos fetch lo que hace que se mueva el puntero y el segundo fetch acaba devolviendo vacio
            $user=$result->fetch_assoc();
            if ($result->num_rows > 0 && password_verify($password,$user["contrasena"] )) {
                $stmt = $this->conn->prepare("UPDATE usuarios SET ultimo_inicio_sesion = NOW() WHERE id = ?");
                $stmt->bind_param("i",$user["id"]);
                if(!$stmt->execute()){
                    return null;
                }
                $stmt->execute();
                return $user;
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