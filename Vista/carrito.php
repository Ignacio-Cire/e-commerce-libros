<?php include_once('Estructura/cabecera.php'); 
if(!$session->activa()) { header('Location: login.php'); exit; }
?>

<div class="container">
    <h2 class="mb-4">Mi Carrito</h2>
    <div class="table-responsive shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpo-carrito">
                <!-- el resto se llebna con js -->
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between mt-4">
        <button onclick="vaciarCarrito()" class="btn btn-outline-danger">Vaciar Carrito</button>
        <button onclick="finalizarCompra()" class="btn btn-success btn-lg">Finalizar Compra</button>
    </div>
</div>


<script src="/proyecto/Utiles/js/funcionesCarrito.js"></script>

<?php include_once('Estructura/pie.php'); ?>