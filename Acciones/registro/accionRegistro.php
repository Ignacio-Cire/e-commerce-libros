<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$abmUsuario = new abmUsuario();
$abmRol = new abmUsuarioRol();

// Verificar si usuario ya existe
$existe = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);

if(count($existe) == 0){
    // Crear usuario
    $datos['uspass'] = md5($datos['uspass']); // Encriptar MD5
    if($abmUsuario->alta($datos)){
        // Buscar el usuario recien creado pa obtener su ID
        $nuevoUser = $abmUsuario->buscar(['usnombre' => $datos['usnombre']]);
        
        if(count($nuevoUser) > 0){
            // Asignar Rol Cliente osea 2
            $objUser = $nuevoUser[0];
            $objRol = new Rol();
            $objRol->setear(2, null); 
            
            $abmRol->alta(['objUsuario' => $objUser, 'objRol' => $objRol]);
            
            // Redirigir al login
            header('Location: ../../Vista/login.php?msg=registrado');
            exit;
        }
    }
}

// Si falla
header('Location: ../../Vista/registro.php?error=1');
?>