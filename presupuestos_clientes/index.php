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
include("../common/funcionesvarias.php");

//header('Content-Type: text/html; charset=UTF-8'); 

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numpresupuesto=$array_cadena_busqueda[3];
	$cboEstados=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$numpresupuesto="";
	$cboEstados="";
	$fechainicio="";
	$fechafin="";
}

?>
<html>
	<head>
		<title>Facturas</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Presupuestos clientes ";


$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();
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


		$(".callbacks").colorbox({
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
		});

});

jQuery(function($){
   $("#fechafin").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});
   $("#fechainicio").mask("99/99/9999",{placeholder:"dd/mm/yyyy"});

});

</script>
<script type="text/javascript">
var scrol=false;
function OpenNote(noteId,w,h, scrol){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: scrol,
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

function pon_prefijo(pref,nombre,nif) {
	$("#codcliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
	document.getElementById("form_busqueda").submit();
}

</script>	

		<script language="javascript">

		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}

		function nuevo_presupuesto() {
			$.colorbox({href:"nuevo_presupuesto.php",
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ window.location.reload();	}
			});	
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
			var numpresupuesto=document.getElementById("numpresupuesto").value;
			var cboEstados=document.getElementById("cboEstados").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var cadena="";
			cadena="~"+codcliente+"~"+nombre+"~"+numpresupuesto+"~"+cboEstados+"~"+fechainicio+"~"+fechafin+"~";
			return cadena;
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
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}

		function abreVentana(){
			miPopup = window.open("ventana_clientes.php","miwin","width=700,height=380,scrollbars=yes");
			miPopup.focus();
		}

		function validarcliente(){
			var codigo=document.getElementById("codcliente").value;
			$.colorbox({
	   	href: "comprobarcliente_ini.php?codcliente="+codigo, open:true,
			iframe:true, width:"100", height:"100"
			});
		}
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr>
					<td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr><td>
	<?php 
		$escribir=verificopermisos('presupuestos', 'escribir', $UserID);
	if ($escribir=="true") { ?>	
	<!-- iconos para los botones -->       
<button class="boletin" onClick="nuevo_presupuesto();"><i class="fa fa-file-o" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;presupuesto</button>
		 	
	<?php } 	?>	
					
				</div></td></tr><tr><td><button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button></td></tr>
				<tr><td>						
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
					</td>
					</tr>
					</table>
					</td>
					<td valign="top" >
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="16%">Codigo&nbsp;de&nbsp;cliente </td>
							<td width="68%"><input id="codcliente" type="text" class="cajaPequena" NAME="codcliente" maxlength="10" value="<?php echo $codcliente?>">
							 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="OpenNote('ventana_clientes.php',580,450);" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
							 <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="6%" align="right"></td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td><input id="nombre" name="nombre" type="text" class="cajaGrande" maxlength="45" value="<?php echo $nombre?>"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						  <td>Nº&nbsp;presupuesto</td>
						  <td><input id="numpresupuesto" type="text" class="cajaPequena" NAME="numpresupuesto" maxlength="15" value="<?php echo $numpresupuesto?>"></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table>					

					  </td><td valign="top" >
					  <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td>Estado</td>
							<td><select id="cboEstados" name="cboEstados" class="comboMedio">
								<option value="0" selected>Todos los estados</option>
								<option value="1">Pendiente</option>
								<option value="2">Aceptado</option>
								</select></td>
					    </tr>
					  <tr>
						  <td>Fecha de inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<?php echo $fechainicio?>" readonly>&nbsp;
						  <img src="../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechainicio",
						     trigger    : "Image1",
						     align		 : "Bl",     
						     onSelect   : function() { this.hide() },
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						</script>
							</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
						<tr>
						  <td>Fecha de fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafini" maxlength="10" value="<?php echo $fechafin?>" readonly>&nbsp;
						  <img src="../img/calendario.png" name="Image2" width="16" height="16" border="0" id="Image2" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechafin",
						     trigger    : "Image2",
						     align		 : "Bl",     
						     onSelect   : function() { this.hide() },
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						</script>						  
						</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table></td><td valign="top" >
					  <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr><td>
					  Sector/Sección
						<select name="sector[]" class="comboGrande ComboBox" id="newItemMCombo" style="width: 250px; height:25px" >
						<option value=''>Seleccione una opción</option>
						<?php
							$sel_resultado="SELECT * FROM sector ";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
							$contador=0;
							$marcaestado=0;
							   while ($contador < mysqli_num_rows($res_resultado)) {
								   echo "<option value='". mysqli_result($res_resultado, $contador, "codsector")."' style='background-color:#". mysqli_result($res_resultado, $contador, "color")."; color:
								    ". color_inverse(mysqli_result($res_resultado, $contador, "color")).";'><span style=\"background-color:#". mysqli_result($res_resultado, $contador, "color").";
								     color: ". color_inverse( mysqli_result($res_resultado, $contador, "color")).";\">". mysqli_result($res_resultado, $contador, "descripcion")."</span></option>";
								     $contador++;
								   }
						?>
						</select>					
					</td></tr></table></td></tr>
					</table>
			  </div>
			  
			<div id="lineaResultado">
			 <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0 style="max-width: 800px;">
			  	<tr>
				<td width="30%" align="left">Cantidad  <input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td width="50" align="center">
</td>
				<td width="20%" align="right">
				<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
				<td><input type="hidden" id="firstpagina" name="firstpagina" value="1">
				<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">
				<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td><td>
				<input type="hidden" id="prevpagina" name="prevpagina" value="">
				<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">
				<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13"></td>
				<td><input id="currentpage" type="text" class="cajaMinima" ></td>
				<td><input type="hidden" id="nextpagina" name="nextpagina" value="">
				<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">
				<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
				<td><input type="hidden" id="lastpagina" name="lastpagina" value="">
				<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">
				<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
				<td>Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno"></select></td> 
					</table>	</td>
			  </table>
		</div>			  
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
			</form>
					<iframe width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" style="max-width: 800px;">
						<ilayer width="98%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			</div>
		  </div>
		</div>
	</body>
</html>
