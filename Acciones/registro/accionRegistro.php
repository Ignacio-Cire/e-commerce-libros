<?php


include_once("../../configuracion.php");

$datos = data_submitted();

// Instanciamos solo el ABM de Usuario
$abmUsuario = new abmUsuario();

if($abmUsuario->alta($datos)){
    header('Location: ../Vista/login.php?msg=registrado');
    exit;
} else {
    header('Location: ../Vista/registro.php?error=1');
    exit;
}
?>