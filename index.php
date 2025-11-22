<?php
include_once("configuracion.php");
$titulo = "Inicio - Clavo Componentes";
// No incluimos la cabecera estándar porque queremos un diseño único de landing, 
// o podemos incluirla si quieres el menú. Para landing suele ser mejor algo limpio 
// pero incluiremos cabecera para mantener la navegación.
// Como cabecera.php está en Vista/Estructura, ajustamos el include o usamos rutas absolutas.
// Para simplificar en XAMPP raiz:
include_once("Vista/Estructura/cabecera.php");
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm mt-4">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold text-primary">Clavo Componentes</h1>
        <p class="col-md-8 fs-4">Tu tienda de confianza para el armado y actualización de PC.</p>
        <p>Ofrecemos los mejores procesadores, placas de video y periféricos del mercado con garantía y asesoramiento personalizado.</p>
        <div class="d-flex gap-3 mt-4">
            <a href="/proyecto/Vista/productos.php" class="btn btn-primary btn-lg" type="button">
                <i class="fas fa-shopping-cart"></i> Ver Catálogo
            </a>
            <a href="/proyecto/Vista/login.php" class="btn btn-outline-secondary btn-lg" type="button">
                <i class="fas fa-user"></i> Ingresar
            </a>
        </div>
    </div>
</div>

<div class="row align-items-md-stretch">
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 text-white bg-dark rounded-3">
            <h2>¿Quiénes Somos?</h2>
            <p>Somos una tienda especializada en hardware de alto rendimiento ubicada en Neuquén. Nos dedicamos a traer la última tecnología para gamers y profesionales.</p>
            <a href="/proyecto/Vista/productos.php" class="btn btn-outline-light" type="button">Ir a la Tienda</a>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h2>Contacto y Ubicación</h2>
            <ul class="list-unstyled mt-3">
                <li class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i> <strong>Dirección:</strong> Amaro Velazquez 317, Neuquén</li>
                <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i> <strong>Email:</strong> clavocomponentes@gmail.com</li>
                <li class="mb-2"><i class="fas fa-phone text-success me-2"></i> <strong>Teléfono:</strong> 2994784498</li>
                <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i> <strong>Horarios:</strong> Lun a Vie 9:00 - 18:00hs</li>
            </ul>
        </div>
    </div>
</div>

<?php include_once("Vista/Estructura/pie.php"); ?>