<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codpresupuestotmp=$_POST["codpresupuestotmp"];

$solicitado=$_POST["solicitado"];
$lugar=$_POST["lugar"];
$fecha=explota($_POST["fecha"]);
$fechaentrega=explota($_POST["fechaentrega"]);
$sector=$_POST["sector"];
$codformapago=$_POST["codformapago"];
$requerimientos=$_POST["requerimientos"];
$observaciones=$_POST["observaciones"];
$iva=$_POST["iva"];
$codcliente=$_POST["codcliente"];
$tipo=$_POST["tipo"];
$estado=$_POST["estado"];
$tipocambio=$_POST["tipocambio"];
$moneda=$_POST["amoneda"];
$totalpresupuesto=$_POST["preciototal"];
$descuentogral=$_POST["descuentogral"];


$minimo=0;

if ($accion=="alta") {
	$query_operacion="INSERT INTO `presupuestos` 
	(`codpresupuesto`, `codfactura`, `solicitado`, `lugarentrega`, `fecha`, `fechaentrega`, `sector`, `codformapago`, `requerimientos`, `observaciones`, `iva`,
	 `codcliente`, `tipo`, `estado`, `tipocambio`, `descuento`, `moneda`, `totalpresupuesto`, `borrado`) VALUES 
	(NULL, '0', '$solicitado', '$lugar', '$fecha', '$fechaentrega', '$sector', '$codformapago', '$requerimientos', '$observaciones', '$iva', 
	'$codcliente', '$tipo', '$estado', '$tipocambio', '$descuentogral', '$moneda', '$totalpresupuesto', '0')";
	
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	$codpresupuesto=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
	if ($rs_operacion) { $mensaje="El presupuesto ha sido dado de alta correctamente"; }
	$query_tmp="SELECT * FROM presulineatmp WHERE codpresupuesto='$codpresupuestotmp' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codarticulo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
		$moneda=mysqli_result($rs_tmp, $contador, "moneda");
		$precio_compra=mysqli_result($rs_tmp, $contador, "precio_compra");
		$precio=mysqli_result($rs_tmp, $contador, "precio");
		$importe=mysqli_result($rs_tmp, $contador, "importe");
		$descuento=mysqli_result($rs_tmp, $contador, "dcto");
		$detalles=mysqli_result($rs_tmp, $contador, "detalles");
		$archivos=mysqli_result($rs_tmp, $contador, 'archivos');
		$codsector=mysqli_result($rs_tmp, $contador, 'codsector');
		$baseimponible=$baseimponible+$importe;

		$sel_insertar="INSERT INTO presulinea (codpresupuesto,numlinea,codigo,codfamilia,detalles,cantidad,moneda,precio_compra,precio,importe,dcto, archivos, codsector)
		VALUES ('$codpresupuesto','','$codarticulo','$codfamilia','$detalles','$cantidad','$moneda','$precio_compra','$precio','$importe','$descuento','$archivos', '$codsector')";
		$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insertar);
		
		/*
		$codfamilia=mysql_result($rs_tmp,$contador,"codfamilia");
		$numlinea=mysql_result($rs_tmp,$contador,"numlinea");
		$codigo=mysql_result($rs_tmp,$contador,"codigo");
		$cantidad=mysql_result($rs_tmp,$contador,"cantidad");
		$precio=mysql_result($rs_tmp,$contador,"precio");
		$importe=mysql_result($rs_tmp,$contador,"importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysql_result($rs_tmp,$contador,"dcto");
		$sel_insertar="INSERT INTO presulinea (codpresupuesto,numlinea,codfamilia,codigo,cantidad,precio,importe,dcto) VALUES
		('$codpresupuesto','$numlinea','$codfamilia','$codigo','$cantidad','$precio','$importe','$dcto')";
		$rs_insertar=mysql_query($sel_insertar);
		*/
