<?php 
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
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
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);

include ("../conectar.php");
include("../common/verificopermisos.php");
include ("../funciones/fechas.php"); 

//header('Content-Type: text/html; charset=UTF-8'); 

$baseimponible='';
$tipocambio='';
$modif='';
$total=0;

$codrecibo=$_GET["codrecibo"];

$sel_alb="SELECT * FROM recibos WHERE codrecibo='$codrecibo'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
$codcliente=mysqli_result($rs_alb, 0, "codcliente");
$moneda=mysqli_result($rs_alb, 0, "moneda");
$fecha=mysqli_result($rs_alb, 0, "fecha");
$importe=mysqli_result($rs_alb, 0, "importe");


$sel_cliente="SELECT nombre,apellido,empresa,nif FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
$nombre=mysqli_result($rs_cliente, 0, "nombre");
$nombre=mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");

$nif=mysqli_result($rs_cliente, 0, "nif");

$fechahoy=date("Y-m-d");


$sel_lineas="SELECT * FROM recibosfactura WHERE codrecibo='$codrecibo' ORDER BY codrecibo ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM recibosfacturatmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$contador=0;
//echo mysql_num_rows($rs_lineas);
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codfactura=mysqli_result($rs_lineas, $contador, "codfactura");
	$sel_tmp="INSERT INTO recibosfacturatmp (codrecibo,codfactura) VALUES ('$codrecibo','$codfactura')";
	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	$contador++;
}

$sel_lineas="SELECT * FROM recibospago WHERE codrecibo='$codrecibo'";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM recibospagotmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$contador=0;
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codrecibo=mysqli_result($rs_lineas, $contador, "codrecibo");
	$tipo=mysqli_result($rs_lineas, $contador, "tipo");
	$codentidad=mysqli_result($rs_lineas, $contador, "codentidad");
	$numeroserie=mysqli_result($rs_lineas, $contador, "numeroserie");
	$numero=mysqli_result($rs_lineas, $contador, "numero");
	$monedapago=mysqli_result($rs_lineas, $contador, "monedapago");
	$importedoc=mysqli_result($rs_lineas, $contador, "importedoc");
	$importe=mysqli_result($rs_lineas, $contador, "importe");
	$tipocambio=mysqli_result($rs_lineas, $contador, "tipocambio");
	$total+=$importe;
	$fechapago=mysqli_result($rs_lineas, $contador, "fechapago");
	$observaciones=mysqli_result($rs_lineas, $contador, "observaciones");
	
		$sel_tmp="INSERT INTO recibospagotmp (codrecibopago,codrecibo,tipo,codentidad,numeroserie,numero,monedapago,tipocambio,importedoc,importe,fechapago,observaciones) 
			VALUES (NULL,'$codrecibo', '$tipo', '$codentidad', '$numeroserie', '$numero', '$monedapago', '$tipocambio', '$importedoc', '$importe', '$fechapago', '$observaciones')";
		$res_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);	
	
	$contador++;
}

$codentidad=0;
$tipo=0;
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
		
<script src="../js3/jquery.min.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.colorbox.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
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
			  if (index==14) {
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
			  if (index==14) {
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
$("form:not(.filter) :input:visible:enabled:first").focus();

		$(".callbacks").colorbox({
			iframe:true, width:"720px", height:"98%",
			onCleanup:function(){ window.location.reload();	}
		});

});
</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"90%", height:"80%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}
function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo_b(pref,nombre,nif) {
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();

	$("#codcliente").val(pref);
	//var moneda=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
	//$("#moneda").val(moneda);
	document.formulario_facturas.submit();
}

function cambiomoneda() {
	var moneda=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
	$("#moneda").val(moneda);
	$("#Cmoneda").val(moneda);
	document.formulario_facturas.submit();
}

function cambionumero() {
	var num=$("#codrecibo").val();
	$("#acodrecibo").val(num);
	$("#bcodrecibo").val(num);
	$("#recibo").text(num);
}
function agregar_factura(codfactura,moneda) {

	document.getElementById("codfactura").value=codfactura;
	$("#Amoneda").val(moneda);
	$("#moneda").val(moneda);
	$("#Cmoneda").val(moneda);
	document.formulario_facturas_sel.submit();

}

