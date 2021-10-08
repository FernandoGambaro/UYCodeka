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




$titulo="Lista de Precios";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

$archivo="Lista102015";
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


	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>"\$", 2=>"U\$S");
	


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



$title="Listado de Articulos";
$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocila=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");


$codigobarras=$_GET["codigobarras"];
$descripcion=$_GET["descripcion"];
$codfamilia=@$_GET["cboFamilias"];
$referencia=$_GET["referencia"];
$codproveedor=@$_GET["cboProveedores"];
$codubicacion=@$_GET["cboUbicacion"];
$stock=$_GET["stock"];
$file=$_GET["file"];


$where="1=1";
if ($codigobarras <> "") { $where.=" AND codigobarras='$codigobarras'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }
if ($codfamilia > "0") { $where.=" AND codfamilia='$codfamilia'"; }
if ($codproveedor > "0") { $where.=" AND (codproveedor1='$codproveedor' OR codproveedor2='$codproveedor')"; }
if ($codubicacion > "0") { $where.=" AND codubicacion='$codubicacion'"; }
if ($referencia <> "") { $where.=" AND referencia like '%".$referencia."%'"; }
if ($stock <> "") { $where.=" AND stock = '".$stock."'"; }

$detalle="Lista de precios";

if(@$_GET['cat']==1) {
	$where.=" order by codfamilia";
	$detalle="Lista de precios según familias";
}
$cat=@$_GET['cat'];


$objPHPExcel->getActiveSheet();

$objPHPExcel->getActiveSheet()->setTitle('Lista de precios '.@genMonth_Text(date('m')). " ".date('Y'));


//cellColor('G3:J3', 'B2B2B2');

$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:F1")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:F1")->getFont()->setSize(24);
$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('A1', $razonsocila);
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);;

	
	
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B3', $direccion);
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B4', $telefono." - ". $fax);
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
$objPHPExcel->getActiveSheet()->setCellValue('B6', $web);
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);


$x=8;

$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x)->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $detalle );
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
cellColor('B'.$x, 'B2B2B2');

$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(14);

$x=10;
cellColor('A'.$x.':F'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(14);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Cod.");
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Artículo");
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Pres.");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Mon.");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Precio s/IVA");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Stock");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($border_fino);

$x++;

$sel_resultado="SELECT * FROM articulos WHERE articulos.borrado=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$contador=0;

while ($contador < mysqli_num_rows($res_resultado)) {

// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, mysqli_result($res_resultado, $contador, "codigobarras")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".mysqli_result($res_resultado, $contador, "descripcion"));
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

					  	$query_embalaje="SELECT codembalaje,nombre FROM embalajes WHERE borrado=0 AND `codembalaje`= ".mysqli_result($res_resultado, $contador, "codembalaje")." ORDER BY nombre ASC";
						$res_embalaje=mysqli_query($GLOBALS["___mysqli_ston"], $query_embalaje);
								if ( mysqli_num_rows($res_embalaje)>0) { 
									$presentacion=mysqli_result($res_embalaje, 0, "nombre").' '. mysqli_result($res_resultado, $contador, "unidades_caja")."u.";
								} else {
									$presentacion=mysqli_result($res_resultado, $contador, "unidades_caja")."u.";
								}

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $presentacion." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $moneda[mysqli_result($res_resultado, $contador, "moneda")]." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, number_format(mysqli_result($res_resultado, $contador, "precio_tienda"),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,  number_format(mysqli_result($res_resultado, $contador, "precio_iva"),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

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
