<?php 
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
/*/user is not logged in*/
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$fechahoy=date("Y-m-d");
/*$sel_fact="INSERT INTO facturastmp (codfactura,fecha) VALUE ('','$fechahoy')";
$rs_fact=mysql_query($sel_fact);
$codfacturatmp=mysql_insert_id();
*/

if($_GET['fecha']=='') {
	$fechahoy=date("Y-m-d");
	$hoy=implota($fechahoy);
	$sel_fact="SELECT codfactura FROM `facturas` ORDER BY fecha DESC LIMIT 1 ";
	$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);
	$codfactura=(int)mysqli_result($rs_fact, 0, "codfactura")+1;
} else {
	$fechahoy=explota($fechahoy);
}
$sel_fact="INSERT INTO facturastmp (codfactura,fecha) VALUE ('$codfactura','$fechahoy')";
$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);
$codfacturatmp=$codfactura;

$tipo=1;
$moneda=1;


$sel_imp="select * from `impuestos` where `fecha` <= '$fechahoy' and `borrado` = 0 ORDER BY `fecha` DESC limit 1";
$rs_imp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_imp);
if ($rowimp=mysqli_fetch_row($rs_imp)){
$iva=$rowimp[3];
}

$descuentogral=0;
	/*$sel_borrar = "DELETE FROM factulineatmp WHERE codfactura > 0 ";
	$rs_borrar = mysql_query($sel_borrar);*/

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../common/timeout.js"></script>
		
		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>

<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>


<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML=" Ventas mostrador ";

var index;

$(document).keydown(function(e) {

    switch(e.keyCode) { 
        case 9:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aCliente").value;
	        		if (codigo=='') {
						abreVentana();
	        			$('[data-index="' + (index + 1).toString() + '"]').focus();
		        		} else {
		        		validarcliente();
		        		}
		     }
			  if (index==8) {
		     		var codigo=document.getElementById("codbarras").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 1).toString() + '"]').focus();	
		     		}
		     }
			  if (index==13) {
		        		validar();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aCliente").value;
	        		if (codigo=='') {
						abreVentana();
	        			$('[data-index="' + (index + 1).toString() + '"]').focus();
		        		} else {
		        		validarcliente();
		        		}
		     }
			  if (index==8) {
		     		var codigo=document.getElementById("codbarras").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		        		$('[data-index="' + (index + 1).toString() + '"]').focus();	
		     		}
		     }
			  if (index==13) {
		        		validar();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();

        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

</script>		
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();


});
</script>
<script type="text/javascript">
$(document).ready( function()
{

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>

<script type="text/javascript">
function OpenNote(noteId,w,h){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
	});
}

function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			
	});

}

