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
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

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

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 


$codusuario=isset($_GET["codusuario"]) ? $_GET["codusuario"] : null;
$fecha=isset($_GET["fecha"]) ? explota($_GET["fecha"]): "-";
$nempleado=$_GET["nempleado"] ? $_GET['nempleado']: null;


$where="1=1 AND feedback.borrado=0 ";
if ($codusuario <> "") { $where.=" AND feedback.colaborador='$codusuario'"; }
if ($fecha <> "-") { $where.=" AND feedback.fecha >= '".$fecha."'"; }
if ($nempleado <> "") { $where.=" AND usuarios.nempleado = '".$nempleado."'"; }

?>
<!doctype html>
<html lang="es">
<head>
<meta name="robots" content="noindex,nofollow">
<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
<title>Horas</title>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../js3/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css3/css/font-awesome.min.css">
<script type="text/javascript" src="../js3/jquery.min.js"></script>
<script type="text/javascript" src="../js3/jquery-ui.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="../common/flot/jquery.flot.stack.min.js"></script>

	<link href="../common/flot/examples/examples.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../common/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.resize.min.js"></script>
	<script language="javascript" type="text/javascript" src="../common/flot/jquery.flot.tickrotor.js"></script>
	<script type="text/javascript" src="../common/flot/jquery.flot.axislabels.js"></script>
	<link rel="stylesheet" href="../css3/styles.css">

<script type="text/javascript" >
var datos = new Array();
var codusuario=<?php echo $codusuario;?>;//

</script>
	
</head>

<body>
<div style="overflow: auto;">
<?php
$sql="SELECT codformulario,fecha FROM feedback WHERE ".$where." 
GROUP BY  feedback.codformulario, feedback.colaborador ORDER BY feedback.fecha ASC";
$res_sql=mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$conta=0;
$rowControl=1;
while ($conta < mysqli_num_rows($res_sql)) { 
		$fecha=mysqli_result($res_sql, $conta, "fecha");
		$codformulario=mysqli_result($res_sql, $conta, "codformulario");
?>
<script type="text/javascript" >
datos[<?php echo $codformulario;?>]=new Array();
</script>
<?php
		$sel_resultado="SELECT * FROM feedback WHERE borrado=0 AND  nivel!='' AND colaborador='".$codusuario."' AND fecha = '".$fecha."'";
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);

				$queryias="SELECT * FROM formularios WHERE borrado=0 AND codformulario='".mysqli_result($res_resultado, 0, "codformulario")."'";
				$resfami=mysqli_query($GLOBALS["___mysqli_ston"], $queryias);	
				$evalua=mysqli_result($resfami, 0, "descripcion");						   

		   $contador=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 
$codformulario=mysqli_result($res_resultado, $contador, "codformulario");
$fila=mysqli_result($res_resultado, $contador, "fila");		

		$query_familias="SELECT competencias FROM `feedbackform` WHERE borrado=0 AND codformulario='".$codformulario."' AND  fila='".$fila."'";
		$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);	
		$evaluacion=$evalua." - ".mysqli_result($res_familias, 0, "competencias");		
?>
<div class="clear">
<?php  if ($rowControl % 2) { ?>
<div class="fichecenter">
<?php } ?>
<?php  if ($conta % 2) { ?>
<script type="text/javascript" >
datos[<?php echo $codformulario;?>][<?php echo $fila;?>]='<?php echo $fecha;?>';
</script>
<div class="fichehalfright">
<div class="ficheaddleft">
<table class="noborder" width="100%"><tr class="liste_titre"><td><div id="title<?php echo $codformulario.$fila;?>"><?php echo $evaluacion;?></div></td>
<td align="right"><a href="#">
<img src="../img/xls.png" alt="" title="<?php echo $codformulario.$fila;?>" class="inline-block valigntextbottom"></a></td></tr>
<tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder<?php echo $codformulario.$fila?>" style="width:480px;height:260px;" class="dolgraph"></div>
</td></tr></table>
</div></div>
<?php } else { ?>
<script type="text/javascript" >
datos[<?php echo $codformulario;?>][<?php echo $fila;?>]='<?php echo $fecha;?>';
</script>
<div class="fichehalfleft">
<table class="noborder" width="100%"><tr class="liste_titre"><td><div id="title<?php echo $codformulario.$fila;?>"><?php echo $evaluacion;?></div></td>
<td align="right"><a href="#">
<img src="../img/xls.png" alt="" title="<?php echo $codformulario.$fila;?>" class="inline-block valigntextbottom"></a></td>
</tr><tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder<?php echo $codformulario.$fila;?>" style="width:480px;height:260px;" class="dolgraph"></div>
</td></tr></table>
</div>
<?php
$rowControl++;
 } ?>
<?php  if ($rowControl % 2) { ?>
</div>
<?php } ?>
</div>
<?php
$contador++;
 } 
$conta++;
} 
 ?>

</div>

<script type="text/javascript" >
var fecha;

for (var i = 1; i < datos.length; ++i) {
  for (var j = 1; j < datos[i].length; ++j){
    fecha=datos[i][j];

var codformulario=i;
var fila=j;

		$.ajax({
        type:'post', 
        dataType: 'json',
        data: {codusuario:codusuario, codformulario:codformulario, fila:fila, fecha:fecha},
        url:'EstadisticasEvaluacionUsuario.php',
			success: function(data) {
				var dat=data['data'];
				var valu='';
				var fila=data['fila'];
				var codformulario=data['codformulario'];
				var placeholder="#placeholder"+codformulario+fila;
				var varLabel=valu;
				var color="#548200";
				//$("#title"+codformulario+fila).html(valu);
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
	  }  
}

function graficar(dat, valu,placeholder,varLabel,color) {

Value=$.parseJSON(dat);

var dataset = [ { label: '', data: Value, color: color } ] ;
	$(function() {
		$.plot(placeholder, dataset, {
			series: {
				bars: {
					show: true,
					barWidth: 0.7,
					align: "center",
					horizontal: false,
            lineWidth:1			
				}
			},
			xaxis: {
        		tickDecimals: 0, // the number of decimals to display

            labelWidth: 70,
            labelHeight: 70,
            reserveSpace: true,
				mode: "categories",
				tickLength: 0,		
			},
         yaxis: {
         	tickDecimals: 0,
             axisLabel: varLabel,
             axisLabelUseCanvas: true,
             axisLabelFontSizePixels: 12,
             axisLabelFontFamily: 'Verdana, Arial',
             axisLabelPadding: 3,
             tickFormatter: function (v, axis) {
                 return v ;
         	}
         },			
         grid: {
             hoverable: true,
             borderWidth: 2,
             backgroundColor: { colors: ["#D1D1D1", "#7A7A7A"] },
         },
			legend:{         
            backgroundOpacity: 0.5,
            noColumns: 2,
            //container : $("#leyenda"),  
        },         		
		});
 		$(placeholder).UseTooltip();
	});
}


        var previousPoint = null, previousLabel = null;
 
        $.fn.UseTooltip = function () {
            $(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong>");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                width:'200px',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }

</script>

</body>
</html>