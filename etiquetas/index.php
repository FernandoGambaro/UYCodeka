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
?>
<html>
	<head>
		<title>Etiquetas</title>
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
var tst='';
var alto=parent.document.getElementById("alto").value-160;

var totales=alto/22;
	 totales=totales-1;

</script>
<script>
var miVariable = totales;
document.cookie ='variable='+miVariable+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
</script>

<?php
$miVariable =  $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>
		<script language="javascript">
		
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; Etiquetas ";

		function ventanaArticulos(){
				$.colorbox({href:"ver_articulos.php",
				iframe:true, width:"95%", height:"95%",
				});
		}
		
		function imprimir(tipo) {
			var codigo=document.getElementById("codarticulo").value;
			if (codigo=="") {
				alert ("Debe seleccionar un articulo antes de imprimir el codigo de barras");
			}else {
				if (tipo=="br") {
					window.open("../fpdf/codigo.php?codigo="+codigo,"frame_rejilla");
				} else {
					var e = document.getElementById("etiqueta");
					var etiqueta = e.options[e.selectedIndex].value;	
					if (etiqueta==0) {
					alert ("Debe seleccionar un tipo de etiqueta");
					} else {
					window.open("../fpdf/etiqueta_avery.php?codarticulo="+codigo+"&etiqueta="+etiqueta,"frame_rejilla");
					}
				}
			}
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}

function pon_prefijo_Fb (codfamilia,pref,nombre,precio,codarticulo,moneda) {

	$("#codbarras").val(pref);
	$("#descripcion").val(nombre);

	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();

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
<script>
function cambiaimagen(idArticulo)
{
	document.getElementById("imagenMarca").src = "../img/"+idArticulo+".png";
}
		
</script>		

<link href="../js3/jquery-ui.css" rel="stylesheet">
<script src="../js3/jquery-ui.js"></script>
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
 	
    $("#descripcion").autocomplete({
        source: 'busco2.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
         	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codfamilia=thisValue.split("~")[0];
			var codigobarras=thisValue.split("~")[1];
			var referencia=thisValue.split("~")[2];
			var nombre=thisValue.split("~")[3];
			var precio=thisValue.split("~")[4];
			var codarticulo=thisValue.split("~")[5];
			var moneda=thisValue.split("~")[6];
			var codservice=thisValue.split("~")[7];
			var detalles=thisValue.split("~")[8];
			var comision=thisValue.split("~")[9];
			var stock=thisValue.split("~")[10];
		
			if (codigobarras!='') {
				$("#codbarras").val(codigobarras);
			} else {
				$("#codbarras").val(referencia);
			}
			//$("#articulos").val(referencia);
			//$("#codservice").val(codservice);
		
			$("#descripcion").val(nombre);

			$("#codarticulo").val(codarticulo);
			Acambio();
			actualizar_importe();
	 		$('[data-index="9"]').focus();
		}
	}).autocomplete("widget").addClass("fixed-height");


});
</script>

	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
			  <div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_rejilla">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
				  <tr>
					<td width="15%">Código </td>
					<td colspan="8" valign="middle">
					<input name="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15">
					<img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();"></td>
					<input name="codarticulo" class="cajaMedia" id="codarticulo" type="hidden">
				    <td valign="middle">&nbsp;</td>
					<td>Tipo Etiqueta</td>
					<td><select id="etiqueta" name="etiqueta" class="comboMedio">
					<option value="0" selected>Selecione uno</option>
					<?php
					$_Avery_Labels = array(5160=>array(0=>'5160',1=>'24') ,5161=>array(0=>'5161', 1=>'20') ,5162=>array(0=>'5162', 1=>'14'), 5163=>array(0=>'5163', 1=>'10'),
					 5164=>array(0=>'5164', 1=>'6'), 8600=>array(0=>'8600', 1=>'24'), 'L7163'=>array(0=>'L7163', 1=>'14'), 3422=>array(0=>'3422',1=>'24'));					

					foreach($_Avery_Labels as $key => $totaletiquetas) {
						echo "<option value='$key' onmouseover=\"cambiaimagen(this.value)\">$key - $totaletiquetas[1] etiquetas</option>";
					}
					?>
					</select>
					
					</td>
					<td rowspan="3"><img id="imagenMarca" src="../img/blank.png" width="49" hight="64"></td>				    
				    <td rowspan="2" valign="bottom"><div align="center">
				    <img src="../img/codigobarras.jpg" border="1" align="absbottom" onClick="imprimir('br');" onMouseOver="style.cursor=cursor" width="100" height="100"></div></td>
				    <td rowspan="2" valign="bottom"><div align="center">
				    <img src="../img/qrcodigo.png" border="1" align="absbottom" onClick="imprimir('qr');" onMouseOver="style.cursor=cursor" width="100" height="100"></div></td>
				</tr><tr>
					<td>Descripcion</td>
					<td colspan="5" ><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" maxlength="50" data-index="7"></td>
				  </tr>
				</table>
				</div>
				<br>			
				<div id="lineaResultado">
			  
			  		<iframe width="95%" height="70%" id="frame_rejilla" name="frame_rejilla" frameborder="0" onload="setIframeHeight(this.id)" style=" overflow-y: scroll; ">
						<ilayer width="95%" height="70%" id="frame_rejilla" name="frame_rejilla"></ilayer>
					</iframe>
			  </form>
			 </div>
		  </div>
		</div>
		</div>
	</body>
</html>
