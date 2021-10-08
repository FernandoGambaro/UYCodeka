<?php // content="text/plain; charset=utf-8"

date_default_timezone_set('America/Montevideo');

include ("../conectar.php");
include ("../funciones/fechas.php");

$mes='';



  
$startTime =data_first_month_day(explota($_GET['fechainicio'])); 
$endTime = data_last_month_day(explota($_GET['fechafin'])); 


if ( $_GET['fechainicio'] != $_GET['fechafin']) {
$m='Del  '. date('d',strtotime($startTime)). ' de '. genMonth_Text(date('m',strtotime($startTime))). ' al ' .date('d',strtotime($endTime)) . ' de '. genMonth_Text(date('m',strtotime($endTime))) . ' de '. date('Y', strtotime($startTime));
} else {
$m=genMonth_Text(date('m',strtotime($endTime))) . ' de 2014';
}


$sTime=$startTime;
 
setlocale (LC_ALL, 'et_EE.ISO-8859-1');

$datay=array();
$datax=array();
$color=array();

$Pinto=array(
'', 'aliceblue','antiquewhite','lightsalmon','lightseagreen','aqua', 'bisque','lime','black','lightyellow','limegreen','linen','blue', 'lightskyblue','blanchedalmond','aquamarine','lightslategray','azure','lightsteelblue','beige', 'magenta','blueviolet','maroon','brown','mediumaquamarine','burlywood','mediumblue', 'coral','mediumslateblue','cornflowerblue','mediumspringgreen','cornsilk','mediumturquoise', 'cadetblue','mediumorchid','chartreuse','mediumpurple','chocolate','mediumseagreen', 'mediumvioletred','cyan','midnightblue','darkblue','mintcream','darkcyan','mistyrose', 'oldlace','darkmagenta','olive','darkolivegreen','olivedrab','darkorange','orange', 'darkgoldenrod','moccasin','darkgray','navajowhite','darkgreen','navy','darkkhaki', 'darkorchid','orangered','darkred','orchid','darksalmon','palegoldenrod','darkseagreen', 'papayawhip','darkviolet','peachpuff','deeppink','peru','deepskyblue','pink','dimgray', 'plum','dodgerblue','powderblue','firebrick','purple','floralwhite','red','forestgreen', 'palegreen','darkslateblue','paleturquoise','darkslategray','palevioletred','darkturquoise', 'rosybrown','fuchsia','royalblue','gainsboro','saddlebrown','ghostwhite','salmon','gold', 'honeydew','skyblue','hotpink','slateblue','indianred','slategray','indigo','snow','ivory', 'sandybrown','goldenrod','seagreen','gray','seashell','green','sienna','greenyellow','silver', 'springgreen','khaki','steelblue','lavender','tan','lavenderblush','teal','lawngreen','thistle', 'lightgoldenrodyellow','white','lightgreen','whitesmoke','lightgrey','yellow','lightpink','yellowgreen', 'lemonchiffon','tomato','lightblue','turquoise','lightcoral','violet','lightcyan','wheat');

			$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			$moneda1=mysqli_result($res_resultado,0, "simbolo");
			$moneda2=mysqli_result($res_resultado, 1, "simbolo");

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>$moneda1, 2=>$moneda2);
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	
$data=array();

$x=0;

