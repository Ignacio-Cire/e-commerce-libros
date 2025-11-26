<?php
header('Content-Type: text/html; charset=utf-8');
header ("Cache-Control: no-cache, must-revalidate ");



/////////////////////////////
// CONFIGURACION APP //
/////////////////////////////

$PROYECTO = 'proyecto';

// Variable que almacena el directorio del proyecto
$ROOT = __DIR__ . "/";

// Guardamos ROOT en GLOBALS para acceder desde el autoloader sin session_start
$GLOBALS['ROOT'] = $ROOT;
include_once($ROOT.'Utiles/funciones.php');

// Variable que define la pagina de autenticacion del proyecto
$INICIO = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Vista/login.php";

// variable que define la pagina principal del proyecto (menu principal)
$PRINCIPAL = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/Vista/index.php";

$_SESSION['ROOT']=$ROOT;
