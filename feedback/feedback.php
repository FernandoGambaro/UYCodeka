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

date_default_timezone_set("America/Montevideo"); 
$codformulario=isset($_GET["codformulario"]) ? $_GET["codformulario"] : null;
if(strlen($codformulario)<=0){
	$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : die('ERROR! Fecha no encontrada!');
	$colaborador = isset($_GET['colaborador']) ? $_GET['colaborador'] : die('ERROR! colaborador no encontrado!');
}
//$codformulario=1;
$y=0;

if(strlen($codformulario)<=0){

	$obj = new Consultas('feedback');
	$obj->Select('feedback.aspectos, feedback.nivel, feedback.fila, feedback.codformulario, feedback.colaborador, feedback.fecha, feedback.fechaproxima, feedback.fechafeedback ');
	$obj->Join('codformulario', 'formularios', 'INNER', 'feedback', 'codformulario');

    $obj->Where('tipo', '0', '=', '', '', 'formularios');
    $obj->Where('fecha', $fecha, '=', '', '', 'feedback');
	$obj->Where('colaborador', $colaborador, '=', '', '', 'feedback');
	$obj->Orden('fila' , 'ASC');

	$paciente = $obj->Ejecutar();
	$rows = $paciente["datos"];

	$total_rows=$paciente["numfilas"];
}
    $codhorasestudio = '';
    $hace = 'Formulario feedback';

    logger($oidcontacto, $codhorasestudio, $colaborador, $hace);
    


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
				showToast('<?php echo 'Error, evaluación ya realizada:';?>', 'error');
	        $("#guardar").hide();
	        }else {
	        	//var fecha=$("#fecha").val();
				var fechaproxima = data.fechaproxima;
					var mDate = new Date($("#fecha").val());
					var mEpoch = mDate.getTime();	    
					var mate = new Date(fechaproxima);
					var mpoch = mate.getTime();	    
	        		if (mpoch>mEpoch) {
						showToast('<?php echo 'Evaluación fuera de fecha, ajuste fechas de la próxima:';?>', 'error');
	        		}
				var fechafeedback = data.fechafeedback;
				if (fechafeedback!==false && fechafeedback!=="" && fechafeedback!=="0000-00-00") {				
				$("#fechafeedback").val(fechafeedback);
				}
	       	$("#guardar").show();
	        }
		},
	      error: function(e){
			showToast('<?php echo 'Error, intente mas tarde:';?>', 'error');
	      }
	      }); 	
		}
	
}

 function validar() {
 	event.preventDefault();
 	var cuento=0;
 	if ($("#codusuario").val()=='') {
		showToast('<?php echo 'Debe seleccionar usuario.';?>', 'error');
    	return false;
 	}
 	$("input:radio:checked").each(function() {
        cuento=cuento+1;
    });
    if (cuento==0) {
		showToast('<?php echo 'Error, Complete el formulario:';?>', 'error');		
		return false;
    }else {
		document.getElementById("formulario").submit();
 	}   	
}
</script>

  
<link href="../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../library/jquery-ui/jquery-ui.js"></script>


<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span >" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};

 $(document).ready(function () {
     
$('#opcionesaexcel').prop( "checked", false );
$('#opcionesaexcel').attr("disabled", true);
    
    $("#Ausuarios").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
			$("#codusuario").val(pref);
			$("#Ausuarios").val(nombre);
		}
	}).autocomplete("widget").addClass("fixed-height");

});
</script>		

</head>

