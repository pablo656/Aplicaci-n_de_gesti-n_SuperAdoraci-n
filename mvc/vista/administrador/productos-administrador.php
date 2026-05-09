<?php  
// ── Configuración inicial y variables ──────────────────
$action = $_GET["action"] ?? "list";
$subcategoria = $_GET["subcategoria"] ?? null;

$categorias = ["Comida", "Bebidas", "Mascotas", "Papeleria_oficina", "Salud_bienestar"];
$subcategorias = [
    "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
    "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
    "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
    "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
    "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
    "Salud_bienestar"   => []
];

// 1. Verificación de seguridad y roles
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: IndexLog.php");
    exit();
}


if ($_SESSION["rol"] != "administrador" && $_SESSION["rol"] != "dueno") { ?>
    <div style="text-align: center; padding: 50px;">
        <h1 class="sin-acceso" style="color: #e31b23;">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar el catálogo.</p>
    </div>
<?php
} else { ?>

<div class="contenido">
    <aside>
        <div class="aside-titulo">Categorías</div>
        <ul>
            <?php foreach($categorias as $cat): ?>
                <li>
                    <a href="IndexProducto-administrador.php?action=<?= htmlspecialchars($cat) ?>"
                       class="<?= $action == $cat ? 'cat-activa' : '' ?>">
                        <?= htmlspecialchars(str_replace("_", " ", $cat)) ?>
                    </a>

                    <?php if($action == $cat && isset($subcategorias[$cat]) && !empty($subcategorias[$cat])): ?>
                        <ul class="subcategorias">
                            <?php foreach($subcategorias[$cat] as $sub): ?>
                                <li>
                                    <a href="IndexProducto-administrador.php?action=<?= htmlspecialchars($cat) ?>&subcategoria=<?= urlencode($sub) ?>"
                                       class="<?= $subcategoria == $sub ? 'sub-activa' : '' ?>">
                                        <?= htmlspecialchars($sub) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>

    <main class="main-admin">
        <div class="header-admin">
            <div>
                <h1>Administración de catálogo</h1>
                <p class="subtitulo">Gestiona el stock, precios y disponibilidad de los productos.</p>
            </div>
            <form method="post" >
            <button name="add" type="submit" class="btn-añadir">
                <i class="fi fi-sr-plus"></i> Añadir producto
            </button>
            </form>
        </div>
        
        <?php
            // Lógica de Breadcrumbs
            $breadcrums = "";
            $separador = "<span class='breadcrumb-separador'> > </span>";

            if($action != "list" && $action != "add"){
                $breadcrums = "<a href='IndexProducto-administrador.php' class='breadcrumb-enlace'>Todos los productos</a>";
                if($subcategoria != null){
                    $breadcrums .= $separador;
                    $breadcrums .= "<a href='IndexProducto-administrador.php?action=$action' class='breadcrumb-enlace'>" . str_replace('_', ' ', $action) . "</a>";
                    $breadcrums .= $separador;
                    $breadcrums .= "<span class='breadcrumb-texto'>$subcategoria</span>";
                }else{
                    $breadcrums .= $separador;
                    $breadcrums .= "<span class='breadcrumb-texto'>" . str_replace('_', ' ', $action) . "</span>";
                }
            }else{
                $breadcrums = "<span class='breadcrumb-texto'>Todos los productos</span>";
            }
            echo "<div class='breadcrumb-wrapper'>" . $breadcrums . "</div>";
        ?>
        <hr>

        <div class="cuadricula-productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $p): 
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
                            <p class="stock">Stock: <?= $p["precio_por_peso"] == 1 ? number_format($p['stock'], 1) . " Kg" : number_format($p['stock'], 0) ?> </p>

                            <div class="descuento">
                                <?php if ($desc): ?>
                                    <p class="sin_descuento"><?= number_format($precio_orig, 2) ?>&euro;</p>
                                <?php endif; ?>
                                <p class="precio"><?= number_format($precio_f, 2) ?>&euro;<?= $p['precio_por_peso'] ? '/Kg' : '' ?></p>
                            </div>
                            <?php if($p["inicio"]==0):?>
                            <form method="post" action="?action=inicio">
                                <input type="hidden" value="<?= $p["id"] ?>" name="id">
                                <button type="submit" class="boton_Inicio" name="aniadir_inicio">Poner producto en el inicio</button>
                            </form>
                            <?php else:?>
                                <form method="post" action="?action=quitar_inicio">
                                <input type="hidden" value="<?= $p["id"] ?>" name="id">
                                <button type="submit" class="boton_Inicio" name="quitar_inicio">Quitar el producto del inicio</button>
                            </form>
                            <?php endif;?>
                            <form method="post">
                                <input type="hidden" value="<?= $p["id"] ?>" name="id">
                                <button type="submit" class="boton_modificar" name="abrir_modal">Modificar</button>
                            </form>

                            <form method="post" action="?action=delete" onsubmit="return confirm('¿Estás seguro?');">
                                <input type="hidden" name="id_producto" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-eliminar"><i class="fi fi-sr-trash"></i> Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="vacio-contenedor"><p class="vacio">No hay productos en esta categoría.</p></div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="js/productos-administrador.js"></script>

<?php
// ── LÓGICA REUTILIZABLE DEL MODAL (AÑADIR / MODIFICAR) ──────────────────
$mostrar_modal = false;
$producto_modal = null;
$action_form = "?action=modificar";
$texto_boton = "Actualizar";

// Caso 1: Abrir para MODIFICAR (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["abrir_modal"])) {
    $id_buscado = $_POST["id"];
    foreach ($productos as $item) {
        if ($item["id"] == $id_buscado) {
            $producto_modal = $item;
            $mostrar_modal = true;
            break;
        }
    }
} 
// Caso 2: Abrir para AÑADIR (GET)
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    $mostrar_modal = true;
    $action_form = "?action=insertar";
    $texto_boton = "Guardar Producto";
    // Objeto vacío para el formulario
    $producto_modal = [
        "id" => "", "nombre" => "", "stock" => 0, "precio" => 0, 
        "porcentaje_descuento" => 0, "precio_por_peso" => 0, 
        "categoria" => "Comida", "subcategoria" => "", "url_imagen" => "imagenes/productos/default.jpg"
    ];
}

