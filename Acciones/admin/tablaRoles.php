<?php
include_once("../../configuracion.php");
$session = new Session();


if($session->activa() && $session->getRolActivo() == 1){
    
    $abmRol = new abmRol();
    $listaRoles = $abmRol->buscar(null);
    
    $salida = [];
    
    foreach($listaRoles as $rol){
        $salida[] = [
            'idrol' => $rol->getIdRol(),
            'rodescripcion' => $rol->getRoDescripcion()
        ];
    }
    echo json_encode($salida);
} else {
    echo json_encode([]);
}
?>