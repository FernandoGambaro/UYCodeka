<?php
//setlocale('LC_ALL', 'es_ES');

include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

	
$startTime =isset($_GET['fechaini']) ? $_GET['fechaini'] : date("d/m/Y"); 
$endTime = isset($_GET['fechafin']) ? $_GET['fechafin'] : date("d/m/Y") ; 

$startTime =data_first_month_day(explota($startTime)); 
$endTime = data_last_month_day(explota($endTime)); 


$sTime=$startTime;
 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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
      <div class="header-background cabeceraTabla"> <b><i><font size=3>Detalles compra – venta - <?php echo genMonth_Text(date('m',strtotime($sTime)));?> de <?php echo date('Y',strtotime($sTime));?></font></i></b></div>      			
<div class="fixed-table-container-inner">
	<table  width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">

	<colgroup width="10"></colgroup>
	<colgroup width="52"></colgroup>
	<colgroup width="72"></colgroup>
	<colgroup width="53"></colgroup>
	<colgroup width="53"></colgroup>
	<colgroup width="51"></colgroup>
	<colgroup width="53"></colgroup>
	<colgroup width="52"></colgroup>
	<colgroup width="52"></colgroup>
	<colgroup span="4" width="72"></colgroup>
	
	<tr>
		<td height="22" align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td align="left"><font size=3><br></font></td>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=2 align="center" valign=middle bgcolor="#B2B2B2"><b><i><font face="ZapfHumnst BT" size=3>Compras</font></i></b></td>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=2 align="center" valign=middle bgcolor="#B2B2B2"><b><i><font face="ZapfHumnst BT" size=3>Ventas</font></i></b></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="20" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Fecha</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Cliente / Proveedor</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Documento</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Moneda</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Importe</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>% Impuesto</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>T/C</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>IVA</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Total</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>IVA</font></i></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" bgcolor="#CCCCCC"><b><i><font face="ZapfHumnst BT" size=3>Total</font></i></b></td>
	</tr>
	
<?php

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array();
	
							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }			
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	
$resguardo=0;

	while (strtotime($startTime) <= strtotime($endTime)) {

			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime($startTime)));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."' order by fecha DESC";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
  			$tipocambio=mysqli_result($res_tipocambio, 0, "valor");   		

			$sel_resultado="SELECT codfactura,clientes.nombre as nombre,facturas.fecha as fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda,clientes.empresa,clientes.apellido
			FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   $marcaestado=0;						   
		   while ($contador < mysqli_num_rows($res_resultado)) { 

				$tipoc=$tipo[mysqli_result($res_resultado, $contador, "tipo")];

				if (mysqli_result($res_resultado, $contador, "empresa")!='') {
					$nombre= mysqli_result($res_resultado, $contador, "empresa");
					} elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {
						$nombre= mysqli_result($res_resultado, $contador, "nombre");
					} else {
						$nombre= mysqli_result($res_resultado, $contador, "nombre"). ' ' . mysqli_result($res_resultado, $contador, "apellido");
					}
					

/* Sector ventas*/
?>	
	
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="17" align="center" sdval="<?php echo implota($startTime);?>" sdnum="3082;0;DD/MM/AA">
		<font face="GillSans"><?php echo implota($startTime);?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="left" valign=middle>
		<font face="GillSans">&nbsp;<?php echo $nombre; ?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo mysqli_result($res_resultado, $contador, "codfactura");?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo mysqli_result($res_resultado, $contador, "codfactura");?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center">
		<font face="GillSans"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".");?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
							echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".");
							} else {
							echo number_format(mysqli_result($res_resultado, $contador, "totalfactura")*(-1),2,",",".");
							}			?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="" sdnum="3082;0;0,00"><font face="GillSans">
<?php
	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			echo mysqli_result($res_iva, 0, "nombre");
?>		
		&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($tipocambio,3,",",".")?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php
		if (mysqli_result($res_resultado, $contador, "moneda")==2){
		 echo number_format($tipocambio,3,",",".");
		 }
		 ?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" sdnum="">
		<font face="GillSans"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" sdnum="">
		<font face="GillSans"><br></font></td>
		<?php
 			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_iva, 0, "valor"));
			} else {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));
			}		
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Iva_Ventas+=$iva;		 
		 $Ventas= number_format($iva,2,",",".");
		 } else {
		 $Iva_Ventas+=$iva*$tipocambio;
		 $Ventas= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;?>
		 		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $Ventas;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $Ventas;?>&nbsp;</font></td>
		<?php
			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
			 $total= mysqli_result($res_resultado, $contador, "totalfactura");
			} else {
			 $total= mysqli_result($res_resultado, $contador, "totalfactura")*(-1);
			}		
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Total_Ventas+=$total;		 
			$TVentas= number_format($total,2,",","."); 
		 } else {
		 $Total_Ventas+=$total*$tipocambio;
			$TVentas= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0; ?>		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right" sdval="<?php echo $TVentas;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $TVentas;?>&nbsp;</font></td>		 
	</tr>
<?php
	 			$contador++;
	 			$Cant_Ventas++;
			}
			
