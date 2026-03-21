
<div class="contenido">
    <aside>
        <ul>
            <li><a>Bebidas</a></li>
            <li><a>Comida</a></li>
            <li><a>Productos para Mascotas</a></li>
            <li><a>Productos de limpieza</a></li>
            <li><a>Material de oficina</a></li>
        </ul>
    </aside>
    <main>
        <h1>Catálogo</h1>
        <h2>Todos los productos</h2>
        <hr>

        <div class="cuadricula-productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="producto">
                        <img src="<?php echo $producto['url_imagen'] ?>" alt="<?php echo $producto['nombre'] ?>" loading="lazy">
                        <div class="info-producto">
                            <p class="nombre"><?php echo $producto['nombre'] ?></p>
                            <?php if ($producto['porcentaje_descuento'] != 0): ?>
                                <div class="descuento">
                                    <p class="sin_descuento"><?php echo $producto['precio'] ?>&euro;</p>
                                    <p class="precio">
                                        <?php echo $producto['precio'] - ($producto['precio'] * ($producto['porcentaje_descuento'] / 100)) ?>
                                        &euro;<?php echo $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="precio">
                                    <?php echo $producto['precio'] ?>&euro;<?php echo $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                </p>
                            <?php endif; ?>
                            <button class="reservar">Reservar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="vacio">No hay productos disponibles</p>
            <?php endif; ?>
        </div>

    </main>
</div>
