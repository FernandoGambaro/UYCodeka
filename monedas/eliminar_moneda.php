<?php
include ("../conectar.php"); 

$codmoneda=$_GET["codmoneda"];

$query="SELECT * FROM monedas WHERE codmoneda='$codmoneda'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>		<script language="javascript">

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
				<div id="tituloForm" class="header">ELIMINAR MONEDA </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_moneda.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td>&nbsp;Tipo&nbsp;</td><td>
						<select  type="text" size="1" name="Atipo" id="tipo" class="comboMedio" >
						<?php
						$Tipox = array(
								 0=>'No seleccionado',
						        1=>"Secundaria",
						        2=>"Principal");
						$xx=0;
						$orden= mysqli_result($rs_query, 0, "orden");
						foreach($Tipox as $ii) {
						  	if ( $xx== $orden)	{
								echo "<option value=$xx selected>$ii</option>";
							}	else 	{
								echo "<option value=$xx>$ii</option>";
							}
							$xx++;
						}	
						?>
						</select>
						</td>
						</tr><tr>						
							<td>C&oacute;digo</td>
							<td><?php echo mysqli_result($rs_query, 0, "moneda");?></td>
						</tr>
						<tr>
							<td width="15%">Númerico</td>
						    <td width="43%"><input name="Anumerico" type="text" class="cajaPequena" id="numerico" size="3" maxlength="3" value="<?php echo mysqli_result($rs_query, 0, "numerico")?>"></td>
				        </tr>
						<tr>
							<td width="15%">Descripción</td>
						    <td width="43%"><input name="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="20" maxlength="20" value="<?php echo mysqli_result($rs_query, 0, "descripcion")?>"></td>
				        </tr>
						<tr>
							<td width="15%">Símbolo</td>
						    <td width="43%"><input name="Asimbolo" type="text" class="cajaPequena" id="valor" size="15" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "simbolo")?>"> </td>
				        </tr>
						<tr>
							<td width="15%">Fracción</td>
						    <td width="43%"><input name="fraccion" type="text" class="cajaPequena" id="valor" size="15" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "fraccion")?>"> </td>
				        </tr>
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="baja" type="hidden">
					<input id="id" name="Zid" value="<?php echo $codmoneda?>" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
		
	</body>
</html>
