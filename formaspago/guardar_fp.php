<?php
include ("../conectar.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$nombrefp=$_POST["Anombrefp"];
$dias=$_POST["Adias"];

if ($accion=="alta") {
	$query_operacion="INSERT INTO formapago (codformapago, nombrefp, dias, borrado) 
					VALUES ('', '$nombrefp', '$dias', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="La forma de pago ha sido dada de alta correctamente"; }
	$cabecera1="Inicio >> Formas de pago &gt;&gt; Nueva Forma de Pago ";
	$cabecera2="INSERTAR FORMA DE PAGO ";
	$sel_maximo="SELECT max(codformapago) as maximo FROM formapago";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codformapago=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codformapago=$_POST["Zid"];
	$query="UPDATE formapago SET nombrefp='$nombrefp', dias='$dias', borrado=0 WHERE codformapago='$codformapago'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos de la forma de pago han sido modificados correctamente"; }
	$cabecera1="Inicio >> Forma de pago &gt;&gt; Modificar Forma de Pago ";
	$cabecera2="MODIFICAR FORMA DE PAGO ";
}

if ($accion=="baja") {
	$codformapago=$_GET["codformapago"];
	$query_comprobar="SELECT * FROM clientes WHERE codformapago='$codformapago' AND borrado=0";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
		?><script>
			alert ("No se puede eliminar esta forma de pago porque tiene clientes asociados.");
			location.href="eliminar_fp.php?codformapago=<?php echo $codformapago?>";
		</script>
		<?php
	} else {
		$query="UPDATE formapago SET borrado=1 WHERE codformapago='$codformapago'";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($rs_query) { $mensaje="La forma de pago ha sido eliminada correctamente"; }
		$cabecera1="Inicio >> Formas de pago &gt;&gt; Eliminar Forma de Pago ";
		$cabecera2="ELIMINAR FORMA DE PAGO ";
		$query_mostrar="SELECT * FROM formapago WHERE codformapago='$codformapago'";
		$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
		$codformapago=mysqli_result($rs_mostrar, 0, "codformapago");
		$nombrefp=mysqli_result($rs_mostrar, 0, "nombrefp");
		$dias=mysqli_result($rs_mostrar, 0, "dias");
	}
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		// Est?? utilizando EXPLORER
		cursor='hand';
		} else {
		// Est?? utilizando MOZILLA/NETSCAPE
		cursor='pointer';
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
							<td width="100%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<tr>
							<td width="15%">Descripci??n</td>
						    <td width="85%" colspan="2"><?php echo $nombrefp;?></td>
					    </tr>						
						<tr>
							<td width="15%">D??as</td>
						    <td width="85%" colspan="2"><?php echo $dias;?></td>
					    </tr>						
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
		  </div>
		</div>
	</body>
</html>
