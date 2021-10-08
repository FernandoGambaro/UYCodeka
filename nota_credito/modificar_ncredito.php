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
 
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$baseimponible='';
$tipocambio='';
$modif='';

$codncreditotmp=$codncredito=$_GET["codncredito"];
$sel_alb="SELECT * FROM ncredito WHERE codncredito='$codncredito'";
$rs_alb=mysqli_query($GLOBALS["___mysqli_ston"], $sel_alb);
	$tipo=mysqli_result($rs_alb, 0, "tipo");
	$fecha=mysqli_result($rs_alb, 0, "fecha");
	$iva=mysqli_result($rs_alb, 0, "iva");
	$codcliente=mysqli_result($rs_alb, 0, "codcliente");
	$moneda=mysqli_result($rs_alb, 0, "moneda");
	$tipocambio=mysqli_result($rs_alb, 0, "tipocambio");
	$observacion=mysqli_result($rs_alb, 0, "observacion");

$sel_cliente="SELECT nombre,apellido,empresa,nif,agencia FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
	$nombre=mysqli_result($rs_cliente, 0, "nombre");
	$nombre=mysqli_result($rs_cliente, 0, "nombre")." ".mysqli_result($rs_cliente, 0, "apellido")." - ".mysqli_result($rs_cliente, 0, "empresa");
	$agencia=mysqli_result($rs_cliente, 0, "agencia");
	$nif=mysqli_result($rs_cliente, 0, "nif");

$fechahoy=date("Y-m-d");
/*
$sel_albaran="INSERT INTO ncreditostmp (codncredito,fecha) VALUE ('','$fechahoy')";
$rs_albaran=mysql_query($sel_albaran);
$codncreditotmp=mysql_insert_id();
*/
$sel_lineas="SELECT * FROM ncreditolinea WHERE codncredito='$codncredito' ORDER BY numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

$sel_borrar = "DELETE FROM ncreditolineatmp WHERE codncredito='$codncredito'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);


$contador=0;
//echo mysql_num_rows($rs_lineas);
while ($contador < mysqli_num_rows($rs_lineas)) {
	$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
	$codfactura=mysqli_result($rs_lineas, $contador, "codfactura");
	$codigo=mysqli_result($rs_lineas, $contador, "codigo");
	$codservice=mysqli_result($rs_lineas, $contador, "codservice");
	$detallestmp=mysqli_result($rs_lineas, $contador, "detalles");
	$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
	$moneda=mysqli_result($rs_lineas, $contador, "moneda");
	$precio=mysqli_result($rs_lineas, $contador, "precio");
	$importe=mysqli_result($rs_lineas, $contador, "importe");
	$baseimponible=$baseimponible+$importe;

	$sel_tmp="INSERT INTO `ncreditolineatmp` (`codncredito`, `numlinea`, `codfactura`, `codfamilia`, `codigo`, `codservice`, `detalles`, `cantidad`, `moneda`, `precio`, `importe`)
												VALUES ('$codncredito','', '$codfactura', '$codfamilia', '$codigo','$codservice','$detallestmp','$cantidad','$moneda','$precio','$importe')";	 

	$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
	$contador++;
}

$baseimpuestos=$baseimponible*($iva/100);
$preciototal=$baseimponible+$baseimpuestos;
//$preciototal=number_format($preciototal,2);
$shoedetalle=1;
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

function pon_prefijo_b(pref,nombre,nif,agencia) {
	$("#codcliente").val(pref);
			document.formulario_facturas.submit();
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$("#agencia").val(agencia);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,codservice,detalles,comision,cantidad,codfactura) {

	var monArray = new Array();
	monArray[0]=" ";
	monArray[1]="Pesos";
	monArray[2]="U\$S";
	$("#codfamilia").val(codfamilia);
	if (codigobarras!='') {
		$("#codbarras").val(codigobarras);
	} else {
		$("#codbarras").val(referencia);
	}
	$("#articulos").val(referencia);
	$("#cantidad").val(cantidad);
	$("#cantidadaux").val(cantidad);
	$("#codservice").val(codservice);
	$("#detalles").val(detalles);
	$("#codfactura").val(codfactura);
	$("#comision").val(comision);	
	
	$("#descripcion").val(nombre);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	cambio();
	actualizar_importe();
}

