<?php
require('japanese.php');

$pdf=new PDF_Japanese();
$pdf->AddSJISFont();
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont('SJIS','',18);
$pdf->Write(8,'9ヶ月の公開テストを経てPHP 3.0は1998年6月に公式にリリースされました。');
$pdf->Output();
?>
