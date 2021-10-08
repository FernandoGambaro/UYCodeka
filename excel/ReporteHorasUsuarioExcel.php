<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 


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

include ("../funciones/fechas.php");
include ("../conectar.php");
require("../funciones/funcionesvarias.php");

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tratamiento!=2 ORDER BY `codusuarios` ASC";
$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);
$num_usuario = mysqli_num_rows($res_usuario);

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

$fechainicio=isset($_GET["fechainicio"]) ? $_GET["fechainicio"] : null ;
$fechafin=isset($_GET["fechafin"]) ? $_GET["fechafin"] : null ;


function lastDateOfMonth($Month, $Year=-1) {
    if ($Year < 0) $Year = 0+date("Y");
    $aMonth         = mktime(0, 0, 0, $Month, 1, $Year);
    $NumOfDay       = 0+date("t", $aMonth);
    $LastDayOfMonth = mktime(0, 0, 0, $Month, $NumOfDay, $Year);
    return $LastDayOfMonth;
}

if($fechainicio<>"") {
$primer_dia=explota($fechainicio);	

} else {
$primer_dia="".$anio."-".$mes."-01";
}
if($fechafin<>"") {
$ultimo_dia=explota($fechafin);
$detalle="las fechas ".$fechainicio." y ".$fechafin;
} else {
$ultimo_dia=date("Y-n-j", lastDateOfMonth($mes,$anio));
$detalle="el mes de ".mes($mes) . " - ".$anio;
}
//$ultimo_dia="".$anio."-".$mes."-".date('t');





$dividefecha = explode("-", explota($fechainicio));
$dividefecha1 = explode("-", explota($fechafin));
 
// $dividefecha[0] = Mes
// $dividefecha[1] = Dia
// $dividefecha[2] = Ano
 
$fecha_previa = mktime(0, 0, $dividefecha[0], $dividefecha[1], $dividefecha[2]); //Convertimos $fecha_desde en formato timestamp
$fecha_hasta = mktime(0, 0, $dividefecha1[0], $dividefecha1[1], $dividefecha1[2]); //Convertimos $fecha_desde en formato timestamp
 
$segundos = $fecha_previa - $fecha_hasta; // Obtenemos los segundos entre esas dos fechas
$segundos = abs($segundos); //en caso de errores
 
$semanas = floor($segundos / 604800); //Obtenemos las semanas entre esas fechas.

if($semanas==0) { 
$semanas=1;
}

$archivo=str_replace(" ", "-", 'test'.$anio);
$file=$_GET['file'];


if (PHP_SAPI == 'cli')
	die('Solo desde un navegador');

require_once('PHPExcel.php');
include('PHPExcel/Writer/Excel2007.php');

$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator($ShowName)
							 ->setLastModifiedBy($ShowName)
							 ->setTitle('')
							 ->setSubject('')
							 ->setDescription('')
							 ->setKeywords('')
							 ->setCategory('');

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
        'size'  => 14,
        'name'  => 'Verdana'
    ));

$tiponormal = array(
    'font'  => array(
        'bold'  => false,
        'color' => array('rgb' => '000000'),
        'size'  => 11,
        'name'  => 'Verdana'
    ));


$objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setTitle('Detalles de horas');


$celda="J";

$objPHPExcel->getActiveSheet()->getDefaultStyle("B1:".$celda."1")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B1:".$celda."1")->getFont()->setSize(22);
$objPHPExcel->getActiveSheet()->getStyle("B1:".$celda."1")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('B1', $razonsocial);
$objPHPExcel->getActiveSheet()->mergeCells('B1:'.$celda."1");
$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B3', "Dirección: ". $direccion);
$objPHPExcel->getActiveSheet()->mergeCells('B3:'.$celda."3");
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B4', "Teléfono/s ". $telefono." - ". $fax);
$objPHPExcel->getActiveSheet()->mergeCells('B4:'.$celda."4");
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B5")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B5")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B5', "email:".$email);
$objPHPExcel->getActiveSheet()->mergeCells('B5:'.$celda."5");
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Web: ". $web);
$objPHPExcel->getActiveSheet()->mergeCells('B6:'.$celda."6");
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

$x=9;

$objPHPExcel->getActiveSheet()->getDefaultStyle("B7:".$celda."7")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B7:".$celda."7")->getFont()->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle("B7:".$celda."7")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('B7',  "Detalles de horas realizadas por usuario y por proyecto durante ".$detalle);
$objPHPExcel->getActiveSheet()->mergeCells('B7:'.$celda."7");
$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(14);

$col=1;
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
$fromCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;
$toCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;

$cellRange = $fromCell . ':' . $toCell;
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setSize(11);
		
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, $row_usuario['nombre']." ".$row_usuario['apellido']);
 //$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(25);

