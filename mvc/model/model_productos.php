<?php
require_once __DIR__ . "/../bd/bd.php";
class model_productos{
    private $conn;
    public function __construct(){
        $base = new bd();
        $this->conn = $base->conectar();
    }
}
?>