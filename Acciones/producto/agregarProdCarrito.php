<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$respuesta = ['exito' => false, 'msg' => 'Error desconocido'];

if($session->activa()){
    if(isset($datos['idproducto']) && isset($datos['cantidad'])){
        $abmCompra = new abmCompra();
        $abmItem = new abmCompraItem();
        $idUsuario = $session->getUsuario()->getIdUsuario();
        
        $idProducto = $datos['idproducto'];
        $cantidad = (int)$datos['cantidad'];

        if($cantidad <= 0) {
            echo json_encode(['exito' => false, 'msg' => 'Cantidad inválida']);
            exit;
        }

        
        $objProducto = new Producto();
        $objProducto->setear($idProducto, null, null, null, null, null);
        if($objProducto->cargar()){
            $stockActual = $objProducto->getProCantStock();
        } else {
            echo json_encode(['exito' => false, 'msg' => 'Producto no encontrado']);
            exit;
        }
        

        $compra = $abmCompra->buscarCarrito($idUsuario);
        if($compra == null){
            $compra = $abmCompra->iniciarCompra($idUsuario);
        }

        if($compra != null){
            $listaItems = $abmItem->buscar([
                'idcompra' => $compra->getIdCompra(), 
                'idproducto' => $idProducto
            ]);
            
            if(count($listaItems) > 0){
               
                $itemExistente = $listaItems[0];
                $cantidadEnCarrito = $itemExistente->getCiCantidad();
                $nuevaCantidadTotal = $cantidadEnCarrito + $cantidad;
                
                
                if($nuevaCantidadTotal > $stockActual){
                    $respuesta = ['exito' => false, 'msg' => "Stock insuficiente. Ya tienes $cantidadEnCarrito en carrito y el stock es $stockActual."];
                } else {
                    $paramModificacion = [
                        'idcompraitem' => $itemExistente->getIdCompraItem(),
                        'cicantidad' => $nuevaCantidadTotal
                    ];
                    if($abmItem->modificacion($paramModificacion)){
                        $respuesta = ['exito' => true, 'msg' => 'Se actualizó la cantidad en el carrito'];
                    }
                }

            } else {
                
                if($cantidad > $stockActual){
                     $respuesta = ['exito' => false, 'msg' => "Stock insuficiente. Solo quedan $stockActual unidades."];
                } else {
                    $paramAlta = [
                        'objProducto' => $objProducto,
                        'objCompra' => $compra,
                        'cicantidad' => $cantidad
                    ];
                    if($abmItem->alta($paramAlta)){
                        $respuesta = ['exito' => true, 'msg' => 'Producto agregado al carrito'];
                    }
                }
            }
        } else {
            $respuesta = ['exito' => false, 'msg' => 'No se pudo iniciar el carrito'];
        }
    } else {
        $respuesta = ['exito' => false, 'msg' => 'Faltan datos'];
    }
} else {
    $respuesta = ['exito' => false, 'msg' => 'Sesión expirada'];
}

echo json_encode($respuesta);
?>