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
setlocale('LC_ALL', 'es_ES');

include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set("America/Montevideo"); 

$fechainicio=explota($_GET['fechainicio']);
$anio=date("Y",strtotime($fechainicio));

$f546=0;
$f108=0;
$f328=0;
$f606=0;
$pago_f546=0;
$pago_f108=0;
$pago_f606=0;
$pago_f328=0;
$fechaDGI='';

 
?>

<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">

	<style type="text/css"><!-- 
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Liberation Sans"; font-size:x-small }
		 -->
	</style>
	
</head>
	<body>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> <b><i><font size=3>Cierre Anualizado Enero - Diciembre <?php echo $anio;?></font></i></b></div>      			
<div class="fixed-table-container-inner">
<br>

<table cellspacing="0" border="0">
	<colgroup width="139"></colgroup>
	<colgroup width="95"></colgroup>
	<colgroup span="2" width="85"></colgroup>
	<colgroup width="23"></colgroup>
	<colgroup span="3" width="85"></colgroup>
	<colgroup width="23"></colgroup>
	<colgroup width="85"></colgroup>
	<colgroup width="63"></colgroup>
	<colgroup width="92"></colgroup>
	<colgroup width="63"></colgroup>
	<colgroup width="23"></colgroup>
	<colgroup width="92"></colgroup>
	<colgroup span="2" width="23"></colgroup>
	<colgroup width="64"></colgroup>
	<colgroup width="23"></colgroup>
	<colgroup width="87"></colgroup>
	<colgroup width="59"></colgroup>
	<colgroup width="94"></colgroup>
	<colgroup width="50"></colgroup>
	<colgroup width="63"></colgroup>
	<colgroup width="23"></colgroup>
	<colgroup width="87"></colgroup>

	<tr>
		<td height="17" align="left" colspan="5">Saldos expresados en $</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
	</tr>
	<tr>
		<td height="18" align="left"><br></td>
		<td align="center" bgcolor="#CCC1DA" sdnum="3082;0;#.##0" colspan="7"><b>DEL PERÍODO</b></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom bgcolor="#FAC090" sdnum="3082;0;#.##0" colspan="4"><b>ACUMULADO</b></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left"><br></td>
		<td align="center" bgcolor="#C4BD97"><b>CÁLCULO</b></td>
		<td align="left"><br></td>
		<td align="center" bgcolor="#B3A2C7" colspan="5"><b>PAGOS REALIZADOS S/BOLETO</b></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
	</tr>
	<tr>
		<td height="18" align="center" valign=bottom>cod 2/178</td>
		<td align="center" valign=bottom sdval="16" sdnum="3082;0;#.##0">16</td>
		<td align="center" valign=bottom sdval="5" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">5</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom sdval="12" sdnum="3082;0;#.##0"><font face="Calibri">12</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">vtas</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">3 y 2</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">exc comp 21</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#17375E"><br></font></td>
		<td align="center" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom><br></td>
		<td align="center" valign=bottom><br></td>
		<td align="center" valign=bottom><br></td>
		<td align="center" valign=bottom><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><br></td>
	</tr>
	<tr>
		<td height="18" align="center" valign=bottom sdnum="3082;0;MMM - AA"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">del mes</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">del mes</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">del mes</font></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#17375E"><br></font></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
	</tr>
	<tr>
		<td height="18" align="center" valign=bottom sdnum="3082;0;MMM - AA"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">iva vtas</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">iva compras</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">iva gtos</font></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri">Ventas </font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">Compras </font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">Gastos</font></td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">Ventas </td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">iva vtas</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">iva compras</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">iva gtos</font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">Cálculo iva</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0"><font face="Calibri" color="#17375E"><br></font></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">Saldo</td>
		<td align="left"><br></td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">IVA</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">IRAE</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">IP</td>
		<td align="center" valign=bottom sdnum="3082;0;#.##0">ICOSA</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left">Fecha Pago</td>
	</tr>
	<tr>
		<td height="18" align="left" sdnum="3082;0;MMM - AA" colspan="3">iva compras excedente</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left">retenciones</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="right" valign=bottom sdval="4465" sdnum="3082;0;_ * #.##0_ ;_ * -#.##0_ ;_ * &quot;-&quot;??_ ;_ @_ "><font face="Calibri" color="#000000"> 4.465 </font></td>
		<td align="left" sdnum="3082;0;#.##0">=</td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
	</tr>

	<tr>
		<td height="18" align="left" sdnum="3082;0;MMM - AA" colspan="2">iva compras ejerc ant</td>
		<td align="right" bgcolor="#FFFF00" sdval="0" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">
		<?php  $Saldo_Anterior=0;
		echo number_format($Saldo_Anterior,2,",",".");
		?>&nbsp;
		</font></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">
		<?php echo number_format(-$Saldo_Anterior,2,",",".");
		?>&nbsp;
		</font></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="right" valign=bottom bgcolor="#FFFF00" sdval="0" sdnum="3082;0;0,000%"><font face="Calibri" color="#000000">0,000%</font></td>
		<td align="right" bgcolor="#FFFF00" sdval="761" sdnum="3082;">761</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left" sdnum="3082;0;@"><br></td>
	</tr>
