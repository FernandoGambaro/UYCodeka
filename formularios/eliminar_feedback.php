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
 
$codfeedback=$_GET["codfeedback"];

$query="SELECT * FROM feedbackform WHERE codfeedback='$codfeedback'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
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
		<script language="javascript">
		
		function cancelar() {
			event.preventDefault();
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiar() {
			document.getElementById("nombre").value="";
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
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

function validar() 
			{
				event.preventDefault();
				document.getElementById("formulario").submit();
			}

</script>		
	</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Eliminar Línea Feedback </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_feedback.php">
				<input  type="hidden" name="codfeedback" value="<?php echo $codfeedback;?>" />
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="11%">Formulario</td>
							<td colspan="2">
								<?php
								$codformulario=mysqli_result($rs_query, 0, "codformulario");

					   $query_familias="SELECT * FROM formularios WHERE borrado=0 ORDER BY descripcion ASC";
						$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);
						$contador=0;
						?>
						<select id="tipoformulario" name="tipoformulario" class="comboGrande" data-index="2">
						<?php
								if($codformulario!='') { ?>
								<option value="0">Seleccione un formulario</option>
								<?php }
								while ($contador < mysqli_num_rows($res_familias)) { 
									if ($codformulario==mysqli_result($res_familias, $contador, "codformulario")) {?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codformulario")?>" selected="selected"><?php echo mysqli_result($res_familias, $contador, "descripcion")?></option>
								<?php } else { ?>
								<option value="<?php echo mysqli_result($res_familias, $contador, "codformulario")?>"><?php echo mysqli_result($res_familias, $contador, "descripcion")?></option>
								<?php } 
									$contador++;
								} ?>				
								</select>
							</td>
				        </tr>		
				        <tr>				
						  <td>Fila</td>
						  <td><input name="afila" type="text" class="cajaPequena2" id="fila" size="10" maxlength="10" value="<?php echo mysqli_result($rs_query, 0, "fila");?>"></td>
						  </tr>
				        <tr>				
						  <td>Competencia</td>
						  <td><input name="Acompetencia" type="text" class="cajaGrande" id="competencia" size="200" maxlength="200" value="<?php echo mysqli_result($rs_query, 0, "competencias");?>"></td>
						  </tr>						  
				        <tr>						
						  <td>Definición</td>
						  <td><textarea name="definicion" class="textArea" rows="6" cols="60" style="font-family: helvetica;font-size: 8pt;"><?php echo mysqli_result($rs_query, 0, "definicion");?></textarea>
						  </td>
						  </tr>	
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
					<input id="accion" name="accion" value="baja" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
	</body>
</html>