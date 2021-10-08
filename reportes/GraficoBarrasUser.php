<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_bar.php');

include("../conexion.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');

//$titulo=$_GET['t'];
//$codusuarios=$_GET['u'];

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


$titulo=$_GET['t'];
$codusuarios=$_GET['u'];



//$codusuarios=13;
$sql="select * from `USUARIOS` WHERE `codusuarios`='$codusuarios'";
$res_sql=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or dir ("Error");
while ($row=mysqli_fetch_array($res_sql)) {
$titulo="Resumen mensual ".$row['nombre']." ".$row['apellido'];
}


$sqluser="SELECT * FROM `ORGANIZACION` WHERE  `codusuarios` ='$codusuarios'";
$resuser=mysqli_query($GLOBALS["___mysqli_ston"], $sqluser);
$cantindad=mysqli_num_rows($resuser);
$ca=1;
$y=1;
$datax=array();
$datay=array();
//echo $cantindad."<br>";

while ($row=mysqli_fetch_array($resuser)) {

      $sqlClie="SELECT * FROM `companies` WHERE `companyId` = '".$row['companyId']."'";
      $ClieBus=mysqli_query($GLOBALS["___mysqli_ston"], $sqlClie);
      if($Companiesrow=@mysqli_fetch_array($ClieBus))
      {
      $nombre[$y] = $Companiesrow['companyName'];
      }

   if ($_GET['mes']!="") {
   	$mes= date($_GET['mes']);
   } else {
   	$mes= date('m');
  	}
  	//echo $mes."<br> ";
$year=intval(date('Y'));
$year='2012';
$m = mktime( 0, 0, 0, $mes, 1, $year );
$fin = intval(date('t', $m));

//echo $fin."----año--".$year."<br>";
   //Mensual
   $x=1;
	$i=1;
   //for($i=1; $i<=$fin; $i+=1){
   while($i<=$fin){
      $dia_mes =date("Y-m-d", mktime( 0, 0, 0, $mes, $i, $year ));
      //echo $dia_mes." ".$i."<br>";
      $total=0;
      $sql="SELECT * FROM  `CONTROL` WHERE `codusuarios` ='$codusuarios' AND `companyId` ='".$row[companyId]."' AND `CONTROLFECHA` = '$dia_mes'";
      //echo $sql."<br>";
      $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
      if ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
         if ($y<=$cantindad) {
         $datax[$x]=$Controlrow['CONTROLDATA']+0;
	    //$total=$Controlrow['CONTROLDATA']+$total;
	    //echo "proyecto: ".$y." día ".$x."  --- ".$datax[$x]."<br>";
   $x=$x+1;
         }
      } else {
      $datax[$x]=0;
   $x=$x+1;
      }
   if ($y<=$cantindad) {
   $datay[$y]=$datax;
   }
	$i=$i+1;
   }

$y=$y+1;
}


//recorro($datay);
//recorro($nombre);


$x=$y-1;

$Pinto=array(
'', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua',
'bisque','lime','black','lightyellow','limegreen','linen','blue',
'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige',
'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue',
'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise',
'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen',
'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose',
'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange',
'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki',
'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen',
'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray',
'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen',
'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise',
'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold',
'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory',
'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver',
'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle',
'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen',
'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');

if (!empty($datax)) {

// Create the graph. These two calls are always required
$graph = new Graph(710,500, "auto");
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(40,230,25,40);

// Show the gridlines
$graph->ygrid->Show(true,true);
$graph->xgrid->Show(true,false);



for ($i = 1; $i<=$x; $i++) {
   $Variable = "b".$i."plot";

   $$Variable = new BarPlot($datay[$i]);
   $$Variable->SetLegend($nombre[$i]);
   $$Variable->SetFillColor($Pinto[$i]);

//   $$Variable->SetColor('darkred');
//   $$Variable->SetWeight(2);

$diaa[]=$i;
   $colo=$colo*3;
   $barplot[]=$$Variable;
   //echo $barplot[$i]."<br>";
}

//recorro($barplot);


//$barplot=array($b1plot,$b2plot,$b3plot);

// Create the grouped bar plot
$gbplot = new AccBarPlot($barplot);
$gbplot->SetWidth(0.7);
// ...and add it to the graPH
$graph->Add($gbplot);


$graph->title->Set($titulo);
$graph->xaxis->title->Set("Dias");
$graph->yaxis->title->Set("Horas");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$graph->xaxis->SetTickLabels($diaa);
$graph->xaxis->SetTextLabelInterval(1);
$graph->xaxis->SetLabelAngle(90);

// Adjust the legend position
$graph->legend->Pos(0.01,0.01,'right','top');

// Display the graph
$graph->Stroke();

}
?>
