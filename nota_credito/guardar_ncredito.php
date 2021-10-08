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

$mensaje='';
$baseimponibledescuento=0;

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codncreditotmp=$_POST["codncreditotmp"];
$codcliente=$_POST["codcliente"];
$tipo=$_POST["atipo"];
$moneda=$_POST["Amoneda"];
$tipocambio=$_POST['tipocambio'];
$fecha=explota($_POST["fecha"]);
$observacion=$_POST["observacion"];


$iva=$_POST["iva"];
$minimo=0;

/*
$query="SELECT * FROM tipocambio WHERE fecha<='$fecha' order by `fecha` DESC";
$rs_query=mysql_query($query);
$tipocambio=mysql_result($rs_query,0,"valor");
*/

if ($accion=="alta") {
	$query_operacion="INSERT INTO ncredito (codncredito, tipo, fecha, iva, codcliente, estado, moneda, tipocambio, total, observacion, emitida, enviada, borrado) 
	VALUES ('$codncreditotmp', '$tipo', '$fecha', '$iva', '$codcliente', '1', '$moneda', '$tipocambio', '$total', '$observacion', '0', '0', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	/*$codncredito=mysql_insert_id(); Anulo pues el nº de ncredito lo ingreso manualmente.-*/
	$codncredito=$codncreditotmp;
	if ($rs_operacion) { $mensaje="La nota crédito ha sido dada de alta correctamente"; }
	$query_tmp="SELECT * FROM ncreditolineatmp WHERE codncredito='$codncreditotmp' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codncreditora=mysqli_result($rs_tmp, $contador, "codncreditora");
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$codservice=mysqli_result($rs_tmp, $contador, "codservice");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$moneda=mysqli_result($rs_tmp, $contador, "moneda");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$baseimponible=$baseimponible+$importe;
		

		$sel_insertar="INSERT INTO ncreditolinea (codncredito,numlinea,codncreditora,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe) VALUES 
		('$codncredito','$numlinea','$codncreditora','$codfamilia','$codigo', '$codservice', '$detalles','$cantidad','$moneda','$precio','$importe')";
		$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
	
				
		$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_minimos= mysqli_query($GLOBALS["___mysqli_ston"], $sel_minimos);
		if ((mysqli_result($rs_minimos, 0, "stock") < mysqli_result($rs_minimos, 0, "stock_minimo")) or (mysqli_result($rs_minimos, 0, "stock") <= 0))
	   		{ 
		  		$mensaje_minimo=$mensaje_minimo . " - " . mysqli_result($rs_minimos, 0, "descripcion")."<br>";
				$minimo=1;
   			};
		$contador++;
	}
	  $baseimpuestos=$baseimponible*($iva/100);
     $preciototal=$baseimponible+$baseimpuestos;
      	
	$sel_act="UPDATE ncredito SET total='$preciototal' WHERE codncredito='$codncredito'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nueva Nota de crédito ";
	$cabecera2="INSERTAR NOTA DE CRÉDITO ";
}

