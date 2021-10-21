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
require_once __DIR__ .'/../classes/class_session.php';

if (!$s = new session()) {
	echo "<h2>".'Ocurrió un error al iniciar session!'."</h2>";
	echo $s->log;
	exit();
}

$oidcontacto = $s->data['UserID'] ;
$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

require_once '../common/fechas.php';   
require_once '../common/funcionesvarias.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

date_default_timezone_set("America/Montevideo"); 

$devolucion=isset($_GET["devolucion"]) ? $_GET['devolucion'] : '';
$codformulario=$formulario=isset($_GET["codformulario"]) ? $_GET['codformulario'] : '';
$codusuarios=isset($_GET['nempleado']) ? $_GET['nempleado'] : '';
$array=$devarray=array();
$y=0;

$array=explode(',', $devolucion);

if(strlen($devolucion)>0){
		$obj = new Consultas('feedback');
		$obj->Select();
		$obj->Where('codfeedback', $array[0]);
		$paciente = $obj->Ejecutar();
		//echo $paciente['consulta'];
		$rows = $paciente["datos"];

		$total_rows=$paciente["numfilas"];

		$colaborador=$rows[0]["colaborador"];
		$formulario=$rows[0]["codformulario"];
		$competencias=$rows[0]["nivel"];
		$fechadevolucion=$rows[0]["fecha"];
		$codusuarios=$rows[0]["codusuarios"];
		$codformulario=$rows[0]["codformulario"];
		$dias=$rows[0]["dias"];
}

if(strlen($dias)<=0){
	$dias=30;
}

/*Saco los datos del primer elementeo del array para saber a que colaborador le hacemos la devolución, y el modelo del formulario*/
/*
$query="SELECT * FROM feedback WHERE codfeedback='$array[0]'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$codformulario= mysqli_result($rs_query, 0, "codformulario");
		$colaborador=mysqli_result($rs_query, 0, "colaborador");
		$dias=mysqli_result($rs_query, 0, "dias");
*/		
$x=0;
$compete='';
foreach($array as $codfeedback){

$objPass = new Consultas('feedback');
$objPass->Select();
$objPass->Where('codfeedback', $codfeedback);
if(strlen($colaborador)>0){
	$objPass->Where('colaborador', $colaborador);
}
$pacientePass = $objPass->Ejecutar();
//echo $paciente['consulta'];
$rowsPass = $pacientePass["datos"];

$total_rowsPass=$pacientePass["numfilas"];

if($total_rowsPass>0) {
	if($x==0) {
		$compete=$codfeedback;
	} else {
		$compete.="-".$codfeedback;
	}
	array_push($devarray, $codfeedback);
}
	$x++;
}

$cantformularios=count($devarray);

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $page_title; ?></title>
		
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

		<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
		<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
		<link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
		<!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

		<link href="../library/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
		<script src="../library/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>

		<link rel="stylesheet" href="../library/colorbox/colorbox.css?u=<?php echo time();?>" />
		<script src="../library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

		<script src="../library/js/cargadatos.js" type="text/javascript"></script>
		<script src="../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

		<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

		<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
		<script src="../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
		<script src="../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>

		<script src="validar.js" type="text/javascript"></script>

		<link rel="stylesheet" href="../library/js/msgBoxLight.css?u=<?php echo time();?>" type="text/css">
		<script type="text/javascript" src="../library/js/jquery.msgBox.js"></script>
		<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>
		<script  src="../library/js/jquery-ui.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">
	
	<style type="text/css">
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Arial"; font-size:x-small }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
	</style>

<script type="text/javascript" >
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

$(document).ready( function(){
	
$('#fecha').change(function () {

    verifico();
});
});

