<?php 
$titulo = "Gestión de Usuarios";
include_once('Estructura/cabecera.php'); 
if(!$session->activa() || $session->getRolActivo() != 1) { 
    echo "<div class='alert alert-danger'>Acceso denegado. Requiere Rol Administrador (1).</div>";
    include_once('Estructura/pie.php');
    exit;
}
?>

<h3>Gestión de Usuarios</h3>
<div class="card p-3">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Roles Actuales</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-usuarios">
            <!-- Se llena con AJAXxxx -->
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    cargarUsuarios();
});

function cargarUsuarios(){
    $.ajax({
        url: '../Acciones/admin/tablaUsuarios.php',
        type: 'POST',
        dataType: 'json',
        success: function(data){
            let html = '';
            data.forEach(user => {
                let rolesHtml = '';
                user.roles.forEach(r => { rolesHtml += `<span class="badge bg-secondary me-1">${r.rodescripcion}</span>`; });
                
                html += `<tr>
                    <td>${user.idusuario}</td>
                    <td>${user.usnombre}</td>
                    <td>${user.usmail}</td>
                    <td>${rolesHtml}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="agregarRol(${user.idusuario}, 1)">+Admin</button>
                        <button class="btn btn-sm btn-warning" onclick="agregarRol(${user.idusuario}, 3)">+Deposito</button>
                         <button class="btn btn-sm btn-info" onclick="agregarRol(${user.idusuario}, 2)">+Cliente</button>
                    </td>
                </tr>`;
            });
            $('#tabla-usuarios').html(html);
        }
    });
}

function agregarRol(idUser, idRol){
    if(confirm("¿Asignar este rol al usuario?")){
        $.post('../Acciones/admin/actualizarRol.php', {idusuario: idUser, idrol: idRol}, function(res){
            let data = JSON.parse(res);
            alert(data.msg);
            cargarUsuarios();
        });
    }
}
</script>

<?php include_once('Estructura/pie.php'); ?>