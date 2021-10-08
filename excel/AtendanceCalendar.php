<?php

//use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
//require __DIR__ . '/../Header.php';

require_once __DIR__ . '/PhpSpreadsheet-develop/src/Bootstrap.php';


require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

$fecha = isset($_GET['fechainicio']) ? $_GET['fechainicio'] : date("Y-m-d");

$fechafin=data_first_month_day($fecha). " 23:59:59";
$fechainicio=data_first_month_day($fecha). " 00:00:00";;
$endTime=data_last_month_day($fechainicio). " 23:59:59";;


function columna($diasemana) {
	$col=array(1=>'C',2=>'D', 3=>'E', 4=>'F',5=>'G',6=>'H',7=>'B');
	return $col[$diasemana];
}

$codusuarios=$_GET['codusuarios'];
$file=$_GET['file'];

if(strlen($codusuarios)>0){
	$objusuario = new Consultas('usuarios');
	$objusuario->Select();
	$objusuario->Where('codusuarios', $codusuarios);
	$resultado = $objusuario->Ejecutar();

	$datos=$resultado['datos'][0];
	$nombre= $datos['nombre']. ' '. $datos['apellido'];
	$nempleado = $datos['nempleado'];
}



$reader = IOFactory::createReader('Xls');
$spreadsheet = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/CalendarTemplate.xls');

$spreadsheet->getActiveSheet()->setCellValue('D1' , $nombre);


$spreadsheet->getActiveSheet()->setCellValue('B2' , genMonth_Text(date("m", strtotime($fechainicio)))." ".date("Y", strtotime($fechainicio)));
$spreadsheet->getActiveSheet()->setTitle(genMonth_Text(date("m", strtotime($fechainicio)))." ".date("Y", strtotime($fechainicio)));
$row=4;

