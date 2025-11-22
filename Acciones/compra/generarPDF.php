<?php
include_once("../../configuracion.php");
$datos = data_submitted();
$session = new Session();



// Verificar extension GD 
if (!extension_loaded('gd')) {
    echo "<div style='font-family:sans-serif; color:#721c24; background-color:#f8d7da; padding:20px; border:1px solid #f5c6cb; margin:20px; border-radius:5px;'>
            <h3> ERROR CRÍTICO: Librería GD desactivada</h3>
            
          </div>";
    exit;
}

// Verificar Libreraas Externas
$rutaFPDF = $ROOT . 'Utiles/librerias/fpdf/fpdf.php';
$rutaQR = $ROOT . 'Utiles/librerias/phpqrcode/qrlib.php';




require_once($rutaFPDF);
require_once($rutaQR);


if($session->activa() && isset($datos['idcompra'])){
    $idCompra = $datos['idcompra'];
    
    $abmCompra = new abmCompra();
    $abmItem = new abmCompraItem();
    $abmUsuario = new abmUsuario();
    
    $compra = $abmCompra->buscar(['idcompra' => $idCompra]);
    
    if(count($compra) > 0){
        $objCompra = $compra[0];
        $items = $abmItem->buscar(['idcompra' => $idCompra]);
        
        $usuario = $abmUsuario->buscar(['idusuario' => $objCompra->getIdUsuario()]);
        $nombreCliente = count($usuario) > 0 ? $usuario[0]->getUsNombre() : 'Cliente';
        $mailCliente = count($usuario) > 0 ? $usuario[0]->getUsMail() : '-';

        
        $contenidoQR = "Compra #$idCompra - Cliente: $nombreCliente - Fecha: " . $objCompra->getCoFecha();
        $tempDir = $ROOT . 'Vista/img/qr/'; 
        
        
        if(!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        
        $fileNameQR = 'qr_compra_'.$idCompra.'.png';
        $pngAbsoluteFilePath = $tempDir . $fileNameQR;
        
       
        QRcode::png($contenidoQR, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3, 2);
        
      
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        
        $pdf->Cell(0,10,'FACTURA DE COMPRA',0,1,'C');
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,10,'Orden #: ' . $idCompra,0,1);
        $pdf->Cell(0,10,'Fecha: ' . $objCompra->getCoFecha(),0,1);
        
        
        $textoCliente = mb_convert_encoding('Cliente: ' . $nombreCliente . ' (' . $mailCliente . ')', 'ISO-8859-1', 'UTF-8');
        $pdf->Cell(0,10, $textoCliente, 0, 1);
        $pdf->Ln(10);
        
        // Tabla
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(80,10,'Producto',1);
        $pdf->Cell(30,10,'Precio',1);
        $pdf->Cell(30,10,'Cant',1);
        $pdf->Cell(40,10,'Subtotal',1);
        $pdf->Ln();
        
        $pdf->SetFont('Arial','',12);
        $total = 0;
        
        foreach($items as $item){
            $prod = $item->getObjProducto();
            $precio = $prod->getProPrecio();
            $cant = $item->getCiCantidad();
            $sub = $precio * $cant;
            $total += $sub;
            
            $nombreProd = mb_convert_encoding($prod->getProNombre(), 'ISO-8859-1', 'UTF-8');
            
            $pdf->Cell(80,10, $nombreProd, 1);
            $pdf->Cell(30,10, '$'.$precio, 1);
            $pdf->Cell(30,10, $cant, 1);
            $pdf->Cell(40,10, '$'.$sub, 1);
            $pdf->Ln();
        }
        
        // Total
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(140,10,'TOTAL A PAGAR',1,0,'R');
        $pdf->Cell(40,10,'$'.$total,1,1,'C');
        
        $pdf->Ln(15);
        
        // Insertar QR debajox
        $pdf->Cell(0,10,'Codigo de Verificacion:',0,1,'C');
        $pdf->Image($pngAbsoluteFilePath, 85, null, 40);
        
        $pdf->Output('D', 'Factura_Compra_'.$idCompra.'.pdf');
        
    } else {
        echo "Compra no encontrada";
    }
} else {
    echo "Acceso denegado o sesión expirada.";
}
?>