/*  Notas de crédito */			
			

			$sel_ncredito="SELECT codncredito,clientes.nombre as nombre,ncredito.fecha as fecha,total,estado,ncredito.iva,ncredito.moneda,clientes.empresa,clientes.apellido
			FROM ncredito,clientes WHERE ncredito.borrado=0 AND ncredito.codcliente=clientes.codcliente AND fecha ='".$startTime."'";
		
			$res_ncredito=mysqli_query($GLOBALS["___mysqli_ston"], $sel_ncredito);
		   $conta=0;
		   $marcaestado=0;						   
		   while ($conta < mysqli_num_rows($res_ncredito)) { 

				//$tipoc=$tipo[mysql_result($res_ncredito,$res_ncredito,"tipo")];

				if (mysqli_result($res_ncredito, $conta, "empresa")!='') {
					$nombre= mysqli_result($res_ncredito, $conta, "empresa");
					} elseif (mysqli_result($res_ncredito, $conta, "apellido")=='') {
						$nombre= mysqli_result($res_ncredito, $conta, "nombre");
					} else {
						$nombre= mysqli_result($res_ncredito, $conta, "nombre"). ' ' . mysqli_result($res_ncredito, $conta, "apellido");
					}

?>	
	
	<tr style="background-color: red;">
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="17" align="center" sdval="<?php echo implota($startTime);?>" sdnum="3082;0;DD/MM/AA">
		<font face="GillSans"><?php echo implota($startTime);?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="left" valign=middle>
		<font face="GillSans">&nbsp;<?php echo $nombre; ?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo mysqli_result($res_ncredito, $conta, "codncredito");?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo mysqli_result($res_ncredito, $conta, "codncredito");?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center">
		<font face="GillSans"><?php echo $moneda[mysqli_result($res_ncredito, $conta, "moneda")];?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format(mysqli_result($res_ncredito, $conta, "total"),2,",",".");?>" sdnum="3082;0;0,00">
		<font face="GillSans">
		<?php 
				echo number_format(mysqli_result($res_ncredito, $conta, "total")*(-1),2,",",".");
		?>
							&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="" sdnum="3082;0;0,00"><font face="GillSans">
<?php
	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			echo mysqli_result($res_iva, 0, "nombre");
?>				
		&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($tipocambio,3,",",".")?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php
		if (mysqli_result($res_ncredito, $conta, "moneda")==2){
		 echo number_format($tipocambio,3,",",".");
		 }
		 ?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" sdnum="">
		<font face="GillSans"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" sdnum="">
		<font face="GillSans"><br></font></td>
		<?php
		 $iva = mysqli_result($res_ncredito, $conta, "total")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));

		 if (mysqli_result($res_ncredito, $conta, "moneda")==1){
		 $Iva_Ventas+=$iva;		 
		 $Ventas= number_format($iva,2,",",".");
		 } else {
		 $Iva_Ventas+=$iva*$tipocambio;
		 $Ventas= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;?>
		 		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $Ventas;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $Ventas;?>&nbsp;</font></td>
		<?php
			 $total= mysqli_result($res_ncredito, $conta, "total")*(-1);

		 if (mysqli_result($res_ncredito, $conta, "moneda")==1){
		 $Total_Ventas+=$total;		 
			$TVentas= number_format($total,2,",","."); 
		 } else {
		 $Total_Ventas+=$total*$tipocambio;
			$TVentas= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0; ?>		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right" sdval="<?php echo $TVentas;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $TVentas;?>&nbsp;</font></td>		 
	</tr>
<?php
	 			$conta++;
	 			$Cant_Ventas++;
			}			
			
			
			
			
/*----------------------*/
			
/* Sector compras*/
				$sel_resultado="SELECT codfactura,proveedores.nombre as nombre,facturasp.fecha as fecha,proveedores.codproveedor,totalfactura,facturasp.iva,estado,moneda,tipo
				FROM facturasp,proveedores WHERE facturasp.codproveedor=proveedores.codproveedor AND fecha ='".$startTime."'";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;	
					if($res_resultado) {					   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
?>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="17" align="center" sdval="<?php echo implota($startTime);?>" sdnum="3082;0;DD/MM/AA">
		<font face="GillSans"><?php echo implota($startTime);?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="left" valign=middle>
		<font face="GillSans">&nbsp;<?php echo mysqli_result($res_resultado, $contador, "nombre")?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right">
		<font face="GillSans"><?php echo mysqli_result($res_resultado, $contador, "codfactura")?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center">
		<font face="GillSans"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".")?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
							echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".");
							} else {
							echo number_format(mysqli_result($res_resultado, $contador, "totalfactura")*(-1),2,",",".");
							}		
		?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="" sdnum="3082;0;0,000"><font face="GillSans">
