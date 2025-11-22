<?php include_once('Estructura/cabecera.php'); ?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>
        
       
        <?php 
        if(isset($_GET['msg']) && $_GET['msg'] == 'registrado'){
            echo '<div class="alert alert-success text-center">¡Registro exitoso!<br>Ahora puedes iniciar sesión.</div>';
        }
        if(isset($_GET['error'])){
            echo '<div class="alert alert-danger text-center">Usuario o contraseña incorrectos.</div>';
        }
        ?>

        <form action="../Acciones/login/accionLogin.php" method="post">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usnombre" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="uspass" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>

        <hr>
        
        <!-- ENLACE AL REGISTRO -->
        <div class="text-center">
            <p class="mb-2">¿No tienes una cuenta?</p>
            <a href="registro.php" class="btn btn-outline-success w-100">Registrarse</a>
        </div>
    </div>
</div>

<?php include_once('Estructura/pie.php'); ?>