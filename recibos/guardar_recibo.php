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

date_default_timezone_set("America/Montevideo"); 

$total=0;
$mensaje='';

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codrecibo=$_POST["codrecibo"];
$codcliente=$_POST["codcliente"];
$Cmoneda=$moneda=$_POST["Amoneda"];
$fecha=explota($_POST["fecha"]);
$importe=$_POST["importe"];
//$tipocambio=$_POST["tipocambio"];



if ($accion=="alta") {
	$query_operacion="INSERT INTO recibos (codrecibo, codcliente, fecha, moneda, importe, borrado) 
	VALUES ('$codrecibo', '$codcliente', '$fecha', '$moneda', '$importe', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	
	if ($rs_operacion) { $mensaje="El recibo Nº ".$codrecibo." ha sido dado de alta correctamente"; }
	
$sel_lineas="SELECT * FROM recibosfacturatmp WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

/*Elimino todos los datos de cobro de la tabla cobros para este recibo*/
$sel_borrar = "DELETE FROM recibosfactura WHERE numdocumento='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
$sel_borrar = "DELETE FROM cobros WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

	$contador=0;
	
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codfactura=mysqli_result($rs_lineas, $contador, "codfactura");
		$pago=mysqli_result($rs_lineas, $contador, "pago");
		
		$sel_factu="SELECT moneda,totalfactura FROM facturas WHERE codfactura='$codfactura'";
		$rs_factu=mysqli_query($GLOBALS["___mysqli_ston"], $sel_factu);
		$totalfactura=mysqli_result($rs_factu, 0, "totalfactura");
		$moneda=mysqli_result($rs_factu, 0, "moneda");
		
		$sel_tmp="INSERT INTO recibosfactura (codrecibo,codfactura,totalfactura,pago) VALUES ('$codrecibo','$codfactura', '$totalfactura','$pago')";
		$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	
 		$con_factura="SELECT estado FROM facturas WHERE codfactura='$codfactura'";
		$rs_con=mysqli_query($GLOBALS["___mysqli_ston"], $con_factura);
		$estado='2';	
		if(mysqli_result($rs_con, 0, "estado")=='3') {	
			$estado='3';
		}
		$act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);	
		
		/*Para mantener compatibilidad con la forma anterior de registro de cobros. */
		$sel_cobro="SELECt codfactura,numdocumento FROM cobros WHERE codfactura='$codfactura' AND numdocumento='$codrecibo'";
		$rs_cobro=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobro);
		
		if(mysqli_num_rows($rs_cobro)<1) {
		/* Agrego a la tabla cobros */	
			$sel_insertar="INSERT INTO cobros (id,codfactura,codcliente,importe,moneda,numdocumento,fechacobro) VALUES 
			('null','$codfactura','$codcliente','$totalfactura','$moneda','$codrecibo','$fecha')";
			$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);	
		} else {
			$sel_insertar="UPDATE cobros SET importe='$totalfactura', fechacobro='$fecha', moneda='$moneda' WHERE codfactura='$codfactura' AND numdocumento='$codrecibo'";
			$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);	
		}
		$contador++;
	}
		
	$sel_lineas="SELECT * FROM recibospagotmp WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
	$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codrecibo=mysqli_result($rs_lineas, $contador, "codrecibo");
		$tipo=mysqli_result($rs_lineas, $contador, "tipo");
		$codentidad=mysqli_result($rs_lineas, $contador, "codentidad");
			if(mysqli_result($rs_lineas, $contador, "numeroserie")!='') {
			$numeroserie=mysqli_result($rs_lineas, $contador, "numeroserie");
			} else {
			$numeroserie='null';				
			}
			if(mysqli_result($rs_lineas, $contador, "numero")!='') {
			$numero=mysqli_result($rs_lineas, $contador, "numero");
			} else {
			$numero='null';				
			}
		$monedapago=mysqli_result($rs_lineas, $contador, "monedapago");
		$importedoc=mysqli_result($rs_lineas, $contador, "importedoc");
		$importe=mysqli_result($rs_lineas, $contador, "importe");
		$tipocambio=mysqli_result($rs_lineas, $contador, "tipocambio");
			if($importe=="") {
				$importe=$importedoc;
			}		
		$total+=$importe;
		$fechapago=mysqli_result($rs_lineas, $contador, "fechapago");
		$observaciones=mysqli_result($rs_lineas, $contador, "observaciones");
		
			$sel_tmp="INSERT INTO recibospago (codrecibopago,codrecibo,tipo,codentidad,numeroserie,numero,monedapago,tipocambio,importedoc,importe,fechapago,observaciones) 
				VALUES (NULL,'$codrecibo', '$tipo', '$codentidad', $numeroserie, $numero, '$monedapago', '$tipocambio', '$importedoc', '$importe', '$fechapago', '$observaciones')";
			$res_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);

		$contador++;
	}	
      	
	$sel_act="UPDATE recibos SET importe='$total' WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	

	$cabecera1="Inicio >> Ventas &gt;&gt; Nueva Factura ";
	$cabecera2="INSERTAR RECIBO ";
}

