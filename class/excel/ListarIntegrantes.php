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


include("../conexion.php");
include("../funcionesvarias.php");


$ShowName='';
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";
$titulo='';
$detalle="Listado: ";

		$CONTACTOSNUMERO='';
		$CONTACTOSNOMBRE='';
		$CONTACTOSESTADO='';
		$CONTACTOSCUMPLE='';
		$CONTACTOSCUMPLE2='';
		$CONTACTOSTELEFONO='';
		$CONTACTOSCELULAR='';
		$CONTACTOSMAILPRI='';
		$BAJACAUSADESC='';

$CONTACTOSESTADO=isset($_GET["CONTACTOSESTADO"]) ? $_GET["CONTACTOSESTADO"] : null ;
$CONTACTOSSERVICE=isset($_GET["CONTACTOSSERVICE"]) ? $_GET["CONTACTOSSERVICE"] : null ;
$CONTACTOSDEPTO=isset($_GET["DEPARTAMENTOS"]) ? $_GET["DEPARTAMENTOS"] : null ;

$Tipox = array(
        0=>"Neodiscípulo/a",
        1=>"Discípulo/a",
        2=>"Amigo/a de la Obra",
        3=>"Todos");
   
        
        
$where=" 1=1 ";

if ($CONTACTOSSERVICE<>'' and $CONTACTOSSERVICE < 3) { $where.=" AND `CONTACTOSSERVICE`='$CONTACTOSSERVICE'";
$detalle.=" ".$Tipox[$CONTACTOSSERVICE];
}
if($CONTACTOSSERVICE == 3) {
$detalle.=" ".$Tipox[$CONTACTOSSERVICE];
}

if ($CONTACTOSESTADO<>'') { $where.=" AND `CONTACTOSESTADO` = '$CONTACTOSESTADO'";

	$c_usu=" SELECT * FROM `BAJACAUSA` WHERE `BAJACAUSAID` = '$CONTACTOSESTADO'";
	$b_usu = mysqli_query($GLOBALS["___mysqli_ston"], $c_usu);
	if($r_usu= mysqli_fetch_array($b_usu)) {
 	$BAJACAUSADESC=$r_usu['BAJACAUSADESC'];
	}	
$detalle.=", ".$BAJACAUSADESC;	
}

if($CONTACTOSDEPTO<>'') { $where.=" AND `CONTACTOSDEPTO` = '$CONTACTOSDEPTO'";

$depto="SELECT * FROM  `DEPARTAMENTOS` WHERE `DEPARTAMENTOSID`='$CONTACTOSDEPTO'";
	$b_depto = mysqli_query($GLOBALS["___mysqli_ston"], $depto);
	if($r_depto= mysqli_fetch_array($b_depto)) {
	$DEPARTAMENTO=$r_depto['DEPARTAMENTOSDESC'];
	}
$detalle.=" en ".$DEPARTAMENTO;

}

$archivo=$file=$_GET['file'];

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("America/Montevideo"); 


function  cambiaf_a_normal( $fecha ){
@ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha );
 $fechafinal = $mifecha [ 3 ]. "/" . $mifecha [ 2 ]. "/" . $mifecha [ 1 ];
return $fechafinal;
 }


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
$objPHPExcel->getActiveSheet()->setTitle('Datos de integrantes');


//$objPHPExcel->getActiveSheet()->getStyle("A1:D1")->applyFromArray($border_grueso);

$objPHPExcel->getActiveSheet()->setCellValue('C1', " Datos de integrantes ");
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:H1")->applyFromArray($tipotitulo);
$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setName('Arial');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setColor('ffffff');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("C1:D1")->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->mergeCells('C1:H1');
$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

cellColor('A1:D1', '194685');


/*               */
$objPHPExcel->getActiveSheet()->setCellValue('B3', trim($detalle));
$objPHPExcel->getActiveSheet()->getDefaultStyle("B3:H3")->applyFromArray($tiponormal);
$objPHPExcel->getActiveSheet()->getDefaultStyle("B1:H3")->getFont()->setName('Arial');
//$objPHPExcel->getActiveSheet()->getDefaultStyle("B3:H3")->getFont()->setSize(12);

//$objPHPExcel->getActiveSheet()->mergeCells('B3:H3');
//$objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->applyFromArray(
//    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
//);


$x=6;
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(-1);
//$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':D'.$x)->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "");
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);


