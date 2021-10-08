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
require_once('../../class/class_session.php');
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
		echo "<script>window.top.location.href='../../index.php'; </script>";
	   exit;

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];


include ("../../conectar.php");
include("../../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/Montevideo');
include ("../../funciones/fechas.php");

$cadena_busqueda=isset($_GET["cadena_busqueda"]) ? @$_GET["cadena_busqueda"] : null ;

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numfactura=$array_cadena_busqueda[3];
	$estado=$array_cadena_busqueda[4];
	$fechaini=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$numfactura="";
	$estado="";
	$fechaini =data_first_month_day(date('Y-m-d')); 
	$fechafin = data_last_month_day(date('Y-m-d')); 
}


$status_msg = "";
$USERTIPO=@$_SESSION['USERTIPO'];

	$e=$_GET['e'];
/*
if($USERTIPO==2) {
	*/
	$query="SELECT * FROM clientes WHERE codcliente='$e'";
	/*
}
/* elseif( $UserID!='') {
	$query="SELECT * FROM clientes WHERE codcliente='$UserID'";
	$e=$UserID;
}
 else {
	$query="SELECT * FROM clientes WHERE codcliente='$e'";
if($UserID!='') {
	$e=$UserID;
	}
}
*/

$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);


?>
<html>
	<head>
		<title>Services</title>

		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		
		<script src="../../calendario/jscal2.js"></script>
		<script src="../../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />	
			
		<script src="../../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../../js3/colorbox.css" />
<script src="../../js3/jquery.min.js"></script>
<script src="../../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../../js3/jquery.toastmessage.css" type="text/css">
		<script src="../../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../../js3/message.js" type="text/javascript"></script>
		<script src="../../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"98%", height:"98%",
			onCleanup:function(){ window.location.reload();	}
	});

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

		function buscar() {
			document.getElementById("form_busqueda").submit();
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}
				
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
		
		function nuevo_service() {
			var e=document.getElementById("e").value;
			$.colorbox({href:"nuevo_service.php?e="+e,
			iframe:true, width:"98%", height:"98%",
			onCleanup:function(){ window.location.reload();	}
			});			
		}
		function nuevo_service_horas() {
			var e=document.getElementById("e").value;
			$.colorbox({href:"nuevo_service_horas.php?e="+e,
			iframe:true, width:"98%", height:"98%",
			onCleanup:function(){ window.location.reload();	}
			});			
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
		
		function imprimir() {
			var e=document.getElementById("e").value;
			var factura=document.getElementById("factura").value;
			var fechaini=document.getElementById("fechaini").value;
			var fechafin=document.getElementById("fechafin").value;
			window.open("../../fpdf/services.php?e="+e+"&factura="+factura+"&fechaini="+fechaini+"&fechafin="+fechafin);
		}
			
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Datos Cliente</div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
				
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">Nombre</td>
						    <td colspan="3"><input NAME="Anombre" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "nombre");?>"></td>
				        </tr>
						<tr>
							<td width="15%">Apellido</td>
						    <td colspan="3"><input NAME="aapellido" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "apellido");?>"></td>
				        </tr>
						<tr>
						  <td>RUT</td>
						  <td ><input id="nif" type="text" autocomplete="off" class="cajaPequena" NAME="anif" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "nif");?>"></td>
							<td>Tipo</td>
							<td><SELECT type=text size=1 name="Ttipo" id="tipo" class="comboMedio">
							<?php
								$tipo = array("Seleccione uno", "Cliente","MCC");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, "tipo")){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>

						
					</table></td>
					
					        <td rowspan="14" align="left" valign="top">

					        </td>
					
					<td>						
						<table class="fuente8" width="30%" cellspacing=0 cellpadding=3 border=0>	
						<tr>
							<td>Tel&eacute;fono</td>
							<td><input id="telefono" name="atelefono" autocomplete="off" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "telefono")?>"></td>

							<td>M&oacute;vil</td>
							<td width="50%"><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "movil");?>"></td>
					    </tr>
						<tr>
							<td>Correo&nbsp;electr&oacute;nico  </td>
							<td colspan="3"><input NAME="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "email");?>"></td>
					    </tr>

						<tr>
							<td>Abonado/Service</td>
							<td colspan="3"><select type=text size=1 name="service" id="service" class="comboMedio">
							<?php
								$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, 'service')){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>
					</table>
					</td>
					<td>
						<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>	
						<td>Fecha&nbsp;de&nbsp;inicio</td>
						  <td><input id="fechaini" type="text" class="cajaPequena" NAME="fechaini" maxlength="10" value="<?php echo implota($fechaini);?>">
						  <img src="../../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">					  
						  </td>
						  
						  </tr><tr>

						<td>Fecha&nbsp;de&nbsp;fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<?php echo implota($fechafin);?>">
						  <img src="../../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
					  </td>
					  </tr><tr>
					  
					  <td width="100px">&nbsp;Sin facturar</td>
						<td>
						<input type="hidden" name="factura" id="factura" value="1">
						<?php
						 if ( $estado=='' or $estado==0) {
						?>
						<input type="checkbox" name="factura" value="0" checked> 
						<?php } else {
						?>
						<input type="checkbox" name="factura" value="0"> 
						<?php }
						?>
						
						</td>
					  </tr>
					  	</table>			
					</td>
					
					</tr></table>
					</div>
		  <div id="lineaResultado">
			  <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="30%" align="left">Nº de encontrados <input id="filas" type="text" class="cajaMinima" NAME="filas" maxlength="5" readonly></td>
				<td align="center"><div>
				
	<?php 
		$leer=verificopermisos('servicios', 'leer', $UserID);
		$escribir=verificopermisos('servicios', 'escribir', $UserID);
		$modificar=verificopermisos('servicios', 'modificar', $UserID);
		$eliminar=verificopermisos('servicios', 'eliminar', $UserID);

	if ( $leer=="true" or $escribir=="true" or $modificar=="true" ) { ?>				
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
					<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
					<button class="boletin" onClick="nuevo_service();" onMouseOver="style.cursor=cursor"><i class="fa fa-wrench" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;Service</button>
					<button class="boletin" onClick="nuevo_service_horas();" onMouseOver="style.cursor=cursor"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;N. Servie Horas</button>
				<?php  
				} ?>
				<button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
					</div>
</td>
				<td width="20%" align="right">
				<table class="fuente8" cellspacing=1 cellpadding=1 border=0>
<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage()" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage()" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13">
</td><td>
<input id="currentpage" type="text" class="cajaMinima" >
</td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage()" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td>
<td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage()" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td>
<td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar()">
		          </select></td>
		          
</table></td>
			  </table>
				</div>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="e" name="e" value="<?php echo $e;?>">
			</form>
					<iframe width="95%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="no">
						<ilayer width="95%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
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
      cal.manageFields("Image1", "fechaini", "%d/%m/%Y");
      cal.manageFields("Image11", "fechafin", "%d/%m/%Y");
//]]></script>			
	</body>
</html>
