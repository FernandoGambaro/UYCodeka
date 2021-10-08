<?php

require_once ('../jpgraph/jpgraph.php');
require_once ('../jpgraph/jpgraph_pie.php');
require_once ('../jpgraph/jpgraph_pie3d.php');

include ("../conectar.php");
header('Content-Type: text/html; charset=UTF-8'); 
require("../funciones/funcionesvarias.php");

setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
$num_usuario = mysqli_num_rows($res_usuario);

if (empty(@$_GET['anio'])) {
$anio=date('Y');
} else {
$anio=@$_GET['anio'];
}
if (empty(@$_GET['mes'])) {
$mes=date('m');
} else {
$mes=@$_GET['mes'];
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

//$ultimo_dia="".$anio."-".$mes."-".date('t');


function SumaHoras($hora1,$hora2) {
$hora1=explode(":",$hora1);
$hora2=explode(":",$hora2);
$horas=(int)$hora1[0]+(int)$hora2[0];
$minutos=(int)$hora1[1]+(int)$hora2[1];
$segundos=(int)$hora1[2]+(int)$hora2[2];
$horas+=(int)($minutos/60);
$minutos=(int)($minutos%60)+(int)($segundos/60);
$segundos=(int)($segundos%60);
return (intval($horas)< 10 ? '0'.intval($horas):intval($horas)).':'.($minutos < 10 ?'0'.$minutos:$minutos).':'.($segundos < 10 ? '0'.$segundos:$segundos);
}


function time2seconds($time='00:00:00')
{
    list($hours, $mins, $secs) = explode(':', $time);
    return ($hours * 3600 ) + ($mins * 60 ) + $secs;
}

$rowColor=array();
$ColorSource=array(
'wheat', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua', 'bisque','lime','black','lightyellow','limegreen','linen','blue', 'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige', 'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue', 'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise', 'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen', 'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose', 'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange', 'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki', 'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen', 'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray', 'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen', 'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise', 'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold', 'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory', 'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver', 'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle', 'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen', 'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');
$xcolor=1;

	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{

 $sql_proyecto="SELECT horas.codusuario,horas.horas,horas.codcliente, clientes.nombre, clientes.empresa FROM
 `horas`, clientes WHERE horas.codcliente=clientes.codcliente AND `fecha` BETWEEN '$primer_dia' AND '$ultimo_dia' GROUP BY codcliente ORDER BY `fecha` DESC";
$res_proyecto=mysqli_query($GLOBALS["___mysqli_ston"], $sql_proyecto);
while ($row_proyecto=mysqli_fetch_array($res_proyecto))
{
	$codcliente=$row_proyecto['codcliente'];
	$Pinto[$codcliente] =$ColorSource[$xcolor];
	$total_proyecto[$codcliente]=0;

$nombreProyecto=$row_proyecto['nombre'];


	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	//echo "<td>".$sql_usuario."</td>";
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario['codusuarios'];  
		
		$sql="SELECT horas FROM horas WHERE `codcliente`='".$codcliente."' AND `codusuario` ='".$codusuarios."' AND borrado=0  AND fecha >= '".$primer_dia."'  
		AND fecha <= '".$ultimo_dia. "' ORDER BY `fecha` DESC";
    //echo $sql."<br>";
      $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
      
   	if (mysqli_num_rows($ListarTotales)>0) {
   		$total_usuario="";

      while ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
      	$total_usuario=SumaHoras($Controlrow['horas'],$total_usuario);
         $total_proyecto[$codcliente]=SumaHoras($total_proyecto[$codcliente],$Controlrow['horas']);
			$total_usuario_proyecto[$codusuarios]=SumaHoras($total_usuario_proyecto[$codusuarios],$Controlrow['horas']);         
         

         
         //$total_usuario_proyecto[$codusuarios]=date("H:i",strtotime($total_usuario)+strtotime($total_usuario_proyecto[$codusuarios]));
      }
			$array = split(":", $total_usuario);
			$total_usuario1=($array[0]*60+$array[1])/60;
			$total_usuario1=number_format($total_usuario1, 2, ',', ' ');

		$total_usuario="";		

	}
	$array = split(":", $total_proyecto[$codcliente]);
	$total_usuario1=($array[0]*60+$array[1])/60;
	$total_proyecto[$codcliente]=number_format($total_usuario1, 2, ',', ' ');


$total_usuario="";
}


	$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' ORDER BY `codusuarios` ASC";
	$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario['codusuarios'];
	$array = split(":", $total_usuario_proyecto[$codusuarios]);
	$total_usuario1=($array[0]*60+$array[1])/60;
	$total_usuario_proyecto[$codusuarios]=number_format($total_usuario1, 2, ',', ' ');		


	}
//////////////////////

$a=$_GET['a'];
$titulo=$_GET['t'];
$codusuarios=$_GET['u'];
$mesFecha=$_GET['mes'];
$tota=0;

$DIA='';
$fin='';
$mes='';
$primer_dia='';
$ultimo='';
$ultimo_dia='';
$horastotales='';


if ($a!="S") {
	$DIA="Mon";
	//Acumulado mensual
	if ($DIA=="Mon") {
	$fin= date('t');
	//$mes= date('Y')."-".$mesFecha;
	$mes= "2012-".$mesFecha;
	//Mensual
	   $primer_dia=$mes."-01";
	   $ultimo_dia=$mes."-".$fin;
	   $sql="SELECT * FROM  `horas` WHERE  `codusuarios` ='$codusuarios' AND `fecha` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `fecha` DESC";
	   //echo $sql."<br>";
	   $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	   while ($Controlrow = mysqli_fetch_array($ListarTotales))
	   {

	      if ($Controlrow['CONTROLDATA']>0) {
	      $data[$nombre]=$Controlrow['CONTROLDATA']+$data[$nombre];
	      $tota=$Controlrow['CONTROLDATA']+$total;
	      }   //echo $nombre."<br>";
	   }
	}   
} else {
   $primer_dia = mktime();
   $ultimo_dia = mktime();
   while(date("w",$primer_dia)!=1){
   $primer_dia -= 3600;
   }
   while(date("w",$ultimo_dia)!=0){
   $ultimo_dia += 3600;
   }
//Semanal
   $primer_dia=date("Y-m-d",$primer_dia);
   $ultimo_dia=date("Y-m-d",$ultimo_dia);
$tota=0;
   $sql="SELECT * FROM  `CONTROL` WHERE  `codusuarios` ='$codusuarios' AND `CONTROLFECHA` BETWEEN '$primer_dia' AND '$ultimo_dia' ORDER BY `CONTROLFECHA` DESC";
   //echo $sql."<br>";
   $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
   while ($Controlrow = mysqli_fetch_array($ListarTotales))
   {
      $sqlClie="SELECT * FROM `companies` WHERE `companyId` = '".$Controlrow['companyId']."'";
      $ClieBus=mysqli_query($GLOBALS["___mysqli_ston"], $sqlClie);
      if($Companiesrow=mysqli_fetch_array($ClieBus))
      {
      $nombre = str_replace(" ", "_",  $Companiesrow['companyName']);
	      $sqlColor="select * from `COLORES` where `COLORID`='".$Companiesrow['companyColor']."'";
	      $resColor=mysqli_query( $conectar, $sqlColor);
	      if ($rowColor=mysqli_fetch_array($resColor)) {
	      $Pinto[$nombre] = $rowColor['COLORCOLOR'];	
	      }
      }
      if ($Controlrow['CONTROLDATA']>0) {
      $data[$nombre]=$Controlrow['CONTROLDATA']+$data[$nombre];
      $tota=$Controlrow['CONTROLDATA']+$tota;
      }
   //echo $nombre."<br>";
   }
   
}      
   


$total = 0;

	foreach ($data as $key => $item)
	{
//echo $key." .. ".$item."<br>";

$datay[$total]=$item;
$datax[$total]=$key;
$dataz[$total]="$key\n$item.hr";
$horastotales=$item+$horastotales;
$ColorChange[$total]=$Pinto[$key];
$total++;
	}

}	
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

