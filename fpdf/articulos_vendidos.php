<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

global $ww;

header("Content-Type: text/html; charset=iso-8859-1 ");

$moneda=$_GET["moneda"];

$fechainicio=$_GET["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_GET["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$where=" 1=1";

if ($moneda <> 0 ) { $where.=" AND facturas.moneda = '".$moneda."'"; }

if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}

$title="Listado de Articulos Vendidos desde ".$_GET["fechainicio"]. " hasta ". $_GET["fechafin"];;
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$header=array('Código','Descripción','Vendidos', 'Stock');
$ww=array(40,120,15,15);

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);


$where.=" GROUP BY articulos.codarticulo ORDER BY articulos.codarticulo ASC , fecha ASC ";

//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
	

$pdf->SetFont('Arial','',8);

$sel_resultado="SELECT factulinea.codigo, factulinea.codfactura, factulinea.cantidad, factulinea.detalles, facturas.moneda, facturas.codfactura, 
facturas.fecha, articulos.codarticulo, articulos.descripcion, articulos.referencia, sum(factulinea.cantidad) as total FROM factulinea 
INNER JOIN facturas ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos ON factulinea.codigo=articulos.codarticulo  WHERE ".$where;
 
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
$SumaTotal=0;

while ($contador < mysqli_num_rows($res_resultado)) {

	$stock='';
 		$detallesA=$detalles=mysqli_result($res_resultado, $contador, "descripcion");
		$largo=0;
		$largo_ini=strlen($detalles);
		if ($largo_ini>88) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 87);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
    			$border="R";
    			$border2="LR";
			} else {	
				$acotado = substr($detallesA, 0, 87);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$border="RB";
    			$border2="LRB";
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 87);
  			$border="RB";
  			$border2="LRB";
		}
	

	$pdf->Cell($ww[0],5,mysqli_result($res_resultado, $contador, "referencia"),$border2,0,'L');
	$pdf->Cell($ww[1],5,$acotado,$border,0,'L');
	$pdf->Cell($ww[2],5,mysqli_result($res_resultado, $contador, "total"),$border,0,'R');
/*Obtener el stock actual */	
$sql_stock="SELECT * FROM articulos where codarticulo=".mysqli_result($res_resultado, $contador, "codarticulo");
$sel_stock=mysqli_query($GLOBALS["___mysqli_ston"], $sql_stock);
$stock=mysqli_result($sel_stock,  0,  'stock');
	$pdf->Cell($ww[3],5,$stock,$border,0,'R');
	
	$SumaTotal=$SumaTotal+mysqli_result($res_resultado, $contador, "total");
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
			$pdf->Cell($ww[1],5, $acotado,$border,0,'L');
	  		$pdf->Cell($ww[2],5,' ',$border,0,'R');
	  		$pdf->Cell($ww[3],5,' ',$border,0,'R');
			$pdf->Ln();
		  //$contador++;
		}




	$contador++;
};

	$pdf->Cell(140,5, "Artículos vendidos ".$contador,'LTB',0,'L');
	$pdf->Cell(35,5, "Total Unidades ".$SumaTotal,'RTB',0,'R');

			
$nombre='../copias/articulos_vendidos.pdf';	
		
$pdf->Output($nombre,'I');

?> 
