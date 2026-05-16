<?php
if (!defined('ACCESO_PERMITIDO')) {
    // Si alguien intenta entrar directo, le mandamos al index
    header("Location: IndexPedidos-administrador.php");
    exit();
}
?>
<?php
if (!isset($_SESSION["nombre"]) || !isset($_SESSION["email"]) || !isset($_SESSION["rol"])) {
    header("Location: IndexLog.php");
    exit();
}

if ($_SESSION["rol"] != "administrador" && $_SESSION["rol"] != "dueno") { ?>
    <div style="text-align: center; padding: 50px;">
        <h1 class="sin-acceso" style="color: #e31b23;">Acceso no permitido</h1>
        <p>No tienes privilegios para gestionar los pedidos.</p>
    </div>
<?php
} else { ?>
    <main>
    <div class="header-admin">
        <div>
            <h1>Administración de pedidos</h1>
            <p class="subtitulo">Gestiona los pedidos de comida de los clientes.</p>
        </div>
    </div>
    <hr>
    <div class="cuadricula_reservas">
        <?php
        if (!empty($pedidos)):
            $usuarios = [];

            foreach ($pedidos as $pedido):
                $id_u = $pedido["id_usuario"];
                $usuarios[$id_u][] = $pedido;
            endforeach;

            foreach ($usuarios as $id_usuario => $lista_pedidos):
                $nombre_usuario = $lista_pedidos[0]["nombre_usuario"];
                $email_usuario  = $lista_pedidos[0]["email_usuario"];
                $pendientes = array_filter($lista_pedidos, fn($p) => $p["realizado"] == 0);
                ?>
                <div class="usuario-grupo">
                    <h2 class="nombre-cliente">
                        <i class="fi fi-sr-user"></i>
                        <?php echo htmlspecialchars($nombre_usuario); ?>
                        <span>(ID: <?php echo htmlspecialchars($id_usuario); ?>) &mdash; <?php echo htmlspecialchars($email_usuario); ?></span>
                        <?php if (count($pendientes) > 0): ?>
                            <span class="badge-pendientes-header"><?php echo count($pendientes); ?> pendiente<?php echo count($pendientes) > 1 ? 's' : ''; ?></span>
                        <?php endif; ?>
                    </h2>

                    <div class="lista-reservas">
                        <?php
                        $total_usuario = 0;
                        foreach ($lista_pedidos as $p) {
                            $total_usuario += $p["precio"] * $p["cantidad"];
                        }
                        ?>
                        <?php foreach ($lista_pedidos as $pedido):
                            $diasPedido = null;
                            if (!empty($pedido['fecha_entrega'])) {
                                $diasPedido = (int)(new DateTime('today'))->diff(new DateTime($pedido['fecha_entrega']))->format('%r%a');
                            }
                            $conRetraso = !$pedido['realizado'] && $diasPedido !== null && $diasPedido < 0;
                        ?>
                            <div class="item-reserva<?= $conRetraso ? ' item-reserva-retraso' : '' ?>">
                                <?php if ($conRetraso): ?>
                                    <div class="banner-retraso">
                                        <i class="fi fi-sr-exclamation"></i> RETRASO · <?= abs($diasPedido) ?> día<?= abs($diasPedido) !== 1 ? 's' : '' ?> tarde
                                    </div>
                                <?php endif; ?>
                                <img src="../<?= htmlspecialchars($pedido["url_imagen"]) ?>" alt="<?= htmlspecialchars($pedido['nombre_comida']) ?>">

                                <div class="item-info">
                                    <p class="nombre"><?= htmlspecialchars($pedido['nombre_comida']) ?></p>
                                    <?php if (!empty($pedido['descripcion'])): ?>
                                        <p class="descripcion"><?= htmlspecialchars($pedido['descripcion']) ?></p>
                                    <?php endif; ?>
                                    <div class="item-meta">
                                        <span class="badge-cantidad"><?= $pedido["cantidad"] ?> Uds</span>
                                        <?php if (!empty($pedido['fecha_entrega'])): ?>
                                            <span class="badge-fecha">
                                                <i class="fi fi-sr-calendar-day"></i>
                                                <?= date('d/m/Y', strtotime($pedido['fecha_entrega'])) ?>
                                            </span>
                                            <?php
                                                if ($diasPedido < 0):
                                                    $cls = 'badge-tiempo badge-tiempo-vencido';
                                                    $txt = 'Vencido';
                                                elseif ($diasPedido === 0):
                                                    $cls = 'badge-tiempo badge-tiempo-hoy';
                                                    $txt = 'Hoy';
                                                elseif ($diasPedido === 1):
                                                    $cls = 'badge-tiempo badge-tiempo-urgente';
                                                    $txt = 'Mañana';
                                                elseif ($diasPedido <= 3):
                                                    $cls = 'badge-tiempo badge-tiempo-urgente';
                                                    $txt = $diasPedido . ' días';
                                                else:
                                                    $cls = 'badge-tiempo badge-tiempo-ok';
                                                    $txt = $diasPedido . ' días';
                                                endif;
                                            ?>
                                            <span class="<?= $cls ?>">
                                                <i class="fi fi-sr-clock"></i> <?= $txt ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($pedido['mensaje'])): ?>
                                            <span class="badge-mensaje badge-mensaje-btn" 
                                                data-nota="<?= htmlspecialchars($pedido['mensaje']) ?>" 
                                                onclick="mostrarModalNota(this.dataset.nota)">
                                                <i class="fi fi-sr-comment"></i> Nota
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($pedido["realizado"]): ?>
                                            <span class="badge-realizado">Realizado</span>
                                        <?php else: ?>
                                            <span class="badge-pendiente">Pendiente</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="item-accion">
                                    <?php
                                        $precioFinal = $pedido["precio"] * $pedido["cantidad"];
                                    ?>
                                    <?php if ($pedido["cantidad"] > 1): ?>
                                        <p class="precio-unit"><?= number_format($pedido["precio"], 2) ?> € / ud.</p>
                                    <?php endif; ?>
                                    <p class="precio"><?= number_format($precioFinal, 2) ?> €</p>

                                    <div class="contador">
                                        <?php if (!$pedido["realizado"]): ?>
                                            <button class="btn-completar" onclick="mostrarModalCompletar(<?= (int)$pedido['id_pedido'] ?>)">
                                                <i class="fi fi-sr-check"></i> Realizado
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn-eliminar" onclick="mostrarModalEliminar(<?= (int)$pedido['id_pedido'] ?>)">
                                            <i class="fi fi-sr-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="fila-total">
                        <span>Total del cliente</span>
                        <span class="total-importe"><?= number_format($total_usuario, 2) ?> €</span>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="vacio-contenedor">
                <p class="vacio">No hay pedidos actualmente.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="js/pedidos-administrador.js"></script>
    </main>
