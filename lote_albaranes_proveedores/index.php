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

$cadena_busqueda=$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codproveedor=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numalbaranini=$array_cadena_busqueda[3];
	$numalbaranfin=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codproveedor="";
	$nombre="";
	$numalbaranini="";
	$numalbaranfin="";
	$fechainicio="";
	$fechafin="";
}

?>
<html>
	<head>
		<title>Albaranes</title>
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

<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"98%", height:"98%",
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
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function activartodos() {
			if (document.formulario.todos.checked==true) {
				for (i=0;i<frames['frame_rejilla'].document.form1.elements.length;i++)
				  if(frames['frame_rejilla'].document.form1.elements[i].type == "checkbox")
					 frames['frame_rejilla'].document.form1.elements[i].checked=1
			} else {
				for (i=0;i<frames['frame_rejilla'].document.form1.elements.length;i++)
				  if(frames['frame_rejilla'].document.form1.elements[i].type == "checkbox")
					 frames['frame_rejilla'].document.form1.elements[i].checked=0
			}
		}
		
		function devolver_cadena_checks(frame,check) {
			cadena="";
			existe=false;
			contador_check=0;
			opciones=0;
		
			for (i=0;i<eval("frames['"+frame+"'].document.form1.elements.length");i++) {
				if (eval("frames['"+frame+"'].document.form1.elements[i].name=='"+check+"'")) {
					contador_check=contador_check+1;
					existe=true;				
				}				
			}
		
			if (existe) {
				if (contador_check==1) {
				//sólo hay un check, o sea, que no se forma un array con los checks y hay que 
				//evaluarlo independientemente
					if (eval("frames['"+frame+"'].document.getElementById('"+check+"').checked")) {
						cadena=eval("frames['"+frame+"'].document.getElementById('"+check+"').value+'~'");
						opciones=1;
					}
				} else {		
		
					for (i=0;i<eval("frames['"+frame+"'].document.form1.elements.length");i++) { 
						if (eval("(frames['"+frame+"'].document.form1.elements[i].checked)") && eval("(frames['"+frame+"'].document.form1.elements[i].name=='"+check+"')")) {
							cadena=cadena+eval("frames['"+frame+"'].document.form1.elements[i].value+'~'");
							opciones=opciones+1;
						}
					} 
				}
			}
							
			if (cadena=="") {
				return "";
			} else {
				cadena="~"+cadena;
				return cadena;
			}		
		}
		
		function abreVentana(){
			miPopup = window.open("ver_proveedores.php","miwin","width=700,height=380,scrollbars=yes");
			miPopup.focus();
		}
		
		function validarproveedor(){
			var codigo=document.getElementById("codproveedor").value;
			miPopup = window.open("comprobarproveedor.php?codproveedor="+codigo,"frame_datos","width=700,height=80,scrollbars=yes");
		}
		
		function facturar_albaran() {
			var cadena_elegidos="";
			var codproveedor=document.getElementById("codproveedor").value;
			cadena_elegidos=devolver_cadena_checks("frame_rejilla","checkbox_socio");
			if (opciones==0)  {
				alert("No hay albaranes seleccionados.");
			} else {
				if (confirm("Va a facturar "+opciones+" albaranes. Desea continuar?")) {
					window.location.href="configurar_lote.php?cadena_busqueda="+cadena_busqueda+"&cadena_elegidos="+cadena_elegidos+"&codproveedor="+codproveedor;
					}
			}
		}
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}	
				
		function buscar() {
			var cadena;
			var nombre=document.getElementById("nombre").value;
			if (nombre=="") {
				alert ("Debe seleccionar un proveedor.");
			} else {
				cadena=hacer_cadena_busqueda();
				document.getElementById("cadena_busqueda").value=cadena;
				if (document.getElementById("iniciopagina").value=="") {
					document.getElementById("iniciopagina").value=1;
				} else {
					document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
				}
				document.getElementById("formulario").submit();
			}
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
			var codproveedor=document.getElementById("codproveedor").value;
			var nombre=document.getElementById("nombre").value;
			var numalbaranini=document.getElementById("numalbaranini").value;			
			var numalbaranfin=document.getElementById("numalbaranfin").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var cadena="";
			cadena="~"+codproveedor+"~"+nombre+"~"+numalbaranini+"~"+numalbaranfin+"~"+fechainicio+"~"+fechafin+"~";
			return cadena;
			}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
		</script>
	</head>
	<body onload="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Facturar ALBARANES </div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					
						<tr><td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					
						<tr>
							<td width="16%">Codigo de proveedor </td>
							<td width="68%"><input id="codproveedor" type="text" class="cajaPequena" NAME="codproveedor" maxlength="10">
							<img src="../img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar proveedor" style="vertical-align: middle; margin-top: -1px;"> 
							<img src="../img/cliente.png" width="16" height="16" onClick="validarproveedor()" title="Validar proveedor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="6%" align="right"></td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td><input id="nombre" name="nombre" type="text" class="cajaGrande" maxlength="45" readonly="yes"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>RUT</td>
							<td><input id="nif" name="nif" type="text" class="cajaMedia" maxlength="20" readonly="yes"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						  <td>Fecha de inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<?php echo $fechainicio?>" readonly>
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechainicio",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script>	</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>						
						</td></table>
						<td valign="top">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					
						<tr>
						  <td>Num. Albaran Inicial</td>
						  <td><input id="numalbaranini" type="text" class="cajaPequena" NAME="numalbaranini" maxlength="15" value="<?php echo $numalbaranini?>"></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Num. Albaran Final</td>
						  <td><input id="numalbaranfin" type="text" class="cajaPequena" NAME="numalbaranfin" maxlength="15" value="<?php echo $numalbaranfin?>"></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
						<tr>
						  <td>Fecha de fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<?php echo $fechafin?>" readonly>
						  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechafin",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table></td></tr>
					</table>
			  </div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>			  
			  
<div id="lineaResultado">
			  <table class="fuente8" width="90%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Nº de orden de compra encontradas <input id="filas" type="text" class="cajaPequena" name="filas" maxlength="5" readonly></td>
				<td width="50" align="center">
			 	<div>
				<img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar();" onMouseOver="style.cursor=cursor">
				<img id="botonBusqueda" src="../img/botonfacturaralbaranes.jpg" width="131" height="22" border="1" onClick="facturar_albaran();" onMouseOver="style.cursor=cursor">				
				</div>
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
<td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>
		          
</table>	</td>
			  </table>
</div>			  
				<div id="lineaResultado">
					<iframe width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="98%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
				</div>
				<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
				</div>
		  </div>			
		</div>
	</body>
</html>