//recorro($ColorChange);
//recorro($datax);
/*
/*
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
*/

// Create the Pie Graph.
if(!empty($datay) and $tota>0) {
$graph = new PieGraph(300,300, "auto");
$graph->SetFrame(false);
 
//$graph->SetShadow();

$theme_class= new PersonalTheme;
$graph->SetTheme($theme_class);


// Set A title for the plot
$graph->title->Set($titulo);
$graph->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->title->SetColor('black');
$graph->title->SetMargin(8);
$graph->legend->Pos(0.1,0.2); 

$p1 = new PiePlotC($datay);
//$p1->SetAngle(50);
$p1->SetCenter(0.5,0.5);
$p1->SetSize(0.35);

// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
$p1->value->SetFont(FF_FONT1,FS_NORMAL,12);
//$p1->value->SetFormat('%2.1f%%'); 
$p1->value->SetColor('darkgray');
 
// Setup the title on the center circle
$p1->midtitle->Set("Horas\nacumuladas\n$horastotales");
$p1->midtitle->SetFont(FF_FONT2,FS_NORMAL,14);
 
// Set color for mid circle
$p1->SetMidColor('yellow');

$p1->SetSliceColors($ColorChange);

// Setup the labels to be displayed
//$p1->SetLabels($dataz);
//$p1->SetFillColor($Pinto);
//$p1->value->SetColor($Pinto);

// Uncomment this line to remove the borders around the slices
$p1->ShowBorder(false);
 
// Add drop shadow to slices
$p1->SetShadow();
 
// Explode all slices 15 pixels
$p1->ExplodeAll(10);

// Add and stroke
$graph->Add($p1);
$graph->Stroke();

} else {
	echo "Falta datos";
}

?>