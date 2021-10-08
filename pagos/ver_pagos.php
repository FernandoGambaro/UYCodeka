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
 
$codfactura=$_GET["codfactura"];
$codproveedor=$_GET["codproveedor"];


 $select_facturas="SELECT proveedores.codproveedor,proveedores.nombre,facturasp.codfactura,estado,fecha,facturasp.fechapago,facturasp.moneda,totalfactura
 FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor 
 INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.codfactura='$codfactura' AND facturasp.codproveedor='$codproveedor'";

$rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $select_facturas);

$hoy=date("d/m/Y");

$sel_cobros="SELECT sum(importe) as aportaciones FROM pagos WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
$rs_cobros=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobros);
$aportaciones=mysqli_result($rs_cobros, 0, "aportaciones");

//$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $tipomon[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }	

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>	
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
			<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />		
		<script src="js/jquery.colorbox.js"></script>
		<script language="javascript">
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function cambiar_estado() {
			var estado=document.getElementById("cboEstados").value;
			var codfactura=document.getElementById("codfactura").value;
			var codproveedor=document.getElementById("codproveedor").value;
			miPopup = window.open("actualizarestado.php?estado="+estado+"&codfactura="+codfactura+"&codproveedor="+codproveedor,"frame_datos","width=700,height=80,scrollbars=yes");
		}
		
		function cambiar_vencimiento() {
			var fechapago=document.getElementById("fechapago").value;
			var codfactura=document.getElementById("codfactura").value;
			var codproveedor=document.getElementById("codproveedor").value;
			miPopup = window.open("actualizarvencimiento.php?fechapago="+fechapago+"&codfactura="+codfactura+"&codproveedor="+codproveedor,"frame_datos","width=700,height=80,scrollbars=yes");
		}
		
		function actualizar() {
			var importe=document.getElementById("Rimporte").value;
			var pendiente=document.getElementById("pendiente").value;
			var falta=pendiente-importe;
			if (falta < 1) {
			$(".cboEstados").val(2);
			cambiar_estado();
			}						
			validar(formulario,true);
			
		}
			
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">PAGOS </div>
				<div id="frmBusqueda">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border="0" >
				<tr><td valign="top" align="center">
				<form id="formdatos" name="formdatos" method="post" action="guardar_pagos.php">
					<table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border=0>
					<?php 
					 	$codproveedor=mysqli_result($rs_facturas, 0, "codproveedor");
						$nombre=mysqli_result($rs_facturas, 0, "nombre");
						$codfactura=mysqli_result($rs_facturas, 0, "codfactura");
						$totalfactura=mysqli_result($rs_facturas, 0, "totalfactura");
						$estado=mysqli_result($rs_facturas, 0, "estado"); 
						$fechapago=mysqli_result($rs_facturas, 0, "fechapago");
						$moneda=$tipomon[mysqli_result($rs_facturas, 0, "moneda")]; 
						
						if ($fechapago=="0000-00-00") { $fechapago=""; } else { $fechapago=implota($fechapago); } 						
						?>
						<tr>
							<td width="15%">C&oacute;digo&nbsp;de&nbsp;proveedor</td>
						    <td width="43%"><?php echo $codproveedor?></td>

							<td width="15%">Nombre</td>
						    <td width="43%"><?php echo $nombre?></td>
					        
						</tr>	
						<tr>
							<td width="15%">C&oacute;digo&nbsp;de&nbsp;factura</td>
						    <td width="43%"><?php echo $codfactura?></td>

							<td width="15%">Importe&nbsp;de&nbsp;la&nbsp;factura</td>
						    <td width="43%"><input type="text" class="cajaPequena2" id="importe" readonly value="<?php echo number_format($totalfactura,2)?>" ></td>
					       
						</tr>
						<?php $pendiente=$totalfactura-$aportaciones; ?>
						<tr>
							<td width="15%">Pendiente&nbsp;por&nbsp;pagar</td>
						    <td width="43%"><input type="text" class="cajaPequena2" readonly value="&nbsp;<?php echo $moneda;?>" >
						    <input type="text" name="pendiente" id="pendiente" value="<?php echo number_format($pendiente,2,".","")?>" readonly="yes" class="cajaTotales">
						    </td>
					        
						</tr>
						<tr>
							<td width="15%">Estado&nbsp;de&nbsp;la&nbsp;factura</td>
						    <td width="43%"><select id="cboEstados" name="cboEstados" class="comboMedio" onChange="cambiar_estado()">
								<?php if ($estado==1) { ?><option value="1" selected="selected">Sin Pagar</option>
								<option value="2">Pagada</option><?php } else { ?>
								<option value="1">Sin Pagar</option>
								<option value="2" selected="selected">Pagada</option>
								<?php } ?> 			
								</select></td>
					        
						</tr>	
						<tr>
							<td width="15%">Fecha&nbsp;de&nbsp;pago</td>
						    <td width="43%"><input id="fechapago" type="text" class="cajaPequena" NAME="fechapago" maxlength="10" value="<?php echo $fechapago?>" readonly>
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechapago",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
								<img src="../img/disco.png" name="Image" id="Image" width="16" height="16" border="0"
		onMouseOver="this.style.cursor='pointer'" title="Guardar fecha" onClick="cambiar_vencimiento();" style="vertical-align: middle; margin-top: -1px;"></td>
					        
						</tr>										
					</table>
					</form>
			  
				</td><td valign="top" align="center">	
								
				<form id="formulario" name="formulario" method="post" action="frame_cobros.php" target="frame_cobros">
					<table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border="1">
						<tr>
							<td width="15%">Fecha&nbsp;del&nbsp;pago</td>
						    <td width="35%"><input id="fechapago2" type="text" class="cajaPequena" NAME="fechapago2" maxlength="10" value="<?php echo $hoy?>" readonly>
						    <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechapago2",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>						    
						</td>
					        
						</tr>
						<tr>
							<td width="15%">Importe</td>
						    <td width="35%"><input id="Rimporte" type="text" class="cajaPequena" NAME="Rimporte" maxlength="12"> </td>
					        
						</tr>	
						<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
						<tr>
							<td width="15%">Forma&nbsp;de&nbsp;pago</td>
							<td width="35%"><select id="AcboFP" name="AcboFP" class="comboGrande">
							
								<option value="0">Seleccione&nbsp;una&nbsp;forma&nbsp;de&nbsp;pago</option>
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago")?>"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php $contador++;
								} ?>				
								</select>							</td>
								
				        </tr>
						<tr>
							<td width="15%">Num.&nbsp;Documento</td>
						    <td width="35%"><input id="anumdocumento" type="text" class="cajaMedia" NAME="anumdocumento" maxlength="30"></td>
					        
						</tr>	
						<tr>
							<td width="15%">Observaciones</td>
						    <td width="35%"><textarea rows="3" cols="30" class="areaTexto" name="observaciones" id="observaciones"></textarea></td>
					        
						</tr>																	
					</table>
			</td></tr></table>			  
			  </div>			  
				<div>
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="accion" id="accion" value="insertar">
					<input type="hidden" name="codproveedor" id="codproveedor" value="<?php echo $codproveedor?>">
					<input type="hidden" name="codfactura" id="codfactura" value="<?php echo $codfactura?>">
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="actualizar();" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar();" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			  </form>
			  <br>
			  <div id="frmBusqueda">
			  <div id="cabeceraResultado2" class="header">
					relacion de PAGOS </div>
				<div id="frmResultado2">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="10%">ITEM</td>
							<td width="12%">FECHA</td>
							<td width="12%">IMPORTE </td>							
							<td width="20%">FORMA PAGO</td>
							<td width="20%">N. DOCUMENTO</td>
							<td width="15%">FECHA PAGO</td>
							<td width="5%">OBV.</td>
							<td width="6%">&nbsp;</td>
						</tr>
				</table>
				</div>
					<div id="lineaResultado">
					
					<iframe width="100%" height="200" id="frame_cobros" name="frame_cobros" frameborder="0" 
					src="frame_pagos.php?accion=ver&codfactura=<?php echo $codfactura?>&codproveedor=<?php echo $codproveedor?>">
						<ilayer width="100%" height="200" id="frame_cobros" name="frame_cobros"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
				</div>
			</div>
			  </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>		
	</body>
</html>
