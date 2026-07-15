<?php
require "fpdf.php";
pdf = new FPDF();
pdf->AddPage();
pdf->SetFont('Arial','B',16);
pdf->Cell(0,10,'Test PDF',0,1,'C');
$ref = new ReflectionClass($pdf);
$method = $ref->getMethod('buildPdf');
$method->setAccessible(true);
$content = $method->invoke($pdf);
if (preg_match('#<< /Length ([0-9]+) >>#', $content, $m)) {
    echo "declared=" . $m[1] . "\n";
} else {
    echo "no length\n";
}
if (preg_match('#stream\r\n(.*?)\r\nendstream#s', $content, $m2)) {
    echo "actual=" . strlen($m2[1]) . "\n";
} else {
    echo "no stream\n";
}
$pos = strpos($content, "xref\r\n");
echo "xrefpos=" . ($pos === false ? 'none' : $pos) . "\n";
file_put_contents('debugpdf2.txt', $content);
?>
