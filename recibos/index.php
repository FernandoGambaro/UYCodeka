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

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 


$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$codrecibo=$array_cadena_busqueda[3];
	$fechainicio=$array_cadena_busqueda[4];
	$fechafin=$array_cadena_busqueda[5];
	$moneda=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$codrecibo="";
	$fechainicio="";
	$fechafin="";
	$moneda="";
}
				$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
			   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			$moneda1=mysqli_result($res_resultado,0, "descripcion")." ".mysqli_result($res_resultado,0, "simbolo");
			$moneda2=mysqli_result($res_resultado,1, "descripcion")." ".mysqli_result($res_resultado,1, "simbolo");

?>
<html>
	<head>
		<title>Recibos</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		

		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
<!--		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
		<script src="../js3/jquery.colorbox.js"></script>
				

		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
		
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Recibos ";

var tst='';
var alto=parent.document.getElementById("alto").value-190;

var totales=alto/22;
	 totales=totales-1;


$(document).ready(function(){
var body = document.body,
    html = document.documentElement;
var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );	
var width = Math.max( body.scrollWidth, body.offsetWidth, 
                       html.clientWidth, html.scrollWidth, html.offsetWidth );
    width=width-10;
    height=height-158;

    document.getElementById("frame_rejilla").height = (height) + "px";
    document.getElementById("frame_rejilla").width = (width) + "px";

$("form:not(.filter) :input:visible:enabled:first").focus();
 	
});

</script>
<script>
var miVariable = totales;
document.cookie ='variable='+miVariable+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
</script>

<?php
$miVariable =  @$_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>
<script type="text/javascript">


var scrol=false;
function OpenNote(noteId,w,h, scrol){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
			escKey: false,
    		overlayClose: false	,
    		onCleanup:function(){ document.getElementById("form_busqueda").submit(); }	
	});
          $('#cboxClose').remove();
	
}

function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",scrolling: false,
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo(pref,nombre,nif) {

	$("#codcliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();

}
function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = alto + "px";
    ifrm.style.visibility = 'visible';
}

