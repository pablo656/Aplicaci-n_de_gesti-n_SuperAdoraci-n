<?php
require_once __DIR__ . '/../model/model_pedidos.php';
require_once __DIR__ . '/../model/model_comida.php';

// Controlador que gestiona los pedidos de comida
class Controller_pedidos {
    private $model_pedidos;
    private $model_comida;

    public function __construct() {
        $this->model_pedidos = new model_pedidos();
        $this->model_comida = new model_comida();
    }

    // Muestra el catálogo de comidas disponibles (vista de usuario)
    public function mostrar_catalogo() {
        $model_comida = new model_comida();
        $comidas = $model_comida->mostrar_comidas();
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Muestra los pedidos de un usuario concreto
    public function mostrar_pedidos_user($id_usuario) {
        $pedidos = $this->model_pedidos->mostrar_pedidos_user($id_usuario);
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Muestra todos los pedidos ordenados por fecha (panel de administración)
    public function mostrar_pedidos_order() {
        $pedidos = $this->model_pedidos->mostrar_pedidos_order();
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Busca pedidos por nombre
    public function mostrar_pedido_nombre($nombre) {
        $pedidos = $this->model_pedidos->mostrar_pedido_nombre($nombre);
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Devuelve el número de pedidos pendientes (realizado = 0)
    public function contar_pedidos_pendientes() {
        return $this->model_pedidos->contar_pedidos_pendientes();
    }

    // Muestra pedidos filtrados por estado: 0 = pendiente, 1 = realizado
    public function mostrar_pedidos_por_estado($realizado) {
        $pedidos = $this->model_pedidos->mostrar_pedidos_por_estado($realizado);
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Valida los datos y crea un nuevo pedido
    public function crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje) {
        $errores = [];

        if (empty($id_usuario) || !is_numeric($id_usuario)) {
            $errores[] = "El usuario no es válido";
        }
        if (empty($id_comida) || !is_numeric($id_comida)) {
            $errores[] = "La comida seleccionada no es válida";
        }
        if (empty($cantidad) || !is_numeric($cantidad) || $cantidad < 1) {
            $errores[] = "La cantidad debe ser un número mayor que 0";
        }

        $pedido_ok = false;
        if (empty($errores)) {
            $this->model_pedidos->crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje);
            $pedido_ok = true;
        }

        $comidas = $this->model_comida->mostrar_comidas();
        require __DIR__ . "/../vista/pedidos.php";
    }
}
