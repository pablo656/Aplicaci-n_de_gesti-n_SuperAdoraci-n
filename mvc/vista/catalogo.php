<?php  $action=$_GET["action"] ?? "list";
        $subcategoria = $_GET["subcategoria"] ?? null;
         $categorias=["Comida","Bebidas","Mascotas","Papeleria_oficina","Salud_bienestar"];
         $subcategorias = [
        "Comida"            => ["Carne", "Panadería", "Pescados", "Snacks", "Pasta", "Conservas", "Salsas", "Arroz y legumbres", "Condimentos y salsas", "Despensa", "Congelados"],
        "Bebidas"           => ["Agua", "Refrescos", "Zumos", "Bebidas alcohólicas"],
        "Limpieza_hogar"    => ["Limpieza del hogar", "Limpieza de ropa", "Higiene personal", "Papel e higiene", "Ambientadores y velas", "Utensilios de limpieza"],
        "Mascotas"          => ["Gatos", "Perros", "Pájaros"],
        "Papeleria_oficina" => ["Material escolar", "Material de oficina", "Escritura y dibujo", "Archivadores y carpetas", "Folios"],
        "Salud_bienestar"   => []
        ];?>
<div class="contenido">
    <aside>
        <ul>
            <?php foreach($categorias as $cat): ?>
                <li>
                    <a href="IndexProducto.php?action=<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars(str_replace("_", " ", $cat)) ?></a>
                    <?php if($action == $cat && isset($subcategorias[$cat]) && !empty($subcategorias[$cat])): ?>
                        <ul class="subcategorias">
                            <?php foreach($subcategorias[$cat] as $sub): ?>
                                <li>
                                    <a href="IndexProducto.php?action=<?= htmlspecialchars($cat) ?>&subcategoria=<?= urlencode($sub) ?>">
                                        <?= htmlspecialchars($sub) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
    <main>
        <h1>Catálogo</h1>
       <?php
            $breadcrums = "";
            $separador = "<span class='breadcrumb-separador'> > </span>";

            if($action != "list"){
                $breadcrums = "<a href='IndexProducto.php' class='breadcrumb-enlace'>Todos los productos</a>";
                if($subcategoria != null){
                    $breadcrums .= $separador;
                    $breadcrums .= "<a href='IndexProducto.php?action=$action' class='breadcrumb-enlace'>" . str_replace('_', ' ', $action) . "</a>";
                    $breadcrums .= $separador;
                    $breadcrums .= "<span class='breadcrumb-texto'>$subcategoria</span>";
                }else{
                    $breadcrums .= $separador;
                    $breadcrums .= "<span class='breadcrumb-texto'>" . str_replace('_', ' ', $action) . "</span>";
                }
            }else{
                $breadcrums = "<span class='breadcrumb-texto'>Todos los productos</span>";
            }
            echo "<div class='breadcrumb-wrapper'>" . $breadcrums . "</div>";
        ?>
        <hr>
        <div class="cuadricula-productos">
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="producto" id="<?php echo htmlspecialchars($producto["id"])?>">
                        <img src="<?= htmlspecialchars($producto['url_imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" loading="lazy">
                        <div class="info-producto">
                            <p class="nombre"><?= htmlspecialchars($producto['nombre']) ?></p>
                            <?php if ($producto['porcentaje_descuento'] != 0): ?>
                                <div class="descuento">
                                    <p class="sin_descuento"><?= $producto['precio'] ?>&euro;</p>
                                    <p class="precio">
                                        <?= $producto['precio'] - ($producto['precio'] * ($producto['porcentaje_descuento'] / 100)) ?>
                                        &euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="precio">
                                    <?= $producto['precio'] ?>&euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                </p>
                            <?php endif; ?>

                           <?php if(isset($_SESSION["id"])):
                           //El input de tipo hiden sirve para guardar el valor del id del producto para que cuando se haga el submit se pueda mandar tambien el id 
                                 $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
                                 $ids=[];
                                 foreach($reservas as $reserva){
                                    $ids[]=$reserva["id"];

                                 }
                                if(in_array($producto["id"], $ids)):
                                    $reserva_producto;
                                    foreach($reservas as $reserva){
                                        if($reserva["id"]==$producto["id"]){
                                            $reserva_producto=$reserva;
                                        }
                                        
                                    }
                                    $cantidadReservada=$reserva["cantidad"];
                                ?>
                                    <!-- PONER opción para aumentar la cantidad del producto -->
                                     <div class="contador">
                                       <button style="display:none" id="borrar-<?= $producto["id"] ?>" class="btn-cantidad" onclick="borrarReserva(<?= $producto['id'] ?>)">
                                        <i class="fi fi-sr-trash"></i> 
                                    </button>
                                        <button id="restar-<?= $producto["id"] ?>" class="btn-cantidad" onclick="cambiarCantidad(<?= $producto['id'] ?>, -1)">-</button>
                                        <span class="cantidad" id="cantidad-<?= $producto["id"] ?>"><?= $cantidadReservada ?></span>
                                        <button class="btn-cantidad" onclick="cambiarCantidad(<?= $producto['id'] ?>, 1)">+</button>
                                    </div>
                                <?php else: ?>
                                   
                                    <form method="post" action="?action=reservar">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto["id"] ?>"> 
                                        <button type="submit" class="reservar" name="reservar">Reservar</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <a class="reservar" href="IndexHome.php?action=log">Reservar</a>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="vacio">No hay productos disponibles</p>
            <?php endif; ?>
        </div>
        <script src="js/catalogo.js"defer></script>
    </main>
</div>