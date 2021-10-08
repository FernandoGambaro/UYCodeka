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
include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set("America/Montevideo"); 

$cadena_busqueda=@$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=explode("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numfactura=$array_cadena_busqueda[3];
	$cboEstados=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$numfactura="";
	$cboEstados="";
	$fechainicio =data_first_month_day(date('Y-m-d')); 
	$fechafin = data_last_month_day(date('Y-m-d')); 
}


?>
<html>
	<head>
		<title>Reporte</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../common/timeout.js"></script>

		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		
		<script src="../js3/jquery.maskedinput.js" type="text/javascript"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
<script type="text/javascript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Cierre mes ";

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

function Comparar_Fecha(Obj1,Obj2)
{
	String1 = Obj1;
	String2 = Obj2;
	// Si los dias y los meses llegan con un valor menor que 10
	// Se concatena un 0 a cada valor dentro del string
	if (String1.substring(1,2)=="/") {
	String1="0"+String1
	}
	if (String1.substring(4,5)=="/"){
	String1=String1.substring(0,3)+"0"+String1.substring(3,9)
	}
	
	if (String2.substring(1,2)=="/") {
	String2="0"+String2
	}
	if (String2.substring(4,5)=="/"){
	String2=String2.substring(0,3)+"0"+String2.substring(3,9)
	}
	
	dia1=String1.substring(0,2);
	mes1=String1.substring(3,5);
	anyo1=String1.substring(6,10);
	dia2=String2.substring(0,2);
	mes2=String2.substring(3,5);
	anyo2=String2.substring(6,10);
	
	
	if (dia1 == "08") // parseInt("08") == 10 base octogonal
	dia1 = "8";
	if (dia1 == '09') // parseInt("09") == 11 base octogonal
	dia1 = "9";
	if (mes1 == "08") // parseInt("08") == 10 base octogonal
	mes1 = "8";
	if (mes1 == "09") // parseInt("09") == 11 base octogonal
	mes1 = "9";
	if (dia2 == "08") // parseInt("08") == 10 base octogonal
	dia2 = "8";
	if (dia2 == '09') // parseInt("09") == 11 base octogonal
	dia2 = "9";
	if (mes2 == "08") // parseInt("08") == 10 base octogonal
	mes2 = "8";
	if (mes2 == "09") // parseInt("09") == 11 base octogonal
	mes2 = "9";
	
	dia1=parseInt(dia1);
	dia2=parseInt(dia2);
	mes1=parseInt(mes1);
	mes2=parseInt(mes2);
	anyo1=parseInt(anyo1);
	anyo2=parseInt(anyo2);
	
	if (anyo1>anyo2)
	{
	return false;
	}
	
	if ((anyo1==anyo2) && (mes1>mes2))
	{
	return false;
	}
	if ((anyo1==anyo2) && (mes1==mes2) && (dia1>dia2))
	{
	return false;
	}

return true;
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
			document.getElementById("form_busqueda").submit();			
		}
		
		
		function buscar() {
			document.getElementById("form_busqueda").submit();
		}
		

		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("iniciopagina").value=1;
			document.getElementById("form_busqueda").submit();
		}
	function Grafico() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			document.getElementById('frame_rejilla').src = 'graficotorta.php?fechainicio='+fechainicio+'&fechafin='+fechafin;		
	}
	function GraficoBarras() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			document.getElementById('frame_rejilla').src = 'graficobarras.php?fechainicio='+fechainicio+'&fechafin='+fechafin;		
	}	

	
	function CierreAnualizado() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			document.getElementById('frame_rejilla').src = 'calculodeiva.php?fechainicio='+fechainicio+'&fechafin='+fechafin;		
	}	
	
function progressExcelBar(xx,file) {
	window.parent.progressExcelBar(xx,file);
}	
	
	function CierreAnualizadoExcel() {
		var fechainicio=document.getElementById("fechainicio").value;
		var fechafin=document.getElementById("fechafin").value;
	
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
					$.get("../excel/CierreAnualizadoExcel.php?fechainicio="+fechainicio+"&fechafin="+fechafin+"&file="+v, function(data, status) {
						if(status == 'success'){	
						$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
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
	
	}
	
		
	function actualizar() {
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;

		if (Comparar_Fecha(fechainicio, fechafin)){
 			document.getElementById("form_busqueda").submit();
 	    } 		
	}	


		</script>
		
<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  //var iframeWin = window.frames[iframeName];
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto"; // helps resize (for some) if new doc shorter than previous
		  //var docHt = getDocHeight(iframeWin.document);
		  // need to add to height to be sure it will all show
		  var h = alertSize();
		  var new_h = (h-74);
		  iframeEl.style.height = new_h + "px";
		  //alertSize();
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
		
	</head>
	<body onLoad="inicio(); setIframeHeight('frame_rejilla');" onresize="setIframeHeight('frame_rejilla');">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="cierremes.php" target="frame_rejilla">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="50%">
					  <table class="fuente8" cellspacing=0 cellpadding=3 border=0>

					  <tr>
						  <td>Fecha&nbsp;de&nbsp;inicio</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" name="fechainicio" maxlength="10" value="<?php echo implota($fechainicio);?>">
						  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">					  
						  </td>
						  
						  <td>&nbsp;</td>

						  <td>Fecha&nbsp;de&nbsp;fin</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" name="fechafin" maxlength="10" value="<?php echo implota($fechafin);?>">
						  <img src="../img/calendario.png" name="Image11" id="Image11" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
					  </td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table></td></tr></table>
			  </div>

			  <table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="50" align="center">
			 	<div>
			 	<img id="botonBusqueda" src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar();" onMouseOver="style.cursor=cursor">
				<img id="botonBusqueda" src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botongraficotorta.jpg" height="22" border="1" onClick="Grafico();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botongraficotorta.jpg" height="22" border="1" onClick="GraficoBarras();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botoncierreanualizado.jpg" height="22" border="1" onClick="CierreAnualizado();" onMouseOver="style.cursor=cursor">
			 	<img id="botonBusqueda" src="../img/botoncierreanualizadoexcel.jpg" height="22" border="1" onClick="CierreAnualizadoExcel();" onMouseOver="style.cursor=cursor">

				</div>				</td>
			  </table>

				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
					<iframe width="100%" height="430" id="frame_rejilla" name="frame_rejilla" frameborder="0">
						<ilayer width="100%" height="430" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
					<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			</div>
		  </div>			
		</div>
 <script type="text/javascript">

     var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide(), actualizar(); },
          showTime: true
      });
      cal.manageFields("Image1", "fechainicio", "%d/%m/%Y");
      cal.manageFields("Image11", "fechafin", "%d/%m/%Y");
</script>	  	
		
	</body>
</html>
