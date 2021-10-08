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


$titulo="Liquidacion de comisiones";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

$archivo="Liquidacioncomisiones";
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
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

$comision=1;
$sql_comision='';
$importetotal=0;

$codusuarios=@$_GET["codusuarios"];
$codprovincia=@$_GET["codprovincia"];
$localidad=@$_GET["localidad"];
$fechainicio=@explota($_GET["fechainicio"]);
$fechafin=@explota($_GET["fechafin"]);
$codcliente=@$_GET["codcliente"];

$detalles=@$_GET["detalles"];


$file=@$_GET["file"];

$where="1=1";
$whereusuario="1=1";
if ($codusuarios <> "" and $codusuarios != 0) { $whereusuario.=$where.=" AND codusuarios='$codusuarios'";  }

if ($codprovincia <> "") { $where.=" AND codprovincia = '$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($codcliente > "") { $where.=" AND codcliente='$codcliente'"; }


	if (($fechainicio<>"") and ($fechafin<>"")) {
		$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
	} else {
		if ($fechainicio<>"") {
			$where.=" AND fecha>='".$fechainicio."'";
		} else {
			if ($fechafin<>"") {
				$where.=" AND fecha<='".$fechafin."'";
			}
		}
	}


/*
$fechainicio=explota(@$_GET['fechainicio']);
$anio=date("Y",strtotime($fechainicio));


$fechainicial=new DateTime($anio."-01-01");
$fechafinal=new DateTime($anio."-12-31");

$diferencia = $fechainicial->diff($fechafinal);
$totlameses = ( $diferencia->y * 12 ) + $diferencia->m;

*/
$detalle="Liquidación de comisiones desde ".$_GET["fechainicio"] . " hasta ".$_GET["fechafin"];


$baja=@$_GET['baja'];


if ($baja =='') { $borrado=" articulos.borrado=0"; } else {$borrado=" articulos.borrado=1"; } 


$objPHPExcel->getActiveSheet();

$objPHPExcel->getActiveSheet()->setTitle('Lista de artículos ');


//cellColor('G3:J3', 'B2B2B2');
if ($comision==1) {
$celda="I";
} else {
$celda="H";
}

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


$objPHPExcel->getActiveSheet()->getDefaultStyle("B".$x.":E".$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("B".$x.":E".$x)->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->setCellValue("B".$x, $detalle);
$objPHPExcel->getActiveSheet()->mergeCells("B".$x.":E".$x);
$objPHPExcel->getActiveSheet()->getStyle("B".$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(18);



$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(15);
cellColor('B'.$x, 'B2B2B2');

$x=10;


$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(16);
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(12);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, "Vendedor.");
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Cliente");
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Nº Factura.");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, "Fecha.");
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Descripción");
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Cantidad");
$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, "Precio s/IVA");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Com.");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

if ($comision==1) {
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "Liquidación");
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);


$sql_usuarios="SELECT * FROM `usuarios` where ". $whereusuario ." AND `borrado`=0 ORDER BY apellido";
$res_usuarios=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuarios);
$contador=0;

$x=11;

while($contador < mysqli_num_rows($res_usuarios)) {

$xaux=$x;	
	
$codusuarios=mysqli_result($res_usuarios, $contador, "codusuarios");

	$sql_comision="select clientes.codcliente, clientes.nombre, clientes.apellido, clientes.empresa, clientes.codusuarios, clientes.codprovincia, clientes.localidad, facturas.codfactura, 
	facturas.fecha, factulinea.codfactura, factulinea.moneda, factulinea.cantidad, factulinea.precio, articulos.comision, articulos.descripcion from clientes INNER JOIN facturas ON facturas.codcliente=clientes.codcliente 
	INNER JOIN factulinea ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos on articulos.codarticulo=factulinea.codigo WHERE clientes.codusuarios='".$codusuarios."' AND
	".$where;
	$res_comision=mysqli_query($GLOBALS["___mysqli_ston"], $sql_comision);
	
	if(mysqli_num_rows($res_comision)>0) {

	cellColor('A'.$x.':I'.$x, 'CCCCCC');
	
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, mysqli_result($res_usuarios, $contador, "nombre")." ".mysqli_result($res_usuarios, $contador, "apellido"));
	$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	
	$objPHPExcel->getActiveSheet()->getRowDimension('A'.$x)->setRowHeight(16);
	$objPHPExcel->getActiveSheet()->getDefaultStyle('A'.$x.':I'.$x)->getFont()->setSize(12);


		$x++;
	}

	
	$contadorCom=0;
	$codclienteaux='';
	$codfacturaaux='';
	
	while($contadorCom < mysqli_num_rows($res_comision)) {
		if(mysqli_result($res_comision, $contadorCom, "comision")>0) {
			
			if($codclienteaux!=mysqli_result($res_comision, $contadorCom, "codcliente")) {
		
			cellColor('B'.$x.':I'.$x, 'CCCCCC');
			$objPHPExcel->getActiveSheet()->getDefaultStyle("B".$x.":D".$x)->getFont()->setName('Arial');
			$objPHPExcel->getActiveSheet()->getDefaultStyle("B".$x.":D".$x)->getFont()->setSize(14);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$x, mysqli_result($res_comision, $contadorCom, "nombre")." ".mysqli_result($res_comision, $contadorCom, "apellido"). " - ".mysqli_result($res_comision, $contadorCom, "empresa"));
			$objPHPExcel->getActiveSheet()->mergeCells("B".$x.":D".$x);
			$objPHPExcel->getActiveSheet()->getStyle("B".$x)->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
			);
			$objPHPExcel->getActiveSheet()->getRowDimension($x)->setRowHeight(16);		

			$x++;
			}
		
		$objPHPExcel->getActiveSheet()->getRowDimension('B'.$x)->setRowHeight(13);
		$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':I'.$x)->getFont()->setSize(11);
		
		//if($detalles==1 and mysql_result($res_comision,$contadorCom,"codfactura")!=$codfacturaaux) {
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, mysqli_result($res_comision, $contadorCom, "codfactura"));
		$objPHPExcel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, implota(mysqli_result($res_comision, $contadorCom, "fecha")));
		$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, " ".mysqli_result($res_comision, $contadorCom, "descripcion"));
		$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, mysqli_result($res_comision, $contadorCom, "cantidad"));
		$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('G'.$x, number_format(mysqli_result($res_comision, $contadorCom, "precio"),2,",","."));
		$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, mysqli_result($res_comision, $contadorCom, "comision")."%");
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		
		//}
		
		$totalcomision=mysqli_result($res_comision, $contadorCom, "cantidad")*mysqli_result($res_comision, $contadorCom, "precio")*(mysqli_result($res_comision, $contadorCom, "comision")/100);
		$importetotal+=$totalcomision;		
		
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, number_format($totalcomision,2,",","."));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':I'.$x)->applyFromArray($border_fino);		
		
		$codclienteaux=mysqli_result($res_comision, $contadorCom, "codcliente");
		$codfacturaaux=mysqli_result($res_comision, $contadorCom, "codfactura");
		$x++;
		}
		$contadorCom++;
	}
$y=$x-1;

	if(mysqli_num_rows($res_comision)>0) {
		cellColor('H'.$x.':I'.$x, 'CCCCCC');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$xaux.':I'.$y)->applyFromArray($border_grueso);

		$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "total");
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

	
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, number_format($importetotal,2,",","."));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->applyFromArray($border_grueso);
	
	}
	
	$importetotal=0;
	$x++;	
	$contador++;
}

/*
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, $sql_comision);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':I'.$x)->applyFromArray($border_grueso);
*/

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
