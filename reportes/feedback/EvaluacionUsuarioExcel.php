<?php
require_once('../../class/class_session.php');
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

$version=$s->data['version'];

$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$ShowName=$UserNom. " " .$UserApe;

include "../../conectar.php";  
include "../../funciones/fechas.php";
include "../../common/funcionesvarias.php";
include "../../feedback/parametros.php";

date_default_timezone_set("America/Montevideo"); 

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$titulo="---";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";
$resumen=array();

$archivo="Evaluacion";
$file="test";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 


//$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
//$rs_datos=mysql_query($sql_datos);


if (PHP_SAPI == 'cli')
	die('Solo desde un navegador');
/** Include PHPExcel */

//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
// Create new PHPExcel object
require_once ('../../excel/PHPExcel.php');
require_once ('../../excel/PHPExcel/Chart.php');
$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator($ShowName)
							 ->setLastModifiedBy($ShowName)
							 ->setTitle($titulo)
							 ->setSubject($asunto)
							 ->setDescription($descripcion)
							 ->setKeywords($palabraclave)
							 ->setCategory($categoria);

$border_grueso= array(
  'borders' => array(
      'outline' => array(
         'style' => PHPExcel_Style_Border::BORDER_THIN,
         'color' => array('rgb' => '000000'),
      ),
   ),
);

$border_fino= array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array('rgb' => '000000'),
    )
  )
);



$title="Evaluación";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

    header("Content-type:".mysqli_result($rs_datos, 0,'fototype'));
    header('Content-Disposition: inline; filename="'.mysqli_result($rs_datos, 0,'fotoname').'"');
    $imagen= mysqli_result($rs_datos, 0,'fotocontent');	


$codusuario=isset($_GET['codusuario']) ? $_GET['codusuario'] : null ;
$fechaform=isset($_GET['fechaform']) ? $_GET['fechaform'] : null ;

$file=$_GET["file"];


$where="1=1 AND feedback.borrado=0 ";
if ($codusuario <> "") { $where.=" AND feedback.colaborador='".$codusuario."'"; }
if ($fechaform<> "" and $fechaform != "undefined") { $where.=" AND feedback.fecha = '".$fechaform."'"; }

$detalle="Evaluación";

$sql="SELECT * FROM feedback INNER JOIN formularios on formularios.codformulario=feedback.codformulario WHERE 
 ".$where." AND formularios.tipo=0 GROUP BY feedback.fecha, feedback.colaborador, feedback.codformulario ORDER BY feedback.fecha ASC";
$res_res=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$rescont=0;
$Eval=array();

while ($rescont < mysqli_num_rows($res_res)) {
		$fecha=mysqli_result($res_res, $rescont, "fecha");
		$codformulario=mysqli_result($res_res, $rescont, "codformulario");
		
				 $sel_resultado="SELECT * FROM feedback INNER JOIN formularios on feedback.codformulario=formularios.codformulario WHERE feedback.borrado=0 
				 AND  feedback.colaborador='".$codusuario."' AND feedback.fecha = '".$fecha."' AND formularios.tipo=0 AND feedback.codformulario='".$codformulario."'";
				$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);

$contador=0;
//$objPHPExcel->getActiveSheet()->setCellValue('G1', ": ".$sel_resultado);
				/*
				$query_familias="SELECT * FROM formularios WHERE borrado=0 AND formularios.tipo=0 AND codformulario='".$codformulario."'";
				$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);	
				*/
				$evaluacion=$tipo[mysqli_result($res_resultado, 0, "tipo")]." - ".mysqli_result($res_resultado, 0, "descripcion");						   

	if ($rescont==0) {
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle(date('d-m-Y', strtotime($fecha)));
	} else {
	$objPHPExcel->createSheet($rescont);
	$objPHPExcel->setActiveSheetIndex($rescont);
	$objPHPExcel->getActiveSheet()->setTitle(date('d-m-Y', strtotime($fecha)));
	}

