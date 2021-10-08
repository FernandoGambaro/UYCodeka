<?php

require_once __DIR__ . '/PhpSpreadsheet-develop/src/Bootstrap.php';

//use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Settings;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');


//$fechafin=data_first_month_day(date("Y-m-d")). " 23:59:59";
//$fechainicio=data_first_month_day(date("Y-m-d")). " 00:00:00";;
//$endTime=data_last_month_day($fechainicio). " 23:59:59";;

// Paso las fechas a timestamp



function columna($diasemana) {
	$col=array(1=>'C',2=>'D', 3=>'E', 4=>'F',5=>'G',6=>'H',7=>'I',8=>'J',9=>'K',10=>'L',11=>'M',12=>'N',13=>'O',14=>'P', 15=>'Q', 16=>'R', 17=>'S',18=>'T',19=>'U',20=>'V',21=>'W',22=>'X');
	return $col[$diasemana];
}

$styleArray =  [
	'borders' => [
		'allBorders' => [
			'borderStyle' => Border::BORDER_THIN,
			'color' => ['argb' => '00000000'],
		]
	]
];

$mes=isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anio=isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$fechaDesde=date('Y-m-01', strtotime(date($anio.'-'.$mes.'-01')));
$fechaHasta=date('Y-m-t', strtotime(date($anio.'-'.$mes.'-01')));

$codusuarios=$_GET['codusuarios'];

$fechaDesde=isset($_GET['fechadesde']) ? $_GET['fechadesde'] : $fechaDesde;
$fechaHasta=isset($_GET['fechahasta']) ? $_GET['fechahasta'] : $fechaHasta;

$file=$_GET['file'];
$tolerancia=5*60; //Tolerancia de 5min tarde
$multa=15*60; //15minutos, si llega mas tarde de 5min, y antes de los 15min 

if(file_exists('../tmp/'.$file.'.xlsx')){
	unlink('../tmp/'.$file.'.xlsx');
}

//$codusuarios='';
$obj = new Consultas('usuarios');
$obj->Select('usuarios.nombre, usuarios.apellido, usuarios.codusuarios, usuarios.nempleado, usuarios.huella, usuarios.estado');

if(strlen($codusuarios)>0){
$obj->Where('codusuarios', $codusuarios, '=',  '', '', 'usuarios');
}else{
	$obj->Join('nempleado', 'biometriclog', 'INNER', 'usuarios', 'codusuarios' );
	$obj->Where('estado', '0', '', '', '', 'usuarios' );
	$obj->Where('huella', '1', '', '', '', 'usuarios' );
}
$obj->Where('borrado', '0', '',  '', '', 'usuarios' );
$obj->Orden('usuarios.nempleado', 'ASC');
if(strlen($codusuarios)==0){
	$obj->Group('biometriclog.codusuarios');
}
//var_dump($obj);
$paciente = $obj->Ejecutar();
$filas=$paciente['datos'];

//echo "<br>".$paciente['consulta'];

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

//$spreadsheet->createSheet();
//$spreadsheet->setActiveSheetIndexByName('Worksheet');


