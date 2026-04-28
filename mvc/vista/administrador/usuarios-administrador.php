<?php
// 1. Verificación de seguridad (Sesión y Roles)
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["rol"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
} 

if ($_SESSION["rol"] != "administrador" && $_SESSION["rol"] != "dueño") { ?>
    <div class="acceso-denegado">
        <h1 class="sin-acceso">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar la base de usuarios.</p>
    </div>
<?php
} else { ?>

<div class="seccion-admin">
    <div class="header-admin">
        <div class="header-textos">
            <h1>Administración de Usuarios</h1>
            <p class="subtitulo">Gestiona los permisos, roles y acceso de los usuarios al sistema.</p>
        </div>
        <form method="post" action="IndexUsuarios-administrador.php">
            <button name="add" type="submit" class="btn-añadir">
                <i class="fi fi-sr-plus"></i> Añadir usuario
            </button>
        </form>
    </div>

    <hr class="divisor-admin">

    <div class="usuarios">
        <?php if(!empty($usuarios)):
            foreach ($usuarios as $usuario): ?>
                <div class="usuario">
                    <div class="usuario-avatar">
                        <i class="fi fi-sr-user"></i>
                    </div>

                    <div class="usuario-cuerpo">
                        <div class="usuario-nombre">
                            <p><?= htmlspecialchars($usuario["nombre"]) ?></p>
                            <span class="badge-rol"><?= htmlspecialchars($usuario["rol"]) ?></span>
                        </div>
                        <div class="usuario-info">
                            <p><?= htmlspecialchars($usuario["email"]) ?></p>
                        </div>
                    </div>

                    <div class="botones">
                        <button class="btn-rol">
                            <i class="fi fi-sr-pencil"></i> Cambiar rol
                        </button>
                        
                         <form method="post" action="?action=delete" onsubmit="return confirm('¿Estás seguro?');">
                            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                            <button type="submit" class="btn-eliminar"><i class="fi fi-sr-trash"></i> Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <div class="vacio-contenedor">
                <i class="fi fi-sr-users-slash"></i>
                <p class="vacio">No se han encontrado usuarios registrados.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php } // Cierre del else de permisos ?>