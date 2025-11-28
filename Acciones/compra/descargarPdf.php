<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();

// Verificación rápida: Si el servidor no tiene GD, avisamos antes de romper todo
if (!extension_loaded('gd')) {
    echo "Error Crítico: La librería GD no está activada en Linux.";
    exit;
}

// Verificamos sesión y que venga el ID de compra
if($session->activa() && isset($datos['idcompra'])){
    
    // Instanciamos el abm
    $objAbmPdf = new abmPdf();
    
    // Le pedimos la factura. 
    // Como el método usa $pdf->Output('D'), esto fuerza la descarga automáticamente.
    $objAbmPdf->generarFactura($datos['idcompra']);
    
} else {
    echo "Error: Acceso denegado o faltan datos.";
}
?>