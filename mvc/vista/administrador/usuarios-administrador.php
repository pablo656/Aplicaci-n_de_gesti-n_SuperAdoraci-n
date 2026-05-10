<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexUsuarios-administrador.php");
    exit();
}
?>

<?php
// 1. Verificación de seguridad (Sesión y Roles)
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["rol"])) {
    header("Location: IndexLog.php");
    exit();
} 

if ($_SESSION["rol"] != "administrador") { ?>
    <div class="acceso-denegado">
        <h1 class="sin-acceso">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar la base de usuarios.</p>
    </div>
<?php
} else { ?>
</main>
<div class="seccion-admin">
    <div class="header-admin">
        <div class="header-textos">
            <h1>Administración de Usuarios</h1>
            <p class="subtitulo">Gestiona los permisos, roles y acceso de los usuarios al sistema.</p>
        </div>
        
        <button name="add"  class="btn-añadir" onclick="abrirModal_Añadir()">
            <i class="fi fi-sr-plus"></i> Añadir usuario
        </button>
       
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
                        <button class="btn-rol" onclick="abrirModal('<?= $usuario['id']?>','<?= $usuario['nombre']?>','<?= $usuario['email']?>','<?= $usuario['rol']?>')">
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
<?php } ?>
<div id="modal" class="modal-overlay" style="display:none"> 
    <div class="modal">
        <button class="modal-cerrar" onclick="cerrarModal()">×</button>
        
        <h2 id="titulo_modificar">Cambiar rol</h2>
        <p class="modal-subtitulo">Selecciona el nuevo nivel de acceso para este usuario.</p>

        <form method="post" action="?action=modificar">
            <!-- Campo oculto para enviar el identificador del usuario -->
            <input type="hidden" value="id" name="id_usuario" id="id_hidden">
            
            <div class="form-group">
                <label for="rol_select">Rol del sistema:</label>
                <select name="rol" id="rol_select">
                    <option value="cliente">Cliente</option>
                    <option value="dueño">Dueño</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <div class="modal-botones">
                <input type="submit" name="modificar" value="Actualizar Rol" class="btn-guardar">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div id="modal_aniadir" class="modal-overlay" style="display:none"> 
    <div class="modal">
        <button class="modal-cerrar" onclick="cerrarModal_Aniadir()">×</button>
        
        <h2 id="titulo_modificar">Añadir Usuarios</h2>
        <p class="modal-subtitulo">Introduce los datos del nuevo usuario para registrarlo en el sistema.</p>

        <form method="post" action="?action=add" id="add">
            <!-- Campo oculto para enviar el identificador del usuario -->
            <input type="hidden" value="id" name="id_usuario" id="id_hidden">
            
            <div class="form-group">
                <label for="user">Usuario</label>
                <input type="text" id="user" name="user" placeholder="Elige un nombre de usuario">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="tu@email.com">
                <label for="pass">Contraseña</label>
                <input type="password" id="pass" name="pass" placeholder="Crea una contraseña">
                <!--<label for="rol_select_<?php echo $usuario->id; ?>">Rol del sistema:</label>-->
                <select name="rol" id="rol_select_<?php echo $usuario->id; ?>">
                    <option value="cliente">Cliente</option>
                    <option value="dueno">Dueño</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <div class="modal-botones">
                <input type="submit" name="add" value="Crear usuario" class="btn-guardar">
                <button type="button" class="btn-cancelar" onclick="cerrarModal_Aniadir()">Cancelar</button>
            </div>
        </form>
    </div>
</div>
<noscript>
    <div class="alerta-no-js">
        <div class="alerta-contenido">
            <i class="fi fi-sr-exclamation"></i>
            <strong>¡Atención! JavaScript está desactivado.</strong>
            <p>Para gestionar usuarios, cambiar roles y usar los formularios de esta página, necesitas activar JavaScript en la configuración de tu navegador.</p>
        </div>
    </div>
</noscript>
<script src="js/usuarios-administrador.js"></script>
</main>