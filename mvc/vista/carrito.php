<?php if (!isset($pedidos_carrito)) $pedidos_carrito = []; ?>
<?php
    $reservas_cookie = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
    $cantidades = [];
    foreach($reservas_cookie as $item) {
        $cantidades[$item['id']] = $item['cantidad'];
    }
    $pedidos_cookie_raw = isset($_COOKIE["pedidos"]) ? json_decode($_COOKIE["pedidos"], true) : [];
    $cantidades_pedidos = [];
    foreach ($pedidos_cookie_raw as $item) {
        $cantidades_pedidos[$item['id']] = $item['cantidad'];
    }
    $total = 0;
    foreach($reservas as $reserva){
        $id = (int)$reserva['id'];
        $cantidad = isset($cantidades[$id]) ? $cantidades[$id] : 1;
        $precio_final = $reserva['precio'];
        if (!empty($reserva['porcentaje_descuento']) && $reserva['porcentaje_descuento'] > 0) {
            $precio_final = $reserva['precio'] * (1 - ($reserva['porcentaje_descuento'] / 100));
        }
        $total += $precio_final * $cantidad;
    }
    foreach($pedidos_carrito as $pedido){
        $pid = (int)$pedido['id'];
        $pcantidad = $cantidades_pedidos[$pid] ?? 1;
        $total += $pedido['precio'] * $pcantidad;
    }
?>

<h1>Carrito</h1>

<div class="carrito-layout">
    <main>
        <h2>Reservas</h2>
        <hr>
        <div id="contenedor-reservas" class="lista-reservas">
        <?php if(!empty($reservas)): ?>
            <?php foreach($reservas as $reserva):
                $id = (int)$reserva['id'];
                $cantidadActual = isset($cantidades[$id]) ? $cantidades[$id] : 1;
                $imagen = (!empty($reserva['url_imagen'])) ? $reserva['url_imagen'] : 'imagenes/placeholder.png';
                $precio_final = $reserva['precio'];
                if (!empty($reserva['porcentaje_descuento']) && $reserva['porcentaje_descuento'] > 0) {
                    $precio_final = $reserva['precio'] * (1 - ($reserva['porcentaje_descuento'] / 100));
                }
            ?>
                <div class="item-reserva" id="reserva-<?= $id ?>">
                    <img src="<?= htmlspecialchars($imagen) ?>" alt="<?= htmlspecialchars($reserva['nombre']) ?>">
                    <div class="item-info">
                        <p class="nombre"><?= htmlspecialchars($reserva['nombre']) ?></p>
                        <p class="descripcion">
                            <?= !empty($reserva['subcategoria']) ? htmlspecialchars($reserva['subcategoria']) : htmlspecialchars($reserva['categoria']) ?>
                        </p>
                    </div>
                    <div class="item-accion">
                        <p class="precio" id="precio-<?= $id ?>"><?= number_format($precio_final, 2) ?> €</p>
                        <div class="contador">
                            <button id="borrar-<?= $id ?>" class="btn-eliminar" onclick="borrarReserva(<?= $id ?>)">
                                <i class="fi fi-sr-trash"></i> Eliminar
                            </button>
                            <div class="btn-grupo">
                                <button id="restar-<?= $id ?>" class="btn-cantidad btn-restar" onclick="cambiarCantidad(<?= $id ?>, -1, <?= $precio_final ?>)">−</button>
                                <span class="cantidad" id="cantidad-<?= $id ?>"><?= $cantidadActual ?></span>
                                <button class="btn-cantidad btn-sumar" onclick="cambiarCantidad(<?= $id ?>, 1, <?= $precio_final ?>)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="vacio">No hay reservas</p>
        <?php endif; ?>
        </div>

        <h2>Pedidos</h2>
        <hr>
        <div class="lista-reservas">
            <?php if (!empty($pedidos_carrito)): ?>
                <?php foreach ($pedidos_carrito as $pedido):
                $pid = (int)$pedido['id'];
                $pcantidad = $cantidades_pedidos[$pid] ?? 1;
                $imagen_p = !empty($pedido['url_imagen']) ? $pedido['url_imagen'] : 'imagenes/placeholder.png';
            ?>
                <div class="item-reserva" id="pedido-<?= $pid ?>">
                    <img src="<?= htmlspecialchars($imagen_p) ?>" alt="<?= htmlspecialchars($pedido['nombre']) ?>">
                    <div class="item-info">
                        <p class="nombre"><?= htmlspecialchars($pedido['nombre']) ?></p>
                        <?php if (!empty($pedido['descripcion'])): ?>
                            <p class="descripcion"><?= htmlspecialchars($pedido['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="item-accion">
                        <p class="precio"><?= number_format($pedido['precio'], 2) ?> €</p>
                        <div class="contador">
                            <button class="btn-eliminar" onclick="borrarPedido(<?= $pid ?>)">
                                <i class="fi fi-sr-trash"></i> Eliminar
                            </button>
                            <div class="btn-grupo">
                                <button id="restar-p-<?= $pid ?>" class="btn-cantidad btn-restar" onclick="cambiarCantidadPedido(<?= $pid ?>, -1, <?= $pedido['precio'] ?>)">−</button>
                                <span class="cantidad" id="cantidad-p-<?= $pid ?>"><?= $pcantidad ?></span>
                                <button class="btn-cantidad btn-sumar" onclick="cambiarCantidadPedido(<?= $pid ?>, 1, <?= $pedido['precio'] ?>)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <p class="vacio">No hay pedidos.</p>
            <?php endif; ?>
        </div>
    </main>

    <aside class="resumen">
        <p class="resumen-label">Resumen</p>
        <div class="resumen-total">
            <span>Total</span>
            <span id="contenedor_precio"><?= number_format($total, 2) ?> €</span>
        </div>
        <?php if (!empty($reservas) || !empty($pedidos_carrito)): ?>
            <button class="btn-confirmar">Confirmar reserva</button>
        <?php else: ?>
            <p class="sin-items">Aún no hay reservas ni pedidos.</p>
        <?php endif; ?>
    </aside>
</div>

<script src="js/carrito.js" defer></script>
