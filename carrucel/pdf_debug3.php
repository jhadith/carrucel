<?php
require "fpdf.php";
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Test PDF',0,1,'C');
$content = (function() use ($pdf) {
    $ref = new ReflectionClass($pdf);
    $method = $ref->getMethod('buildPdf');
    $method->setAccessible(true);
    return $method->invoke($pdf);
})();
file_put_contents('debugpdf.txt', $content);
echo "content written\n";
?>