// No se controla el stock en los presupuestos
//		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
/*
		$rs_articulos=mysql_query($sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_minimos= mysql_query($sel_minimos);
		if ((mysql_result($rs_minimos,0,"stock") < mysql_result($rs_minimos,0,"stock_minimo")) or (mysql_result($rs_minimos,0,"stock") <= 0))
	   		{
		  		$mensaje_minimo=$mensaje_minimo . " " . mysql_result($rs_minimos,0,"descripcion")."<br>";
				$minimo=1;
   			};
   */
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);
	$sel_act="UPDATE presupuestos SET totalpresupuesto='$preciototal' WHERE codpresupuesto='$codpresupuesto'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimponible=0;
	$preciototal=0;
	$baseimpuestos=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Nuevo presupuesto ";
	$cabecera2="INSERTAR PRESUPUESTO ";
}

if ($accion=="modificar") {
	$codpresupuesto=$_POST["codpresupuesto"];
	$act_presupuesto="UPDATE `presupuestos` SET `codfactura` = '$codfactura', `solicitado` = '$solicitado', `lugarentrega` = '$lugar', `fecha` = '$fecha',
	 `fechaentrega` = '$fechaentrega', `sector` = '$sector', `codformapago` = '$codformapago', `requerimientos` = '$requerimientos', 
	 `observaciones` = '$observaciones', `iva` = '$iva', `codcliente` = '$codcliente', `tipo` = '$tipo', `estado` = '$estado', `tipocambio` = '$tipocambio', `descuento` = '$descuentogral',
	  `moneda` = '$moneda', `totalpresupuesto` = '$totalpresupuesto' WHERE `codpresupuesto` = '$codpresupuesto'";
	  
	$rs_presupuesto=mysqli_query($GLOBALS["___mysqli_ston"], $act_presupuesto);
	$sel_lineas = "SELECT codigo,codfamilia,cantidad FROM presulinea WHERE codpresupuesto='$codpresupuesto' order by numlinea";
	$rs_lineas = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	$contador=0;
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
//		$sel_actualizar="UPDATE `articulos` SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualizar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualizar);
		$contador++;
	}
	$sel_borrar = "DELETE FROM presulinea WHERE codpresupuesto='$codpresupuesto'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	$sel_lineastmp = "SELECT * FROM presulineatmp WHERE codpresupuesto='$codpresupuestotmp' ORDER BY numlinea";
	$rs_lineastmp = mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineastmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_lineastmp)) {

		$codfamilia=mysqli_result($rs_lineastmp, $contador, "codfamilia");
		$codarticulo=mysqli_result($rs_lineastmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_lineastmp, $contador, "cantidad");
		$moneda=mysqli_result($rs_lineastmp, $contador, "moneda");
		$precio_compra=mysqli_result($rs_lineastmp, $contador, "precio_compra");
		$precio=mysqli_result($rs_lineastmp, $contador, "precio");
		$importe=mysqli_result($rs_lineastmp, $contador, "importe");
		$descuento=mysqli_result($rs_lineastmp, $contador, "dcto");
		$detalles=mysqli_result($rs_lineastmp, $contador, "detalles");
		$archivos=mysqli_result($rs_tmp, $contador, 'archivos');
		$codsector=mysqli_result($rs_lineastmp, $contador, 'codsector');

		$baseimponible=$baseimponible+$importe;

		$sel_insert="INSERT INTO presulinea (codpresupuesto,numlinea,codigo,codfamilia,detalles,cantidad,moneda,precio_compra,precio,importe,dcto, archivos, codsector)
		VALUES ('$codpresupuestotmp','','$codarticulo','$codfamilia','$detalles','$cantidad','$moneda','$precio_compra','$precio','$importe','$descuento','$archivos', '$codsector')";
		/*$sel_insert = "INSERT INTO presulinea (codpresupuesto,numlinea,codigo,codfamilia,cantidad,precio,importe,dcto)
		VALUES ('$codpresupuesto','','$codigo','$codfamilia','$cantidad','$precio','$importe','$dcto')";*/
		$rs_insert = mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
/*
		$sel_actualiza="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_actualiza = mysql_query($sel_actualiza);
		$sel_bajominimo = "SELECT codarticulo,codfamilia,stock,stock_minimo,descripcion FROM articulos WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_bajominimo= mysql_query($sel_bajominimo);
		$stock=mysql_result($rs_bajominimo,0,"stock");
		$stock_minimo=mysql_result($rs_bajominimo,0,"stock_minimo");
		$descripcion=mysql_result($rs_bajominimo,0,"descripcion");

		if (($stock < $stock_minimo) or ($stock <= 0))
		   {
			  $mensaje_minimo=$mensaje_minimo . " " . $descripcion."<br>";
			  $minimo=1;
		   };
*/
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	$preciototal=$preciototal*(1-$descuentogral/100);
	//$preciototal=number_format($preciototal,2);
	$sel_act="UPDATE presupuestos SET totalpresupuesto='$preciototal' WHERE codpresupuesto='$codpresupuesto'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimponible=0;
	$preciototal=0;
	$baseimpuestos=0;
	if ($rs_query) { $mensaje="Los datos del presupuesto han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Modificar presupuesto ";
	$cabecera2="MODIFICAR PRESUPUESTO ";
}

if ($accion=="baja") {
	$codpresupuesto=$_GET["codpresupuesto"];
	$query="UPDATE presupuestos SET borrado=1 WHERE codpresupuesto='$codpresupuesto'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$query="SELECT * FROM presulinea WHERE codpresupuesto='$codpresupuesto' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$cantidad=mysqli_result($rs_tmp, $contador, "cantidad");
//		$sel_articulos="UPDATE articulos SET stock=(stock+'$cantidad') WHERE codarticulo='$codigo' AND codfamilia='$codfamilia'";
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador++;
	}
	if ($rs_query) { $mensaje="El presupuesto ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Ventas &gt;&gt; Eliminar presupuesto";
	$cabecera2="ELIMINAR PRESUPUESTO";
	$query_mostrar="SELECT * FROM presupuestos WHERE codpresupuesto='$codpresupuesto'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$iva=mysqli_result($rs_mostrar, 0, "iva");
}

if ($accion=="convertir") {
	
	$codfactura=$_POST["Acodfactura"];
	$codpresupuestotmp=$codpresupuesto=$_POST["codpresupuesto"];
	$fecha=$_POST["fecha"];
	
	$fecha=explota($fecha);
	$sel_presupuesto="SELECT * FROM presupuestos WHERE codpresupuesto='$codpresupuesto'";
	$rs_presupuesto=mysqli_query($GLOBALS["___mysqli_ston"], $sel_presupuesto);
	$iva=mysqli_result($rs_presupuesto, 0, "iva");
	$codcliente=mysqli_result($rs_presupuesto, 0, "codcliente");
	$totalfactura=mysqli_result($rs_presupuesto, 0, "totalpresupuesto");	
	$codformapago=mysqli_result($rs_presupuesto, 0, "codformapago");
	$observaciones=mysqli_result($rs_presupuesto, 0, "observaciones");
	$iva=mysqli_result($rs_presupuesto, 0, "iva");
	$tipo=mysqli_result($rs_presupuesto, 0, "tipo");
	$estado=mysqli_result($rs_presupuesto, 0, "estado");
	$tipocambio=mysqli_result($rs_presupuesto, 0, "tipocambio");
	$descuentogral=mysqli_result($rs_presupuesto, 0, "descuento");
	$moneda=mysqli_result($rs_presupuesto, 0, "moneda");
	

	$query_operacion="INSERT INTO facturas (codfactura, tipo, fecha, iva, codcliente, estado, moneda, tipocambio, descuento, observacion, borrado) 
	VALUES ('$codfactura', '$tipo', '$fecha', '$iva', '$codcliente', '1', '$moneda', '$tipocambio', '$descuentogral', '$observaciones', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	
	if ($rs_operacion) { $mensaje="La factura ha sido dada de alta correctamente"; }
	
	$sel_lineas="SELECT * FROM presulinea WHERE codpresupuesto='$codpresupuesto' ORDER BY numlinea ASC";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);		
	$contador=0;
	$baseimponible=0;
	while ($contador < mysqli_num_rows($rs_tmp)) {
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
		$numlinea=mysqli_result($rs_tmp, $contador, "numlinea");
		$codigo=mysqli_result($rs_tmp, $contador, "codigo");
		$codfamilia=mysqli_result($rs_tmp, $contador, "codfamilia");
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
	  if ($descuentogral==0 or $descuentogral=='') {
	  $baseimpuestos=$baseimponible*($iva/100);
	  } else {
	  $baseimponibledescuento=($baseimponible/(1+$descuentogral/100));
	  $baseimpuestos=($baseimponible/(1+$descuentogral/100))*($iva/100);
	  }
      $preciototal=$baseimponible+$baseimpuestos;

	$sel_act="UPDATE facturas SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	
	$query="UPDATE presupuestos SET estado=5 WHERE codpresupuesto='$codpresupuesto'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	
	$mensaje="El presupuesto ha sido convertido correctamente";
	$cabecera1="Inicio >> Ventas &gt;&gt; Convertir presupuesto";
	$cabecera2="CONVERTIR PRESUPUESTO";
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
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

		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();			
		}

		function imprimir(codpresupuesto) {
			window.open("../fpdf/imprimir_presupuesto.php?codpresupuesto="+codpresupuesto);
		}

		function imprimirf(codfactura) {
			window.open("../fpdf/imprimir_factura.php?codfactura="+codfactura);
		}

		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
					    <tr><td>
					<table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border=0>
						<?php 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'";
						  $rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente); ?>
						<tr>
							<td width="15%">Cliente</td>
							<td width="85%" colspan="2"><?php echo mysqli_result($rs_cliente, 0, "nombre");?></td>
					    </tr>
						<tr>
							<td width="15%">RUT / CI</td>
						    <td width="85%" colspan="2"><?php echo mysqli_result($rs_cliente, 0, "nif");?></td>
					    </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo mysqli_result($rs_cliente, 0, "direccion"); ?></td>
					  </tr>
					  </table>
					  </td><td>
					  <table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border=0>
					  <?php if ($accion=="convertir") { ?>
						<tr>
						  <td>Nº de factura</td>
						  <td colspan="2"><?php echo $codfactura;?></td>
					  </tr>
					  <?php } else { ?>
					  	<tr>
						  <td>Nº de presupuesto</td>
						  <td colspan="2"><?php echo $codpresupuesto;?></td>
					  </tr>
					  <?php } ?>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha);?></td>
					  </tr>
					  <tr>
						  <td>IVA</td>
						  <td colspan="2"><?php echo $iva;?> %</td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
					  </table></td>
				  </table>
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">REFERENCIA</td>
							<td width="30%">DESCRIPCION</td>
							<td width="5%">ARCHIVO</td>
							<td width="5%">CANTIDAD</td>
							<td width="5%">COSTO</td>
							<td width="5%">PRECIO</td>
							<td width="5%">DCTO %</td>
							<td width="5%">MONEDA</td>
							<td width="5%">IMPORTE</td>	
							<td width="5%">GANANCIA</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					

