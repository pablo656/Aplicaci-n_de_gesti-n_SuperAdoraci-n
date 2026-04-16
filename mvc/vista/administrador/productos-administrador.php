<?php 
// 1. Verificación de seguridad y roles
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
} 

if ($_SESSION["rol"] != "administrador" && $_SESSION["rol"] != "dueño") { ?>
    <div style="text-align: center; padding: 50px;">
        <h1 class="sin-acceso" style="color: #e31b23;">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar el catálogo.</p>
    </div>
<?php
} else { ?>

<main class="main-admin">
    <h1>Administración de catálogo</h1>
    <p class="subtitulo">Gestiona el stock, precios y disponibilidad de los productos.</p>
    <hr>
    <div class="cuadricula-productos">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $producto): 
                // Calcular precio final si hay descuento
                $precio_original = $producto['precio'];
                $tiene_descuento = ($producto['porcentaje_descuento'] != 0);
                $precio_final = $tiene_descuento 
                    ? $precio_original - ($precio_original * ($producto['porcentaje_descuento'] / 100)) 
                    : $precio_original;
            ?>
                <div class="producto" id="prod-<?= htmlspecialchars($producto['id']) ?>">
                    <div class="contenedor-img">
                        <img src="../<?= htmlspecialchars($producto['url_imagen']) ?>" 
                             alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                             loading="lazy">
                    </div>
                    
                    <div class="info-producto">
                        <p class="nombre"><?= htmlspecialchars($producto['nombre']) ?></p>
                        
                        <p class="stock">
                            Stock: <?= htmlspecialchars($producto['stock']) ?><?= $producto['precio_por_peso'] == 1 ? ' Kg' : '' ?>
                        </p>

                        <div class="descuento">
                            <?php if ($tiene_descuento): ?>
                                <p class="sin_descuento"><?= number_format($precio_original, 2) ?>&euro;</p>
                            <?php endif; ?>
                            <p class="precio">
                                <?= number_format($precio_final, 2) ?>&euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                            </p>
                        </div>

                        <form method="post" action="?action=delete" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                            <button type="submit" name="eliminar" class="btn-eliminar">
                                <i class="fi fi-sr-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="vacio-contenedor">
                <p class="vacio">No hay productos disponibles en el catálogo actual.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php } ?>