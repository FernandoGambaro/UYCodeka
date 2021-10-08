<?php
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

$version=$s->data['version'];

$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$ShowName=$UserNom. " " .$UserApe;

include "../conectar.php";  
include "../funciones/fechas.php";
include "../feedback/parametros.php";


function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$titulo="Listado de artículos";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

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
require_once ('PHPExcel.php');
// Create new PHPExcel object
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
         'style' => PHPExcel_Style_Border::BORDER_THICK,
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



$colaboradorform=@$_GET["colaboradorform"];
$fechaform=@$_GET["fechaform"];


$file=$_GET["file"];


$where="1=1 AND borrado=0 ";
if ($colaboradorform <> "") { $where.=" AND colaborador='".$colaboradorform."'"; }
if ($fechaform<> "") { $where.=" AND fecha = '".$fechaform."'"; }

$detalle="Evaluación";

						 $sel_resultado="SELECT * FROM feedback WHERE borrado=0 AND ".$where;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);

						   $query_familias="SELECT * FROM formularios WHERE borrado=0 AND codformulario='".mysqli_result($res_resultado, 0, "codformulario")."'";
							$res_familias=mysqli_query($GLOBALS["___mysqli_ston"], $query_familias);	
							$evaluacion=$tipo[mysqli_result($res_resultado, 0, "tipo")]." - ".mysqli_result($res_familias, 0, "descripcion");						   

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setTitle('Resumen de cuenta ');

					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('imagen');
					$objDrawing->setDescription('');
					$imagen ="../tmp/tmpimage.png";
					$objDrawing->setPath($imagen);
					$objDrawing->setCoordinates('A1');
					$objDrawing->setHeight(150);
					$objDrawing->setWidth(705);

					$offsetX =0;
					$objDrawing->setOffsetX($offsetX); 
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(55);

$x=2;
$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(16);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(12);

	$sql_col="SELECT nombre,apellido FROM usuarios WHERE codusuarios='".mysqli_result($res_resultado, 0, "colaborador")."'";
	$res_col=	mysqli_query($GLOBALS["___mysqli_ston"], $sql_col);					
	$nombre=mysqli_result($res_col, 0, "nombre"). " - ".mysqli_result($res_col, 0, "apellido");

		$cargo= mysqli_result($res_familias, 0, "descripcion");

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
$contador=0;
$z=0;

while ($contador < mysqli_num_rows($res_resultado)) { 

	if(mysqli_result($res_resultado, $contador, "nivel")!='') {
		$sql_feedback="SELECT competencias, definicion FROM feedbackform WHERE codformulario=".mysqli_result($res_resultado, $contador, "codformulario")." AND fila=".(mysqli_result($res_resultado, $contador, "fila")+1);
		$res_feedback=mysqli_query($GLOBALS["___mysqli_ston"], $sql_feedback);

		// Add some data
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
		
			if($nivel[$y]!=0) {
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "✓ ");
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
} else {
		$color=array(0=>"00FF00",1=>"FFCC99", 2=>"CC99FF");
		$sql_feedback="SELECT competencias, definicion FROM feedbackform WHERE codformulario=".mysqli_result($res_resultado, $contador, "codformulario")." AND fila=".(mysqli_result($res_resultado, $contador, "fila")+1);
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
/* Finaliza */	
}


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

$objWriter->save('../tmp/'.$file.'.xlsx');
//$objWriter->save('php://output');


?>