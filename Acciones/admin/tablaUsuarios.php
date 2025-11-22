<?php
include_once("../../configuracion.php");
$abmUsuario = new abmUsuario();
$abmUsuarioRol = new abmUsuarioRol();
$listaUsers = $abmUsuario->buscar(null);
$salida = [];

foreach($listaUsers as $user){
    // Buscar roles de este usuario
    $rolesUser = $abmUsuarioRol->buscar(['idusuario' => $user->getIdUsuario()]);
    $rolesDesc = [];
    foreach($rolesUser as $ur){
        $rolesDesc[] = ['idrol' => $ur->getObjRol()->getIdRol(), 'rodescripcion' => $ur->getObjRol()->getRoDescripcion()];
    }

    $salida[] = [
        'idusuario' => $user->getIdUsuario(),
        'usnombre' => $user->getUsNombre(),
        'usmail' => $user->getUsMail(),
        'roles' => $rolesDesc
    ];
}
echo json_encode($salida);
?>