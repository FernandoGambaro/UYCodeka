<?php // content="text/plain; charset=utf-8"

//require_once ('jpgraph/src/jpgraph.php');
//require_once ('jpgraph/src/jpgraph_log.php');
//require_once ('jpgraph/src/jpgraph_bar.php');

include ("../conectar.php");
header('Content-Type: text/html; charset=UTF-8'); 
require("../funciones/funcionesvarias.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');
include ("../funciones/fechas.php");


if (""==@$_GET['anio']) {
$anio=date('Y');
} else {
$anio=@$_GET['anio'];
}
if (""==@$_GET['mes']) {
$mes=date('m');
} else {
$mes=@$_GET['mes'];
}

//$SubTitle="error ".mes($mes);
$primer_dia="".$anio."-".$mes."-01";
$ultimo_dia="".$anio."-".$mes."-".date('t');


$codcliente=@$_GET["codcliente"];
$codusuarios=@$_GET["codusuarios"];

$fechafin=@$_GET["fechafin"];
$fechainicio=@$_GET["fechainicio"];
$proyecto=@$_GET['proyecto'];

$where="AND 1=1";
$wherec="";
if ($codcliente <> "") { $wherec=" AND codcliente='".$codcliente."'";
	$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
	$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
	$SubTitle= " ". mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido")." - ";
 }
if ($codusuarios <> "") { $where.=" AND codusuarios = '".$codusuarios."' ";
	$query_bus="SELECT * FROM usuarios WHERE codusuarios='".$codusuarios."'";
	$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
	$SubTitle.= " / ". mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido")." - ";
 }

if ($fechainicio <> "") { $where.=" AND fecha >= '".explota($fechainicio)."'"; }
if ($fechafin <> "") { $where.=" AND fecha <= '".explota($fechafin)."'"; }


$dividefecha = explode("-", explota($fechainicio));
$dividefecha1 = explode("-", explota($fechafin));
 
// $dividefecha[0] = Mes
// $dividefecha[1] = Dia
// $dividefecha[2] = Ano
 
$fecha_previa = mktime(0, 0, $dividefecha[0], $dividefecha[1], $dividefecha[2]); //Convertimos $fecha_desde en formato timestamp
$fecha_hasta = mktime(0, 0, $dividefecha1[0], $dividefecha1[1], $dividefecha1[2]); //Convertimos $fecha_desde en formato timestamp
 
$segundos = $fecha_previa - $fecha_hasta; // Obtenemos los segundos entre esas dos fechas
$segundos = abs($segundos); //en caso de errores
 
$semanas = floor($segundos / 604800); //Obtenemos las semanas entre esas fechas.

if($semanas==0) { 
$semanas=1;
}


if(date("m",strtotime(explota($fechainicio)))==date("m",strtotime(explota($fechafin))) and date("Y",strtotime(explota($fechainicio)))==date("Y",strtotime(explota($fechafin)))) {
	@$mes=date('m',strtotime(explota($fechainicio)));
	@$SubTitle.=" ".mes($mes)." ".date("Y",strtotime(explota($fechafin)));
} elseif(date("m",strtotime(explota($fechainicio)))!=date("m",strtotime(explota($fechafin))) and date("Y",strtotime(explota($fechainicio)))==date("Y",strtotime(explota($fechafin)))) {
	@$mes=date('m',strtotime(explota($fechainicio)));
	@$mesfin=date('m',strtotime(explota($fechafin)));
	@$SubTitle.=" ".mes($mes)." - ".mes($mesfin)." ".date("Y",strtotime(explota($fechafin)));
	
} elseif(date("m",strtotime(explota($fechainicio)))!=date("m",strtotime(explota($fechafin))) and date("Y",strtotime(explota($fechainicio)))!=date("Y",strtotime(explota($fechafin)))) {
	@$mes=date('m',strtotime(explota($fechainicio)));
	@$mesfin=date('m',strtotime(explota($fechafin)));
	@$SubTitle.=" ".mes($mes)." ".date("Y",strtotime(explota($fechainicio)))." - ".mes($mesfin)." ".date("Y",strtotime(explota($fechafin)));
	
}


function SumaHoras($hora1,$hora2) {
$hora1=explode(":",$hora1);
$hora2=explode(":",$hora2);
$horas=@(int)$hora1[0]+@(int)$hora2[0];
$minutos=@(int)$hora1[1]+@(int)$hora2[1];
$segundos=@(int)$hora1[2]+@(int)$hora2[2];
$horas+=@(int)($minutos/60);
$minutos=@(int)($minutos%60)+@(int)($segundos/60);
$segundos=@(int)($segundos%60);
return (intval($horas)< 10 ? '0'.intval($horas):intval($horas)).':'.($minutos < 10 ?'0'.$minutos:$minutos).':'.($segundos < 10 ? '0'.$segundos:$segundos);
}


function time2seconds($time='00:00:00')
{
    list($hours, $mins, $secs) = explode(':', $time);
    return ($hours * 3600 ) + ($mins * 60 ) + $secs;
}

$Pinto=array('', 'aliceblue','lightsalmon','antiquewhite','lightseagreen','aqua','lightskyblue','aquamarine','lightslategray','azure','lightsteelblue','beige','lightyellow','bisque','lime','black','limegreen','blanchedalmond','linen','blue','magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue','cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen','coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise','mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose','darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki','oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange','darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen','palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise','papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray','plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen','rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold','sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver','honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory','springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle','lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat','lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen');


$y=0;
//$tiempos=array();
$datay=array();
$datahoras=array();
$color=array();

		if ($wherec=='' or $proyecto==2) {
			if($proyecto==2) {
				if ($codcliente <> "") { 
				$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND codcliente='".$codcliente."'";
				$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
				$SubTitle= " ". mysqli_result($rs_busqu, 0, "nombre").' '. mysqli_result($rs_busqu, 0, "apellido");
				$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND service=2 AND codcliente='".$codcliente."'";
 				} else {
				$query_busqu="SELECT * FROM clientes WHERE borrado=0 AND service=2";
				}
			} else {
			$query_busqu="SELECT * FROM clientes WHERE borrado=0 ";
			}
		
		$rs_busqu=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqu);
		$contador=0;
		$cod=0;
	
		$totalhoras='00:00';
		$ini='00:00';
		/* Recorro todos los clientes */
			while ($contador < mysqli_num_rows($rs_busqu)) {
			$codcliente=mysqli_result($rs_busqu, $contador, "codcliente");
			
				$query="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 ".$where;
				$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
				$cont=0;
				
				if(mysqli_num_rows($rs)>0) {
			
			$datahoras[$cod]=0;			
			if($proyecto==2) {				
			$datahoras[$cod]=mysqli_result($rs_busqu, $contador, "horas")*$semanas;
			}					
					while ($cont < mysqli_num_rows($rs)) {
						//$tiempos[$codcliente][$cont]=mysql_result($rs,$cont,"horas");
						$parcial=mysqli_result($rs, $cont, "horas").':00';
						$ini=SumaHoras($ini,$parcial);
						$totalhoras=SumaHoras($parcial,$totalhoras);
						$cont++;
					}
					if($ini!='00:00') {
					$nombre[$cod]=mysqli_result($rs_busqu, $contador, "nombre")." - ".$ini;
					$datay[$cod]= time2seconds($ini)/60/60;
					$color[$cod]=$Pinto[$cod];
					if(time2seconds($ini)/60>0) {
						
					}
					}
					$cod++;					
				}
			$contador++;
			$ini='00:00';
			}
			
		} else {	
			if ($proyecto==0) {
			$query_a="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 " .$where. " group by codproyectos order by codproyectos DESC ";
			} else {
			$query_a="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 " .$where. " group by codusuarios order by codusuarios DESC ";
			}
			$rs_a=mysqli_query($GLOBALS["___mysqli_ston"], $query_a);
			$cod=0;
			$totalhoras='00:00';
			$cont_a=0;
		if(mysqli_num_rows($rs_a)>0) {
			while ($cont_a < mysqli_num_rows($rs_a)) {
			$ini='00:00';
			if ($proyecto==0) {
				$codproyectos=mysqli_result($rs_a, $cont_a, "codproyectos");
				$query="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 " .$where. " and codproyectos='".$codproyectos."' order by codproyectos DESC";			
			} else {
				$codusuarios=mysqli_result($rs_a, $cont_a, "codusuarios");
				$query="SELECT * FROM horas WHERE codcliente=$codcliente AND borrado=0 " .$where. " and codusuarios='".$codusuarios."' order by codusuarios DESC";				
			}
				$rs=mysqli_query($GLOBALS["___mysqli_ston"], $query);
				$cont=0;
				while ($cont < mysqli_num_rows($rs)) {
					$parcial=mysqli_result($rs, $cont, "horas").':00';
					$ini=SumaHoras($ini,$parcial);
					$totalhoras=SumaHoras($parcial,$totalhoras);
					$cont++;
				}										
				if($ini!='00:00') {
					if($proyecto==1) {
						$query_bus="SELECT * FROM usuarios WHERE codusuarios='".$codusuarios."'";
						$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $query_bus);
						$nombre[$cod]=mysqli_result($rs_bus, 0, "nombre").' '. mysqli_result($rs_bus, 0, "apellido")." - ".$ini;
					} else {
						$query_busq="SELECT * FROM proyectos WHERE borrado=0 AND codproyectos='".$codproyectos."'";
						$rs_busq=mysqli_query($GLOBALS["___mysqli_ston"], $query_busq);
						if(mysqli_result($rs_busq, 0, "descripcion")!='') {
						$nombre[$cod]=mysqli_result($rs_busq, 0, "descripcion")." - ".$ini;
						} else {
						$nombre[$cod]="General - ".$ini;
						}
					}
				$datay[$cod]= time2seconds($ini)/60;
				$color[$cod]=$Pinto[$cod];
					if(time2seconds($ini)/60>0) {
					$cod++;
					}
				}
			$cont_a++;	
			}
		}
	}

