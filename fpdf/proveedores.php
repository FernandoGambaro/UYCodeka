<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

$title="Listado de Proveedores";
$header=array('Nombre','RUT','Dirección','Localidad','Teléfono');
$ww=array(50,22,60,40,20);


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

$codproveedor=$_GET["codproveedor"];
$nombre=$_GET["nombre"];
$nif=$_GET["nif"];
$codprovincia=$_GET["cboProvincias"];
$localidad=$_GET["localidad"];
$telefono=$_GET["telefono"];

$where="1=1";

if ($codproveedor <> "") { $where.=" AND codproveedor='$codproveedor'"; }
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."%'"; }
if ($nif <> "") { $where.=" AND nif like '%".$nif."%'"; }
if ($codprovincia > "0") { $where.=" AND codprovincia='$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }


//Ttulos de las columnas

//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera

$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM proveedores WHERE borrado=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
while ($contador < mysqli_num_rows($res_resultado)) {
	$pdf->Cell($ww[0],5,mysqli_result($res_resultado, $contador, "nombre"),'LRTB',0,'L');
	$pdf->Cell($ww[1],5,mysqli_result($res_resultado, $contador, "nif"),'LRTB',0,'C');
	$pdf->Cell($ww[2],5,mysqli_result($res_resultado, $contador, "direccion"),'LRTB',0,'L');
	$pdf->Cell($ww[3],5,mysqli_result($res_resultado, $contador, "localidad"),'LRTB',0,'L');
	$pdf->Cell($ww[4],5,mysqli_result($res_resultado, $contador, "telefono"),'LRTB',0,'C');
	$pdf->Ln();
	$contador++;
};
			
$nombre='../copias/proveedores.pdf';	
		
$pdf->Output($nombre,'I');?> 
