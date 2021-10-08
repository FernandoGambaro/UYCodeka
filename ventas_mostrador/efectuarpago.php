<html>
<head>
<title>Pago Mostrador Venta</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<link href="../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/lang/calendar-sp.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar-setup.js"></script>
		
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>

<script>

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
		
function actualizarimporte() {
	var importe=document.getElementById("importe").value;
	var importevale=document.getElementById("importevale").value;
	if( importevale.search('[^0-9.]') == -1 ) 
		{
			var resta=parseFloat(importe.replace(/[^\d\.\-\ ]/g, '')-importevale.replace(/[^\d\.\-\ ]/g, ''));
			var valor=Math.round(resta*100)/100 ;
			document.getElementById("apagar").value=valor;
		} else {
			alert ("El importe del vale no es correcto.");
		}		
}

function actualizarimportedevolver() {
	var importe=document.getElementById("apagar").value;
	var pagado=document.getElementById("pagado").value;
	if( pagado.search('[^0-9.]') == -1 ) 
		{
			var resta=parseFloat(pagado.replace(/[^\d\.\-\ ]/g, '')-importe.replace(/[^\d\.\-\ ]/g, ''));
			var valor=Math.round(resta*100)/100 ;
			document.getElementById("adevolver").value=valor;
		} else {
			alert ("El importe pagado no es correcto.");
		}		
}

function imprimir(codfactura) {
	var pagado=document.getElementById("pagado").value;
	var adevolver=document.getElementById("adevolver").value;
	location.href="../fpdf/imprimir_ticket_html.php?codfactura=" + codfactura + "&pagado=" + pagado + "&adevolver=" + adevolver;
}

function enviar() {
	var mensaje="";
	if (document.getElementById("pagado").value=="") {
		mensaje=" importe a pagar nulo\n";
			} else {
				if (isNaN(document.getElementById("precio").value)==true) {
					mensaje+="  - El precio debe ser númerico\n";
				}
			}
	if (mensaje!="") {
		alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
	} else {
		alert('pp');
	document.getElementById("formulario").submit();
	parent.$('idOfDomElement').colorbox.close();
	}			
}




</script>
</head>
<?php include ("../conectar.php"); 

$codfactura=$_GET["codfactura"];
$codcliente=$_GET["codcliente"];
$importe=$_GET["importe"]; 
$moneda=$_GET["moneda"]; 

$sel_clientes="SELECT nombre, apellido, empresa FROM clientes WHERE codcliente='$codcliente'";
$rs_clientes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_clientes);
//$nombre_cliente=mysql_result($rs_clientes,0,"nombre");

if (!empty(mysqli_result($rs_clientes, 0, "empresa"))) {
	 $nombre_cliente= mysqli_result($rs_clientes, 0, "empresa");
	} elseif (empty(mysqli_result($rs_clientes, 0, "apellido"))) {
		$nombre_cliente= mysqli_result($rs_clientes, 0, "nombre");
	} else { 
		$nombre_cliente= mysqli_result($rs_clientes, 0, "nombre") .' '. mysqli_result($rs_clientes, 0, "apellido");
	}
	 	  


?>
<body>
<br>
	
<table width="97%"><td>
<div id="pagina">
<div id="zonaContenido">
<div id="tituloForm2" class="header">COBRO 

				  <?php
   
			  	   $tipof = array(  1=>"Pesos", 2=>"U\$S");
					foreach ($tipof as $key => $monedai ) {
					  	if ( $moneda==$key and $moneda!='' ) {
							//echo "....".$monedai;
							break;
						}
					}
					?>

