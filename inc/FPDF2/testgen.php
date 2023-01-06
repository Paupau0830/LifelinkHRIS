<?php

require_once 'vendor/autoload.php';
//require_once('tcpdf/tcpdf.php');

require_once('fpdf/fpdf.php');

require_once('vendor/setasign/fpdi/fpdi.php');


$pdf = new FPDI();


$pagecount = $pdf->setSourceFile('ok.pdf');

for ($n = 1; $n <= $pagecount; $n++) {
$pdf->AddPage();


$tplIdx = $pdf->importPage($n);
$pdf->useTemplate($tplIdx);

$pdf->SetFont('Helvetica', 'B', 10);

$pdf->SetXY(150, 10);
$pdf->Write(0, "Appendix 1(new)");
}

$pdf->Output("output_sample_ken.pdf", "D");

?>
