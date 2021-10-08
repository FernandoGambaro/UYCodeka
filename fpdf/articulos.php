<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

global $ww;

header("Content-Type: text/html; charset=iso-8859-1 ");

$title="Listado de Articulos";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$header=array('Familia','Referencia','Descripcion','P. Tienda','Stock');
$ww=array(40,30,80,20,20);

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);


$codigobarras=$_GET["codigobarras"];
$descripcion=$_GET["descripcion"];
$codfamilia=@$_GET["cboFamilias"];
$referencia=$_GET["referencia"];
$codproveedor=@$_GET["cboProveedores"];
$codubicacion=@$_GET["cboUbicacion"];
$stock=$_GET["stock"];

$where="1=1";
if ($codigobarras <> "") { $where.=" AND codigobarras='$codigobarras'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
if ($codfamilia > "0") { $where.=" AND codfamilia='$codfamilia'"; }
if ($codproveedor > "0") { $where.=" AND (codproveedor1='$codproveedor' OR codproveedor2='$codproveedor')"; }
if ($codubicacion > "0") { $where.=" AND codubicacion='$codubicacion'"; }
if ($referencia <> "") { $where.=" AND referencia like '%".$referencia."%'"; }
if ($stock <> "") { $where.=" AND stock = '".$stock."'"; }



//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
	

$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM articulos LEFT JOIN familias ON articulos.codfamilia=familias.codfamilia WHERE articulos.borrado=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
while ($contador < mysqli_num_rows($res_resultado)) {
	$pdf->Cell($ww[0],5,mysqli_result($res_resultado, $contador, "familias.nombre"),'LRTB',0,'L');
	$pdf->Cell($ww[1],5,mysqli_result($res_resultado, $contador, "referencia"),'LRTB',0,'C');
	$pdf->Cell($ww[2],5,mysqli_result($res_resultado, $contador, "descripcion_corta"),'LRTB',0,'L');
	$pdf->Cell($ww[3],5,mysqli_result($res_resultado, $contador, "precio_tienda"),'LRTB',0,'R');
	$pdf->Cell($ww[4],5,mysqli_result($res_resultado, $contador, "stock"),'LRTB',0,'R');
	$pdf->Ln();
	$contador++;
};
			
$nombre='../copias/articulos.pdf';	
		
$pdf->Output($nombre,'I');

?> 
