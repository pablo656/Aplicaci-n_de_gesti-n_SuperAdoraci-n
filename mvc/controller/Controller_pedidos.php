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
        $comidas = $model_comida->mostrar_comidas(true);
        require __DIR__ . "/../vista/pedidos.php";
    }

    // Muestra los pedidos de un usuario concreto
    public function mostrar_pedidos_user($id_usuario) {
        $pedidos = $this->model_pedidos->mostrar_pedidos_user($id_usuario);
        return $pedidos;
        //require __DIR__ . "/../vista/perfil.php";
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

    // Devuelve las comidas del cookie de pedidos (para mostrar en el carrito)
    public function buscar_pedidos_cookie($pedidos_cookie) {
        return $this->model_comida->buscar_pedidos_cookie($pedidos_cookie);
    }

    // Guarda o suma cantidad de una comida en la cookie del carrito
    public function guardar_en_cookie($id_comida, $cantidad, $mensaje = "", $fecha_entrega = "") {
        $pedidos = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
        $encontrado = false;
        foreach ($pedidos as &$pedido) {
            if ($pedido["id"] == $id_comida) {
                $pedido["cantidad"]      = max(1, (int)$pedido["cantidad"] + (int)$cantidad);
                $pedido["mensaje"]       = $mensaje;
                $pedido["fecha_entrega"] = $fecha_entrega;
                $encontrado = true;
                break;
            }
        }
        unset($pedido);
        if (!$encontrado) {
            $pedidos[] = ["id" => $id_comida, "cantidad" => (int)$cantidad, "mensaje" => $mensaje, "fecha_entrega" => $fecha_entrega];
        }
        setcookie("pedidos", json_encode($pedidos), time() + (60 * 60 * 24), "/");
    }

    // Muestra todos los pedidos agrupados por usuario (panel de administración)
    public function consultar_pedidos_admin() {
        $pedidos = $this->model_pedidos->consultar_pedidos_admin();
        require __DIR__ . "/../vista/administrador/pedidos-administrador.php";
    }

    // Elimina un pedido por su ID
    public function eliminar_pedido($id) {
        $this->model_pedidos->eliminar_pedido($id);
    }

    // Marca un pedido como realizado
    public function marcar_realizado($id) {
        $this->model_pedidos->marcar_realizado($id);
    }

    // Valida los datos y crea un nuevo pedido
    public function crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje, $fecha_entrega = null) {
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

        if (!empty($errores)) {
            $_SESSION['pedido_errores'] = $errores;
            return false;
        }

        $ok = $this->model_pedidos->crear_pedido($id_usuario, $id_comida, $cantidad, $mensaje, $fecha_entrega ?: null);
        if (!$ok) {
            $_SESSION['pedido_errores'] = ["No se pudo guardar el pedido en la base de datos."];
            return false;
        }
        return true;
    }
}
