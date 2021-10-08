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
include("../common/funcionesvarias.php");
include ("../funciones/fechas.php"); 
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

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

	$sel_resultado="SELECT * FROM `presupuestos`  WHERE `estado` LIKE '1' AND borrado=0 AND fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data1y[$x]=mysqli_num_rows($res_resultado);

	$sel_resultado="SELECT * FROM `presupuestos`  WHERE `estado` LIKE '2' AND borrado=0 AND fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data2y[$x]=mysqli_num_rows($res_resultado);

	$sel_resultado="SELECT * FROM `presupuestos`  WHERE `estado` LIKE '3' AND borrado=0 AND fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data3y[$x]=mysqli_num_rows($res_resultado);

	$sel_resultado="SELECT * FROM `presupuestos`  WHERE `estado` LIKE '4' AND borrado=0 AND fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data4y[$x]=mysqli_num_rows($res_resultado);

	$sel_resultado="SELECT * FROM `presupuestos`  WHERE `estado` LIKE '5' AND borrado=0 AND fecha between '".$startTime."' AND '".$endTime."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	$data5y[$x]=mysqli_num_rows($res_resultado);
	
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
$b3plot = new BarPlot($data3y);
$b4plot = new BarPlot($data4y);
$b5plot = new BarPlot($data5y);

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot,$b4plot,$b5plot));
// ...and add it to the graPH
$graph->Add($gbplot);


$b1plot->SetColor("white");
$b1plot->SetFillColor("#ccc111");
$b1plot->SetLegend("Iniciados");

$b2plot->SetColor("white");
$b2plot->SetFillColor("#111ccc");
$b2plot->SetLegend("Eviados");

$b3plot->SetColor("white");
$b3plot->SetFillColor("#11c1cc");
$b3plot->SetLegend("Rechasados");

$b4plot->SetColor("white");
$b4plot->SetFillColor("#cc11cc");
$b4plot->SetLegend("Aprovados");

$b5plot->SetColor("white");
$b5plot->SetFillColor("#ac2222");
$b5plot->SetLegend("Facturados");


// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.97,"center","bottom");
$graph->legend->SetColumns(3);


$graph->title->Set("Estado presupuestos");

$graph->subtitle->Set($m);
$graph->subtitle->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->subtitle->SetColor('navy');
// Display the graph
$graph->Stroke();
?>  