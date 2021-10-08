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
require_once __DIR__ .'/../../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;
$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

// set page headers
$page_title = "Nuevo Detalles de horas";

require_once '../../common/fechas.php';   
require_once '../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$codhorasestudio = '';
$codhoraspaciente = '';
$hace = 'Detalles de horas';

logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);

$obj = new Consultas('horas');


if(strlen($_GET["codcliente"])>0){ $codcliente=$_GET["codcliente"]; } else { $codcliente="0"; }
if(strlen($_GET["codusuario"])>0) { $codusuario=$_GET["codusuario"]; } else { $codusuario="0";}
if(strlen($_GET["fechaini"])>0){ $fechaini=trim($_GET["fechaini"]);}else{ $fechaini=date("d/m/Y");}
if(strlen($_GET["fechafin"])>0){ $fechafin=trim($_GET["fechafin"]);}else{ $fechafin=date("d/m/Y");}

$anio=date("Y", strtotime(explota($fechaini)));


?>
<!doctype html>
<html lang="es">
<head>
<meta name="robots" content="noindex,nofollow">
<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
<title>Horas</title>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../../library/bootstrap/bootstrap.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <script src="../../library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../library/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

<script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">

<link href="../../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../../library/jquery-ui/jquery-ui.js"></script>

<script type="text/javascript" src="../../library/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="../../library/flot/jquery.flot.pie.min.js"></script>
<script type="text/javascript" src="../../library/flot/jquery.flot.stack.min.js"></script>

<link href="../../library/flot/examples/examples.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../common/flot/excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.resize.min.js"></script>
	<script type="text/javascript" src="../../library/flot/jquery.flot.axislabels.js"></script>

	<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.stack.js"></script>

	<script src="https://envato.stammtec.de/themeforest/melon/plugins/flot/jquery.flot.tooltip.min.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">
<style>
#flotTip {
    padding: 3px 5px;
    background-color: #000;
    z-index: 100;
    color: #fff;
    opacity: .80;
    filter: alpha(opacity=85);
}
</style>

<script type="text/javascript">
//<!--
function ClickHereToPrint(){
	var x = parent.document.getElementsByClassName("cboxIframe");
		window.print(x[0].innerHTML);
}
//-->
</script>
<script language="javascript">

var codcliente=<?php echo $codcliente;?>;
var codusuario=<?php echo $codusuario;?>;
var anio=<?php echo $anio;?>;
</script>


</head>
<body>

<!-- container -->
<div class="container" id="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="titleA">Total de horas por mes</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="row">
				<div class="col-xs-10">
					<div id="titleA">Visión general</div>
				</div>
				<div class="col-xs-2">
					<div id="print_btn">
					<a href="#" onclick="ClickHereToPrint();" style="cursor: pointer;">
					<span class="print_btn">
						Imprimir
					</span>
					</a>
					</div> 
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12"><!-- Build using jflot -->
					<div id="placeholder" style="width:380px;height:260px;" class="dolgraph"></div>
<script>



tipo=1;

if (codcliente!='0' && codusuario=='') {
	var Barurl='EstadisticasCliente.php';
		$.ajax({
        type:'post', 
        data: {codcliente:codcliente, tipo:tipo, anio:anio},
        url:Barurl,
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder";
				var varLabel=valu;
				var color="#548200";
				$("#titleA").html("Total de horas por mes: "+valu);
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
			
} else {
	var Barurl='EstadisticasUsuario.php';

		$.ajax({
        type:'post', 
        data: {codusuario:codusuario, codcliente:codcliente, tipo:tipo, anio:anio},
        url:Barurl,
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder";
				var varLabel=valu;
				var color="#548200";
				
				$("#titleA").html("Total de horas del usuario: "+valu);
       		plotWithOptions(dat, valu,placeholder,varLabel,color);
			},
		});	
		
}

	function plotWithOptions(dat, valu,placeholder,varLabel,color) {

	var stack = true,
		bars = true,
		lines = false,
		steps = true;
		var data=$.parseJSON(dat);

    var options = {
		series: {
			stack: stack,
			lines: {
				show: lines,
				fill: true,
				steps: steps
			},
			bars: {
				show: bars,
				barWidth: 0.6
			}
		},				
        xaxis: {
			ticks: [[1,'Ene'], [2,'Feb'], [3,'Mar'], [4,'Abr'], [5,'May'], [6,'Jun'], [7,'Jul'], [8,'Ago'], [9,'Set'], [10,'Oct'],[11,'Nov'],[12,'Dic']],
			tickLength: 10,
			minTickSize: 1,
		},
		yaxis: {
            autoScale:"exact"
		},		
        legend:{         
            backgroundOpacity: 0.5,
            noColumns: 2,
            container : $("#leyenda"),  

		},		
        grid: {
             hoverable: true,
             borderWidth: 2,
        },
		tooltip: true,
		tooltipOpts: {
			cssClass: "flotTip",
			content: "%s",
			shifts: {
				x: 20,
				y: 0
			},
			defaultTheme: false
    	},		
	};
	

		
		$.plot(placeholder, data, options);
		
	}

		
function graficar(dat, valu,placeholder,varLabel,color) {

Value=$.parseJSON(dat);


var dataset = [ { label: '', data: Value, color: color } ] ;
	$(function() {
		$.plot(placeholder, dataset, {
		series: {
			bars: {
				show: true,
				barWidth: 0.5,
				align: "center"
			}
		},
		xaxis: {
			mode: "categories",
			tickLength: 0
		},
        yaxis: {
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
            container : $("#leyenda"),  
		},
		tooltip: true,
		tooltipOpts: {
			cssClass: "flotTip",
			content: "%s",
			shifts: {
				x: 20,
				y: 0
			},
			defaultTheme: false
    	},            		
		});
 		$(placeholder).UseTooltip();
	});
}

var previousPoint = null,
    previousLabel = null;

function showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
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

                        //console.log(item.series.xaxis.ticks[x].label);               

                        showTooltip(item.pageX, item.pageY,
                        color,
                        "<strong>"+ item.series.xaxis.ticks[x-1].label + "-" + item.series.label + "</strong><br>");
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
		};
		
		
		