//$objPHPExcel->getActiveSheet()->getStyle($col.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);


cellColor($cellRange, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($border_grueso);

$col++;
	}

$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setSize(11);	
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, 'Total');
 //$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(25);
 $col++;
 $objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setSize(11);	
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, 'Asig.');
 //$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(25);

$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);

$x++;
$num=1;

 $sql_proyecto="SELECT * FROM clientes WHERE service=2 ORDER BY nombre ASC";
$res_proyecto=mysqli_query($GLOBALS["___mysqli_ston"], $sql_proyecto);
while ($row_proyecto=mysqli_fetch_array($res_proyecto))
{

	$col=0;
	$codcliente=$row_proyecto['codcliente'];
	$total_proyecto[$codcliente]=0;
	$nombre= $row_proyecto['nombre'];
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $nombre);

$fromCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;

$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x)->getFont()->setSize(12);		

$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tratamiento!=2 ORDER BY `codusuarios` ASC";
$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);

	$col=1;
	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
		$codusuarios=$row_usuario['codusuarios'];  
	
		
		$sql="SELECT horas FROM horas WHERE `codcliente`='".$codcliente."' AND `codusuario` ='".$codusuarios."' AND borrado=0  AND fecha >= '".$primer_dia."'  
		AND `borrado` = '0' AND fecha <= '".$ultimo_dia. "' ORDER BY `fecha` DESC";

      $ListarTotales = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setSize(9);		
	      
   	if (mysqli_num_rows($ListarTotales)>0) {
   		$total_usuario1="";

      while ($Controlrow = mysqli_fetch_array($ListarTotales))
      {
      	$total_usuario=SumaHoras($Controlrow['horas'],$total_usuario);
         $total_proyecto[$codcliente]=SumaHoras($total_proyecto[$codcliente],$Controlrow['horas']);
			$total_usuario_proyecto[$codusuarios]=SumaHoras($total_usuario_proyecto[$codusuarios],$Controlrow['horas']);         
      }
			$array = split(":", $total_usuario);
			$total_usuario1=($array[0]*60+$array[1])/60;
			$total_usuario1=number_format($total_usuario1, 2, '.', ' ');
	
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, $total_usuario1);
		$total_usuario="";
		$total_usuario1="";

	} else {
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, " ");
		
	}
	$total_usuario="";
	$col++;
	}
	$array = split(":", $total_proyecto[$codcliente]);
	$total_usuario1=($array[0]*60+$array[1])/60;
	$total_proyecto[$codcliente]=number_format($total_usuario1, 2, '.', ' ');
/* Calcular la direfencia de horas asignadas y realizadas y cambiar el color de fondo*/	
	$diferencia=$total_proyecto[$codcliente]-(int)$row_proyecto['horas']*$semanas;
	if($diferencia<>5) {
		$fromCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;
		$toCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;
		$cellRange = $fromCell . ':' . $toCell;
		if($diferencia >5) {
			$DifColor=sprintf("%02x%02x%02x", 255, 3*$diferencia,0);
			cellColor($cellRange, $DifColor);
		} elseif($diferencia<-5) {
			$DifColor=sprintf("%02x%02x%02x", 255+(3*$diferencia),255 ,0);
			cellColor($cellRange, $DifColor);
		}
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, $total_proyecto[$codcliente]);
	$col++;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, (int)$row_proyecto['horas']*$semanas);
	
	$toCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;
	$cellRange = 'A'.$x . ':' . $toCell;
	$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($border_fino);
			
//		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':H'.$x)->applyFromArray($border_fino);
	$x++;
	$num++;

}

$col=1;
$sql_usuario="SELECT * FROM `usuarios` WHERE `borrado` = '0' and tratamiento!=2 ORDER BY `codusuarios` ASC";
$res_usuario=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuario);

	while ($row_usuario=mysqli_fetch_array($res_usuario))
	{
$fromCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;
$toCell = PHPExcel_Cell::stringFromColumnIndex($col) . $x;

$cellRange = $fromCell . ':' . $toCell;
$codusuarios=$row_usuario['codusuarios'];
	$array = split(":", $total_usuario_proyecto[$codusuarios]);
	$total_usuario1=($array[0]*60+$array[1])/60;
	$total_usuario_proyecto[$codusuarios]=number_format($total_usuario1, 2, '.', ' ');

$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle($col, $x)->getFont()->setSize(9);		
		
 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $x, $total_usuario_proyecto[$codusuarios]);
 $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setWidth(25);

//$objPHPExcel->getActiveSheet()->getStyle($col.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);


cellColor($cellRange, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle($cellRange)->applyFromArray($border_grueso);

$col++;
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