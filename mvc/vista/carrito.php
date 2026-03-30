<h1>Carrito</h1>
<h2>Reservas</h2>
<hr>

<div class="carrito-layout">
    <main>
        <div id="contenedor-reservas" class="lista-reservas">
        <?php
            $reservas_cookie = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
            $cantidades = [];
            foreach($reservas_cookie as $item) {
                $cantidades[$item['id']] = $item['cantidad'];
               
            }
            if(!empty($reservas)): 
                foreach($reservas as $reserva): 
                    
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
    </main>

    <aside class="resumen">
        <?php 
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
        ?>
        <p class="resumen-label">Resumen</p>
        <div class="resumen-total">
            <span>Total</span>
            <span id="contenedor_precio"><?= number_format($total, 2) ?> €</span>
        </div>
        <button class="btn-confirmar">Confirmar reserva</button>
    </aside>
</div>

<script src="js/carrito.js" defer></script>