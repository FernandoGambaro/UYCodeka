<?php
session_start();
/*

require_once('../class/class_session.php');*/
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*//*
if (!$s = new session()) {*/
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  *//*
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$ShowName=$UserNom. " " .$UserApe;
*/

$ShowName=" - ";

include("../conexion.php");
include("../funcionesvarias.php");



$anio=$_GET['anio'];
$titulo=$_GET['titulo'];


$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

$DIA='';
$ORGANIZACIONHORA='';
$ORGANIZACIONDIA='';
$SALA='';

$archivo=str_replace(" ", "-", $titulo.$anio);
$file=$_GET['file'];

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 


if (PHP_SAPI == 'cli')
	die('Solo desde un navegador');

require_once('PHPExcel.php');
include('PHPExcel/Writer/Excel2007.php');

$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator($ShowName)
							 ->setLastModifiedBy($ShowName)
							 ->setTitle($titulo)
							 ->setSubject($asunto)
							 ->setDescription($descripcion)
							 ->setKeywords($palabraclave)
							 ->setCategory($categoria);

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

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

$tipotitulo = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF'),
        'size'  => 16,
        'name'  => 'Verdana'
    ));

$tiponormal = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => 'Verdana'
    ));


//$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 


//$objPHPExcel->getActiveSheet();

$objPHPExcel->createSheet();
$hoja=0;

$sel_resultado="SELECT * FROM `ORGANIZACION` WHERE `ANIOID` = 2016 GROUP BY ACTIVIDADID";
$contador=0;
$res_resultado = mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	while ($contador < mysqli_num_rows($res_resultado)) { 

	$ACTIVIDADID=mysqli_result($res_resultado, $contador, "ACTIVIDADID");

		$c_usuario=" SELECT * FROM `ACTIVIDAD` WHERE `ACTIVIDADID`= '".$ACTIVIDADID."'";
		$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
		while($r_usuario= mysqli_fetch_array($b_usuario)){
			$ACTIVIDADCOD=$r_usuario['ACTIVIDADCOD'];
			$ACTIVIDADDESCRIPCION=substr($r_usuario['ACTIVIDADDESCRIPCION'], 0, 30);
			$ACTIVIDAD=$r_usuario['ACTIVIDADDESCRIPCION'];
			}

$sheet = $objPHPExcel->getSheet($hoja);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$logo = '../css3/images/tmp/logo.png'; // Provide path to your logo file
$objDrawing->setPath($logo);  //setOffsetY has no effect
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(50); // logo height
$objDrawing->setWorksheet($sheet); 

$sheet->setTitle($ACTIVIDADDESCRIPCION);


//$sheet->getStyle("A1:D1")->applyFromArray($border_grueso);

$sheet->setCellValue('C1', " Organización año ".$anio);
$sheet->getDefaultStyle("C1:D1")->applyFromArray($tipotitulo);
//$sheet->getDefaultStyle("C1:D1")->getFont()->setName('Arial');
//$sheet->getDefaultStyle("C1:D1")->getFont()->setColor('ffffff');
//$sheet->getDefaultStyle("C1:D1")->getFont()->setSize(16);

$sheet->mergeCells('C1:D1');
$sheet->getStyle('C1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getRowDimension('1')->setRowHeight(40);

cellColor('A1:D1', '194685');

/*               */
$sheet->setCellValue('B3', "Actividad: ".$ACTIVIDAD);
$sheet->getDefaultStyle("C1:D1")->applyFromArray($tiponormal);
$sheet->getDefaultStyle("B3:D3")->getFont()->setSize(12);

$sheet->mergeCells('B3:D3');
$sheet->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);


$x=5;
$sheet->getRowDimension($x)->setRowHeight(-1);
//$sheet->getRowDimension('B'.$x)->setRowHeight(-1);
$sheet->getDefaultStyle('A'.$x.':D'.$x)->getFont()->setSize(10);

//$sheet->setCellValue('A'.$x, "Nº");
$sheet->setCellValue('B'.$x, "Cargo");
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getStyle('B1:B'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);

$sheet->setCellValue('C'.$x, "Nº");
$sheet->getColumnDimension('C')->setWidth(12);

$sheet->setCellValue('D'.$x, "Nombre/s y Apellido/s");
$sheet->getColumnDimension('D')->setWidth(45);


cellColor('B'.$x.':D'.$x, 'CCCCCC');
$sheet->getStyle('B'.$x.':D'.$x)->applyFromArray($border_grueso);
$sheet->getRowDimension($x)->setRowHeight(20);


$x++;

		$ssql=" SELECT * FROM `ORGANIZACION` WHERE `ANIOID` = 2016 AND `ACTIVIDADID` = '".$ACTIVIDADID."'  ORDER BY `CALIDADID` ASC, `CONTACTOSID` ASC"; //ORDER BY `$ordenado` $orden";
		$rs1 = mysqli_query($GLOBALS["___mysqli_ston"], $ssql);
		while($DATA = mysqli_fetch_array($rs1)){

			$c_usuario=" SELECT * FROM `CONTACTOS` WHERE `CONTACTOSID`= '$DATA[CONTACTOSID]'";
			$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
				while($r_usuario= mysqli_fetch_array($b_usuario)){
				$NUMERO=$r_usuario['CONTACTOSID'];
				$NOMBRE=trim($r_usuario['CONTACTOSNOMBRE'])."  ".trim($r_usuario['CONTACTOSAPELLIDO']);
				}
				$c_usu=" SELECT * FROM `CALIDAD` WHERE `CALIDADID`= '".$DATA['CALIDADID']."'";
				$b_usu = mysqli_query($GLOBALS["___mysqli_ston"], $c_usu);
				while($r_usu= mysqli_fetch_array($b_usu)){
				$CALIDAD=trim($r_usu['CALIDADDESCRIPCION']);
				}
				if ($DATA['CONTACTOSID']!=0) {


// Add some data

$sheet->setCellValue('B'.$x," ".$CALIDAD);
$sheet->getStyle('B'.$x.':B'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);

$sheet->setCellValue('C'.$x, $NUMERO);
$sheet->getColumnDimension('C')->setAutoSize(true);

$sheet->getStyle('C'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$sheet->setCellValue('D'.$x," ".$NOMBRE);
$sheet->getColumnDimension('D')->setWidth(45);
$sheet->getStyle('D'.$x.':D'.$sheet->getHighestRow())->getAlignment()->setWrapText(true);

//$sheet->getColumnDimension('D')->setAutoSize(true);

$sheet->getStyle('A'.$x.':D'.$x)->applyFromArray($border_fino);
				}

	$x++;
}


$hoja++;
$contador++;

$objPHPExcel->createSheet($hoja);
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