<?php 
/*Comienzo cálculo anual*/

$x=1;
$Acumulado_IVA_Ventas=0;
$Acumulado_IVA_Compras=0;
$pago_f546=0;
$Saldo_Retenciones=0;
$Total_Iva_Retenciones=0;
$Iva_Retenciones=0;
	while ($x<=12) {
		if (strlen($x)==1){
		$fecha=$anio."-0".$x."-01";	
		} else {
		$fecha=$anio."-".$x."-01";	
		}
		$startTime =data_first_month_day($fecha); 
		$endTime = data_last_month_day($fecha); 

		$sTime=$startTime;

	while (strtotime($startTime) <= strtotime($endTime)) {

			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime($startTime)));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <'".$fechaTipoCambio."'";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
   		while ($row=mysqli_fetch_array($res_tipocambio)) {
   			$tipocambio=$row['valor'];
   		} 

/*Sector Ventas*/
			$sel_resultado="SELECT fecha,totalfactura,estado,facturas.tipo as tipo ,facturas.iva,facturas.moneda	FROM facturas WHERE facturas.borrado=0 AND fecha ='".$startTime."'";
		
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		$contador=0;

		while ($contador < mysqli_num_rows($res_resultado)) {
							  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
								$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
								
			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
			$iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_resultado, $contador, "iva"));
			} else {
			$iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_resultado, $contador, "iva"));
			}					
			$importe=$iva/(mysqli_result($res_iva, 0, "valor")/100);
			if (mysqli_result($res_resultado, $contador, "moneda")==1){
			@$Iva_Ventas+=$iva;
			@$Importe_Ventas+=$importe;
			} else {
			@$Iva_Ventas+=$iva*$tipocambio;
			@$Importe_Ventas+=$importe*$tipocambio;
			}
			
			$iva=0;
			$importe=0;
	   	$contador++;
		}
		   
/* Sector compras*/
		$sel_resultado="SELECT * FROM facturasp WHERE fecha ='".$startTime."'";
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		$contador=0;						   
	   while ($contador < mysqli_num_rows($res_resultado)) {
							  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
								$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
								
			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
			$iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_resultado, $contador, "iva"));
			} else {
			$iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_resultado, $contador, "iva"));
			}					
	   	 		   
			if(mysqli_result($res_iva, 0, "valor")!=0 and mysqli_result($res_iva, 0, "valor")!='') {
			$importe=$iva/(mysqli_result($res_iva, 0, "valor")/100);
			}
			
			if (mysqli_result($res_resultado, $contador, "moneda")==1){
			@$Iva_Compras+=$iva;		 
			@$Importe_Compras+=$importe;
			} else {
			@$Iva_Compras+=$iva*$tipocambio;
			@$Importe_Compras+=$importe*$tipocambio;
			}
			$iva=0;
	   	$contador++;
		}

	$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
	}		
