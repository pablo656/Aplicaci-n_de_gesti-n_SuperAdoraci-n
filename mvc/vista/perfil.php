<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexPerfil.php");
    exit();
}
?>

<?php
$perfil_url ??= 'indexPerfil.php';
$home_url   ??= 'IndexHome.php';
$img_base   ??= '';
?>
<div class="layout-perfil">
    <aside class="sidebar-perfil">
        <div class="perfil-container">
            <div class="avatar-circulo">
                <?php
                $partes = explode(" ", $_SESSION["nombre"]);
                echo (count($partes) > 1)
                    ? strtoupper(substr($partes[0],0,1) . substr(end($partes),0,1))
                    : strtoupper(substr($partes[0],0,1));
                ?>
            </div>
            <p class="usuario-nombre"><?=$_SESSION["nombre"]?></p>
            <p class="usuario-email"><?=$_SESSION["email"]?></p>
        </div>

        <div class="editar-sidebar">
            <h3 class="editar-sidebar-titulo">Editar usuario</h3>

            <?php if (isset($_GET["ok"])): ?>
                <p class="editar-ok">Nombre actualizado correctamente.</p>
            <?php elseif (isset($_GET["error"])): ?>
                <?php $msgs = [
                    "nombre_vacio"     => "El nombre no puede estar vacío.",
                    "nombre_duplicado" => "Ese nombre ya está en uso.",
                    "error_guardado"   => "Error al guardar.",
                ]; ?>
                <p class="editar-error"><?= htmlspecialchars($msgs[$_GET["error"]] ?? "Error desconocido.") ?></p>
            <?php endif; ?>

            <form method="post" action="<?= $home_url ?>?action=actualizar_nombre" class="form-editar">
                <div class="form-grupo">
                    <label for="nombre">Nombre de usuario</label>
                    <input type="text" id="nombre" name="nombre"
                           value="<?= htmlspecialchars($_SESSION["nombre"]) ?>"
                           maxlength="100" required>
                </div>
                <button type="submit" class="btn-guardar">Guardar</button>
            </form>
        </div>

        <div class="feedback-sidebar">
            <h3 class="feedback-titulo">Enviar sugerencia</h3>
            <?php if (isset($_GET['feedback_ok'])): ?>
                <p class="feedback-ok">¡Mensaje enviado correctamente!</p>
            <?php elseif (isset($_GET['feedback_error'])): ?>
                <p class="feedback-error">Error al enviar. Inténtalo de nuevo.</p>
            <?php endif; ?>
            <form method="post" action="indexPerfil.php?action=enviar_feedback" class="form-feedback">
                <textarea name="mensaje" rows="4" placeholder="Tu sugerencia o comentario..." required maxlength="1000" aria-label="Mensaje de sugerencia"></textarea>
                <button type="submit" class="btn-feedback">Enviar</button>
            </form>
        </div>
    </aside>

    <main class="contenido-principal">
        
        <h1>Perfil</h1>
        <div class="seccion">
            <h2 class="seccion-titulo">Resumen</h2>
            <div class="stats">
                <?php 
                   
                ?>
                <div class="stat"><div class="stat-num"><?php if(!empty($reservas)){
                    echo count($reservas);
                    }else{echo "0";
                    }?></div><div class="stat-label">Reservas activas</div></div>
                    <div class="stat"><div class="stat-num"><?php if(!empty($pedidos)){
                    echo count($pedidos);
                    }else{echo "0";
                    }?></div><div class="stat-label">Pedidos activos</div></div>
                    <?php 
                    $total_pendiente=0;
                    if(!empty($reservas)){
                        foreach($reservas as $reserva){
                            if($reserva["porcentaje_descuento"]==0){
                                $total_pendiente+=$reserva["precio"]*$reserva["cantidad"];
                            }else{ 
                                $total_pendiente += ($reserva["precio"] * (1 - ($reserva["porcentaje_descuento"] / 100))) * $reserva["cantidad"];
                            }
                           
                        }
                    }
                    if(!empty($pedidos)){
                        foreach($pedidos as $pedido){
                            $total_pendiente+=$pedido["precio"]*$pedido["cantidad"];
                        }
                    }
                    ?>
                <div class="stat"><div class="stat-num"><?= number_format($total_pendiente, 2) ?> €</div><div class="stat-label">Total pendiente</div></div>
            </div>
        </div>
        <div class="seccion">
            <h2 class="seccion-titulo">Mis reservas</h2>
            
            <?php if(!empty($reservas)): ?>
                <?php foreach($reservas as $reserva): ?>
                    <div class="item-pedido">
                        <div class="item-info-izquierda">
                            <div class="item-img">
                                <img src="<?= $img_base . htmlspecialchars($reserva["url_imagen"]) ?>" alt="producto">
                            </div>
                            <div class="item-detalles">
                                <p class="item-nombre">
                                    <?=$reserva["nombre_producto"]?> 
                                    <span class="badge-gris">x<?=$reserva["cantidad"]?></span>
                                </p>
                                <p class="item-sub"><?=$reserva["subcategoria"]?></p>
                            </div>
                        </div>

                        <div class="item-info-derecha">
                            <?php if($reserva["porcentaje_descuento"] == 0): ?>
                                <span class="item-precio">
                                    <?=number_format($reserva["precio"] * $reserva["cantidad"], 2)?> €
                                </span>
                            <?php else: ?>
                                <div class="precios-contenedor">
                                    <span class="item-sindescuento">
                                        <?=number_format($reserva["precio"] * $reserva["cantidad"], 2)?> €
                                    </span>
                                    <span class="item-precio">
                                        <?php 
                                            $precio_desc = $reserva["precio"] * (1 - ($reserva["porcentaje_descuento"]/100));
                                            echo number_format($precio_desc * $reserva["cantidad"], 2);
                                        ?> €
                                    </span>
                                </div>
                            <?php endif; ?>
                            <form method="post" action="?action=borrar_reserva">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
                            <input type="hidden" name="id_reserva" value="<?=$reserva['id_reserva']?>">
                            <button type="submit" class="btn-eliminar" title="Eliminar reserva"><i class="fi fi-sr-trash"></i> Eliminar</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="vacio">No hay reservas</p>
            <?php endif; ?>
        </div>
        <div class="seccion">
            <h2 class="seccion-titulo">Mis pedidos</h2>
            <?php if (isset($_GET['eliminado'])): ?>
                <p class="editar-ok">Pedido cancelado correctamente.</p>
            <?php elseif (isset($_GET['error_eliminar'])): ?>
                <p class="editar-error">No se puede cancelar: quedan 3 días o menos para la entrega.</p>
            <?php endif; ?>
            <?php if(!empty($pedidos)):
                foreach($pedidos as $pedido): ?>
                <div class="item-pedido">
                    <div class="item-info-izquierda">
                        <div class="item-img"> 
                            <img src="<?= $img_base . htmlspecialchars($pedido["url_imagen"]) ?>" alt="producto">
                        </div>
                        
                        <div class="item-detalles">
                            <p class="item-nombre">
                                <?= htmlspecialchars($pedido["nombre_comida"]) ?>
                                <span class="badge-gris">x<?=$pedido["cantidad"]?></span>
                            </p>

                            <p class="item-sub">
                                <?= !empty($pedido["mensaje"]) ? "Nota: " . htmlspecialchars($pedido["mensaje"]) : "Sin observaciones" ?>
                            </p>

                            <?php if (!empty($pedido['fecha_entrega'])): ?>
                                <?php
                                    $hoy     = new DateTime('today');
                                    $entrega = new DateTime($pedido['fecha_entrega']);
                                    $dias    = (int)$hoy->diff($entrega)->format('%r%a');
                                    if ($dias < 0) {
                                        $badgeCls = 'badge badge-rojo';
                                        $badgeTxt = 'Vencido';
                                    } elseif ($dias === 0) {
                                        $badgeCls = 'badge badge-rojo';
                                        $badgeTxt = 'Hoy';
                                    } elseif ($dias === 1) {
                                        $badgeCls = 'badge badge-naranja';
                                        $badgeTxt = 'Mañana';
                                    } elseif ($dias <= 3) {
                                        $badgeCls = 'badge badge-naranja';
                                        $badgeTxt = $dias . ' días';
                                    } else {
                                        $badgeCls = 'badge badge-verde';
                                        $badgeTxt = $dias . ' días';
                                    }
                                ?>
                                <div class="item-fecha-entrega">
                                    <i class="fi fi-sr-calendar-day"></i>
                                    Entrega: <?= date('d/m/Y', strtotime($pedido['fecha_entrega'])) ?>
                                    <span class="<?= $badgeCls ?>"><?= $badgeTxt ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="item-info-derecha">
                        <span class="item-precio">
                            <?= number_format($pedido["precio"] * $pedido["cantidad"], 2) ?> €
                        </span>
                        <?php
                            $puedeCancelar = !empty($pedido['fecha_entrega']) &&
                                (new DateTime('today'))->diff(new DateTime($pedido['fecha_entrega']))->format('%r%a') > 3;
                        ?>
                        <?php if ($puedeCancelar): ?>
                            <button class="btn-cancelar-pedido" onclick="abrirModalCancelar(<?= (int)$pedido['id_pedido'] ?>)">
                                <i class="fi fi-sr-trash"></i> Cancelar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach;
            else: ?>
                <p class="vacio">No hay pedidos</p>
            <?php endif; ?>

        </div>

        <!-- Modal cancelar pedido -->
        <div id="modal-cancelar-pedido" class="modal-cancelar-overlay">
            <div class="modal-cancelar-box">
                <p class="modal-cancelar-titulo">¿Cancelar pedido?</p>
                <p class="modal-cancelar-desc">Esta acción no se puede deshacer.</p>
                <form method="POST" action="<?= $perfil_url ?>?action=borrar_pedido">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
                    <input type="hidden" name="id_pedido" id="input-cancelar-id">
                    <div class="modal-cancelar-acciones">
                        <button type="button" onclick="cerrarModalCancelar()">Volver</button>
                        <button type="submit" class="btn-confirmar-cancelar">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function abrirModalCancelar(id) {
                document.getElementById('input-cancelar-id').value = id;
                document.getElementById('modal-cancelar-pedido').style.display = 'flex';
            }
            function cerrarModalCancelar() {
                document.getElementById('modal-cancelar-pedido').style.display = 'none';
            }
        </script>


        <div class="seccion seccion-editar">
            <h2 class="seccion-titulo">Editar perfil</h2>

            <?php if (isset($_GET["ok"])): ?>
                <p class="editar-ok">Nombre actualizado correctamente.</p>
            <?php elseif (isset($_GET["error"])): ?>
                <?php $msgs = [
                    "nombre_vacio"     => "El nombre no puede estar vacío.",
                    "nombre_duplicado" => "Ese nombre de usuario ya está en uso.",
                    "error_guardado"   => "Error al guardar los cambios.",
                ]; ?>
                <p class="editar-error"><?= htmlspecialchars($msgs[$_GET["error"]] ?? "Error desconocido.") ?></p>
            <?php endif; ?>

            <form method="post" action="indexPerfil.php?action=actualizar_nombre" class="form-editar">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
                <div class="form-grupo">
                    <label for="nombre">Nombre de usuario</label>
                    <input type="text" id="nombre" name="nombre"
                           value="<?= htmlspecialchars($_SESSION["nombre"]) ?>"
                           maxlength="100" required>
                </div>
                <button type="submit" class="btn-guardar">Guardar cambios</button>
            </form>
        </div>
    </main>
</div>