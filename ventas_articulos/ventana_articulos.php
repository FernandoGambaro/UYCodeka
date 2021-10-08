<html>
<head>
<title>Buscador de Articulos</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
	
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
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
<script type="text/javascript">
$(document).ready( function(){
	$("form:not(.filter) :input:visible:enabled:first").focus();
});
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

function inicio() {

	var combo_familia=document.getElementById("cmbfamilia").value;
	if (combo_familia==0) {
		buscar();
	} else {
		document.getElementById("tabla_resultado").style.display="none";
	}
			
}

function paginar() {
	document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
	document.getElementById("form1").submit();
}

function enviar() {
	document.getElementById("form1").submit();
}

</script>
</head>
<?php include ("../conectar.php");
$anterior='';
 ?>
<body onLoad="buscar()">
<form name="form1" id="form1" method="post" action="frame_articulos.php" target="frame_resultado" onSubmit="buscar()">	
<div align="center">
<div id="frmBusqueda">
 <div align="center">
	<table class="fuente8" align="center" border="0" >
     <tr>
	    <td>Familia:</td>
	    <td colspan="2">
		  <select id="cmbfamilia" name="cmbfamilia" class="comboGrande" data-index="1">
		  <?php
		    $consultafamilia="select * from familias where borrado=0 order by nombre ASC";
			$queryfamilia=mysqli_query($GLOBALS["___mysqli_ston"], $consultafamilia);
			?><option value=0>Todos los articulos</option><?php
			while ($rowfamilia=mysqli_fetch_row($queryfamilia))
			  { 
			  	if ($anterior==$rowfamilia[0]) { ?>
					<option value="<?php echo $rowfamilia[0]?>" selected><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	} else { ?>
					<option value="<?php echo $rowfamilia[0]?>"><?php echo utf8_encode($rowfamilia[1])?></option>
			<?php	}   
		   	  };
		  ?>
	    </select>		</td></tr>
		<tr>
		<td class="busqueda">Referencia:</td>
	    <td colspan="2"><input name="referencia" type="text" id="referencia" size="20" class="cajaMedia" data-index="2"></td></tr>
		<tr><td class="busqueda">Descripci&oacute;n:</td>
	    <td><input name="descripcion" type="text" id="descripcion" size="50" class="cajaGrande" data-index="3"></td>
		  <td class="busqueda">
		  <img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="enviar();" onMouseOver="style.cursor=cursor">
		  </td>
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

