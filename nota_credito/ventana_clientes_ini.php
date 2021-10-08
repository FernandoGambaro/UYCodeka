<html>
<head>
<title>Buscador de Clientes</title>
<script>
var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}


function enviar() {
	document.getElementById("form1").submit();
	document.getElementById("tabla_resultado").style.display="";
}

</script>

<script language="javascript">

function pon_prefijo(pref,nombre,nif) {
	parent.pon_prefijo_b(pref,nombre,nif);
}


</script>

<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<?php include ("../conectar.php"); ?>
<body onLoad="enviar();">
<div style="width: 100%;  margin: 0 auto;">

<form name="form1" id="form1" method="post" action="frame_clientes_ini.php" target="frame_resultado" onSubmit="buscar();">
<div align="center">
<div id="frmBusqueda">
 <div align="center">
<table class="fuente8" align="center">
     <tr>
		<td class="busqueda">Código:</td>
	    <td><input name="codcliente" type="text" id="codcliente" size="20" class="cajaMedia"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="nombre" type="text" id="nombre" size="50" class="cajaGrande"></td>
		</tr>
		<tr>

		  <td colspan="4" align="center"><img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor"></td>
	  </tr>
</table>
</div></div></div>


			<iframe width="100%" height="300" id="frame_resultado" name="frame_resultado" frameborder="0" >
				<ilayer width="100%" height="300" id="frame_resultado" name="frame_resultado"></ilayer>
			</iframe>

<input type="hidden" id="iniciopagina" name="iniciopagina">
<div align="center">
      <img id="botonBusqueda" src="../img/botoncerrar.jpg" width="70" height="22" onClick="parent.$('idOfDomElement').colorbox.close();" border="0" onMouseOver="style.cursor=cursor">
    </div>
<br>&nbsp;
</form>
</body>
</html>
</body>
</html>