if ($accion=="modificar") {
	$codncredito=$_POST["codncredito"];
	$act_albaran="UPDATE ncredito SET codcliente='$codcliente', tipo='$tipo', fecha='$fecha', iva='$iva', codciente='$codcliente', moneda='$moneda', tipocambio='$tipocambio',
	  observacion='$observacion' WHERE codncredito='$codncredito'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM ncreditolinea WHERE codncredito='$codncredito' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$sel_actualizar="UPDATE `articulos` SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualizar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		$contador++;
	}

	$sel_borrar = "DELETE FROM ncreditolinea WHERE codncredito='$codncredito'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	$query_tmp="SELECT * FROM ncreditolineatmp WHERE codncredito='$codncredito' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codfactura=mysqli_result($rs_tmp, $contador, "codfactura");
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$codservice=mysqli_result($rs_tmp, $contador, "codservice");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$moneda=mysqli_result($rs_tmp, $contador, "moneda");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$baseimponible=$baseimponible+$importe;

		$sel_insertar="INSERT INTO ncreditolinea (codncredito,numlinea,codfactura,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe) VALUES 
		('$codncredito','$numlinea','$codfactura','$codfamilia','$codigo', '$codservice', '$detalles','$cantidad','$moneda','$precio','$importe')";
		$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
	
		$sel_actualiza="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_bajominimo= mysqli_query($GLOBALS["___mysqli_ston"], $sel_bajominimo);
		$stock=mysqli_result($rs_bajominimo, 0, "stock");
		$stock_minimo=mysqli_result($rs_bajominimo, 0, "stock_minimo");
		$descripcion=mysqli_result($rs_bajominimo, 0, "descripcion");
		
		if (($stock < $stock_minimo) or ($stock <= 0) and $descripcion!='' )
		   { 
			  $mensaje_minimo=$mensaje_minimo . " - " . $descripcion."<br>";
			  $minimo=1;
		   };
		$contador++;
	}
	  $baseimpuestos=$baseimponible*($iva/100);
     $preciototal=$baseimponible+$baseimpuestos;
      
	$sel_act="UPDATE ncredito SET total='$preciototal' WHERE codncredito='$codncredito'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);

	if ($rs_act) { $mensaje="Los datos de la nota de crédito han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar Nota de Crédito ";
	$cabecera2="MODIFICAR NOTA DE CREDITO ";
}

