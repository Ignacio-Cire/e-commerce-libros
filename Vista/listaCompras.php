<?php 
include_once('Estructura/cabecera.php'); 
if(!$session->activa()) { header('Location: login.php'); exit; }
$idUsuario = $session->getUsuario()->getIdUsuario();
$abmCompra = new abmCompra();
$abmCE = new abmCompraEstado();
$listaCompras = $abmCompra->buscar(['idusuario' => $idUsuario]);
?>

<div class="container">
    <h2 class="mb-4">Mis Compras Realizadas</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover border">
            <thead class="table-dark">
                <tr>
                    <th>ID Compra</th>
                    <th>Fecha Inicio</th>
                    <th>Estado Actual</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(count($listaCompras) > 0){
                    foreach($listaCompras as $compra){
                        $estados = $abmCE->buscar(['idcompra' => $compra->getIdCompra(), 'cefechafin' => 'null']);
                        $estadoNombre = "Desconocido";
                        $tipo = 0;
                        if(count($estados) > 0){
                            $tipo = $estados[0]->getIdCompraEstadoTipo();
                            if($tipo == 1) $estadoNombre = "Iniciada";
                            elseif($tipo == 2) $estadoNombre = "Aceptada";
                            elseif($tipo == 3) $estadoNombre = "Enviada";
                            elseif($tipo == 4) $estadoNombre = "Cancelada";
                            elseif($tipo == 5) $estadoNombre = "En Carrito";
                        }
                        
                        if($tipo != 5){
                            echo "<tr>";
                            echo "<td>#".$compra->getIdCompra()."</td>";
                            echo "<td>".$compra->getCoFecha()."</td>";
                            echo "<td><span class='badge bg-secondary'>".$estadoNombre."</span></td>";
                            echo "<td>
                                    <button class='btn btn-sm btn-primary me-1' onclick='verDetalle(".$compra->getIdCompra().")'>
                                        <i class='fas fa-eye'></i>
                                    </button>
                                    <a href='../Acciones/compra/generarPDF.php?idcompra=".$compra->getIdCompra()."' target='_blank' class='btn btn-sm btn-danger' title='Descargar Factura'>
                                        <i class='fas fa-file-pdf'></i> PDF
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No tiene compras registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalle de Compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table">
            <thead><tr><th>Producto</th><th>Cant</th><th>Subtotal</th></tr></thead>
            <tbody id="cuerpo-modal"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function verDetalle(idCompra){
    var myModal = new bootstrap.Modal(document.getElementById('modalDetalle'));
    myModal.show();
    $('#cuerpo-modal').html('<tr><td colspan="3">Cargando...</td></tr>');

    $.post('/proyecto/Acciones/compra/itemsDeCompra.php', {idcompra: idCompra}, function(res){
        let data = JSON.parse(res);
        let html = '';
        let total = 0;
        data.forEach(item => {
            total += item.total;
            html += `<tr>
                <td>${item.pronombre}</td>
                <td>${item.cantidad}</td>
                <td>$${item.total}</td>
            </tr>`;
        });
        html += `<tr class="table-active">
            <td colspan="2"><strong>TOTAL</strong></td>
            <td><strong>$${total}</strong></td>
        </tr>`;
        $('#cuerpo-modal').html(html);
    });
}
</script>

<?php include_once('Estructura/pie.php'); ?>