/* ******************* MODIFICAR ******************** */
if ($accion=="modificar") {
	$codrecibo=$_POST["codrecibo"];
	$act_albaran="UPDATE recibos SET codcliente='$codcliente', fecha='$fecha', moneda='$moneda' WHERE codrecibo='$codrecibo'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	
	$sel_lineas="SELECT * FROM recibosfacturatmp WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
	$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	
	$sel_borrar = "DELETE FROM recibosfactura WHERE codrecibo='$codrecibo'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	/*Elimino todos los datos de cobro de la tabla cobros para este recibo*/
	$sel_borrar = "DELETE FROM recibosfactura WHERE numdocumento='$codrecibo'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codfactura=mysqli_result($rs_lineas, $contador, "codfactura");
		
		$sel_factu="SELECT moneda,totalfactura FROM facturas WHERE codfactura='$codfactura'";
		$rs_factu=mysqli_query($GLOBALS["___mysqli_ston"], $sel_factu);
		$totalfactura=mysqli_result($rs_factu, 0, "totalfactura");
		$moneda=mysqli_result($rs_factu, 0, "moneda");
		
		
		$sel_tmp="INSERT INTO recibosfactura (codrecibo,codfactura,totalfactura) VALUES ('$codrecibo','$codfactura', '$totalfactura')";
		$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	
 		$con_factura="SELECT estado FROM facturas WHERE codfactura='$codfactura'";
		$rs_con=mysqli_query($GLOBALS["___mysqli_ston"], $con_factura);
		$estado='2';	
		if(mysqli_result($rs_con, 0, "estado")=='3') {	
			$estado='3';
		}
		$act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);	
		
		/*Para mantener compatibilidad con la forma anterior de registro de cobros. */
		$sel_cobro="SELECt codfactura,numdocumento FROM cobros WHERE codfactura='$codfactura' AND numdocumento='$codrecibo'";
		$rs_cobro=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobro);
		if(mysqli_num_rows($rs_cobro)<1) {
		/* Agrego a la tabla cobros */	
			$sel_insertar="INSERT INTO cobros (id,codfactura,codcliente,importe,moneda,numdocumento,fechacobro) VALUES 
			('null','$codfactura','$codcliente','$totalfactura','$moneda','$codrecibo','$fecha')";
			$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);	
		} else {
			$sel_insertar="UPDATE cobros SET importe='$totalfactura', fechacobro='$fecha', moneda='$moneda' WHERE codfactura='$codfactura' AND numdocumento='$codrecibo'";
			$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);	
		}
		$contador++;
	}
	
	$sel_lineas="SELECT * FROM recibospagotmp WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
	$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	
	$sel_borrar = "DELETE FROM recibospago WHERE codrecibo='$codrecibo'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
			$codrecibo=mysqli_result($rs_lineas, $contador, "codrecibo");
			$tipo=mysqli_result($rs_lineas, $contador, "tipo");
			$codentidad=mysqli_result($rs_lineas, $contador, "codentidad");
			if(mysqli_result($rs_lineas, $contador, "numeroserie")!='') {
			$numeroserie=mysqli_result($rs_lineas, $contador, "numeroserie");
			} else {
			$numeroserie='null';				
			}
			if(mysqli_result($rs_lineas, $contador, "numero")!='') {
			$numero=mysqli_result($rs_lineas, $contador, "numero");
			} else {
			$numero='null';				
			}

			$monedapago=mysqli_result($rs_lineas, $contador, "monedapago");
			$importedoc=mysqli_result($rs_lineas, $contador, "importedoc");
			$importe=mysqli_result($rs_lineas, $contador, "importe");
			$tipocambio=mysqli_result($rs_lineas, $contador, "tipocambio");
			if($importe=="") {
				$importe=$importedoc;
			}
			$total+=$importe;
			$fechapago=mysqli_result($rs_lineas, $contador, "fechapago");
			$observaciones=mysqli_result($rs_lineas, $contador, "observaciones");
			
			$sel_tmp="INSERT INTO recibospago (codrecibopago,codrecibo,tipo,codentidad,numeroserie,numero, monedapago,tipocambio,importedoc,importe, fechapago,observaciones)
			VALUES (NULL,'$codrecibo', '$tipo', '$codentidad', $numeroserie, $numero, '$monedapago', '$tipocambio', '$importedoc', '$importe', '$fechapago', '$observaciones')";
				$res_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
				
		$contador++;
	}

	$sel_act="UPDATE recibos SET importe='$total' WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);


	if ($rs_act) { $mensaje="Los datos del recibo Nº ".$codrecibo." han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar Recibo ";
	$cabecera2="MODIFICAR RECIBO ";
	
	
}