function verifico() {
		var fecha=$("#fecha").val();
		var codformulario=$("#codformulario").val();
		var colaborador=$("#codusuario").val();
		if (fecha!='' && codformulario!='' && colaborador!='') {			
		  $.ajax({
	      url: 'verifico.php',
	      data: 'fecha='+fecha+'&colaborador='+colaborador+'&codformulario='+codformulario,
	      type: 'POST',
	      dataType: 'json',
	      success: function (data) {
			var erro =data.erro;
			var query=data.query;
	        if(erro != '0'){
	        showWarningToast('Error, evaluación ya realizada: ');
	        $("#guardar").hide();
	        }else {
	        	//var fecha=$("#fecha").val();
				var fechaproxima = data.fechaproxima;
					var mDate = new Date($("#fecha").val());
					var mEpoch = mDate.getTime();	    
					var mate = new Date(fechaproxima);
					var mpoch = mate.getTime();	    
	        		if (mpoch>mEpoch) {
				        showWarningToast('Evaluación fuera de fecha, ajuste fechas de la próxima: ');
	        		}
				var fechafeedback = data.fechafeedback;
//alert(fechafeedback);
				if (fechafeedback!==false && fechafeedback!=="" && fechafeedback!=="0000-00-00") {				
				$("#fechafeedback").val(fechafeedback);
				}
	       	$("#guardar").show();
	        }
		},
	      error: function(e){
	        showWarningToast('Error, intente mas tarde: ');
	      }
	    }); 	
	}
}

 function validar() {
 	event.preventDefault();
 	var cuento=0;
 	if ($("#codusuario").val()=='') {
    	showWarningToast('Error, Seleccione colaborador: ');
    	return false;
 	}else {
 		document.getElementById("formulario").submit();
 	}   	
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
 	
     $("#colaborador").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var colaborador=thisValue.split("~")[1];

		$("#codusuario").val(pref);
		$("#colaborador").val(colaborador);
		verifico();
		}
	}).autocomplete("widget").addClass("fixed-height");
});
</script>	
</head>

<body>
		<div id="pagina">
			
				<div align="center">
				<div id="frmBusqueda">
<form name="formulario" id="formulario" action="guardar_devolucion_form.php" method="post">
<table cellspacing="0" border="0" width="100%">
	<colgroup width="155"></colgroup>
	<colgroup span="5" width="76"></colgroup>
	<colgroup width="115"></colgroup>
	<colgroup width="253"></colgroup>
	<colgroup width="20"></colgroup>
	<tr>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="20" align="center" valign=middle bgcolor="#FFCC99">
		<font face="Calibri" size=5>FEEDBACK AL COLABORADOR</font>
		<input name="formulario" id="formulario" type="hidden" value="<?php echo $formulario;?>" />
		</td>
		</tr>
	<tr>
		<td style="border-top: 2px solid #1a1a1a; border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="20" align="left" valign=middle bgcolor="#FFFFFF"><font face="Calibri">NOMBRE: 
<?php


$objPass1 = new Consultas('usuarios');
$objPass1->Select('nombre,apellido');
$objPass1->Where('codusuarios', $codusuarios);
$pacientePass1 = $objPass1->Ejecutar();
echo $pacientePass1['consulta'];
$rowsPass1 = $pacientePass1["datos"];

//$total_rowsPass1=$pacientePass1["numfilas"];

	/*
	$sql_col="SELECT nombre,apellido FROM usuarios WHERE codusuarios='".$colaborador."'";
	$res_col=	mysqli_query($GLOBALS["___mysqli_ston"], $sql_col);		
	*/			
?>
		<input type="hidden" name="colaborador"  value="<?php echo $codusuarios;?>" />
		<input type="text"  id="usuario" class="comboGrande" value="<?php echo $rowsPass1[0]["nombre"]. " - ".$rowsPass1[0]["apellido"];?>" disabled="disabled" />		
		</font></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #1a1a1a; border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="20" align="left" valign=middle bgcolor="#FFFFFF">
		<font face="Calibri">PUESTO:  
		<?php

$objPass2 = new Consultas('formularios');
$objPass2->Select();
$objPass2->Where('codformulario', $codformulario);
$objPass2->Where('borrado', '0');
$pacientePass2 = $objPass2->Ejecutar();
//echo $pacientePass2['consulta'];
$rowsPass2 = $pacientePass2["datos"];

$total_rowsPass2=$pacientePass2["numfilas"];
	/*
	$query_familias="SELECT * FROM formularios WHERE borrado=0 AND codformulario='".$codformulario."'";
	$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);	
	*/
	echo $rowsPass2[0]["descripcion"];
