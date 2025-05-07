<?php
require('fpdf.php');

class PDF extends FPDF
{
function Header()
{
    $this->Image('https://images.squarespace-cdn.com/content/v1/6788d438405dc03eabea6c99/1737020479975-V3ZE94TGRALPK7D15TNY/image-asset.png',8,2,30);
    $this->SetFont('Arial','B',15);
    $this->Cell(70);
    $this->Cell(48,10,'Animal oso Panda',3,3,'C');
    $this->Ln(20);
}

function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','I',12);
    $this->Cell(0,10,'Av. 28 de Julio - Senati Sede - Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
    $pdf->Cell(0,10,'Imprimiendo linea numero '.$i,0,1);
$pdf->Output();
?>