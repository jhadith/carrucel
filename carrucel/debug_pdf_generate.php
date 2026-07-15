<?php
require_once 'fpdf.php';
class DebugFPDF extends FPDF {
    public function debugBuild() {
        return $this->buildPdf();
    }
}
$pdf = new DebugFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Test PDF',0,1,'C');
$raw = $pdf->debugBuild();
file_put_contents('pdf_debug_raw.txt', $raw);
file_put_contents('pdf_debug_info.txt', "length=" . strlen($raw) . "\nstartxref=" . strpos($raw, 'startxref') . "\n" . substr($raw, strpos($raw, 'xref'), 200));
?>
