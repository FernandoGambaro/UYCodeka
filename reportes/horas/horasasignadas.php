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
require_once __DIR__ .'/../../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;
$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

// set page headers
$page_title = "Nuevo Detalles de horas";

require_once '../../common/fechas.php';   
require_once '../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$codhorasestudio = '';
$codhoraspaciente = '';
$hace = _('Muestra horas asignadas a clientes');

logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);

$mes='';

if(strlen($_GET["fechaini"])>0){ $fechaini=explota($_GET["fechaini"]);}else{ $fechaini=date("Y-m-d");}
if(strlen($_GET["fechafin"])>0){ $fechafin=explota($_GET["fechafin"]);}else{ $fechafin=date("Y-m-d");}


$startTime =data_first_month_day($fechaini); 
$endTime = data_last_month_day($fechafin); 

$fechainicial = new DateTime($fechaini);
$fechafinal = new DateTime($fechafin);


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

	
$totalhoras='00:00';
$total='00:00';
$x=0;

		//$Descripcion='Todos los clientes activos';
		$objclientes = new Consultas('clientes');
		$objclientes->Select();
		
		$objclientes->Where('borrado', '0');    
		$objclientes->Where('service', '2');    

		$clientes = $objclientes->Ejecutar();
		//echo "<br>".$clientes["consulta"]."<br>-->";
		$total_rows=$clientes["numfilas"];
		$rows = $clientes["datos"];
		
		if($total_rows>=0){
			foreach($rows as $row){
				$nombre=$row['nombre']." ".$row['apellido'];
				if($row['empresa']!='') {
					$nombre=$row['empresa'];
				}										
				$datax[$x]=	$row["horas"]*$meses;

				///////////////////Inicio horas registradas
				$objhoras = new Consultas('horas');
				$objhoras->Select();
				
				$objhoras->Where('borrado', '0');    
				$objhoras->Where('codcliente', $row['codcliente']);    
				$objhoras->Where("fecha" , $startTime, '>=');
				$objhoras->Where("fecha" , $endTime, '<=');
		
				$horas = $objhoras->Ejecutar();
				$total_horas=$horase["numfilas"];
				$rowshoras = $horas["datos"];
				//echo $horas["consulta"];
				// check if more than 0 record found
				if($total_horas>=0){
					foreach($rowshoras as $rowhoras){
						$parcial=$rowhoras["horas"].':00';
						if($rowhoras["horas"]!='0:00') {
						$totalhoras=SumaHoras($parcial,$totalhoras);
						$horasTotales=SumaHoras($horasTotales,$totalhoras);
						}
					}
					if($totalhoras!='00:00') {
						$datay[$x]= str_replace(",",".",time2seconds($totalhoras)/60/60);
						//$horasTotales=SumaHoras($horasTotales,$total);
					}else{
						$datay[$x]=0;
					}
				}
				$totalhoras='00:00';
				/////////////////////////////Fin horas registradas
				$name[$x]=str_replace('', '&nbsp;',$nombre);
				$color[$x]=$Pinto[$x];	
				$x++;
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

//recorro($datay);
//recorro($name);
//recorro($Pinto);



$x=$x-1;

$titulo='Horas asignadas/realizadas - clientes abonados';
$lateral=genMonth_Text(date("m",strtotime($startTime))).' de '. date("Y",strtotime($startTime));
$subtitulo="Horas totales ". $horasTotales;

if ($datay!='') {

require_once ('../../jpgraph/jpgraph.php');
require_once ('../../jpgraph/jpgraph_bar.php');

$graph = new Graph(900,600,'auto');    
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->img->SetMargin(80,30,40,260);

$graph->yaxis->title->Set($lateral);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,12);

$graph->title->Set($titulo);
$graph->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->subtitle->Set($subtitulo);
$graph->subtitle->SetFont(FF_FONT1,FS_ITALIC,10);

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