/*Recorro todas las ventas del mes agrupadas por cliente, para cada cliente sumo las ventas del mes y las agrego a un array*/
	 $sel_clientesmes="SELECT clientes.codcliente,clientes.empresa as empresa,nombre,apellido FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente AND fecha 
	 >='".$startTime."' AND fecha <='".$endTime."' group by facturas.codcliente order by facturas.codcliente ASC";

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
					


			$sel_resultado="SELECT codfactura,clientes.nombre as nombre,facturas.fecha as fecha,totalfactura,estado,facturas.tipo,facturas.iva,facturas.moneda,clientes.empresa,clientes.apellido
			FROM facturas,clientes WHERE facturas.borrado=0 AND facturas.codcliente=clientes.codcliente 
			AND fecha >='".$startTime."' AND fecha <='".$endTime."' AND facturas.codcliente= '".$codcliente."'";


			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 

			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime(mysqli_result($res_resultado, $contador, "fecha"))));
			
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."'";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
   		while ($row=mysqli_fetch_array($res_tipocambio)) {
   			$tipocambio=$row['valor'];
   		}
				//$tipoc=$tipo[mysql_result($res_resultado,$contador,"tipo")];
			/*IVA*/
	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			
					 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_iva, 0, "valor"));
					 if (mysqli_result($res_resultado, $contador, "moneda")==1){
					 $Iva_Ventas+=$iva;		 
					 $Ventas= number_format($iva,2,",",".");
					 } else {
					 $Iva_Ventas+=$iva*$tipocambio;
					 $Ventas= number_format($iva*$tipocambio,2,",",".");
					 }
					 $iva=0;					
			
			/*Importe*/
					 $total= mysqli_result($res_resultado, $contador, "totalfactura");
					 if (mysqli_result($res_resultado, $contador, "moneda")==1){
					 @$datay[$x]+=$total;		 
					 } else {
					 @$datay[$x]+=$total*$tipocambio;
					 }
					 $total=0;
					 
			$contador++;		
			}
			$data[$x]=array('datos'=>$datay[$x] , 'detalles'=>array('cliente'=>$codcliente, 'nombre'=> $nombre));
			@$importe+=$datay[$x];
			//$datax[$x]=$datay[$x];
			//$name[$x]=$nombre; //.' (%.1f%%)';
			$color[$x]=$Pinto[$x];

			
			
	$codcliente='';
	$contador_clientesmes++;
	$x++;
	}

			$importe=number_format($importe,2,",",".");

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


$tmpx=array();
$tmpy=array();
$tmpname=array();
$x=0;
//arsort($datay);

foreach ($data as $clave => $fila) {
    $monto[$clave] = $fila['datos'];
    $detalles[$clave] = $fila['detalles'];
}
array_multisort($monto, SORT_DESC, $detalles, SORT_ASC, $data);
//echo  "ver array<br>";
	foreach($monto as $key=>$value){
		       //si es un elemento lo muestro
		$tmpx[$x] = "$ ". number_format($value,2,",",".")." - ".$detalles[$key]['nombre'];
		$tmpy[$x]=$value;
		$tmpname[$x]=$detalles[$key]['nombre'].' (%.1f%%)';	
		$targ[$x]="javascript:window.parent.OpenNote('detalles.php?c=".$detalles[$key]['cliente']. "&fechaini=".$startTime."&fechafin=".$endTime."','70%','70%');";
//		$targ[$x]="javascript:alert('detalles.php?c=".$detalles[$key]['cliente']. "&fechaini=".$startTime."&fechafin=".$endTime."','70%','70%');";
		//echo "<br>";
		$alt[$x]="val=$ %d";
		//echo $key.'=>'.$value.' - '.$targ[$key].'<br>';
	$x++;
	}



		$datax=$tmpx;
		$datay=$tmpy;
		$name=$tmpname;	

//recorro($data);
//recorro($cliente);
//recorro($Pinto);


if ($datay!='-') {

include ("../jpgraph/jpgraph.php");
include ("../jpgraph/jpgraph_pie.php");
include ("../jpgraph/jpgraph_pie3d.php");

// Create the Pie Graph.
$graph = new PieGraph(890,770);
$graph->SetAntiAliasing();
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set($m);
$graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
$graph->title->SetColor('black');
$graph->title->SetMargin(10);

$graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->subtitle->Set('Importe Total $ '.$importe);


// Create pie plot
$p1 = new PiePlot($datay);

// Set the scatter plot image map targets
$p1->SetCSIMTargets($targ,$alt);

$p1->SetSize(0.3);
$p1->SetCenter(0.5,0.32);


$p1->ExplodeAll(5);
$p1->SetShadow(); 

$p1->SetSliceColors($color);

$p1->SetGuideLines(true,false);
$p1->SetGuideLinesAdjust(1.1);

$p1->SetStartAngle(200);

// Setup the labels to be displayed
$p1->SetLabels($name);


$p1->SetLegends($datax);
$graph->legend->SetPos(0.39,0.97,'center','bottom');
$graph->legend->SetColumns(2);

// This method adjust the position of the labels. This is given as fractions
// of the radius of the Pie. A value < 1 will put the center of the label
// inside the Pie and a value >= 1 will pout the center of the label outside the
// Pie. By default the label is positioned at 0.5, in the middle of each slice.
$p1->SetLabelPos(0.55);

// Setup the label formats and what value we want to be shown (The absolute)
// or the percentage.
$p1->SetLabelType(PIE_VALUE_PER);
$p1->value->Show();
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
$p1->value->SetColor('black');


// Add and stroke
$graph->Add($p1);
//$graph->Stroke();
$graph->StrokeCSIM();


}

?>