if ($accion=="baja") {
	$codncredito=$_GET["codncredito"];
	$query="UPDATE ncredito SET borrado=1 WHERE codncredito='$codncredito'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$query="SELECT * FROM ncreditolinea WHERE codncredito='$codncredito' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador++;
	}
	if ($rs_query) { $mensaje="La nota de crédito ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Nota de Crédito";
	$cabecera2="ELIMINAR Nota de Crédito";
	$query_mostrar="SELECT * FROM ncredito WHERE codncredito='$codncredito'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

	$sel_borrar = "DELETE FROM ncreditolineatmp WHERE codncredito='$codncredito'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>

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
		
		function imprimir(codncredito) {
			var top = window.open("../fpdf/imprimir_ncredito_mcc.php?codncredito="+codncredito, "ncredito", "location=1,status=1,scrollbars=1");
			//top.close();
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">GUARDAR NOTA DE CREDITO</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_ncredito.php">
				<input id="habilitacambio" name="habilitacambio" value="SI" type="hidden">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
						<?php 
						$sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
						?>
							<td>C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" value="<?php echo $codcliente;?>" data-index="1">
					        <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"> 
					        <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td>Nombre</td>
						    <td><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_cliente, 0, "nombre");?>" readonly></td>
						  <td>Nº&nbsp;nota&nbsp;crédito</td>
						  <td colspan="2"><input name="codncredito" class="cajaPequena" readonly="" value="<?php echo $codncredito;?>" readonly ></input></td>				         					        					
						</tr>
						<tr>
				            <td>RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo mysqli_result($rs_cliente, 0, "nif");?>" readonly></td>
								<td>Tipo</td>
				            <td>
				            <input id="tipo" name="atipo" class="cajaPequena" type="hidden" value="2" data-index="2">Nota de crédito&nbsp;

								</td>
						<td>Moneda</td><td> 
					<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							echo "$monedai";
							break;
						}
					}
					?>						
					</td>
						</tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td>Fecha</td><td>
							<input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { actualizovencimeinto(); this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						
						</td>
				      <td>IVA</td>
				      <td><input name="iva" type="text" class="cajaPequena" id="iva" size="5" maxlength="5" onChange="cambio_iva()" value="<?php echo $iva;?>"> %</td>
				       <td colspan="4">Tipo&nbsp;Cambio
								<label>$&nbsp;->&nbsp;U$S</label><span>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" onChange="cambio();"></span>
								<!--<input type="checkbox" name="usartc" style="vertical-align: middle; margin-top: -1px;">Buscar T/C</input>-->
						</td>
						<td><label>Agencia&nbsp;</label><span><input name="agencia" type="text" class="cajaGrande" id="agencia" size="5" maxlength="5" value="<?php echo mysqli_result($rs_cliente, 0, "agencia");?>"></span>
						  &nbsp;</td>				         					        					
						
				            
						</tr>
					</table>										
			  </div>
			  
			  </form>
			  <br style="line-height:2px">
 				<div id="frmBusqueda">

				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
					<thead>
					  <tr class="cabeceraTabla">	
							<th width="7%" align="left">Nº Factura</th>
							<th align="left">DESCRIPCION/DETALLES </th>
							<th width="75px" align="left">CANTIDAD</th>
							<th width="75px" align="left">PRECIO</th>
							<th width="75px" align="left">MON.</th>
							<th width="75px" align="left">IMPORTE</th>
						</tr>
					</thead>
					<tbody>				
				
					  <?php
					  $baseimponible=0;
					  $filastotal=10;
					   $tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
						$sel_lineas="SELECT ncreditolinea.*,articulos.*,familias.nombre as nombrefamilia FROM ncreditolinea,articulos,familias 
						WHERE ncreditolinea.codncredito='$codncredito' AND ncreditolinea.codigo=articulos.codarticulo 
						AND ncreditolinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY ncreditolinea.numlinea ASC";
						$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
							$codfactura=mysqli_result($rs_lineas, $i, "codfactura");
							$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
							$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
							$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
							$detalles=mysqli_result($rs_lineas, $i, "detalles");
							$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
							$referencia=mysqli_result($rs_lineas, $i, "referencia");
							$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
							$precio=mysqli_result($rs_lineas, $i, "precio");
							$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
							$importe=mysqli_result($rs_lineas, $i, "importe");
							$baseimponible=$baseimponible+$importe;
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>

									<tr class="<?php echo $fondolinea?>">
										<td width="8%" class="aCentro"><?php echo $codfactura;?></td>
										<td width="54%"><?php echo $descripcion.' '.$detalles?></td>
										<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
										<td width="8%" class="aCentro"><?php echo $precio;?></td>
										<td width="10%" class="aCentro"><?php echo $moneda;?></td>
										<td width="8%" class="aCentro" ><?php echo $importe;?></td>									
									</tr>
					<?php
					$filastotal--;
					}
					for ($i = 0; $i <= $filastotal; $i++) {
					?>
									<tr>
										<td width="14%">&nbsp;</td>
										<td width="42%">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro">&nbsp;</td>
										<td width="10%" class="aCentro">&nbsp;</td>
										<td width="8%" class="aCentro" >&nbsp;</td>									
									</tr>
					<?php					
					
					}
					 ?>
					</table>	
				  <?php
				  $baseimponibleaux=$baseimponible;
				  $baseimpuestos=$baseimponible*($iva/100);
			     $preciototal=$baseimponible+$baseimpuestos;
		      $preciototal=number_format($preciototal,2);
			  	  ?>
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			<tr>
			<td align="rigth" valign="top">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea name="observacion" id="observacion" rows="4" cols="40"> <?php echo $observacion;?></textarea>
				</td>
				<td colspan="6"></td>
			    <td class="busqueda">Sub-total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponibleaux,2);?>" align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td></td>
				<td></td>
				<td></td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" value="<?php echo number_format($baseimponible,2);?>" size="12" align="right" readonly> 
				</td>
				<td class="busqueda">IVA</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td colspan="2">
					<div align="center">
					  <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					  <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<?php echo $codncreditotmp;?>);" onMouseOver="style.cursor=cursor">
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal;?>" readonly> 
		        </div></td>
				</tr> 				
				</table>
				</td><td >
				
			  </tr>
		</table>			  
			  </div>


			 </div>
		  </div>
		</div>

			
	</body>
</html><?php
//mysql_close($descriptor);
?>