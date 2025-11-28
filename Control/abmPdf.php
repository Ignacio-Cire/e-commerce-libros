<?php
class abmPdf {

    /**
     * Genera y descarga la factura en PDF
     * @param int $idCompra
     */
    public function generarFactura($idCompra){
        
        // 1. Rutas y Librerías (Usamos GLOBALS)
        $ROOT = $GLOBALS['ROOT'];
        $rutaFPDF = $ROOT . 'Utiles/librerias/fpdf/fpdf.php';
        $rutaQR = $ROOT . 'Utiles/librerias/phpqrcode/qrlib.php';

        // Verificamos que existan antes de romper todo
        if (!file_exists($rutaFPDF) || !file_exists($rutaQR)) {
            die("Error crítico: Faltan las librerías FPDF o QR en la carpeta Utiles.");
        }

        require_once($rutaFPDF);
        require_once($rutaQR);

        // 2. Buscamos los datos con los otros ABM
        $abmCompra = new abmCompra();
        $abmItem = new abmCompraItem();
        $abmUsuario = new abmUsuario();
        
        $datosCompra = $abmCompra->buscar(['idcompra' => $idCompra]);
        
        if(count($datosCompra) == 0){
            echo "La compra no existe.";
            return;
        }

        $objCompra = $datosCompra[0];
        $items = $abmItem->buscar(['idcompra' => $idCompra]);
        $usuario = $abmUsuario->buscar(['idusuario' => $objCompra->getIdUsuario()]);
        
        $nombreCliente = count($usuario) > 0 ? $usuario[0]->getUsNombre() : 'Cliente Genérico';
        $mailCliente = count($usuario) > 0 ? $usuario[0]->getUsMail() : '-';

        // 3. Generar imagen QR temporal
        $contenidoQR = "Compra #$idCompra - Cliente: $nombreCliente - Fecha: " . $objCompra->getCoFecha();
        $tempDir = $ROOT . 'Vista/img/qr/';
        
        // Crear carpeta si no existe (OJO CON PERMISOS EN LINUX)
        if(!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0775, true)) {
                die('Error: No se pudo crear la carpeta para el QR. Revisa permisos (chmod).');
            }
        }
        
        $fileNameQR = 'qr_compra_'.$idCompra.'.png';
        $pngAbsoluteFilePath = $tempDir . $fileNameQR;
        
        // Generamos el PNG del QR
        QRcode::png($contenidoQR, $pngAbsoluteFilePath, QR_ECLEVEL_L, 3, 2);

        // 4. Armar el PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Encabezado
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'FACTURA DE COMPRA',0,1,'C');
        $pdf->Ln(5);
        
        // Info Cliente
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,10,'Orden #: ' . $idCompra,0,1);
        $pdf->Cell(0,10,'Fecha: ' . $objCompra->getCoFecha(),0,1);
        
        $textoCliente = mb_convert_encoding('Cliente: ' . $nombreCliente . ' (' . $mailCliente . ')', 'ISO-8859-1', 'UTF-8');
        $pdf->Cell(0,10, $textoCliente, 0, 1);
        $pdf->Ln(10);
        
        // Tabla Items
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
        
        // Total Final
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(140,10,'TOTAL A PAGAR',1,0,'R');
        $pdf->Cell(40,10,'$'.$total,1,1,'C');
        $pdf->Ln(15);
        
        // Pegar QR
        $pdf->Cell(0,10,'Codigo de Verificacion:',0,1,'C');
        // Ajusta las coordenadas X, Y según necesites
        $pdf->Image($pngAbsoluteFilePath, 85, null, 40);
        
        // 5. Descargar
        $pdf->Output('D', 'Factura_Compra_'.$idCompra.'.pdf');
    }
}
?>