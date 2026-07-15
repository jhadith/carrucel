<?php
$_GET['id']=1;
ob_start();
include 'download_invoice.php';
$data = ob_get_clean();
file_put_contents('pdf_download_output.bin', $data);
?>
