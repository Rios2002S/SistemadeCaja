<?php
// Incluir la librería TCPDF
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Incluir archivo de consultas
require_once('../bd/consultas/consultas.php');

// Asegúrate de que la sesión esté iniciada y puedes acceder a los valores de la sesión
session_start(); 

// Obtener la sucursal del usuario desde la sesión
$sucursal = isset($_SESSION['sucursal_asignada']) ? $_SESSION['sucursal_asignada'] : 'Sucursal Desconocida';
$nombre_persona = isset($_SESSION['nombre_persona']) ? $_SESSION['nombre_persona'] : 'Usuario Desconocido';

// Crear un nuevo objeto TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GRUPO MULTICOMP CANDELARIA');
$pdf->SetTitle('Reporte de Retiros');
$pdf->SetSubject('Historial de Retiros');
$pdf->SetKeywords('retiros, historial, reporte');

// Establecer márgenes
$pdf->SetMargins(20, 30, 20); // Márgenes más amplios para la parte superior e inferior

// Establecer la distancia de la cabecera desde el borde superior
$pdf->SetHeaderMargin(15);

// Establecer la cabecera
$pdf->SetHeaderData('', 0, 'Reporte de Retiros', 'Generado por: ' . $sucursal);

// Establecer fuente
$pdf->SetFont('helvetica', '', 12);

// Recorrer los resultados y agregar información por cada retiro
while ($retiro = $resultadoRetiros->fetch_assoc()) {
    // Agregar una nueva página antes de cada retiro
    $pdf->AddPage();
    
    // Título del reporte
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Historial de Retiros', 0, 1, 'C');

    // Fecha del reporte (Fila superior)
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12); // Establecer zona horaria
    date_default_timezone_set('America/El_Salvador');

    // Obtener fecha y hora del sistema
    $pdf->Cell(0, 10, 'Fecha del Reporte: ' . $retiro['fecha'], 0, 1, 'L');

    // Agregar un salto de línea
    $pdf->Ln(10);

    // ID Retiro y Monto en la misma línea
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'ID Retiro: ' . $retiro['id'], 1, 0, 'L');  // Celda para el ID
    $pdf->Cell(0, 10, 'Monto: $' . number_format($retiro['monto'], 2), 1, 1, 'L');  // Celda para el Monto

    // Fecha del Retiro
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(60, 10, 'Fecha:', 1, 0, 'L');
    $pdf->Cell(0, 10, $retiro['fecha'], 1, 1, 'L');

    // Usuario
    $pdf->Cell(60, 10, 'Usuario:', 1, 0, 'L');
    $pdf->Cell(0, 10, $nombre_persona, 1, 1, 'L');

    // Calcular el alto necesario para la celda de Observaciones
    $altoObservaciones = $pdf->getStringHeight(0, $retiro['observaciones']);  // Calcular el alto de las observaciones

    // **Nuevo cambio**: Aquí usamos MultiCell para que ambas celdas tengan altura ajustable
    $pdf->Cell(60, $altoObservaciones, 'Observaciones:', 1, 0, 'L');  // Título con el alto dinámico
    $pdf->MultiCell(0, $altoObservaciones, $retiro['observaciones'], 1, 'L');

    // Agregar el pie de página con el texto fijo
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'Retirado por Ing. Walter Ramos', 0, 1, 'C');
}

// Generar el PDF
$pdf->Output('reporte_retiros.pdf', 'I'); // 'I' para mostrar en el navegador, 'D' para descargar

?>
