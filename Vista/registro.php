<?php include_once('Estructura/cabecera.php'); ?>
<div class="d-flex justify-content-center mt-5">
    <div class="card p-4 shadow" style="width:400px">
        <h3 class="text-center">Registro</h3>
        <form action="../Acciones/registro/accionRegistro.php" method="post">
            <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="usnombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="usmail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="uspass" class="form-control" required>
            </div>
            <button class="btn btn-success w-100">Registrarse</button>
        </form>
        <a href="login.php" class="d-block text-center mt-3">Ya tengo cuenta</a>
    </div>
</div>
<?php include_once('Estructura/pie.php'); ?>