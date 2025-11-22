<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$resultado = [];

if($session->activa() && isset($datos['idcompra'])){
    $abmItem = new abmCompraItem();
    
    
    $listaItems = $abmItem->buscar(['idcompra' => $datos['idcompra']]);
    
    foreach($listaItems as $item){
        $prod = $item->getObjProducto();
        $resultado[] = [
            'pronombre' => $prod->getProNombre(),
            'cantidad' => $item->getCiCantidad(),
            'precio' => 100, // Precio simulado
            'total' => $item->getCiCantidad() * 100
        ];
    }
}
echo json_encode($resultado);
?>