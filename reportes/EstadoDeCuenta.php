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
 
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	 /*/user is not logged in*/
         /*echo "<script>parent.changeURL('../../index.php' ); </script>";*/
	 header("Location:../index.php");
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];


include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set("America/Montevideo"); 
include ("../funciones/fechas.php");
$where="";

$codusuarios = isset($_POST["codusuarios"]) ? $_POST["codusuarios"] : $_POST["codusuarios"];
$localidad = isset($_POST["localidad"]) ? $_POST["localidad"] : $_POST["localidad"];
$fechainicio = isset($_POST["fechainicio"]) ? explota($_POST["fechainicio"]) : explota($_POST["fechainicio"]);
$fechafin = isset($_POST["fechafin"]) ? explota($_POST["fechafin"]) : explota($_POST["fechafin"]);
$codcliente = isset($_POST["codcliente"]) ? $_POST["codcliente"] : $_POST["codcliente"];
$moneda = isset($_POST["amoneda"]) ? $_POST["amoneda"] : $_POST["amoneda"];

if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($codusuarios <> "" and $codusuarios != 0) { $where.=" AND codusuarios='$codusuarios'";  }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
//if ($moneda > "") { $where.=" AND moneda='$moneda'"; }


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
/*
$startTime = date ("Y-m-d", strtotime("-90 day", strtotime(explota($_POST["fechainicio"]))));
$startTime =data_first_month_day($startTime ); 
$endTime =  explota($_POST["fechafin"]);
$sTime=$startTime;
*/
 
?>
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--This file was converted to xhtml by LibreOffice - see http://cgit.freedesktop.org/libreoffice/core/tree/filter/source/xslt for the code.-->
<head profile="http://dublincore.org/documents/dcmi-terms/">
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
<title xml:lang="en-US">Estado de Cuenta</title>

