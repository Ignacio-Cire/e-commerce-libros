<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$abmUsuarioRol = new abmUsuarioRol();

// La acción solo dice: "Intenta dar de alta esto"
$exito = $abmUsuarioRol->alta($datos); 

if($exito){
    echo json_encode(['exito' => true, 'msg' => 'Rol asignado correctamente']);
} else {
    // El ABM se encargó de verificar, si devolvió false es que no se pudo
    echo json_encode(['exito' => false, 'msg' => 'No se pudo asignar (quizás ya tiene el rol)']);
}
?>