<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$codpagodgi=$_GET["codpagodgi"];
$cadena_busqueda=$_GET['cadena_busqueda'];

$query="SELECT * FROM pagodgi WHERE codpagodgi='$codpagodgi'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />
		<script type="text/javascript" src="../funciones/validar.js"></script>
    		
			<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
		
		<script language="javascript">
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
		
		</script>
<script type="text/javascript">
function  actualizar() {
	var f108=document.getElementById('f108').value;
	var f328=document.getElementById('f328').value;
	var f546=document.getElementById('f546').value;
	var f606=document.getElementById('f606').value;
	document.getElementById('total').value=parseFloat(f108)+parseFloat(f328)+parseFloat(f546)+parseFloat(f606);
	
}
</script>		
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR PAGO DGI </div>
				<div id="frmBusqueda">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha</td><td>
							<?php $hoy=implota(date("Y-m-d"));?>
						    <input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10"  value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script></td>
						<td>&nbsp;</td>
						</tr>					
						<tr>
							<td>Año</td>
						    <td><select name="anio" id="anio" class="cajaPequena2" readonly>
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
						   <td>Mes&nbsp;<select name="mes" id="mes" class="cajaPequena" readonly>
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
						<tr><td>108        </td><td>IRAE - Anticipo                  </td><td><input name="f108" type="text" class="cajaTotales" id="f108" value="<?php echo mysqli_result($rs_query, 0, "f108");?>" onchange="actualizar();" readonly></td></tr>
						<tr><td>328        </td><td>IMPUESTO AL PATRIMONIO - Anticipo</td><td><input name="f328" type="text" class="cajaTotales" id="f328" value="<?php echo mysqli_result($rs_query, 0, "f328");?>" onchange="actualizar();" readonly></td></tr>
						<tr><td>546        </td><td>IVA - Contribuyente No CEDE      </td><td><input name="f546" type="text" class="cajaTotales" id="f546" value="<?php echo mysqli_result($rs_query, 0, "f546");?>" onchange="actualizar();" readonly></td></tr>
						<tr><td>606        </td><td>ICOSA Anticipo                   </td><td><input name="f606" type="text" class="cajaTotales" id="f606" value="<?php echo mysqli_result($rs_query, 0, "f606");?>" onchange="actualizar();" readonly></td></tr>
						<tr><td>           </td><td><div align="right">Total</div>   </td><td><input  type="text" class="cajaTotales" id="total" value="0" readonly></td></tr>
											
					</table>
			  </div>
				<div>
					<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="cancelar();" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>
 	<script type="text/javascript">
 	actualizar();
 	</script>		
	</body>
</html>
