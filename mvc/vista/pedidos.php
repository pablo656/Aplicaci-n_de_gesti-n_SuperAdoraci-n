<main>
    <h1>Nuestro menú</h1>
    <h2>Elige lo que quieres pedir</h2>

    <?php if (isset($pedido_ok) && $pedido_ok): ?>
        <p class="aviso-ok">¡Pedido realizado correctamente!</p>
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
                    <div class="item-accion">
                        <p class="precio"><?= number_format($comida['precio'], 2) ?> €</p>
                        <button
                            class="btn-pedir"
                            onclick="abrirModal(<?= (int)$comida['id'] ?>, <?= htmlspecialchars(json_encode($comida['nombre'])) ?>)"
                        >&#43;</button>
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

<script>
function abrirModal(idComida, nombre) {
    document.getElementById('modalIdComida').value = idComida;
    document.getElementById('modalTitulo').textContent = 'Pedir: ' + nombre;
    document.getElementById('cantidad').value = 1;
    document.getElementById('cantidadDisplay').textContent = 1;
    document.getElementById('mensaje').value = '';
    document.querySelector('.btn-menos').style.visibility = 'hidden';
    document.getElementById('modalPedir').classList.add('activo');
}

function cambiarCantidad(delta) {
    const input = document.getElementById('cantidad');
    const display = document.getElementById('cantidadDisplay');
    const nuevo = Math.max(1, parseInt(input.value) + delta);
    input.value = nuevo;
    display.textContent = nuevo;
    document.querySelector('.btn-menos').style.visibility = nuevo <= 1 ? 'hidden' : 'visible';
}

function cerrarModal() {
    document.getElementById('modalPedir').classList.remove('activo');
}

document.getElementById('modalPedir').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
