<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codfacturatmp=$_POST["codfacturatmpaux"];
$codcliente=$_POST["codcliente"];
$tipo=$_POST["atipo"];
$moneda=$_POST["Amoneda"];
$fecha=explota($_POST["fecha"]);
$observacion=$_POST["observacion"];
$descuentogral=$_POST["descuentogral"];
$iva=$_POST["iva"];
$minimo=0;


$query="SELECT * FROM tipocambio WHERE fecha<='$fecha' order by `fecha` DESC";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$tipocambio=mysqli_result($rs_query, 0, "valor");


if ($accion=="alta") {
	$query_operacion="INSERT INTO facturas (codfactura, tipo, fecha, iva, codcliente, estado, moneda, descuento, observacion, borrado) 
	VALUES ('$codfacturatmp', '$tipo', '$fecha', '$iva', '$codcliente', '1', '$moneda', '$descuentogral', '$observacion', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	/*$codfactura=mysql_insert_id(); Anulo pues el nº de factura lo ingreso manualmente.-*/
	$codfactura=$codfacturatmp;
	if ($rs_operacion) { $mensaje="La factura ha sido dada de alta correctamente"; }
	$query_tmp="SELECT * FROM factulineatmp WHERE codfactura='$codfactura' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$codservice=mysqli_result($rs_tmp, $contador, "codservice");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_tmp, $contador, "dcto");
		$sel_insertar="INSERT INTO factulinea (codfactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,precio,importe,dcto) VALUES 
		('$codfactura','$numlinea','$codfamilia','$codigo', '$codservice', '$detalles','$cantidad','$precio','$importe','$dcto')";
		$rs_insertar=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);		
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_minimos= mysqli_query($GLOBALS["___mysqli_ston"], $sel_minimos);
		if ((mysqli_result($rs_minimos, 0, "stock") < mysqli_result($rs_minimos, 0, "stock_minimo")) or (mysqli_result($rs_minimos, 0, "stock") <= 0))
	   		{ 
		  		$mensaje_minimo=$mensaje_minimo . " " . mysqli_result($rs_minimos, 0, "descripcion")."<br>";
				$minimo=1;
   			};
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	/*/$preciototal=number_format($preciototal,2);	*/
	$sel_act="UPDATE facturas SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nueva Factura ";
	$cabecera2="INSERTAR FACTURA ";
}

