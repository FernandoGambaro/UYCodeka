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

$file=$_GET['file'];
$fechainicio=explota($_GET['fechainicio']);
$anio=date("Y",strtotime($fechainicio));


$fechainicial=new DateTime($anio."-01-01");
$fechafinal=new DateTime($anio."-12-31");

$diferencia = $fechainicial->diff($fechafinal);
$totlameses = ( $diferencia->y * 12 ) + $diferencia->m;

$fechaDGI='';
$totalResumenIva_Ventas=0;
$totalResumenIva_Compras=0;
$totalResumenTotal_Ventas=0;
$totalResumenTotal_Compras=0;
$totalIva_Retenciones=0;
$totalSaldo_Retenciones=0;
$totalf546=0;
$totalf108=0;
$totalf328=0;
$totalf606=0;

$titulo="Reporte cierre mes";
$asunto="";
$descripcion="";
$palabraclave="";
$categoria="";

$archivo="Cierre";

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
	$moneda = array();
	
							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }		
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	
$resguardo=0;
$AcumuladoIVA_Ventas=0;
$AcumuladoIVA_Compras=0;
$Acumulado_Ventas=0;
$SaldoIVA_CompraVenta=0;
$Iva_Retenciones=0;
$Saldo_Retenciones=0;

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

/*Recorro todos los meses entre las fechas creando una hoja por mes*/
$nuevafecha=date_format($fechainicial, 'Y-m-d');

$objPHPExcel->getActiveSheet();