<body>
	<div id="pagina">	
		<div align="center">
		<div id="frmBusqueda">
		<form name="formulario" id="formulario" action="guardar_feedback_form.php" method="post">
		<input type="hidden" name="codformulario"  value="<?php echo $codformulario;?>" />

	<table cellspacing="0" border="2">
		<colgroup width="255"></colgroup>
		<colgroup span="5" width="76"></colgroup>
		<colgroup width="115"></colgroup>
		<colgroup width="253"></colgroup>
		<colgroup width="20"></colgroup>
	<tr>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" height="20" align="left" bgcolor="#FFFFFF">
		<font face="Calibri">COLABORADOR: </font>
		<?php
			if(strlen($codformulario)<=0){
				$colaborador=$rows[0]["colaborador"];
				$objusuarios = new Consultas('usuarios');
				$objusuarios->Select('nombre,apellido');
				$objusuarios->Where('codusuarios', $rows[0]["colaborador"]);    
				$usuarios = $objusuarios->Ejecutar();
				$rowusuarios = $usuarios["datos"][0];
				$nombrecolaborador=$rowusuarios["nombre"]. " - ".$rowusuarios["apellido"];
				$habilito='disabled="disabled"';
			}else{
				$colaborador='';
				$nombrecolaborador='';
			}
		?>
		<input type="hidden" name="colaborador" id="codusuario" value="<?php echo $colaborador;?>" />
		<input type="text" id="Ausuarios" class="comboGrande" value="<?php echo $nombrecolaborador;?>" <?php echo $habilito;?> autocomplete="disable" /></td>
		<td style="border-top: 2px solid #101010; border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 2px solid #101010" colspan=8 rowspan=3 align="center" 
		valign=middle bgcolor="#f5f5f5"><b>
		<font face="Calibri" size=5>SEGUIMIENTO SEMANAL PARA LA GESTIÓN DEL DESEMPEÑO</font></b></td>
		</tr>
	<tr>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" height="20" align="left" bgcolor="#FFFFFF">
		<font face="Calibri">PUESTO: 
	<?php
		if(strlen($codformulario)<=0){
			$codformula=$rows[0]["codformulario"];
			$fechaalta=implota($rows[0]["fecha"]);
		}else{
			$codformula=$codformulario;
			$fechaalta=date("d/m/Y");
		}
		$objformularios = new Consultas('formularios');
		$objformularios->Select('descripcion');
		$objformularios->Where('codformulario', $codformula);    
		$formularios = $objformularios->Ejecutar();
		$rowformularios = $formularios["datos"][0];
			$dias=isset($rowformularios["dias"]) ? $rowformularios["dias"] : 1;
			echo $rowformularios["descripcion"];
	?>		
		</font></td>
	</tr>
	<tr>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" height="20" align="left" bgcolor="#FFFFFF">
		<font face="Calibri">FECHA: </font><input name="fecha" id="fecha" value="<?php echo $fechaalta;?>"  /></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" height="21" align="center">
		<font face="Calibri" size=3>COMPETENCIAS A EVALUAR</font></td>
		<td style="border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" colspan=6 align="center">
		<font face="Calibri" size=3>DEFINICIÓN</font></td>
		<td style="border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" align="center">
		<font face="Calibri" size=3>NIVEL DE DESARROLLO</font></td>
		<td style="border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 2px solid #101010" align="center">
		<font face="Calibri" size=3><br></font></td>
	</tr>
	<?php
	
	if(strlen($codformulario)>0){
		$objformularios = new Consultas('feedbackform');
		$objformularios->Select();
		$objformularios->Where('codformulario', $codformula);
		$objformularios->Where('borrado', '0');
		//$objformularios->Orden('nivel' , 'DESC');
		$objformularios->Orden('fila' , 'ASC');

		$paciente = $objformularios->Ejecutar();
		$rows = $paciente["datos"];
	
		$total_rows=$paciente["numfilas"];
		$fechaproxima=date("d/m/Y", strtotime ( '+'.$dias.' day' , strtotime ( date('Y-m-j') ) ));
		$fechafeedback=date("d/m/Y", strtotime ( '+'.($dias*4).' day' , strtotime ( date('Y-m-j') ) ));
	}else{
		$fechaproxima=implota($rows[0]["fechaproxima"]);
		$fechafeedback=implota($rows[0]["fechafeedback"]);
	}

$contador=0;

