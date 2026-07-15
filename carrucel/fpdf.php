<?php
// FPDF compatible minimal library for simple PDF generation.
// Esta implementación produce un PDF básico con texto plano para facturas simples.

class FPDF {
    protected $lines = [];
    protected $fontSize = 12;
    protected $lineHeight = 14;

    public function AddPage() {
        $this->lines = [];
    }

    public function SetFont($family, $style = '', $size = 0) {
        if ($size > 0) {
            $this->fontSize = $size;
            $this->lineHeight = intval($size * 1.4);
        }
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        $this->lines[] = $txt;
        if ($ln > 0) {
            $this->Ln($h > 0 ? $h : $this->lineHeight);
        }
    }

    public function Ln($h = null) {
        $this->lines[] = '';
    }

    public function Output($dest = '', $name = '') {
        $pdf = $this->buildPdf();
        if ($dest === 'D') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');
            echo $pdf;
            exit;
        }
        if ($dest === 'F') {
            file_put_contents($name, $pdf);
            return;
        }
        echo $pdf;
    }

    protected function buildPdf() {
        $contentStream = $this->buildContentStream();
        $length = strlen($contentStream);

        $objects = [];
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        $objects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        $objects[] = "5 0 obj\n<< /Length $length >>\nstream\n$contentStream\nendstream\nendobj\n";

        $pdf = "%PDF-1.4\r\n%\xE2\xE3\xCF\xD3\r\n";
        $positions = [];
        foreach ($objects as $object) {
            $positions[] = strlen($pdf);
            $pdf .= $object;
        }

        $xrefPos = strlen($pdf);
        $pdf .= "xref\r\n0 " . (count($objects) + 1) . "\r\n";
        $pdf .= "0000000000 65535 f\r\n";
        foreach ($positions as $pos) {
            $pdf .= sprintf("%010d 00000 n\r\n", $pos);
        }
        $pdf .= "trailer\r\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\r\nstartxref\r\n$xrefPos\r\n%%EOF";

        return $pdf;
    }

    protected function buildContentStream() {
        $stream = "BT\n/F1 " . $this->fontSize . " Tf\n";
        $y = 780;
        foreach ($this->lines as $line) {
            $escaped = $this->escapeText($line);
            $stream .= sprintf("1 0 0 1 50 %d Tm\r\n", $y);
            $stream .= '(' . $escaped . ") Tj\r\n";
            $y -= $this->lineHeight;
        }
        $stream .= "ET";
        return $stream;
    }

    protected function escapeText($text) {
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace('(', '\\(', $text);
        return str_replace(')', '\\)', $text);
    }
}
