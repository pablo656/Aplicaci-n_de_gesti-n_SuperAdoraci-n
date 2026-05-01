<?php
    if (!isset($_SESSION["nombre"]) || !isset($_SESSION["rol"])) {
    header("Location: ../IndexHome.php?action=log");
    exit();
} 

if ($_SESSION["rol"] != "administrador") { ?>
    <div class="acceso-denegado">
        <h1 class="sin-acceso">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar la base de usuarios.</p>
    </div>
<?php
} else {?>
    <div class="seccion-admin">
        <div class="header-admin">
            <div class="header-textos">
                <h1>Administración de productos más vendidos</h1>
                <p class="subtitulo">Gestiona los permisos, roles y acceso de los usuarios al sistema.</p>
            </div>
            
            <!--<button name="add"  class="btn-añadir" onclick="abrirModal_Añadir()">
                <i class="fi fi-sr-plus"></i> Añadir producto
            </button>-->
        
        </div>

        <hr class="divisor-admin">
        <div class="usuarios">

        </div>
    </div>
<?php }?>