function pon_importe(importe) {
	if (importe!="undefined" && !isNaN(importe)) {
		importe=round(importe, 2);
		$("#totalrecibo").val(importe);
	} else {
		$("#totalrecibo").val(0);
	}	
		var saldo=$("#apagar").val()-$("#totalrecibo").val();
		saldo=round(saldo, 2);
		$("#saldo").val(saldo);
}
function pon_apagar(importe) {
	importe=round(importe, 2);
	$("#apagar").val(importe);
	var saldo=$("#apagar").val()-$("#totalrecibo").val();
	saldo=round(saldo, 2);
	$("#saldo").val(saldo);
}
function pon_cantidad(cant) {
	$("#cantidad").val(cant);
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
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		function abreVentana(){
			$.colorbox({
	   	href: "ventana_clientes_ini.php", open:true,
			iframe:true, width:"580", height:"450",
			onCleanup:function() {
				$('#tipo').focus();
				}			
			});
			
			
		}		
		
		function inicio() {
			document.formulario_facturas.submit();
			
			document.getElementById("modiffactu").value=1;
			document.formulario_facturas_sel.submit();
			document.getElementById("modiffactu").value=0;
			
			document.getElementById("modifpago").value=1;
			document.formulario_pago.submit();
			document.getElementById("modifpago").value=0;
			
			var fecha=$("#fecha").val();
				$.post('busco_tipocambio.php', {fecha : fecha },  function(data){
				$("#tipocambio").val(data);
			});				
		}
				
		function validarcliente() {
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo+"&codrecibo=<?php echo $codrecibo;?>",
				iframe:true, width:"350", height:"200",
				});

		}
		
		function cancelar(codrecibo, codcliente) {
		$.post('eliminotmp.php', { codrecibo: codrecibo, codcliente:codcliente }, function(data){
        	});
			parent.$('idOfDomElement').colorbox.close();	
		}
		function validar_cabecera() {
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("importe").value=document.getElementById("totalrecibo").value;
					document.getElementById("formulario").submit();
				}
			}			
		
		function validar() {
				var mensaje="";

				if (document.getElementById("nombre").value=="") mensaje+="  - Cliente<br>";
				if (document.getElementById("codrecibo").value=="") mensaje+="  - Nº Recibo<br>";
				if (document.getElementById("cantidad").value<1) mensaje+="  - Agregue una factura a cobrar<br>";
				
				var tipo=document.formulario_pago.Atipo.options[document.formulario_pago.Atipo.selectedIndex].value;

				if (tipo==0) mensaje+="  - Tipo de pago<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Falta fecha<br>"; 
				var Amonedapago=document.formulario_pago.Amonedapago.options[document.formulario_pago.Amonedapago.selectedIndex].value;
				if (Amonedapago==0) mensaje+="  - Seleccione moneda<br>";
							 
				if (document.getElementById("Atipo").value==2) 
						{
						var cboBanco=document.formulario_pago.cboBanco.options[document.formulario_pago.cboBanco.selectedIndex].value;
						if (cboBanco==0) mensaje+="  - Entidad bancaria<br>";											 
						if (document.getElementById("anumeroserie").value=="") mensaje+="  - Nº serie<br>";
						if (document.getElementById("anumero").value=="") mensaje+="  - Nº documento<br>";
						}

				if (document.getElementById("Rimportedoc").value=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {

					document.getElementById("formulario_pago").submit();
					document.getElementById("Atipo").value=0;
					document.getElementById("fechapago").value="";
					document.getElementById("anumeroserie").value="";
					document.getElementById("anumero").value="";
					document.getElementById("cboBanco").value=0;
					document.getElementById("Rimportedoc").value="";
					document.getElementById("total").value="";
					document.getElementById("observaciones").value="";
					
				}
			}
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);			
		}		
		
		function cambio() {
			var Index = document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
			var tipoaux = document.formulario_pago.Amonedapago.options[document.formulario_pago.Amonedapago.selectedIndex].value;

				if (tipoaux==1 && Index == 2){
					document.getElementById("total").value=round(($("#Rimportedoc").val() / parseFloat($("#tipocambio").val())),2);
				} else { 
					if (tipoaux==2 && Index == 1) {
					document.getElementById("total").value=round(($("#Rimportedoc").val() * parseFloat($("#tipocambio").val())),2);
					} else {
					document.getElementById("total").value=round($("#Rimportedoc").val());
					}
				}
		}	
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR RECIBO Nº <span id="recibo"> <?php echo $codrecibo;?></span></div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_recibo.php">
				<input name="importe" id="importe" value="<?php echo $total;?>" type="hidden">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td width="5%">C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" onClick="limpiarcaja()" value="<?php echo $codcliente;?>" data-index="1">
					        <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"> 
					        <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td width="6%">Nombre</td>
						    <td width="27%"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre;?>" readonly></td>
						</tr>
						<tr>
				            <td width="5%">RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif;?>" readonly></td>
				            
						<td>Moneda</td><td width="26%">
						<select name="Amoneda" id="Amoneda" class="cajaPequena2" onchange="cambiomoneda(); cambio();" data-index="2">
					<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
					echo '<option value="" >Selecione uno</option>';
					foreach ($tipofa as $key => $i ) {
					  	if ( $moneda==$key ) {
							echo "<option value=$key selected>$i</option>";
						} else {
							echo "<option value=$key>$i</option>";
						}						
					}
					?>
					</select></td>
								
				            
						</tr>
						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td width="6%">Fecha</td>
						    <td width="27%"><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha);?>" readonly data-index="3"> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); busco_tipocambio(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
								<td width="5%">Nº recibo</td>
				            <td><input name="codrecibo" type="text" class="cajaPequena" id="codrecibo" onchange="cambionumero();" value="<?php echo $codrecibo;?>" size="20" maxlength="15" data-index="5" ></td>
						</tr>
											
					</table>									
			  </div>

			  <input id="accion" name="accion" value="modificar" type="hidden">	
			  		  
			  </form>
			  <br style="line-height:5px">
			  <div id="frmBusqueda">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>

				  <tr><td>
			  <!-- Sección listado de facturas pendientes de cobro -->
			  
				<form id="formulario_facturas" name="formulario_facturas" method="post" action="frame_facturas.php" target="frame_facturas">
				<input id="codcliente" name="cdocliente" value="<?php echo $codcliente;?>" type="hidden">
				<input id="moneda" name="moneda" value="<?php echo $moneda;?>" type="hidden">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300" >
				  <tr>
					<td width="100%" >
					<iframe width="300" height="100" id="frame_facturas" name="frame_facturas" frameborder="0">
						<ilayer width="300" height="100" id="frame_facturas" name="frame_facturas"></ilayer>
					</iframe>					
					</td>
				  </tr>
				</table>
				
				</form>
				</td>
				<!-- Sección ingreso detalles del recibo -->
				<td>
				<form id="formulario_pago" name="formulario_pago" method="post" action="frame_pagos.php" target="frame_pago">
					<input name="bcodrecibo" id="bcodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
					<input type="hidden" name="modifpago" id="modifpago" value="0">
				<input id="Cmoneda" name="Cmoneda" value="<?php echo $moneda;?>" type="hidden">
					
					<table class="fuente8" cellspacing=1 cellpadding=2 border=0 width="100%"><tr><td valign="top">
						<table class="fuente8" cellspacing=1 cellpadding=1 border=0 >
						<tr>
						
						<td>Tipo</td>
						<td>
						 <select name="Atipo" id="Atipo" class="cajaPequena" data-index="6">
					<?php $tipofa = array( 0=>"Seleccione uno", 1=>"Contado", 2=>"Cheque",3=>"Giro Bancario", 4=>"Giro RED cobranza", 5=>"Resguardo");
					foreach ($tipofa as $key => $i ) {
					  	if ( @$tipo==$key ) {
							echo "<option  value=$key selected>$i</option>";
						} else {
							echo "<option  value=$key>$i</option>";
						}

					}
					?>
					</select>
  						</td>
						<td>Fecha&nbsp;vencimiento</td>
						    <td ><input id="fechapago" type="text" class="cajaPequena" name="fechapago" maxlength="10" value="<?php echo $hoy?>" onchange="busco_tipocambio();" readonly data-index="7">
						    <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0"  onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">

								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechapago",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide(); busco_tipocambio(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>  								
							<td colspan="2">
						
								<label>U$S -> $&nbsp;</label>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" onblur="cambio();" data-index="8">							
							</td> 
						<td>Moneda</td><td>
						 <select name="Amonedapago" id="Amonedapago" class="cajaPequena2" data-index="9" onchange="cambio();">
							<?php $tipofa = array( 0=>"Seleccione uno", 1=>"Pesos", 2=>"U\$S");
							foreach ($tipofa as $key => $i ) {
									echo "<option  value=$key>$i</option>";
							}
							?>
						</select>
  							</td>							 
						    
						<?php
					  	$query_entidades="SELECT * FROM entidades WHERE borrado=0 ORDER BY nombreentidad ASC";
						$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
						$cont=0;
					  	?>
					  	</tr><tr>
							<td>Entidad&nbsp;Bancaria</td>
							<td colspan="3"><select id="cboBanco" name="cboBanco" class="comboGrande" data-index="10">
							<option value="0">Seleccione&nbsp;una&nbsp;Entidad&nbsp;Bancaria</option>
								<?php
								while ($cont < mysqli_num_rows($res_entidades)) { 
									if ($codentidad == mysqli_result($res_entidades, $cont, "codentidad")) { ?>
								<option value="<?php echo mysqli_result($res_entidades, $cont, "codentidad")?>" selected="selected"><?php echo mysqli_result($res_entidades, $cont, "nombreentidad");?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_entidades, $cont, "codentidad")?>"><?php echo mysqli_result($res_entidades, $cont, "nombreentidad");?></option>
								<?php } $cont++;
								} ?>
								</select></td>					  	
					  		<td >Nº&nbsp;Serie</td>
						   <td><input id="anumeroserie" type="text" class="cajaPequena2" name="anumeroserie" maxlength="30" data-index="11"></td>
							<td >Nº&nbsp;documenro</td>
						   <td><input id="anumero" type="text" class="cajaMedia" name="anumero" maxlength="30" data-index="12"></td>
						</tr></table></tr><tr><td>
						<table class="fuente8" cellspacing=1 cellpadding=1 border=0 >
						<tr>
							<td>Importe Doc.</td>
						    <td><input id="Rimportedoc" type="text" class="cajaPequena" name="Rimportedoc" maxlength="12" data-index="13" onblur="cambio();"> </td>
							<td>Total</td>
						    <td><input id="total" type="text" class="cajaPequena" name="total" maxlength="12" readonly disabled> </td>
							<td>Observaciones</td>
						    <td colspan="3"><textarea rows="2" cols="40" class="areaTexto" name="observaciones" id="observaciones" data-index="14"></textarea></td>
						</tr>							    
					</table></td></tr></table>
				  </form>
					</td>
					<td rowspan="2">
					<div>
						<button class="boletin" onClick="javascript:validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Aceptar</button>
			  </div>
				</td></table>
				</div>
				<!-- Detalles del recibo -->
				<br style="line-height:5px">
				<div id="frmBusqueda">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				<tr><td valign="top">
				
				<form id="formulario_facturas_sel" name="formulario_facturas_sel" method="post" action="frame_facturas_sel.php" target="frame_facturas_sel">
				<input id="codfactura" name="codfactura" value="" type="hidden">
				<input id="cantidad" name="cantidad" value="0" type="hidden">
				<input name="acodrecibo" id="acodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
				<input id="modiffactu" name="modiffactu" value="0" type="hidden">				    

				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300">
				<tr>
				<td width="100%" >
					<iframe width="400" height="100" id="frame_facturas_sel" name="frame_facturas_sel" frameborder="0">
						<ilayer width="400" height="100" id="frame_facturas_sel" name="frame_facturas_sel"></ilayer>
					</iframe>					
				</td>
				</tr>
				<tr><td>
				<label><input type="checkbox" style=" margin-top: 2px;" checked="checked" disabled>Selecione para indicar pago parcial
				<span></span></label>				
				</td></tr>
				</table>
				</form>
				</td>
				<td>
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%" height="160" >
				<tr>
				<td width="100%" valign="top" >
					<iframe width="550" height="160" id="frame_pago" name="frame_pago" frameborder="0">
						<ilayer width="550" height="160" id="frame_pago" name="frame_pago"></ilayer>
					</iframe>					
				</td>
				</tr>
				</table>				
				</td>
				</tr></table>
				</div>
				<br style="line-height:5px">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
					<tr>
						<td align="right">Total a pagar&nbsp;<input type="text" id="apagar" value="0" name="apagar" class="cajaPequena" readonly></td>
						<td align="right">Total recibo&nbsp;<input type="text" id="totalrecibo" value="0" name="totalrecibo" class="cajaPequena" readonly></td>
						<td align="right">Saldo&nbsp;<input type="text" id="saldo" name="saldo" value="0" class="cajaPequena" readonly></td>
						<td>
				<br style="line-height:5px">
					<div align="center">
						<button class="boletin" onClick="validar_cabecera();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();cancelar('<?php echo $codrecibo;?>','<?php echo $codcliente;?>');" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
				</tr> 				
				</table>			  
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  
			 </div>
		  </div>
		</div>
	
			
	</body>
</html>
