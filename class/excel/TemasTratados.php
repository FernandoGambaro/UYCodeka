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
$actividad=$_GET['actividad'];
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


/*
$actividad=$_GET['ACTIVIDADID'];
$anio=$_GET['ANIOID'];
*/
$ordenado="REUNIONESFECHA";
$orden="ASC";


	$c_usuario=" SELECT * FROM `ANIO` WHERE `ANIOID`= '$anio'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	$ANIODESCRIPCION=$r_usuario['ANIODESCRIPCION'];
	}
	$c_usuario=" SELECT * FROM `ACTIVIDAD` WHERE `ACTIVIDADID`= '$actividad'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	if ($r_usuario['ACTIVIDADSECTOR']!="") {
	$ACTIVIDADSECTOR=" - ".$r_usuario['ACTIVIDADSECTOR'];
	}
	$ACTIVIDADDESCRIPCION=$r_usuario['ACTIVIDADDESCRIPCION'].$ACTIVIDADSECTOR;
	}
	$c_usuario=" SELECT * FROM `ORGASALAS` WHERE `ACTIVIDADID`= '$actividad' AND `ANIOID`= '$anio'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"],$c_usuario);
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	$SALA=$r_usuario['SALASID'];
	$ORGANIZACIONDIA=$r_usuario['ORGANIZACIONDIA'];
	$ORGANIZACIONHORA=$r_usuario['ORGANIZACIONHORA'];
	}
	if($SALA<>'') {
	$c_sala=" SELECT * FROM `SALAS` WHERE `SALASID`= '$SALA'";
	$b_sala = mysqli_query($GLOBALS["___mysqli_ston"], $c_sala);
	while($r_sala= mysqli_fetch_array($b_sala)){
	$SALASDESCRIPCION=$r_sala['SALASDESCRIPCION'];
	}
	}
	if($ORGANIZACIONDIA<>'') {
	$c_dia=" SELECT * FROM `DIAS` WHERE `DIASID`= '$ORGANIZACIONDIA'";
	$b_dia = mysqli_query($GLOBALS["___mysqli_ston"], $c_dia);
	while($r_dia= mysqli_fetch_array($b_dia)){
	$DIA=$r_dia['DIASDESC'];
	}
	}
$HoraTipo = array(
        0=>"10:00hrs",
	1=>"17:00hrs",
	2=>"18:00hrs",
        3=>"19:00hrs",
        4=>"19:30hrs",
        5=>"20:00hrs",
        6=>"21:00hrs");
$x=0;
foreach($HoraTipo as $i) {
  	if ( $x==$ORGANIZACIONHORA)
	{
		$HORA=$i;
	}
	$x++;
}
if ($DIA=="")
$HORA="";




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


$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$logo = '../css3/images/tmp/logo.png'; // Provide path to your logo file
$objDrawing->setPath($logo);  //setOffsetY has no effect
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(50); // logo height
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 


$objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setTitle('Lista de temas tratados');


//$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->applyFromArray($border_grueso);

$objPHPExcel->getActiveSheet()->setCellValue('C1', " Listado de temas tratados ");
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->applyFromArray($tipotitulo);
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setName('Arial');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setColor('ffffff');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setSize(16);

$objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

cellColor('A1:D1', '194685');


/*               */
$objPHPExcel->getActiveSheet()->setCellValue('B3', "Organización: ".$ANIODESCRIPCION);
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->applyFromArray($tiponormal);
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3:D3")->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->mergeCells('B3:D3');
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4:D4")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4:D4")->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B4', "Actividad: ".$ACTIVIDADDESCRIPCION );
$objPHPExcel->getActiveSheet()->mergeCells('B4:D4');
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);


$x=6;
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(-1);
//$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':D'.$x)->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Nº");
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Dirigido por");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Fecha");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Tema tratado");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);


cellColor('A'.$x.':D'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);


$x++;

$num=1;
	$criterio="WHERE `ANIOID` = '$anio' and `ACTIVIDADID` = '$actividad'";
	$ssql=" SELECT * FROM `REUNIONES` " .$criterio. " ORDER BY `$ordenado` $orden, CONTACTOSID $orden";
	$rs = mysqli_query($GLOBALS["___mysqli_ston"], $ssql);
	while($DATA = @mysqli_fetch_array($rs)){
	$c_usuario=" SELECT * FROM `CONTACTOS` WHERE `CONTACTOSID`= '$DATA[CONTACTOSID]'";
	$b_usuario =mysqli_query($GLOBALS["___mysqli_ston"],$c_usuario);
		while($r_usuario= mysqli_fetch_array($b_usuario)){
		$CONTACTOSNUMERO=$r_usuario['CONTACTOSNUMERO'];
		$CONTACTOSNOMBRE=$r_usuario['CONTACTOSNUMERO']. " - ".$r_usuario['CONTACTOSNOMBRE']."  ".$r_usuario['CONTACTOSAPELLIDO'];
		}
		if (""!=($DATA['DIRIGIOEXTERNO']) and ""!=($CONTACTOSNUMERO)){
		$CONTACTOSNOMBRE.=", ".$DATA['DIRIGIOEXTERNO'];			
		} elseif(""==($CONTACTOSNUMERO)) {
		$CONTACTOSNOMBRE=$DATA['DIRIGIOEXTERNO'];			
		}
	$REUNIONESFECHA=implota($DATA['REUNIONESFECHA']);
	$REUNIONESTEMA=$DATA['REUNIONESTEMA'];

// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $num);
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $CONTACTOSNOMBRE);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $REUNIONESFECHA);
//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $REUNIONESTEMA);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
$objPHPExcel->getActiveSheet()->getStyle('D'.$x.':D'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

//$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($border_fino);


	$x++;
	$num++;
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