function pon_baseimponible(baseimponible) {
	$("#habilitacambio").val('NO');
	$("#baseimponible").val(baseimponible);
	cambio_iva();
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
			if (document.getElementById("habilitacambio").value=='NO') {
				showWarningToast("Para esta nota de crédito no se puede cambiar el cliente:<br>");
			} else {
				
			$.colorbox({
	   	href: "ventana_clientes_ini.php", open:true,
			iframe:true, width:"580", height:"450",
			onCleanup:function() {
				$('#tipo').focus();
				}			
			});
			}			
		}		
		
		function inicio() {
			document.formulario_facturas.submit();

			document.formulario_facturas_linea.submit();
			
			document.getElementById("modif").value=1;
			document.formulario_lineas.submit();
			document.getElementById("modif").value=0;
		}
		
				
		function validarcliente() {
			if (document.getElementById("habilitacambio").value=='NO') {
				showWarningToast("Para esta nota de crédito no se puede cambiar el cliente:<br>");
			} else {
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"350", height:"200",
				
				});
			}
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiarcaja() {
			document.getElementById("nombre").value="";
			document.getElementById("nif").value="";
		}

		function limpiar() {
					document.getElementById("codbarras").value="";
					document.getElementById("articulos").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value="";
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("comision").value=0;
					document.getElementById("importe").value="";
		}		


		function actualizar_importe() {
			var cantidadaux=document.getElementById("cantidadaux").value;
			var cantidad=document.getElementById("cantidad").value;
			if (cantidad<=cantidadaux) {
				/*Si la ncredito es en pesos y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambioncredito=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
				var tipocambioarcticulo=document.getElementById("moneda").value;
				if (tipocambioarcticulo==0) {
					tipocambioarcticulo=tipocambioncredito;
					var monArray = new Array();
					monArray[0]=" ";
					monArray[1]="Pesos";
					monArray[2]="U\$S";
					document.getElementById("monedaShow").value=monArray[tipocambioncredito];
					document.getElementById("moneda").value=tipocambioncredito;

				}
				if (tipocambioncredito==1 && tipocambioarcticulo == 2){
					var precio=document.getElementById("precio").value * parseFloat(document.getElementById("tipocambio").value);
				}
				if (tipocambioncredito==2 && tipocambioarcticulo == 1){
					var precio=document.getElementById("precio").value / parseFloat(document.getElementById("tipocambio").value);
				}
				if ((tipocambioncredito==1 && tipocambioarcticulo == 1) || (tipocambioncredito==2 && tipocambioarcticulo == 2)){
				var precio=document.getElementById("precio").value;
				}
				//var cantidad=document.getElementById("cantidad").value;

				total=precio*cantidad;

				var original=parseFloat(total);
				var result=round(original,2) ;
				
				document.getElementById("importe").value=result;
				} else {
					showWarningToast("La cantidad no puede ser mayor a la facturada:<br>");
				}
			}
			
		function validar_cabecera() {
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("observacion").value=document.getElementById("observacionaux").value;
					document.getElementById("formulario").submit();
				}
			}			
		
		function validar() {
			var cantidadaux=document.getElementById("cantidadaux").value;
			var cantidad=document.getElementById("cantidad").value;
			if (cantidad<=cantidadaux) {
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio<br>"; 
						} else {
							if (isNaN(document.getElementById("precio").value)==true) {
								mensaje+="  - El precio debe ser numérico<br>";
							}
						}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad<br>";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numérica<br>";
							} else {
									document.getElementById("cantidad").value=enteroo;
								}
						}

				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);
					
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value="";
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("articulos").value="";					
				}
			} else {
				showWarningToast("La cantidad no puede ser mayor a la facturada:<br>");
			}				
		}

			
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);

			var result=round(original,2) ;
			document.getElementById("baseimponible").value=result;

			var original=parseFloat(document.getElementById("baseimponible").value);

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
				$.post('busco_tipocambio.php', {fecha : fecha },  function(data){
				$("#tipocambio").val(data);
			});			
	 		
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
			$("#cmoneda").val(Index);
			document.formulario_facturas.submit();
			$("#codfacturatmp").val('');
			
			document.formulario_facturas_linea.submit();

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
		
function agregar_factura(codfactura) {

	document.getElementById("codfacturatmp").value=codfactura;
	document.formulario_facturas_linea.submit();

}		
		</script>

<script type="text/javascript" >
function actualizovencimeinto() {
	var pago = document.getElementById("codformapago").value;
	var d = pago.split("~")[1];
	var fecha=document.getElementById('fecha').value;
	var Fecha = new Date();
	var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
	var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
	var aFecha = sFecha.split(sep);
	var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
	fecha= new Date(fecha);
	fecha.setDate(fecha.getDate()+parseInt(d));
	var anno=fecha.getFullYear();
	var mes= fecha.getMonth()+1;
	var dia= fecha.getDate();
	mes = (mes < 10) ? ("0" + mes) : mes;
	dia = (dia < 10) ? ("0" + dia) : dia;
	var fechaFinal = dia+sep+mes+sep+anno;
	document.getElementById('vencimiento').value=fechaFinal;
}
</script>	


<link href="js/jquery-ui.css" rel="stylesheet">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <style type="text/css">
  
		.fixed-height {
			padding: 1px;
			max-height: 200px;
			overflow: auto;
		}  
/****** jQuery Autocomplete CSS *************/

.ui-corner-all {
  -moz-border-radius: 0;
  -webkit-border-radius: 0;
  border-radius: 0;
}

.ui-menu {
  border: 1px solid lightgray;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
}

.ui-menu .ui-menu-item a {
  color: #000;
}

.ui-menu .ui-menu-item:hover {
  display: block;
  text-decoration: none;
  color: #3D3D3D;
  cursor: pointer;
 /* background-color: lightgray;
  background-image: none;*/
  border: 1px solid lightgray;
}

.ui-widget-content .ui-state-hover,
.ui-widget-content .ui-state-focus {
  border: 1px solid lightgray;
  /*background-image: none;
  background-color: lightgray;*/
  font-weight: bold;
  color: #3D3D3D;
}
  
  </style>
<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span style='color:#5C5C5C;'>" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};
$(document).ready(function () {
 	

    $("#nombre").autocomplete({
        source: 'busco.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];
		var nif=thisValue.split("~")[2];
		var agencia=thisValue.split("~")[2];

		$("#aCliente").val(pref);
		$("#nombre").val(nombre);
		$("#nif").val(nif);
		$("#agencia").val(agencia);
	
		$('[data-index="2"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>	
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR NOTA DE CREDITO</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_ncredito.php">
				<input id="habilitacambio" name="habilitacambio" value="SI" type="hidden">
<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" value="<?php echo $codcliente?>" data-index="1">
					        <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"> 
					        <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td>Nombre</td>
						    <td colspan="5"><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo $nombre?>" readonly></td>
						    <td>RUT</td>
				          <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="<?php echo $nif?>" readonly></td>
						  <td>Nota&nbsp;crédito&nbsp;Nº&nbsp;</td>
						  <td colspan="2"><input name="codncredito" class="cajaPequena" readonly="" value="<?php echo $codncredito;?>" readonly ></input></td>
							<td>Moneda</td><td>
							
						 <select onchange="cambio();" name="Amoneda" id="Amoneda" class="cajaPequena2" data-index="2">
							<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
							if ($moneda==" ")
							{
							echo '<option value="" selected>Selecione uno</option>';
							}
							foreach ($tipofa as $key => $i ) {
							  	if ( $moneda==$key ) {
									echo "<option value=$key selected>$i</option>";
								} else {
									echo "<option value=$key>$i</option>";
								}
		
							}
							?>
						</select>							

							</td>						  				         					        					
							</tr>
				            <input id="tipo" name="atipo" class="cajaPequena" type="hidden" value="2">&nbsp;

						<?php $hoy=date("d/m/Y"); ?>
						<tr>
							<td>Fecha</td><td>
							<input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo implota($fecha)?>"  data-index="3" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { actualizovencimeinto(); this.hide(); },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
						
						</td>
				      <td>IVA</td>
				      <td><input name="iva" type="text" class="cajaPequena" id="iva" size="5" maxlength="5" onChange="cambio_iva()" value="<?php echo $iva;?>"> %</td>
				       <td colspan="4">Tipo&nbsp;Cambio
								<label>$&nbsp;->&nbsp;U$S</label><span>
								<input name="tipocambio" type="text" class="cajaPequena2" id="tipocambio" size="5" maxlength="5" value="<?php echo $tipocambio; ?>" onChange="cambio();"></span>
								<!--<input type="checkbox" name="usartc" style="vertical-align: middle; margin-top: -1px;">Buscar T/C</input>-->
						</td>
						<td colspan="3"><label>Agencia&nbsp;</label><span><input name="agencia" type="text" class="cajaGrande" id="agencia" size="5" maxlength="5" value="<?php echo $agencia; ?>"></span>
						  &nbsp;</td>				         					        					
						</tr>
					</table>	
									
														
			  </div>
			  <input id="codncreditotmp" name="codncreditotmp" value="<?php echo $codncreditotmp;?>" type="hidden">
			  <!--<input id="codncredito" name="codncredito" value="<?php echo $codncredito?>" type="hidden">-->
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo $baseimpuestos;?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo $baseimponible;?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo $preciototal;?>" type="hidden">
			  <input id="observacion" name="observacion" value="<?php echo $observacion;?>" type="hidden">
			  <input id="accion" name="accion" value="modificar" type="hidden">			  
			  </form>
			  <br style="line-height:2px">
			  <div id="frmBusqueda">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="100%">
				  <tr>
				<td valign="top"> 
			  <!-- Sección listado de facturas del cliente -->
			  
				<form id="formulario_facturas" name="formulario_facturas" method="post" action="frame_facturas.php" target="frame_facturas">
				<input id="codcliente" name="cdocliente" value="<?php echo $codcliente;?>" type="hidden">
				<input id="cmoneda" name="cmoneda" value="<?php echo $moneda;?>" type="hidden">
				
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300" >
				  <tr>
					<td>
					<iframe width="300" height="120" id="frame_facturas" name="frame_facturas" frameborder="0">
						<ilayer width="300" height="120" id="frame_facturas" name="frame_facturas"></ilayer>
					</iframe>					
					</td>
				  </tr>
				</table>
				</form>
				</td>				  
				<td valign="top">			  <!-- Sección listado de filas de facturas seleccionada -->
			  
				<form id="formulario_facturas_linea" name="formulario_facturas_linea" method="post" action="frame_facturas_linea.php" target="frame_facturas_linea">
				<input id="codfacturatmp" name="codfacturatmp" value="" type="hidden">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0 width="300" >
				  <tr>
					<td width="100%" >
					<iframe width="800" height="120" id="frame_facturas_linea" name="frame_facturas_linea" frameborder="0">
						<ilayer width="800" height="120" id="frame_facturas_linea" name="frame_facturas_linea"></ilayer>
					</iframe>					
					</td>
				  </tr>
				</table>
				</form>
				</td>
			  </tr>

				</table>
				</div>
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
				<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td >Codigo </td>
					<td valign="middle">
					<input type="hidden" name="codbarras" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					<input type="text" size="26" maxlength="60" value="" id="articulos" autocomplete="off" class="cajaMedia" readonly/>
					&nbsp;Descripcion</td>
					<td  ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="50" readonly></td>
					<td colspan="3">Moneda&nbsp;&nbsp;&nbsp;
					 <input name="monedaShow" type="text" class="cajaPequena2" id="monedaShow" size="10" maxlength="10" readonly>&nbsp;&nbsp;&nbsp;
					 <input name="moneda"  id="moneda" type="hidden" >
					 </td>
					<td colspan="2" valign="top" >Precio&nbsp;<input name="precio" type="text" class="cajaPequena2" id="precio" size="10" maxlength="10" readonly></td>
					<td colspan="2" valign="top">Comisión&nbsp;<input name="comision" type="text" class="cajaMinima" id="comision" size="10" maxlength="10" style="background-color: yellow;"  readonly>&nbsp;%</td>
					 
				  </tr>
				  <tr>
  				  <?php if($shoedetalle==1) { ?>
					<td valign="top">Detalles</td>
					<td colspan="2"><textarea name="detalles" rows="1" cols="100" class="areaTexto" id="detalles" data-index="7" readonly> </textarea></td>
					<?php } ?>
					<td colspan="2" valign="top" >Disponible&nbsp;<input type="text" id="cantidadaux" name="cantidadaux" class="cajaMinima" value="" readonly />
					</td>
					
					<td colspan="2" valign="top" >Cantidad&nbsp;
					<input name="cantidad" type="text" class="cajaMinima" id="cantidad" size="10" maxlength="10" value="" onChange="actualizar_importe();"></td>
					<td colspan="2" valign="top" >Importe&nbsp;<input name="importe" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" readonly></td>
					<td valign="top" align="right"><img id="botonBusqueda" src="../img/botonagregar.jpg" width="72" height="22" border="1" onClick="validar();" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>
					<td valign="top" align="right"><img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="72" height="22" border="1" onClick="limpiar();" onMouseOver="style.cursor=cursor" title="Agregar articulo"></td>

				  </tr>
				</table>	
		    	<input id="codfamilia" name="codfamilia" value="" type="hidden">
		    	<input id="codncreditotmp" name="codncreditotmp" value="<?php echo $codncreditotmp;?>" type="hidden">	
				<input id="modif" name="modif" value="0" type="hidden">
				<input id="preciototal2" name="preciototal" type="hidden">
				<input name="codarticulo" value="" type="hidden" id="codarticulo">
				<input name="codservice" value="" type="hidden" id="codservice">	
				<input id="codfactura" name="codfactura" value="" type="hidden">
											    
				</form>		
				
				
				<div id="frmBusqueda" style="top: -6px;">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0 ID="Table1">

						<tr><td width="100%" colspan="11">
					<iframe width="100%" height="160" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="160" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>
				</td></tr>					
				</table>
				</div>
			  <div id="frmBusqueda" style="top: -6px;">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40" class="areaTexto"><?php echo $observacion;?></textarea>
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
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
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
			  
			  
			
			  </div>
				
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>

			 </div>
		  </div>
		</div>
		<script type="text/javascript">
		cambio();
		</script>	
			
	</body>
</html>
<?php
//mysql_close($descriptor);
?>