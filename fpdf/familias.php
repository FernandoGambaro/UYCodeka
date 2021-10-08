<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php"); 

$title="Listado de Familias";

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);


//Buscamos y listamos las familias

$codfamilia=$_GET["codfamilia"];
$nombre=$_GET["nombre"];

$where="1=1";
if ($codfamilia <> "") { $where.=" AND codfamilia='$codfamilia'"; }
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."%'"; }


//Ttulos de las columnas
$header=array('Cod. Familia','Nombre');

//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera
$pdf->SetX(60);
$w=array(20,60);
for($i=0;$i<count($header);$i++)
	$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
$pdf->Ln();
$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM familias WHERE borrado=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
while ($contador < mysqli_num_rows($res_resultado)) {
	$pdf->SetX(60);
	$pdf->Cell($w[0],5,mysqli_result($res_resultado, $contador, "codfamilia"),'LRTB',0,'C');
	$pdf->Cell($w[1],5,mysqli_result($res_resultado, $contador, "nombre"),'LRTB',0,'C');
	$pdf->Ln();
	$contador++;
};
			
$nombre='../copias/familias.pdf';	
		
$pdf->Output($nombre,'I');
?> 