$Title="";
	$SubTitle.=" - total horas: ".$totalhoras;

	if ($proyecto==0 and $wherec!='') {
		$Title.=" Desglose por Cliente";
	} elseif ($proyecto==1 and $wherec!='') {
		$Title.=" Desglose por usuarios ";
	} else {
		$Title.=" Resumen General ";
	}
	if($proyecto==2) {
		$Title='Horas asignadas/realizadas - clientes activos';
		$lateral=' Horas ';
	}
	


function recorro($matriz){
//echo "ver array<br>";
	foreach($matriz as $key=>$value){
		if (is_array($value)){  
                        //si es un array sigo recorriendo
			echo '<br>Proyecto:'. $key.'<br> ';
			recorro($value);
		}else{  
		       //si es un elemento lo muestro
			echo $key.'=>'.$value.'<br>';
		}
	}
}

//recorro($tiempos);
/*
echo "Recorro Datay<br><br>";
recorro($datay);
echo "Recorro Datahoras<br><br>";
recorro($datahoras);
echo "Recorro Nombre<br><br>";
recorro($nombre);
*/


function graficarBarra($datay,$datax,$Pinto,$Title,$SubTitle) {

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_log.php');
require_once ('../jpgraph/jpgraph_bar.php');

//$Pinto=array('', 'aliceblue','lightsalmon','antiquewhite','lightseagreen','aqua','lightskyblue','aquamarine','lightslategray','azure','lightsteelblue','beige','lightyellow','bisque','lime','black','limegreen','blanchedalmond','linen','blue','magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue','cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen','coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise','mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose','darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki','oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange','darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen','palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise','papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray','plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen','rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold','sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver','honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory','springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle','lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat','lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen');
	if (""!=$datay) {
	// Create the graph. These two calls are always required
	$graph = new Graph(1000,550, "auto");
	$graph->SetMarginColor('white');
	$graph->SetScale("textlin");
	$graph->SetFrame(false); 
	// Add a drop shadow
	$graph->SetShadow();
	 
	// Adjust the margin a bit to make more room for titles
	$graph->SetMargin(60,50,30,220);
	
	// Show the gridlines
	$graph->ygrid->Show(true,true);
	$graph->xgrid->Show(true,false);
	// Create a bar pot
	$bplot = new BarPlot($datay);
	 
	// Adjust fill color
	$bplot->SetFillColor($Pinto);
	$bplot->SetWidth(.7);
	$graph->Add($bplot);
	
	// Setup the titles
	$graph->title->Set($Title);
	$graph->subtitle->Set("--".$SubTitle."--");
	
	$graph->xaxis->title->Set('');
	$graph->yaxis->title->Set('minutos');
	
	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetTextLabelInterval(1);
	$graph->xaxis->SetLabelAngle(90);
	 
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	 
	// Display the graph
	$graph->Stroke();
	} else {
	echo "No hay datos para mostrar";
	
	}
}


