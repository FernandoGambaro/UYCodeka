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
	//echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=@$s->data['loggedAt'];
   $timeOut=@$s->data['timeOut'];
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
$paginacion=$s->data['alto'];
if($paginacion<=0) {
	$paginacion=20;
}
include ("../conectar.php");
include("../common/funcionesvarias.php");

header('Content-Type: text/html; charset=UTF-8'); 
setlocale('LC_ALL', 'es_ES');
date_default_timezone_set('America/Montevideo');

include ("../funciones/fechas.php"); 

function  cambiaf_a_normal( $fecha ){
 ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha );
 $mifecha[4] = $mifecha [ 3 ]. "/" . $mifecha [ 2 ]. "/" . $mifecha [ 1 ];
return $mifecha;
 }
/*
   function array_envia($array) {
      $tmp = serialize($array);
      $tmp = urlencode($tmp);
      return $tmp;
   }
   */
$ordenado="CALIDADID";
$orden="ASC";

$codusuarios=@$_GET['u'];

$CONTROLFECHA=@$_GET['CONTROLFECHA'];

if ($CONTROLFECHA!="") {
$criterio=" AND `CONTROLFECHA` = '$CONTROLFECHA' ";
} else {
$criterio="";
}

if ($codusuarios=="")
$codusuarios=$UserID;

if (empty($_GET['anio'])) {
$anio=date('Y');
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}


$fechainicio="01/".$mes."/".$anio;
$fechafin=date('t')."/".$mes."/".$anio;


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />	
		<script type="text/javascript" src="../funciones/validar.js"></script>

		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
	
		

<script language="JavaScript">
parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Reportes";

function autoResize(id){
    var newheight;
    var newwidth;

    if(document.getElementById){
        newheight=document.getElementById(id).contentWindow.document .body.scrollHeight;
        newwidth=document.getElementById(id).contentWindow.document .body.scrollWidth;
    }

    document.getElementById(id).height= (newheight) + "px";
    document.getElementById(id).width= (newwidth) + "px";
}

</script>
        
        <script>
            function HideFrame(){
					autoResize('lowframe');
            }

        </script>
 <script>
 
	function submitform1() {
		//event.preventDefault();
		var mes=formb.mes.options[formb.mes.selectedIndex].value;
		var user=formb.user.options[formb.user.selectedIndex].value;
//alert(user);
		lowframe.location.href='graficobarras.php?u='+user+'&mes='+mes;

		return true;	
	} 
	function submitform2(reporte){
		
		var fechainicio=$("#fechainicio").val();
		var fechafin=$("#fechafin").val();
			var codusuarios=document.getElementById("codusuarios").value;
				if (codusuarios=='') {
					var titulo="Detalls horas realizadas por usuario y por proyecto entre ";
				} else {
					var titulo="Detalls horas realizadas por proyecto entre ";
				}

		if (reporte==1) {
			lowframe.location.href='DetallesHorasProyectoUsuarioNew.php?fechainicio='+fechainicio+'&fechafin='+fechafin+"&codusuarios="+codusuarios+"&titulo="+titulo;
		}else {
			var codcliente=$("#Acodcliente").val();
			if (codcliente=='') {
				showWarningToast('Seleccione cliente');
			}else{
				lowframe.location.href='DetallesHorasProyectosCliente.php?codcliente='+codcliente+'&fechainicio='+fechainicio+'&fechafin='+fechafin;
			}
		}
	}
        </script>
 <script>
 	
function excel(){
			var url ='';
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var codusuarios=document.getElementById("codusuarios").value;
				if (codusuarios=='') {
					var titulo="Detalls horas realizadas por usuario y por proyecto entre ";
				} else {
					var titulo="Detalls horas realizadas por proyecto entre ";
				}
					url = "../excel/reporteaexcel.php?fechainicio="+fechainicio+"&fechafin="+fechafin+"&codusuarios="+codusuarios+"&titulo="+titulo;
					$.msgBox({ type: "prompt",
					 title: "Nombre archivo sin extensión",
					 inputs: [
					 { header: "Nombre de Archivo", type: "text", name: "nombre" }],
					 buttons: [
					 { value: "Aceptar" }, { value:"Cancelar" }],
					 success: function (result, values) {
											$(values).each(function (index, input) {
											v =  input.value ;
					  						});									    	
							if (v!="") {
								//var windowObjectReference=null;
								windowObjectReference = window.open('../excel/proceso.php?file='+v,"excel", "resizable,scrollbars,status, width=340,height=160,right=120,top=200");
								var TURL=url+"&file="+v;
								 jQuery.ajax({
							    type: "POST", url: TURL, data: "", success: function(data){
									setTimeout(function() {windowObjectReference.close();}, 1000);	
									$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');
			   					$("#newItemESShow").hide();
							    },
							    error: function (XMLHttpRequest, textStatus, errorThrown) {
									setTimeout(function() {windowObjectReference.close();}, 1000);					
									showWarningToast('Se produjo un error, intentelo mas tarde');
									$("#newItemESShow").hide();
									},
								 dataType: "text" 
							    });
   											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}
						}
					})(jQuery);
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
 	
    $("#proyectos").autocomplete({
    		source: function(request, response) {
            $.ajax({
                url: "../common/busco_proyectos.php",
                dataType: "json",
                data: {
                    term: request.term,
                    codcliente: $("#Acodcliente").val(), 
                },
                success: function(data) {
							var codigo=$("#Acodcliente").val();
							if (codigo=="") {
								alert("Debe introducir cliente");
								return false;
							}                	
                    response(data);
                }
            });
        },
    	  minLength:1,
        autoFocus:true,
        select: function(event, ui) {
           	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codproyectos=thisValue.split("~")[0];
			var proyecto=thisValue.split("~")[1];

			$("#codproyectos").val(codproyectos);
			$("#proyectos").val(proyecto);

		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#Acliente").autocomplete({
        source: '../common/busco_clientes.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];

		$("#Acodcliente").val(pref);
		$("#Acliente").val(nombre);
			$("#codproyectos").val('');
			$("#proyectos").val('');


		}
	}).autocomplete("widget").addClass("fixed-height");

    $("#Ausuarios").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
	
			$("#codusuarios").val(pref);
			$("#Ausuarios").val(nombre);

			}
	}).autocomplete("widget").addClass("fixed-height");
	
	
});
</script>	 
 
