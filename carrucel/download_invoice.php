<?php
require_once 'servicios.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    exit('ID de factura inválido.');
}

$id = intval($_GET['id']);
$conexion = servicios::conectar();
$query = "SELECT * FROM compra WHERE id = $id LIMIT 1";
$result = mysqli_query($conexion, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    exit('Factura no encontrada.');
}

$compra = mysqli_fetch_assoc($result);
$cedula = $compra['cedula'];
$total = number_format($compra['total'], 2, '.', ',');

// Librería FPDF: requiere el archivo fpdf.php en el proyecto.
require_once 'fpdf.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Factura de Compra', 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Empresa: Tu Empresa S.A.', 0, 1);
$pdf->Cell(0, 8, 'Dirección: Avenida Principal 123', 0, 1);
$pdf->Cell(0, 8, 'Teléfono: (01) 2345 6789', 0, 1);
$pdf->Ln(8);
$pdf->Cell(0, 8, 'Cédula cliente: ' . $cedula, 0, 1);
$pdf->Cell(0, 8, 'Total: $' . $total, 0, 1);

$pdf->Output('D', 'factura_' . $id . '.pdf');
