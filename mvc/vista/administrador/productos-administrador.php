<?php  $action=$_GET["action"] ?? "list";
        $subcategoria = $_GET["subcategoria"] ?? null;
         $categorias=["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
         $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
        ];?>
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
                        <form method="post" action="IndexProducto-administrador.php">
                            <input type="hidden" value="<?= $producto["id"] ?>" name="id">
                            <button type="submit" class="boton_modificar" name="abrir_modal">Modificar</button>
                        </form>
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
<script src="js/productos-administrador.js"></script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["abrir_modal"])):
    $id = $_POST["id"];
    $producto = null;
    foreach ($productos as $posible_producto) {
        if ($posible_producto["id"] == $id) {
            $producto = $posible_producto;
            break;
        }
    }

    if ($producto): ?>
        <div id="modal" class="modal-overlay" style="display:none"> <div class="modal">
                <button class="modal-cerrar" onclick="cerrarModal()">x</button>
                
                <form method="post" action="?action=modificar" id="form-modal" enctype="multipart/form-data">
                    <input type="hidden" value="<?= htmlspecialchars($producto["id"]) ?>" name="id">
                    
                    <label for="nombre">Nombre: </label>
                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($producto["nombre"]) ?>" required>
                    
                    <label for="stock">Stock: </label>
                    <input type="number" step="0.1" name="stock" id="stock" value="<?= $producto["stock"] ?>">
                    
                    <label for="precio">Precio: </label>
                    <input type="number" step="0.01" min="0" name="precio" id="precio" value="<?= $producto["precio"] ?>">
                    
                    <label for="descuento">Descuento: </label>
                    <div class="input-porcentaje">
                        <input type="number" name="descuento" id="descuento" min="0" max="99" value="<?= $producto["porcentaje_descuento"] ?>">
                        <span class="simbolo">%</span>
                    </div>

                    <div id="precio_descontado_container">
                        Precio final: <span id="precio_descontado">0.00</span>€
                    </div>

                    <div class="checkbox-container">
                        <input type="checkbox" name="precio_por_peso" id="precio_por_peso" value="1" <?= $producto["precio_por_peso"] ? 'checked' : '' ?>> 
                        <label for="precio_por_peso">Precio por peso</label>
                    </div>

                    <label for="categoria">Categoría: </label>
                    <select name="categoria" id="categoria" onchange="selectSubcategotia()">
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($producto['categoria'] == $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="subcategorias">Subcategoría: </label>
                    <select id="subcategorias" name="subcategoria"></select>

                    <label>Imagen del producto:</label>
                    <div class="contenedor-preview">
                        <img id="preview" src="../<?= $producto['url_imagen'] ?>" alt="Vista previa">
                    </div>
                    
                    <input type="file" name="nueva_imagen" id="input_imagen" accept="image/*">
                    
                    <input type="submit" name="modificar" value="Actualizar">
                </form>
            </div>
        </div>

        <script>
            // Esta llamada ahora es segura
            abrirModal(
                <?= json_encode($producto['id']) ?>, 
                <?= json_encode($producto['categoria']) ?>, 
                <?= json_encode($producto['subcategoria']) ?>
            );
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    <?php endif; 
endif; ?>
<?php } ?>
