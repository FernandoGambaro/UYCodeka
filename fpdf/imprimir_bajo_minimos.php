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

date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes.php");


$moneda='';
$impo=0;
$importe=0;
$obs='';
$obs[0]=$obs[1]=$obs[2]=$obs[3]=$obs[4]=$obs[5]=$obs[6]='';

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

//Ttulos de las columnas
$header=array('Familia','Cod. Artculo', 'Referencia','Descripción','Costo','Stock','Mínimo');

$ww=array(19,19,19,70,15,15,15);

$title="Listado de articulos con stock bajo mínimo";

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
$sel_articulos= "select articulos.codarticulo as cod,articulos.descripcion,articulos.stock, articulos.precio_compra,articulos.stock_minimo,familias.codfamilia,familias.nombre from articulos,familias where articulos.borrado=0 AND familias.borrado=0 
AND articulos.codfamilia=familias.codfamilia AND articulos.stock<=articulos.stock_minimo AND articulos.aviso_minimo > 0 ORDER BY codfamilia ASC,cod ASC";
	
$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
$contador=0;
$item=1;
$numero_articulos=mysqli_num_rows($rs_articulos);
		if ($numero_articulos>0) {		

	
			//Colores, ancho de lnea y fuente en negrita
			$pdf->SetFillColor(200,200,200);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','B',8);
				
			$pdf->SetFont('Arial','',8);
			while ($contador < mysqli_num_rows($rs_articulos)) {
				$pdf->Cell($ww[0],5,mysqli_result($rs_articulos, $contador, "nombre"),'LRTB',0,'C');
				$pdf->Cell($ww[1],5,mysqli_result($rs_articulos, $contador, "codarticulo"),'LRTB',0,'C');
				$pdf->Cell($ww[2],5,mysqli_result($rs_articulos, $contador, "referencia"),'LRTB',0,'C');
				$pdf->Cell($ww[3],5,mysqli_result($rs_articulos, $contador, "descripcion"),'LRTB',0,'L');
				$pdf->Cell($ww[4],5,mysqli_result($rs_articulos, $contador, "precio_compra"),'LRTB',0,'C');
				$pdf->Cell($ww[5],5,mysqli_result($rs_articulos, $contador, "stock"),'LRTB',0,'C');
				$pdf->Cell($ww[6],5,mysqli_result($rs_articulos, $contador, "stock_minimo"),'LRTB',0,'C');
				
				$pdf->Ln();
				$item++;
				$contador++;
			}
		};

$pdf->Output();

?>