if($x==1) {
@$Iva_Retenciones=-$Saldo_Anterior+$Iva_Ventas-$Iva_Compras;
} else {
@$Iva_Retenciones=$Iva_Ventas-$Iva_Compras;
}
@$Acumulado_Ventas+=$Importe_Ventas;
@$Acumulado_IVA_Ventas+=$Iva_Ventas;
@$Acumulado_IVA_Compras+=$Iva_Compras;
@$Total_Ventas+=$Importe_Ventas;
@$Total_Compras+=$Importe_Compras;
?>	
	<tr>
		<td height="18" align="right" sdval="41640" sdnum="3082;0;MMM - AA"><?php echo genMonth_Text(date('m',strtotime($sTime)));?>&nbsp;-&nbsp;<?php echo date('Y',strtotime($sTime));?></td>
		<td align="right" valign=bottom sdval="<?php echo number_format($Iva_Ventas,2,",",".");?>" sdnum="3082;0;#.##0"><font face="Calibri" color="#000000">
		<?php echo number_format($Iva_Ventas,2,",",".");?>&nbsp;
		</font></td>
		<td align="right" valign=bottom sdval="<?php echo number_format($Iva_Compras,2,",",".");?>" sdnum="3082;0;#.##0_ ;-#.##0 "><font face="Calibri" color="#000000">
		<?php echo number_format($Iva_Compras,2,",",".");?>&nbsp;
		</font></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="right" sdval="<?php echo number_format($Importe_Ventas,2,",",".");?>" sdnum="3082;0;#.##0"><font face="Calibri">
		<?php echo number_format($Importe_Ventas,2,",",".");?>&nbsp;
		</font></td>
		<td align="right" sdval="<?php echo number_format($Importe_Compras,2,",",".");?>" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">
		<?php echo number_format($Importe_Compras,2,",",".");?>&nbsp;
		</font></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">0</font></td>
		<td align="left"><br></td>
		<td align="right" sdval="<?php echo number_format($Acumulado_Ventas,2,",",".");?>" sdnum="3082;0;#.##0">
		<?php echo number_format($Acumulado_Ventas,2,",",".");?>&nbsp;
		</td>
		<td align="right" sdval="<?php echo number_format($Acumulado_IVA_Ventas,2,",",".");?>" sdnum="3082;0;#.##0">
		<?php echo number_format($Acumulado_IVA_Ventas,2,",",".");?>&nbsp;
		</td>
		<td align="right" sdval="<?php echo number_format($Acumulado_IVA_Compras,2,",",".");?>" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">
		<?php echo number_format($Acumulado_IVA_Compras,2,",",".");?>&nbsp;
		</font></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><font face="Calibri" color="#984807">0</font></td>
		<td align="left"><br></td>
		<td align="right" sdval="<?php echo number_format($Iva_Retenciones,2,",",".");?>" sdnum="3082;0;#.##0">
		<?php echo number_format($Iva_Retenciones,2,",",".");?>&nbsp;
		</td>
		<td align="left"><br></td>
		<td align="left"><br></td>

<?php
$Total_Iva_Retenciones+=$Iva_Retenciones;
/*Pagos a la DGI*/
$sql_dgi="SELECT * FROM pagodgi WHERE `mes`='".$x."' AND `anio`='".$anio."'";
$r_dgi=mysqli_query($GLOBALS["___mysqli_ston"], $sql_dgi);

if($r_dgi !==false) {
$Saldo_Retenciones=$Iva_Retenciones+$Saldo_Retenciones - @mysqli_result($r_dgi, 0, "f546");

if (mysqli_num_rows($r_dgi)>0) {
	$cant=0;
	while($cant<mysqli_num_rows($r_dgi)) {
		$f546+=mysqli_result($r_dgi, $cant, "f546");
		$f108+=mysqli_result($r_dgi, $cant, "f108");
		$f328+=mysqli_result($r_dgi, $cant, "f328");
		$f606+=mysqli_result($r_dgi, $cant, "f606");
		$cant++;
	}
		$fechaDGI=mysqli_result($r_dgi, 0, "fecha");

} else {
$f546=0;
$f108=0;
$f328=0;
$f606=0;
$fechaDGI='';
}
} else {
$f546=0;
$f108=0;
$f328=0;
$f606=0;
$Saldo_Retenciones=$Iva_Retenciones+$Saldo_Retenciones;
$fechaDGI='';
}

?>			
		
		<td align="right" sdval="2148,4" sdnum="3082;0;#.##0">
		<?php echo number_format($Saldo_Retenciones,2,",",".");?>&nbsp;
		</td>

		<td align="left"><br></td>
		<td align="right" valign=bottom sdval="<?php echo $f546;?>" sdnum="3082;0;#.##0">
		<font face="Calibri" color="#000000">
		 <?php echo $f546;?>&nbsp;   
		 </font></td>
		<td align="right" sdval="<?php echo $f108;?>" sdnum="3082;0;#.##0">
		 <?php echo $f108;?>&nbsp;   
		</td>
		<td align="right" sdval="<?php echo $f328;?>" sdnum="3082;0;#.##0">
		 <?php echo $f328;?>&nbsp;   
		</td>
		<td align="right" sdnum="3082;0;#.##0" sdval="<?php echo $f606;?>">
		 <?php echo $f606;?>&nbsp;   
		</td>
		<td align="right" sdval="<?php echo $f546+$f108+$f328+$f606;?>" sdnum="3082;0;#.##0">
		 <?php echo $f546+$f108+$f328+$f606;?>&nbsp;   
		</td>
		<td align="right" sdval="" sdnum="3082;0;#.##0"></td>
		<td align="center" sdnum="3082;0;@">
		<?php echo implota($fechaDGI);?>&nbsp;  
		</td>
	</tr>
