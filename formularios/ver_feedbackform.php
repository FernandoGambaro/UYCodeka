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

	<script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.1.min.js"></script>
	<script src="../js3/tinymce/tinymce.min.js"></script>

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
		
	</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Ver Línea Feedback </div>
				<div id="frmBusqueda">
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
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cerrar</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
<script type="text/javascript">

	$(document ).ready(function() {
	
		function applyMCE() {
			tinyMCE.init({
				mode : "textareas",
				editor_selector : "textArea",     
		 language: 'es',
		 height : 150,
		 max_height: 200,
		 menubar: 'file edit insert view format table tools',
		 menubar: false,
		 resize: false,
		 statusbar: false,
       convert_urls: false,		 
		theme: 'modern',
		external_plugins: {"nanospell": "/js3/tinymce/nanospell/plugin.js"},
		nanospell_dictionary: "es", 
       nanospell_server: "php", // choose "php" "asp" "asp.net" or "java"
       nanospell_autostart:true,				

	toolbar1: " undo redo ",
 	
	paste_as_text: true,
	image_advtab: true ,

 	menubar: false,
 	toolbar_items_size: 'small',
	spellchecker_language: 'es_ES',

	
	
 	style_formats: [{
    title: 'Bold text',
    inline: 'b'
 	}, {
    title: 'Red text',
    inline: 'span',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Red header',
    block: 'h1',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Example 1',
    inline: 'span',
    classes: 'example1'
 	}, {
    title: 'Example 2',
    inline: 'span',
    classes: 'example2'
 	}, {
    title: 'Table styles'
 	}, {
    title: 'Table row 1',
    selector: 'tr',
    classes: 'tablerow1'
  	}],
  	
  	paste_as_text: true,
  	paste_text_sticky : true,

		});			      
		}
	applyMCE();
});
</script>		
</html>