<?php
require_once __DIR__ . '/../model/model_reservas.php';
class Controller_reservas{
    private $model_reservas;
    public function __construct(){
        $this->model_reservas = new model_reservas();
    }

    public function consultar_reservas(){
        $reservas = $this->model_reservas->consultar_reservas();
        require_once __DIR__ . "/../view/view_reservas.php";

    }
    public function crear_reserva($id_usuario, $id_producto, $cantidad){
        $reservas_crear =$this->model_reservas->crear_reserva($id_usuario, $id_producto, $cantidad);
        require_once __DIR__ . "/../view/view_reservas.php";

    }
    public function eliminar_reserva($id_reserva){
        $eliminar_reserva =$this->model_reservas->eliminar_reserva($id_reserva);
        require_once __DIR__ . "/../view/view_reservas.php";

    }
    public function eliminar_reserva_por_id_usuario($id_usuario){
        $eliminar_reserva_id = $this->model_reservas->eliminar_reserva_por_id_usuario($id_usuario);
        require_once __DIR__ . "/../view/view_reservas.php";

    }

}

?>