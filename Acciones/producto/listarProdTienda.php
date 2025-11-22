<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$abmProducto = new abmProducto();
$lista = $abmProducto->buscar(null);
$arregloSalida = [];

foreach($lista as $prod){
    $stock = (int)$prod->getProCantStock();

    
    if(isset($datos['soloStock']) && $datos['soloStock'] == 'true'){
        if($stock <= 0) {
            continue; 
        }
    }

   
    $img = $prod->getProImagen();
    if($img == null || $img == "") {
        $img = "https://placehold.co/600x400?text=" . urlencode($prod->getProNombre());
    }

    $elem = [
        'idproducto' => $prod->getIdProducto(),
        'pronombre' => $prod->getProNombre(),
        'prodetalle' => $prod->getProDetalle(),
        'procantstock' => $stock,
        'precio' => $prod->getProPrecio(),
        'imagen' => $img
    ];
    array_push($arregloSalida, $elem);
}
echo json_encode($arregloSalida);
?>