<?php
	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			echo mysqli_result($res_iva, 0, "nombre");
?>				
		&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($tipocambio,3,",",".")?>" sdnum="3082;0;0,000">
		<font face="GillSans"><?php
		if (mysqli_result($res_resultado, $contador, "moneda")==2){
		 echo number_format($tipocambio,3,",",".");
		 }
		 ?>&nbsp;</font></td>
		 <?php
		 if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_iva, 0, "valor"));
		 } else {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));
		 }			
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Iva_Compras+=$iva;		 
		 $Compras= number_format($iva,2,",",".");
		 } else {
		 $Iva_Compras+=$iva*$tipocambio;
		 $Compras= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;?>	
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo $Compras;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $Compras;?>&nbsp; </font></td>
		<?php 
		if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		$total= mysqli_result($res_resultado, $contador, "totalfactura");
		} else {
		$total= mysqli_result($res_resultado, $contador, "totalfactura")*(-1);
		}				
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Total_Compras+=$total;		 
			$TCompras= number_format($total,2,",","."); 
		 } else {
		 $Total_Compras+=$total*$tipocambio;
			$TCompras= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0; ?>		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right" sdval="<?php echo $TCompras;?>" sdnum="3082;0;0,00">
		<font face="GillSans"><?php echo $TCompras;?>&nbsp;</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" sdnum="3082;0;0,000">
		<font face="GillSans"><br></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" sdnum="3082;0;0,00">
		<font face="GillSans"><br></font></td>
	</tr>
<?php
		$contador++;
		$Cant_Compras++;	
		}
		}
/*Cálculo resguardo*/		
			$sqlcobro="SELECT * FROM `cobros` WHERE `fechacobro` = '".$startTime."' AND `resguardo` = 1  ";
			$rsql=mysqli_query($GLOBALS["___mysqli_ston"], $sqlcobro);
			$cuento=0;
				while ($cuento < mysqli_num_rows($rsql)) {
					
					if (mysqli_result($rsql, $cuento, "moneda")==2){
						$sqlfactura="SELECT codfactura,fecha FROM facturas WHERE `codfactura`= '".mysqli_result($rsql, $cuento, "codfactura")."'";
						$rfactura=mysqli_query($GLOBALS["___mysqli_ston"], $sqlfactura);
						$fechafactura=mysqli_result($rfactura, 0, "fecha");
					
			   		$stipocambio="SELECT valor FROM tipocambio WHERE fecha <'".$fechafactura."'";
			   		$rtipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $stipocambio);
			   		while ($row=mysqli_fetch_array($rtipocambio)) {
			   			$tc=$row['valor'];
			   		} 		
						$resguardo= $resguardo + mysqli_result($rsql, $cuento, "importe") * $tc;
					} else {
						$resguardo=$resguardo + mysqli_result($rsql, $cuento, "importe");
					}
					
				$cuento++;	
				}
		
			$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));

	}

