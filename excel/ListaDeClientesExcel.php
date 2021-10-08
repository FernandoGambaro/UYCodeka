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

include ("../conectar.php");  
include ("../funciones/fechas.php");

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$titulo="Listado de clientes";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

$archivo="Lista102015";
//$file="Lista de clientes";

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 

if (PHP_SAPI == 'cli')
	die('Solo desde un navegador');

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

$title="Listado de Clientes";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

//codcliente="+codcliente+"&nombre="+nombre+"&nif="+nif+"&telefono="+telefono
$codcliente=isset($_GET['codcliente']) ? $_GET['codcliente'] : null ;
$nombre=isset($_GET['nombre']) ? $_GET['nombre'] : null ;
$nif=isset($_GET['nif']) ? $_GET['nif'] : null ;
$telefono=isset($_GET['telefono']) ? $_GET['telefono'] : null ;

//$localidad=@$_GET["localidad"];

$file= $archivo=$_GET["file"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."%'"; }
if ($nif > "0") { $where.=" AND nif='$nif'"; }
//if ($localidad > "0") { $where.=" AND localidad='$localidad'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

$where.=" ORDER BY codcliente ASC";
$detalle="Listado de clientes";

$objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setTitle('Listado de clientes ');
$celda="H";

$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:".$celda."1")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:".$celda."1")->getFont()->setSize(24);
$objPHPExcel->getActiveSheet()->getStyle("A1:".$celda."1")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('A1', $razonsocial);
$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$celda."1");
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B3', "Dirección: ". $direccion);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B4', "Teléfono/s ". $telefono." - ". $fax);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B5")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B5")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B5', "email:".$email);
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Web: ". $web);
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

$x=8;

$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $detalle);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(15);
cellColor('B'.$x, 'B2B2B2');

$x=10;
$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(16);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Cod.");
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Nombre - Empresa");
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Documento");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Dirección");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Departamento");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Email");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, "Teléfono");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Fax");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


cellColor('A'.$x.':'.$celda.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);


$x++;

$sel_resultado="SELECT * FROM clientes WHERE `borrado`=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;

while ($contador < mysqli_num_rows($res_resultado)) {

// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, mysqli_result($res_resultado, $contador, "codcliente")." ");
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".mysqli_result($res_resultado, $contador, "nombre")." ".mysqli_result($res_resultado, $contador, "apellido")." - "." ".mysqli_result($res_resultado, $contador, "empresa"));
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".mysqli_result($res_resultado, $contador, "nif"));
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " ".mysqli_result($res_resultado, $contador, "direccion"));
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


		$nombreprovincia='';
		if(mysqli_result($res_resultado, $contador, "codprovincia")>0) {
		  	$query_provincias="SELECT * FROM provincias WHERE codprovincia='".mysqli_result($res_resultado, $contador, "codprovincia")."'";
			$res_provincias=mysqli_query($GLOBALS["___mysqli_ston"], $query_provincias);
			$nombreprovincia=mysqli_result($res_provincias, 0, "nombreprovincia");
		} 

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " ".$nombreprovincia);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " ".mysqli_result($res_resultado, $contador, "email"));
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  " ".mysqli_result($res_resultado, $contador, "telefono"));
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,  " ".mysqli_result($res_resultado, $contador, "fax"));
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_fino);

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