<?php
 $sel_lineas="SELECT presulinea.*,articulos.*,familias.nombre as nombrefamilia FROM presulinea,articulos,familias WHERE presulinea.codpresupuesto='$codpresupuestotmp'
 AND presulinea.codigo=articulos.codarticulo AND presulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY presulinea.numlinea ASC";
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");

$file='';
$total_costo=0;

$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$archivos=mysqli_result($rs_lineas, $i, "archivos");
	if($archivos!='') {
		$file=explode('*', $archivos);
	} else {
		$file='';
	}
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$precio_compra=mysqli_result($rs_lineas, $i, "precio_compra");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$moneda=mysqli_result($rs_lineas, $i, "moneda");
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$baseimponible=$baseimponible+$importe;
	$total_costo=$total_costo+$precio_compra*$cantidad;
	$descuento=mysqli_result($rs_lineas, $i, "dcto");
	$codsector=mysqli_result($rs_lineas, $i, "codsector");

			$sel_resultado="SELECT * FROM sector where codsector='".$codsector."'";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   if ($contador < mysqli_num_rows($res_resultado)) {
		   	$color='style="background-color:#' . mysqli_result($res_resultado, $contador, "color").'"';
		   } else {
		   	$color='';
		   }

	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>



			<tr class="<?php echo $fondolinea?>">
				<td width="8%" class="aCentro"><?php echo $referencia;?></td>
				<td width="30%" class="aIzquierda"><?php echo $descripcion; if ($detalles!="") echo " - ".$detalles;?></td>
				<td width="5%" class="aIzquierda">				
				<?php
					$tmp='';
					$nombre='';
					if(count($file)>0 and $file!='') {
					for($x=0;$x < count($file); $x++) {
						$tmp=$file[$x];
						$nombre=explode('.',$tmp);
						echo '<img id="botonBusqueda" src="../img/'.$nombre[1].'.png"  width="16" height="16" alt="'.$nombre[0].'" title="'.$nombre[0].".".$nombre[1].'">';
						$nombre='';
					}
					
					} else {
						echo "&nbsp;";
					}
				?>
				</td>
				<td width="5%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="5%" class="aCentro"><?php echo $precio_compra;?></td>
				<td width="5%" class="aCentro"><?php echo $precio;?></td>
				<td width="5%" class="aCentro"><?php echo $descuento;?></td>
				<td width="5%" class="aCentro"><?php echo $tipomon[$moneda];?></td>
				<td width="5%" class="aCentro"><?php echo $importe;?></td>
				<td width="5%" class="aCentro"><?php echo $importe-($precio_compra*$cantidad);?></td>
			</tr>
