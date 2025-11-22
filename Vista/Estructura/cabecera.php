<?php
include_once(__DIR__."/../../configuracion.php");

$session = new Session();
if(!isset($titulo)) $titulo = "Carrito MVC";

$objUsuario = null;
if ($session->activa()) {
    $objUsuario = $session->getUsuario();
    if ($objUsuario == null) $session->cerrar();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
   
    <link href="/proyecto/Utiles/menu.css" rel="stylesheet">
    
   
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
   
    <a class="navbar-brand" href="../proyecto/index.php">Clavo Componentes</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav me-auto" id="menu-dinamico">
       
      </ul>
      <div class="d-flex">
        <?php if($session->activa() && $objUsuario != null): ?>
            <span class="text-white me-3 align-self-center">
                <?php echo $objUsuario->getUsNombre(); ?> 
                (Rol: <?php echo $session->getRolActivo(); ?>)
            </span>
            <a href="/proyecto/Acciones/login/cerrarSesion.php" class="btn btn-outline-danger btn-sm">Salir</a>
        <?php else: ?>
            <a href="/proyecto/Vista/login.php" class="btn btn-outline-light btn-sm">Ingresar</a>
        <?php endif; ?>
    </div>
    </div>
  </div>
</nav>

<input type="hidden" id="rol-activo" value="<?php echo $session->getRolActivo(); ?>">
<script src="/proyecto/Utiles/js/menu.js"></script>

<!-- Contenedor que crece para empujar el footer pa abajo de la pag -->
<div class="container mt-4 flex-grow-1">