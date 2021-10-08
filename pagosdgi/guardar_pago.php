<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$anio=$_POST['anio'];
$mes=$_POST['mes'];
$f108=$_POST['f108'];
$f328=$_POST['f328'];
$f546=$_POST['f546'];
$f606=$_POST['f606'];
$fecha=explota($_POST['fecha']);


if ($accion=="alta") {
	$query_operacion="INSERT INTO pagodgi (codpagodgi, mes, anio, f108, f328, f546, f606, fecha) 
					VALUES ('', '$mes', '$anio', '$f108', '$f328', '$f546', '$f606', '$fecha')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El pago a DGI ha sido dada de alta correctamente"; }
	$cabecera1="Inicio >> Pago DGI &gt;&gt; Nuevo Pago DGI ";
	$cabecera2="INSERTAR PAGO DGI ";
	$sel_maximo="SELECT max(codpagodgi) as maximo FROM pagodgi";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codpagodgi=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codpagodgi=$_POST["codpagodgi"];
	$query="UPDATE pagodgi SET mes='$mes', anio='$anio', f108='$f108', f328='$f328', f546='$f546', f606='$f606', fecha='$fecha' WHERE codpagodgi='$codpagodgi'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del pago a DGI han sido modificados correctamente"; }
	$cabecera1="Inicio >> Pago DGI &gt;&gt; Modificar Pago DGI ";
	$cabecera2="MODIFICAR PAGO DGI";
}

if ($accion=="baja") {
	$codpagodgi=$_GET["codpagodgi"];
			$query="DELETE FROM `pagodgi` WHERE `codpagodgi` = '$codpagodgi'";
			$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
			if ($rs_query) { $mensaje="El pago DGI ha sido eliminado correctamente"; }
			$cabecera1="Inicio >> Pago DGI&gt;&gt; Eliminar Pago DGI ";
			$cabecera2="ELIMINAR PAGO DGI ";

}

$query="SELECT * FROM pagodgi WHERE codpagodgi='$codpagodgi'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}

		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR PAGO DGI </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_pago.php">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha</td><td>
							<?php $hoy=implota(date("Y-m-d"));?>
						    <input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10"  value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						<td>&nbsp;</td>
						</tr>					
						<tr>
							<td>Año</td>
						    <td><select name="anio" id="anio" class="cajaPequena2">
						    <?php 
						    for ($x=2010; $x<=2020; $x++){
						    	if($x==mysqli_result($rs_query, 0, "anio")) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo $x;?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo $x;?></option>
								<?php	
								}					    
						    }
						    ?>
						    </select>
						   </td>
						   <td>Mes&nbsp;<select name="mes" id="mes" class="cajaPequena">
						    <?php 
						    for ($x=1; $x<=12; $x++){
						    	if ($x== mysqli_result($rs_query, 0, "mes")) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo genMonth_Text($x);?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo genMonth_Text($x);?></option>
								<?php
								}						    
						    }
						    ?>
						    </select>						   
						   </td>
						</tr>	
						<tr><td>Código&nbsp;Imp.</td><td>Concepto                     </td><td>Importe</td></tr>
						<tr><td>108        </td><td>IRAE - Anticipo                  </td><td>
						<input name="f108" type="text" class="cajaTotales" id="f108" value="<?php echo mysqli_result($rs_query, 0, "f108");?>" onchange="actualizar();"></td></tr>
						<tr><td>328        </td><td>IMPUESTO AL PATRIMONIO - Anticipo</td><td>
						<input name="f328" type="text" class="cajaTotales" id="f328" value="<?php echo mysqli_result($rs_query, 0, "f328");?>" onchange="actualizar();"></td></tr>
						<tr><td>546        </td><td>IVA - Contribuyente No CEDE      </td><td>
						<input name="f546" type="text" class="cajaTotales" id="f546" value="<?php echo mysqli_result($rs_query, 0, "f546");?>" onchange="actualizar();"></td></tr>
						<tr><td>606        </td><td>ICOSA Anticipo                   </td><td>
						<input name="f606" type="text" class="cajaTotales" id="f606" value="<?php echo mysqli_result($rs_query, 0, "f606");?>" onchange="actualizar();"></td></tr>
						<tr><td>           </td><td><div align="right">Total</div>   </td><td>
						<input type="text" class="cajaTotales" id="total" value="<?php echo mysqli_result($rs_query, 0, "f606")+mysqli_result($rs_query, 0, "f546")+mysqli_result($rs_query, 0, "f328")+mysqli_result($rs_query, 0, "f108");?>" readonly></td></tr>
											
					</table>
			  </div>
				<div>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			  </form>
			 </div>
		  </div>
		</div>

	</body>
</html>