</script>

</div><!-- Cierro columna -->
		</div><!-- Cierro Fila -->
	</div><!-- Cierro columna -->
	<div class="col-xs-6">
		<div class="row">
			<div class="col-xs-10">
				<div id="titleB">Detalles</div>
				</div>
				<div class="col-xs-2">
				</div>
		</div>
		<div class="row">
			<div class="col-xs-12"><!-- Build using jflot -->
				<div id="leyenda" style="width:100%;height:160px;" class="dolgraph"></div>
			</div><!-- Cierro columna -->
		</div><!-- Cierro Fila -->
	</div><!-- Cierro columna -->
	</div><!-- Cierro Fila -->

	<?php
$x=1; 
while($x<= 12) {
?>

<div class="row">
	<div class="col-xs-6">
		<div class="row">
			<div class="col-xs-10">
				<div id="title<?php echo $x;?>"></div>
			</div>
			<div class="col-xs-2">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2"><!-- Build using jflot -->
				<div id="placeholder<?php echo $x;?>" style="width:480px;height:260px;" class="dolgraph"></div>
				<?php $x++;?>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="row">
			<div class="col-xs-10">
				<div id="title<?php echo $x;?>"></div>
			</div>
			<div class="col-xs-2">

			</div>
		</div>
		<div class="row">
			<div class="col-xs-2"><!-- Build using jflot -->
				<div id="placeholder<?php echo $x?>" style="width:480px;height:260px;" class="dolgraph">
			</div>
			</div>
		</div>
	</div>
</div>

<?php
$x++;
 } ?>

<script language="javascript" type="text/javascript" src="../../library/flot/jquery.flot.pie.js"></script>
<script type="text/javascript" >

for (var i = 1; i <= 12; i++){

var mes=i;

		$.ajax({
        type:'post', 
        dataType: 'json',
        data: {codusuario:codusuario, codcliente:codcliente, mes:mes, anio:anio},
        url:'EstadisticasTortaUsuario.php',
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var control=data['control'];
				var mes=data['mes'];
				$("#title"+mes).html(data['mesTxt']);
				if (control==1) {
       		graficar2(dat,valu,mes);
				}
			},
		});
		
}


function graficar2(dat, valu, mes) {

Value=$.parseJSON(dat);
//alert(Value);
var placeholder = $("#placeholder"+mes);

	placeholder.unbind();


	$.plot(placeholder, Value, {
		series: {
			pie: { 
				show: true,
				radius:.9,
				label: {
					show: false,
					formatter: labelFormatter,
					background: {
						opacity: 0.5,
						color: '#000'
					}
				}				
			}
		},
		legend: {
			show: true,
			noColumns: 1,
		},
		grid: {
			hoverable: true,
			clickable: true
		},
		tooltip: true,
		tooltipOpts: {
			cssClass: "flotTip",
			content: "%s",
			shifts: {
				x: 20,
				y: 0
			},
			defaultTheme: false
    	},		

	});
			placeholder.bind("plotclick", function(event, pos, obj) {

				if (!obj) {
					return;
				}

				percent = parseFloat(obj.series.percent).toFixed(2);
				showToast(obj.series.label+ ": ->" + percent + "%", 'success');
			});		
}


	function labelFormatter(label, series) {
		return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
	}

</script>


</body>
</html>