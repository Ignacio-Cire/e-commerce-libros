<?php 
$titulo = "Administrar Órdenes";
include_once('Estructura/cabecera.php'); 
$rol = $session->getRolActivo();


if(!$session->activa() || ($rol != 1 && $rol != 3)) { 
    echo "<div class='alert alert-danger mt-3'>Acceso denegado.</div>";
    include_once('Estructura/pie.php');
    exit;
}
?>

<div class="container">
    <h3 class="mb-4">Gestión de Pedidos</h3>
    
    <div class="table-responsive shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado Actual</th>
                    <th>Cambiar Estado</th>
                    <th>Detalles</th>
                </tr>
            </thead>
            <tbody id="tabla-ordenes">
                <tr><td colspan="6" class="text-center">Cargando órdenes...</td></tr>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modalItems" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Items del Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm">
            <thead><tr><th>Prod</th><th>Cant</th></tr></thead>
            <tbody id="cuerpo-modal-items"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
    cargarOrdenes();
});

function cargarOrdenes(){
    $.ajax({
        url: '/proyecto/Acciones/compra/listarOrdenes.php',
        type: 'POST',
        dataType: 'json',
        success: function(data){
            let html = '';
            if(data.length > 0){
                data.forEach(orden => {
                    // Colores segun estado
                    let badgeClass = 'bg-secondary';
                    if(orden.idestado == 1) badgeClass = 'bg-warning text-dark'; // Iniciada
                    if(orden.idestado == 2) badgeClass = 'bg-info text-dark';    // Aceptada
                    if(orden.idestado == 3) badgeClass = 'bg-success';           // Enviada
                    if(orden.idestado == 4) badgeClass = 'bg-danger';            // Cancelada

                    // Botones de accion segun estado actual
                    let botones = '';
                    if(orden.idestado == 1){
                        botones += `<button class="btn btn-sm btn-outline-primary me-1" onclick="cambiarEstado(${orden.idcompra}, 2)">Aceptar</button>`;
                        botones += `<button class="btn btn-sm btn-outline-danger" onclick="cambiarEstado(${orden.idcompra}, 4)">Cancelar</button>`;
                    } else if(orden.idestado == 2){
                        botones += `<button class="btn btn-sm btn-outline-success me-1" onclick="cambiarEstado(${orden.idcompra}, 3)">Enviar</button>`;
                        botones += `<button class="btn btn-sm btn-outline-danger" onclick="cambiarEstado(${orden.idcompra}, 4)">Cancelar</button>`;
                    } else if(orden.idestado == 3){
                        botones = '<span class="text-success"><i class="fas fa-check"></i> Finalizado</span>';
                    } else {
                        botones = '<span class="text-muted">Cancelado</span>';
                    }

                    html += `<tr>
                        <td>#${orden.idcompra}</td>
                        <td>${orden.usnombre}</td>
                        <td>${orden.fecha}</td>
                        <td><span class="badge ${badgeClass}">${orden.estado_desc}</span></td>
                        <td>${botones}</td>
                        <td><button class="btn btn-sm btn-secondary" onclick="verItems(${orden.idcompra})"><i class="fas fa-eye"></i></button></td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="6" class="text-center">No hay órdenes pendientes.</td></tr>';
            }
            $('#tabla-ordenes').html(html);
        }
    });
}

function cambiarEstado(idCompra, idNuevoEstado){
    if(confirm("¿Confirmar cambio de estado?")){
        $.post('/proyecto/Acciones/compra/cambiarEstado.php', {idcompra: idCompra, idestadotipo: idNuevoEstado}, function(res){
            let data = JSON.parse(res);
            alert(data.msg);
            if(data.exito) cargarOrdenes();
        });
    }
}

function verItems(idCompra){
    var myModal = new bootstrap.Modal(document.getElementById('modalItems'));
    myModal.show();
    $('#cuerpo-modal-items').html('<tr><td colspan="2">Cargando...</td></tr>');
    
    $.post('/proyecto/Acciones/compra/itemsDeCompra.php', {idcompra: idCompra}, function(res){
        let data = JSON.parse(res);
        let html = '';
        data.forEach(i => {
            html += `<tr><td>${i.pronombre}</td><td>${i.cantidad}</td></tr>`;
        });
        $('#cuerpo-modal-items').html(html);
    });
}
</script>

<?php include_once('Estructura/pie.php'); ?>