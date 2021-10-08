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
include ("../common/funcionesvarias.php");
include ("../funciones/fechas.php"); 
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

		$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden =1 ORDER BY orden ASC";
	   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
	   $con_monedas=0;
	   $moneda = '';
	 while ($con_monedas < mysqli_num_rows($res_monedas)) {
	 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
	 	 $moneda = $descripcion[0];
	 	 $con_monedas++;

	 }	

$endTime=date('Y-m-d');
$startTime= date ('Y-m-d', strtotime('-6 month')) ;

$m=genMonthAb_Text(date('m', strtotime($startTime))) . '/' .  date('Y',strtotime($startTime)) . ' - '. genMonthAb_Text(date('m',strtotime($endTime))). '/'. date('Y', strtotime($endTime));

$fechafi=strtotime($endTime);
$fechain=strtotime($startTime);
$x=0;
$data1y=[];
$data2y=[];
$total=0;
$tipocambio=1;
$totalfactura=0;

while ( $fechain <= $fechafi ) {
	$fechain=date ('Y-m-d', $fechain);
	$xtitle[$x]=genMonthAb_Text(date('m', strtotime($fechain)));

	$startTime =data_first_month_day($fechain); 
	$endTime = data_last_month_day($fechain);
	
			$sel_resultado="SELECT *	FROM facturas WHERE facturas.borrado=0  AND  fecha between '".$startTime."' AND '".$endTime."'";
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   $total=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 

					 if (mysqli_result($res_resultado, $contador, "moneda")==1){
					 $total+=mysqli_result($res_resultado, $contador, "totalfactura");		 
					 } else {
						$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime(mysqli_result($res_resultado, $contador, "fecha"))));
			   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."' limit 0";
			   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
			   		while ($row=mysqli_fetch_array($res_tipocambio)) {
			   			$tipocambio=$row['valor'];
			   		}					 	
					$total+=mysqli_result($res_resultado, $contador, "totalfactura")*$tipocambio;
					 }
			$contador++;		
			}
			$data1y[$x]=$total/1000;
			
/*Calculo importe total compras */

			$sel_resultado="SELECT  fecha,moneda, totalfactura	FROM facturasp WHERE borrado=0  AND  fecha between '".$startTime."' AND '".$endTime."'";

			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		    $total=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 
			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime(mysqli_result($res_resultado, $contador, "fecha"))));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."'";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
   		while ($row=mysqli_fetch_array($res_tipocambio)) {
   			$tipocambio=$row['valor'];
   		}
			/*Importe*/
					 $totalfactura= mysqli_result($res_resultado, $contador, "totalfactura");
					 
					 if (mysqli_result($res_resultado, $contador, "moneda")==1){
					 $total+=$totalfactura;		 
					 } else {
					$total+=$totalfactura*$tipocambio;
					 }
					
			$contador++;		
			}
			$data2y[$x]=$total/1000;
			
	$nuevafecha = strtotime ( '+1 month' , strtotime ( $fechain ) ) ;
	$fecha = date ( 'Y-m-j' , $nuevafecha );
	$fechain=strtotime($fecha);
	$x++;
}
graficar($data1y, $data2y, $xtitle, $m,$moneda);

function graficar($data1y, $data2y, $xtitle, $m) {
include (realpath(dirname(__FILE__)).'/../jpgraph/jpgraph.php');
include (realpath(dirname(__FILE__)).'/../jpgraph/jpgraph_bar.php');

// Create the graph. These two calls are always required
$graph = new Graph(350,200,'auto');
$graph->img->SetImgFormat("png");
$graph->SetScale("textlin");
 
//$graph->SetMargin(225,0,20,30);
//$graph->SetShadow();
//$graph->img->SetMargin(140,3,25,40);

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

//$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($xtitle);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->yaxis->scale->SetGrace(10,0);
$graph -> yaxis -> title -> set("Valores x 1000");



//$graph->xaxis->title->Set("Expresado en ".$moneda);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
$gbplot->SetWidth(0.7);
// ...and add it to the graPH
$graph->Add($gbplot);


$b1plot->SetColor("white");
$b1plot->SetFillColor("#ccc111");
$b1plot->SetLegend("Ventas");

$b2plot->SetColor("white");
$b2plot->SetFillColor("#111ccc");
$b2plot->SetLegend("Compras");

// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.97,"center","bottom");
$graph->legend->SetColumns(2);

$graph->title->Set("Ventas vs Compras");

$graph->subtitle->Set($m);
$graph->subtitle->SetFont(FF_VERDANA,FS_NORMAL,8);
$graph->subtitle->SetColor('navy');
// Display the graph
$graph->Stroke();

}
?>  