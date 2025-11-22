<?php include_once('Estructura/cabecera.php'); ?>

<div class="container">
    <h2 class="mb-4">Productos Disponibles</h2>
    <div class="row" id="lista-productos">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando productos...</p>
        </div>
    </div>
</div>

<!-- RUTA ABSOLUTA  -->
<script src="/proyecto/Utiles/js/listarProductos.js"></script>

<?php include_once('Estructura/pie.php'); ?>