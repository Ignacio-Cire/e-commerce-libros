<?php 
$titulo = "Gestión de Menús";
include_once('Estructura/cabecera.php'); 
$rol = $session->getRolActivo();


if(!$session->activa() || $rol != 1) { 
    echo "<div class='container mt-5'><div class='alert alert-danger'>Acceso denegado. Solo administradores.</div></div>";
    include_once('Estructura/pie.php');
    exit;
}
?>

<div class="container">
    <h3 class="mb-4">Administración de Menús</h3>

    <!-- Form de alta -->
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">Crear Nuevo Item de Menú</div>
        <div class="card-body">
            <form id="form-menu" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Nombre del Botón</label>
                    <input type="text" class="form-control" name="menombre" placeholder="Ej: Productos" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ruta del Archivo</label>
                    <input type="text" class="form-control" name="medescripcion" placeholder="Ej: /proyecto/Vista/prod.php" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">ID Padre</label>
                    <input type="number" class="form-control" name="idpadre" placeholder="Opcional">
                </div>
                
                
                <div class="col-md-2">
                    <label class="form-label d-block">Roles Permitidos</label>
                    <div id="container-roles-alta" class="border p-2 rounded bg-light" style="max-height: 80px; overflow-y: auto;">
                        <small class="text-muted">Cargando roles...</small>
                    </div>
                </div>

                <div class="col-md-2">
                     <label class="form-label d-block">&nbsp;</label>
                    <button type="button" onclick="crearMenu()" class="btn btn-success w-100">
                        <i class="fas fa-plus"></i> Crear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edición -->
    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-hover bg-white align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nombre</th>
                    <th>Ruta / Descripción</th>
                    <th>Roles Asignados</th>
                    <th style="width: 100px;">Estado</th>
                    <th style="width: 220px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-menus">
                <tr><td colspan="6" class="text-center p-3">Cargando menús...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var rolesDisponibles = [];

$(document).ready(function(){ 
    // Primero cargamos los roles disponibles para armar los checkboxes
    cargarRoles();
});

function cargarRoles(){
    $.post('/proyecto/Acciones/admin/tablaRoles.php', {}, function(res){
        try {
            rolesDisponibles = JSON.parse(res);
            // Generar checkboxes pa alta
            let html = '';
            if(rolesDisponibles.length > 0){
                rolesDisponibles.forEach(r => {
                    html += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="roles[]" value="${r.idrol}" id="rol_alta_${r.idrol}">
                        <label class="form-check-label small" for="rol_alta_${r.idrol}">${r.rodescripcion}</label>
                    </div>`;
                });
            } else {
                html = '<small class="text-danger">No hay roles cargados</small>';
            }
            $('#container-roles-alta').html(html);
            
            // Una vez tenemos roles, cargamos los menus
            cargarMenusAdmin();
        } catch(e) {
            console.error("Error parsing roles", e);
        }
    });
}

function cargarMenusAdmin(){
    $.post('/proyecto/Acciones/admin/listarMenuesCompleto.php', {}, function(res){
        let data = JSON.parse(res);
        let html = '';
        
        if(data.length > 0){
            data.forEach(m => {
                // checkboxes
                let rolesHtml = '<div class="d-flex flex-wrap gap-2">';
                rolesDisponibles.forEach(r => {
                    // Verificamos si el menu tiene este rol asignado
                    let checked = m.roles.includes(r.idrol) ? 'checked' : '';
                    rolesHtml += `
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="check_${m.idmenu}_${r.idrol}" value="${r.idrol}" ${checked}>
                        <label class="form-check-label small text-muted" for="check_${m.idmenu}_${r.idrol}">${r.rodescripcion}</label>
                    </div>`;
                });
                rolesHtml += '</div>';

                // Botones de estado
                let btnEstado = m.medeshabilitado == null ? 
                    `<button class="btn btn-sm btn-outline-danger" onclick="cambiarEstado(${m.idmenu}, 'baja')">
                        <i class="fas fa-ban"></i> Baja
                     </button>` :
                    `<button class="btn btn-sm btn-outline-success" onclick="cambiarEstado(${m.idmenu}, 'alta')">
                        <i class="fas fa-check"></i> Alta
                     </button>`;
                
                let estadoLbl = m.medeshabilitado == null ? 
                    '<span class="badge bg-success">Activo</span>' : 
                    '<span class="badge bg-danger">Baja</span>';

                html += `<tr>
                    <td class="text-center fw-bold">${m.idmenu}</td>
                    <td><input type="text" class="form-control form-control-sm" id="nom_${m.idmenu}" value="${m.menombre}"></td>
                    <td><input type="text" class="form-control form-control-sm" id="desc_${m.idmenu}" value="${m.medescripcion}"></td>
                    <td>${rolesHtml}</td>
                    <td class="text-center">${estadoLbl}</td>
                    <td>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-sm btn-primary" onclick="editarMenu(${m.idmenu})">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                            ${btnEstado}
                        </div>
                    </td>
                </tr>`;
            });
        } else {
            html = '<tr><td colspan="6" class="text-center p-4">No se encontraron menús.</td></tr>';
        }
        $('#tabla-menus').html(html);
    });
}

function crearMenu(){
    
    $.post('/proyecto/Acciones/admin/guardarMenu.php', $('#form-menu').serialize() + '&accion=nuevo', function(res){
        let response = JSON.parse(res);
        alert(response.msg);
        if(response.exito){
            cargarMenusAdmin();
            $('#form-menu')[0].reset();
        }
    });
}

function editarMenu(id){
    // tomo los roles roles seleccionados manualmente en la fila
    let rolesSeleccionados = [];
    $(`input[id^="check_${id}_"]:checked`).each(function(){
        rolesSeleccionados.push($(this).val());
    });

    $.post('/proyecto/Acciones/admin/guardarMenu.php', {
        accion: 'editar',
        idmenu: id,
        menombre: $('#nom_'+id).val(),
        medescripcion: $('#desc_'+id).val(),
        roles: rolesSeleccionados 
    }, function(res){
        let response = JSON.parse(res);
        alert(response.msg);
        cargarMenusAdmin();
    });
}

function cambiarEstado(id, tipo){
    if(confirm("¿Seguro desea cambiar el estado de este menú?")){
        $.post('/proyecto/Acciones/admin/eliminarMenu.php', {idmenu: id, accion: tipo}, function(res){
            cargarMenusAdmin();
        });
    }
}
</script>

<?php include_once('Estructura/pie.php'); ?>