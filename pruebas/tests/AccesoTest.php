<?php
require_once __DIR__ . '/../../vendor/autoload.php';

@session_start();

$root = dirname(__DIR__, 2); 

require_once $root . '/mvc/bd/bd.php';                
require_once $root . '/mvc/model/model_user.php';  
require_once $root . '/mvc/controller/controller_user.php'; 

use PHPUnit\Framework\TestCase;

class AccesoTest extends TestCase 
{
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new Controller_user();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_funcion_loginar() 
    {
        $_SESSION = [];
        $user = "Alejandro"; 
        $pass = "12345678";

        
        @$this->controller->loginar($user, $pass);

        $this->assertEquals($user, $_SESSION["nombre"] ?? null, "El login falló: Comprueba que el usuario existe en la DB.");
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_funcion_loginar_admin() 
    {
        $_SESSION = [];
        $admin_user = "Alejandro"; 
        $admin_pass = "12345678"; 

        @$this->controller->loginar_admin($admin_user, $admin_pass);

        
        if (!isset($_SESSION["id"])) {
             // Esto te ayudará a ver si al menos entró algo
             var_dump("Error de login para admin. Sesión actual:", $_SESSION);
        }

        $this->assertNotNull($_SESSION["id"] ?? null, "El login de admin falló.");
    }
}