</div>
<div id="frmBusqueda2">
<div align="center">
<form id="formulario" name="formulario" method="post" action="guardar_cobro.php" target="frame_datos">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0><tr><td>
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="25%">C&oacute;digo&nbsp;Factura </td>
				        	<td width="75%"><input NAME="codfactura" type="text" class="cajaPequena" id="codfactura" size="15" maxlength="15" value="<?php echo $codfactura?>" readonly="yes">						</tr>
						<tr>
							<td>Cliente</td>
						    <td><input NAME="nombre_cliente" type="text" class="cajaGrande" id="nombre_cliente" size="45" maxlength="45" value="<?php echo $nombre_cliente;?>" readonly></td>
			            </tr>
						<tr>
							<td>Importe</td>
						    <td><input NAME="importe" type="text" class="cajaPequena" id="importe" size="10" maxlength="10" value="<?php echo $importe?>" readonly>
				 				<input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
						    </td>
			            </tr>
						<tr>
							<td>Importe&nbsp;vale</td>
						    <td><input NAME="importevale" type="text" class="cajaPequena" id="importevale" size="10" maxlength="10" value="0" onblur="actualizarimporte();">
				 				<input type="text" class="cajaPequena2" id="monSh" name="moneda" value="<?php echo $monedai;?>" readonly>
						     <img src="../img/disco.png" name="Image2" id="Image2" width="16" height="16" border="0" id="Image2" onMouseOver="this.style.cursor='pointer'" title="Aplicar Vale"
						      onClick="actualizarimporte();"></td>
			            </tr>
						<tr>
							<td>A&nbsp;pagar</td>
						    <td><input NAME="apagar" type="text" class="cajaPequena" id="apagar" size="10" maxlength="10" value="<?php echo $importe?>" readonly>
				 				<input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
						    </td>
			            </tr>
						<tr>
							<td>Pagado</td>
						    <td><input NAME="pagado" type="text" class="cajaPequena" id="pagado" size="10" maxlength="10" onblur="actualizarimportedevolver();"> 
				 				<input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
						    <img src="../img/dinero.jpg" name="Image2" id="Image2" width="16" height="16" border="0" id="Image2" onMouseOver="this.style.cursor='pointer'" title="Pagado"
						     onClick="actualizarimportedevolver();"></td>
			            </tr>
						<tr>
							<td>A&nbsp;devolver</td>
						    <td><input NAME="adevolver" type="text" class="cajaPequena" id="adevolver" size="10" maxlength="10" readonly>
				 				<input type="text" class="cajaPequena2" id="monSh" value="<?php echo $monedai;?>" readonly>
						   </td>
			            </tr>
			            </table></td><td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<?php
						$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0; ?>
						<tr>
							<td>Forma&nbsp;de&nbsp;pago</td>
						    <td><select id="formapago" name="formapago" class="comboGrande">
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { 
									if (mysqli_result($res_fp, $contador, "codformapago") ==1) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago")?>" selected="selected"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option> 
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago")?>"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php 
									}
									$contador++;
								} ?>				
								</select></td>
			            </tr>
						<tr>
							<td>N&uacute;mero de documento</td>
						    <td><input NAME="numdocumento" type="text" class="cajaGrande" id="numdocumento" size="40" maxlength="40"></td>
			            </tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td>Fecha&nbsp;de&nbsp;cobro</td>
						    <td><input NAME="fechacobro" type="text" class="cajaPequena" id="fechacobro" size="10" maxlength="10" value="<?php echo $hoy?>" readonly> <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechacobro",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
			            </tr>
			            </table></td></tr>
					</table>										
			  </div>
			  <input id="codfactura" name="codfactura" value="<?php echo $codfactura?>" type="hidden">
			  <input id="codcliente" name="codcliente" value="<?php echo $codcliente?>" type="hidden">
			  <input id="importe" name="importe" value="<?php echo $importe?>" type="hidden">
</form>

			  <div align="center">
			  <img src="../img/botonaceptar.jpg" width="85" height="22" onClick="enviar();" border="1" onMouseOver="style.cursor=cursor">
					<img src="../img/botonimprimirticket.jpg" width="122" height="22" onClick="imprimir(<?php echo $codfactura?>);" border="1" onMouseOver="style.cursor=cursor">
					<img src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar();" border="1" onMouseOver="style.cursor=cursor">
				</div>
							  
			  </div>
			  </div>			  
			  </div>
			  </div>
		
</td></table>			  
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>