if($total_rows>=0){
	foreach($rows as $row){
		$continuar=false;
		//echo "<br>".$row["nivel"];
		if(strlen($codformulario)<=0){
			if($row["nivel"]!='' and strlen($row["aspectos"])<=0 ) {
			$continuar=true;
			}
		}else{
			if(strlen($row["competencias"])>0 and strlen($row["definicion"])>0){
				$continuar=true;
			}
		}

	if($continuar==true ) {
		if(strlen($codformulario)<=0){
			$objfeedback = new Consultas('feedbackform');
			$objfeedback->Select('competencias, definicion');
			$objfeedback->Where('codformulario', $rows[0]["codformulario"]);    
			$objfeedback->Where('fila', $row["fila"]);  
			$objfeedback->Orden('fila' , 'ASC');
	
			$feedback = $objfeedback->Ejecutar();
			$rowfeedback = $feedback["datos"][0];
			$competencias=$rowfeedback["competencias"];
			$definicion=$rowfeedback["definicion"];
		}else{
			$competencias=$row["competencias"];
			$definicion=$row["definicion"];	
		}
	?>
	<tr>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" rowspan=4 height="84" align="center" valign=middle>
		<font face="Calibri" size=3><?php echo $competencias;?>	</font></td>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" colspan=6 rowspan=4 align="center" valign=middle bgcolor="#FFFFFF">
		<font face="Calibri" size=3 ><?php echo $definicion;?></font></td>
		<?php
		$nivel=explode("-",$row["nivel"]);
		$x=0;
		$Tipo = array(
		1=>"Medio",
		2=>"Alto",
		3=>"Destacado");
		?>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" align="center">
		<font face="Calibri" size=3>Bajo</font></td>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 2px solid #101010" align="left"><font face="Calibri" size=3>
		
		<?php 	if ( $nivel[$x]!=0) {	?>
		<input type="radio" name="DATA[<?php echo $contador;?>][]" value="<?php echo $x;?>" checked="checked" /></input>
		<?php } else { ?>
		<input type="radio" name="DATA[<?php echo $contador;?>][]" value="<?php echo $x;?>" /></input>
		<?php } ?>
		</font></td>
	</tr>
		<?php
		$x++;
		foreach($Tipo as $i) {
		?>
		<tr>
			<td style="border-top: 1px solid #101010; border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" align="center">
			<font face="Calibri" size=3><?php echo $i;?></font></td>
			<td style="border-top: 1px solid #101010; border-bottom: 1px solid #101010; border-left: 1px solid #101010; border-right: 2px solid #101010" align="left"><font face="Calibri" size=3>
			<?php if ( $nivel[$x]!=0) {	?>
			<input type="radio" name="DATA[<?php echo $contador;?>][]" value="<?php echo $x;?>" checked="checked" /></input>
			<?php } else { ?>
			<input type="radio" name="DATA[<?php echo $contador;?>][]" value="<?php echo $x;?>" /></input>
			<?php } ?>
			</font></td>				
			<?php $x++; } ?>
		</tr>
		<?php
	} else {
		$color=array(0=>"#00FF00",1=>"#FFCC99", 2=>"#CC99FF");

		if(strlen($codformulario)<=0){
			$objfeedback = new Consultas('feedbackform');
			$objfeedback->Select('competencias, definicion');
			$objfeedback->Where('codformulario', $rows[0]["codformulario"]);    
			$objfeedback->Where('fila', $row["fila"]);  
			$objfeedback->Orden('fila' , 'ASC');
	
			$feedback = $objfeedback->Ejecutar();
			$rowfeedback = $feedback["datos"][0];

			$competencias=$rowfeedback["competencias"];
			$definicion=$rowfeedback["definicion"];
		}else{
			$competencias=$row["competencias"];
			$definicion=$row["definicion"];
		}
		?>
		<tr>
			<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 1px solid #101010; border-right: 1px solid #101010" colspan=9 height="40" align="center"	 valign=middle 
			bgcolor="<?php echo $color[$y];?>">
			<font face="Calibri">
		<?php 
		if($competencias!='') {
		echo $competencias;
		} else { 
		echo $definicion;
		}
		?>	 </font></td>
			</tr>
			<tr>
				<td style="border-bottom: 3px solid #101010; border-left: 2px solid #101010; border-right: 2px solid #101010" colspan=9  align="center">
				<textarea class="myeditablediv" name="desempenio[<?php echo $contador;?>][<?php echo $x;?>]" id="desempenio<?php echo $y;?>" rows="6" cols="60">
				<?php echo $row['aspectos'];?></textarea>		
				</td>
			</tr>
		<?php	
		$y++;
		}
	$contador++;
	}
}
?>	
	<tr>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" colspan=5 height="26" align="left" valign=middle>
		<font face="Calibri">PRÓXIMA EVALUACIÓN: </font>
		<div class="col-xs-3">
			<div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2">
				<input placeholder="Fecha fin" class="form-control input-sm" size="10" type="text" value="<?php echo $fechaproxima;?>" name="fechaproxima" id="fechaproxima" readonly>
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
			</div>
		</div>
		</td>
		<td style="border-top: 3px solid #101010; border-bottom: 2px solid #101010; border-left: 2px solid #101010; border-right: 1px solid #101010" colspan=4 height="26" align="left" valign=middle>
		<font face="Calibri">PRÓXIMA REUNIÓN FEEDBACK: </font>
		<div class="col-xs-5">
			<div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2">
				<input placeholder="Fecha fin" class="form-control input-sm" size="10" type="text" value="<?php echo $fechafeedback;?>" name="fechafeedback" id="fechafeedback" readonly>
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
			<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
			</div>
		</div>
		</td>
	</tr>
</table>
		<div>
			<button class="boletin" onClick="validar();" ><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
			<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" ><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
		</div>
<?php	if(strlen($codformulario)<=0){ ?>
		<input id="accion" name="accion" value="modificar" type="hidden">
<?php }else{ ?>
		<input id="accion" name="accion" value="alta" type="hidden">
<?php } ?>
		</form>
</div></div></div>

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
<script type="text/javascript">
 	$('.form_date').datetimepicker({
        minView: 2, pickTime: false,
        format: 'dd/mm/yyyy',
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
        forceParse: 0,
    });
</script>
</html>