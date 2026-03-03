<?php
    class bd{
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "supermercado";
        public $conn;
        public function conectar(){
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                return null;
            } else {
                return $this->conn;
            }
        }

    }
?>