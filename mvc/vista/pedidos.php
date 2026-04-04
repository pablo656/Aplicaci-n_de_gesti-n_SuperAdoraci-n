<main>
    <h1>Nuestro menú</h1>
    <h2>Elige lo que quieres pedir</h2>

    <?php
        $mostrar_ok = (!empty($pedido_ok)) || (!empty($_SESSION['pedido_ok']));
        if (!empty($_SESSION['pedido_ok'])) unset($_SESSION['pedido_ok']);

        $pedidos_cookie = isset($_COOKIE['pedidos']) ? json_decode($_COOKIE['pedidos'], true) : [];
        $cantidades_cookie = [];
        foreach ($pedidos_cookie as $p) {
            $cantidades_cookie[(int)$p['id']] = (int)$p['cantidad'];
        }
    ?>
    <?php if ($mostrar_ok): ?>
        <p class="aviso-ok">¡Pedido realizado correctamente!</p>
    <?php endif; ?>

    <?php
        $errores_mostrar = !empty($errores) ? $errores : (!empty($_SESSION['pedido_errores']) ? $_SESSION['pedido_errores'] : []);
        if (!empty($_SESSION['pedido_errores'])) unset($_SESSION['pedido_errores']);
    ?>
    <?php if (!empty($errores_mostrar)): ?>
        <?php foreach ($errores_mostrar as $error): ?>
            <p class="aviso-error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="lista-comidas">
        <?php if (!empty($comidas)): ?>
            <?php foreach ($comidas as $comida): ?>
                <div class="item-comida">
                    <?php if (!empty($comida['url_imagen'])): ?>
                        <img src="<?= htmlspecialchars($comida['url_imagen']) ?>" alt="<?= htmlspecialchars($comida['nombre']) ?>">
                    <?php else: ?>
                        <img src="imagenes/placeholder.png" alt="<?= htmlspecialchars($comida['nombre']) ?>">
                    <?php endif; ?>
                    <div class="item-info">
                        <p class="nombre"><?= htmlspecialchars($comida['nombre']) ?></p>
                        <?php if (!empty($comida['descripcion'])): ?>
                            <p class="descripcion"><?= htmlspecialchars($comida['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="item-accion" id="accion-<?= (int)$comida['id'] ?>" data-nombre="<?= htmlspecialchars($comida['nombre'], ENT_QUOTES) ?>">
                        <p class="precio"><?= number_format($comida['precio'], 2) ?> €</p>
                        <?php $qty = $cantidades_cookie[(int)$comida['id']] ?? 0; ?>
                        <?php if ($qty > 0): ?>
                            <div class="contador-inline">
                                <button type="button" class="btn-menos-inline" onclick="ajustarPedido(<?= (int)$comida['id'] ?>, -1)">&#8722;</button>
                                <span id="cantidad-pedido-<?= (int)$comida['id'] ?>"><?= $qty ?></span>
                                <button type="button" class="btn-mas-inline" onclick="ajustarPedido(<?= (int)$comida['id'] ?>, 1)">&#43;</button>
                            </div>
                        <?php else: ?>
                            <button
                                class="btn-pedir"
                                onclick="abrirModal(<?= (int)$comida['id'] ?>, <?= htmlspecialchars(json_encode($comida['nombre'])) ?>)"
                            >&#43;</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="vacio">No hay platos disponibles en este momento.</p>
        <?php endif; ?>
    </div>
</main>

<!-- Modal de pedido -->
<div class="modal-overlay" id="modalPedir">
    <div class="modal">
        <button class="btn-cerrar-modal" onclick="cerrarModal()" aria-label="Cerrar">&times;</button>
        <h3 id="modalTitulo">Pedir</h3>
        <form method="POST" action="IndexPedidos.php?action=crear">
            <input type="hidden" name="id_comida" id="modalIdComida">

            <label for="mensaje">Mensaje (opcional)</label>
            <textarea name="mensaje" id="mensaje" placeholder="Alergias, preferencias especiales..."></textarea>

            <div class="modal-pie">
                <div class="contador-modal">
                    <span class="cantidad-label">Cantidad</span>
                    <button type="button" class="btn-menos" onclick="cambiarCantidad(-1)">&#8722;</button>
                    <span id="cantidadDisplay">1</span>
                    <button type="button" class="btn-mas" onclick="cambiarCantidad(1)">&#43;</button>
                </div>
                <input type="hidden" name="cantidad" id="cantidad" value="1">
                <button type="submit" class="btn-confirmar">Confirmar pedido</button>
            </div>
        </form>
    </div>
</div>

<script src="js/pedidos.js">
</script>
