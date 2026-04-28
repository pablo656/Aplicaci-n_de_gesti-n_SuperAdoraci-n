<?php
require_once __DIR__ . '/../model/model_comida.php';

class Controller_comidas {
    private $model;

    public function __construct() {
        $this->model = new model_comida();
    }

    public function listar() {
        $comidas = $this->model->mostrar_comidas();
        require __DIR__ . '/../vista/administrador/comidas-administrador.php';
    }

    public function insertar($datos, $imagen) {
        $this->model->añadir_comida(
            trim($datos['nombre']),
            trim($datos['descripcion']),
            (float)$datos['precio'],
            isset($datos['disponible']) ? 1 : 0,
            $imagen
        );
    }

    public function modificar($id, $datos, $imagen) {
        $img = (!empty($imagen['name'])) ? $imagen : null;
        $this->model->update_comida(
            (int)$id,
            trim($datos['nombre']),
            trim($datos['descripcion']),
            (float)$datos['precio'],
            isset($datos['disponible']) ? 1 : 0,
            $img
        );
    }

    public function eliminar($id) {
        $this->model->borrar_comida((int)$id);
    }
}
