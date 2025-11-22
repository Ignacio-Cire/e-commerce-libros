<?php
include_once("../../configuracion.php");
$session = new Session();
$salida = [];

if($session->activa() && $session->getRolActivo() == 1){
    
    $menus = Menu::listar("");
    $abmMenuRol = new abmMenuRol();

    foreach($menus as $m){
       
        $rolesAsignados = $abmMenuRol->buscar(['idmenu' => $m->getIdMenu()]);
        $listaRolesID = [];
        
        foreach($rolesAsignados as $mr){
            $listaRolesID[] = $mr->getObjRol()->getIdRol();
        }

        $salida[] = [
            'idmenu' => $m->getIdMenu(),
            'menombre' => $m->getMeNombre(),
            'medescripcion' => $m->getMeDescripcion(),
            'idpadre' => $m->getIdPadre(),
            'medeshabilitado' => $m->getMeDeshabilitado(),
            'roles' => $listaRolesID 
        ];
    }
}
echo json_encode($salida);
?>