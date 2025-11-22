<?php
include_once("../../configuracion.php");
$session = new Session();
$salida = [];


if($session->activa() && ($session->getRolActivo() == 1 || $session->getRolActivo() == 3)){
    $abmCompra = new abmCompra();
    $abmCE = new abmCompraEstado();
    $abmUsuario = new abmUsuario(); 

    
    $listaCompras = $abmCompra->buscar(null);
    
   

    foreach($listaCompras as $compra){
       
        $estados = $abmCE->buscar(['idcompra' => $compra->getIdCompra(), 'cefechafin' => 'null']);
        
        if(count($estados) > 0){
            $estadoActual = $estados[0];
            $tipo = $estadoActual->getIdCompraEstadoTipo();
            
            
            if($tipo != 5){
               
                $usuario = $abmUsuario->buscar(['idusuario' => $compra->getIdUsuario()]);
                $nombreUser = (count($usuario) > 0) ? $usuario[0]->getUsNombre() : "Desconocido";
                
               
                $descEstado = "Desconocido";
                if($tipo==1) $descEstado = "Iniciada";
                if($tipo==2) $descEstado = "Aceptada";
                if($tipo==3) $descEstado = "Enviada";
                if($tipo==4) $descEstado = "Cancelada";

                $salida[] = [
                    'idcompra' => $compra->getIdCompra(),
                    'usnombre' => $nombreUser,
                    'fecha' => $compra->getCoFecha(),
                    'idestado' => $tipo,
                    'estado_desc' => $descEstado
                ];
            }
        }
    }
}

echo json_encode($salida);
?>