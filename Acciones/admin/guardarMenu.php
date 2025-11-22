<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$res = ['exito'=>false, 'msg'=>'Error permisos'];

if($session->activa() && $session->getRolActivo() == 1){
    $abmMenu = new abmMenu();
    
    if($datos['accion'] == 'nuevo'){
        // El alta ahora se encarga de crear y asignar roles si vienen
        if($abmMenu->alta($datos)){ 
            $res = ['exito'=>true, 'msg'=>'Menú creado correctamente'];
        }
    } elseif($datos['accion'] == 'editar'){
        // La modificación se encarga de actualizar datos y roles
        if($abmMenu->modificacion($datos)){
            $res = ['exito'=>true, 'msg'=>'Menú modificado correctamente'];
        }
    }
    
    if(!$res['exito']){
        $res['msg'] = 'Error al guardar datos del menú';
    }
}
echo json_encode($res);
?>