<style type="text/css">
	@page {  }
	table { border-collapse:collapse; border-spacing:0; empty-cells:show }
	td, th { vertical-align:top; font-size:10pt;}
	h1, h2, h3, h4, h5, h6 { clear:both }
	ol, ul { margin:0; padding:0;}
	li { list-style: none; margin:0; padding:0;}
	<!-- "li span.odfLiEnd" - IE 7 issue-->
	li span. { clear: both; line-height:0; width:0; height:0; margin:0; padding:0; }
	span.footnodeNumber { padding-right:1em; }
	span.annotation_style_by_filter { font-size:95%; font-family:Arial; background-color:#fff000;  margin:0; border:0; padding:0;  }
	* { margin:0;}
	.ta1 { writing-mode:lr-tb; }
	.Default { font-family:Liberation Sans; }
	.ce1 { font-family:Verdana; font-size:11pt; }
	.ce10 { font-family:Verdana; background-color:#ccff00; border-width:0,0621cm; border-style:solid; border-color:#000000; vertical-align:middle; text-align:right ! important; margin-left:0mm; font-size:11pt; }
	.ce11 { font-family:Verdana; background-color:#ccff00; border-width:0,0621cm; border-style:solid; border-color:#000000; font-size:11pt; }
	.ce12 { font-family:Verdana; background-color:#ccff00; border-width:0,0621cm; border-style:solid; border-color:#000000; vertical-align:middle; text-align:right ! important; margin-left:0mm; color:#000000; font-size:11pt; font-style:italic; }
	.ce13 { font-family:Verdana; background-color:#ccff00; border-width:0,0621cm; border-style:solid; border-color:#000000; vertical-align:middle; font-size:11pt; }
	.ce2 { font-family:Verdana; background-color:#666666; vertical-align:middle; text-align:center ! important; color:#ffffff; font-size:11pt; font-weight:bold; }
	.ce3 { font-family:Verdana; background-color:#999999; border-width:0,0621cm; border-style:solid; border-color:#000000; vertical-align:middle; text-align:center ! important; margin-left:0mm; font-size:11pt; font-style:italic; }
	.ce4 { font-family:Verdana; border-width:0,0021cm; border-style:solid; border-color:#000000; vertical-align:middle; font-size:11pt; }
	.ce5 { font-family:Verdana; vertical-align:middle; font-size:11pt; }
	.ce6 { font-family:Verdana; background-color:#666666; font-size:11pt; }
	.ce7 { font-family:Verdana; border-width:0,0021cm; border-style:solid; border-color:#000000; vertical-align:middle; font-size:11pt; }
	.ce8 { font-family:Verdana; background-color:#666666; color:#ffffff; font-size:11pt; }
	.ce9 { font-family:Verdana; background-color:#ccff00; border-width:0,0621cm; border-style:solid; border-color:#000000; vertical-align:middle; text-align:center ! important; margin-left:0mm; font-size:11pt; font-style:italic; }
	.co1 { width:25.59mm; }
	.co2 { width:30.5mm; }
	.co3 { width:38.66mm; }
	.co4 { width:30.76mm; }
	.co5 { width:33.21mm; }
	.co6 { width:24.77mm; }
	.co7 { width:25.05mm; }
	.ro1 { height:4.52mm; }
	.ro2 { height:5.29mm; }
	.ro3 { height:7.92mm; }
	.ro4 { height:6.33mm; }
	.ro5 { height:6.86mm; }
	<!-- ODF styles with no properties representable as CSS -->
	 { }
	</style></head><body dir="ltr">
	<table border="0" cellspacing="0" cellpadding="0" class="ta1"><colgroup><col width="112"/>
	<col width="133"/><col width="169"/><col width="134"/><col width="145"/><col width="108"/><col width="109"/><col width="109"/>
	<col width="109"/></colgroup>
	<tr class="ro1">
	<td style="text-align:left;width:25.59mm; " class="Default"> </td>
	<td style="text-align:left;width:30.5mm; " class="Default"> </td>
	<td style="text-align:left;width:38.66mm; " class="Default"> </td>
	<td style="text-align:left;width:30.76mm; " class="Default"> </td>
	<td style="text-align:left;width:33.21mm; " class="Default"> </td>
	<td style="text-align:left;width:24.77mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td></tr>
	<tr class="ro1">
	<td style="text-align:left;width:25.59mm; " class="Default"> </td>
	<td style="text-align:left;width:30.5mm; " class="Default"> </td>
	<td style="text-align:left;width:38.66mm; " class="Default"> </td>
	<td style="text-align:left;width:30.76mm; " class="Default"> </td>
	<td style="text-align:left;width:33.21mm; " class="Default"> </td>
	<td style="text-align:left;width:24.77mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td>
	<td style="text-align:left;width:25.05mm; " class="Default"> </td></tr>
	<tr class="ro2">
	<td style="text-align:left;width:25.59mm; " class="ce1"> </td>
	<td style="text-align:left;width:30.5mm; " class="ce1"> </td>
	<td style="text-align:left;width:38.66mm; " class="ce1"> </td>
	<td style="text-align:left;width:30.76mm; " class="ce1"> </td>
	<td style="text-align:left;width:33.21mm; " class="ce1"> </td>
	<td style="text-align:left;width:24.77mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td></tr>
	<tr class="ro2">
	<td colspan="9" style="text-align:left;width:25.59mm; " class="ce2">
	<?php 
		$cont=0;
		$sql_cliente="SELECT nombre, apellido, empresa FROM clientes WHERE codcliente='".$codcliente."'";
		$res_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
		$nombre=mysqli_result($res_cliente, $cont, "nombre")." ".mysqli_result($res_cliente, $cont, "apellido")." - ".mysqli_result($res_cliente, $cont, "empresa");	
	?>
	<p>Estado de cuenta cliente: <?php echo $nombre;?> a la Fecha <?php echo $_POST["fechafin"];?></p></td></tr>
	<tr class="ro2">
	<td style="text-align:left;width:25.59mm; " class="ce1"> </td>
	<td style="text-align:left;width:30.5mm; " class="ce1"> </td>
	<td style="text-align:left;width:38.66mm; " class="ce1"> </td>
	<td style="text-align:left;width:30.76mm; " class="ce1"> </td>
	<td style="text-align:left;width:33.21mm; " class="ce1"> </td>
	<td style="text-align:left;width:24.77mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td>
	<td style="text-align:left;width:25.05mm; " class="ce1"> </td></tr>
	<tr class="ro2">
<?php

$tope=1;
if($moneda==0) {
$tope=2;
}
 $wheretmp=$where;
for($x=0; $x<$tope;$x++) {

$startTime = date ("Y-m-d", strtotime(explota($_POST["fechainicio"])));
$startTime =data_first_month_day($startTime ); 
$endTime =  explota($_POST["fechafin"]);
$sTime=$startTime;	
	/*
$startTime = explota($_GET["fechainicio"]);
$startTime =data_first_month_day($startTime ); 
$endTime =  explota($_GET["fechafin"]);
$sTime=$startTime;
*/

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
  			$sel_resultado="SELECT codfactura,fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda
				FROM facturas WHERE facturas.borrado=0 AND facturas.tipo=1  ".$where." AND fecha < '".$startTime."'";
	
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
		   	$totalfactura=$saldo+=mysqli_result($res_resultado, $contador, "totalfactura");
		   	$contador++;
		   }

			$sel_resultado="SELECT codrecibo,fecha,importe,moneda
			FROM recibos WHERE borrado=0 ".$where." AND fecha < '".$startTime."'";
	
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) {
		   	 $saldo-=mysqli_result($res_resultado, $contador, "importe");
		   	 $importe+=mysqli_result($res_resultado, $contador, "importe");
		   	 $contador++;
		   }
	
?>	
	<td style="text-align:left;width:25.59mm; " class="ce1"> </td>
	<td style="text-align:left;width:30.5mm; " class="Default"> </td>
	<td style="text-align:left;width:38.66mm; " class="Default"> </td>
	<td style="text-align:left;width:30.76mm; " class="ce1"> </td>
	<td colspan="2" style="text-align:left;width:33.21mm; " class="ce9"><p>Estado Anterior en <?php echo @$monedas[$mon];?>: </p></td>
	<td style="text-align:right; width:25.05mm; " class="ce11"><p><?php echo $totalfactura;?>&nbsp;</p></td>
	<td style="text-align:right; width:25.05mm; " class="ce11"><p><?php echo number_format($importe,2,",",".");?>&nbsp;</p></td>
	<td style="text-align:right; width:25.05mm; " class="ce11"><p><?php echo number_format($saldo,2,",",".");?>&nbsp;</p></td></tr>
	<tr class="ro3"><td style="text-align:left;width:25.59mm; " class="ce3"><p>Fecha</p></td>
	<td style="text-align:left;width:30.5mm; " class="ce3"><p>Doc.Núm.</p></td>
	<td style="text-align:left;width:38.66mm; " class="ce3"><p>Tipo Documento</p></td>
	<td style="text-align:left;width:30.76mm; " class="ce3"><p>Vencimiento</p></td>
	<td style="text-align:left;width:33.21mm; " class="ce3"><p>Días vencidos</p></td>
	<td style="text-align:left;width:24.77mm; " class="ce3"><p>Moneda</p></td>
	<td style="text-align:left;width:25.05mm; " class="ce3"><p>Importe</p></td>
	<td style="text-align:left;width:25.05mm; " class="ce3"><p>Pagado</p></td>
	<td style="text-align:left;width:25.05mm; " class="ce3"><p>Saldo</p></td></tr>
	<tr class="ro4">
<?php

	while (strtotime($startTime) <= strtotime($endTime)) {
				
/*Factura*/		
			$sel_resultado="SELECT codfactura,fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda
				FROM facturas WHERE facturas.borrado=0  AND facturas.tipo=1 ".$where." AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;				   
		   while ($contador < mysqli_num_rows($res_resultado)) { 

?>	
			<td style="text-align:right; width:25.59mm; " class="ce4"><p><?php echo implota($startTime);?>&nbsp;</p></td>
			<td style="text-align:right; width:30.5mm; " class="ce7"><p><?php echo mysqli_result($res_resultado, $contador, "codfactura");?>&nbsp;</p></td>
			<td style="text-align:left;width:38.66mm; " class="ce7"><p>&nbsp;<?php echo $tipo[mysqli_result($res_resultado, $contador, "tipo")];?>&nbsp;</p></td>
			<td style="text-align:left;width:30.76mm; " class="ce7"> </td>
			<td style="text-align:left;width:33.21mm; " class="ce7"> </td>
			<td style="text-align:left;width:24.77mm; " class="ce7"><p>&nbsp;<?php echo $monedas[mysqli_result($res_resultado, $contador, "moneda")];?>&nbsp; </p></td>
			<td style="text-align:right; width:25.05mm; " class="ce7"><p><?php 
			$saldo+=mysqli_result($res_resultado, $contador, "totalfactura");
			 echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".");?>&nbsp;</p></td>
			<td style="text-align:left;width:25.05mm; " class="ce7"> </td>
			<td style="text-align:right; width:25.05mm; " class="ce7"><p><?php echo  number_format($saldo,2,",",".");?>&nbsp;</p></td></tr>
<?php
				$contador++;
			}
/*Recibos*/
			$sel_resultado="SELECT codrecibo,fecha,importe,moneda
			FROM recibos WHERE borrado=0 ".$where." AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;					   
		   while ($contador < mysqli_num_rows($res_resultado)) { 
?>
	<tr class="ro4">
	<td style="text-align:right; width:25.59mm; " class="ce4"><p><?php echo implota($startTime);?>&nbsp;</p></td>
	<td style="text-align:right; width:30.5mm; " class="ce7"><p><?php echo mysqli_result($res_resultado, $contador, "codrecibo");?>&nbsp;</p></td>
	<td style="text-align:left;width:38.66mm; " class="ce7"><p>&nbsp;Recibo</p></td>
	<td style="text-align:left;width:30.76mm; " class="ce7"> </td>
	<td style="text-align:left;width:33.21mm; " class="ce7"> </td>
	<td style="text-align:left;width:24.77mm; " class="ce7"><p>&nbsp;<?php echo $monedas[mysqli_result($res_resultado, $contador, "moneda")];?>&nbsp; </p></td>
	<td style="text-align:left;width:25.05mm; " class="ce7"> </td>
	<td style="text-align:right; width:25.05mm; " class="ce7"><p><?php $saldo-=mysqli_result($res_resultado, $contador, "importe");
	 echo number_format(mysqli_result($res_resultado, $contador, "importe"),2,",",".");?>&nbsp;</p></td>
	<td style="text-align:right; width:25.05mm; " class="ce7"><p><?php echo number_format($saldo,2,",",".");?>&nbsp;</p></td></tr>
<?php
		$contador++;
		}
			$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
	}
?>
	<tr class="ro5">
	<td style="text-align:left;width:25.59mm; " class="ce5"> </td>
	<td style="text-align:left;width:30.5mm; " class="ce5"> </td>
	<td style="text-align:left;width:38.66mm; " class="ce5"> </td>
	<td style="text-align:left;width:30.76mm; " class="ce5"> </td>
	<td style="text-align:left;width:33.21mm; " class="ce5"> </td>
	<td style="text-align:left;width:24.77mm; " class="ce5"> </td>
	<td colspan="2" style="text-align:left;width:25.05mm; " class="ce12"><p>Saldo Final <?php echo @$monedas[$mon];?>&nbsp;</p></td>
	<td style="text-align:right; width:25.05mm; " class="ce13"><p><?php echo number_format($saldo,2,",",".");?>&nbsp;</p></td>
	</tr>
<tr><td colspan="9">&nbsp;</td></tr>	
<tr><td colspan="9">&nbsp;</td></tr>	
	<?php
	}
	?>
	</table>
	
	</body></html>