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
                            
                            <button class="btn-eliminar" title="Eliminar reserva"><i class="fi fi-sr-trash"></i> Eliminar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="vacio">No hay reservas</p>
            <?php endif; ?>
        </div>
        <h2>Pedidos</h2>
    </main>
</div>