<?php
require "fpdf.php";
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Test PDF',0,1,'C');
$pdf->Output('F','pdf_inspect.pdf');
?>
