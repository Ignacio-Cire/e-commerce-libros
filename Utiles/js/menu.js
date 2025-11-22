$(document).ready(function() {
    
    $.ajax({
        url: '/proyecto/Acciones/admin/tablaMenues.php', 
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            var menuHtml = '';
            $.each(data, function(i, item) {
                menuHtml += '<li class="nav-item"><a class="nav-link" href="'+item.medescripcion+'">'+item.menombre+'</a></li>';
            });
            $('#menu-dinamico').html(menuHtml);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error cargando menú:", textStatus, errorThrown);
        }
    });
});