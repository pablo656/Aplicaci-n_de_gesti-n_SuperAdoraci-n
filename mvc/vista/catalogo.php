
<div class="contenido">
    <aside>
        <ul>
            <li><a href="IndexProducto.php?action=Bebidas">Bebidas</a></li>
            <li><a href="IndexProducto.php?action=Comida">Comida</a></li>
            <li><a href="IndexProducto.php?action=Mascotas">Mascotas</a></li>
            <li><a href="IndexProducto.php?action=Limpieza_hogar">Limpieza y hogar</a></li>
            <li><a href="IndexProducto.php?action=Papeleria_oficina">Papeleria y oficina</a></li>
            <li><a href="IndexProducto.php?action=Salud_bienestar">Salud y bienestar</a></li>
        </ul>
    </aside>
    <main>
        <h1>Catalogo</h1>
        <h2>Todos los productos</h2>
        <hr>
        
        <!-- BORRAR PROXIMAMENTE: Productos de prueva para ver como se ven
        <div class="producto">
            <img>
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;</p>
            <button class="reservar">Reservar</button>
        </div>
        <div class="producto">
            <img>
            <p class="nombre">cxvcxvxc</p>
            <div class="descuento">
                <p class="sin_descuento">10&euro;</p>
                <p class="precio">5&euro;</p>
            </div>
            <button class="reservar">Reservar</button>
        </div>
        <div class="producto">
            <img>
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;/Kg</p>
            <button class="reservar">Reservar</button>
        </div>
        <div class="producto">
            <img>
            <p class="nombre">cxvcxvxc</p>
            <div class="descuento">
                <p class="sin_descuento">10&euro;</p>
                <p class="precio">5&euro;/Kg</p>
            </div>
            <button class="reservar">Reservar</button>
        </div>-->
        
        <?php
            if(!empty($productos)){
                foreach($productos as $producto){?>
                    <div class="producto">
                        <img src="<?php echo $producto["url_imagen"]?>">
                        <p class="nombre"><?php echo $producto["nombre"]?></p>
                        <?php
                        if($producto["porcentaje_descuento"]!=0){
                            if($producto["precio_por_peso"]){?>
                            <div class="descuento">
                                <p class="sin_descuento"><?php echo $producto["precio"] ?>&euro;</p>
                                <p class="precio"><?php echo $producto["precio"]-($producto["precio"]*($producto["porcentaje_descuento"]/100))?>&euro;/Kg</p>
                            </div>
                            <?php
                            }else{?>
                                <div class="descuento">
                                    <p class="sin_descuento"><?php echo $producto["precio"] ?>&euro;</p>
                                    <p class="precio"><?php echo $producto["precio"]-($producto["precio"]*($producto["porcentaje_descuento"]/100))?>&euro;</p>
                                </div>
                            <?php
                            }
                        }else{
                            if($producto["precio_por_peso"]){?>
                                <p class="precio"><?php echo $producto["precio"]?>&euro;/Kg</p>
                            <?php
                            }else{?>
                                <p class="precio"><?php echo $producto["precio"]?>&euro;</p>
                            <?php
                            }
                        }
                    
                        ?>
                        <button class="reservar">Reservar</button>
                    </div>
                <?php
                }
            }else{?>
            <h2 class="vacio">No hay productos</h2>
            <?php
            }
            ?>
        
    </main>
</div>