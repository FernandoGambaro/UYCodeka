<?php
/*No usar*/
class PDF extends FPDF
{
/*/Cabecera de pgina*/
function Header()
{
global $title; 
global $subtitle;
global $codcliente;	
global $ww;
global $header;

   /*/Logo*/
    $this->Image('./logo/logo.jpg',10,8,190);
    $this->Ln();	


    
}

/*/Pie de pgina*/
function Footer()
{
   $this->SetFont('Arial','',6);
	$this->SetY(-12);
	$this->Cell(0,10,'GESER WEB - MCC RUT 214355250015',0,0,'L');
	$this->SetY(-12);
   $this->Cell(0,10,'Pagina '.$this->PageNo()."/{nb}",0,0,'C');	
}

}
?>
