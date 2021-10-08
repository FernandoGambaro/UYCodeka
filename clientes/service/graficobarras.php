<?php // content="text/plain; charset=utf-8"

date_default_timezone_set('America/Montevideo');

include ("../../conectar.php");
include ("../../funciones/fechas.php");

$mes='';


  
//$startTime =data_first_month_day(explota($_GET['fechainicio'])); 
//$endTime = data_last_month_day(explota($_GET['fechafin'])); 

$startTime =data_first_month_day('2015-08-02'); 
$endTime = data_last_month_day('2015-08-30'); 


$fechainicial = new DateTime('2015-08-01');
$fechafinal = new DateTime('2015-08-30');

$diferencia = $fechainicial->diff($fechafinal);
$meses = ( $diferencia->y * 12 ) + $diferencia->m;
if($meses==0) {
	$meses=1;
}


$sTime=$startTime;
 
setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$datay=array();
$datax=array();
$color=array();

$Pinto=array(
'', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua', 'bisque','lime','black','lightyellow','limegreen','linen','blue', 'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige', 'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue', 'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise', 'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen', 'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose', 'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange', 'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki', 'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen', 'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray', 'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen', 'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise', 'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold', 'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory', 'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver', 'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle', 'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen', 'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');


	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>"\$", 2=>"U\$S");
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	

$x=0;

/*Recorro todas las ventas del mes agrupadas por cliente, para cada cliente sumo las ventas del mes y las agrego a un array*/
	 $sel_clientesmes="SELECT * FROM clientes WHERE borrado=0 AND service=2 order by codcliente ASC";

	$res_clientesmes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_clientesmes);
	$contador_clientesmes=0;
	while ($contador_clientesmes < mysqli_num_rows($res_clientesmes)) {
	$codcliente=mysqli_result($res_clientesmes, $contador_clientesmes, "codcliente");

				if (mysqli_result($res_clientesmes, $contador_clientesmes, "empresa")!='') {
					$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "empresa");
					} elseif (mysqli_result($res_clientesmes, $contador_clientesmes, "apellido")=='') {
						$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "nombre");
					} else {
						$nombre= mysqli_result($res_clientesmes, $contador_clientesmes, "nombre"). ' ' . mysqli_result($res_clientesmes, $contador_clientesmes, "apellido");
					}
			$datax[$x]=	mysqli_result($res_clientesmes, $contador_clientesmes, "horas")*$meses;


			$sel_resultado="SELECT * FROM service WHERE borrado=0 AND codcliente=". $codcliente. "	AND fecha >='".$startTime."' AND fecha <='".$endTime."' ";

			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				 $total= mysqli_result($res_resultado, $contador, "horas");
				 $datay[$x]+=$total;		 
			$contador++;		
			}
			if ($total==0){
			$datay[$x]=0;
			}
			$total=0;
			$nombre=rtrim($nombre);
			$name[$x]=str_replace('', '&nbsp;',$nombre);
			$color[$x]=$Pinto[$x];
	$codcliente='';
	$contador_clientesmes++;
	$x++;
	}

//echo $cantindad."<br>";

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

//recorro($datay);
//recorro($name);
//recorro($Pinto);


$x=$x-1;

$titulo='Algo va';
$m=genMonth_Text(date("m",strtotime($startTime))).' de 2014';

if ($datay!='') {

require_once ('/usr/share/jpgraph/jpgraph.php');
require_once ('/usr/share/jpgraph/jpgraph_bar.php');


$graph = new Graph(600,600,'auto');    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(80,30,40,260);


$graph->yaxis->title->Set($m);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->title->Set('Horas asignadas/realizadas - clientes abonados');
$graph->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xaxis->SetTickLabels($name);
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetLabelAngle(60);

$bplot1 = new BarPlot($datay);
$bplot1->SetFillColor("orange");
$bplot1->SetLegend("Realizadas");

$bplot2 = new BarPlot($datax);
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

?>