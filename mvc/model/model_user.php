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

/*<<<<<<< HEAD
=======
        // Guarda un registro pendiente de confirmar por email
        public function guardar_verificacion($nombre, $email, $hash) {
            $token    = bin2hex(random_bytes(32));
            $expira   = date("Y-m-d H:i:s", strtotime("+24 hours"));
            $sql      = "INSERT INTO verificaciones_email (token, nombre, email, contrasena, expira_en)
                         VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("sssss", $token, $nombre, $email, $hash, $expira);
            if (!$stmt->execute()) return false;
            $stmt->close();
            return $token;
        }

        // Busca la verificación por token; la borra si ha expirado
        public function confirmar_verificacion($token) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM verificaciones_email WHERE token = ?"
            );
            if (!$stmt) return false;
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$fila) return false;

            if (strtotime($fila["expira_en"]) < time()) {
                $this->conn->prepare("DELETE FROM verificaciones_email WHERE token = ?")
                    ->bind_param("s", $token);
                return false;
            }

            // Crear el usuario real
            $sql  = "INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("sss", $fila["nombre"], $fila["email"], $fila["contrasena"]);
            if (!$stmt->execute()) return false;
            $id = $this->conn->insert_id;
            $stmt->close();

            // Borrar el token usado
            $del = $this->conn->prepare("DELETE FROM verificaciones_email WHERE token = ?");
            $del->bind_param("s", $token);
            $del->execute();
            $del->close();

            // Devolver datos del usuario para iniciar sesión
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $usuario = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $usuario;
        }

        public function actualizar_nombre($id, $nuevo_nombre) {
            $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE nombre = ? AND id != ?");
            $stmt->bind_param("si", $nuevo_nombre, $id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $stmt->close();
                return "nombre_duplicado";
            }
            $stmt->close();
            $stmt = $this->conn->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevo_nombre, $id);
            if (!$stmt->execute()) {
                $stmt->close();
                return false;
            }
            $stmt->close();
            return true;
        }

        public function crearusuario_existe($user, $email){
            return $this->comprobarusuario_crear($user, $email);
        }

>>>>>>> 79220663df93dbe286a9ef08d7ed2817a02f44a9*/
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