for($mesactual=0;$mesactual<=$totlameses; $mesactual++) {
	
$startTime =data_first_month_day($nuevafecha); 
$endTime = data_last_month_day($nuevafecha); 

$sTime=$startTime;
 	
if ($mesactual==0) {
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle(@genMonth_Text(date('m',strtotime($sTime))));
} else {
$objPHPExcel->createSheet($mesactual);
$objPHPExcel->setActiveSheetIndex($mesactual);
$objPHPExcel->getActiveSheet()->setTitle(@genMonth_Text(date('m',strtotime($sTime))));
}

cellColor('H3:K3', 'B2B2B2');

$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:K1")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("A1:K1")->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle("A1:K1")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('A1', "Detalles compra / venta – ". genMonth_Text(date('m',strtotime($sTime)))." de ".date('Y',strtotime($sTime)));
$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("H3:I3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("H3:I3")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("H3:I3")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('H3', "Compras");
$objPHPExcel->getActiveSheet()->mergeCells('H3:I3');
$objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("J3:K3")->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("J3:K3")->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("J3:K3")->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('J3', "Ventas");
$objPHPExcel->getActiveSheet()->mergeCells('J3:K3');
$objPHPExcel->getActiveSheet()->getStyle('J3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
/*               */

$x=4;
cellColor('A4:K4', 'CCCCCC');
$objPHPExcel->getActiveSheet()->setCellValue('A4', "Fecha");
$objPHPExcel->getActiveSheet()->setCellValue('B4', "Cliente/Proveedor");
$objPHPExcel->getActiveSheet()->setCellValue('C4', "Documento");
$objPHPExcel->getActiveSheet()->setCellValue('D4', "Moneda");
$objPHPExcel->getActiveSheet()->setCellValue('E4', "Importe");
$objPHPExcel->getActiveSheet()->getStyle('E4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('F4', "T/C");
$objPHPExcel->getActiveSheet()->getStyle('F4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('G4', "Impuesto");
$objPHPExcel->getActiveSheet()->getStyle('G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('H4', "IVA");
$objPHPExcel->getActiveSheet()->getStyle('H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('I4', "Total");
$objPHPExcel->getActiveSheet()->getStyle('I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('J4', "IVA");
$objPHPExcel->getActiveSheet()->getStyle('J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('K4', "Total");
$objPHPExcel->getActiveSheet()->getStyle('K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':H'.$x)->applyFromArray($border_fino);
$x++;

$Iva_Compras=0;
$Total_Compras=0;
$Iva_Ventas=0;
$Total_Ventas=0;

	while (strtotime($startTime) <= strtotime($endTime)) {

			$fechaTipoCambio=date ("Y-m-d", strtotime("-1 day", strtotime($startTime)));
   		$sel_tipocambio="SELECT valor FROM tipocambio WHERE fecha <='".$fechaTipoCambio."' order by fecha DESC";
   		$res_tipocambio=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tipocambio);
  			$tipocambio=mysqli_result($res_tipocambio, 0, "valor");

			$sel_resultado="SELECT codfactura,clientes.nombre as nombre,facturas.fecha as fecha,totalfactura,estado,facturas.tipo,facturas.iva,
			facturas.moneda,clientes.empresa,clientes.apellido 	FROM facturas INNER JOIN clientes ON  facturas.codcliente=clientes.codcliente
			 WHERE facturas.borrado=0 AND fecha ='".$startTime."'";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			if($res_resultado) {
		   $contador=0;
		   while ($contador < mysqli_num_rows($res_resultado)) { 

	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
								
				$tipoc=$tipo[mysqli_result($res_resultado, $contador, "tipo")];

				if (mysqli_result($res_resultado, $contador, "empresa")!='') {
					$nombre= mysqli_result($res_resultado, $contador, "empresa");
					} elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {
						$nombre= mysqli_result($res_resultado, $contador, "nombre");
					} else {
						$nombre= mysqli_result($res_resultado, $contador, "nombre"). ' ' . mysqli_result($res_resultado, $contador, "apellido");
					}

$codfactura=mysqli_result($res_resultado, $contador, "codfactura");
$mon=$moneda[mysqli_result($res_resultado, $contador, "moneda")];
// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, implota($startTime));
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $nombre);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $codfactura." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $mon);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            
            if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

							} else {
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  number_format(mysqli_result($res_resultado, $contador, "totalfactura")*(-1),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

							}			
            
				if (mysqli_result($res_resultado, $contador, "moneda")==2){
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,  number_format($tipocambio,3,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				 }        

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  mysqli_result($res_iva, 0, "nombre")." ");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

 			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_iva, 0, "valor"));
			} else {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));
			}		
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Iva_Ventas+=$iva;		 
		 $Ventas= number_format($iva,2,",",".");
		 } else {
		 $Iva_Ventas+=$iva*$tipocambio;
		 $Ventas= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;
$objPHPExcel->getActiveSheet()->setCellValue('J'.$x,$Ventas." ");
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

         
			if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
			 $total= mysqli_result($res_resultado, $contador, "totalfactura");
			} else {
			 $total= mysqli_result($res_resultado, $contador, "totalfactura")*(-1);
			}		
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Total_Ventas+=$total;		 
			$TVentas= number_format($total,2,",","."); 
		 } else {
		 $Total_Ventas+=$total*$tipocambio;
			$TVentas= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0;
		          
$objPHPExcel->getActiveSheet()->setCellValue('K'.$x, $TVentas." ");
$objPHPExcel->getActiveSheet()->getStyle('K'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':K'.$x)->applyFromArray($border_fino);
            
            $contador++;
            $x++;         
		}
	}
	
/* Notas de crédito */	
	/*

			$sel_resulta="SELECT codncredito,clientes.nombre as nombre,ncredito.fecha as fecha,total,estado,ncredito.tipo,ncredito.iva,ncredito.moneda,clientes.empresa,clientes.apellido
			FROM ncredito,clientes WHERE ncredito.borrado=0 AND ncredito.codcliente=clientes.codcliente AND fecha ='".$startTime."'";
		
			$res_resulta=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resulta);
			if($res_resulta) {
		   $conta=0;
		   while ($conta < mysqli_num_rows($res_resulta)) { 

	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resulta, $conta, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);

				if (mysqli_result($res_resulta, $conta, "empresa")!='') {
					$nombre= mysqli_result($res_resulta, $conta, "empresa");
					} elseif (mysqli_result($res_resulta, $conta, "apellido")=='') {
						$nombre= mysqli_result($res_resulta, $conta, "nombre");
					} else {
						$nombre= mysqli_result($res_resulta, $conta, "nombre"). ' ' . mysqli_result($res_resulta, $conta, "apellido");
					}

$codncredito=mysqli_result($res_resulta, $conta, "codncredito");
$mon=$moneda[mysqli_result($res_resulta, $conta, "moneda")];
// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, implota($startTime));
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $nombre);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $codncredito." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $mon);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  number_format(mysqli_result($res_resulta, $conta, "total")*(-1),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		
            
				if (mysqli_result($res_resulta, $conta, "moneda")==2){
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,  number_format($tipocambio,3,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				 }        

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  mysqli_result($res_iva, 0, "nombre")." ");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);


		 $iva = mysqli_result($res_resulta, $conta, "total")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));

		 if (mysqli_result($res_resulta, $conta, "moneda")==1){
		 $Iva_Ventas+=$iva;		 
		 $Ventas= number_format($iva,2,",",".");
		 } else {
		 $Iva_Ventas+=$iva*$tipocambio;
		 $Ventas= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;
$objPHPExcel->getActiveSheet()->setCellValue('J'.$x,$Ventas." ");
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

         
			 $total= mysqli_result($res_resulta, $conta, "total")*(-1);

		 if (mysqli_result($res_resulta, $conta, "moneda")==1){
		 $Total_Ventas+=$total;		 
			$TVentas= number_format($total,2,",","."); 
		 } else {
		 $Total_Ventas+=$total*$tipocambio;
			$TVentas= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0;
		          
$objPHPExcel->getActiveSheet()->setCellValue('K'.$x, $TVentas." ");
$objPHPExcel->getActiveSheet()->getStyle('K'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':K'.$x)->applyFromArray($border_fino);
            
            $conta++;
            $x++;         
		}
	}

*/

/*-------------------------------------------------------------------*/
	
/* Sector compras*/
	$sel_resultado="SELECT codfactura,proveedores.nombre as nombre,facturasp.fecha as fecha,proveedores.codproveedor,totalfactura,facturasp.iva,estado,moneda,tipo
	FROM facturasp,proveedores WHERE facturasp.codproveedor=proveedores.codproveedor AND fecha ='".$startTime."'";
	   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
	   if($res_resultado) {
	   $contador=0;						   
	   while ($contador < mysqli_num_rows($res_resultado)) { 

	  		$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".mysqli_result($res_resultado, $contador, "iva");
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
         
$nombre=mysqli_result($res_resultado, $contador, "nombre");
$codfactura=mysqli_result($res_resultado, $contador, "codfactura");
$mon=$moneda[mysqli_result($res_resultado, $contador, "moneda")];
// Add some data
$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, implota($startTime));

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, $nombre);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, $codfactura." ");
$objPHPExcel->getActiveSheet()->setCellValue('D'.$x, $mon);

$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

 				if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				} else {
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  number_format(mysqli_result($res_resultado, $contador, "totalfactura")*(-1),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				}		

				if (mysqli_result($res_resultado, $contador, "moneda")==2){
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,  number_format($tipocambio,3,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				 }        

$objPHPExcel->getActiveSheet()->setCellValue('G'.$x,  mysqli_result($res_iva, 0, "nombre")." ");
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


		 if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")/(100+mysqli_result($res_iva, 0, "valor"));
		 } else {
		 $iva = mysqli_result($res_resultado, $contador, "totalfactura")*mysqli_result($res_iva, 0, "valor")*(-1)/(100+mysqli_result($res_iva, 0, "valor"));
		 }			
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Iva_Compras+=$iva;		 
		 $Compras= number_format($iva,2,",",".");
		 } else {
		 $Iva_Compras+=$iva*$tipocambio;
		 $Compras= number_format($iva*$tipocambio,2,",",".");
		 }
		 $iva=0;

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,$Compras." ");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

