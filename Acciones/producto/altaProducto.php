<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$respuesta = ['exito' => false, 'msg' => 'Permiso denegado'];


if($session->activa() && ($session->getRolActivo() == 1 || $session->getRolActivo() == 3)){
    $abmProd = new abmProducto();
    
    if(isset($datos['pronombre']) && isset($datos['procantstock'])){
        if($abmProd->alta($datos)){
            $respuesta = ['exito' => true, 'msg' => 'Producto creado exitosamente'];
        } else {
            $respuesta = ['exito' => false, 'msg' => 'Error al crear producto en BD'];
        }
    } else {
        $respuesta = ['exito' => false, 'msg' => 'Datos incompletos'];
    }
}

echo json_encode($respuesta);
?>