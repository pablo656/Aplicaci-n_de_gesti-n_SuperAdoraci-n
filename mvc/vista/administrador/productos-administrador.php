<?php
    if(!isset($_SESSION["nombre"])&&!isset($_SESSION["email"])&&!isset($_SESSION["rol"])&&!isset($_SESSION["nombre"])){
        header("Location:../IndexHome.php?action=log");
    }else if($_SESSION["rol"]!="administrador"&&$_SESSION["rol"]!="dueño"){?>
    <h1 class="sin-acceso">Acceso no permitido a usuarios normales</h1>
    <?php
    }else{?>
        <h1>Administración de catalogo</h1>
         <div>
            <?php
             if (!empty($productos)): 
                foreach ($productos as $producto):
                    // Calcular precio final
                    $precio_final = $producto['precio'];
                    if($producto['porcentaje_descuento'] != 0){
                        $precio_final = $producto['precio'] - ($producto['precio'] * ($producto['porcentaje_descuento'] / 100));
                    }
            ?>
                <div class="producto" id="<?= htmlspecialchars($producto["id"]) ?>">
                    <img src="<?= htmlspecialchars($producto['url_imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" loading="lazy">
                    <div class="info-producto">
                        <p class="nombre"><?= htmlspecialchars($producto['nombre']) ?></p>
                        <?php if($producto["precio_por_peso"]==1):?>
                            <p class="stock">Stock: <?= htmlspecialchars($producto['stock']) ?>Kg</p>
                        <?php else:?>
                            <p class="stock">Stock: <?= htmlspecialchars($producto['stock']) ?></p>
                        <?php endif;?>
                        <p class="stock">Stock: <?= htmlspecialchars($producto['stock']) ?></p>
                        <?php if ($producto['porcentaje_descuento'] != 0): ?>
                            <div class="descuento">
                                <p class="sin_descuento"><?= $producto['precio'] ?>&euro;</p>
                                <p class="precio">
                                    <?= $precio_final ?>
                                    &euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <p class="precio">
                                <?= $producto['precio'] ?>&euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                            </p>
                            <p>
                                <form method="post" action="?action=add">
                                    <input type="hidden" name="id_producto" value="<?php $producto['id']?>">
                                    <button type="submit" name="eliminar"><i class="fi fi-sr-trash"></i>Eliminar</button>
                                </form>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="vacio">No hay productos disponibles</p>
        <?php endif; ?>
         </div>
      <?php  
    }
?>