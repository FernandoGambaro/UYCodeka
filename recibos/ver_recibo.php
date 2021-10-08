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

$baseimponible='';
$tipocambio='';
$modif='';
$total=0;

$codrecibo=$_GET["codrecibo"];

$sel_alb="SELECT * FROM recibos WHERE codrecibo='$codrecibo'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codcliente=mysqli_result($rs_alb, 0, "codcliente");
$moneda=mysqli_result($rs_alb, 0, "moneda");
$fecha=mysqli_result($rs_alb, 0, "fecha");
$importe=mysqli_result($rs_alb, 0, "importe");
$emitido=mysqli_result($rs_alb, 0, "emitido");



$sel_cliente="SELECT nombre,apellido,empresa,nif FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nombre=mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");

$nif=mysqli_result($rs_cliente, 0, "nif");

$fechahoy=date("Y-m-d");


$sel_lineas="SELECT * FROM recibosfactura WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM recibosfacturatmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$contador=0;
//echo mysql_num_rows($rs_lineas);
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codfactura=mysqli_result($rs_lineas, $contador, "codfactura");
	$sel_tmp="INSERT INTO recibosfacturatmp (codrecibo,codfactura) VALUES ('$codrecibo','$codfactura')";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	$contador++;
}

$sel_lineas="SELECT * FROM recibospago WHERE codrecibo='$codrecibo'";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM recibospagotmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$contador=0;
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codrecibo=mysqli_result($rs_lineas, $contador, "codrecibo");
	$tipo=mysqli_result($rs_lineas, $contador, "tipo");
	$codentidad=mysqli_result($rs_lineas, $contador, "codentidad");
	$numeroserie=mysqli_result($rs_lineas, $contador, "numeroserie");
	$numero=mysqli_result($rs_lineas, $contador, "numero");
	$monedapago=mysqli_result($rs_lineas, $contador, "monedapago");
	$importedoc=mysqli_result($rs_lineas, $contador, "importedoc");
	$importe=mysqli_result($rs_lineas, $contador, "importe");
	$tipocambio=mysqli_result($rs_lineas, $contador, "tipocambio");
	$total+=$importe;
	$fechapago=mysqli_result($rs_lineas, $contador, "fechapago");
	$observaciones=mysqli_result($rs_lineas, $contador, "observaciones");
	
		$sel_tmp="INSERT INTO recibospagotmp (codrecibopago,codrecibo,tipo,codentidad,numeroserie,numero,monedapago,tipocambio,importedoc,importe,fechapago,observaciones) 
			VALUES (NULL,'$codrecibo', '$tipo', '$codentidad', '$numeroserie', '$numero', '$monedapago', '$tipocambio', '$importedoc', '$importe', '$fechapago', '$observaciones')";
		$res_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);	
	
	$contador++;
}

$codentidad=0;
$tipo=0;
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

		
		<script language="javascript">
		function callGpsDiag(xx,numrecibo){
			parent.callGpsDiag(xx,numrecibo);
		}
				
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		
		function inicio() {
			document.formulario_facturas_sel.submit();
			document.formulario_pago.submit();
		}
		
				
		function imprimir(codrecibo,emitido) {
			if (emitido!=1) {
			var top = window.open("../fpdf/imprimir_recibo.php?codrecibo="+codrecibo, "recibo", "location=1,status=1,scrollbars=1");
			//setTimeout(top.close(), 3000000);				
				//parent.$('idOfDomElement').colorbox.close();
			} else {
				$.msgBox({
				    title: "Alerta",
				    content: "Quiere reimprimir recibo emitido?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});									    	
    										if (v=="1234") {
												var top = window.open("../fpdf/imprimir_recibo.php?codrecibo="+codrecibo, "recibo", "location=1,status=1,scrollbars=1");
												//setTimeout(top.close(),300000);												
												//parent.$('idOfDomElement').colorbox.close();
											} else {
												showWarningToast('Contraseña erronea');
											}
										}
								});					        	
				        }
				    }
				})(jQuery);				
				
			}
		}

		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">VER RECIBO Nº <span id="recibo"> <?php echo $codrecibo;?></span></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" value="<?php echo $codcliente;?>" data-index="1">
							</td>
							<td width="6%">Nombre</td>
						    <td width="27%"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre;?>" readonly></td>
						</tr>
						<tr>
				            <td width="5%">RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif;?>" readonly></td>
				            
						<td>Moneda</td><td width="26%">
					<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipofa as $key => $i ) {
					  	if ( $moneda==$key ) {
							echo "$i";
						} 					
					}
					?>
					</select></td>
								
				            
						</tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td width="6%">Fecha</td>
						    <td width="27%"><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha);?>" readonly> 
						    </td>
								<td width="5%">Nº recibo</td>
				            <td><input name="codrecibo" type="text" class="cajaPequena" id="codrecibo" value="<?php echo $codrecibo;?>" size="20" maxlength="15" readonly></td>
						</tr>
											
					</table>									
			  </div>

							<!-- Detalles del recibo -->
				<br style="line-height:5px">
				<div id="frmBusqueda">
				<form id="formulario_pago" name="formulario_pago" method="post" action="frame_pagos.php" target="frame_pago">
					<input name="bcodrecibo" id="bcodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
					<input type="hidden" name="modifpago" id="modifpago" value="2">
				<input id="Cmoneda" name="Cmoneda" value="<?php echo $moneda;?>" type="hidden">
					
					
				  </form>				
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				<tr><td valign="top">
				
				<form id="formulario_facturas_sel" name="formulario_facturas_sel" method="post" action="frame_facturas_sel.php" target="frame_facturas_sel">
				<input id="codfactura" name="codfactura" value="" type="hidden">
				<input id="cantidad" name="cantidad" value="0" type="hidden">
				<input name="acodrecibo" id="acodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
				<input id="modiffactu" name="modiffactu" value="2" type="hidden">				    

				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300">
				<tr>
				<td width="100%" >
					<iframe width="300" height="100" id="frame_facturas_sel" name="frame_facturas_sel" frameborder="0">
						<ilayer width="300" height="100" id="frame_facturas_sel" name="frame_facturas_sel"></ilayer>
					</iframe>					
				</td>
				</tr>
				<tr><td>
							
				</td></tr>
				</table>
				</form>
				</td>
				<td>
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%" height="160" >
				<tr>
				<td width="100%" valign="top" >
					<iframe width="550" height="160" id="frame_pago" name="frame_pago" frameborder="0">
						<ilayer width="550" height="160" id="frame_pago" name="frame_pago"></ilayer>
					</iframe>					
				</td>
				</tr>
				</table>				
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
						<button class="boletin" onClick="imprimir('<?php echo $codrecibo;?>', '<?php echo $emitido;?>');" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
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
	
			
	</body>
</html>
