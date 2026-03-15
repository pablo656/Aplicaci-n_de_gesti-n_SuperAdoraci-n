<?php
require_once __DIR__ . "/../model/model_user.php";
class Controller_user{
    private $model_user;
    public function __construct(){
        $this->model_user = new model_user();
    }
    //IMPORTANTE: Para la actialización del log in intentar usar una estructura parecida a la de Sing in
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
    public function register($username,$password,$email){
        $user = $this->model_user->crearusuario($username, $password,$email);
        if ($user != false) {
            // esto es temporal y puede quedar obsoleto
            //require __DIR__ . '/../index_home.php';
            //INICIO DE SESSIÓN (No añadir contraseña al inicion de sesión, por que no es seguro)
            $_SESSION["id"]=$user["id"];
            $_SESSION["nombre"]=$user["nombre"];
            $_SESSION["email"]=$user["email"];
            $_SESSION["rol"]=$user["rol"];
            header("Location:indexHome.php?action=home");
        }else{
            echo "Registro fallido, el usuario ya existe.";
            header("Location: indexHome.php?action=sing_fallido");
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
