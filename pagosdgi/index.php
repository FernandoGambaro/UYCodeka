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
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include ("../funciones/fechas.php"); 

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8'); 


$cadena_busqueda=@$_GET["cadena_busqueda"];
$fechainicio='';

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$fecha=$array_cadena_busqueda[1];
	$anio=$array_cadena_busqueda[2];
	$mes=$array_cadena_busqueda[3];
} else {
	$fecha="";
	$anio='';
	$mes='';
}

?>
<html>
	<head>
		<title>PAgo DGI</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../funciones/validar.js"></script>
    	
<script src="js/jquery.min.js"></script>

<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">


<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; Pagos DGI ";

var tst='';
var alto=parent.document.getElementById("alto").value-130;

var totales=alto/22;
	 totales=totales-1;


function OpenNote(noteId,w,h){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
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
		
		function imprimir() {
			var fecha=document.getElementById("fechainicio").value;
			var anio=document.getElementById("anio").value;
			var mes=document.getElementById("mes").value;
			window.open("../fpdf/pagodgi.php?fecha="+fecha+"&anio="+anio+"&mes="+mes);
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
			var fecha=document.getElementById("fechainicio").value;
			var anio=document.getElementById("anio").value;
			var mes=document.getElementById("mes").value;
			var cadena="";
			cadena="~"+fecha+"~"+anio+"~"+mes+"~";
			return cadena;
			}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}		
		</script>
	</head>
	<body onLoad="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">
		
					<table class="fuente8" cellspacing="0" cellpadding="3" border="0">					
						<tr><td valign="top">
						<?php 
						$escribir=verificopermisos('proveedores', 'escribir', $UserID);
						$reportes=verificopermisos('reportes', 'leer', $UserID);
						if ($escribir=="true") { ?>						
						<button class="boletin" onClick="OpenNote('nuevo_pagodgi.php',600,300);" onMouseOver="style.cursor=cursor">
						<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nuevo&nbsp;pago</button>
						<?php } ?>						
							</td>						
							<td valign="top">Fecha pago</td>
						  <td valign="top"><input id="fechainicio" type="text" class="cajaPequena" name="fechainicio" maxlength="10" value="<?php echo $fechainicio;?>" readonly>&nbsp;
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
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
							<td valign="top">Año</td>
						    <td valign="top"><select name="anio" id="anio" class="cajaPequena2">
						    <option value="" selected>Seleccione uno</option>
						    <?php 
						    for ($x=2010; $x<=2020; $x++){
						    	if($x==$anio) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo $x;?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo $x;?></option>
								<?php	
								}					    
						    }
						    ?>
						    </select>		
						    </td>
							<td valign="top">Mes&nbsp;<select name="mes" id="mes" class="cajaPequena">
						    <option value="" selected>Seleccione uno</option>
						    <?php 
						    for ($x=1; $x<=12; $x++){
						    	if ($x==$mes) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo $x." - ". genMonth_Text($x);?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo $x." - ". genMonth_Text($x);?></option>
								<?php
								}						    
						    }
						    ?>
						    </select>						   
						   </td>		
						   <td valign="top">
<table>
			<tr><td><button class="boletin" onClick="buscar();" onMouseOver="style.cursor=cursor"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</button></td>
			  							</tr><tr>
						<td><button class="boletin" onClick="limpiar();" onMouseOver="style.cursor=cursor"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button></td>
						</tr><tr>
						<td>
						<?php 
						 if ($reportes=="true") {?>
						 <button class="boletin" onClick="imprimir();" onMouseOver="style.cursor=cursor">
						<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
						<?php } ?>							
						</td>						
			  </tr>			
			</table>						   
						   </td>				    					
						</tr>
					
					</table>
			  </div>
				
		<div id="lineaResultado">
			  <table class="fuente8"  cellspacing="0" cellpadding="2" border="0" style="max-width: 800px;">
			  	<tr>
				<td width="30%" align="left">Cantidad <input id="filas" type="text" class="cajaMinima" name="filas" maxlength="5" readonly></td>
				<td width="50" align="center">

				</td>
				<td width="20%" align="right">
				
				<table class="fuente8" cellspacing="1" cellpadding="1" border="0">
		<td>				
		<input type="hidden" id="firstpagina" name="firstpagina" value="1">
		<img style="display: none;" src="../img/paginar/first.gif" id="first" border="0" height="13" width="13" onClick="firstpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/firstdisab.gif" id="firstdisab" border="0" height="13" width="13"></td><td>
		<input type="hidden" id="prevpagina" name="prevpagina" value="">
		<img style="display: none;" src="../img/paginar/prev.gif" id="prev" border="0" height="13" width="13" onClick="prevpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/prevdisab.gif" id="prevdisab" border="0" height="13" width="13"></td><td>
		<input id="currentpage" type="text" class="cajaMinima" ></td><td>
		<input type="hidden" id="nextpagina" name="nextpagina" value="">
		<img style="display: none;" src="../img/paginar/next.gif" id="next" border="0" height="13" width="13" onClick="nextpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/nextdisab.gif" id="nextdisab" border="0" height="13" width="13"></td><td>
		<input type="hidden" id="lastpagina" name="lastpagina" value="">
		<img style="display: none;" src="../img/paginar/last.gif" id="last" border="0" height="13" width="13" onClick="lastpage();" onMouseOver="style.cursor=cursor">

		<img style="display: none;" src="../img/paginar/lastdisab.gif" id="lastdisab" border="0" height="13" width="13"></td><td>			
				Mostrados</td><td> <select name="paginas" id="paginas" onChange="paginar();">
		          </select></td>
		          
			</table>	</td>
			  </table>
			</div>				
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
				<input type="hidden" id="selid" name="selid">				
			</form>				
				
					<iframe width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" onload="setIframeHeight(this.id)" style="max-width: 800px;">
						<ilayer width="98%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
				
			</div>
		  </div>			
		</div>
	</body>
</html>
