<?php // content="text/plain; charset=utf-8"

require_once ('/var/www/improfit/jpgraph/src/jpgraph.php');
require_once ('/var/www/improfit/jpgraph/src/jpgraph_log.php');
require_once ('/var/www/improfit/jpgraph/src/jpgraph_bar.php');


include("conexion.php");
require("funcionesvarias.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');


$titulo=$_GET['t'];

if (empty($_GET['anio'])) {
$anio=date('Y');
} else {
$anio=$_GET['anio'];
}
$anio='2012';

if (empty($_GET['mes'])) {
$mes=date('m');
} else {
$mes=$_GET['mes'];
}

$SubTitle=mes($mes);

$primer_dia="".$anio."-".$mes."-01";
$ultimo_dia="".$anio."-".$mes."-".date('t');

$y=0;
$datax=array();
$datay=array();
$targ=array();
$alts=array();

//echo $cantindad."<br>";
$sqlClie="SELECT * FROM `USUARIOS` WHERE `USUARIOESTADO` = 'active'";
$ClieBus=@mysqli_query( $conectar, $sqlClie);
while ($row=@mysqli_fetch_array($ClieBus))
{
	$usuario=$row['USUARIOID'];
   $datax[$y] = $row['USUARIONOM']." ".$row['USUARIOAPE'];
      $sql="SELECT * FROM  `CONTROL` WHERE `USUARIOID` ='".$row['USUARIOID']."' AND `CONTROLFECHA` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `CONTROLFECHA` DESC";
      //echo $sql."<br>";
      $ListarTotales = mysqli_query( $conectar, $sql);
      while ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
         $datay[$y]+=$Controlrow['CONTROLDATA'] ? $Controlrow['CONTROLDATA'] : '' ;
	    //$total=$Controlrow['CONTROLDATA']+$total;
	    //echo "proyecto: ".$y." día ".$x."  --- ".$datax[$x]."<br>";
      }
     	$targ[$y]="graficobarras.php?u=".$usuario."&mes=".$mes;
		$alts[$y]=$datay[$y]." horas\r Clic ver detalles ";
$y=$y+1;
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

//recorro($datax);
//recorro($targ);


function graficar($datay,$datax,$targ,$alts, $SubTitle)
 {

//$Pinto=array('', 'aliceblue','lightsalmon','antiquewhite','lightseagreen','aqua','lightskyblue','aquamarine','lightslategray','azure','lightsteelblue','beige','lightyellow','bisque','lime','black','limegreen','blanchedalmond','linen','blue','magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue','cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen','coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise','mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose','darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki','oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange','darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen','palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise','papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray','plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen','rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold','sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver','honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory','springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle','lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat','lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen');

	if (!empty($datay)) {
	
	// Create the graph. 
	// One minute timeout for the cached image
	// INLINE_NO means don't stream it back to the browser.
	$graph = new Graph(400,450,'auto');
	$graph->SetScale("textlin");
	$graph->img->SetMargin(60,30,20,140);
	$graph->yaxis->SetTitleMargin(45);
	$graph->yaxis->scale->SetGrace(30);
	$graph->SetShadow();

	$graph->subtitle->Set("--".$SubTitle."--");
	
	// Use a gradient to fill the plot area
	$graph->SetBackgroundGradient('white','lightblue',GRAD_HOR,BGRAD_PLOT);
	
	
	// Turn the tickmarks
	$graph->xaxis->SetTickSide(SIDE_DOWN);
	$graph->yaxis->SetTickSide(SIDE_LEFT);

	// Use a gradient to fill the plot area
	$graph->SetBackgroundGradient('white','lightblue',GRAD_HOR,BGRAD_PLOT);
	
	// Create a bar pot
	$bplot = new BarPlot($datay);
	
	// Create targets for the image maps. One for each column
	$bplot->SetCSIMTargets($targ,$alts);
	
	// Use a shadow on the bar graphs (just use the default settings)
	$bplot->SetShadow();
	
//	$bplot->SetFillColor($Pinto);
	$bplot->SetWidth(0.7);
	
	$bplot->value->SetFormat(" $ %2.1f",70);
	$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,9);
	
	// Set gradient fill for bars
	$bplot->SetFillGradient('darkred','yellow',GRAD_HOR);

// Adjust fill color
//	$bplot->value->SetColor($Pinto);
	$bplot->value->Show();
	
	$graph->Add($bplot);
	
// Setup the titles
	$graph->title->Set('Acumulados del mes para cada usuario');
	$graph->xaxis->title->Set('');
	$graph->yaxis->title->Set('Horas');

	$graph->xaxis->SetTickLabels($datax);
	$graph->xaxis->SetTextLabelInterval(1);
	$graph->xaxis->SetLabelAngle(90);
 	
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	
	// Send back the HTML page which will call this script again
	// to retrieve the image.
	$graph->StrokeCSIM();
	
	} else {
	echo "No hay datos para mostrar";
	
	}
}


graficar($datay,$datax,$targ,$alts, $SubTitle);


?>
