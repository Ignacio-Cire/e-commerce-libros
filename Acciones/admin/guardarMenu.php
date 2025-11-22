<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();
$res = ['exito'=>false, 'msg'=>'Error permisos'];

if($session->activa() && $session->getRolActivo() == 1){
    $abmMenu = new abmMenu();
    $abmMenuRol = new abmMenuRol();
    $exito = false;
    $idMenu = null;

    
    if($datos['accion'] == 'nuevo'){
        if($abmMenu->alta($datos)){
            
            $busqueda = $abmMenu->buscar(['menombre'=>$datos['menombre'], 'medescripcion'=>$datos['medescripcion']]);
            if(count($busqueda) > 0){
                
                $nuevoMenu = end($busqueda); 
                $idMenu = $nuevoMenu->getIdMenu();
                $exito = true;
            }
        }
    } elseif($datos['accion'] == 'editar'){
        if($abmMenu->modificacion($datos)){
            $idMenu = $datos['idmenu'];
            $exito = true;
        }
    }

    // catualizar roles
    if($exito && $idMenu != null){
        
        $rolesActuales = $abmMenuRol->buscar(['idmenu' => $idMenu]);
        foreach($rolesActuales as $mr){
            $mr->eliminar(); 
        }

        
        if(isset($datos['roles']) && is_array($datos['roles'])){
            foreach($datos['roles'] as $idRol){
                $objMenu = new Menu();
                $objMenu->setear($idMenu, null, null, null, null);
                
                $objRol = new Rol();
                $objRol->setear($idRol, null);
                
                $abmMenuRol->alta(['objMenu' => $objMenu, 'objRol' => $objRol]);
            }
        }
        $res = ['exito'=>true, 'msg'=>'Menú y roles actualizados'];
    } else {
        $res['msg'] = 'Error al guardar datos del menú';
    }
}
echo json_encode($res);
?>