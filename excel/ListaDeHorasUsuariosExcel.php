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

$title="Listado de Horarios";
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
$telefono=isset($_GET['telefono']) ? $_GET['telefono'] : null ;

$codusuarios=isset($_GET['codusuarios']) ? $_GET['codusuarios'] : null ;
$fechainicio=isset($_GET['fechainicio']) ? $_GET['fechainicio'] : null ;
$fechafin=isset($_GET['fechafin']) ? $_GET['fechafin'] : null ;

$codusuarios=isset($_GET['codusuarios']) ? $_GET['codusuarios'] : null ;


$file= $archivo=$_GET["file"];

$where=" WHERE  1=1";
$whereusuario=" WHERE  borrado=0 AND pin!='' ";

if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."'"; }
if ($apellido <> "") { $where.=" AND apellido like '%".$apellido."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

if ($codusuarios <> "") { $whereusuario=" AND codusuarios = '".$codusuarios."'"; }

if ($fechafin <> "" and $fechafin <> "" ) { $where.=" AND datetime <= '".$fechafin."' and  datetime >= '".$fechafin."'"; }
//if ($fechafin <> "") { $where.=" AND fechafin = '".$fechafin."'"; }


//$where.=" ORDER BY codusuarios ASC";
$detalle="Listado de usuarios";

	$sel_resulta="SELECT * FROM usuarios ".$whereusuario;
	$res_resulta=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resulta);
	$conta=0;

while ($conta < mysqli_num_rows($res_resulta)) {
		$usuario=mysqli_result($res_resulta, $conta, "nombre")." ".mysqli_result($res_resulta, $conta, "apellido");
		$codusuarios=mysqli_result($res_resulta, $conta, "codusuarios");
		
if ($conta==0) {
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle($usuario);
} else {
$objPHPExcel->createSheet($conta);
$objPHPExcel->setActiveSheetIndex($conta);
$objPHPExcel->getActiveSheet()->setTitle($usuario);
}

//$objPHPExcel->setActiveSheetIndex(0);
//$objPHPExcel->getActiveSheet();

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
	$celda='C';
	
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
	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Fecha");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Hora");
	
	cellColor('A'.$x.':'.$celda.$x, 'CCCCCC');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_grueso);
	$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
	
	$x++;
	$sel_resultado="SELECT * FROM `biometriclog` ".$where." AND codusuarios = '".$codusuarios."'";
	$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	$contador=0;
	
	while ($contador < mysqli_num_rows($res_resultado)) {
	
	// Add some data
if($whereusuario='') {	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, " ".$usuario);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
}	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".date( "d/m/Y", strtotime(mysqli_result($res_resultado, $contador, "datetime"))));
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".date( "H:i:s", strtotime( mysqli_result($res_resultado, $contador, "datetime") ))) ;
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	if(mysqli_result($res_resultado, $contador, "enddatetime")!='0000-00-00 00:00:00') {
		$x++;
		if($whereusuario='') {		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, " ".$usuario);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		}
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".date( "d/m/Y", strtotime(mysqli_result($res_resultado, $contador, "enddatetime"))));
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".date( "H:i:s", strtotime( mysqli_result($res_resultado, $contador, "enddatetime") ))) ;
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	
	}
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':'.$celda.$x)->applyFromArray($border_fino);
	
		$x++;
	
	
	$contador++;
	/* Finaliza */	
	}
$conta++;
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