?>	
</font></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #1a1a1a; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="16" align="left" valign=middle bgcolor="#FFFFFF">
		<font face="Calibri">REALIZADO POR: 
		<input  name="codusuario" type="hidden" value="<?php echo $UserID;?>" /><input value="<?php echo $UserNom." ".$UserApe;?>" />&nbsp;el&nbsp;
		<input name="fecha" id="fecha" value="<?php echo date("d/m/Y");?>" />
		</font></td>
		</tr>
	<tr>
		<td style="border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #1a1a1a" rowspan=1 height="48" align="center" valign=middle>
		<font face="Calibri" size=3>COMPETENCIAS</font>
				<input name="competencias" type="hidden" value="<?php echo $compete;?>" />		
		</td>
		
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=5 align="left" valign=top>
<table border="0">
<tr>
<?php
$Tipo = array(
0=>"Bajo",
1=>"Medio",
2=>"Alto",
3=>"Destacado");

/*
$sql_feedback="SELECT fila,competencias FROM feedbackform WHERE codformulario=".$codformulario." and competencias<>'' and definicion<>'' ORDER BY fila ASC";
$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);
*/

$objPass3 = new Consultas('feedbackform');
$objPass3->Select('fila,competencias');
$objPass3->Where('codformulario', $codformulario);
$objPass3->Where('competencias', ' ', '<>');
$objPass3->Where('definicion', ' ', '<>');
$objPass3->Orden('fila', 'ASC');
$pacientePass3 = $objPass3->Ejecutar();
//echo $pacientePass3['consulta'];
$rowsPass3 = $pacientePass3["datos"];

$total_rowsPass3=$pacientePass3["numfilas"];

if($total_rowsPass3>=0){
	foreach($rowsPass3 as $rowPass3){


//while ($contafeed < mysqli_num_rows($res_feedback)) { 
	
$fila=$rowPass3["fila"];

?>
<td valign="top"><table border="1"><tbody><tr>
<td colspan="<?php echo $cantformularios;?>" align="center">
<font face="Calibri" size="3"> 
<?php
	echo $rowPass3["competencias"];
?>		
</font>
</td></tr><tr>
<?php
$x=1;
foreach($devarray as $codfeedback){
	
	$objPass4 = new Consultas('feedback');
	$objPass4->Select();
	$objPass4->Where('codfeedback', $codfeedback);
	if(strlen($colaborador)>0){
		$objPass4->Where('colaborador', $colaborador);
	}
	$pacientePass4 = $objPass4->Ejecutar();
	//echo $paciente['consulta'];
	$rowsPass4 = $pacientePass4["datos"];
	
	$total_rowsPass4=$pacientePass4["numfilas"];

/*
$query="SELECT * FROM feedback WHERE codfeedback='$codfeedback' and colaborador='$colaborador'" ;
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
*/
$fecha=$rowsPass4[0]["fecha"];

?>
<td valign="top" align="center"><table><tbody><tr><td  align="center">
<?php echo $x;?>º</td></tr>	
<?php
$x++;
$xi=0;

$objPass5 = new Consultas('feedback');
$objPass5->Select();
$objPass5->Where('fecha', $fecha);
if(strlen($colaborador)>0){
	$objPass5->Where('colaborador', $colaborador);
}
$objPass5->Where('fila', $fila);

$pacientePass5 = $objPass5->Ejecutar();
//echo $paciente['consulta'];
$rowsPass5 = $pacientePass5["datos"];

$total_rowsPass5=$pacientePass5["numfilas"];
/*
$query="SELECT * FROM feedback WHERE fecha='$fecha' AND colaborador='$colaborador' and fila='$fila'";
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $query);
*/
 $nivel=explode("-",$rowsPass5[0]["nivel"]);

 foreach($nivel as $i) {
 	//echo $i."<br>";
 	if($i==1) {
		break;
 	}
 	$xi++;
 }
 ?>		
	<tr><td  align="center">
		<font face="Calibri" size="3"><?php echo $Tipo[$xi];?></font></td></tr></tbody></table>	</td>
<?php 
}
?>
</tr></tbody></table></td>
<?php
	}
}

?>
</tr>
</table>
</td>

		</tr>
<?php
$y=0;

$objPass6 = new Consultas('feedbackform');
$objPass6->Select();
$objPass6->Where('codformulario', $formulario);
$objPass6->Where('competencias', ' ');
$objPass6->Orden('fila', 'ASC');
$pacientePass6 = $objPass6->Ejecutar();
//echo $pacientePass6['consulta'];
$rowsPass6 = $pacientePass6["datos"];

$total_rowsPass6=$pacientePass6["numfilas"];

