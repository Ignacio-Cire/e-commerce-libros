<?php 
$titulo = "Gestión de Productos";
include_once('Estructura/cabecera.php'); 
$rol = $session->getRolActivo();
if(!$session->activa() || ($rol != 1 && $rol != 3)) { exit("Acceso denegado"); }
?>

<div class="container">
    <h3 class="mb-4">Gestión de Inventario</h3>

    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">Nuevo Producto</div>
        <div class="card-body">
            <form id="form-alta-prod" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="pronombre" placeholder="Nombre" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="prodetalle" placeholder="Detalle" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="proimagen" placeholder="URL Imagen">
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" name="proprecio" placeholder="Precio" required step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="procantstock" placeholder="Stock" required min="0">
                </div>
                <div class="col-12">
                    <button type="button" onclick="crearProducto()" class="btn btn-success w-100">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <div id="panel-productos"></div>
    </div>
</div>

<script>
$(document).ready(function(){ cargarProductosGestion(); });

function cargarProductosGestion(){
    $.ajax({
        url: '/proyecto/Acciones/producto/listarProdTienda.php',
        type: 'POST', dataType: 'json',
        success: function(productos){
            let html = '<table class="table table-bordered bg-white align-middle">';
            // Ajustamos anchos de columna para que entre todo
            html += '<thead class="table-dark"><tr><th style="width:50px">ID</th><th style="width:150px">Imagen</th><th>Nombre</th><th>Detalle</th><th style="width:100px">Precio</th><th style="width:80px">Stock</th><th>Acciones</th></tr></thead><tbody>';
            productos.forEach(prod => {
                html += `<tr>
                    <td>${prod.idproducto}</td>
                    <td>
                        <img src="${prod.imagen}" style="width:50px;height:50px;object-fit:cover" class="mb-1 d-block mx-auto">
                        <input type="text" class="form-control form-control-sm" id="img_${prod.idproducto}" value="${prod.imagen}" placeholder="Ruta/URL">
                    </td>
                    <td><input type="text" class="form-control form-control-sm" id="nom_${prod.idproducto}" value="${prod.pronombre}"></td>
                    <td><input type="text" class="form-control form-control-sm" id="det_${prod.idproducto}" value="${prod.prodetalle}"></td>
                    <td><input type="number" class="form-control form-control-sm" id="pre_${prod.idproducto}" value="${prod.precio}" step="0.01"></td>
                    <td><input type="number" class="form-control form-control-sm" id="stk_${prod.idproducto}" value="${prod.procantstock}"></td>
                    <td>
                        <button class="btn btn-primary btn-sm w-100" onclick="guardarProducto(${prod.idproducto})">Guardar</button>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            $('#panel-productos').html(html);
        }
    });
}

function crearProducto(){
    $.post('/proyecto/Acciones/producto/altaProducto.php', $('#form-alta-prod').serialize(), function(res){
        let response = JSON.parse(res);
        alert(response.msg);
        if(response.exito){ $('#form-alta-prod')[0].reset(); cargarProductosGestion(); }
    });
}

function guardarProducto(id){
    $.post('/proyecto/Acciones/producto/editarProducto.php', {
        idproducto: id,
        pronombre: $('#nom_'+id).val(),
        prodetalle: $('#det_'+id).val(),
        procantstock: $('#stk_'+id).val(),
        proprecio: $('#pre_'+id).val(),
        proimagen: $('#img_'+id).val() 
    }, function(res){
        alert(JSON.parse(res).msg);
        cargarProductosGestion();
    });
}
</script>
<?php include_once('Estructura/pie.php'); ?>