</head>
<body marginwidth="0" topmargin="0" leftmargin="0"> 
<table width="100%" border="0" class="fuente8"><TR><td valign="top" width="180">

<table class="fuente8">
<tr><td>

</td></tr><TR><td>
Reporte mensual por Usuario<br>
<br>
<form name="formb" action="#" method="get">

<select name="user" id="user" onchange="submitform1();">
<?php
$user=@$_GET['u'];
$sql="select * from `usuarios` WHERE `borrado`='0'";
$res_sql=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or dir ("Error");
while ($row=mysqli_fetch_array($res_sql)) {
	if($row['codusuarios']==$user) {
		echo "<option value='".$row['codusuarios']."' selected>".$row['nombre']." ".$row['apellido']."</option>";
	} else {
		echo "<option value='".$row['codusuarios']."'>".$row['nombre']." ".$row['apellido']."</option>";
	}
}
?>
</select>
<br>
<br>mes:
<select id="mes" name="mes" onchange="submitform1();">

<?php

   for ($x=1; $x<=12; $x++) {
      if ($mes==$x) {
         echo "<option value='$x' selected>".mes($x)."</option>";
      } else {
         echo "<option value='$x'>".mes($x)."</option>";
      }
   }
?>
</select>
<p>
</center>

</form>
</td></TR>

<tr>
<td><table border="0"><td>
<form name="formb1" action="#" method="get" target="lowframe">

Fecha&nbsp;de&nbsp;inicio
	<input id="fechainicio" type="text" class="cajaPequena" name="fechainicio" maxlength="10" value="<?php echo $fechainicio;?>" readonly>
		  <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
				<script type="text/javascript">
		   Calendar.setup({
		     inputField : "fechainicio",
		     trigger    : "Image1",
		     align		 : "Bl",
		     onSelect   : function() { this.hide();  },
		     dateFormat : "%d/%m/%Y"
		   });
		</script></td>
	</tr><tr>
		  <td>Fecha&nbsp;de&nbsp;fin&nbsp;<input id="fechafin" type="text" class="cajaPequena" name="fechafin" maxlength="10" value="<?php echo $fechafin;?>" readonly>
		  <img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
				<script type="text/javascript">
		   Calendar.setup({
		     inputField : "fechafin",
		     trigger    : "Image2",
		     align		 : "Bl",
		     onSelect   : function() { this.hide();  },
		     dateFormat : "%d/%m/%Y"
		   });</script>

</tr><tr><td>
</form>
<fieldset><legend>&nbsp;Reporte&nbsp;todos los Usuario&nbsp;</legend>	
<table>
<tr>
<td>
 <input name="Ausuarios" type="text" class="cajaMedia" id="Ausuarios" size="35" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
 <input name="codusuarios" type="hidden" id="codusuarios" value="" >
</td></tr>
<tr><td>
<button class="boletin" onClick="submitform2(1);" >
<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Ver</button>				   
				
		   

</td></tr>
<tr><td>
<button class="boletin" onClick="excel();" >
<i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel</button>		
</td></tr>
</table>
</fieldset>
</td></tr><tr><td>
<fieldset><legend>&nbsp;Reporte&nbsp;Cliente&nbsp;</legend>
<table>

<tr>
<td>
 <input value="<?php echo $nombre;?>" name="Acliente" type="text" class="cajaMedia" id="Acliente" size="35" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
 <input name="codcliente" type="hidden" id="Acodcliente" value="" >
</td></tr>
<tr><td>				
<button class="boletin" onClick="HideFrame(); submitform2(2)" >
<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Ver</button>				   
</td></tr>

<tr>
<td>

<button class="boletin" onClick="excel();" >
<i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;Exportar Excel</button>		
</td></tr>
</table>
</fieldset></td>
</table>
</td>
</tr>


</table>

</td><td width="680" valign="top">
   <iframe src="graficobarras.php?u=<?php echo $codusuarios;?>&mes=<?php echo $mes;?>&t=Proyectos dia a dia"  width="100%" height="600px" frameborder="0" scrolling="no" id="lowframe" name="lowframe" onLoad="autoResize('lowframe');"></iframe>

</td>



</tr></table>

</body>
</html>