?>	


	<tr>
		<td height="18" align="left" colspan="2">Documentos&nbsp;Compras: <?php echo $Cant_Compras;	?></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=2 align="center" valign=middle sdnum="3082;0;0,00"><b><i><u><font face="ZapfHumnst BT">Compras</font></u></i></b></td>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" colspan=2 align="center" valign=middle><b><i><u><font face="ZapfHumnst BT">Ventas</font></u></i></b></td>
		</tr>
	<tr>
		<td height="18" align="left" colspan="2">Documentos&nbsp;Ventas: <?php echo $Cant_Ventas;	?></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#FFFF00" sdval="<?php echo number_format($Iva_Compras,2,",",".");?>" sdnum="3082;0;0,00">
		<font face="ZapfHumnst BT"><?php echo number_format($Iva_Compras,2,",",".");?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#FFFF00" sdval="<?php echo number_format($Total_Compras,2,",",".");?>" sdnum="3082;0;0,00">
		<font face="ZapfHumnst BT"><?php echo number_format($Total_Compras,2,",",".");?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" bgcolor="#FFFF00" sdval="<?php echo number_format($Iva_Ventas,2,",",".");?>" sdnum="3082;0;0,00">
		<font face="ZapfHumnst BT"><?php echo number_format($Iva_Ventas,2,",",".");?></font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" bgcolor="#FFFF00" sdval="<?php echo number_format($Total_Ventas,2,",",".");?>" sdnum="3082;0;0,00">
		<font face="ZapfHumnst BT"><?php echo number_format($Total_Ventas,2,",",".");?></font></td>
	</tr>
	<tr>
		<td height="17" align="left"><br></td>
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
		<td height="17" align="left"><br></td>
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
<?php 
/*Pagos DGI*/
$x=date("m",strtotime($sTime))-1;
$anio=date("Y",strtotime($sTime));

$sql_dgi="SELECT * FROM pagodgi WHERE `mes`='".$x."' AND `anio`='".$anio."'";

$r_dgi=@mysqli_query($GLOBALS["___mysqli_ston"], $sql_dgi);

?>	
	<tr>
		<td colspan=3 height="20" align="center" valign=middle><b><font size=3>Ultimo Pago DGI: <?php echo @implota(mysqli_result($r_dgi, 0, "fecha"));?></font></b></td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#B2B2B2">
		<b><font size=3><?php echo @genMonth_Text(date('m',strtotime($sTime)));?></font></b></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#B2B2B2">
		<b><font size=3>Resguardo <?php echo @genMonth_Text(date('m',strtotime($sTime)));?></font></b></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=2 align="center" valign=middle bgcolor="#B2B2B2">
		<b><font size=3>Acumulado <?php echo @genMonth_Text(date('m',strtotime($sTime)));?></font></b></td>
		</tr>
	<tr>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="17" align="left" valign=middle>MES <?php echo $x."-".$anio;?></td>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000;" height="17" align="left" valign=middle>108 – IRAE Anticipo:</td>
		<td style="border-top: 2px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right" sdval="3270" sdnum="3082;">
		<?php echo @mysqli_result($r_dgi, 0, "f108");?>&nbsp; </td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" bgcolor="#CCCCCC">Saldo IVA</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($Iva_Ventas-$Iva_Compras,2,",",".");?>" sdnum="3082;0;0,00">
<?php echo @number_format($Iva_Ventas-$Iva_Compras,2,",",".");?>		
		</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" bgcolor="#CCCCCC">Importe</td>		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" sdnum="3082;0;0,00">&nbsp;
		<?php echo @number_format($resguardo,2,",",".")?>

		<br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" bgcolor="#CCCCCC">IVA</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left">
		<br></td>
	</tr>
	<tr>
		<td style=" border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="17" align="left" valign=middle>MES <?php echo $x."-".$anio;?></td>
		<td style=" border-bottom: 1px solid #000000;" height="17" align="left" valign=middle>328 – Impuesto al Patrimonio Anticipo:</td>
		<td style=" border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right" sdval="928" sdnum="3082;">
		<?php echo @mysqli_result($r_dgi, 0, "f328");?>&nbsp;</td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" bgcolor="#CCCCCC">Saldo Total</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="right" sdval="<?php echo number_format($Total_Ventas-$Total_Compras,2,",",".");?>" sdnum="3082;">
		<?php echo @number_format($Total_Ventas-$Total_Compras,2,",",".");?>		
		</td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td align="left"><br></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" bgcolor="#CCCCCC">Total</td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left">
		<br></td>
	</tr>
	<tr>
		<td style=" border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="17" align="left" valign=middle>MES <?php echo $x."-".$anio;?></td>
		<td style=" border-bottom: 1px solid #000000;" height="17" align="left" valign=middle>546 – IVA Contribuyentes No CEDE :</td>
		<td style=" border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right">
		<?php echo @mysqli_result($r_dgi, 0, "f546");?>&nbsp;</td>
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
		<td style=" border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="17" align="left" valign=middle>MES <?php echo $x."-".$anio;?></td>
		<td style=" border-bottom: 2px solid #000000;" height="17" align="left" valign=middle>606 – ICOSA Anticipo:</td>
		<td style=" border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="right">
		<?php echo @mysqli_result($r_dgi, 0, "f606");?>&nbsp;</td>
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
</table>
<!-- ************************************************************************** -->
</div>
</div>
</div>
</div>
</div>

</body>

</html>
