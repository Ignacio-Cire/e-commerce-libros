<?php 
function data_submitted() {
    $_AAux= array();
    if (!empty($_POST))
        $_AAux =$_POST;
    else
        if(!empty($_GET)) {
            $_AAux =$_GET;
        }
    if (count($_AAux)){
        foreach ($_AAux as $indice => $valor) {
            if ($valor=="")
                $_AAux[$indice] = 'null' ;
        }
    }
    return $_AAux;
}

spl_autoload_register(function ($clase) {
    // Carga automática de clases Control y Modelo
    // Usamos GLOBALS por si la sesion aun no arranco
    if(isset($GLOBALS['ROOT'])){
        $root = $GLOBALS['ROOT'];
    } else {
        $root = $_SESSION['ROOT'];
    }
    
    $directorys = array(
        $root.'Modelo/',
        $root.'Control/',
    );
    foreach($directorys as $directory){
        if(file_exists($directory.$clase.'.php')){
            require_once($directory.$clase.'.php');
            return;
        }
    }
});
?>