<html>
<head>
<title>Buscador de Clientes</title>
		<script src="../js/jquery.min.js"></script>
		<link rel="stylesheet" href="../js/colorbox.css" />
		<script src="../js/jquery.colorbox.js"></script>

<script type="text/javascript">
function pon_prefijo(pref,descripcion,service) {
	parent.pon_prefijo(pref,descripcion,service);
}
</script>
<script>
var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}


function buscar() {
	if (document.getElementById("iniciopagina").value=="") {
		document.getElementById("iniciopagina").value=1;
	} else {
		document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	}
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

</script>
<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>
<?php include ("../../conectar.php");
	$e=$_GET['e'];

 ?>
<body onLoad="buscar()">
<form name="form1" id="form1" method="post" action="frame_equipos.php" target="frame_resultado" onSubmit="buscar()">
	<input type="hidden" id="e" name="e" value="<?php echo $e?>">
  <table width="95%" id="tabla_resultado" name="tabla_resultado" style="display:none" align="center">
	<tr>
  		<td>
			<iframe width="100%" height="300" id="frame_resultado" name="frame_resultado">
				<ilayer width="100%" height="300" id="frame_resultado" name="frame_resultado"></ilayer>
			</iframe>
		</td>
	</tr>
</table>
<input type="hidden" id="iniciopagina" name="iniciopagina">
<table width="100%" border="0">
  <tr>
    <td><div align="center">
      <img id="botonBusqueda" src="../../img/botoncerrar.jpg" width="70" height="22" onClick="parent.$('idOfDomElement').colorbox.close();" border="1" onMouseOver="style.cursor=cursor">
    </div></td>
  </tr>
</table>

</form>
</body>
</html>
