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
            <h2 class="usuario-nombre"><?=$_SESSION["nombre"]?></h2>
            <p class="usuario-email"><?=$_SESSION["email"]?></p>
        </div>
    </aside>

    <main class="contenido-principal">
        
        <h1>Perfil</h1>
        <div class="seccion">
            <p class="seccion-titulo">Resumen</p>
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
                <div class="stat"><div class="stat-num"><?=$total_pendiente?> €</div><div class="stat-label">Total pendiente</div></div>
            </div>
        </div>
        <div class="seccion">
            <p class="seccion-titulo">Mis reservas</p>
            
            <?php if(!empty($reservas)): ?>
                <?php foreach($reservas as $reserva): ?>
                    <div class="item-pedido">
                        <div class="item-info-izquierda">
                            <div class="item-img">
                                <img src="<?=$reserva["url_imagen"]?>" alt="producto">
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
            <p class="seccion-titulo">Mis pedidos</p>
            <?php if(!empty($pedidos)): 
                foreach($pedidos as $pedido): ?>
                <div class="item-pedido">
                    <div class="item-info-izquierda">
                        <div class="item-img"> 
                            <img src="<?= htmlspecialchars($pedido["url_imagen"]) ?>" alt="producto">
                        </div>
                        
                        <div class="item-detalles">
                            <p class="item-nombre">
                                <?= htmlspecialchars($pedido["nombre_comida"]) ?> 
                                <span class="badge-gris">x<?=$pedido["cantidad"]?></span>
                            </p>
                            
                            <p class="item-sub">
                                <?= !empty($pedido["mensaje"]) ? "Nota: " . htmlspecialchars($pedido["mensaje"]) : "Sin observaciones" ?>
                            </p>
                        </div>
                    </div>

                    <div class="item-info-derecha">
                        <span class="item-precio">
                            <?= number_format($pedido["precio"] * $pedido["cantidad"], 2) ?> €
                        </span>
                    </div>
                </div>
                <?php endforeach; 
            else: ?>
                <p class="vacio">No hay pedidos</p>
            <?php endif; ?>

        </div>
    </main>
</div>