if ($accion=="baja") {
	$codrecibo=$_GET["codrecibo"];
	$query="UPDATE recibos SET borrado=1 WHERE codrecibo='$codrecibo'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$query="SELECT * FROM factulinea WHERE codrecibo='$codrecibo' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	
	if ($rs_query) { $mensaje="El recibo ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Recibo";
	$cabecera2="ELIMINAR RECIBO";
	$query_mostrar="SELECT * FROM recibos WHERE codrecibo='$codrecibo'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

$sel_cliente="SELECT nombre,apellido,empresa,nif FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nombre=mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");

$nif=mysqli_result($rs_cliente, 0, "nif");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="../js3/jquery.min.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.colorbox.js"></script>

		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">


<script type="text/javascript">
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			parent.$('idOfDomElement').colorbox.close();
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break; 
	 }
});
</script>		
		<script language="javascript">
		function callGpsDiag(xx,numfactura){
			window.parent.callGpsDiag(xx,numfactura);
		}
				
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function imprimir(codrecibo) {
			var top = window.open("../fpdf/imprimir_recibo.php?codrecibo="+codrecibo, "recibo", "location=1,status=1,scrollbars=1");
			//top.close();
			//parent.$('idOfDomElement').colorbox.close();
		}

		function inicio() {
	var moneda=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
	$("#moneda").val(moneda);
	$("#Cmoneda").val(moneda);

			document.getElementById("modiffactu").value=2;
			document.formulario_facturas_sel.submit();
			
			document.getElementById("modifpago").value=2;
			document.formulario_pago.submit();
		}
		
		function pon_importe(importe) {
			if (importe!="undefined" && !isNaN(importe)) {
				importe=round(importe, 2);
				$("#totalrecibo").val(importe);
			} else {
				$("#totalrecibo").val(0);
			}	
				var saldo=$("#apagar").val()-$("#totalrecibo").val();
				saldo=round(saldo, 2);
				$("#saldo").val(saldo);
		}
		function pon_apagar(importe) {
			importe=round(importe, 2);
			$("#apagar").val(importe);
			var saldo=$("#apagar").val()-$("#totalrecibo").val();
			saldo=round(saldo, 2);
			$("#saldo").val(saldo);
		}	
			
		</script>
	</head>
	<body onload="cambiomoneda();">
