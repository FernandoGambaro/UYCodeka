<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

global $ww;

header("Content-Type: text/html; charset=iso-8859-1 ");

$title="Listado de Pagos DGI";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$header=array('Mes/Año','IRAE','IMP. Pat.','IVA','ICOSA');
$ww=array(40,30,30,30,30);

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);


$fecha=@$_GET["fecha"];
$mes=@$_GET["mes"];
$anio=@$_GET["anio"];


$where="1=1";
if ($fecha <> "") { $where.=" AND fecha='$fecha'"; }
if ($anio <> "") { $where.=" AND anio='$anio'"; }
if ($mes <> "") { $where.=" AND mes='$mes'"; }

$where.=" ORDER BY anio DESC, mes DESC";



//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
	

$pdf->SetFont('Arial','',8);
$sel_resultado="SELECT * FROM pagodgi WHERE ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
$total=0;
while ($contador < mysqli_num_rows($res_resultado)) {
	$pdf->Cell($ww[0],5,genMonth_Text(mysqli_result($res_resultado, $contador, "mes")). ' / '. mysqli_result($res_resultado, $contador, "anio"),'LRTB',0,'L');
	$f108=mysqli_result($res_resultado, $contador, "f108");
	$pdf->Cell($ww[1],5,$f108,'LRTB',0,'C');
	$f328=mysqli_result($res_resultado, $contador, "f328");
	$pdf->Cell($ww[2],5,$f328,'LRTB',0,'C');
	$f546=mysqli_result($res_resultado, $contador, "f546");
	$pdf->Cell($ww[3],5,$f546,'LRTB',0,'C');
	$f606=mysqli_result($res_resultado, $contador, "f606");
	$pdf->Cell($ww[4],5,$f606,'LRTB',0,'C');
	$pdf->Ln();
	$contador++;
	$total+=$f108+$f328+$f546+$f606;
};

	$pdf->Cell($ww[0],5,'','',0,'C');
	$pdf->Cell($ww[1],5,'','',0,'C');
	$pdf->Cell($ww[2],5,'','',0,'C');
	$pdf->Cell($ww[3],5,'','',0,'C');
	$pdf->Cell($ww[4],5,'Total','',0,'R');
	$pdf->Cell(15,5,$total,'',0,'C');

			
$nombre='../copias/pagoDGI.pdf';	
		
$pdf->Output($nombre,'I');

?> 
