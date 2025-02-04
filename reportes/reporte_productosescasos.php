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
        $this->Image('../img/1.jpg', 255, 8, 25); // Ajusta la posición y el tamaño del logo
        
        // Título
        $this->SetFont('Helvetica', 'B', 16);
        $this->Cell(0, 15, 'Reporte de Productos con Menor Stock', 0, 1, 'L');
        $this->SetFont('Helvetica', 12);
        $this->Cell(0, 8, 'Sucursal Candelaria: Pastorsito´s Market', 0, 1, 'L');
        
        // Espacio adicional debajo del título
        $this->Ln(7);

        // Encabezados de la tabla
        $this->SetFillColor(200, 220, 255);
        $this->Cell(110, 10, 'Producto', 1, 0, 'C', 1);
        $this->Cell(110, 10, 'Stock', 1, 1, 'C', 1);
    }

    // Personalizar el pie de página
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 10);
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Crear una instancia de TCPDF
$pdf = new CustomPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Configuración del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alberto');
$pdf->SetTitle('Reporte de Productos');
$pdf->SetSubject('Reporte Detallado de Productos en Stock');
$pdf->SetKeywords('TCPDF, PDF, reporte, productos, stock');

// Establecer márgenes
$pdf->SetMargins(30, 50, 15); // Ajusta el margen superior para evitar la superposición
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);

// Agregar una página
$pdf->AddPage();

// Establecer la fuente para el contenido
$pdf->SetFont('Helvetica', '', 12);

// Obtener los datos de los productos con menor stock y agregarlos a la tabla
while ($producto = $resultTodosProductosMenorStock->fetch_assoc()) {
    $pdf->Cell(110, 10, $producto['nombre'], 1, 0, 'L');
    $pdf->Cell(110, 10, $producto['cantidad_en_stock'], 1, 1, 'C');
}

// Salvar el archivo PDF en el servidor o enviarlo al navegador
$pdf->Output('reporte_productos_menor_stock.pdf', 'I');
?>