<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location:IndexComidas-administrador.php");
    exit();
}
?>
<?php
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
}

if ($_SESSION["rol"] != "administrador" && $_SESSION["rol"] != "dueno"): ?>
    <div style="text-align:center; padding:50px;">
        <h1 style="color:#e31b23;">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar las comidas.</p>
    </div>
<?php else: ?>

<main class="main-admin">
    <div class="header-admin">
        <div>
            <h1>Administración de comidas</h1>
            <p class="subtitulo">Gestiona el menú disponible para los pedidos.</p>
        </div>
        <form method="post" action="IndexComidas-administrador.php">
            <button name="add" type="submit" class="btn-añadir">
                <i class="fi fi-sr-plus"></i> Añadir comida
            </button>
        </form>
    </div>
    <hr>

    <div class="seccion-comidas">
        <?php if (!empty($comidas)): ?>
        <div class="lista-comidas-admin">
            <?php foreach ($comidas as $c): ?>
                <div class="item-comida-admin">
                    <img src="../<?= htmlspecialchars($c['url_imagen']) ?>" alt="<?= htmlspecialchars($c['nombre']) ?>" loading="lazy">

                    <div class="comida-info">
                        <p class="comida-nombre"><?= htmlspecialchars($c['nombre']) ?></p>
                        <?php if (!empty($c['descripcion'])): ?>
                            <p class="comida-descripcion"><?= htmlspecialchars($c['descripcion']) ?></p>
                        <?php endif; ?>
                        <span class="<?= $c['disponible'] ? 'badge-disponible' : 'badge-no-disponible' ?>">
                            <?= $c['disponible'] ? 'Disponible' : 'No disponible' ?>
                        </span>
                    </div>

                    <div class="comida-accion">
                        <p class="comida-precio"><?= number_format($c['precio'], 2) ?>&euro;</p>

                        <form method="post" action="IndexComidas-administrador.php">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($c['id']) ?>">
                            <button type="submit" class="btn-modificar-comida" name="abrir_modal">
                                <i class="fi fi-sr-pencil"></i> Modificar
                            </button>
                        </form>

                        <form method="post" action="?action=delete" onsubmit="return confirm('¿Eliminar esta comida?');">
                            <input type="hidden" name="id_comida" value="<?= htmlspecialchars($c['id']) ?>">
                            <button type="submit" class="btn-eliminar-comida">
                                <i class="fi fi-sr-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="vacio-comidas">No hay comidas registradas.</p>
        <?php endif; ?>
    </div>
</main>

<script src="js/comidas-administrador.js"></script>

<?php
// ── Modal añadir / modificar ──────────────────────────────
$mostrar_modal  = false;
$comida_modal   = null;
$action_form    = "?action=insertar";
$texto_boton    = "Guardar comida";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["abrir_modal"])) {
    $id_buscado = (int)$_POST["id"];
    foreach ($comidas as $item) {
        if ((int)$item["id"] === $id_buscado) {
            $comida_modal  = $item;
            $mostrar_modal = true;
            $action_form   = "?action=modificar";
            $texto_boton   = "Actualizar comida";
            break;
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $mostrar_modal = true;
    $comida_modal  = ["id" => "", "nombre" => "", "descripcion" => "", "precio" => 0, "disponible" => 1, "url_imagen" => "imagenes/placeholder.png"];
}

if ($mostrar_modal && $comida_modal): ?>
    <div id="modal" class="modal-overlay" style="display:flex;">
        <div class="modal">
            <button class="modal-cerrar" onclick="window.location.href='IndexComidas-administrador.php'">&#x2715;</button>

            <form method="post" action="<?= htmlspecialchars($action_form) ?>" id="form-modal" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($comida_modal['id']) ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($comida_modal['nombre'],ENT_QUOTES, 'UTF-8') ?>" required>

                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                    style="padding:10px;border:1px solid #ccc;border-radius:4px;font-size:1rem;resize:vertical;"
                ><?= htmlspecialchars($comida_modal['descripcion']) ?></textarea>

                <label for="precio">Precio (€)</label>
                <input type="number" step="0.01" min="0" name="precio" id="precio" value="<?= htmlspecialchars($comida_modal['precio']) ?>" required>

                <div class="checkbox-container">
                    <input type="checkbox" name="disponible" id="disponible" value="1"
                        <?= $comida_modal['disponible'] ? 'checked' : '' ?>>
                    <label for="disponible">Disponible</label>
                </div>

                <label>Imagen</label>
                <div class="contenedor-preview">
                    <img id="preview" src="../<?= htmlspecialchars($comida_modal['url_imagen']) ?>" alt="Vista previa">
                </div>

                <input type="file" name="nueva_imagen" id="input_imagen" accept="image/*" aria-label="Seleccionar imagen del producto"
                    <?= $action_form === "?action=insertar" ? 'required' : '' ?>>

                <input type="submit" name="enviar" value="<?= htmlspecialchars($texto_boton) ?>" class="btn-actualizar">
            </form>
        </div>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href.split('?')[0]);
        }
    </script>
<?php endif; ?>

<?php endif; ?>
