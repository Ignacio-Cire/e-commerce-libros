<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$respuesta = ['exito' => false, 'msg' => 'Error de permisos'];

if($session->activa() && ($session->getRolActivo() == 1 || $session->getRolActivo() == 3)){
    if(isset($datos['idcompra']) && isset($datos['idestadotipo'])){
        
        $abmCE = new abmCompraEstado();
        $idCompra = $datos['idcompra'];
        $nuevoTipo = $datos['idestadotipo'];

       
        $estadosAbiertos = $abmCE->buscar(['idcompra' => $idCompra, 'cefechafin' => 'null']);
        
        $exitoCierre = true;
        foreach($estadosAbiertos as $est){
            $est->setCefechaFin(date('Y-m-d H:i:s'));
            if(!$est->modificar()){
                $exitoCierre = false;
            }
        }

        if($exitoCierre){
            
            $nuevoEstado = new CompraEstado();
            
            $nuevoEstado->setear(null, $idCompra, $nuevoTipo, date('Y-m-d H:i:s'), null);
            
            if($nuevoEstado->insertar()){
                $respuesta = ['exito' => true, 'msg' => 'Estado actualizado correctamente'];
                
                
            } else {
                $respuesta = ['exito' => false, 'msg' => 'Error al insertar nuevo estado'];
            }
        } else {
            $respuesta = ['exito' => false, 'msg' => 'Error al cerrar estado anterior'];
        }
    } else {
        $respuesta = ['exito' => false, 'msg' => 'Datos faltantes'];
    }
}

echo json_encode($respuesta);
?>