function pon_prefijo_b(pref,nombre,nif) {
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo (codfamilia,pref,nombre,precio,codarticulo,moneda,codservice,detalles) {
	var monArray = new Array();
	monArray[0]="Selecione uno";
	monArray[1]="Pesos";
	monArray[2]="U\$S";
	$("#codfamilia").val(codfamilia);
	$("#codbarras").val(pref);

	$("#codservice").val(codservice);
	$("#detalles").val(detalles);
	
	$("#descripcion").val(nombre);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
	cambio();
	actualizar_importe();
}
</script>
<script type="text/javascript">
function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}
</script>			
		<script language="javascript">
		var borrado=0;
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function ventanaArticulos(){
			var mensaje="";
			if (document.getElementById("codfacturatmpaux").value=="") mensaje="  - Nº factura no válido<br>";
				if (mensaje!="") {
					showWarningToast("Atencion, se han detectado las siguientes incorrecciones:<br>"+mensaje);
					$("#codfacturatmpaux").focus();
				} else {
				document.getElementById("codfacturaaux").value=document.getElementById("codfacturatmpaux").value;
				var codigo=document.getElementById("aCliente").value;
				if (codigo=="") {
					showWarningToast("Debe introducir el codigo del cliente");
				} else {
					$.colorbox({
	   				href: "ver_articulos.php", open:true,
						iframe:true, width:"800", height:"450",
						scrolling: false,
						onCleanup:function() {
						$('#precio').focus();
						}
					});
				}
			}
		}
		function abreVentana(){
			$.colorbox({
	   	href: "ver_clientes.php", open:true,
			iframe:true, width:"580", height:"450",
			onCleanup:function() {
				$('#tipo').focus();
				}			
			});			
		}		
		function validarcliente(){
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"350", height:"200",
				onCleanup:function() {
						$('#codfacturatmpaux').focus();
						}				
				
				});
		}
		
		function validarArticulo(){
			var codigo=document.getElementById("codbarras").value;
				$.colorbox({href:"comprobararticulo.php?codbarras="+codigo,
				iframe:true, width:"100", height:"100",
				onCleanup:function() {
						$('#precio').focus();
						}
				});
		}		
		
		function cancelar() {
			location.href="index.php";
		}
		
		function limpiarcaja() {
			document.getElementById("nombre").value="";
		}
		
		function actualizar_importe()
			{
				/*Si la factura es en pesos y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambiofactura=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
				var tipocambioarcticulo=document.getElementById("moneda").value;
				if (tipocambiofactura==1 && tipocambioarcticulo == 2){
					var precio=document.getElementById("precio").value * parseFloat(document.getElementById("tipocambio").value);
				}
				if (tipocambiofactura==2 && tipocambioarcticulo == 1){
					var precio=document.getElementById("precio").value / parseFloat(document.getElementById("tipocambio").value);
				}
				if ((tipocambiofactura==1 && tipocambioarcticulo == 1) || (tipocambiofactura==2 && tipocambioarcticulo == 2)){
				var precio=document.getElementById("precio").value;
				}
				var cantidad=document.getElementById("cantidad").value;
				var descuento=document.getElementById("descuento").value;
				var descuentopp=document.getElementById("descuentopp").value;
				descuento=descuento/100;
				descuentopp=descuentopp/100;
				

				total=precio*cantidad;

				descuento=total*descuento;
				total=total-descuento;

				descuentopp=total*descuentopp;
				total=total-descuentopp;

				var original=parseFloat(total);
				var result=round(original,2) ;
				document.getElementById("importe").value=result;
			}
			
		function validar_cabecera()
			{
				var mensaje="";
				if (document.getElementById("codfacturatmpaux").value=="") mensaje="  - Nº factura no válido<br>";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (mensaje!="") {
					showWarningToast("Erores detectados:<br>"+mensaje);
				} else {
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Codigo de barras\n";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion\n";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio\n"; 
						} else {
							if (isNaN(document.getElementById("precio").value)==true) {
								mensaje+="  - El precio debe ser numerico\n";
							}
						}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad\n";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numerica\n";
							} else {
									document.getElementById("cantidad").value=enteroo;
								}
						}
				if (document.getElementById("descuento").value=="") 
						{ 
						document.getElementById("descuento").value=0 
						} else {
							entero=parseInt(document.getElementById("descuento").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento debe ser numerico\n";
							} else {
								document.getElementById("descuento").value=entero;
							}
						}
				if (document.getElementById("descuentopp").value=="") 
						{ 
						document.getElementById("descuentopp").value=0 
						} else {
							entero=parseInt(document.getElementById("descuentopp").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento pronto pago debe ser numerico\n";
							} else {
								document.getElementById("descuentopp").value=entero;
							}
						}						 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe\n";
				
				if (mensaje!="") {
					showWarningToast("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
					var descuentogral=document.getElementById("descuentogralaux").value;
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);
					
					document.getElementById("baseimponibledescuento").value=	round((parseFloat(document.getElementById("baseimponible").value) / (1+descuentogral/100)),2);
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value=1;
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;						
					document.getElementById("descuentopp").value=0;						
				}
				$('#codbarras').focus();
			}

		function actualizo_descuento() {
				var descuentogral=parseFloat(document.getElementById("descuentogralaux").value);
				document.getElementById("baseimponibledescuento").value=	round((parseFloat(document.getElementById("baseimponible").value) * (1-descuentogral/100)),2);
				cambio_iva();
		}			
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);

			var result=round(original,2) ;
			document.getElementById("baseimponible").value=result;

			var descuentogral=document.getElementById("descuentogralaux").value;
			if (descuentogral=0) {
			var original=parseFloat(document.getElementById("baseimponible").value);
			} else {
			var original=parseFloat(document.getElementById("baseimponibledescuento").value);
			}
			var result=round(original,2) ;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=round(original1,2) ;
			document.getElementById("baseimpuestos").value=result1+0;
			var original2=parseFloat(result + result1);
			var result2=round(original2,2) ;
			document.getElementById("preciototal").value=result2+0;
		}	
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);			
	 		
		}		
		var tipoaux='';
		function cambio() {
			var Index = document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="Selecione uno";
			monArray[1]="Pesos";
			monArray[2]="U\$S";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

				if (tipoaux==1 && Index == 2){
					document.getElementById("baseimponible").value=round(($("#baseimponible").val() / parseFloat($("#tipocambio").val())) , 2);
					document.getElementById("baseimpuestos").value=round(($("#baseimpuestos").val() / parseFloat($("#tipocambio").val())) , 2);
					document.getElementById("preciototal").value=round(($("#preciototal").val() / parseFloat($("#tipocambio").val())) , 2);
				}
				if (tipoaux==2 && Index == 1){
					document.getElementById("baseimponible").value=round(($("#baseimponible").val() * parseFloat($("#tipocambio").val())) , 2);
					document.getElementById("baseimpuestos").value=round(($("#baseimpuestos").val() * parseFloat($("#tipocambio").val())) , 2);
					document.getElementById("preciototal").value=round(($("#preciototal").val() * parseFloat($("#tipocambio").val())) , 2);
				}
			tipoaux=Index;
		}
		</script>
	</head>
	<body onload="busco_tipocambio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"> NUEVA VENTA </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_factura.php">
					<table class="fuente8" width="70%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td >C&oacute;digo&nbsp;Cliente </td>
					      <td colspan="2"><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" onClick="limpiarcaja();" data-index="1">
					      <img src="../img/ver.png" width="16" height="16" onClick="OpenNote('ver_clientes.php',880,450);" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
					       <img src="../img/cliente.png" width="16" height="16" onClick="validarcliente();" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
					      </td>					
							<td>Nombre</td>
						    <td colspan="4"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" readonly></td>
						  <td>Nº&nbsp;factura</td>
						  <td colspan="2">
							<input id="codfacturatmpaux" class="cajaPequena" name="codfacturatmpaux" value="<?php echo @$codfacturatmp?>" onblur="borro_numfactura();" data-index="2">
							<input type="hidden" id="outside" value="0">						  
						  </td>
							<td>Tipo</td>
				            <td>
				            
				            <select id="tipo" name="atipo" class="cajaPequena" data-index="3">

					<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
					if ($tipo==" ")
					{
					echo '<OPTION value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($tipof as $i) {
					  	if ( $x==$tipo) {
							echo "<OPTION value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<OPTION value=$x>$i</option>";
						}
						$x++;
					}
					?>				            
				      </td>						  				         					        					
						</tr>						
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td>Fecha</td>
						    <td><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $hoy?>" readonly data-index="4">
							<img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">//<![CDATA[
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); busco_tipocambio(); },
						     dateFormat : "%d/%m/%Y"
						   });
						//]]></script>	
							</td>
							<td></td>
				            <td width="3%">IVA</td>
				            <td><input name="iva" type="text" class="cajaMinima" id="iva" size="5" maxlength="5" value="<?php echo $iva; ?>" onChange="cambio_iva()" data-index="5"> %</td>

						<td>Tipo&nbsp;cambio</td>
						<td><label>U$S -> $&nbsp;</label></td><td>
						<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo @$tipocambio; ?>" data-index="6"></td></td>
						<td>Moneda</td><td>
		 					<select onchange="cambio();" name="Amoneda" id="Amoneda" class="cajaPequena2" data-index="7">
							<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
							if ($moneda==" ")
							{
							echo '<OPTION value="" selected>Selecione uno</option>';
							}
							foreach ($tipofa as $key => $i ) {
							  	if ( $moneda==$key ) {
									echo "<OPTION value=$key selected>$i</option>";
								} else {
									echo "<OPTION value=$key>$i</option>";
								}
		
							}
							?>
							</select>							

  							
  							</td>
						</tr>
					</table>										
			  </div>
			  <input id="codfacturatmp" name="codfacturatmp" value="<?php echo @$codfacturatmp?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo @$baseimpuestos?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo @$baseimponible?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo @$preciototal?>" type="hidden">
			  <input id="accion" name="accion" value="alta" type="hidden">
			  </form>
				<br style="line-height:5px">
			  <div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">

			  <input id="codfacturaaux" name="codfacturatmpaux" value="<?php echo @$codfacturatmp?>" type="hidden">
				
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
				  <tr>
					<td width="11%">Codigo barras </td>
					<td colspan="2" valign="middle"><input name="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15" data-index="8">
					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;" title="Validar codigo de barras">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;" title="Buscar articulo"></td>
					<td>Descripcion</td>
					<td colspan="5" ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="50" maxlength="60" readonly></td>
					<td>Moneda </td>					 
					 <td colspan="2">
					 <input name="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>
					 <input name="moneda"  id="moneda" type="hidden" >
					 </td>
				  </tr>
				  <tr>
					<td valign="top">Detalles</td>
					<td colspan="2"><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles"> </textarea>
					</td>
					<td valign="top">Precio</td>
					<td valign="top"><input name="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" onChange="actualizar_importe();" data-index="9"></td>
					<td valign="top">Cantidad</td>
					<td valign="top"><input name="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe();" data-index="10"></td>
					<td valign="top">Dcto.</td>
					<td valign="top"><input name="descuento" type="text" class="cajaMinima" id="descuento" size="10" maxlength="10" onChange="actualizar_importe();" data-index="11"> %</td>
					<td valign="top">Dcto.&nbsp;PP</td>
					<td valign="top" colspan="2"><input name="descuentopp" type="text" class="cajaMinima" id="descuentopp" size="10" maxlength="10" onChange="actualizar_importe();" data-index="12">&nbsp;%</td>
					<td valign="top">Importe</td>
					<td valign="top"><input name="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" readonly data-index="13"></td>
					<td width="23%" valign="top" align="right"><img id="botonBusqueda" src="../img/botonagregar.jpg" width="72" height="22" border="1" onClick="validar();" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>

				  </tr>
				</table>
				</div>
				<br style="line-height:5px">
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="3%">ITEM</td>
							<td width="14%" align="left">&nbsp;DESCRIPCION</td>
							<td width="42%" align="left">&nbsp;DETALLES</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="4%">Dcto.&nbsp;%</td>
							<td width="4%">D.&nbsp;PP&nbsp;%</td>
							<td width="5%">MONEDA</td>
							<td width="7%">IMPORTE</td>
							<td width="3%">ACC.</td>
							<td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						</tr>
				<tr><td colspan="10" width="100%">
					<iframe width="100%" height="200" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="200" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>
				</td></tr>
				</table>
			  </div>
			  <div id="frmBusqueda">
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			<tr>
			<td align="rigth" valign="top">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40" class="areaTexto"></textarea>
				</td>
				<td colspan="5"></td>
			    <td class="busqueda">Sub-total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda" >Descuento</td>
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" value="0" class="cajaMinima" onChange="actualizo_descuento();"></td>
				<td class="busqueda">&nbsp;%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" size="12" value=0 align="right" readonly> 
				</td>
				<td class="busqueda">IVA</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value=0 readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td>
					<div align="center">
				   	<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="validar_cabecera();" border="1" onMouseOver="style.cursor=cursor">
						<img id="botonBusqueda" src="../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar();" border="1" onMouseOver="style.cursor=cursor">
				    	<input id="codfamilia" name="codfamilia" value="<?php echo @$codfamilia?>" type="hidden">
				    	<input id="codfacturatmp" name="codfacturatmp" value="<?php echo @$codfacturatmp?>" type="hidden">	
						<input id="preciototal2" name="preciototal" value="<?php echo @$preciototal?>" type="hidden">
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value=0 readonly> 
		        </div></td>				
				</tr> 				
				</table>
				</td><td >
				
			  </tr>
		</table>
			  </div>
				
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
		  </div>
		</div>
		<script type="text/javascript">
		cambio();
		</script>
	</body>
</html>