<?php 
$pago_f546+=$f546;
$pago_f108+=$f108;
$pago_f606+=$f606;
$pago_f328+=$f328;
$Total_pago_dgi=$pago_f546+$pago_f108+$pago_f606+$pago_f328;
	$Importe_Ventas=0;
	$Importe_Compras=0;
	$Iva_Ventas=0;
	$Iva_Compras=0;
	$x++;
$f546=0;
$f108=0;
$f328=0;
$f606=0;	
	}
?>	

	<tr>
		<td height="18" align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000"><br></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="right" sdval="<?php echo number_format($Acumulado_IVA_Ventas,2,",",".");?>" sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#000000">
		<?php echo number_format($Acumulado_IVA_Ventas,2,",",".");?>&nbsp;
		</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($Acumulado_IVA_Compras,2,",",".");?>" sdnum="3082;0;#.##0"><b>
		<font face="Calibri" color="#000000">
		<?php echo number_format($Acumulado_IVA_Compras,2,",",".");?>&nbsp;
		</font></b></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000">&nbsp;</font></b></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000">&nbsp;</font></b></td>
		<td align="right" sdval="<?php echo number_format($Total_Ventas,2,",",".");?>" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000">
		<?php echo number_format($Total_Ventas,2,",",".");?>&nbsp;
		</font></b></td>
		<td align="right" sdval="<?php echo number_format($Total_Compras,2,",",".");?>" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000">
		<?php echo number_format($Total_Compras,2,",",".");?>&nbsp;
		</font></b></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000">&nbsp;</font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="right" sdnum="3082;0;#.##0" sdval="<?php echo number_format($Total_Iva_Retenciones,2,",",".");?>"><b><font face="Calibri" color="#FF0000">
		<?php echo number_format($Total_Iva_Retenciones,2,",",".");?>&nbsp;
		</font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000"><br></font></b></td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000">&nbsp;</font></b></td>
		<td align="right" sdnum="3082;0;#.##0" sdval="<?php echo number_format($Saldo_Retenciones,2,",",".");?>">
		<?php echo number_format($Saldo_Retenciones,2,",",".");?>&nbsp;
		</td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#FF0000">&nbsp;</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $pago_f546;?>"
		 sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#FF0000">
		<?php echo $pago_f546;?>&nbsp;
		</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $pago_f108;?>"
		 sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#FF0000">
		<?php echo $pago_f108;?>&nbsp;
		</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $pago_f328;?>"
		 sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#FF0000">
		<?php echo $pago_f328;?>&nbsp;
		</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $pago_f606;?>"
		 sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#FF0000">
		<?php echo $pago_f606;?>&nbsp;
		</font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $Total_pago_dgi;?>"
		 sdnum="3082;0;#.##0">
		<b><font face="Calibri" color="#FF0000">
		<?php echo $Total_pago_dgi;?>&nbsp;
		</font></b></td>
		<td align="left" sdnum="3082;0;#.##0"><b><font face="Calibri" color="#000000"><br></font></b></td>
		<td align="left" sdnum="3082;0;@"><b><font face="Calibri" color="#000000"><br></font></b></td>
	</tr>

	<tr>
		<td height="18" align="left" sdnum="3082;0;MMM - AA" colspan="2">iva compras excedente</td>
		<td align="right" sdval="<?php echo number_format($Acumulado_IVA_Compras-$Acumulado_IVA_Ventas,2,",",".");?>" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000">
		<?php echo number_format($Acumulado_IVA_Compras-$Acumulado_IVA_Ventas,2,",",".");?>&nbsp;
		</font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#984807"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0">retenciones</td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#FF0000"><br></font></td>
		<td align="left" sdnum="3082;0;#.##0"><font face="Calibri" color="#17375E"><br></font></td>
		<td align="right" valign=bottom sdval="8397" sdnum="3082;0;#.##0"><font face="Calibri" color="#000000"> ????8.397 &nbsp;</font></td>
		<td align="left" sdnum="3082;0;#.##0">=</td>
		<td align="right" sdval="8397" sdnum="3082;0;#.##0">?????8.397&nbsp;</td>
		<td align="left">+&nbsp;</td>
		<td align="right" sdval="0" sdnum="3082;0;#.##0">0</td>
		<td align="left"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
		<td align="left" sdnum="3082;0;#.##0"><br></td>
	</tr>
</table>
<!-- ************************************************************************** -->
</div>
</div>
</div>
</div>
</div>
</body>

</html>
