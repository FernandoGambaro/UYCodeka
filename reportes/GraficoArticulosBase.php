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
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 

$codarticulo=$_GET['codarticulo'];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codigobarras=mysqli_result($rs_query, 0, "codigobarras");

?>
<!doctype html>
<html lang="es">
<head>
<meta name="robots" content="noindex,nofollow">
<link rel="copyright" title="GNU General Public License" href="http://www.gnu.org/copyleft/gpl.html#SEC1">
<title>Articulos</title>
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
	<script type="text/javascript" src="../common/flot/jquery.flot.axislabels.js"></script>
	<link rel="stylesheet" href="../css3/styles.css">
</head>

<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
<form name="stats" method="POST" action="#">
<input type="hidden" name="id" value="">
					<table class="fuente8" cellspacing="4" cellpadding="2" border="0"><tr>
					<td valign="top">
					<table class="fuente8" cellspacing="4" cellpadding="2" border="0">										
						<tr>
						<td ><textarea name="Adescripcion" cols="41" rows="5" id="descripcion" class="areaTexto" ><?php echo mysqli_result($rs_query, 0, "descripcion")?></textarea></td>
							<td> <?php if (mysqli_result($rs_query, 0, "imagen")!=""){ ?>   
								<img id="uploadPreview" style="height: 100px; " src="../fotos/<?php echo mysqli_result($rs_query, 0, "imagen");?>" border="0">					  
						 <?php } else { ?>
								<img id="uploadPreview" style="height: 100px; display: none;" />
						 <?php }	 ?>
						<script type="text/javascript">
						
						    function PreviewImage() {
						        var oFReader = new FileReader();
						        oFReader.readAsDataURL(document.getElementById("foto").files[0]);
						
						        oFReader.onload = function (oFREvent) {
						            document.getElementById("uploadPreview").src = oFREvent.target.result;
						            $("#uploadPreview").show();
						        };
						    };
						
						</script>	

								</td>
<td valign="top" >
							<img src="../barcode/gd.php?text=<?php echo $codigobarras;?>&height=50"></td>
					    </tr>
							</table>
					<td valign="top">
					  						
					</td>
					<td>
<table>
			<tr><td>
			<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" 
			onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
			</td>
			</tr>
			</table>					
					</td>
					</tr></table>

</form>
</div>
<br>
<div style="overflow: auto;">
<div class="fichecenter">
<div class="fichehalfleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td>Cantidad de unidades compradas</td>
<td align="right">
<a href="">
<img src="../img/dashboard/refresh.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a>
</td></tr><tr class="impair">
<td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder1" style="width:380px;height:160px;" class="dolgraph"></div>
<script>
var codarticulo=<?php echo $codarticulo;?>;
tipo=1;
		$.ajax({
        type:'post', 
        data: {codarticulo:codarticulo, tipo:tipo},
        url:'../articulos/EstadisticasBarra.php',
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder"+data['tipo'];
				var varLabel="Compras";
				var color="#548200";
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
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
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }		
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
</td></tr></table>
</div>

<div class="fichehalfright">
<div class="ficheaddleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td>Cantidad de unidades vendidas</td><td align="right"><a href="/product/stats/card.php?id=&action=recalcul&mode=byunit&search_year=2017&search_categ=">
<img src="../img/dashboard/refresh.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td></tr><tr class="impair">
<td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder2" style="width:380px;height:160px;" class="dolgraph"></div>
<script >
var codarticulo=<?php echo $codarticulo;?>;
var tipo=2;
		$.ajax({
        type:'post', 
        data: {codarticulo:codarticulo, tipo:tipo},
        url:'../articulos/EstadisticasBarra.php',
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder"+data['tipo'];
				var varLabel="Ventas";
				var color="#5482FF";
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
</script>
</td></tr></table>
</div></div></div>

<div class="clear">
<div class="fichecenter"><br>
</div></div>

<div class="fichecenter"><div class="fichehalfleft">
<table class="noborder" width="100%"><tr class="liste_titre"><td>Importe compras</td>
<td align="right">
<a href="#">
<img src="../img/dashboard/refresh.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td>
</tr><tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder4" style="width:380px;height:160px;" class="dolgraph"></div>
<script >
var codarticulo=<?php echo $codarticulo;?>;
var tipo=3;
		$.ajax({
        type:'post', 
        data: {codarticulo:codarticulo, tipo:tipo},
        url:'../articulos/EstadisticasBarra.php',
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder"+data['tipo'];
				var varLabel="Importe ventas";
				var color="#54FF00";
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
</script>
</td></tr></table>
</div>

<div class="fichehalfright"><div class="ficheaddleft">
<table class="noborder" width="100%"><tr class="liste_titre">
<td>Importe ventas</td><td align="right">
<a href="#"><img src="../img/dashboard/refresh.png" alt="" title="<?php echo date("d/m/Y");?>" class="inline-block valigntextbottom"></a></td></tr>
<tr class="impair"><td colspan="2" class="nohover" align="center"><!-- Build using jflot -->
<div id="placeholder3" style="width:380px;height:160px;" class="dolgraph"></div>
<script >
var codarticulo=<?php echo $codarticulo;?>;
var tipo=4;
		$.ajax({
        type:'post', 
        data: {codarticulo:codarticulo, tipo:tipo},
        url:'../articulos/EstadisticasBarra.php',
			success: function(data) {
				var dat=data['data'];
				var valu=data['valu'];
				var placeholder="#placeholder"+data['tipo'];
				var varLabel="Importe compras";
				var color="#FF8200";
       		graficar(dat, valu,placeholder,varLabel,color);
			},
		});
		
</script>
</td></tr></table>
</div></div></div><div class="clear"><div class="fichecenter"><br></div></div>


<div class="clear"><div class="fichecenter"><br></div></div>

</div>


</div> <!-- End div class="fiche" -->
</div> <!-- End div id-right -->
</div>

</body>
</html>