if ($mostrar_modal && $producto_modal): ?>
    <div id="modal" class="modal-overlay" style="display:flex"> 
        <div class="modal">
            <button class="modal-cerrar" onclick="window.location.href='IndexProducto-administrador.php'">x</button>
            
            <form method="post" action="<?= $action_form ?>" id="form-modal" enctype="multipart/form-data">
                <input type="hidden" value="<?= htmlspecialchars($producto_modal["id"]) ?>" name="id">
                
                <label for="nombre">Nombre: </label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($producto_modal["nombre"]) ?>" required>
                
                <label for="stock">Stock: </label>
                <?php 
                    // Determinamos el step: 0.1 para peso (1), 1 para unidades (0)
                    $step = ($producto_modal["precio_por_peso"] == 1) ? "0.1" : "1";
                    
                    // Formateamos el valor inicial: 1 decimal para peso, 0 para unidades
                    $stock_valor = ($producto_modal["precio_por_peso"] == 1) 
                        ? number_format($producto_modal["stock"], 1, '.', '') 
                        : number_format($producto_modal["stock"], 0, '.', '');
                ?>

                <input 
                    type="number" 
                    step="<?= $step ?>" 
                    name="stock" 
                    id="stock" 
                    value="<?= $stock_valor ?>"
                >
                
                <label for="precio">Precio: </label>
                <input type="number" step="0.01" min="0" name="precio" id="precio" value="<?= $producto_modal["precio"] ?>">
                
                <label for="descuento">Descuento: </label>
                <div class="input-porcentaje">
                    <input type="number" name="descuento" id="descuento" min="0" max="99" step="1" value="<?= (int)$producto_modal["porcentaje_descuento"] ?>">
                    <span class="simbolo">%</span>
                </div>

                <div id="precio_descontado_container">
                    Precio final: <span id="precio_descontado">0.00</span>€
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" name="precio_por_peso" id="precio_por_peso" value="1" <?= $producto_modal["precio_por_peso"] ? 'checked' : '' ?>> 
                    <label for="precio_por_peso">Precio por peso</label>
                </div>

                <label for="categoria">Categoría: </label>
                <select name="categoria" id="categoria" onchange="selectSubcategotia()">
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($producto_modal['categoria'] == $cat) ? 'selected' : '' ?>><?= str_replace('_', ' ', $cat) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="subcategorias">Subcategoría: </label>
                <select id="subcategorias" name="subcategoria"></select>

                <label>Imagen del producto:</label>
                <div class="contenedor-preview">
                    <img id="preview" src="../<?= $producto_modal['url_imagen'] ?>" alt="Vista previa">
                </div>
                <?php if($action_form=="?action=insertar"):?>
                    <input type="file" name="nueva_imagen" id="input_imagen" accept="image/*" aria-label="Seleccionar imagen" required>
                <?php else:?>
                    <input type="file" name="nueva_imagen" id="input_imagen" accept="image/*" aria-label="Seleccionar imagen">
                <?php endif;?>
                
                    
                
                
                <input type="submit" name="enviar" value="<?= $texto_boton ?>" class="btn-actualizar">
            </form>
        </div>
    </div>

    <script>
        abrirModal(
            <?= json_encode($producto_modal['id']) ?>, 
            <?= json_encode($producto_modal['categoria']) ?>, 
            <?= json_encode($producto_modal['subcategoria']) ?>
        );
        if (window.history.replaceState) { window.history.replaceState(null, null, window.location.href.split('?')[0]); }
    </script>
<?php endif; ?>

<?php } // Cierre else permisos ?>