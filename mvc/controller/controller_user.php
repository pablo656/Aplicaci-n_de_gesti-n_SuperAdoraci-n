<?php
require_once __DIR__ . "/../model/model_user.php";
class Controller_user{
    private $model_user;
    public function __construct(){
        $this->model_user = new model_user();
    }
    public function loginar($username, $password){
        $user= $this->model_user->selectusuarios($username, $password);
        if($user == false){
            // esto es temporal y puede quedar obsoleto
            require __DIR__ . '/../view/login.php';
        }else{
            require __DIR__ . '/../index_home.php';
            return $user;
        }
    }
    public function register($username, $password){
        $user = $this->model_user->crearusuario($username, $password);
        if ($user != false) {
            // esto es temporal y puede quedar obsoleto
            require __DIR__ . '/../index_home.php';
        }else{
            echo "Registro fallido, el usuario ya existe.";
            require __DIR__ . '/../view/login.php';
            return $user;
        }
    }
    //Funciones para moverse entre Home, Log in,Sign in y Perfil
    public function home(){
        require("../vista/Inicio.php");
    }
    public function log(){
        require("../vista/Log_in.php");
    }
    public function sing(){
        require("../vista/Sign_in.php");
    }
    public function perfil(){
        //require();
    }
}
?>