<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" value="<?php echo $codcliente?>" readonly data-index="1">
							<td width="6%">Nombre</td>
						    <td width="27%"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly></td>
						</tr>
						<tr>
				            <td width="5%">RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
				            
						<td>Moneda</td><td width="26%">
						<select name="Amoneda" id="Amoneda" class="cajaPequena2" data-index="2" readonly>
					<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
					if ($moneda==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach ($tipofa as $key => $i ) {
					  	if ( $moneda==$key ) {
							echo "<option value=$key selected>$i</option>";
						} else {
							echo "<option value=$key>$i</option>";
						}

					}
					?>
					</select></td>
								
				            
						</tr>
						<tr>
							<td width="6%">Fecha</td>
						    <td width="27%"><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly data-index="3"> 
								</td>
				            <td colspan="3">Tipo&nbsp;Cambio
								<label>$&nbsp;->&nbsp;U$S</label><span>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="" data-index="4"></span>
								<!--<input type="checkbox" name="usartc" style="vertical-align: middle; margin-top: -1px;">Buscar T/C</input>-->
								</td>
						</tr>
											
					</table>	
					</form>								
			  </div>
				<!-- Detalles del recibo -->
				<br style="line-height:5px">
				<div id="frmBusqueda">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				<tr><td valign="top">
				
				<form id="formulario_facturas_sel" name="formulario_facturas_sel" method="post" action="frame_facturas_sel.php" target="frame_facturas_sel">
				<input name="acodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
				<input id="modiffactu" name="modiffactu" value="2" type="hidden">				    

				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300">
				<tr>
				<td width="100%" >
					<iframe width="300" height="100" id="frame_facturas_sel" name="frame_facturas_sel" frameborder="0">
						<ilayer width="300" height="100" id="frame_facturas_sel" name="frame_facturas_sel"></ilayer>
					</iframe>					
				</td>
				</tr>
				
				</table>
				</form>
				</td>
				<td>
				<form id="formulario_pago" name="formulario_pago" method="post" action="frame_pagos.php" target="frame_pago">
					<input name="bcodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
					<input type="hidden" name="modifpago" id="modifpago" value="2">
				<input id="Cmoneda" name="Cmoneda" value="" value="<?php echo $Cmoneda;?>" type="hidden">
				
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%" height="160" >
				<tr>
				<td width="100%" valign="top" >
					<iframe width="550" height="160" id="frame_pago" name="frame_pago" frameborder="0" >
						<ilayer width="550" height="160" id="frame_pago" name="frame_pago"></ilayer>
					</iframe>					
				</td>
				</tr>
				</table>	
				</form>			
				</td>
				</tr></table>
				</div>
				<br style="line-height:5px">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
					<tr>
						<td align="right">Total a pagar&nbsp;<input type="text" id="apagar" value="0" name="apagar" class="cajaPequena" readonly></td>
						<td align="right">Total recibo&nbsp;<input type="text" id="totalrecibo" value="0" name="totalrecibo" class="cajaPequena" readonly></td>
						<td align="right">Saldo&nbsp;<input type="text" id="saldo" name="saldo" value="0" class="cajaPequena" readonly></td>
						<td>
				<br style="line-height:5px">
					<div align="center">
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
						<button class="boletin" onClick="imprimir(<?php echo $codrecibo;?>);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
					</div>
				</td>
				</tr> 				
				</table>			  
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
		  </div>
		</div>
		<script type="text/javascript">
		inicio();
		</script>
	</body>
</html>