if(file_exists($imagen)) {
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					//$imagen ="../tmp/tmpimage.png";
					$objDrawing->setPath($imagen);
					$objDrawing->setCoordinates('A1');
					$objDrawing->setHeight(150);
					$objDrawing->setWidth(705);

					$offsetX =0;
					$objDrawing->setOffsetX($offsetX); 
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(55);
}
		$x=3;
		$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(16);
		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(12);
	
		$sql_col="SELECT nombre,apellido FROM usuarios WHERE codusuarios='".mysqli_result($res_resultado, 0, "colaborador")."'";
		$res_col=	mysqli_query($GLOBALS["___mysqli_ston"], $sql_col);					
		$nombre=mysqli_result($res_col, 0, "nombre"). " - ".mysqli_result($res_col, 0, "apellido");

		$cargo= mysqli_result($res_resultado, 0, "descripcion");
		$FechaEval=mysqli_result($res_resultado, 0, "fecha");
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Colaborador: ".$nombre);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($x+1), "Cargo: ".$cargo);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($x+2), "Semana del: ".implota(mysqli_result($res_resultado, 0, "fecha")));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':A'.($x+2))->applyFromArray($border_grueso);
		
		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setSize(16);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_grueso);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Seguimiento semanal para la gestión del desempeño");
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':I'.($x+2));
		$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
		);
		//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		
		
		cellColor('B'.$x.':I'.($x+2), 'CCCCCC');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.($x+2))->applyFromArray($border_grueso);
		//$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
		
		$x=$x+3;

		$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':A'.$x)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':A'.$x)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Competencias a evaluar ");
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':G'.$x)->getFont()->setName('Arial');
		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':G'.$x)->getFont()->setSize(12);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':G'.$x)->applyFromArray($border_grueso);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Definicion");
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':G'.$x);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Nivel de desarrollo ");
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':H'.$x)->applyFromArray($border_grueso);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->getStyle('I'.$x.':I'.$x)->applyFromArray($border_grueso);

		$x++;
		$z=0;
		$yy=1;
			while ($contador < mysqli_num_rows($res_resultado)) { 
			
				if(mysqli_result($res_resultado, $contador, "nivel")!='') {
					$sql_feedback="SELECT competencias, definicion FROM feedbackform WHERE codformulario=".$codformulario." 
					AND fila=".mysqli_result($res_resultado, $contador, "fila") . " " ;
					$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);

					$resumen[$yy]=mysqli_result($res_feedback, 0, "competencias");	
			
					$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':A'.$x)->getFont()->setName('Arial');
					$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':A'.$x)->getFont()->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':A'.$x)->applyFromArray($border_grueso);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':G'.$x)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, mysqli_result($res_feedback, 0, "competencias"));
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$x.':A'.($x+3));
					$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->applyFromArray(
					    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
					);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			
				
					$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':G'.$x)->getFont()->setName('Arial');
					$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':G'.$x)->getFont()->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':G'.$x)->applyFromArray($border_grueso);
					$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':G'.$x)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, mysqli_result($res_feedback, 0, "definicion"));
					$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':G'.($x+3));
					$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
					    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
					);		
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
					
					$nivel=explode("-",mysqli_result($res_resultado, $contador, "nivel"));
					$y=0;
					$Tipo = array(
					0=>"Bajo",
					1=>"Medio",
					2=>"Alto",
					3=>"Destacado");
					/*Guardo en un array la competencia a evaluar*/
					foreach($Tipo as $i) {
					$objPHPExcel->getActiveSheet()->getDefaultStyle('H'.$x.':H'.$x)->getFont()->setName('Arial');
					$objPHPExcel->getActiveSheet()->getDefaultStyle('H'.$x.':H'.$x)->getFont()->setSize(12);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':H'.$x)->applyFromArray($border_grueso);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, $Tipo[$y]." ");
					$objPHPExcel->getActiveSheet()->mergeCells('H'.$x.':H'.$x);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
					    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
					);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		

						if(@$nivel[$y]!=0) {
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "✓ ");
							$Eval[$FechaEval][$yy]=$y;
												
			cellColor('H'.$x.':I'.$x, 'CCCCCC');				
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
						}else {
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, " ");
						}
					$objPHPExcel->getActiveSheet()->getStyle('I'.$x.':I'.$x)->applyFromArray($border_grueso);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
					
					$x++;
					$y++;
					}
					$x--;
					$yy++;
			} else {
					$color=array(0=>"00FF00",1=>"FFCC99", 2=>"CC99FF");
					$sql_feedback="SELECT competencias, definicion FROM feedbackform WHERE codformulario=".$codformulario." 
					AND fila=".(mysqli_result($res_resultado, $contador, "fila"));
					$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);
			
			
				if(mysqli_result($res_feedback, 0, "competencias")!='') {
				$aspectos= mysqli_result($res_feedback, 0, "competencias");
				} else { 
				$aspectos= mysqli_result($res_feedback, 0, "definicion");
				}
			
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_grueso);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $aspectos);
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$x.':I'.$x);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			
			cellColor('A'.$x.':I'.$x, $color[$z]);
			$x++;
			
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setSize(12);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_grueso);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, mysqli_result($res_resultado, $contador, "aspectos"));
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$x.':I'.$x);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_fino);
			$z++;
			}
			
			$x++;
			$contador++;
			/* Finaliza 	*/
			}