$diasemana=date("N", strtotime($fechainicio));
$diaCol=1;
if($diasemana!=7) {
	$diaCol=$diasemana;
}
	$inicio=date("Y-m-d ", strtotime( '-'.$diaCol.' day' , strtotime($fechainicio)));
	$x=1;
	while($x<=$diaCol) {
	$col=columna(date("N", strtotime($inicio)));
	$spreadsheet->getActiveSheet()->getStyle($col. $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->setCellValue($col. $row, date("j", strtotime($inicio)))->getStyle($col. $row)->getFill()->getStartColor()->setARGB('87CEEB');
	$spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->setCellValue($col. ($row+1),' ')->getStyle($col. ($row+1))->getFill()->getStartColor()->setARGB('87CEEB');
	
	$inicio=date("Y-m-d ", strtotime( '+1 day' , strtotime($inicio)));
	$x++;
	}
	

while(strtotime($fechainicio)<=strtotime($endTime)) {
	$diasemana=date("N", strtotime($fechainicio));	
	$col=columna($diasemana);
	
	$spreadsheet->getActiveSheet()->setCellValue($col. $row, date("j", strtotime($fechainicio)));
////////////////////Aquí viene todo el cálculo de las horas	

$marcain=1;
$noregistro=1; // Digo que no hay registro para este día, si encuentro alguno cambio a 0    
/*
Obtengo el horario que debería cumplir el usuario y lo almaveno en un array multidimencional 
*/
$objhora = new Consultas('horariousuario');
$objhora->Select();
$objhora->Where('diasemana', $diasemana, 'LIKE');
//$objhora->Where('vigencia', $fechainicio, '<=');
$objhora->Where('codusuarios', $codusuarios, '=');
$objhora->Orden('vigencia', 'DESC');
$paciente=$objhora->Ejecutar();
	//echo '<br>Consulto horario asignado<br>'.$paciente['consulta'].'<br>';

	$hingreso='';    $hsalida='';    $descanso='';
	$TSHsalida=$TSHingreso=$horaSalidaTS=$horaIngresoTS=array();

	$CantidadHorarios=$paciente['numfilas'];

	if( $CantidadHorarios>0){

	$x=0;      
		foreach($paciente['datos'] as $reshorario){
			$hingreso=date("Y-m-d", strtotime($inicio)).' '.$reshorario["horaingreso"];
			$hsalida=date("Y-m-d", strtotime($inicio)).' '.$reshorario["horasalida"];
					
			$Time = new DateTime($hingreso);
			$TSHingreso[$x]=$Time->getTimestamp();
			$horaIngresoTS[$x]=$Time->format('H:i:s');
			$Time = new DateTime($hsalida);
			$horaSalidaTS[$x]=$Time->format('H:i:s');
			$TSHsalida[$x]=$Time->getTimestamp();
			$x++;
		}
	}

	/*

	Para cada día unifico las marcas, elimino las que estan cerca una de otras a menos de 2 minutos

	*/

	$obj1 = new Consultas('biometriclog');
	$obj1->Select();
	$obj1->Where('codusuarios', $nempleado, '=');
	$obj1->Where('datetime', $fechainicio, '>=');
	$obj1->Where('datetime', $fechafin, '<=');
	$obj1->Orden('codlog', 'ASC');
	$obj1->Where('borrado', '0');
	$obj1->Where('depurado', '0');

	$pacie=$obj1->Ejecutar();
	$rows = $pacie["datos"];
	//echo " Consula para depurar ". $pacie['consulta'].'<br>';
	//echo '<br>Cantidad de marcas antes de depurar: '.$pacie['numfilas'];

	$MarcaControl=$OldCodlog=$noregistro=$TSMarcaAux=0;
	$control=$pacie['numfilas'];
	$DeafaultTime=(new DateTime('2017-01-01 00:00:00'))->getTimestamp();

	if($pacie['numfilas']>0){
		foreach($rows as $row){
			
			if($noregistro==0){
				$OldCodlog=$row['codlog'];
				$TSMarcaAux = (new DateTime($row['datetime']))->getTimestamp();
			}

			if($noregistro==($control-1) and $control%2!=0){
				$nombres = array();
				$valores = array();

				$nombres[] = 'depurado';
				$valores[] = '1';

				$objqq = new Consultas('biometriclog');
				$objqq->Update($nombres,$valores);
				$objqq->Where('codlog',$row['codlog'] , '=');
				$objqq->Ejecutar();

			}

			$TSMarca = (new DateTime($row['datetime']))->getTimestamp();
			
			//echo '<br> --------';			
			//echo '<br> diferencia entre marca y hora '.(((int)$TSMarca-(int)$TSMarcaAux-60*2));
			//echo '<br> /********/';
			if(((int)$TSMarca-(int)$TSMarcaAux-60*2)<=0 and $noregistro>0 and $DeafaultTime==$EnddatetimeAux ) {

				//echo '<br>Elimino la marca actual luego de actualizar la marca anterior con el horario de salida de la marca actual<br>';
				$nombres = array();
				$valores = array();

				$nombres[] = 'borrado';
				$valores[] = '1';

				$objqq = new Consultas('biometriclog');
				$objqq->Update($nombres,$valores);
				$objqq->Where('codlog',$row['codlog'] , '=');
				$objqq->Ejecutar();
				
				
			}if(((int)$TSMarca-(int)$TSMarcaAux-60*2)>0 and $noregistro>0 and $DeafaultTime==(new DateTime($row['enddatetime']))->getTimestamp() ){
				$objqq = new Consultas('biometriclog');
				$objqq->Select();
				$objqq->Where('codlog', $OldCodlog , '=');
				$objqq->Where('borrado', '0', '=');
				$respuesta=$objqq->Ejecutar();
				if($respuesta['numfilas']>0){
					$nombres = array();
					$valores = array();

					$nombres[] = 'enddatetime';
					$valores[] = $row['datetime'];
					$nombres[] = 'depurado';
					$valores[] = '1';

					$objqq = new Consultas('biometriclog');
					$objqq->Update($nombres,$valores);
					$objqq->Where('codlog',$OldCodlog , '=');
					$objqq->Ejecutar();
				
				//echo '<br>Elimino la marca actual luego de actualizar la marca anterior con el horario de salida de la marca actual<br>';
				$nombres = array();
				$valores = array();

				$nombres[] = 'borrado';
				$valores[] = '1';

				$objqq = new Consultas('biometriclog');
				$objqq->Update($nombres,$valores);
				$objqq->Where('codlog',$row['codlog'] , '=');
				$objqq->Ejecutar();
				
				}
				$OldCodlog=$row['codlog'];
				
			}
			$TSMarcaAux=$TSMarca;
			
			$EnddatetimeAux=(new DateTime($row['enddatetime']))->getTimestamp();
			
			$noregistro++;

		}
	}

		

	$obj1 = new Consultas('biometriclog');
	$obj1->Select();
	$obj1->Where('codusuarios', $nempleado, '=');
	$obj1->Where('datetime', $fechainicio, '>=');
	$obj1->Where('datetime', $fechafin, '<=');
	$obj1->Orden('codlog', 'ASC');	
	$obj1->Where('borrado', '0');
	$obj1->Where('depurado', '1');
	$pacie=$obj1->Ejecutar();
	//echo $pacie['consulta'];
	$rows = $pacie["datos"];

	$DeafaultTime=(new DateTime('2017-01-01 00:00:00'))->getTimestamp();
	$comentario='';
	$horastotales='00:00';

	if($CantidadHorarios>0){
		$comentario.='Horario a cumplir ';
		for($xy=$horarioCumplido;$xy<$CantidadHorarios; $xy++){
			$comentario.="\r\n Ing: ".$horaIngresoTS[$xy]."\r\n salida ".$horaSalidaTS[$xy];
		}
	}

	if($pacie['numfilas']>0){
		$MarcaCumplida= array();
		$comentario="\r\n Horas realizadas: ";
		foreach($rows as $fetch){

			if($pacie['numfilas']==1){
				if($fetch['enddatetime']==$DeafaultTime){
					$start = date("H:i:s", strtotime($fetch['datetime']));
					$end = ' ';
					$comentario.="\r\n Ing: ".$start."\r\n No marca salida ";

				}else{
					$start = date("H:i:s", strtotime($fetch['datetime']));
					$end = date("H:i:s", strtotime($fetch['enddatetime']));
					$restahoras=RestaHoras($end, $start);
					$horastotales=SumaHoras($horastotales,$restahoras);
					$comentario.="\r\n Ing: ".$start."\r\n salida ".$end;
				}
			}else{
				if($fetch['enddatetime']!=$DeafaultTime){
					$start = date("H:i:s", strtotime($fetch['datetime']));
					$end = date("H:i:s", strtotime($fetch['enddatetime']));
					$restahoras=RestaHoras($end, $start);
					$horastotales=SumaHoras($horastotales,$restahoras);
					$comentario.="\r\n Ing: ".$start."\r\n salida ".$end;
				}else{
					$start = date("H:i:s", strtotime($fetch['datetime']));
					$comentario.="\r\n Ing: ".$start."\r\n no marca salida ";
				}
			}
 		}
	   	$timeaux=$fetch['datetime'];
	   	$contador=1;
	} //fin del día

 $spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$spreadsheet->getActiveSheet()->setCellValue($col. ($row+1),$comentario ."\r\n Total: ".$horastotales);
//Comentarios en la celda
$spreadsheet->getActiveSheet()->getComment($col. ($row+1))->setAuthor('UYCODEKA');
//$commentRichText = $spreadsheet->getActiveSheet()->getComment($col. ($row+1))->getText()->createTextRun(':');
//$commentRichText->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getComment($col. ($row+1))->getText()->createTextRun($comentario);
//Fin comentarios

/////////////////////////////////
	if($diasemana==6) {
		$row=$row+2;
	}
    $fechainicio=date("Y-m-d ", strtotime ( '+1 day' , strtotime($fechafin))). " 00:00:00";//$fechafin;
    $fechafin= date("Y-m-d ", strtotime ( '+1 day' , strtotime($fechafin))). " 23:59:59";
}

//Completo las celdas del mes
$inicio=date("Y-m-d ", strtotime( '+1 day' , strtotime($endTime)));;
$diasemana=date("N", strtotime($inicio));

	while($diasemana<=6) {
	$col=columna(date("N", strtotime($inicio)));
	$spreadsheet->getActiveSheet()->getStyle($col. $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->setCellValue($col. $row, date("j", strtotime($inicio)))->getStyle($col. $row)->getFill()->getStartColor()->setARGB('87CEEB');
	$spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$spreadsheet->getActiveSheet()->setCellValue($col. ($row+1),' ')->getStyle($col. ($row+1))->getFill()->getStartColor()->setARGB('87CEEB');
	$diasemana++;
	$inicio=date("Y-m-d ", strtotime( '+1 day' , strtotime($inicio)));;
	}

//Proteger la hoja contra modificaciones
/*
$spreadsheet->getActiveSheet()->getProtection()->setPassword('mcc123mcc');
$spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
$spreadsheet->getActiveSheet()->getProtection()->setSort(true);
$spreadsheet->getActiveSheet()->getProtection()->setInsertRows(true);
$spreadsheet->getActiveSheet()->getProtection()->setFormatCells(true);
    */

//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('../tmp/'.$file.'.xlsx');
exit;