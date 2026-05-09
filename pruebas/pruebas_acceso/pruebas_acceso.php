<?php
// Importas las herramientas de testing (esto varía según el framework)
use Tests\TestCase; 

class LoginTest extends TestCase 
{
    /**
     * Prueba que un usuario con credenciales válidas entra al sistema.
     */
    public function test_usuario_puede_iniciar_sesion() 
    {
        $this->visit('http://tu-web.com/mvc/vista/IndexHome.php?action=log')
             ->type('Alejandro', 'user') 
             ->type('12345678', 'pass')
             ->press('Entrar')
             ->seePageIs('/mvc/vista/IndexAdmin.php')
             ->see('Bienvenido Administrador');
    }

    /**
     * Prueba la seguridad: el sistema debe rechazar accesos no autorizados.
     */
    public function test_usuario_no_autorizado_no_entra_a_admin() 
    {
        $this->visit('http://tu-web.com/mvc/vista/IndexPedidos.php')
             ->seePageIs('/mvc/vista/IndexHome.php?action=log');
    }
}