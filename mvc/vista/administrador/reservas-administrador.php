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
    <div class="header-admin">
            <div>
                <h1>Administración de catálogo</h1>
                <p class="subtitulo">Gestiona el stock, precios y disponibilidad de los productos.</p>
            </div>
        </div>
        <hr>
        <div class="cuadricula_reservas">
            <?php 
            // CORRECCIÓN: Añadida la 'r' a $reservas
            if(!empty($reservas)): 
                $usuarios = [];
                
                // Agrupamos las reservas por ID de usuario
                
                foreach($reservas as $reserva):
                    $id_u = $reserva["id_usuario"]; 
                    $usuarios[$id_u][] = $reserva;  
                endforeach;

                // Ahora que ya están agrupados, recorremos los usuarios
                foreach($usuarios as $id_usuario => $lista_reservas):
                    // Accedemos al nombre del usuario (está en cualquier reserva del grupo)
                    $nombre_usuario = $lista_reservas[0]["nombre_usuario"];
                    $email_usuario=$lista_reservas[0]["email_usuario"];
                    ?>
                    <div class="usuario-grupo">
                        <h2 class="nombre-cliente">Cliente: <?php echo $nombre_usuario; ?> (ID: <?php echo $id_usuario; ?>)Email:<?php echo $email_usuario ?></h2>
                        
                      <div class="lista-reservas">
                    <?php foreach($lista_reservas as $reserva): ?>
                        <div class="item-reserva">
                            <img src="../<?= htmlspecialchars($reserva["url_imagen"]) ?>" alt="<?= htmlspecialchars($reserva['nombre_producto']) ?>">
                            
                            <div class="item-info">
                                <p class="nombre"><?= htmlspecialchars($reserva['nombre_producto']) ?></p>
                                <p class="descripcion">
                                    <?= !empty($reserva['subcategoria']) ? htmlspecialchars($reserva['subcategoria']) : htmlspecialchars($reserva['categoria']) ?>
                                </p>
                                <p class="unidad-peso">Cantidad: <?= $reserva["cantidad"] ?> <?php if($reserva["precio_por_peso"]){echo "Kg";}else{echo "Unidades";} ?></p>
                            </div>

                            <div class="item-accion">
                                <?php 
                                    // Cálculo del total con descuento
                                    $precioBase = $reserva["precio"];
                                    $descuento = $reserva["porcentaje_descuento"];
                                    $precioFinal = ($precioBase - ($precioBase * ($descuento / 100))) * $reserva["cantidad"];
                                ?>
                                <p class="precio"><?= number_format($precioFinal, 2) ?> €</p>
                                
                                <div class="contador">
                                    <button  name="modal_eliminar" class="btn-eliminar" onclick="mostrarModalEliminar(<?= $reserva['id_reserva'] ?>)">
                                        <i class="fi fi-sr-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="vacio-contenedor">
                    <p class="vacio">No hay reservas actualmente.</p>
                </div>
            <?php endif; ?>
        </div>
<script src="js/reservas-administrador.js"></script>
<?php }?>
 <div id="modal-eliminar" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fi fi-sr-exclamation"></i>
            <h2>¿Eliminar reserva?</h2>
        </div>
        
        <p class="modal-descripcion">
            Esta acción eliminará la reserva del sistema. El producto volverá a estar disponible en el catálogo general.
        </p>

        <form action="?action=delete" method="POST">
            <input type="hidden" name="id_reserva" id="input-id-reserva-modal">
            
            <div class="campo-nota">
                <label for="motivo_nota">Notas de cancelación (opcional):</label>
                <textarea 
                    id="motivo_nota" 
                    name="nota_administrador" 
                    placeholder="Escribe aquí el motivo para que el cliente lo sepa..."
                ></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">
                    Volver atrás
                </button>
                <button type="submit" name="confirmar_borrado" class="btn-confirmar-borrar">
                    Confirmar eliminación
                </button>
            </div>
        </form>
    </div>
</div>