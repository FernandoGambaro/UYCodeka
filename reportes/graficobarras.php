<?php // content="text/plain; charset=utf-8"

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');

include ("../conectar.php");
header('Content-Type: text/html; charset=UTF-8'); 
require("../common/funcionesvarias.php");
include ("../funciones/fechas.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');
//$titulo=$_GET['t'];

$codusuarios= isset($_GET['u']) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],$_GET['u']) :  "";;


if (empty($_GET['anio'])) {
$anio=date('Y');
} else {
$anio=$_GET['anio'];
}
if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}

//$codusuarios=13;
$sql="select nombre,apellido from `usuarios` WHERE `codusuarios`='$codusuarios'";
$res_sql=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or dir ("Error");
while ($row=mysqli_fetch_array($res_sql)) {
$titulo="Resumen: ".$row['nombre']." ".$row['apellido'];
}

function lastDateOfMonth($Month, $Year=-1) {
    if ($Year < 0) $Year = 0+date("Y");
    $aMonth         = mktime(0, 0, 0, $Month, 1, $Year);
    $NumOfDay       = 0+date("t", $aMonth);
    $LastDayOfMonth = mktime(0, 0, 0, $Month, $NumOfDay, $Year);
    return $LastDayOfMonth;
}

$primer_dia="".$anio."-".$mes."-01";

$ultimo_dia=date("Y-n-j", lastDateOfMonth($mes,$anio));

$titulo.=" ".mes($mes)." ".$anio;
//$ultimo_dia="".$anio."-".$mes."-".date('t');

/*
function SumaHoras($hora1,$hora2) {
$hora1=split(":",$hora1);
$hora2=split(":",$hora2);
$horas=(int)$hora1[0]+(int)$hora2[0];
$minutos=(int)$hora1[1]+(int)$hora2[1];
$segundos=(int)$hora1[2]+(int)$hora2[2];
$horas+=(int)($minutos/60);
$minutos=(int)($minutos%60)+(int)($segundos/60);
$segundos=(int)($segundos%60);
return (intval($horas)< 10 ? '0'.intval($horas):intval($horas)).':'.($minutos < 10 ?'0'.$minutos:$minutos).':'.($segundos < 10 ? '0'.$segundos:$segundos);
}
*/
/*
function time2seconds($time='00:00:00')
{
    list($hours, $mins, $secs) = explode(':', $time);
    return ($hours * 3600 ) + ($mins * 60 ) + $secs;
}
*/

