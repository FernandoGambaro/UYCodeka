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
         /*echo "<script>parent.changeURL('../../index.php' ); </script>";*/
	 header("Location:../index.php");
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
$codfeedback=@$_GET['codfeedback'];
$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codfeedback=$array_cadena_busqueda[1];
	$descripcion=$array_cadena_busqueda[2];
} else {
	$codfeedback="";
	$descripcion="";
}

?>
<html>
	<head>
		<title>Sectores de la Empresa</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto"; 
		  var h = alertSize();
		  var new_h = (h-65);
		  iframeEl.style.height = new_h + "px";
		  }
		}

		function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }
		
		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}
</script>
	
<script type="text/javascript">

function OpenLeft(noteId){
window.parent.OpenLeft(noteId);
}
function OpenRight(noteId){
window.parent.OpenRight(noteId);
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
		
		function inicio() {
			document.getElementById("firstdisab").style.display = 'block';
			document.getElementById("prevdisab").style.display = 'block';
			document.getElementById("last").style.display = 'block';
			document.getElementById("next").style.display = 'block';
			document.getElementById("form_busqueda").submit();			
		}
		function nuevo_registro() {
			var codformulario=$("#codformulario").val();
			window.parent.OpenRight('nuevo_feedback.php?codformulario='+codformulario);
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}		
		function imprimir() {
			var codformulario=document.getElementById("codformulario").value;
			var descripcion=document.getElementById("descripcion").value;
			window.open("../fpdf/sectores.php?codformulario="+codformulario+"&descripcion="+descripcion);
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
		
		function hacer_cadena_busqueda() {
			var codfeedback=document.getElementById("codformulario").value;
			var cadena="";
			cadena="~"+codfeedback+"~";
			return cadena;
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


		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("codformulario").value='';
			document.getElementById("form_busqueda").submit();
		}					
		</script>
	</head>
	<body onLoad="inicio();setIframeHeight('frame_rejilla');" onresize="setIframeHeight('frame_rejilla');">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla_right.php" target="frame_rejilla">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0><tr>

							<input id="codformulario" type="hidden" class="cajaPequena" name="codformulario" maxlength="3" value="<?php echo $codfeedback;?>">
						<tr>
							<td>Descripción</td>
							<td>
		<?php

					   $query_familias="SELECT * FROM formularios WHERE borrado=0 ORDER BY descripcion ASC";
						$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);
						$contador=0;
						?>
						<select id="tipoformulario" name="tipoformulario" class="comboGrande" data-index="2"  onchange="document.getElementById('codformulario').value =this.value;">
						<option value="">Seleccione un formulario</option>
								<?php 
								while ($contador < mysqli_num_rows($res_familias)) { ?> 
								<option value="<?php echo mysqli_result($res_familias, $contador, "codformulario")?>"><?php echo mysqli_result($res_familias, $contador, "descripcion")?></option>
								<?php $contador++;
								} ?>				
								</select>							
							
</td>
						</tr>
					<tr><td colspan="2" align="center">
						<?php 
						$escribir=verificopermisos('formularios', 'escribir', $UserID);
						$reportes=verificopermisos('formularios', 'leer', $UserID);
						if ($escribir=="true") { ?>						
						<button class="boletin" onClick="nuevo_registro();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;registro</button>
						<?php } ?>						
					<button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button>
					<button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button></td>
						</tr><tr>
					
			  </tr>			
			</table>
					
			  </div>

				
<div id="lineaResultado">
			  <table class="fuente8" width="100%" cellspacing="0" cellpadding="3" border="0" >
			  	<tr>
				<td width="30%" align="left">Cantidad<input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td width="50" align="center">
			 	
				</td>
				<td width="20%" align="right">
				
				<table class="fuente8" cellspacing="1" cellpadding="1" border="0">
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
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();" class="comboPequeno">
		          </select></td>
		          
</table>	</td>
			  </table>
</div>				
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">
				<input type="hidden" id="stylesel" name="stylesel">
			</form>
				<div id="lineaResultado">
					<iframe width="100%" height="430" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="100%" height="430" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
