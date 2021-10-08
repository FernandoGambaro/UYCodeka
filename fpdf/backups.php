<?php

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

global $ww;

header("Content-Type: text/html; charset=iso-8859-1 ");
$pos=0;
$codcliente=$_GET["e"];
$title="Detalle respaldos";
$subtitle="Listado de Respaldos";

$ww=array(14,40,30,20,35,20,20,15);
/*/Ttulos de las columnas*/
$header=array('Fecha','Tarea','Equipo/Usuario','Versión','Errores', 'Procesados', 'Respaldados','Tamaño');

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



$pdf->SetFont('Arial','',6);
$contador=0;

$sel_resultado="SELECT * FROM respaldospc WHERE codcliente='".$codcliente."' order by `fecha` DESC LIMIT 0 , 40";
	
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;


while ($contador < mysqli_num_rows($res_resultado)) { 

	
 		$detallesA=$detalles=mysqli_result($res_resultado, $contador, "errores");
		$largo=0;
		$largo_ini=strlen($detalles);
		if ($largo_ini>35) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 35);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
    			$border="R";
    			$border2="LR";
			} else {	
				$acotado = substr($detallesA, 0, 35);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$border="RB";
    			$border2="LRB";
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 35);
  			$border="RB";
  			$border2="LRB";
		}

	$pdf->Cell($ww[0],5,implota(mysqli_result($res_resultado, $contador, "fecha")),$border2,0,'L');
	$pdf->Cell($ww[1],5, mysqli_result($res_resultado, $contador, "tarea"),$border,0,'L');
	$pdf->Cell($ww[2],5, mysqli_result($res_resultado, $contador, "usuario"),$border,0,'L');
	$pdf->Cell($ww[3],5, mysqli_result($res_resultado, $contador, "version"),$border,0,'L');

	$pdf->Cell($ww[4],5, $acotado,$border,0,'L');
	$pdf->Cell($ww[5],5, mysqli_result($res_resultado, $contador, "procesados"),$border,0,'L');
	$pdf->Cell($ww[6],5, mysqli_result($res_resultado, $contador, "respaldados"),$border,0,'L');
	$pdf->Cell($ww[7],5, mysqli_result($res_resultado, $contador, "tamano"),$border,0,'L');
	$pdf->Ln();	
	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>35) {
				$largo=1;
				$texto_corto = substr($resta, 0, 35);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 35);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
	  			$border="R";
  				$border2="LR";
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini-1, 35);
	  			$border="RB";
  				$border2="LRB";
			}
			$ini=$ini+$pos;	

			$pdf->Cell($ww[0],5,' ',$border2,0,'L');
			$pdf->Cell($ww[1],5,' ',$border,0,'C');	
			$pdf->Cell($ww[2],5,' ',$border,0,'C');	
			$pdf->Cell($ww[3],5,' ',$border,0,'C');	
			$pdf->Cell($ww[4],5, $acotado,$border,0,'L');
	  		$pdf->Cell($ww[5],5,' ',$border,0,'R');
	  		$pdf->Cell($ww[6],5,' ',$border,0,'R');
	  		$pdf->Cell($ww[7],5,' ',$border,0,'R');
			$pdf->Ln();
		  //$contador++;
		}

	$contador++;
}



$nombre='../copias/backups.pdf';			
$pdf->Output($nombre,'I');
?> 