function callGpsDiag(xx,numDoc){
	$('idOfDomElement').colorbox.close();
	parent.callGpsDiag(xx,numDoc);
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
		
	
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
		
		function nuevo_recibo() {

			OpenNote("nuevo_recibo.php",'99%','99%');

		}
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}

		function firstpage() {
			document.getElementById("iniciopagina").value=document.getElementById("firstpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function prevpage() {
			document.getElementById("iniciopagina").value=document.getElementById("prevpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function nextpage() {
			document.getElementById("iniciopagina").value=document.getElementById("nextpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		function lastpage() {
			document.getElementById("iniciopagina").value=document.getElementById("lastpagina").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codcliente=document.getElementById("codcliente").value;
			var nombre=document.getElementById("nombre").value;
			var codrecibo=document.getElementById("codrecibo").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var moneda=document.getElementById("moneda").value;
			var cadena="";
			cadena="~"+codcliente+"~"+nombre+"~"+codrecibo+"~"+fechainicio+"~"+fechafin+"~"+moneda+"~";
			return cadena;
			}

		function limpiar() {
			document.getElementById("form_busqueda").reset();
			$('#codcliente').val('');
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}	

		function imprimir(event) {
			//event.preventDefault();
			var url ='';
			var opcionesimpresion=$('#opcionesimpresion').val();
			var codcliente=document.getElementById("codcliente").value;
			var codrecibo=document.getElementById("codrecibo").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;			
			var moneda=document.getElementById("moneda").value;

			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesimpresion==1) {
					url = "../excel/ResumenDeCuentaExcel.php?codcliente="+codcliente+"&codrecibo="+codrecibo+"&fechainicio="+fechainicio+"&fechafin="+fechafin+"&moneda="+moneda+"&anterior=N";
				} 
				if (opcionesimpresion==2) {
					url = "../excel/ResumenDeCuentaExcel.php?codcliente="+codcliente+"&codrecibo="+codrecibo+"&fechainicio="+fechainicio+"&fechafin="+fechafin+"&moneda="+moneda+"&anterior=S";
				} 				
					$.msgBox({ type: "prompt",
					 title: "Ingrese el nombre del archivo sin extención",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
											$(values).each(function (index, input) {
											v =  input.value ;
					  						});	
							if (v!="") {
								$.get("../excel/preparo.php?file="+v,function (data,status) { });
						      window.parent.progressExcelBar(1,v);
									$.get(url+"&file="+v, function(data, status) {
									if(status == 'success'){	
									$('#downloadFrame').remove(); 
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
										return false;
							  		}else{					
									showWarningToast('Se produjo un error, intentelo mas tarde');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}					  														    	
						}
					});	
			} else {
				if (opcionesimpresion==1) {
					window.open("../fpdf/articulos.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}		
			}
		}
			
		function abreVentana(){
			$.colorbox({
	   	href: "ver_clientes.php", open:true,
			iframe:true, width:"580", height:"450",scrolling: false,
			});			
		}
		
		function validarcliente(){
			var codigo=document.getElementById("codcliente").value;
			$.colorbox({
	   	href: "comprobarcliente.php?codcliente="+codigo, open:true,
			iframe:true, width:"230", height:"200",scrolling: false,
			});
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
		var agencia=thisValue.split("~")[3];

		$("#codcliente").val(pref);
		$("#nombre").val(nombre);
		 document.getElementById('form_busqueda').submit();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>		
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0><tr><td valign="top" width="50%">					
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=1 border=0>
						<tr>
							<td valign="top" rowspan="3">
						<fieldset><legend>&nbsp;Pendiente de cobro&nbsp;</legend>
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr><td><?php echo $moneda1;?></td><td><div id="TotalPesos"></div></td></tr>
						<tr><td><?php echo $moneda2;?></td><td><div id="TotalDolar"></div></td></tr></table>
						</fieldset></td>					
							<td>Cliente</td>
							<td colspan="4"><input id="codcliente" type="hidden" class="cajaPequena" name="codcliente">
							<input id="nombre" name="nombre" type="text" class="cajaGrande" maxlength="45" value="<?php echo $nombre;?>"></td>
						</tr>
						<tr>
						
						<td>Nº Recibo</td><td valign="top" ><input id="codrecibo" name="codrecibo" type="text" class="cajaPequena" maxlength="20"></td>
						
<td colspan="2">Moneda
		 					<select  name="moneda" id="moneda" class="comboPequeno" data-index="4">
		 					<option value="">Seleccione uno</option>
<?php
						$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
							$xmon=1;
						 while ($contador < mysqli_num_rows($res_resultado)) { 
						 			if ($moneda==$xmon) {
						 				?>
						 				<option value="<?php echo $xmon;?>" selected="selected"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
						 			} else {
						 				?>
						 				<option value="<?php echo $xmon;?>"><?php echo mysqli_result($res_resultado, $contador, "simbolo");?></option>
 										<?php
									}
									$xmon++;						 
						 $contador++;
						 }						
						?>
						</select>
						<td>
						</tr>
						<tr><td>Fecha de inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" name="fechainicio" maxlength="10" value="<?php echo $fechainicio?>">
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario"  style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechainicio",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						  <td>Fecha de fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" name="fechafin" maxlength="10" value="<?php echo $fechafin?>">
						  <img src="../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image11",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						</tr><tr>
					<td valign="top">
			 	<div>
			 	
	<?php 
		$escribir=verificopermisos('tesoreria', 'escribir', $UserID);
	if ($escribir=="true") { ?>			 	
	<!-- iconos para los botones -->       
<button class="boletin" onClick="nuevo_recibo();"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;recibo</button>
	<?php } 	?>	
					
				</div>					
					</td>	
						</table></td>
						<td valign="top" width="50%">
						<table class="fuente8" cellspacing=1 cellpadding=2 border=0>					    
						<tr>
						<td rowspan="3" valign="top">
						<fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
						<select name="opcionesimpresion" id="opcionesimpresion" class="comboGrande" >
						<option value="1">Resumen de cuenta sin saldo anterior</option>
						<option value="2">Resumen de cuenta con saldo anterior</option>
						<option value="3">Listado de recibos</option>
						</select>
						<br>&nbsp;<br>
						<label>Exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>						
						</fieldset>
						</td>
						  <td>
							<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
						</td>
					  </tr><tr>
						<td>
			 			<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
						</td>
					  </tr>
					  <tr>
						<td>
						<?php
						$reportes=verificopermisos('reportes', 'leer', $UserID);		
						if ($reportes=="true") { ?>
						<button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<?php } ?>						
						</td>						  </tr>
					</table></td></tr></table>
			  </div>

		<div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0 style="max-width: 800px;">
			  	<tr>
				<td width="30%" align="left">Cantidad <input id="filas" type="text" class="cajaPequena" name="filas" maxlength="5" readonly></td>
				<td width="50%" align="center">

		
				</td>
			<td width="20%" align="right">
		<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
		<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
		</td><td>
		<input id="currentpage" type="text" class="cajaMinima" >
		</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">
		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
		<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
		<td>Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno">
		          </select></td> 
		</table>	</td>										
				</table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
			</form>
					<iframe width="98%" height="300" id="frame_rejilla" name="frame_rejilla" frameborder="0" style="max-width: 800px;">
						<ilayer width="98%" height="300" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
				<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>

					
					
				</div>
		  </div>			
		</div>
	</body>
</html>
