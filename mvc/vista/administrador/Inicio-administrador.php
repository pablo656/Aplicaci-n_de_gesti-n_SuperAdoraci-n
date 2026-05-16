<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexInicio-administrador.php");
    exit();
}
?>

<?php
// 1. Verificación de seguridad (Sesión y Roles)
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["rol"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
} 

// Verificación de permisos: solo administradores pueden gestionar esta sección
if ($_SESSION["rol"] != "administrador"&& $_SESSION["rol"] != "dueno") { ?>
    <div class="acceso-denegado">
        <h1 class="sin-acceso">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar la base de productos.</p>
    </div>
<?php
} else { ?>
<main>
<div class="seccion-admin">
    <div class="header-admin">
        <div class="header-textos">
            <h1>Administración de productos más vendidos</h1>
            <p class="subtitulo">Gestiona los productos destacados que aparecen en el inicio.</p>
        </div>
        
    </div>

    <hr class="divisor-admin">

    <!-- Contenedor principal con la cuadrícula -->
    <div class="productos-inicio cuadricula-productos">
        <?php
        $max_productos = 10;
        $productos_actuales = is_array($productos) ? count($productos) : 0;
        $huecos_vacios = max(0, $max_productos - $productos_actuales);

        // 2. Renderizado de productos existentes (Estilo image_306e35.png)
        if(!empty($productos)):
            foreach($productos as $p): 
                $precio_orig = $p['precio'];
                $desc = ($p['porcentaje_descuento'] != 0);
                $precio_f = $desc ? $precio_orig * (1 - ($p['porcentaje_descuento'] / 100)) : $precio_orig;
                ?>
                
                <div class="producto" id="prod-<?= htmlspecialchars($p['id']) ?>">
                    <div class="contenedor-img">
                        <img src="../<?= htmlspecialchars($p['url_imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>" loading="lazy">
                    </div>
                    
                    <div class="info-producto">
                        <p class="nombre"><?= htmlspecialchars($p['nombre']) ?></p>
                        <span class="stock">Stock: <?= htmlspecialchars($p['stock']) ?><?= $p['precio_por_peso'] == 1 ? ' Kg' : '' ?></span>

                        <div class="descuento">
                            <?php if ($desc): ?>
                                <p class="sin_descuento"><?= number_format($precio_orig, 2) ?>&euro;</p>
                            <?php endif; ?>
                            <p class="precio"><?= number_format($precio_f, 2) ?>&euro;<?= $p['precio_por_peso'] ? '/Kg' : '' ?></p>
                        </div>
                    </div>

                    <div class="acciones-tarjeta">
                        <form method="post" action="?action=quitar" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                            <button type="submit" class="btn-eliminar">
                                <i class="fi fi-sr-trash"></i> Quitar producto
                            </button>
                        </form>
                    </div>
                </div>

            <?php endforeach;
        endif;

        // 3. Renderizado de huecos vacíos (Misma forma que el producto, estilo image_30795c.png)
        for($i = 0; $i < $huecos_vacios; $i++): ?>
                <a  class="btn-hueco-vacio" href="IndexProducto-administrador.php">
                    <div class="producto_vacio">
                        <div class="contenido_interno">
                            <div class="simbolo">+</div>
                            <div class="titulo">Añadir productos</div>
                        </div>
                    </div>
                </a>
        <?php endfor; ?>
    </div>
</div>
<div>

</div>
<!-- Alerta para cuando no hay JS activo -->
<noscript>
    <div class="alerta-no-js">
        <div class="alerta-contenido">
            <i class="fi fi-sr-exclamation"></i>
            <strong>JavaScript Desactivado</strong>
            <p>Es necesario activar JavaScript para gestionar los productos correctamente.</p>
        </div>
    </div>
</noscript>

<script src="js/usuarios-administrador.js"></script>
</main>
<?php } // Fin del bloque de administrador ?>