<?php } ?>
					</table>
			  </div>
				  <?php

				  $baseimponibledescuento=$baseimponible*(1-$descuentogral/100);
				  $baseimpuestos=$baseimponibledescuento*($iva/100);
			     $preciototal=$baseimponibledescuento+$baseimpuestos;
			     $preciototal=number_format($preciototal,2);
			     
			     $ganancia=$baseimponibledescuento-$total_costo;
			     
					$tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key ) {
							break;
						}
					}			      
			  	  ?>
			  	  
			<div id="frmBusqueda">

		
<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8"><table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40"><?php echo $observaciones;?></textarea>
				</td>
				<td colspan="3"></td>
				<td>Ganancia</td><td>
					<input class="cajaTotales" type="text" size="12" value="<?php echo $ganancia;?>" align="right" readonly>
				</td>
			    <td class="busqueda">Sub-total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monShow" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12"  size="12" value="<?php echo number_format($baseimponible,2);?>" align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda" >Descuento</td>
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" class="cajaMinima" value="<?php echo $descuentogral;?>"></td>
				<td class="busqueda">%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" size="12" value="<?php echo $baseimponibledescuento;?>" align="right" readonly> 
				</td>
				<td class="busqueda">IVA </td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSho" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value="<?php echo number_format($baseimpuestos,2);?>" readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td>
					<div align="center">
					 <img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
					  <?php if ($accion=="convertir") { ?>
					   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimirf(<?php echo $codfactura?>)" onMouseOver="style.cursor=cursor">
					   <?php } else { ?>
					   <img id="botonBusqueda" src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<?php echo $codpresupuesto?>)" onMouseOver="style.cursor=cursor">
					   <?php } ?>
				   </div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value="<?php echo $preciototal;?>" readonly>
			       
		        </div></td>				
				</tr> 				
				</table>	
		
		
			  </div>			  	  
			  	  

			  </div>
		  </div>
		</div>
	</body>
</html>
