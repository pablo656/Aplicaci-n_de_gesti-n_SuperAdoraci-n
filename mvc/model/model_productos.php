<?php
require_once __DIR__ . "/../db/db.php";
class model_productos{
    private $conn;
    public function __construct(){
        $base = new db();
        $this->conn = $base->conectar();
    }
}
?>