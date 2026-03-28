<?php
require_once __DIR__ . '/../model/model_reservas.php';

// Controlador que gestiona las reservas (carrito) del usuario
class Controller_reservas{
    private $model_reservas;

    // Instancia el modelo de reservas
    public function __construct(){
        $this->model_reservas = new model_reservas();
    }

    // Obtiene todas las reservas y carga la vista
    public function consultar_reservas(){
        $reservas = $this->model_reservas->consultar_reservas();
        require_once __DIR__ . "/../vista/reservas.php";

    }

    // Crea una nueva reserva para un usuario y producto concretos con la cantidad indicada
    public function crear_reserva($id_usuario, $id_producto, $cantidad){
        $reservas_crear =$this->model_reservas->crear_reserva($id_usuario, $id_producto, $cantidad);
        require_once __DIR__ . "/../vista/reservas.php";

    }

    // Elimina una reserva concreta por su ID
    public function eliminar_reserva($id_reserva){
        $eliminar_reserva =$this->model_reservas->eliminar_reserva($id_reserva);
        require_once __DIR__ . "/../vista/reservas.php";

    }

    // Elimina todas las reservas de un usuario (útil al confirmar un pedido o al cerrar sesión)
    public function eliminar_reserva_por_id_usuario($id_usuario){
        $eliminar_reserva_id = $this->model_reservas->eliminar_reserva_por_id_usuario($id_usuario);
        require_once __DIR__ . "/../vista/reservas.php";

    }

}

?>