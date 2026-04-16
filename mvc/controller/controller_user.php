<?php
require_once __DIR__ . "/../model/model_user.php";
<<<<<<< HEAD
=======
require_once __DIR__ . "/../helpers/Mailer.php";
>>>>>>> 79220663df93dbe286a9ef08d7ed2817a02f44a9

// Controlador que gestiona el registro, login y navegación del usuario
class Controller_user{
    private $model_user;

    // Instancia el modelo de usuario
    public function __construct(){
        $this->model_user = new model_user();
    }

    //IMPORTANTE: Para la actualización del log in intentar usar una estructura parecida a la de Sing in
    // Verifica credenciales; si son correctas inicia sesión, si no redirige al formulario con error
    public function loginar($username, $password){
        $user= $this->model_user->iniciousuario($username, $password);
        if($user == false){
            echo "Usuario o contraseña incorrecta";
            header("Location: indexHome.php?action=log_fallido");
        }else{
             //INICIO DE SESSIÓN (No añadir contraseña al inicion de sesión, por que no es seguro)
            $_SESSION["id"]=$user["id"];
            $_SESSION["nombre"]=$user["nombre"];
            $_SESSION["email"]=$user["email"];
            $_SESSION["rol"]=$user["rol"];
            header("Location:indexHome.php?action=home");
        }
    }

<<<<<<< HEAD
    // Crea el usuario; si tiene éxito inicia sesión directamente, si ya existe redirige con error
    public function register($username,$password,$email){
        $user = $this->model_user->crearusuario($username, $password,$email);
        if ($user != false) {
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
=======
    // Envía email de verificación; no crea el usuario hasta que confirme
    public function register($username, $password, $email) {
        if (empty($username) || empty($password) || empty($email)) {
            header("Location: indexHome.php?action=sing");
            return;
        }
        if ($this->model_user->crearusuario_existe($username, $email)) {
            header("Location: indexHome.php?action=sing_fallido");
            return;
        }
        $hash  = password_hash($password, PASSWORD_DEFAULT);
        $token = $this->model_user->guardar_verificacion($username, $email, $hash);
        if (!$token) {
            header("Location: indexHome.php?action=sing_fallido");
            return;
        }
        $link   = APP_URL . "/IndexHome.php?action=confirmar_email&token=" . $token;
        $asunto = "Confirma tu cuenta en SuperAdoracion";
        $cuerpo = "
            <p>Hola <strong>" . htmlspecialchars($username) . "</strong>,</p>
            <p>Haz clic en el siguiente enlace para activar tu cuenta:</p>
            <p><a href=\"$link\">$link</a></p>
            <p>El enlace caduca en 24 horas.</p>
        ";
        $mailer = new Mailer();
        $mailer->enviar($email, $asunto, $cuerpo);
        require("../vista/email_enviado.php");
    }

    // Confirma el token del email y crea el usuario real
    public function confirmar_email($token) {
        $user = $this->model_user->confirmar_verificacion($token);
        if (!$user) {
            $_SESSION["confirm_error"] = "El enlace no es válido o ha caducado.";
            header("Location: indexHome.php?action=sing");
            return;
        }
        $_SESSION["id"]     = $user["id"];
        $_SESSION["nombre"] = $user["nombre"];
        $_SESSION["email"]  = $user["email"];
        $_SESSION["rol"]    = $user["rol"];
        header("Location: indexHome.php?action=home");
>>>>>>> 79220663df93dbe286a9ef08d7ed2817a02f44a9
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
        require("../vista/perfil.php");
    }

    public function actualizar_nombre() {
        if (!isset($_SESSION["id"])) {
            header("Location: indexHome.php?action=log");
            return;
        }
        $nuevo_nombre = trim($_POST["nombre"] ?? "");
        if (empty($nuevo_nombre)) {
            header("Location: indexHome.php?action=perfil&error=nombre_vacio");
            return;
        }
        $resultado = $this->model_user->actualizar_nombre($_SESSION["id"], $nuevo_nombre);
        if ($resultado === "nombre_duplicado") {
            header("Location: indexHome.php?action=perfil&error=nombre_duplicado");
            return;
        }
        if ($resultado === false) {
            header("Location: indexHome.php?action=perfil&error=error_guardado");
            return;
        }
        $_SESSION["nombre"] = $nuevo_nombre;
        header("Location: indexHome.php?action=perfil&ok=1");
    }
}
?>
