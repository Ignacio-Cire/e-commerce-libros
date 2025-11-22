<?php
include_once("../../configuracion.php");
$session = new Session();
$listaSalida = [];

if($session->activa()){
    $idRol = $session->getRolActivo();
   
    
    if($idRol == null){
       
        $idRol = 1; 
    }

    $abmMenu = new abmMenu();
    $listaMenues = $abmMenu->obtenerMenuPorRol($idRol);
    
    foreach($listaMenues as $menu){
        $nuevoElem = [
            'idmenu' => $menu->getIdMenu(),
            'menombre' => $menu->getMeNombre(),
            'medescripcion' => $menu->getMeDescripcion(),
            'idpadre' => $menu->getIdPadre()
        ];
        array_push($listaSalida, $nuevoElem);
    }
}
echo json_encode($listaSalida);
?>