$rescont++;
}


	$objWorksheet =$objPHPExcel->createSheet($rescont);
	//$objPHPExcel->setActiveSheetIndex($rescont);
//	$objPHPExcel->getActiveSheet()->setTitle('Resumen');


if(file_exists($imagen)) {
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('');
					$objDrawing->setDescription('');
					//$imagen ="../tmp/tmpimage.png";
					$objDrawing->setPath($imagen);
					$objDrawing->setCoordinates('A1');
					$objDrawing->setHeight(150);
					$objDrawing->setWidth(705);

					$offsetX =0;
					$objDrawing->setOffsetX($offsetX); 
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(55);
}


/***********************************************/

//$objWorksheet = $objPHPExcel->getActiveSheet();

$contarElementosResumen=count($resumen);
foreach($resumen as $clave => $valor) {
	$objWorksheet ->setCellValue('A'.($clave+1), $valor." ");
}
	$objWorksheet->getColumnDimension('A')->setAutoSize(true);		

$column = 'A';
$adjust = 1;
//$column = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($column) + $adjust -1);
$contarElementosEval=count($Eval);
if(is_array($Eval )) {					
	foreach($Eval as $clave => $valor) {
		if(is_array($valor)) {
			/*La clave del array el la fecha*/
				$column = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($column) + $adjust -1);
				$objWorksheet ->setCellValue($column."1", implota($clave)." ");
				/*Comienzo a mostar las evaluaciones en la celda nº dos*/
			$xz=2;
			foreach($valor as $key => $value){
				$objWorksheet ->setCellValue($column.$xz, $value." ");
				$xz++;
			}
		} 
			$objWorksheet->getColumnDimension($column)->setAutoSize(true);		

	}
}

//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataseriesLabels1=array();
$column = 'A';
$adjust = 1;

for($x=0;$x<$contarElementosEval; $x++) {
	$column = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($column) + $adjust -1);
	array_push($dataseriesLabels1 , new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$'.$column.'$1', null, 1));
}

//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues1 = array(
	new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$A$2:$A$'.($contarElementosResumen+1), null, ($contarElementosResumen+1)),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker

$dataSeriesValues1=array();
$column = 'A';
$adjust = 1;
				//$objWorksheet ->setCellValue("F1", $contarElementosResumen." ");

for($x=0;$x<$contarElementosResumen; $x++) {
	$column = PHPExcel_Cell::stringFromColumnIndex(PHPExcel_Cell::columnIndexFromString($column) + $adjust -1);
	array_push($dataSeriesValues1 , new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$'.$column.'$2:$'.$column.'$'.($contarElementosResumen+1), null, ($contarElementosResumen+1)));
}
//	Build the dataseries
$series1 = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_BARCHART,		// plotType
	null, //PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues1)-1),			// plotOrder
	$dataseriesLabels1,								// plotLabel
	$xAxisTickValues1,								// plotCategory
	$dataSeriesValues1								// plotValues
);

$layout1 = new PHPExcel_Chart_Layout();
$layout1->setShowVal(TRUE);      // Initializing the data labels with Values
//$layout1->setShowPercent(TRUE);  // Initializing the data labels with Percentages

//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
//	Set the series in the plot area
$plotarea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
//	Set the chart legend
$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, null, false);

$title1 = new PHPExcel_Chart_Title('Evolución de las evaluaciones');
$yAxisLabel1 = new PHPExcel_Chart_Title('Evaluación');
//	Create the chart
$chart1 = new PHPExcel_Chart(
	'Gráfico',		// name
	$title1,		// title
	$legend1,		// legend
	$plotarea1,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	null,			// xAxisLabel
	$yAxisLabel1	// yAxisLabel
);
//	Set the position where the chart should appear in the worksheet
$chart1->setTopLeftPosition('A9');
$chart1->setBottomRightPosition('F30');
//	Add the chart to the worksheet
$objWorksheet->addChart($chart1);


/*************************************/
	$objPHPExcel->setActiveSheetIndex($rescont);
	$objPHPExcel->getActiveSheet()->setTitle('Resumen');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$archivo.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('../../tmp/'.$file.'.xlsx');
//$objWriter->save('php://output');


?>
