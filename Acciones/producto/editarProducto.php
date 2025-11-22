<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$respuesta = ['exito' => false, 'msg' => 'Permiso denegado'];


if($session->activa() && ($session->getRolActivo() == 1 || $session->getRolActivo() == 3)){
    $abmProd = new abmProducto();
    
   
    if(isset($datos['idproducto'])){
       
        if($abmProd->modificacion($datos)){
            $respuesta = ['exito' => true, 'msg' => 'Producto actualizado correctamente'];
        } else {
            $respuesta = ['exito' => false, 'msg' => 'Error al actualizar en base de datos'];
        }
    } else {
        $respuesta = ['exito' => false, 'msg' => 'Falta ID del producto'];
    }
}

echo json_encode($respuesta);
?>