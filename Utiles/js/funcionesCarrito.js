$(document).ready(function(){
    cargarCarrito();
});

function cargarCarrito(){
    $.ajax({
        
        url: '/proyecto/Acciones/compra/listadoProdCarrito.php',
        type: 'POST',
        dataType: 'json',
        success: function(items){
            let html = '';
            let total = 0;
            if(items.length > 0){
                items.forEach(item => {
                    // (Asegurarse de que item.precio venga del PHP, sino usar simulado) esto es asi porque es viejo, cuando estabamos probando sin todavia alterar la base de datos, pero todavia sirve, nunca va a mostrar ese 100 xd
                    let precio = item.precio || 100; 
                    let subtotal = item.cantidad * precio;
                    total += subtotal;
                    
                    html += `
                    <tr>
                        <td class="align-middle">${item.pronombre}</td>
                        <td class="align-middle">${item.cantidad}</td>
                        <td class="align-middle">$${precio}</td>
                        <td class="align-middle">
                            <button class="btn btn-danger btn-sm" onclick="eliminarItem(${item.idcompraitem})">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                });
                html += `<tr class="table-secondary">
                            <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                            <td><strong>$${total}</strong></td>
                         </tr>`;
            } else {
                html = '<tr><td colspan="4" class="text-center p-4">El carrito está vacío</td></tr>';
            }
            $('#cuerpo-carrito').html(html);
        },
        error: function(e) {
            console.error("Error cargando carrito:", e);
            $('#cuerpo-carrito').html('<tr><td colspan="4" class="text-danger text-center">Error de conexión con el servidor.</td></tr>');
        }
    });
}

function eliminarItem(idItem){
    if(confirm('¿Seguro desea eliminar este producto?')){
        $.post('/proyecto/Acciones/producto/eliminarProdCarrito.php', {idcompraitem: idItem}, function(res){
            try {
                let data = JSON.parse(res);
                if(data.exito){
                    cargarCarrito();
                } else {
                    alert(data.msg);
                }
            } catch(e) { console.error(e); }
        });
    }
}

function vaciarCarrito(){
    if(confirm('¿Seguro desea vaciar todo el carrito?')){
        $.post('/proyecto/Acciones/compra/vaciarCarrito.php', {}, function(res){
            cargarCarrito();
        });
    }
}

function finalizarCompra(){
    if(confirm('¿Confirmar compra?')){
        $.post('/proyecto/Acciones/compra/ejecutarCompraCarrito.php', {}, function(res){
            try {
                let data = JSON.parse(res);
                alert(data.msg);
                if(data.exito){
                   
                    window.location.href = '/proyecto/Vista/listaCompras.php';
                }
            } catch(e) { 
                console.error(e); 
                alert("Error procesando la respuesta de compra.");
            }
        });
    }
}