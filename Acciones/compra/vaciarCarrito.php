<?php
include_once("../../configuracion.php");
$session = new Session();
$respuesta = ['exito' => false, 'msg' => ''];

if($session->activa()){
    $abmCompra = new abmCompra();
    $abmCompraItem = new abmCompraItem();
    $idUsuario = $session->getUsuario()->getIdUsuario();
    
    $compra = $abmCompra->buscarCarrito($idUsuario);
    if($compra != null){
        $items = $abmCompraItem->buscar(['idcompra' => $compra->getIdCompra()]);
        foreach($items as $item){
            $item->eliminar(); // Asume que el modelo CompraItem tiene eliminar()
        }
        $respuesta['exito'] = true;
        $respuesta['msg'] = 'Carrito vaciado';
    }
}
echo json_encode($respuesta);
?>