/*
$sql="SELECT * FROM feedbackform WHERE codformulario=".$formulario ." ORDER BY fila ASC";
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
*/

include '../class/RandomColor.php';
use \Colors\RandomColor;

		$color=RandomColor::many(18, array('hue'=>'green'));

$contador=0;

if($total_rowsPass6>=0){
	foreach($rowsPass6 as $rowPass6){
//while ($contador < mysqli_num_rows($res_resultado)) { 

	$objPass7 = new Consultas('feedbackform');
	$objPass7->Select('competencias, definicion');
	$objPass7->Where('codformulario', $rowPass6["codformulario"]);
	$objPass7->Where('fila', $rowPass6["fila"]);
	
	$pacientePass7 = $objPass7->Ejecutar();
	//echo $paciente['consulta'];
	$rowsPass7 = $pacientePass7["datos"];
	
	$total_rowsPass7=$pacientePass7["numfilas"];
		/*
		$sql_feedback="SELECT competencias, definicion FROM feedbackform WHERE codformulario=".mysqli_result($res_resultado, $contador, "codformulario")." 
		AND fila=".(mysqli_result($res_resultado, $contador, "fila"));
		$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);
		*/
	?>
		<tr>
			<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" colspan=9 height="40" align="center" valign=middle 
			bgcolor="<?php echo $color[$y];?>">
			<font face="Calibri" size=3>
	<?php 

	echo $rowsPass7[0]["definicion"];

	?>	 </font></td>
		</tr>
		<tr>
			<td style="border-bottom: 3px solid #101010; border-left: 2px solid #101010; border-right: 2px solid #101010" colspan=9  align="center" style="text-align: left;">
			<textarea name="desempenio[<?php echo $contador;?>][<?php echo $x;?>]" id="desempenio<?php echo $y;?>" rows="6" cols="60">
			<?php echo $rowPass6["aspectos"];?></textarea>		
			</td>
			</tr>
	<?php	
	$y++;
	$contador++;

	}
}
?>		
	<tr>
		<td style="border-top: 1px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010; " colspan="9" height="26" align="left" valign=middle>
		<font face="Calibri">PRÓXIMA REUNIÓN DE FEEDBACK: </font>
		<div class="col-xs-2">
			<div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2">
				<input placeholder="Fecha fin" class="form-control input-sm" size="10" type="text" value="<?php echo date("d/m/Y", strtotime ( '+'.$dias.' day' , strtotime ( date('Y-m-j') ) ));?>" name="fechaproxima" id="fechaproxima" readonly>
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
			</div>
		</div>
		</td>
	</tr>
</table><br>
		<div>
				<button id="guardar" class="boletin" onClick="validar();" ><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
				<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" ><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
		</div>
			<input id="accion" name="accion" value="alta" type="hidden">
</form>
</div></div></div><p>&nbsp;</p>
</body>

<script src="../../library/ckeditor/ckeditor.js?ver<%=DateTime.Now.Ticks.ToString()%>"></script>
    <script src="../../library/nanospell/autoload.js?ver<%=DateTime.Now.Ticks.ToString()%>"></script>
    <script src="../../library/ckeditor/config.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>
    <script src="../../library/ckeditor/lang/es.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>
    <script src="../../library/ckeditor/styles.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>

<script type="text/javascript">
var focusElement = {};

	function applyCKEDITOR(elemento) {
		var editor = CKEDITOR.replace( elemento, {
				customConfig: 'config.js',
				width: '100%',
				height: 150,                    
				fullPage: true,
				allowedContent: true,
				language: 'es',
				coreStyles_bold: { element: 'b' },
				coreStyles_italic: { element: 'i' },
				extraPlugins: 'enterkey',
				removePlugins : 'easyimage, cloudservices',
				enterMode: CKEDITOR.ENTER_BR,
				shiftEnterMode: 2,    
				extraPlugins: 'notification',
				removePlugins: 'autosave,scayt,wsc',
				disableNativeSpellChecker: false
		});
	}

$(document ).ready(function() {
	$("textarea").focus(function(e){
	e.preventDefault();
		focusElement = this;
		areaclick=$(focusElement).attr("id");
		for(name in CKEDITOR.instances){
		CKEDITOR.instances[name].destroy();
		}

	CKEDITOR.replace(areaclick);

	});		
});

</script>
</html>