function graficarTorta($datay,$datax,$Pinto,$Title,$SubTitle) {

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_pie.php');
require_once ('../jpgraph/jpgraph_pie3d.php');


$tmpx=array();
$tmpy=array();
$tmpname=array();
$x=1;
arsort($datay);
//echo "ver array<br>";
	foreach($datay as $key=>$value){
		       //si es un elemento lo muestro
		$tmpx[$x] = $datax[$key];
		$tmpy[$x]=$value;
		$tmpname[$x]=' (%.1f%%)';		   
//		echo $key.'=>'.$value.'<br>';
	$x++;
	}

		$datax=$tmpx;
		$datay=$tmpy;
		$name=$tmpname;

// Create the Pie Graph.
$graph = new PieGraph(1024,768);
$graph->SetAntiAliasing();
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set($Title);
//$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
$graph->title->SetColor('black');
$graph->title->SetMargin(5);

//$graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->subtitle->Set($SubTitle);


// Create pie plot
$p1 = new PiePlot($datay);

$p1->SetSize(0.34);
$p1->SetCenter(0.5,0.42);


$p1->ExplodeAll(6);
$p1->SetShadow(); 

$p1->SetSliceColors($Pinto);

$p1->SetGuideLines(true,false);
$p1->SetGuideLinesAdjust(0.91);

$p1->SetStartAngle(200);

// Setup the labels to be displayed
$p1->SetLabels($name);


$p1->SetLegends($datax);
$graph->legend->SetPos(0.49,0.97,'center','bottom');
$graph->legend->SetColumns(5);

// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(0.55);

// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
//$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$p1->value->SetColor('black');

// Add and stroke
$graph->Add($p1);
$graph->Stroke();



}

