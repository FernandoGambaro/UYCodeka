<?php
include ("../conectar.php");
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
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
		 header("Location:../index.php");
	    exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$cadena_busqueda=$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$fechainicio=$array_cadena_busqueda[1];
} else {
	$fechainicio="";
}

$hoy=date("d/m/Y");

?>
<html>
	<head>
		<title>Cobros</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />
		
	<script language="javascript">
		parent.document.getElementById("msganio").innerHTML=" Cerrar caja ";

		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function inicio() {
			document.getElementById("formulario").submit();
		}
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			document.getElementById("formulario").submit();
		}
		
		function hacer_cadena_busqueda() {			
			var fechainicio=document.getElementById("fechainicio").value;
			var cadena="";
			cadena="~"+fechainicio+"~";
			return cadena;
			}
			
		function printFrame(id) {
			var frm = document.getElementById(id).contentWindow;
			frm.focus();// focus on contentWindow is needed on some ie versions
			frm.print();
		}			
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Buscar FECHA</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="rejilla.php" target="frame_rejilla">
					<table class="fuente8"  cellspacing=0 cellpadding=3 border=0>					
					  <tr>
						  <td>Fecha de cierre</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<?php echo $hoy?>" readonly><img src="../img/calendario.png"
						   name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
						</td>
						</tr><tr>
					  <td colspan="2" align="center">
					  <div id="botonBusqueda"><img src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar();" onMouseOver="style.cursor=cursor"></div>
			 			<div id="botonBusqueda"> <img src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick='printFrame("frame_rejilla");' onMouseOver="style.cursor=cursor"></div>
					  </td>
				  </tr>
					</table>
				</div>
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<iframe width="98%" height="430" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="98%" height="430" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>					
		
		  </div>			
		</div>
		</div>
		
 <script type="text/javascript">//<![CDATA[
     var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide(), actualizar(); },
          showTime: true
      });
      cal.manageFields("Image1", "fechainicio", "%d/%m/%Y");
      cal.manageFields("Image2", "fechafin", "%d/%m/%Y");
//]]></script>			
	</body>
</html>
