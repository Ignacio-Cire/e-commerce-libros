$(document).ready(function(){
    $.ajax({
        url: '/proyecto/Acciones/producto/listarProdTienda.php',
        type: 'POST',
        data: { soloStock: 'true' }, 
        dataType: 'json',
        success: function(productos){
            let html = '';
            
            
            let productosDisponibles = productos.filter(prod => parseInt(prod.procantstock) > 0);

            if(productosDisponibles.length > 0){
                productosDisponibles.forEach(prod => {
                    html += `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="${prod.imagen}" class="card-img-top" style="height:200px; object-fit:cover;" alt="${prod.pronombre}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${prod.pronombre}</h5>
                                <p class="card-text text-muted small">${prod.prodetalle}</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="h4 mb-0 text-primary">$${prod.precio}</span>
                                        <span class="badge bg-success">Stock: ${prod.procantstock}</span>
                                    </div>
                                    <div class="input-group">
                                        <input type="number" id="cant_${prod.idproducto}" value="1" min="1" max="${prod.procantstock}" class="form-control">
                                        <button class="btn btn-success" onclick="agregarCarrito(${prod.idproducto}, ${prod.procantstock})">
                                            Agregar <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html = '<div class="col-12"><div class="alert alert-warning text-center">No hay productos en stock por el momento.</div></div>';
            }
            $('#lista-productos').html(html);
        },
        error: function() {
            $('#lista-productos').html('<div class="col-12"><div class="alert alert-danger text-center">Error cargando productos.</div></div>');
        }
    });
});

function agregarCarrito(idProd, stockMax){
    let cant = parseInt($('#cant_'+idProd).val());
    
    if(cant > stockMax){
        alert("No puedes pedir más productos de los que hay en stock (" + stockMax + ")");
        return;
    }
    if(cant < 1){
        alert("La cantidad mínima es 1");
        return;
    }

    $.post('/proyecto/Acciones/producto/agregarProdCarrito.php', {idproducto: idProd, cantidad: cant}, function(res){
        try { 
            let data = JSON.parse(res);
            alert(data.msg);
        } catch(e){ 
            console.log("Error parseando respuesta:", res); 
        }
    });
}