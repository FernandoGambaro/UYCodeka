<?php
include ("../conectar.php");
include ("../funciones/fechas.php");

$codpresupuesto=$_GET["codpresupuesto"];
$codfactura=$_GET["num"];
$fecha=$_GET["fecha"];

/*
$sel_presupuesto="SELECT fecha FROM presupuestos WHERE codpresupuesto='$codpresupuesto'";
$rs_presupuesto=mysql_query($sel_presupuesto);
$fecha=mysql_result($rs_presupuesto,0,"fecha");
$fecha=implota($fecha);
*/
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
		<link href="../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/lang/calendar-sp.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar-setup.js"></script>
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

		function limpiar() {
			document.getElementById("formulario").reset();
		}

		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">CONVERTIR PRESUPUESTO </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_presupuesto.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="14%">C&oacute;digo&nbsp;de&nbsp;presupuesto</td>
						    <td width="36%"><?php echo $codpresupuesto;?></td>
				            <td width="50%"><ul id="lista-errores"></ul></td>
						</tr>
						<tr>
							<td width="14%">N.&nbsp;Factura</td>
						   <td width="36%"><input type="text" name="Acodfactura" id="codfactura" class="cajaMedia" value="<?php echo $codfactura;?>" maxlength="20"></td>
				         <td width="50%"></td>
						</tr>
						<tr>
							<td width="14%">Fecha</td>
						    <td width="36%"><input NAME="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $fecha;?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
					        <script type="text/javascript">
										Calendar.setup(
										  {
										inputField : "fecha",
										ifFormat   : "%d/%m/%Y",
										button     : "Image1"
										  }
										);
							</script></td>
				            <td width="50%"></td>
						</tr>
					</table>
			  </div>
				<div>
					<input type="hidden" name="id" id="id" value="">
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar(formulario,true)" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" onClick="limpiar()" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
					<input id="accion" name="accion" value="convertir" type="hidden">
					<input id="codpresupuesto" name="codpresupuesto" value="<?php echo $codpresupuesto;?>" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
	</body>
</html>