foreach($filas as $fila){

	$codusuarios=$fila['codusuarios'];
	$nempleado = $fila['nempleado'];

	if(strlen($nempleado)>0){

	$dateTime = new DateTime($fechaDesde);
	$fechainicio= $dateTime->format('Y-m-d H:i:s');
	$Timestampfechainicio=$dateTime->getTimestamp();
	
	$dateTimeFin = new DateTime($fechaDesde);
	$dateTimeFin->add(new DateInterval('P1D'));
	$fechafin = $dateTimeFin->format('Y-m-d H:i:s');
	
	$showEndTimne=$dateTime = new DateTime($fechaHasta);
	$dateTime->add(new DateInterval('P1D'));
	
	$endTime=$dateTime->getTimestamp();

	$nombre=$fila['nombre'] .' '.$fila["apellido"];

		$reader = IOFactory::createReader('Xlsx');
		$spreadsheet1 = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/HorasTrabajadas.xlsx');

		$clonedWorksheet = clone $spreadsheet1->getSheetByName('Hoja1');
		$spreadsheet->addExternalSheet($clonedWorksheet);

		$spreadsheet->setActiveSheetIndexByName('Hoja1');


		$locale = 'es';
		$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
		if (!$validLocale) {
			echo 'Unable to set locale to ' . $locale . " - reverting to en_us" . PHP_EOL;
		}

		//$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		//$spreadsheet = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/HorasTrabajadas.xlsx');
		$spreadsheet->getActiveSheet()->setCellValue('M1' , 'Presionar Shift+Ctrl+F9 para mostrar');

		$spreadsheet->getActiveSheet()->setCellValue('B2' , $nombre);
		$spreadsheet->getActiveSheet()->setTitle(substr(trim($nombre), 0 , 15));

		$spreadsheet->getActiveSheet()->setCellValue('G2' , date("d/m/Y", strtotime($fechaDesde)));
		$spreadsheet->getActiveSheet()->setCellValue('J2' , date("d/m/Y", strtotime($fechaHasta)));


		$row=6;

		while($Timestampfechainicio<$endTime) {
			$licencia=0;
			$NewdateTime = new DateTime();
			$NewdateTime->setTimestamp($Timestampfechainicio);
			$fechainicio=$NewdateTime->format('Y-m-d H:i:s');

			$diasemana=$NewdateTime->format('w');

			if($diasemana==0) {
				$diasemana=7;
			}	
			
			$spreadsheet->getActiveSheet()->getStyle('A'.$row.':U'.$row)->applyFromArray($styleArray);

			$desde=$NewdateTime->format('Y-m-d');

			$objLicencia = new Consultas('usuarioslicencia');
			$objLicencia->Select();
			$objLicencia->Where('desde', $desde, '<=');
			$objLicencia->Where('hasta', $desde, '>=');
			$objLicencia->Where('codusuarios', $codusuarios, '=');
			$objLicencia->Where('borrado', '0', '=');
			
			$objLicencia->Orden('codlicencia', 'DESC');
			
			$Licencia=$objLicencia->Ejecutar();
			//echo "<br>". $Licencia['consulta'];
			$NumLicencia=$Licencia['numfilas'];
			
			if($NumLicencia>0){

				$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()->setCellValue('A'.$row,date("d/m/Y ", strtotime($fechainicio)));

				$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
				$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$spreadsheet->getActiveSheet()->setCellValue('B'.$row,diasemana(date("Y-m-d ", strtotime($fechainicio))));

		if($diasemana!=6 and $diasemana!=7){
				$spreadsheet->getActiveSheet()->mergeCells('C'.$row.':U'.$row);
				$spreadsheet->getActiveSheet()->getStyle('C'.$row.':U'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('808080');
				$spreadsheet->getActiveSheet()->setCellValue('C'.$row,'Licencia');
				$spreadsheet->getActiveSheet()->getStyle('C'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}
				$licencia=1;
			}else{	

			/*
			Obtengo el horario que debería cumplir el usuario y lo almaceno en un array multidimencional 
			*/
			$objhora = new Consultas('horariousuario');
			$objhora->Select();
			$objhora->Where('diasemana', $diasemana, 'LIKE');
			//$objhora->Where('vigencia', $fechainicio, '<=');
			$objhora->Where('codusuarios', $codusuarios, '=');
			$objhora->Orden('vigencia', 'DESC');
			$paciente=$objhora->Ejecutar();
				//echo '<br>Consulto horario asignado<br>'.$paciente['consulta'].'<br>';

				$hingreso='';    $hsalida='';    
				$descanso=$TSHsalida=$TSHingreso=$horaSalidaTS=$horaIngresoTS=[];

				$CantidadHorarios=$paciente['numfilas'];
				$horastotales='00:00';
		
				if( $CantidadHorarios>0){
				$x=0;      
					foreach($paciente['datos'] as $reshorario){
						$col=1;	
						$hingreso=date("Y-m-d", strtotime($inicio)).' '.$reshorario["horaingreso"];
						$hsalida=date("Y-m-d", strtotime($inicio)).' '.$reshorario["horasalida"];
								
						$Time = new DateTime($hingreso);
						$TSHingreso[$x]=$Time->getTimestamp();
						$horaIngresoTS[$x]=$Time->format('H:i:s');
						$Time = new DateTime($hsalida);
						$horaSalidaTS[$x]=$Time->format('H:i:s');
						$TSHsalida[$x]=$Time->getTimestamp();
						if(strlen($reshorario["descanso"])>0){
							$descanso[$x]=$reshorario['descanso'];
						}

						$start = date("H:i", $TSHingreso[$x]);
						$end = date("H:i", $TSHsalida[$x]);

						$restahoras=RestaHoras($end, $start);
						$horastotales=SumaHoras($horastotales,$restahoras);

						$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$spreadsheet->getActiveSheet()->setCellValue('A'.$row,date("d/m/Y ", strtotime($fechainicio)));

						$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$spreadsheet->getActiveSheet()->setCellValue('B'.$row,diasemana(date("Y-m-d ", strtotime($fechainicio))));

							//for($xy=0;$xy<$CantidadHorarios; $xy++){
							//	echo '<br>'."\r\n Ing: ".$horaIngresoTS[$xy]." salida ".$horaSalidaTS[$xy];
							//}	

						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$horaIngresoTS[$x]);
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ff99');

						$col++;
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$horaSalidaTS[$x]);
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
						$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ff99');

						$col++;
						if(strlen($descanso[$x])>0){
							$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
							$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
							$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$descanso[$x]);
							$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
						}
						//$descanso[$x]='';
						$x++;
						$row++;
					}
					$row=$row-1;
				}
		$col=5;	

			////////////////////Aquí viene todo el cálculo de las horas	
/*

		Para cada día unifico las marcas, elimino las que estan cerca una de otras a menos de 3 minutos

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

		if($pacie['numfilas']>1){
			foreach($rows as $rowfila){
				
				if($noregistro==0){
					$OldCodlog=$rowfila['codlog'];
					$TSMarcaAux = (new DateTime($rowfila['datetime']))->getTimestamp();
				}

				$TSMarca = (new DateTime($rowfila['datetime']))->getTimestamp();
				
				if(((int)$TSMarca-(int)$TSMarcaAux-60*3)<=0 and $noregistro>0 and $DeafaultTime==$EnddatetimeAux ) {
				//echo '<br> --------';			
				//echo '<br> diferencia entre marca y hora '.(((int)$TSMarca-(int)$TSMarcaAux-60*2));
				//echo '<br> /********/';

					//echo '<br>Elimino la marca actual luego de actualizar la marca anterior con el horario de salida de la marca actual<br>';
					$nombres = array();
					$valores = array();

					$nombres[] = 'borrado';
					$valores[] = '1';

					$objqq = new Consultas('biometriclog');
					$objqq->Update($nombres,$valores);
					$objqq->Where('codlog',$rowfila['codlog'] , '=');
					$objqq->Ejecutar();
					
					//$TSMarcaAux=$TSMarca;
				}elseif(((int)$TSMarca-(int)$TSMarcaAux-60*3)>0 and $noregistro>0 and $DeafaultTime==(new DateTime($rowfila['enddatetime']))->getTimestamp() ){
					$objqq = new Consultas('biometriclog');
					$objqq->Select();
					$objqq->Where('codlog', $OldCodlog , '=');
					$objqq->Where('borrado', '0', '=');
					$respuesta=$objqq->Ejecutar();
					if($respuesta['numfilas']>0){
						$nombres = array();
						$valores = array();

						$nombres[] = 'enddatetime';
						$valores[] = $rowfila['datetime'];
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
					$objqq->Where('codlog',$rowfila['codlog'] , '=');
					$objqq->Ejecutar();
					
					}
					$OldCodlog=$rowfila['codlog'];
					$TSMarcaAux=$TSMarca;
				}
				
				
				$EnddatetimeAux=(new DateTime($rowfila['enddatetime']))->getTimestamp();
				
				$noregistro++;

			}
		}elseif($pacie['numfilas']==1){

			$nombres = array();
			$valores = array();

			$nombres[] = 'depurado';
			$valores[] = '1';

			$objqq = new Consultas('biometriclog');
			$objqq->Update($nombres,$valores);
			$objqq->Where('codlog',$rows[0]['codlog'] , '=');
			$objqq->Ejecutar();

		}



			/*
			Para cada día unifico las marcas, elimino las que estan cerca una de otras a menos de 2 minutos
			*/


			$obj1 = new Consultas('biometriclog');
			$obj1->Select();
			$obj1->Where('codusuarios', $nempleado, '=');
			$obj1->Where('datetime', $fechainicio, '>=');
			$obj1->Where('datetime', $fechafin, '<=');
			$obj1->Where('borrado', '0');
			$obj1->Where('depurado', '1', '=');
			$obj1->Orden('codlog', 'ASC');	
			$pacie=$obj1->Ejecutar();
			$DATArows = $pacie["datos"];
			//echo '<br>'. $pacie['consulta'];

			$DeafaultTime=(new DateTime('2017-01-01 00:00:00'))->getTimestamp();
			$comentario='';

			$spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			$spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getNumberFormat()->setFormatCode('HH:MM');
			$pacienumfilas=0;
			if($pacie['numfilas']>0){
				$pacienumfilas=$pacie['numfilas'];
				$MarcaCumplida= array();
				//echo $pacie['numfilas'];
				$control=0;
				foreach($DATArows as $fetch){
					if($control>6){
					break;
					}
					if($pacie['numfilas']==1){
						if($fetch['enddatetime']==$DeafaultTime){
							$start = date("H:i", strtotime($fetch['datetime']));
							
							$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
							$col++;	

						}else{
							$start = date("H:i", strtotime($fetch['datetime']));
							$end = date("H:i", strtotime($fetch['enddatetime']));
							$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
							$col++;	
							if($end!='00:00'){
								$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
							}
							$col++;	
						}
					}else{
						if($fetch['enddatetime']!=$DeafaultTime){
							$start = date("H:i", strtotime($fetch['datetime']));
							$end = date("H:i", strtotime($fetch['enddatetime']));
							$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
							$col++;	
							if($end!='00:00'){
								$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
							}
							$col++;	
						}else{
							$start = date("H:i", strtotime($fetch['datetime']));
							
							$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
							$col++;	
						}
					}
					$control++;
				}
				$col=7;

			} //fin del día
			
		}
		$col=11;

		if(date('H:i', strtotime($horastotales))!='00:00' and $licencia==0){
			$formula = '=TIMEVALUE("'.date('H:i', strtotime($horastotales)).'")';
			$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
			$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
			$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('66ff00');
		}



		$col++;
		
		//Cálculo la cantidad de horas realizadas sin descanso, si la cantidad de horas es mayor a las asignadas muestro las asignadas y luego en extras el resto
		//NOTA: para que la formula funcione hay que utilizar coma (,) en lugar de punto y coma (;) en las mismas 
		//Columna N
		
		$formula = '=IF(IF(ISBLANK(G'.$row.'),0,IF(ISBLANK(H'.$row.'),0,IF(ISBLANK(I'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(J'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(K'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',IF(ISBLANK(L'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',L'.$row.'-K'.$row.'+J'.$row.'-I'.$row.'+H'.$row.'-G'.$row.'))))))>M'.$row.',IF(AND(ISBLANK(D'.$row.'),ISBLANK(C'.$row.')),"",M'.$row.'),IF(ISBLANK(G'.$row.'),"",IF(ISBLANK(H'.$row.'),"",IF(ISBLANK(I'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(J'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(K'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',IF(ISBLANK(L'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',L'.$row.'-K'.$row.'+J'.$row.'-I'.$row.'+H'.$row.'-G'.$row.')))))))';
		

		$spreadsheet->getActiveSheet()->getCell(columna($col).$row)->setValue($formula);
		$spreadsheet->getActiveSheet()->getCell(columna($col).$row)->getStyle()->setQuotePrefix(true);
		//->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b2b2b2');

		$col++;

		//Cantidad de horas extras, horas asignadas menos horas realizadas columna O
		$formula = '=IF(IF(ISBLANK(G'.$row.'),0,IF(ISBLANK(H'.$row.'),0,IF(ISBLANK(I'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(J'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(K'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',IF(ISBLANK(L'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',L'.$row.'-K'.$row.'+J'.$row.'-I'.$row.'+H'.$row.'-G'.$row.'))))))>M'.$row.',IF(ISBLANK(G'.$row.'),"",IF(ISBLANK(H'.$row.'),"",IF(ISBLANK(I'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(J'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(K'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',IF(ISBLANK(L'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',L'.$row.'-K'.$row.'+J'.$row.'-I'.$row.'+H'.$row.'-G'.$row.'))))))-M'.$row.',IF(ISBLANK(G'.$row.'),"",IF(ISBLANK(H'.$row.'),"",IF(ISBLANK(I'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(J'.$row.'),H'.$row.'-G'.$row.',IF(ISBLANK(K'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',IF(ISBLANK(L'.$row.'),H'.$row.'-G'.$row.'+J'.$row.'-I'.$row.',L'.$row.'-K'.$row.'+J'.$row.'-I'.$row.'+H'.$row.'-G'.$row.')))))))';
		$spreadsheet->getActiveSheet()->getCell(columna($col).$row)->setValue($formula);
		$spreadsheet->getActiveSheet()->getCell(columna($col).$row)->getStyle()->setQuotePrefix(true);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b2b2b2');
		$col++;
		//Total de Horas
		$formula ='=IF(N'.$row.'="","",IF(O'.$row.'="","",N'.$row.'+O'.$row.'))';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99ffff');
		//Horas en minutos.
		$col++;
		//Falta
		$col++;
		$formula = '=IF(ISBLANK(C'.$row.'),"",IF(AND(ISBLANK(G'.$row.'), ISBLANK(H'.$row.'),ISBLANK(I'.$row.'),ISBLANK(J'.$row.'),ISBLANK(K'.$row.'),ISBLANK(L'.$row.')),"falto",""))';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		//$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
		//
		$valor=$spreadsheet->getActiveSheet()->getCell(columna($col).$row)->getCalculatedValue();
		if((int)$valor!=0) {
		$spreadsheet->getActiveSheet()->getStyle('A'.$row.':U'.$row)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getStyle('A'.$row.':U'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle('A'.$row.':U'.$row)->getFill()->getStartColor()->setARGB('FFFF0000');	
		}
		$valor='';
		//$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
		//Tarde
		$col++;
		$formula = '=IF(ISBLANK(C'.$row.'),"",IF((G'.$row.')*1440>(C'.$row.')*1440,(G'.$row.'-C'.$row.'),""))';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');

		//E.Des
		$col++;
		$formula = '=IF(ISBLANK(H'.$row.'),"",IF(ISBLANK(I'.$row.'),"",I'.$row.'-H'.$row.'))';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');

		//Sale Antes
		$col++;
		
		$formula = '=IF(ISBLANK(C'.$row.'),"",IF(ISBLANK(H'.$row.'),"",IF(ISBLANK(I'.$row.'),IF((H'.$row.')*1440<(D'.$row.')*1440,
		D'.$row.'-H'.$row.',""),IF(ISBLANK(J'.$row.'),IF((I'.$row.')*1440<(D'.$row.')*1440,D'.$row.'-I'.$row.',""),IF(ISBLANK(K'.$row.'),
		IF(J'.$row.'*1440<D'.$row.'*1440,D'.$row.'-J'.$row.',""),IF(ISBLANK(L'.$row.'),IF(K'.$row.'*1440<D'.$row.'*1440,D'.$row.'-K'.$row.',""),
		IF(L'.$row.'*1440<D'.$row.'*1440,D'.$row.'-L'.$row.',"")))))))';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');

		//IF(AND(ISBLANK(G7),ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),"",IF(ISBLANK(J7),(IF(ISBLANK(I7),(IF(ISBLANK(H7),"",IF(D7>H7,(D7-H7)*1440,""))),IF(D7>I7,(D7-I7)*1440,""))),IF(D7>J7,(D7-J7)*1440,"")))
		//IF(AND(ISBLANK(G7),ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),"",IF(AND(ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),IF(D7>G7,(D7-G7)*1440,""),IF(AND(ISBLANK(I7),ISBLANK(J7)),IF(D7>H7,	(D7-H7)*1440,	0),IF(ISBLANK(J7),IF(D7>I7,(D7-I7)*1440;0),IF(D7>J7,(D7-J7)*1440,""),""))))
		//Comentarios en la celda
		/*
		$spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->setAuthor('UYCODEKA');
		$commentRichText = $spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->getText()->createTextRun('Horas realizadas:');
		$commentRichText->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->getText()->createTextRun($comentario);
		*/
		//Fin comentarios


		/////////////////////////////////
		$spreadsheet->getActiveSheet()->getStyle('A'.$row.':U'.$row)->applyFromArray($styleArray);

		if( $CantidadHorarios==0 or $pacienumfilas==0){

			$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->setCellValue('A'.$row,date("d/m/Y ", strtotime($fechainicio)));

			$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->setCellValue('B'.$row,diasemana(date("Y-m-d ", strtotime($fechainicio))));

		}

			$row++;

			$NewdateTime->add(new DateInterval('P1D'));
			$Timestampfechainicio = $NewdateTime->getTimestamp();   
			
			$dateTimeFin = new DateTime($fechafin);
			$dateTimeFin->add(new DateInterval('P1D'));
			$fechafin = $dateTimeFin->format('Y-m-d H:i:s');
		
		}
		//$row=38;
			
		$col=11;
		$formula = '=SUMIF(M6:M'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');
		$col++;
		$formula = '=SUMIF(N6:N'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');
		//Extras
		$col++;
		$formula = '=SUMIF(O6:O'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');
		//Nocturnas
		$col++;
		//Ex.Noc.
		$formula = '=SUMIF(P6:P'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');
		$col++;
		//Falta
		$col++;
		$formula = '=COUNTIF(R6:R'.($row-1).',"falto")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('');
		//Tarde
		$col++;
		$formula = '=SUMIF(S6:S'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');

		//E.Des
		$col++;
		$formula = '=SUMIF(T6:T'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');

		//Sale Antes
		$col++;
		$formula = '=SUMIF(U6:U'.($row-1).',"<>")';
		$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
		$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('[HH]:MM');
		
	}
}

//Proteger la hoja contra modificaciones
/*
$spreadsheet->getActiveSheet()->getProtection()->setPassword('mcc123mcc');
$spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
$spreadsheet->getActiveSheet()->getProtection()->setSort(true);
$spreadsheet->getActiveSheet()->getProtection()->setInsertRows(true);
$spreadsheet->getActiveSheet()->getProtection()->setFormatCells(true);
*/    

$sheetIndex = $spreadsheet->getIndex(
	$spreadsheet->getSheetByName('Worksheet')
);
$spreadsheet->removeSheetByIndex($sheetIndex);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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
$writer->setPreCalculateFormulas(false);
$writer->save('../tmp/'.$file.'.xlsx');
exit;
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);