function GraficoHoras($datay,$datahoras,$nombre,$color,$Title, $SubTitle, $lateral) {

if ($datay!='') {

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');


$graph = new Graph(600,600,'auto');    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(80,30,40,260);


$graph->yaxis->title->Set($lateral);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,12);

$graph->title->Set($Title);
$graph->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->subtitle->Set($SubTitle);
$graph->subtitle->SetFont(FF_FONT1,FS_ITALIC,10);

$graph->xaxis->SetTickLabels($nombre);
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetLabelAngle(60);

$bplot1 = new BarPlot($datay);
$bplot1->SetFillColor("orange");
$bplot1->SetLegend("Realizadas");

$bplot2 = new BarPlot($datahoras);
$bplot2->SetFillColor("brown");
$bplot2->SetLegend("Asignadas");

$graph->legend->SetShadow('gray@0.4',5);
$graph->legend->SetPos(0.1,0.1,'right','top');
$graph->legend->SetColumns(1);
$graph->legend->SetVColMargin(1);

$gbarplot = new GroupBarPlot(array($bplot2,$bplot1));
$gbarplot->SetWidth(0.7);
$graph->Add($gbarplot);

$graph->Stroke();


}


}


$tipo=$_GET['tipo'];
if($proyecto==2) {
	GraficoHoras($datay,$datahoras,$nombre,$color,$Title, $SubTitle, $lateral);
} elseif ($tipo==1) {
graficarBarra($datay,$nombre, $color,$Title, $SubTitle);
} else {
graficarTorta($datay,$nombre,$Pinto,$Title, $SubTitle);
}



?>