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
                    <a href="IndexProducto.php?action=<?= $cat ?>"><?= str_replace("_", " ", $cat) ?></a>
                    <?php if($action == $cat && isset($subcategorias[$cat]) && !empty($subcategorias[$cat])): ?>
                        <ul class="subcategorias">
                            <?php foreach($subcategorias[$cat] as $sub): ?>
                                <li>
                                    <a href="IndexProducto.php?action=<?= $cat ?>&subcategoria=<?= urlencode($sub) ?>">
                                        <?= $sub ?>
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
                    <div class="producto">
                        <img src="<?php echo $producto['url_imagen'] ?>" alt="<?php echo $producto['nombre'] ?>" loading="lazy">
                        <div class="info-producto">
                            <p class="nombre"><?php echo $producto['nombre'] ?></p>
                            <?php if ($producto['porcentaje_descuento'] != 0): ?>
                                <div class="descuento">
                                    <p class="sin_descuento"><?php echo $producto['precio'] ?>&euro;</p>
                                    <p class="precio">
                                        <?php echo $producto['precio'] - ($producto['precio'] * ($producto['porcentaje_descuento'] / 100)) ?>
                                        &euro;<?php echo $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="precio">
                                    <?php echo $producto['precio'] ?>&euro;<?php echo $producto['precio_por_peso'] ? '/Kg' : '' ?>
                                </p>
                            <?php endif; 
                            if(isset($_SESSION["id"]))://El input de tipo hiden sirve para guardar el valor del id del producto para que cuando se haga el submit se pueda mandar tambien el id 
                                 $reservas = isset($_COOKIE["reservas"]) ? json_decode($_COOKIE["reservas"], true) : [];
                                if(in_array($producto["id"], $reservas)):?>
                                    <!-- PONER opción para aumentar la cantidad del producto -->
                                <?php else: ?>
                                    <form method="post" action="?action=reservar">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto["id"] ?>"> <!-- ← echo -->
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

    </main>
</div>
