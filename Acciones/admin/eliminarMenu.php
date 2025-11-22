<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$res = ['exito'=>false];

if($session->activa() && $session->getRolActivo() == 1 && isset($datos['idmenu'])){
    $abmMenu = new abmMenu();
    
    if($datos['accion'] == 'baja'){
        if($abmMenu->baja($datos)){ $res['exito']=true; }
    } elseif($datos['accion'] == 'alta'){
        if($abmMenu->habilitar($datos)){ $res['exito']=true; }
    }
}
echo json_encode($res);
?>