<?php
include_once("../../configuracion.php");
$session = new Session();
$respuesta = ['exito' => false, 'msg' => 'Error desconocido'];

if($session->activa()){
    $abmCompra = new abmCompra();
    $abmItem = new abmCompraItem(); 
    $abmProd = new abmProducto();  
    $idUsuario = $session->getUsuario()->getIdUsuario();
    
    
    $compraCarrito = $abmCompra->buscarCarrito($idUsuario);
    
    if($compraCarrito != null){
        
       
        $itemsCarrito = $abmItem->buscar(['idcompra' => $compraCarrito->getIdCompra()]);
        
       
        $stockOk = true;
        foreach($itemsCarrito as $item){
            $prod = $item->getObjProducto();
            if($prod->getProCantStock() < $item->getCiCantidad()){
                $stockOk = false;
                $respuesta['msg'] = "No hay suficiente stock de " . $prod->getProNombre();
                break;
            }
        }

        if($stockOk){
            
            foreach($itemsCarrito as $item){
                $prod = $item->getObjProducto();
               
                $abmProd->modificarStock($prod->getIdProducto(), $item->getCiCantidad());
            }

            
            $abmCE = new abmCompraEstado();
            $listaCE = $abmCE->buscar(['idcompra' => $compraCarrito->getIdCompra(), 'cefechafin' => 'null']);
            
            $exitoEstado = true;
            foreach($listaCE as $estado){
                 $estado->setCefechaFin(date('Y-m-d H:i:s'));
                 if(!$estado->modificar()){ $exitoEstado = false; }
            }
            
            if($exitoEstado){
                $nuevoEstado = new CompraEstado();
                $nuevoEstado->setear(null, $compraCarrito->getIdCompra(), 1, date('Y-m-d H:i:s'), null);
                
                if($nuevoEstado->insertar()){
                    $respuesta = ['exito' => true, 'msg' => 'Compra realizada con éxito. Stock actualizado.'];
                } else {
                    $respuesta = ['exito' => false, 'msg' => 'Error al generar la orden de compra'];
                }
            } else {
                $respuesta = ['exito' => false, 'msg' => 'Error al cerrar el carrito'];
            }
        }

    } else {
        $respuesta = ['exito' => false, 'msg' => 'No tienes carrito activo'];
    }
} else {
    $respuesta = ['exito' => false, 'msg' => 'Sesión expirada'];
}

echo json_encode($respuesta);
?>