if(mysqli_result($res_resultado, $contador, "tipo")!=2) {
		$total= mysqli_result($res_resultado, $contador, "totalfactura");
		} else {
		$total= mysqli_result($res_resultado, $contador, "totalfactura")*(-1);
		}				
		 if (mysqli_result($res_resultado, $contador, "moneda")==1){
		 $Total_Compras+=$total;		 
			$TCompras= number_format($total,2,",","."); 
		 } else {
		 $Total_Compras+=$total*$tipocambio;
			$TCompras= number_format($total*$tipocambio,2,",","."); 
		 }
		 $total=0;

$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, $TCompras." ");
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('A'.$x.':K'.$x)->applyFromArray($border_fino);

		$contador++;
		$Cant_Compras++;	
		$x++;
		}         
	}
$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
}

/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle("H".$x.":I".$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("H".$x.":I".$x)->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle("H".$x.":I".$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue("H".$x, "Compras");
$objPHPExcel->getActiveSheet()->mergeCells("H".$x.":I".$x);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER)
);
/*               */
$objPHPExcel->getActiveSheet()->getDefaultStyle('J'.$x.":K".$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('J'.$x.":K".$x)->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle('J'.$x.":K".$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$x, "Ventas");
$objPHPExcel->getActiveSheet()->mergeCells('J'.$x.":K".$x);
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER)
);
/*               */
$x++;

