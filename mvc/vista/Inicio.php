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

<div class="envoltura" id="carrusel">
    <ul class="pista">
        <li class="diapositiva">
            <img src="imagenes/carrusel/Carniceria.jpeg" alt="Carnicería">
            <div class="diapositiva-contenido">
                <span class="etiqueta">OFERTA DE LA SEMANA</span>
                <p class="diapositiva-titulo">Frescura que inspira tu cocina diaria</p>
                <p class="diapositiva-subtitulo">Hasta 40% de descuento en frutas y verduras seleccionadas.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Ofertas</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/black-swan-cygnus-atratus-illustrated-by-elizabeth-gould.jpg" alt="Cisne negro">
            <div class="diapositiva-contenido">
                <span class="etiqueta">NUEVOS PRODUCTOS</span>
                <p class="diapositiva-titulo">Descubre nuestra selección exclusiva</p>
                <p class="diapositiva-subtitulo">Los mejores productos frescos traídos cada día para ti.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Catálogo</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/closeup-shot-european-cat-with-camera-lens.jpg" alt="Gato europeo">
            <div class="diapositiva-contenido">
                <span class="etiqueta">CALIDAD GARANTIZADA</span>
                <p class="diapositiva-titulo">Productos de primera calidad cada día</p>
                <p class="diapositiva-subtitulo">Seleccionamos lo mejor para que tu familia disfrute al máximo.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Comprar Ahora</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/black-swan-cygnus-atratus-illustrated-by-elizabeth-gould.jpg" alt="Cisne negro 2" loading="lazy">
            <div class="diapositiva-contenido">
                <span class="etiqueta">TEMPORADA</span>
                <p class="diapositiva-titulo">Lo mejor de cada estación en tu mesa</p>
                <p class="diapositiva-subtitulo">Productos de temporada con el mejor sabor y frescura.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Ver Más</a>
            </div>
        </li>
        <li class="diapositiva">
            <img src="imagenes/carrusel/baby-londok-calotes-closeup-dry-leaves.jpg" alt="Lagarto" loading="lazy">
            <div class="diapositiva-contenido">
                <span class="etiqueta">ESPECIAL</span>
                <p class="diapositiva-titulo">Sabores únicos que marcan la diferencia</p>
                <p class="diapositiva-subtitulo">Explora nuestra gama de productos especiales y exclusivos.</p>
                <a href="IndexProducto.php" class="diapositiva-boton">Explorar</a>
            </div>
        </li>
    </ul>
    <button class="boton anterior" aria-label="Anterior">&#8249;</button>
    <button class="boton siguiente" aria-label="Siguiente">&#8250;</button>
    <div class="indicadores"></div>
</div>

<h2>Productos más vendidos</h2>
<div class="productos">
    <div class="producto">
        <img src="" alt="">
        <div class="info-producto">
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;</p>
            <button class="reservar">Reservar</button>
        </div>
    </div>
    <div class="producto">
        <img src="" alt="">
        <div class="info-producto">
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;</p>
            <button class="reservar">Reservar</button>
        </div>
    </div>
    <div class="producto">
        <img src="" alt="">
        <div class="info-producto">
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;</p>
            <button class="reservar">Reservar</button>
        </div>
    </div>
    <div class="producto">
        <img src="" alt="">
        <div class="info-producto">
            <p class="nombre">xcvxcvcxv</p>
            <p class="precio">10&euro;</p>
            <button class="reservar">Reservar</button>
        </div>
    </div>
</div>

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

<script src="js/home.js"></script>
