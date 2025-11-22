<?php
include_once("../../configuracion.php");
$session = new Session();
$resultado = [];

if($session->activa()){
    $idUsuario = $session->getUsuario()->getIdUsuario();
    $abmCompra = new abmCompra();
    $abmItem = new abmCompraItem();

    $compra = $abmCompra->buscarCarrito($idUsuario);
    
    if($compra != null){
        $listaItems = $abmItem->buscar(['idcompra' => $compra->getIdCompra()]);
        foreach($listaItems as $item){
            $prod = $item->getObjProducto();
            $nuevoElem = [
                'idcompraitem' => $item->getIdCompraItem(),
                'idproducto' => $prod->getIdProducto(),
                'pronombre' => $prod->getProNombre(),
                'cantidad' => $item->getCiCantidad(),
                'precio' => $prod->getProPrecio() 
            ];
            array_push($resultado, $nuevoElem);
        }
    }
}
echo json_encode($resultado);
?>