if ($accion=="modificar") {
	$codfactura=$_POST["codfactura"];
	$act_albaran="UPDATE facturas SET codcliente='$codcliente', tipo='$tipo', fecha='$fecha', iva='$iva', moneda='$moneda', descuento='$descuentogral', observacion='$observacion' WHERE codfactura='$codfactura'";
	$rs_albaran=mysqli_query($GLOBALS["___mysqli_ston"], $act_albaran);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM factulinea WHERE codfactura='$codfactura' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$sel_actualizar="UPDATE `articulos` SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualizar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		$contador++;
	}

	$sel_borrar = "DELETE FROM factulinea WHERE codfactura='$codfactura'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	$sel_lineastmp = "SELECT * FROM factulineatmp WHERE codfactura='$codfactura' ORDER BY numlinea";
	$rs_lineastmp = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineastmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_lineastmp)) {
		$numlinea=mysqli_result($rs_lineastmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_lineastmp, $contador, "codigo");
		$codservice=mysqli_result($rs_lineastmp, $contador, "codservice");
		$codfamilia=mysqli_result($rs_lineastmp, $contador, "codfamilia");
		$detalles=mysqli_result($rs_lineastmp, $contador, "detalles");
		$cantidad=mysqli_result($rs_lineastmp, $contador, "cantidad");
		$precio=mysqli_result($rs_lineastmp, $contador, "precio");
		$importe=mysqli_result($rs_lineastmp, $contador, "importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysqli_result($rs_lineastmp, $contador, "dcto");
	
		$sel_insert = "INSERT INTO factulinea (codfactura,numlinea,codigo,codservice,detalles,codfamilia,cantidad,precio,importe,dcto) 
		VALUES ('$codfactura','','$codigo','$codservice', '$detalles','$codfamilia','$cantidad','$precio','$importe','$dcto')";
		$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		
		$sel_actualiza="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_bajominimo= mysqli_query($GLOBALS["___mysqli_ston"], $sel_bajominimo);
		$stock=mysqli_result($rs_bajominimo, 0, "stock");
		$stock_minimo=mysqli_result($rs_bajominimo, 0, "stock_minimo");
		$descripcion=mysqli_result($rs_bajominimo, 0, "descripcion");
		
		if (($stock < $stock_minimo) or ($stock <= 0) and !empty($descripcion) )
		   { 
			  $mensaje_minimo=$mensaje_minimo . " - " . $descripcion."<br>";
			  $minimo=1;
		   };
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	/*/$preciototal=number_format($preciototal,2);*/	
	$sel_act="UPDATE facturas SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);

//	$baseimpuestos=0;
//	$baseimponible=0;
//	$preciototal=0;
	if ($rs_query) { $mensaje="Los datos de la factura han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar Factura ";
	$cabecera2="MODIFICAR FACTURA ";
}

if ($accion=="baja") {
	$codfactura=$_GET["codfactura"];
	$query="UPDATE facturas SET borrado=1 WHERE codfactura='$codfactura'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$query="SELECT * FROM factulinea WHERE codfactura='$codfactura' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador++;
	}
	if ($rs_query) { $mensaje="La factura ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar Factura";
	$cabecera2="ELIMINAR FACTURA";
	$query_mostrar="SELECT * FROM facturas WHERE codfactura='$codfactura'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

	$sel_borrar = "DELETE FROM factulineatmp WHERE codfactura='$codfactura'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
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
			location.href="index.php";
		}
		
		function imprimir(codfactura) {
			window.open("../fpdf/imprimir_factura_betty.php?codfactura="+codfactura);
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function efectuarpago(codfactura,codcliente,importe,moneda) {
			$.colorbox({href:"efectuarpago.php?codfactura="+codfactura+"&codcliente="+codcliente+"&importe="+importe+"&moneda="+moneda,
			iframe:true, width:"750", height:"350",
			
			});
		}		
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<?php if ($minimo==1) { ?>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensajeminimo">Los siguientes art&iacute;culos est&aacute;n bajo m&iacute;nimo:<br>
							<?php echo $mensaje_minimo;?></td>
					    </tr>
						<?php } 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente); ?>


<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">C&oacute;digo&nbsp;Cliente </td>
					      <td><?php echo $codcliente;?></td>
							<td width="6%">Nombre</td>
						    <td width="27%"><?php echo mysqli_result($rs_cliente, 0, "nombre");?></td>
						  <td>Nº&nbsp;factura</td>
						  <td colspan="2"><?php echo $codfactura;?></td>				         					        					
						</tr>
						<tr>
				            <td width="5%">RUT</td>
				            <td><?php echo mysqli_result($rs_cliente, 0, "nif");?></td>
								<td>Tipo</td>
				            <td>
					<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
					$NoEstado=0;
					foreach($tipof as $key => $facturai ) {
					  	if ( $key==$tipo) {
							echo "$facturai";
							break;
						}
					}
					?>
						</td>
						<td>Moneda</td><td width="26%">
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
							<td width="6%">Fecha</td>
						    <td width="27%">
						    <?php echo implota($fecha)?>
								</td>
				            <td width="3%">IVA</td>
				            <td ><?php echo $iva?>%</td>
				            <td colspan="3">Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<?php echo $tipocambio;?></span>
								</td>
					</tr>

					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
				  </table>
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="14%" align="left">&nbsp;DESCRIPCION</td>
							<td width="42%" align="left">&nbsp;DETALLES</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="6%">MONEDA</td>
							<td width="8%">IMPORTE</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <?php
					  $baseimponible=0;
					   $tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
						$sel_lineas="SELECT factulinea.*,articulos.*,familias.nombre as nombrefamilia FROM factulinea,articulos,familias WHERE factulinea.codfactura='$codfactura' AND factulinea.codigo=articulos.codarticulo AND factulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY factulinea.numlinea ASC";
						$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
						for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
							$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
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
							$descuento=mysqli_result($rs_lineas, $i, "dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>

									<tr class="<?php echo $fondolinea?>">
										<td width="14%"><?php echo $descripcion?></td>
										<td width="42%"><?php echo $detalles?></td>
										<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
										<td width="8%" class="aCentro"><?php echo $precio;?></td>
										<td width="8%" class="aCentro"><?php echo $descuento;?></td>
										<td width="10%" class="aCentro"><?php echo $moneda;?></td>
										<td width="8%" class="aCentro" ><?php echo $importe;?></td>									
									</tr>
					<?php } ?>
					</table>
			  </div>
				  <?php
				  if ($descuentogral==0 or $descuentogral=='') {
				  $baseimpuestos=$baseimponible*($iva/100);
				  } else {
				  $baseimponibledescuento=($baseimponible/(1+$descuentogral/100));
				  $baseimpuestos=($baseimponible/(1+$descuentogral/100))*($iva/100);
				  }
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=number_format($preciototal,2);
			  	  ?>
			  	  
<div id="frmBusqueda">

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
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value="<?php echo number_format($baseimponible,2);?>" align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda">Descuento</td>
				<td class="busqueda"><input id="descuentogral" name="descuentogral" value="<?php echo $descuentogral;?>" class="cajaMinima"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" value="<?php echo number_format($baseimponibledescuento,2);?>" size="12" value=0 align="right" readonly> 
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

					<div align="center">
					  <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<?php echo $codfactura?>);" onMouseOver="style.cursor=cursor">
				        </div>
						<br>
						<div align="center" id="cajareg">
					  <img id="botonBusqueda" src="../img/caja.jpg" width="80" height="77" border="1" onClick="efectuarpago('<?php echo $codfactura?>','<?php echo $codcliente?>','<?php echo $preciototal?>','<?php echo $moneda;?>');" onMouseOver="style.cursor=cursor" title="Efectuar pago">
				        </div>
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="right"><div align="center">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal?>" readonly> 
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
</html>
