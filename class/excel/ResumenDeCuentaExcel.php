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

$titulo="Resumen de cuenta";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";
$logo='';
$archivo="Resumen";
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
	$mon_txt = array();
	
							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $mon_txt[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }		

		$query = "SELECT * FROM `foto` where `oid`='2'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
		  if($result=mysqli_fetch_array($resulta)){		
		    $imageLogo= mysqli_result($resulta,0,'fotocontent');			
			}

/*Grabo la información blob a un archivo*/
if (file_exists("../tmp/tmpimage.png")){
	unlink("../tmp/tmpimage.png");
}
file_put_contents("../tmp/tmpimage.png", $imageLogo);


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

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

$codcliente=isset($_GET['codcliente']) ? $_GET['codcliente'] : null ;
$codrecibo=isset($_GET['codrecibo']) ? $_GET['codrecibo'] : null ;
$fechainicio=isset($_GET['fechainicio']) ? $_GET['fechainicio'] : null ;
$fechafin=isset($_GET['fechafin']) ? $_GET['fechafin'] : null ;
$moneda=isset($_GET['moneda']) ? $_GET['moneda'] : null ;

$saldoanterior=isset($_GET['anterior']) ? $_GET['anterior'] : null ;
//$localidad=@$_GET["localidad"];

$file= $archivo=$_GET['file'] ;

$where="1=1";
$WHEREcli="";

if ($codcliente <> "") { $where.=$WHEREcli=" AND facturas.codcliente='$codcliente'"; }
if ($codrecibo <> "") { $where.=" AND codrecibo = '".$codrecibo."'"; }
if ($fechainicio <> "") { $where.=" AND fechainicio >= '".explota($fechainicio)."'"; }
if ($fechafin <> "") { $where.=" AND fechafin <= '".explota($fechafin)."'"; }
if ($moneda <> "") { $where.=" AND moneda = '".$moneda."'"; }

$where.=" ORDER BY fecha ASC";
$detalle="Resumen de cuenta";

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
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

$x=3;					

$tot_mon1='';
$tot_mon2='';
	if($fechainicio=='' and $fechafin=='') {
		$fechain=$fechafi=date("Y-m-d");
	} elseif($fechafin=='') {
		$fechain = explota($fechainicio);
		$fechafi = explota($fechainicio);
	} elseif($fechainicio=='') {
		$fechain = explota($fechafin);
		$fechafi = explota($fechafin);
	} else {
		$fechain = explota($fechainicio);
		$fechafi = explota($fechafin);
	}
	$startTime =data_first_month_day($fechain); 
	$endTime = data_last_month_day($fechafi); 

$tiporesumen='';
if($saldoanterior=='S') {
	
$tiporesumen="Resumen de cuenta con saldo anterior, entre ".implota($fechain)." y ".implota($fechafi) ;	
} else {
$tiporesumen="Resumen de cuenta sin saldo anterior, entre ".implota($fechain)." y ".implota($fechafi) ;
}
$objPHPExcel->getActiveSheet()->getDefaultStyle("A3:I3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("A3:I3")->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle("A3:I3")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('A3', $tiporesumen);
$objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);;

$x++;
//comienza los cálculos

$sql_clientes="SELECT facturas.codcliente, clientes.nombre, clientes.empresa FROM facturas INNER JOIN clientes on clientes.codcliente = facturas.codcliente 
WHERE facturas.fecha >= '".$startTime."' AND facturas.fecha <= '".$endTime."' ".$WHEREcli." GROUP BY facturas.codcliente ORDER BY facturas.codcliente ASC";

$res_clientes=mysqli_query($GLOBALS["___mysqli_ston"], $sql_clientes);
$conta_cliente=0;
while ($conta_cliente < mysqli_num_rows($res_clientes)) {
$codcliente=mysqli_result($res_clientes, $conta_cliente, "codcliente");
$startTime =data_first_month_day($fechain); 
$endTime = data_last_month_day($fechafi); 

$detalle="Resumen de cuenta ".mysqli_result($res_clientes, $conta_cliente, "nombre")." ".mysqli_result($res_clientes, $conta_cliente, "apellido")." - ".mysqli_result($res_clientes, $conta_cliente, "empresa");
$x++;

$styleArray = array('font'  => array('bold'  => true, 'color' => array('rgb' => 'FFFFFF'), 'size'  => 11, 'name'  => 'Verdana'));
$objPHPExcel->getActiveSheet()->getRowDimension('A'.$x)->setRowHeight(18);
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, $detalle );
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$x.':I'.$x);
cellColor('A'.$x, '558ed5');

$x++;

cellColor('A'.$x.':I'.$x, 'CCCCCC');

$styleArray = array('font'  => array('bold'  => true, 'color' => array('rgb' => '000000'), 'size'  => 9, 'name'  => 'Verdana'));
$objPHPExcel->getActiveSheet()->getRowDimension('A'.$x)->setRowHeight(10);
$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Fecha");
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Nº Factura");
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Moneda");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Importe");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Recibo");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Moneda");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, "Importe");
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Saldo ".$mon_txt[1]);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "Saldo ".$mon_txt[2]);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);

$x++;

$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setSize(9);
$objPHPExcel->getActiveSheet()->getRowDimension('A'.$x)->setRowHeight(10);

$subtot_mon1=0;
$subtot_mon2=0;
$subtot_mon1f=0;
$subtot_mon2f=0;
$subtot_mon1c=0;
$subtot_mon2c=0;
$TotalDolar=0;
$TotalPesos=0;

