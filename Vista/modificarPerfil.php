<?php 
include_once('Estructura/cabecera.php'); 
if(!$session->activa()) { header('Location: login.php'); exit; }

// Obtenemos usuario actual de la sesioooon
$usuario = $session->getUsuario();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Mi Perfil</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($_GET['msg'])): ?>
                        <div class="alert alert-success">Datos actualizados correctamente.</div>
                    <?php endif; ?>
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger">Error al actualizar los datos.</div>
                    <?php endif; ?>

                    <form action="../Acciones/login/accionActualizarPerfil.php" method="post">
                        <input type="hidden" name="idusuario" value="<?php echo $usuario->getIdUsuario(); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Usuario (No editable)</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $usuario->getUsNombre(); ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="usmail" class="form-control" value="<?php echo $usuario->getUsMail(); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="uspass" class="form-control" placeholder="Dejar vacío para mantener la actual">
                            <div class="form-text">Solo completa esto si deseas cambiar tu contraseña.</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('Estructura/pie.php'); ?>