$Pinto=array(
'wheat', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua', 'bisque','lime','black','lightyellow','limegreen','linen','blue', 'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige', 'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue', 'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise', 'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen', 'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose', 'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange', 'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki', 'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen', 'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray', 'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen', 'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise', 'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold', 'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory', 'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver', 'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle', 'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen', 'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');

$datax=array();
$datay=array();
$clientes=array();
$total=array();
  	//echo $mes."<br> ";
$year=intval(date('Y'));
//$year='2016';
$m = mktime( 0, 0, 0, $mes, 1, $year );
$fin = intval(date('t', $m));

$total_usuario="00:00:00";
$total_proyecto=0;
//echo $fin."----año--".$year."<br>";
   //Mensual
   $x=0;
	$i=1;
	$y=0;

$dia_ini =date("Y-m-d", mktime( 0, 0, 0, $mes, $i, $year ));
$dia_fin =date("Y-m-d", mktime( 0, 0, 0, $mes, $fin, $year ));

 $sql_total="SELECT horas.fecha,horas.codusuario,horas.horas,horas.codcliente, clientes.nombre, clientes.empresa FROM
 `horas` INNER JOIN clientes ON horas.codcliente=clientes.codcliente 
 WHERE horas.codusuario='$codusuarios' AND horas.borrado=0 
 AND `fecha`>= '$dia_ini' AND fecha <='$dia_fin' GROUP BY codcliente ORDER BY `fecha` DESC";

$res_total=mysqli_query($GLOBALS["___mysqli_ston"], $sql_total);
$count=0;
while($count < mysqli_num_rows($res_total)) {
	$clientes[$count]=mysqli_result($res_total, $count, 'codcliente');
	$nombre[$count]=mysqli_result($res_total, $count, 'nombre');
	$count++;
}
/*Para cada cliente busco día a día las horas realizadas*/
foreach($clientes as $xx=>$codcliente){
	$datay=array();

$i=1;
$t=0;
$tot=0;
$total_codcliente='00:00:00';
while($i<=$fin){
	$dia_mes =date("Y-m-d", mktime( 0, 0, 0, $mes, $i, $year ));
  //echo  $dia_mes." ".$i."<br>";
//$datay[0]=0;
$total_usuario='00:00:00';

 $sql_proyecto="SELECT horas.codusuario,horas.horas,horas.codcliente, 
 clientes.nombre, clientes.empresa FROM
 `horas` INNER JOIN  clientes on horas.codcliente=clientes.codcliente 
 WHERE horas.codcliente=$codcliente AND horas.codusuario='$codusuarios' 
 AND `fecha` ='$dia_mes' AND horas.borrado=0 ";
 
 //echo $sql_proyecto."<br>";

	$res_proyecto=mysqli_query($GLOBALS["___mysqli_ston"], $sql_proyecto);
	if(mysqli_num_rows($res_proyecto)>0){
 //echo $sql_proyecto."<br>";
 	$con=0;

	while ($con < mysqli_num_rows($res_proyecto)) {
	$total_usuario=SumaHoras(mysqli_result($res_proyecto, $con, "horas"),$total_usuario);
	$con++;
	}
   	//echo $total_usuario."<br>";
			$array = explode(":", $total_usuario);
			//echo "<br>Cliente".$codcliente." ->Hora".$array[0]." --min ".$array[1]."<br>";
			$total_codcliente=($array[0]*60+$array[1])/60;
			if($total_codcliente>0) {

				$datay[$t]=floatval($total_codcliente);
				$tot=$tot+$total_codcliente;
			} else {

				$datay[$t]=0;
			}
					       
		
		
	}else{

		$datay[$t]=0;

	}
	
	$i++;
	$t++;
}
   $datax[]=$datay;
   $total[]=$tot;
   $tot=0;
   //echo count($datay)."<br>";
	$y++;
	$datay='';
	//break;
}

///////////////////

function recorro($matriz){
//echo "ver array<br>";
	foreach($matriz as $key=>$value){
		if (is_array($value)){  
                        //si es un array sigo recorriendo
			echo '<br>Cliente: '. $key.'<br> ';
			recorro($value);
		}else{  
		       //si es un elemento lo muestro
			echo 'Día- '.$key.' Horas =>'.$value.'<br>';
		}
	}
}
/*
recorro($datax);
/*
echo "<br>";
recorro($nombre);
echo "<br>";
//recorro($Pinto);
echo "<br>";
*/
//echo "<pre>".print_r($datax, true)."</pre><br />";
//echo "Count: ".count($datax)."<br />";

 $x=$y-1;

//$datax='';

if (!empty($datax) and $x!=0) {

// Create the graph. These two calls are always required
$graph = new Graph(810,700, "auto");
$graph->SetScale("textlin");

$graph->SetY2Scale("lin",0,90);
$graph->SetY2OrderBack(false);

//$graph->SetShadow();
$graph->img->SetMargin(30,30,235,50);

$theme_class = new UniversalTheme;
$graph->SetTheme($theme_class);


// Show the gridlines
//$graph->ygrid->Show(true,true);
//$graph->xgrid->Show(true,false);

$graph->ygrid->SetFill(false);
//$graph->xaxis->SetTickLabels(array('A','B','C','D'));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

if($x>0) {
	for ($t = 0; $t<=$x; $t++) {

//echo "<pre>".print_r($datax[$t], true)."</pre><br />";
//echo "Count: ".count($datax[$t],1)."<br />";

	$bplot = new BarPlot($datax[$t]);
	$bplot->SetColor($Pinto[$t]);
	$bplot->SetFillColor($Pinto[$t]);
	$bplot->SetLegend($total[$t].' - '.$nombre[$t]);
/*
	   $Variable = "b".$i."plot";
	   $$Variable = new BarPlot($datax[$i]);
	   //$$Variable->SetLegend($nombre[$i]);
	   //$$Variable->SetFillColor($Pinto[$i]);
*/
		$diaa[]=$t+1;
		$barplot[]=$bplot;
	}

//echo "<pre>".print_r($barplot, true)."</pre><br />";
//echo "Count: ".count($barplot,1)."<br />";


	$gbplot = new AccBarPlot($barplot);
} else {
	$gbplot = new BarPlot($datax[0]);
	$gbplot->SetColor($Pinto[0]);
	$gbplot->SetFillColor($Pinto[0]);
	$gbplot->SetLegend($total[0].' - '.$nombre[0]);	
}

$gbplot->SetWidth(0.7);
// ...and add it to the graPH
$graph->Add($gbplot);


//$graph->title->Set($titulo);

$graph->tabtitle->Set($titulo);
//$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);

$graph->xaxis->title->Set("Dia");
$graph->yaxis->title->Set("Horas");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xaxis->SetTickLabels($diaa);
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetLabelAngle(90);

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColor('#4E4E4E','#00A78A');

// Adjust the legend position
$graph->legend->Pos(0.01,0.01,'right','top');

// Display the graph
$graph->Stroke();


} else {
	echo "No hay datos suficientes, puede ser ingresos para un solo cliente";
}

?>