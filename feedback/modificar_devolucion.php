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

$y=0;
date_default_timezone_set("America/Montevideo"); 

$codfeedback=isset($_GET["codfeedback"]) ? $_GET["codfeedback"] : die('');
$devarray=array();

	$obj = new Consultas('feedback');
	$obj->Select();
    $obj->Where('codfeedback', $codfeedback);

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
    $codhorasestudio = '';
    $hace = 'Formulario feedback';

    logger($oidcontacto, $codhorasestudio, $colaborador, $hace);

$y=0;

$x=0;
$devarray=explode('-', $competencias);
echo $cantformularios=count($devarray);

echo "<br>". $query="SELECT * FROM feedback WHERE codfeedback='$devarray[0]'";
//$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
//		$codformulario= mysqli_result($rs_query, 0, "codformulario");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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
		$objusuarios = new Consultas('usuarios');
		$objusuarios->Select('nombre,apellido');
		$objusuarios->Where('codusuarios', $colaborador);
		$usuarios = $objusuarios->Ejecutar();
		$rowusuarios = $usuarios["datos"][0];
		$nombrecolaborador=$rowusuarios["nombre"]. " - ".$rowusuarios["apellido"];
		$habilito='disabled="disabled"';

		//echo "<br>".$sql_col="SELECT nombre,apellido FROM usuarios WHERE codusuarios='".$colaborador."'";
//		$res_col=	mysqli_query($GLOBALS["___mysqli_ston"], $sql_col);					

	?>
		<input type="hidden" name="colaborador"  value="<?php echo $colaborador;?>" />
		<input type="text"  id="usuario" class="comboGrande" value="<?php echo $nombrecolaborador;?>" <?php echo $habilito;?> />		
		</font></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #1a1a1a; border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="20" align="left" valign=middle bgcolor="#FFFFFF">
		<font face="Calibri">PUESTO:  
	<?php

		$objformularios = new Consultas('formularios');
		$objformularios->Select('descripcion');
		$objformularios->Where('codformulario', $formulario);    
		$objformularios->Where('borrado', '0');    
		$formularios = $objformularios->Ejecutar();
		$rowformularios = $formularios["datos"][0];
		$dias=isset($rowformularios["dias"]) ? $rowformularios["dias"] : 1;
		echo $rowformularios["descripcion"];

		$query_familias="SELECT * FROM formularios WHERE borrado=0 AND codformulario='".$formulario."'";
//		$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);	
//		echo mysqli_result($res_familias, 0, "descripcion");

	?>
</font></td>
		</tr>
	<tr>
		<td style="border-top: 1px solid #1a1a1a; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=9 height="16" align="left" valign=middle bgcolor="#FFFFFF">
		<font face="Calibri">REALIZADO POR: 
	<?php
		$objusuarios = new Consultas('usuarios');
		$objusuarios->Select('nombre,apellido');
		$objusuarios->Where('codusuarios', $codusuarios);
		$usuarios = $objusuarios->Ejecutar();
		$rowusuarios = $usuarios["datos"][0];
		$nombrecolaborador=$rowusuarios["nombre"]. " - ".$rowusuarios["apellido"];
		$habilito='disabled="disabled"';

//	echo	$sql_col="SELECT nombre,apellido FROM usuarios WHERE codusuarios='".$codusuarios."'";
		//$res_col=	mysqli_query($GLOBALS["___mysqli_ston"], $sql_col);

	?>
		<input  name="codusuario" type="hidden" value="<?php echo $codusuarios;?>" /><input value="<?php echo $nombrecolaborador;?>" />&nbsp;el&nbsp;
		<input name="fecha" id="fecha" value="<?php echo implota($fechadevolucion);?>" />
		</font></td>
		</tr>
	<tr>
		<td style="border-bottom: 1px solid #1a1a1a; border-left: 1px solid #000000; border-right: 1px solid #1a1a1a" rowspan=1 height="48" align="center" valign=middle>
		<font face="Calibri" size=3>COMPETENCIAS</font>

			<input name="competencias" type="hidden" value="<?php echo $competencias;?>" />

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

$objformularios = new Consultas('feedbackform');
$objformularios->Select();
$objformularios->Where('codformulario', $codformulario);
$objformularios->Where('competencias', ' ', '<>');
//$objformularios->Where('definicion', ' ', '<>');
//$objformularios->Orden('nivel' , 'DESC');
$objformularios->Orden('fila' , 'ASC');

$paciente = $objformularios->Ejecutar();
echo $paciente['consulta'];
$rows = $paciente["datos"];
$total_rows=$paciente["numfilas"];


//echo "<br>". $sql_feedback="SELECT fila,competencias FROM feedbackform WHERE codformulario=".$codformulario." and competencias<>'' and definicion<>'' ORDER BY fila ASC";
//$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);