$objPHPExcel->getActiveSheet()->getDefaultStyle("H".$x.":K".$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("H".$x.":K".$x)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("H".$x.":K".$x)->applyFromArray($border_grueso);

cellColor('H'.$x.':K'.$x, 'FFFF00');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,number_format($Iva_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle("H".$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, number_format($Total_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle("I".$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->setCellValue('J'.$x,number_format($Iva_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle("J".$x)->applyFromArray($border_grueso);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$x, number_format($Total_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('K'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle("K".$x)->applyFromArray($border_grueso);

$ResumenIva_Compras[$mesactual]=$Iva_Compras;
$ResumenTotal_Compras[$mesactual]=$Total_Compras;

$ResumenIva_Ventas[$mesactual]=$Iva_Ventas;
$ResumenTotal_Ventas[$mesactual]=$Total_Ventas;



$x++;
$x++;

cellColor('D'.$x.':E'.$x, 'FFFF00');
$y=$x;

$objPHPExcel->getActiveSheet()->getDefaultStyle("D".$x,":E".$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle("D".$x,":E".$x)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle("D".$x,":E".$x)->applyFromArray($border_fino);

$objPHPExcel->getActiveSheet()->setCellValue("D".$x,@genMonth_Text(date('m',strtotime($sTime))));
$objPHPExcel->getActiveSheet()->mergeCells("D".$x.":E".$x);
$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$x++;

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x,"Saldo IVA ");
$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,  @number_format($Iva_Ventas-$Iva_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$x++;

$objPHPExcel->getActiveSheet()->setCellValue('D'.$x,"Saldo Total ");
$objPHPExcel->getActiveSheet()->getStyle('D'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, @number_format($Total_Ventas-$Total_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$y.':E'.$x)->applyFromArray($border_fino);


/*Finalizo el recorrido para todos los meses entre las fechas señaladas*/

$nuevafecha = strtotime ( '+1 month' , strtotime ( $nuevafecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

}

//Comienzo la hoja del resumen.

$objPHPExcel->createSheet($mesactual);
$objPHPExcel->setActiveSheetIndex($mesactual);
$objPHPExcel->getActiveSheet()->setTitle("Resumen");


$x=2;

$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('B'.$x.':F'.$x)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x.':F'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Del Periodo");
$objPHPExcel->getActiveSheet()->mergeCells('B'.$x.':F'.$x);
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getDefaultStyle('H'.$x.':J'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('H'.$x.':J'.$x)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x.':J'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Acumulados");
$objPHPExcel->getActiveSheet()->mergeCells('H'.$x.':J'.$x);
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getDefaultStyle('O'.$x.':T'.$x)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle('O'.$x.':T'.$x)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('O'.$x.':T'.$x)->applyFromArray($border_fino);
$objPHPExcel->getActiveSheet()->setCellValue('O'.$x, "Pagos realizados s/boleto");
$objPHPExcel->getActiveSheet()->mergeCells('O'.$x.':T'.$x);
$objPHPExcel->getActiveSheet()->getStyle('O'.$x)->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


$x=3;


cellColor('B'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x, "Iva Vtas");
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

cellColor('C'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x, "Iva Compras");
$objPHPExcel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

cellColor('E'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x, "Ventas");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

cellColor('F'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x, "Compras");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


cellColor('H'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$x, "Ventas");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

cellColor('I'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$x, "Iva vtas");
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

cellColor('J'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$x, "Iva compras");
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

cellColor('L'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('L'.$x, "Iva retenciones");
$objPHPExcel->getActiveSheet()->getStyle('L'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

cellColor('M'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('M'.$x, "Saldo");
$objPHPExcel->getActiveSheet()->getStyle('M'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

cellColor('O'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$x, "IVA (f546)");
$objPHPExcel->getActiveSheet()->getStyle('O'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

cellColor('P'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('P'.$x, "IRAE (f108)");
$objPHPExcel->getActiveSheet()->getStyle('P'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

cellColor('Q'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$x, "IP (f328)");
$objPHPExcel->getActiveSheet()->getStyle('Q'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

cellColor('R'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('R'.$x, "ICOSA (f606)");
$objPHPExcel->getActiveSheet()->getStyle('R'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

cellColor('S'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('S'.$x, "Total");
$objPHPExcel->getActiveSheet()->getStyle('S'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

cellColor('T'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('T'.$x, "Fecha");
$objPHPExcel->getActiveSheet()->getStyle('T'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

$x=4;

$nuevafecha=date_format($fechainicial, 'Y-m-d');

for($mesactual=0;$mesactual<=$totlameses; $mesactual++) {

$startTime =data_first_month_day($nuevafecha); 
$sTime=$startTime;

$endTime = data_last_month_day($nuevafecha); 

$objPHPExcel->getActiveSheet()->setCellValue('A'.$x, genMonth_Text(date('m',strtotime($sTime)))." - ".date('Y',strtotime($sTime)));
$objPHPExcel->getActiveSheet()->getStyle('A'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('B'.$x,number_format($ResumenIva_Ventas[$mesactual],2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setCellValue('C'.$x,number_format($ResumenIva_Compras[$mesactual],2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,number_format($ResumenTotal_Ventas[$mesactual]-$ResumenIva_Ventas[$mesactual],2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,number_format($ResumenTotal_Compras[$mesactual]-$ResumenIva_Compras[$mesactual],2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


$Acumulado_Ventas=$ResumenTotal_Ventas[$mesactual]-$ResumenIva_Ventas[$mesactual] + $Acumulado_Ventas;

$objPHPExcel->getActiveSheet()->setCellValue('H'.$x,number_format($Acumulado_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('H'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


$AcumuladoIVA_Ventas=$ResumenIva_Ventas[$mesactual] + $AcumuladoIVA_Ventas;

$objPHPExcel->getActiveSheet()->setCellValue('I'.$x,number_format($AcumuladoIVA_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('I'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

$AcumuladoIVA_Compras=$ResumenIva_Compras[$mesactual]+ $AcumuladoIVA_Compras;

$objPHPExcel->getActiveSheet()->setCellValue('J'.$x,number_format($AcumuladoIVA_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('J'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);



$Iva_Retenciones=$ResumenIva_Ventas[$mesactual]-$ResumenIva_Compras[$mesactual];

$objPHPExcel->getActiveSheet()->setCellValue('L'.$x,number_format($Iva_Retenciones,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('L'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);


$mes=date('m',strtotime($sTime));

//$Total_Iva_Retenciones+=$Iva_Retenciones;
/*Pagos a la DGI*/
$sql_dgi="SELECT * FROM pagodgi WHERE `mes`='".$mes."' AND `anio`='".$anio."'";
$r_dgi=mysqli_query($GLOBALS["___mysqli_ston"], $sql_dgi);

if($r_dgi !==false) {
$Saldo_Retenciones=$Iva_Retenciones+$Saldo_Retenciones - @mysqli_result($r_dgi, 0, "f546");
if (mysqli_num_rows($r_dgi)>0) {
$f546=mysqli_result($r_dgi, 0, "f546");
$f108=mysqli_result($r_dgi, 0, "f108");
$f328=mysqli_result($r_dgi, 0, "f328");
$f606=mysqli_result($r_dgi, 0, "f606");
$fechaDGI=mysqli_result($r_dgi, 0, "fecha");
} else {
$f546=0;
$f108=0;
$f328=0;
$f606=0;
}
} else {
$f546=0;
$f108=0;
$f328=0;
$f606=0;
$Saldo_Retenciones=$Iva_Retenciones+$Saldo_Retenciones;
}


$objPHPExcel->getActiveSheet()->setCellValue('M'.$x,number_format($Saldo_Retenciones,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('M'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setCellValue('O'.$x,number_format($f546,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('O'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('P'.$x,number_format($f108,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('P'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('Q'.$x,number_format($f328,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('Q'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('R'.$x,number_format($f606,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('R'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('S'.$x,number_format(($f546+$f108+$f328+$f606),2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('S'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setCellValue('T'.$x,implota($fechaDGI)." ");
$objPHPExcel->getActiveSheet()->getStyle('T'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);


$nuevafecha = strtotime ( '+1 month' , strtotime ( $nuevafecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

$fechaDGI='';

$totalResumenIva_Ventas=$totalResumenIva_Ventas+$ResumenIva_Ventas[$mesactual];
$totalResumenIva_Compras=$totalResumenIva_Compras+$ResumenIva_Compras[$mesactual];
$totalResumenTotal_Ventas=$totalResumenTotal_Ventas+$ResumenTotal_Ventas[$mesactual]-$ResumenIva_Ventas[$mesactual];
$totalResumenTotal_Compras=$totalResumenTotal_Compras+$ResumenTotal_Compras[$mesactual]-$ResumenIva_Compras[$mesactual];
$totalIva_Retenciones=$totalIva_Retenciones+$Iva_Retenciones;
$totalSaldo_Retenciones=$totalSaldo_Retenciones+$Saldo_Retenciones;
$totalf546=$totalf546+$f546;
$totalf108=$totalf108+$f108;
$totalf328=$totalf328+$f328;
$totalf606=$totalf606+$f606;
$Saldo_Retenciones=0;
$f546=0;
$f108=0;
$f328=0;
$f606=0;
$x++;
}

cellColor('B'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$x,number_format($totalResumenIva_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('B'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

cellColor('C'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$x,number_format($totalResumenIva_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('C'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

cellColor('EM'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$x,number_format($totalResumenTotal_Ventas,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('E'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

cellColor('F'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$x,number_format($totalResumenTotal_Compras,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('F'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

cellColor('L'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('L'.$x,number_format($totalIva_Retenciones,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('L'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

cellColor('M'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('M'.$x,number_format($totalSaldo_Retenciones,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('M'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

cellColor('O'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$x,number_format($totalf546,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('O'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);

cellColor('P'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('P'.$x,number_format($totalf108,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('P'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

cellColor('Q'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('Q'.$x,number_format($totalf328,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('Q'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

cellColor('R'.$x, 'B2B2B2');
$objPHPExcel->getActiveSheet()->setCellValue('R'.$x,number_format($totalf606,2,",",".")." ");
$objPHPExcel->getActiveSheet()->getStyle('R'.$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

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