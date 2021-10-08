<?php
include ("../conectar.php");  
include ("../funciones/fechas.php");
define('FPDF_FONTPATH','font/');

require('mysql_table.php');
//require('fpdf.php');
//include("comunes_factura.php");


include("comunes_cliente.php");
header("Content-Type: text/html; charset=iso-8859-1 ");
mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes
date_default_timezone_set('America/Montevideo');


$codcliente=$_GET["e"];
$factura=$_GET['factura'];
$fechainicio=$_GET["fechaini"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_GET["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=@$_GET["cadena_busqueda"];


$where="1=1";
if ($codcliente <> "") { $where.=" AND service.codcliente='$codcliente'"; }
if ($factura <> "") { $where.=" AND service.factura=0"; }
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

$title="Detalle services";

$ww=array(14,25,18,20,75,12,16,12);
/*/Ttulos de las columnas*/
$header=array('Fecha','Equipo','Tipo','Solicitado','Detalle','Horas','Estado','Factura');

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}
$pos='';

$inicio=$fechainicio." 00:00:00";
$fin=$fechafin." 23:59:59";

$datetime1=new DateTime($inicio);
$datetime2=new DateTime($fin);
# obtenemos la diferencia entre las dos fechas
$interval=$datetime2->diff($datetime1);
# obtenemos la diferencia en meses
$intervalMeses=$interval->format("%m");
# obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses
$intervalAnos = $interval->format("%y")*12;
$meses=$intervalMeses+$intervalAnos;
if ($meses==0){
	$meses=1;
}
$sel="SELECT sum(horas) as filas FROM service WHERE service.borrado=0 AND ".$where." ORDER BY fecha DESC";

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $sel);
$horasservice=mysqli_result($rs_busqueda, 0, "filas");


$pdf=new PDF('P','mm',array(210,297));


//$pdf->Open();
$pdf->SetMargins(5, 10 ,5);

$pdf->AddPage();


$pdf->SetFont('Arial','',6);
$contador=0;

$Tipo = array( 0=>"Llamada", 1=>"Service", 2=>"Mantenimiento", 3=>"Consulta");
$estadoarray = array(0=>"Pendiente", 1=>"Asignado", 2=>"Terminado");
$estadocolor = array(0=>"red", 1=>"blue", 2=>"green");


$sel_resultado="SELECT * FROM service WHERE service.borrado=0 AND ".$where." ORDER BY fecha DESC";
/*$sel_resultado=$sel_resultado."  limit 0,10";*/

$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;

/* - */

while ($contador < mysqli_num_rows($res_resultado)) { 

if(!empty(mysqli_result($res_resultado, $contador, "detalles"))){
	$detallesA=$detalles=mysqli_result($res_resultado, $contador, "detalles");
} else {
	$detallesA=$detalles=mysqli_result($res_resultado, $contador, "realizado");
}	

		$largo=0;
		$largo_ini=strlen($detalles);
		if ($largo_ini>75) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 75);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
    			$border="R";
    			$border2="LR";
			} else {	
				$acotado = substr($detallesA, 0, 75);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$border="RB";
    			$border2="LRB";
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 75);
  			$border="RB";
  			$border2="LRB";
		}

	$pdf->Cell($ww[0],5,implota(mysqli_result($res_resultado, $contador, "fecha")),$border2,0,'L');
	
if ( mysqli_result($res_resultado, $contador, "codequipo")!=0){
$codequipo= mysqli_result($res_resultado, $contador, "codequipo");
$consulta="SELECT * FROM equipos WHERE borrado=0 AND `codequipo`='".$codequipo."'";
$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);

	$pdf->Cell($ww[1],5, mysqli_result($rs_tabla, 0, "alias"),$border,0,'L');
} else {
	$pdf->Cell($ww[1],5, 'Horas',$border,0,'L');
}	

if(mysqli_result($res_resultado, $contador, "factura")!=0) {
	$factura='Nº '. mysqli_result($res_resultado, $contador, "factura");
} else {
	$factura='No';
}	

 if (is_numeric(mysqli_result($res_resultado, $contador, "tipo") )) {
	$pdf->Cell($ww[2],5,$Tipo[mysqli_result($res_resultado, $contador, "tipo")],$border,0,'L');
 } else {
	$pdf->Cell($ww[2],5,mysqli_result($res_resultado, $contador, "tipo"),$border,0,'L');
 }		
	
	$pdf->Cell($ww[3],5, mysqli_result($res_resultado, $contador, "solicito"),$border,0,'L');
	$pdf->Cell($ww[4],5, $acotado,$border,0,'L');
	$pdf->Cell($ww[5],5, number_format(mysqli_result($res_resultado, $contador, "horas"),2,",","."),$border,0,'L');
	$pdf->Cell($ww[6],5, $estadoarray[mysqli_result($res_resultado, $contador, "estado")],$border,0,'L');
	$pdf->Cell($ww[7],5, $factura,$border,0,'L');
	$pdf->Ln();	
	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>75) {
				$largo=1;
				$texto_corto = substr($resta, 0, 75);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 75);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
	  			$border="R";
  				$border2="LR";
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini-1, 75);
	  			$border="RB";
  				$border2="LRB";
			}
			$ini=$ini+$pos;	

			$pdf->Cell($ww[0],4,' ',$border2,0,'L');
			$pdf->Cell($ww[1],4,' ',$border,0,'C');	
			$pdf->Cell($ww[2],4,' ',$border,0,'C');	
			$pdf->Cell($ww[3],4,' ',$border,0,'C');	
			$pdf->Cell($ww[4],4, $acotado,$border,0,'L');
	  		$pdf->Cell($ww[5],4,' ',$border,0,'R');
	  		$pdf->Cell($ww[6],4,' ',$border,0,'R');
	  		$pdf->Cell($ww[7],4,' ',$border,0,'R');
			$pdf->Ln();
		  //$contador++;
		}

	$contador++;
}


$nombre='../copias/service.pdf';			
$pdf->Output($nombre,'I');
?> 
