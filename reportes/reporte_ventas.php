<?php
// Incluir la librería TCPDF
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Incluir archivo de consultas
require_once('../bd/consultas/consultas.php');

// Crear una clase personalizada para TCPDF
class CustomPDF extends TCPDF {
    // Personalizar el encabezado
    public function Header() {
        // Logo
        $this->Image('../img/1.jpg', 175, 8, 25); // Ajusta la posición y el tamaño del logo
        
        // Título
        $this->SetFont('Helvetica', 'B', 16);
        $this->Cell(0, 15, 'Reporte de Ventas', 0, 1, 'L');
        $this->SetFont('Helvetica', 12);
        $this->Cell(0, 8, 'Sucursal Candelaria: Pastorsito´s Market', 0, 1, 'L');
        
        // Espacio adicional debajo del título
        $this->Ln(7);
    }

    // Personalizar el pie de página
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 10);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Crear una instancia de TCPDF en orientación vertical
$pdf = new CustomPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alberto');
$pdf->SetTitle('Reporte de Ventas');
$pdf->SetSubject('Reporte Detallado de Ventas');
$pdf->SetKeywords('TCPDF, PDF, reporte, ventas');

// Establecer márgenes
$pdf->SetMargins(15, 50, 15); // Ajusta el margen superior para evitar la superposición
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);

// Agregar una página
$pdf->AddPage();

// Establecer la fuente para el contenido
$pdf->SetFont('Helvetica', '', 12);

// Variables para almacenar la última ID de venta y no repetir la información
$lastVentaId = null;

// Obtener las ventas y mostrar los detalles
while ($venta = $resultVentasPDF->fetch_assoc()) {
    // Si la venta cambia (compara por id_venta), imprime los encabezados de la venta
    if ($venta['id_venta'] != $lastVentaId) {
        // Si hay una venta anterior, podemos agregar un salto de línea
        if ($lastVentaId !== null) {
            $pdf->Ln(5); // Salto de línea entre ventas
        }

        // Título de la venta
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Venta ID: ' . $venta['id_venta'], 0, 1, 'L');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 10, 'Fecha: ' . $venta['fecha_venta'], 0, 1, 'L');
        $pdf->Cell(0, 10, 'Vendedor: ' . $venta['vendedor'], 0, 1, 'L');
        $pdf->Cell(0, 10, 'Total: $' . number_format($venta['total'], 2), 0, 1, 'L');
        
        // Encabezados de la tabla para los productos vendidos
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(50, 10, 'Producto', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Precio', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

        // Actualizar el ID de la venta actual
        $lastVentaId = $venta['id_venta'];
    }

    // Detalles de los productos vendidos
    $pdf->SetFont('Helvetica', '', 10);
    $pdf->Cell(50, 10, $venta['producto'], 1, 0, 'L');
    $pdf->Cell(30, 10, $venta['cantidad'], 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($venta['precio'], 2), 1, 0, 'C');
    $pdf->Cell(40, 10, '$' . number_format($venta['subtotal'], 2), 1, 1, 'C');
}

// Salvar el archivo PDF en el servidor o enviarlo al navegador
$pdf->Output('reporte_ventas.pdf', 'I');
?>
