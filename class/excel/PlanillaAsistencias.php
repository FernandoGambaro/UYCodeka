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
$ACTIVIDADDESCRIPCION ='';
$SALA='';

$archivo=str_replace(" ", "-", $titulo.$anio);
$file=$_GET['file'];

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 




	$c_usuario=" SELECT * FROM `ANIO` WHERE `ANIOID`= '$anio'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
	$ANIODESCRIPCION='';
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	$ANIODESCRIPCION=$r_usuario['ANIODESCRIPCION'];
	}
	$c_usuario=" SELECT * FROM `ACTIVIDAD` WHERE `ACTIVIDADID`= '$actividad'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
	$ACTIVIDADSECTOR='';
	while($r_usuario= mysqli_fetch_array($b_usuario)){
	if ($r_usuario['ACTIVIDADSECTOR']!="") {
	$ACTIVIDADSECTOR=" - ".$r_usuario['ACTIVIDADSECTOR'];
	}
	$ACTIVIDADDESCRIPCION=$r_usuario['ACTIVIDADDESCRIPCION'].$ACTIVIDADSECTOR;
	}
	$c_usuario=" SELECT * FROM `ORGASALAS` WHERE `ACTIVIDADID`= '$actividad' AND `ANIOID`= '$anio'";
	$b_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario);
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


$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$logo = '../css3/images/tmp/logo.png'; // Provide path to your logo file
$objDrawing->setPath($logo);  //setOffsetY has no effect
$objDrawing->setCoordinates('A1');
$objDrawing->setHeight(50); // logo height
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 


$objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setTitle('Planilla asistencia mensual');


//$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->applyFromArray($border_grueso);

$objPHPExcel->getActiveSheet()->setCellValue('C1', " Planilla de asistencia mensual ");
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:I1")->applyFromArray($tipotitulo);
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setName('Arial');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setColor('ffffff');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setSize(16);

$objPHPExcel->getActiveSheet()->mergeCells('C1:I1');
$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);

cellColor('A1:D1', '194685');


/*               */
$objPHPExcel->getActiveSheet()->setCellValue('B3', "Organización: ".$ANIODESCRIPCION);
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:I1")->applyFromArray($tiponormal);
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3:I3")->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->mergeCells('B3:I3');
$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4:I4")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B4:I4")->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B4', "Actividad: ".$ACTIVIDADDESCRIPCION );
$objPHPExcel->getActiveSheet()->mergeCells('B4:I4');
$objPHPExcel->getActiveSheet()->getStyle('B4')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);


/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6:C6")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B6:C6")->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B6', "Integrante " );
$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("D6:I6")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("D6:I6")->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('D6', "Mes:       " );
$objPHPExcel->getActiveSheet()->mergeCells('D6:I6');
$objPHPExcel->getActiveSheet()->getStyle('D6')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$x=6;
cellColor('B'.$x.':I'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);



$x=7;
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(-1);
//$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':D'.$x)->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "");
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);


$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " Nº ");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(6);
$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " Nombre y Apellido");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(32);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " Día:");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " Día:");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " Día:");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, " Día:");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, " Día:");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);

$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, " Total:");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6);


cellColor('B'.$x.':I'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);

$x++;

$num=1;

$ssql="SELECT `ORGANIZACION`.`CALIDADID`,ANIOID,ACTIVIDADID,CONTACTOSNOMBRE,CONTACTOSAPELLIDO,
CONTACTOS.CONTACTOSID as contacto FROM `ORGANIZACION` INNER JOIN 
CONTACTOS ON CONTACTOS.CONTACTOSID = ORGANIZACION.CONTACTOSID WHERE `ANIOID` = '$anio' 
and `ACTIVIDADID` = '$actividad' ORDER BY CALIDADID ASC , ORGANIZACIONORDEN ASC , contacto ASC";
/*
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "sql: ".$ssql );
$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':I'.$x);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);
*/
	$rs2 = mysqli_query($GLOBALS["___mysqli_ston"], $ssql);
	while($DATA = mysqli_fetch_array($rs2)){

		$CONTACTOSNUMERO=$DATA['contacto'];
		$CONTACTOSNOMBRE=$DATA['CONTACTOSNOMBRE']." ".$DATA['CONTACTOSAPELLIDO'];

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $num);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $CONTACTOSNUMERO." ");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".$CONTACTOSNOMBRE);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "");
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, "");
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "");
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "");
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);


	$x++;
	$num++;

}
$resto=$x+16;
for ($xx = $x; $xx <= $resto; $xx++) { 

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$xx, $num);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$xx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$xx, "");
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$xx, "");
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$xx.':I'.$xx)->applyFromArray($border_fino);

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