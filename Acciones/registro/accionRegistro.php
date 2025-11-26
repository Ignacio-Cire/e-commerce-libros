<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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