<?php } ?>

<!-- Modal nota -->
<div id="modal-nota" class="modal-overlay">
    <div class="modal-content modal-nota-content">
<div class="modal-nota-header">
            <span class="modal-nota-icon"><i class="fi fi-sr-comment-alt"></i></span>
            <div>
                <p class="modal-nota-label">Nota del cliente</p>
                <h2 class="modal-nota-titulo">Mensaje del pedido</h2>
            </div>
        </div>
        <div class="modal-nota-cuerpo">
            <p class="modal-nota-texto" id="modal-nota-texto"></p>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-cancelar" onclick="cerrarModalNota()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal eliminar -->
<div id="modal-eliminar" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fi fi-sr-exclamation"></i>
            <h2>¿Eliminar pedido?</h2>
        </div>
        <p class="modal-descripcion">
            Esta acción eliminará el pedido del sistema de forma permanente.
        </p>
        <form action="?action=delete" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
            <input type="hidden" name="id_pedido" id="input-id-pedido-eliminar">
            <div class="campo-nota">
                <label for="motivo_nota">Notas de cancelación (opcional):</label>
                <textarea id="motivo_nota" name="nota_administrador"
                    placeholder="Escribe aquí el motivo para que el cliente lo sepa..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">Volver atrás</button>
                <button type="submit" class="btn-confirmar-borrar">Confirmar eliminación</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal marcar realizado -->
<div id="modal-completar" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <i class="fi fi-sr-check" style="color: var(--color-verde);"></i>
            <h2>¿Marcar como realizado?</h2>
        </div>
        <p class="modal-descripcion">
            El pedido quedará marcado como entregado al cliente.
        </p>
        <form action="?action=completar" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'])?>">
            <input type="hidden" name="id_pedido" id="input-id-pedido-completar">
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModalCompletar()">Volver atrás</button>
                <button type="submit" class="btn-confirmar-completar">Confirmar</button>
            </div>
        </form>
    </div>
</div>