if($saldoanterior=='S') {
/*Calculo saldo por cobrar en Pesos*/
$queryFacturado="SELECT SUM( totalfactura ) AS Total FROM facturas INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
WHERE facturas.borrado=0 AND facturas.tipo=1 AND facturas.moneda=1 AND facturas.fecha <'".$startTime."' AND facturas.codcliente= '".$codcliente ."'
ORDER BY facturas.fecha DESC";
$rs_Facturado=mysqli_query($GLOBALS["___mysqli_ston"], $queryFacturado);
$TotalPesos=mysqli_result($rs_Facturado, 0, "Total");
    
$queryCobrado="SELECT SUM( importe) AS Cobrado FROM recibos INNER JOIN clientes ON recibos.codcliente=clientes.codcliente
WHERE recibos.moneda=1 AND recibos.fechacobro <'".$startTime."' AND recibos.codcliente= '".$codcliente ."'";
$rs_Cobrado=mysqli_query($GLOBALS["___mysqli_ston"], $queryCobrado);
$subtot_mon1=$TotalPesos-mysqli_result($rs_Cobrado, 0, "Cobrado");

/*Calculo saldo por cobrar en Dolares*/
$queryFacturado="SELECT SUM(totalfactura ) AS Total FROM facturas INNER JOIN clientes ON facturas.codcliente=clientes.codcliente 
WHERE facturas.borrado=0 AND facturas.tipo=1 AND facturas.moneda=2 AND facturas.fecha <'".$startTime."' AND facturas.codcliente= '".$codcliente ."'";
$rs_Facturado=mysqli_query($GLOBALS["___mysqli_ston"], $queryFacturado);
$TotalDolar=mysqli_result($rs_Facturado, 0, "Total");
    
$queryCobrado="SELECT SUM( importe ) AS Cobrado FROM recibos  INNER JOIN clientes ON recibos.codcliente=clientes.codcliente 
WHERE recibos.moneda=2 AND recibos.fechacobro <'".$startTime."' AND recibos.codcliente= '".$codcliente ."'";
$rs_Cobrado=mysqli_query($GLOBALS["___mysqli_ston"], $queryCobrado);
$subtot_mon2=$TotalDolar-mysqli_result($rs_Cobrado, 0, "Cobrado");

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,   number_format($subtot_mon1,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x,  number_format($subtot_mon2,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
);
$x++;
}
		while (strtotime($startTime) <= strtotime($endTime)) {
//Para la fecha seleccionada recorro todas las facturas

		$sel_resultado="SELECT codfactura, fecha,totalfactura,estado,tipo, moneda, codcliente	
		FROM facturas WHERE facturas.borrado=0 AND facturas.tipo=1 AND fecha ='".$startTime."' AND codcliente= '".$codcliente ."'" ;
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		$contador=0;
		while ($contador < mysqli_num_rows($res_resultado)) {
			
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, implota($startTime));
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, " ".mysqli_result($res_resultado, $contador, "codfactura"));
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, " ".$mon_txt[mysqli_result($res_resultado, $contador, "moneda")]);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
);
//number_format($importe,2,",",".");
			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
				if(mysqli_result($res_resultado, $contador, "moneda")==1) {
					$subtot_mon1f-=mysqli_result($res_resultado, $contador, "totalfactura");
				} else {
					$subtot_mon2f-=mysqli_result($res_resultado, $contador, "totalfactura");
				}
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " ".number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",","."));
			} else {
				if(mysqli_result($res_resultado, $contador, "moneda")==1) {
					$subtot_mon1f+=mysqli_result($res_resultado, $contador, "totalfactura");
				} else {
					$subtot_mon2f+=mysqli_result($res_resultado, $contador, "totalfactura");
				}
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, " -".number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",","."));
			}					
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);			
	   	$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);
			$x++;
	   	$contador++;
		}	
		$sel_resultado="SELECT codrecibo, fecha, moneda, importe
		FROM recibos WHERE borrado=0 AND fecha ='".$startTime."' AND codcliente= '".$codcliente ."'" ;
		$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		$contador=0;
		while ($contador < mysqli_num_rows($res_resultado)) {
//Parte recibos	
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x,implota($startTime));
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " ".mysqli_result($res_resultado, $contador, "codrecibo"));
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, " ".$mon_txt[mysqli_result($res_resultado, $contador, "moneda")]);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);			
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  " ".number_format(mysqli_result($res_resultado, $contador, "importe"),2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

				if(mysqli_result($res_resultado, $contador, "moneda")==1) {
					$subtot_mon1c+=mysqli_result($res_resultado, $contador, "importe");
				} else {
					$subtot_mon2c+=mysqli_result($res_resultado, $contador, "importe");
				}
	   	$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);
			$x++;
	   	$contador++;
		}
		$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));		
	}
	$subtot_mon1=$subtot_mon1f+$subtot_mon1c;
	$subtot_mon2=$subtot_mon2f+$subtot_mon2c;
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,  number_format($subtot_mon1,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x,  number_format($subtot_mon2,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
);

$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->applyFromArray($border_fino);

	$x++;	
	$tot_mon1+=$subtot_mon1;
	$tot_mon2+=$subtot_mon2;
	$conta_cliente++;
}
$x++;

$styleArray = array('font'  => array( 'bold'  => true,'color' => array('rgb' => '000000'), 'size'  => 9,'name'  => 'Verdana'));
$objPHPExcel->getActiveSheet()->getRowDimension('A'.$x)->setRowHeight(10);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, 'Resultado' );
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
);
$objPHPExcel->getActiveSheet()->mergeCells('H'.$x.':I'.$x);
cellColor('H'.$x, '558ed5');
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->applyFromArray($border_fino);

$x++;

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,  number_format($tot_mon1,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x,  number_format($tot_mon2,2,",","."));
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->applyFromArray($border_fino);

$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
);

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

$name = '../tmp/'.$file.'.xlsx';
$objWriter->save($name);
//$objWriter->save('php://output');
?>