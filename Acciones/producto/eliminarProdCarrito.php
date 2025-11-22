<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();

if($session->activa() && isset($datos['idcompraitem'])){
    $abmItem = new abmCompraItem();
    
    
    if($abmItem->baja(['idcompraitem' => $datos['idcompraitem']])){
        echo json_encode(['exito' => true, 'msg' => 'Producto eliminado']);
    } else {
        echo json_encode(['exito' => false, 'msg' => 'Error al eliminar']);
    }
} else {
    echo json_encode(['exito' => false, 'msg' => 'Error de sesión']);
}
?>