$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " Nombre y Apellido ");
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$objPHPExcel->getActiveSheet()->getStyle('B1:B'.$objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " Centro Estudio ");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " Cumpleaños ");
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " Ingreso ");
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " Teléfono ");
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, " Celular ");
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, " Email ");
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(28);


if($CONTACTOSSERVICE==3) {
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, " Estado ");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
cellColor('B'.$x.':H'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':I'.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);

} else {
cellColor('B'.$x.':H'.$x, 'CCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':H'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':H'.$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);

}

$x++;

$num=1;

$ssql="SELECT CONTACTOSSERVICE,CENTROSESTUDIOID, CONTACTOSESTADO, CONTACTOSMAILPRI, CONTACTOSDEPTO, CONTACTOSCELULAR, CONTACTOSTELEFONO, CONTACTOSCUMPLE2, CONTACTOSCUMPLE, CONTACTOSNOMBRE, CONTACTOSAPELLIDO,
 CONTACTOSID FROM `CONTACTOS` WHERE ".$where."  order by CONTACTOSID ASC";

/*
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "sql: ".$ssql );
$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':I'.$x);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
);

$x++;
*/
	$rs2 = mysqli_query($GLOBALS["___mysqli_ston"], $ssql);
	while($DATA = mysqli_fetch_array($rs2)){
	$CENTROSESTUDIO='';
		$CONTACTOSID=$DATA['CONTACTOSID'];
		$CONTACTOSNOMBRE=$DATA['CONTACTOSNOMBRE']." ".$DATA['CONTACTOSAPELLIDO'];
		if($DATA['CENTROSESTUDIOID']>0) {
			$depto="SELECT CENTROSESTUDIODESCRIPCION FROM `CENTROSESTUDIO` WHERE `CENTROSESTUDIOID`='".$DATA['CENTROSESTUDIOID']."'";
			$b_depto = mysqli_query($GLOBALS["___mysqli_ston"], $depto);
			if($r_depto= mysqli_fetch_array($b_depto)) {
				$CENTROSESTUDIO=trim($r_depto['CENTROSESTUDIODESCRIPCION']);
			}
		}
		if ($DATA['CONTACTOSCUMPLE']!="0000-00-00" AND isset($DATA['CONTACTOSCUMPLE']) ) {
		$CONTACTOSCUMPLE=fechaATexto($DATA['CONTACTOSCUMPLE'], "M"); } else {
		$CONTACTOSCUMPLE=" - ";
		}
		if (cambiaf_a_normal($DATA['CONTACTOSCUMPLE2'])!="00/00/0000" AND isset($DATA['CONTACTOSCUMPLE2']) ) {
		$CONTACTOSCUMPLE2=fechaATexto($DATA['CONTACTOSCUMPLE2'], "l");  } else {
		$CONTACTOSCUMPLE2=" - ";
		}		

		$CONTACTOSTELEFONO=$DATA['CONTACTOSTELEFONO'];
		$CONTACTOSCELULAR=$DATA['CONTACTOSCELULAR'];
		$CONTACTOSMAILPRI=$DATA['CONTACTOSMAILPRI'];

if($CONTACTOSSERVICE==3) {
	$CONTACTOSESTADO=$DATA['CONTACTOSESTADO'];
	$c_usu=" SELECT * FROM `BAJACAUSA` WHERE `BAJACAUSAID` = '$CONTACTOSESTADO'";
	$b_usu = mysqli_query($GLOBALS["___mysqli_ston"], $c_usu);
	if($r_usu= mysqli_fetch_array($b_usu)) {
 	$BAJACAUSADESC=trim($r_usu['BAJACAUSADESC']);
	}
}		
		

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $CONTACTOSID);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".$CONTACTOSNOMBRE);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".$CENTROSESTUDIO);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " ".$CONTACTOSCUMPLE);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " ".$CONTACTOSCUMPLE2);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " ".$CONTACTOSTELEFONO);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, " ".$CONTACTOSCELULAR);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, " ".$CONTACTOSMAILPRI);

if($CONTACTOSSERVICE==3) {
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, " ".$BAJACAUSADESC);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);
} else {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':H'.$x)->applyFromArray($border_fino);
}
	$x++;
	$num++;

}



// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.$archivo.'.xlsx"');
//header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
//header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
//header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
//header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$objWriter->save('../tmp/'.$file.'.xlsx');
//$objWriter->save('php://output');


?>