<?php
error_reporting(E_ALL);
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

define('FPDF_FONTPATH','font/');
include ("../conectar.php");
include ("../funciones/fechas.php"); 

require('mysql_table.php');
include("comunes_cliente.php");
require('pdf_js.php');


class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}


function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
   $script = "var pp = getPrintParams();";
   if($dialog)
   	$script .= "pp.interactive = pp.constants.interactionLevel.full;";
   else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
 	$script .= "pp.colorOverride = pp.constants.colorOverrides.gray;";
	$script .="var fv = pp.constants.flagValues;";
	$script .= "pp.flags |= fv.setPageSize;";
	$script .= "pp.pageHandling=1;";
	if($server=='') {
		$script .= 'pp.printerName="'.$printer.'";';
	} else {
		$script .= "pp.printerName='\\\\\\\\".$server."\\\\".$printer."';";
	} 
	$script .="print(pp);";
	$script .="closeDoc(pp);";
	$script .=" doc.Close();";
   $this->IncludeJS($script);
}
}

function forceRoundUp($value, $decimals)
{
    $ord = pow(10, $decimals);
    return ceil($value * $ord) / $ord;
}

$codfactura=@$_GET["codfactura"];
$envio=@$_GET['envio'];  

//$pdf=new FPDF('L','mm',array(210,145));
//header("Content-Type: text/html; charset=iso-8859-1 ");

$where="";

//codcliente="+codcliente+"&provincia="+cboProvincias+"&localidad="+localidad+"&codusuarios="+codusuarios+"&fechainicio="+fechainicio+"&fechafin="+fechafin

$codusuarios = isset($_GET["codusuarios"]) ? $_GET["codusuarios"] : $_GET["codusuarios"];
$fechainicio = isset($_GET["fechainicio"]) ? explota($_GET["fechainicio"]) : explota($_GET["fechainicio"]);
$localidad = isset($_GET["localidad"]) ? $_GET["localidad"] : $_GET["localidad"];
$provincia = isset($_GET["provincia"]) ? $_GET["provincia"] : $_GET["provincia"];
$fechafin = isset($_GET["fechafin"]) ? explota($_GET["fechafin"]) : explota($_GET["fechafin"]);
$codcliente = isset($_GET["codcliente"]) ? $_GET["codcliente"] : $_GET["codcliente"];
$moneda = isset($_GET["moneda"]) ? $_GET["moneda"] : $_GET["moneda"];

if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($codusuarios <> "" and $codusuarios != 0) { $where.=" AND codusuarios='$codusuarios'";  }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }

$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$monedas = array();
							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $monedas[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }		

$startTime = date ("Y-m-d", strtotime("-90 day", strtotime(explota($_GET["fechainicio"]))));
$startTime =data_first_month_day($startTime ); 
$endTime =  explota($_GET["fechafin"]);
$sTime=$startTime;

		$cont=0;
		$sql_cliente="SELECT nombre, apellido, empresa FROM clientes WHERE codcliente='".$codcliente."'";
		$res_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
		$nombre=mysqli_result($res_cliente, $cont, "nombre")." ".mysqli_result($res_cliente, $cont, "apellido")." - ".mysqli_result($res_cliente, $cont, "empresa");	

//$header=array('Fecha','Doc. núm.','Tipo Doc.','Vencimiento','Moneda','Importe', 'Pago', 'Saldo');
$ww=array(20,20,20,20,30,20,20,20);

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$title="Estado de cuenta a la Fecha ".  implota($fechafin);
$pdf=new PDF_AutoPrint($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$tope=1;
if($moneda==0) {
$tope=2;
}
 $wheretmp=$where;
for($x=0; $x<$tope;$x++) {
$startTime = explota($_GET["fechainicio"]);
$startTime =data_first_month_day($startTime ); 
$endTime =  explota($_GET["fechafin"]);
$sTime=$startTime;


	if ($moneda ==0 and $x==0) { 
		$mon=1;
	} elseif($moneda ==0 and $x==1) {
		$mon=2;
	} else {
		$mon=$moneda;
	}
	$where=$wheretmp." AND moneda='$mon'"; 


$totalfactura=0;
$importe=0;
$saldo=0;
 			$sel_resultado="SELECT codfactura,fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda FROM facturas WHERE facturas.borrado=0 AND facturas.tipo=1  ".$where." AND fecha < '".$startTime."'";
	
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
		   	$totalfactura=$saldo+=mysqli_result($res_resultado, $contador, "totalfactura");
		   	$contador++;
		   }

			$sel_resultado="SELECT codrecibo,fecha,importe,moneda	FROM recibos WHERE borrado=0 ".$where." AND fecha < '".$startTime."'";
	
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) {
		   	 $saldo-=mysqli_result($res_resultado, $contador, "importe");
		   	 $importe+=mysqli_result($res_resultado, $contador, "importe");
		   	 $contador++;
		   }		   

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");

