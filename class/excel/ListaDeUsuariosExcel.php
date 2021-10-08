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

						$Tipox = array(0=>'Seleccione uno',1=>"Usuario-Empleado",2=>"Administrador",3=>"Vendedor",4=>"Asistente",5=>"Administrativo",
						6=>'GERENTE O ENCARGADO DE SUCURSAL', 7=>'SUB JEFE ENCARGADO DE SECCION', 8=>'SUB JEFE O ENCARGADO DE ESCRITORIO', 9=>'AUXILIAR DE 1era.',
						10=>'AUX. DE MARCAS DE 1era',11=>'AUX. DE MARCAS DE 2da',12=>'AUX. VENTAS', 13=>'VENDEDOR DE 1ra.', 14=>'VENDEDOR DE 2da.',15=>'JEFE DE ESCRITORIO',
						 16=>'SUB JEFE ENCARGADO DE LOGISTICA', );

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}


$titulo="Listado de usuario";
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

$title="Listado de Usuarios";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

//nombre+"&apellido="+apellido+"&usuario="+usuario+"&telefono="+telefono

$nombre=isset($_GET['nombre']) ? $_GET['nombre'] : null ;
$apellido=isset($_GET['apellido']) ? $_GET['apellido'] : null ;
$usuario=isset($_GET['usuario']) ? $_GET['usuario'] : null ;
$telefono=isset($_GET['telefono']) ? $_GET['telefono'] : null ;

$file= $archivo=$_GET["file"];

$where="1=1";
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."'"; }
if ($apellido <> "") { $where.=" AND apellido like '%".$provincia."%'"; }
if ($usuario <> "") { $where.=" AND usuario like '%".$usuario."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

$where.=" ORDER BY codusuarios ASC";
$detalle="Listado de usuarios";

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
$celda='H';

$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setSize(15);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $detalle);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(15);
cellColor('B'.$x, 'B2B2B2');

$x=4;
$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(16);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Nombre");
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Apellido");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Estado");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Tratamiento");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Email");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, "Teléfono");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Usuario");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

cellColor('A'.$x.':'.$celda.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);

$x++;
$sel_resultado="SELECT * FROM usuarios WHERE `borrado`=0 ";//.$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;
$estado=array(0=>'Activo', 1=>'Baja');

while ($contador < mysqli_num_rows($res_resultado)) {

// Add some data
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".mysqli_result($res_resultado, $contador, "nombre"));
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".mysqli_result($res_resultado, $contador, "apellido"));
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " ".$estado[mysqli_result($res_resultado, $contador, "estado")]);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

if(mysqli_result($res_resultado, $contador, "tratamiento")!='') {
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  " ".$Tipox[mysqli_result($res_resultado, $contador, "tratamiento")]);
} else {
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  " ");
}
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " ".mysqli_result($res_resultado, $contador, "email"));
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  " ".mysqli_result($res_resultado, $contador, "telefono"));
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,  " ".mysqli_result($res_resultado, $contador, "usuario"));
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