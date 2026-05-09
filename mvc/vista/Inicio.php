<style>
.diapositiva{position:relative!important;flex-shrink:0}
.diapositiva img{width:100%;height:clamp(220px,36vw,500px);object-fit:cover;display:block}
.diapositiva-contenido{position:absolute!important;top:0;left:0;right:0;bottom:0;z-index:6;display:flex!important;flex-direction:column;justify-content:flex-end;padding:clamp(1.5rem,4vw,3.5rem) clamp(1.5rem,5vw,4rem) clamp(1.5rem,4vw,3.5rem) clamp(3rem,7vw,7rem);max-width:620px}
.etiqueta{display:inline-block;background:#e31b23;color:#fff;font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:5px 14px;border-radius:30px;margin-bottom:16px;width:fit-content}
.diapositiva-titulo{font-size:clamp(1.6rem,4vw,3rem);font-weight:800;color:#fff!important;line-height:1.15;margin:0 0 14px;text-shadow:0 2px 8px rgba(0,0,0,.3)}
.diapositiva-subtitulo{font-size:clamp(.9rem,1.8vw,1.1rem);color:rgba(255,255,255,.88)!important;margin:0 0 28px;line-height:1.5;text-shadow:0 1px 4px rgba(0,0,0,.25)}
.diapositiva-boton{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#e31b23 0%,#ff5c38 100%);color:#fff;font-size:.95rem;font-weight:700;padding:14px 36px;border-radius:50px;text-decoration:none;width:fit-content;letter-spacing:.03em;transition:background .25s,color .25s,transform .2s,box-shadow .25s;box-shadow:0 6px 24px rgba(227,27,35,.45),inset 0 1px 0 rgba(255,255,255,.25)}
.diapositiva-boton::after{content:'→';font-size:1.05em;transition:transform .2s}
.diapositiva-boton:hover{background:linear-gradient(135deg,#ff5c38 0%,#e31b23 100%);color:#fff;transform:translateY(-3px);box-shadow:0 12px 36px rgba(227,27,35,.6)}
.diapositiva-boton:hover::after{transform:translateX(4px)}
</style>
<main>
<div class="envoltura" id="carrusel">
    <ul class="pista">
        <li class="diapositiva">
            <img src="imagenes/carrusel/Carniceria.jpeg" alt="Carnicería">
            <div class="diapositiva-contenido">
                <span class="etiqueta">CALIDAD SUPERIOR</span>
                <p class="diapositiva-titulo">Cortes premium con frescura garantizada</p>
                <p class="diapositiva-subtitulo">Seleccionamos las mejores carnes para tus platos más exigentes.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Ofertas</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/nocilla.jpeg" alt="Cisne negro">
            <div class="diapositiva-contenido">
                <span class="etiqueta">NUEVOS SABORES</span>
                <p class="diapositiva-titulo">Desayunos irresistibles para empezar el día con energía</p>
                <p class="diapositiva-subtitulo">Descubre nuestra gama de cremas de cacao y productos de primera calidad.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Catálogo</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/legumbres.jpeg" alt="Gato europeo">
            <div class="diapositiva-contenido">
                <span class="etiqueta">CALIDAD GARANTIZADA</span>
                <p class="diapositiva-titulo">Tu despensa siempre llena al mejor precio</p>
                <p class="diapositiva-subtitulo">Seleccionamos lo mejor para que tu familia disfrute al máximo.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Comprar Ahora</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/limpieza.jpeg" alt="Cisne negro 2" loading="lazy">
            <div class="diapositiva-contenido">
                <span class="etiqueta">LIMPIEZA DEL HOGAR</span>
                <p class="diapositiva-titulo">Eficacia y ahorro en cada lavado</p>
                <p class="diapositiva-subtitulo">Descubre los detergentes y suavizantes que cuidan tu ropa y tu bolsillo..</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Más</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/mascotas.jpeg" alt="Lagarto" loading="lazy">
            <div class="diapositiva-contenido">
                <span class="etiqueta">LO MEJOR PARA TUS MASCOTAS</span>
                <p class="diapositiva-titulo">Ellos se merecen lo mejor todos los días</p>
                <p class="diapositiva-subtitulo">Nutrición completa para perros, gatos y pájaros. ¡Pruébalo!</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Explorar</a>
            </div>
        </li>
    </ul>
    <button class="boton anterior" aria-label="Anterior">&#8249;</button>
    <button class="boton siguiente" aria-label="Siguiente">&#8250;</button>
    <div class="indicadores"></div>
</div>
<?php if (!empty($productos)): ?>
    <h2 style="text-align: center; margin: 30px 0;">Productos más vendidos</h2>
    <section class="seccion-destacados">

        <div class="cuadricula-productos">
            <?php foreach ($productos as $producto):
                // 1. Cálculo del precio lógico
                $precio_original = number_format($producto['precio'], 2, '.', '');
                $precio_final = $precio_original;
                $tiene_descuento = ($producto['porcentaje_descuento'] > 0);

                if ($tiene_descuento) {
                    $descuento = $precio_original * ($producto['porcentaje_descuento'] / 100);
                    $precio_final = number_format($precio_original - $descuento, 2, '.', '');
                }

                // 2. Formateo de stock (1 decimal para peso, 0 para unidades)
                $stock_formateado = ($producto['precio_por_peso'] == 1) 
                    ? number_format($producto['stock'], 1, '.', '') . " Kg" 
                    : number_format($producto['stock'], 0);
            ?>
                
                <div class="producto" id="prod-<?= htmlspecialchars($producto["id"]) ?>">
                    <div class="contenedor-img">
                        <img src="<?= htmlspecialchars($producto['url_imagen']) ?>" 
                             alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                             loading="lazy">
                    </div>

                    <div class="info-producto">
                        <p class="nombre"><?= htmlspecialchars($producto['nombre']) ?></p>
                        
                        <span class="stock">Stock: <?= $stock_formateado ?></span>

                        <div class="descuento">
                            <?php if ($tiene_descuento): ?>
                                <p class="sin_descuento"><?= $precio_original ?>&euro;</p>
                            <?php endif; ?>

                            <p class="precio">
                                <?= $precio_final ?>&euro;<?= $producto['precio_por_peso'] ? '/Kg' : '' ?>
                            </p>
                        </div>
                    </div>
                    <div class="">
                        <a href="IndexProducto.php" class="btn-ir-catalogo">Ir al catálogo</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<h2>Nuestra historia</h2>

<section>
    <div class="texto">
        <h3>Sobre SuperAdoracion</h3>
        <p>SuperAdoracion nació en 2010 con una visión clara: ofrecer productos de la más alta calidad a nuestros clientes. Comenzamos como una pequeña tienda familiar y hemos podido convertirnos en uno de los principales referentes del sector.</p>
        <p>Nuestra pasión por la excelencia y el servicio al cliente nos ha permitido construir relaciones duraderas con miles de clientes satisfechos en todo el país.</p>
        <p>Cada producto que ofrecemos es cuidadosamente seleccionado para garantizar que cumpla con nuestros estándares de calidad. Creemos en la importancia de la confianza y la transparencia en cada transacción.</p>
    </div>
    <div class="imagen-contenedor">
        <img src="imagenes/SuperAdoración.webp" alt="Entrada de la tienda" loading="lazy">
    </div>
</section>

<div class="seccion-invertida">
    <div class="contenedor-video">
        <div class="video">
            <iframe src="https://www.youtube.com/embed/VQAXTZOZSq0?si=KLEbfUGrrY5s4gtz"
                title="SUPER ADORACION"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen>
            </iframe>
        </div>
    </div>
    <div class="texto">
        <h3>Nuestros Fundadores</h3>
        <p>María y Carlos González fundaron SuperAdoracion con el sueño de crear un espacio donde la calidad y el servicio excepcional fueran la norma, no la excepción.</p>
        <p>Con más de 15 años de experiencia en el sector, han dedicado sus vidas a construir una empresa que refleje sus valores de integridad, compromiso y excelencia.</p>
        <p>Hoy en día, continúan liderando la empresa con la misma pasión y dedicación que tuvieron desde el primer día, asegurándose de que cada cliente reciba la mejor experiencia posible.</p>
    </div>
</div>
</main>
<script src="js/home.js"></script>
