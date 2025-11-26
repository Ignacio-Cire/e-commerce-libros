<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../../configuracion.php";
$datos = data_submitted();
$abmUsuario = new abmUsuario();

echo json_encode($abmUsuario->crearUsuario($datos));
?>