$contafeed=0;
if($total_rows>=0){
	foreach($rows as $row){

//while ($contafeed < mysqli_num_rows($res_feedback)) { 
//$fila=mysqli_result($res_feedback, $contafeed, "fila");
echo $fila=$row["fila"];
?>
<td valign="top"><table border="1"><tbody><tr>
<td colspan="<?php echo $cantformularios;?>" align="center">
<font face="Calibri" size="2"> 
<?php
	//echo mysqli_result($res_feedback, $contafeed, "competencias");
	echo $row["competencias"];
?>		
</font>
</td></tr><tr>
<?php
$x=1;
foreach($devarray as $codfeedback){
	
	$objfeedbackform = new Consultas('feedback');
	$objfeedbackform->Select();
	$objfeedbackform->Where('codfeedback', $codfeedback);
	$objfeedbackform->Where('colaborador', $colaborador);
	//$objformularios->Orden('nivel' , 'DESC');
	//	$objformularios->Orden('fila' , 'ASC');
	$feedbackform = $objfeedbackform->Ejecutar();
	echo "<br>".$feedbackform['consulta'];
	$rowsfeedbackform = $feedbackform["datos"];
	//$total_feedbackform=$feedbackform["numfilas"];
	$fecha=$rowsfeedbackform[0]['fecha'];

//echo "<br>".$query="SELECT * FROM feedback WHERE codfeedback='$codfeedback' and colaborador='$colaborador'" ;
//$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
//$fecha=mysqli_result($rs_query, 0, "fecha");


?>
<td valign="top" align="center"><table><tbody><tr><td  align="center">
<?php echo $x;?>º</td></tr>	
<?php
$xi=0;

if(strlen($fecha)>3){
	$objfeedback = new Consultas('feedback');
	$objfeedback->Select();
	$objfeedback->Where('fecha', $fecha);
	$objfeedback->Where('colaborador', $colaborador);
	$objfeedback->Where('fila', $fila);

	//$objformularios->Orden('nivel' , 'DESC');
	//	$objformularios->Orden('fila' , 'ASC');
	$feedback = $objfeedback->Ejecutar();
	$rowsfeedback = $feedback["datos"];
	//$total_feedbackform=$feedbackform["numfilas"];
	//$fecha=$rowsfeedbackform[0]['nivel'];

	echo "<br>".$query="SELECT * FROM feedback WHERE fecha='$fecha' AND colaborador='$colaborador' and fila='$fila'";
	//$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $query);

	$nivel=explode("-",$rowsfeedbackform[0]['nivel']);

	foreach($nivel as $i) {
		//echo $i."<br>";
		if($i==1) {
			break;
		}
		$xi++;
	}
}
 ?>		
	<tr><td  align="center">
		<font face="Calibri" size=2><?php echo $Tipo[$xi];?></font></td></tr></tbody></table>	</td>
<?php 
//}
$x++;
?>

<?php
}
?>
</tr></tbody></table></td>
<?php
$contafeed++;
	}
}
?>
</tr>
</table>
</td>
		</tr>
<?php

$y=0;

$objfeedbackform = new Consultas('feedbackform');
$objfeedbackform->Select();
$objfeedbackform->Where('codformulario', $formulario);

//$objformularios->Orden('nivel' , 'DESC');
$objfeedbackform->Orden('fila' , 'ASC');
$feedbackform = $objfeedbackform->Ejecutar();
$filasfeedbackform = $feedbackform["datos"];

$total_feedbackform=$feedbackform["numfilas"];

//$sql="SELECT * FROM feedbackform WHERE codformulario=".$formulario ." ORDER BY fila ASC";

//$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
include '../class/RandomColor.php';
use \Colors\RandomColor;

		$color=RandomColor::many(18, array('hue'=>'green'));

$contador=0;

if($total_feedbackform>=0){
	foreach($filasfeedbackform as $row){

//while ($contador < mysqli_num_rows($res_resultado)) { 

	$objfeedbackform = new Consultas('feedbackform');
	$objfeedbackform->Select();
	$objfeedbackform->Where('codformulario',$row['codformulario']);
	$objfeedbackform->Where('fila',$row['fila']);
	
	//$objformularios->Orden('nivel' , 'DESC');
	$objfeedbackform->Orden('fila' , 'ASC');
	$feedbackform = $objfeedbackform->Ejecutar();
	$rowsfeedbackform = $feedbackform["datos"];
	
	$total_feedbackform=$feedbackform["numfilas"];
	

		//$sql_feedback="SELECT competencias, definicion,codfeedback FROM feedbackform WHERE codformulario=".mysqli_result($res_resultado, $contador, "codformulario")." AND fila=".(mysqli_result($res_resultado, $contador, "fila"));
		//$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);
		
//$query="SELECT * FROM feedback WHERE fecha='".$fechadevolucion."' AND colaborador='".$colaborador."' and fila='".mysqli_result($res_resultado, $contador, "fila")."'";
//$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
//		$codformulario= mysqli_result($rs_query, 0, "aspectos");		
		
	?>
		<tr>
			<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" colspan=9 height="40" align="center"	 valign=middle 
			bgcolor="<?php echo $color[$y];?>">
			<font face="Calibri" size=3>
	<?php 

	//echo mysqli_result($res_feedback, 0, "definicion");

	?>	 </font></td>
		</tr>
		<tr>
			<td style="border-bottom: 3px solid #101010; border-left: 2px solid #101010; border-right: 2px solid #101010" colspan=9  align="center" style="text-align: left;">
			
			<textarea name="desempenio[<?php echo $contador;?>][<?php echo $x;?>]" id="desempenio<?php echo $y;?>" rows="6" cols="60" style="text-align: left;">
			<?php //echo  mysqli_result($rs_query, 0, "aspectos");?></textarea>		
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
		<input name="fechaproxima" id="fechaproxima" value="<?php echo date("d/m/Y", strtotime ( $fechadevolucion ) );?>" />
						  <img src="../img/calendario.png"  id="fechaproxima1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechaproxima",
						     trigger    : "fechaproxima1",
						     align		 : "Tl",     
						     showTime   : 12,
						     dateFormat : "%d/%m/%Y"
						   });
						</script>			
		</td>

	</tr>
</table><br>
				<div>
						<button id="guardar" class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
			  </div>
					<input id="accion" name="accion" value="modificar" type="hidden">
</form>
</div></div></div><p>&nbsp;</p>
</body>

</html>
