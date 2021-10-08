<?php // content="text/plain; charset=utf-8"

header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache");
date_default_timezone_set('America/Montevideo');

include ("../conectar.php");
include ("../funciones/fechas.php");

$startTime=date('Y-m-d');

$endTime = date ('Y-m-d', strtotime('-6 month')) ;
//$endTime = date ( 'Y-m-j' , $endTime );


$m=genMonthAb_Text(date('m', strtotime($endTime))) . '/' .  date('Y',strtotime($endTime)) . ' - '. genMonthAb_Text(date('m',strtotime($startTime))). '/'. date('Y', strtotime($startTime));

$fechain=strtotime($endTime);
$fechafi=strtotime($startTime);
$x=0;
while ( $fechain <= $fechafi ) {

	$fechain=date ('Y-m-d', $fechain);
	$xtitle[$x]=genMonthAb_Text(date('m', strtotime($fechain)));

	$startTime =data_first_month_day($fechain); 
	$endTime = data_last_month_day($fechain);
	$sel_resultado="SELECT codfactura FROM facturas WHERE facturas.borrado=0 AND facturas.fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data1y[$x]=mysqli_num_rows($res_resultado);

	$sel_resultado="SELECT codfactura FROM `cobros`  WHERE fechacobro between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data2y[$x]=mysqli_num_rows($res_resultado);

	
	$nuevafecha = strtotime ( '+1 month' , strtotime ( $fechain ) ) ;
	$fecha = date ( 'Y-m-j' , $nuevafecha );



	$fechain=strtotime($fecha);
	$x++;
}



require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');



// Create the graph. These two calls are always required
$graph = new Graph(350,200,'auto');
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

/*
$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
$graph->SetBox(false);
*/

$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($xtitle);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
 
// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
// ...and add it to the graPH
$graph->Add($gbplot);


$b1plot->SetColor("white");
$b1plot->SetFillColor("#cc1111");
$b1plot->SetLegend("Facturas");

$b2plot->SetColor("white");
$b2plot->SetFillColor("#1111cc");
$b2plot->SetLegend("Cobros");

$graph->title->Set("Facturas/Cobros emitidos");

$graph->subtitle->Set($m);
$graph->subtitle->SetFont(FF_VERDANA,FS_NORMAL,9);
$graph->subtitle->SetColor('navy');
// Display the graph
$graph->Stroke();
?>  