//				$pdf->Cell($ww[0],5,$sel_resultado,'',0,'L');
				//$pdf->Ln();	
				$pdf->Cell($ww[0],5,'','',0,'L');
				$pdf->Cell($ww[1],5,'','',0,'C');
				$pdf->Cell($ww[2],5,'','',0,'L');
				$pdf->Cell($ww[3],5,'','',0,'R');
				$pdf->Cell($ww[4],5,'Estado Anterior','LRTB',0,'R');
				$pdf->Cell($ww[5],5,number_format($totalfactura,2,",","."),'LRTB',0,'R');
				$pdf->Cell($ww[6],5,number_format($importe,2,",","."),'LRTB',0,'R');
				$pdf->Cell($ww[7],5,number_format($saldo,2,",","."),'LRTB',0,'R');
				$pdf->Ln();	

//$header=array('Fecha','Doc. núm.','Tipo Doc.','Vencimiento','Moneda','Importe', 'Pago', 'Saldo');
				$pdf->Cell($ww[0],5,'Fecha','',0,'L');
				$pdf->Cell($ww[1],5,'Doc. Núm.','',0,'C');
				$pdf->Cell($ww[2],5,'Tipo Doc.','',0,'L');
				$pdf->Cell($ww[3],5,'Vencimiento','',0,'R');
				$pdf->Cell($ww[4],5,'Moneda','LRTB',0,'R');
				$pdf->Cell($ww[5],5,'Importe','LRTB',0,'R');
				$pdf->Cell($ww[6],5,'Pago','LRTB',0,'R');
				$pdf->Cell($ww[7],5,'Saldo','LRTB',0,'R');
				$pdf->Ln();

//Colores, ancho de lnea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);

while (strtotime($startTime) <= strtotime($endTime)) {
				
/*Factura*/		
			$sel_resultado="SELECT codfactura,fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda	FROM facturas WHERE facturas.borrado=0  AND facturas.tipo=1 ".$where." AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;				   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				$pdf->Cell($ww[0],5,implota($startTime),'LRTB',0,'L');
				$pdf->Cell($ww[1],5,mysqli_result($res_resultado, $contador, "codfactura"),'LRTB',0,'C');
				$pdf->Cell($ww[2],5,$tipo[mysqli_result($res_resultado, $contador, "tipo")],'LRTB',0,'L');
				$pdf->Cell($ww[3],5,'','LRTB',0,'R');
				$pdf->Cell($ww[4],5,$monedas[mysqli_result($res_resultado, $contador, "moneda")],'LRTB',0,'R');
				$pdf->Cell($ww[5],5,number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",","."),'LRTB',0,'R');
				$saldo+=mysqli_result($res_resultado, $contador, "totalfactura");		   
				$pdf->Cell($ww[6],5,"",'LRTB',0,'R');
				$pdf->Cell($ww[7],5,number_format($saldo,2,",","."),'LRTB',0,'R');
				$pdf->Ln();		   
  				$contador++;
			}

			$sel_resultado="SELECT codrecibo,fecha,importe,moneda	FROM recibos WHERE borrado=0 ".$where." AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				$pdf->Cell($ww[0],5,implota($startTime),'LRTB',0,'L');
				$pdf->Cell($ww[1],5,mysqli_result($res_resultado, $contador, "codrecibo"),'LRTB',0,'C');
				$pdf->Cell($ww[2],5,'Recibo','LRTB',0,'L');
				$pdf->Cell($ww[3],5,'','LRTB',0,'R');
				$pdf->Cell($ww[4],5,$monedas[mysqli_result($res_resultado, $contador, "moneda")],'LRTB',0,'R');
				$pdf->Cell($ww[5],5,'','LRTB',0,'R');
				$saldo-=mysqli_result($res_resultado, $contador, "importe");   
				$pdf->Cell($ww[6],5,number_format(mysqli_result($res_resultado, $contador, "importe"),2,",","."),'LRTB',0,'R');
				$pdf->Cell($ww[7],5,number_format($saldo,2,",","."),'LRTB',0,'R');
				$pdf->Ln();		   
  				$contador++;
		}
			$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
	}		   
				$pdf->Cell(130,5,"",'',0,'R');
				$pdf->Cell($ww[6],5,"Saldo Final",'LRTB',0,'R');
				$pdf->Cell($ww[7],5,number_format($saldo,2,",","."),'LRTB',0,'R');

				$pdf->Ln(15);		   
}

			
$nombre='../tmp/EstadoDeCuenta'.$codcliente.'.pdf';	
		
$pdf->Output($nombre,'I');

?> 
