<?php

require_once __DIR__ . '/PhpSpreadsheet-develop/src/Bootstrap.php';

//use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use\PhpOffice\PhpSpreadsheet\Settings;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

use PhpOffice\PhpSpreadsheet\Calculation\Calculation;
//require __DIR__ . '/../Header.php';

include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');


$fechafin=data_first_month_day(date("Y-m-d")). " 23:59:59";
$fechainicio=data_first_month_day(date("Y-m-d")). " 00:00:00";;
$endTime=data_last_month_day($fechainicio). " 23:59:59";;

// Paso las fechas a timestamp
$dateTime = new DateTime();
$fechainicio= $dateTime->format('Y-m-d H:i:s');
$Timestampfechainicio=$dateTime->getTimestamp();

$dateTimeFin = new DateTime();
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');

$dateTime = new DateTime();
$dateTime->add(new DateInterval('P1M'));
$endTime=$dateTime->getTimestamp();

function columna($diasemana) {
	$col=array(1=>'C',2=>'D', 3=>'E', 4=>'F',5=>'G',6=>'H',7=>'I',8=>'J',9=>'K',10=>'L',11=>'M',12=>'N',13=>'O',14=>'P', 15=>'Q', 16=>'R', 17=>'S',18=>'T',19=>'U');
	return $col[$diasemana];
}

$codusuarios=$_GET['codusuarios'];
$file=$_GET['file'];
$tolerancia=5*60; //Tolerancia de 5min tarde
$multa=15*60; //15minutos, si llega mas tarde de 5min, y antes de los 15min 

$sel_resultado="SELECT * FROM usuarios WHERE borrado=0 AND  pin='".$codusuarios."' ";
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$nombre=mysqli_result($res_resultado, 0, "nombre")." ".mysqli_result($res_resultado, 0, "apellido");


$reader = IOFactory::createReader('Xlsx');
$spreadsheet = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/HorasTrabajadas.xlsx');

$locale = 'es';
$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
if (!$validLocale) {
    echo 'Unable to set locale to ' . $locale . " - reverting to en_us" . PHP_EOL;
}

//$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
//$spreadsheet = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/HorasTrabajadas.xlsx');

$spreadsheet->getActiveSheet()->setCellValue('B2' , $nombre);
$spreadsheet->getActiveSheet()->setTitle(genMonth_Text(date("m"))." ".date("Y"));

$spreadsheet->getActiveSheet()->setCellValue('F2' , date("d/m/Y", strtotime($fechainicio)));
$spreadsheet->getActiveSheet()->setCellValue('I2' , date("d/m/Y", strtotime($endTime)));


$row=6;

while($Timestampfechainicio<$endTime) {
	
$dateTime = new DateTime();
$dateTime->setTimestamp($Timestampfechainicio);
$fechainicio=$dateTime->format('Y-m-d H:i:s');

$diasemana=$dateTime->format('N');

if($diasemana==0) {
	$diasemana=7;
}	
	
$col=1;	


$sql="SELECT * FROM horariousuario WHERE  diasemana LIKE '%".$diasemana."%'  AND vigencia<='".$fechainicio."' 
AND  codusuarios='".$codusuarios."'  ORDER BY vigencia DESC limit 0,1  ";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

    	$hingreso='';    $hsalida='';    $descanso='';
	   $hingreso=mysqli_result($query, 0, "horaingreso");
		$hsalida=mysqli_result($query, 0, "horasalida");
		if(mysqli_result($query, 0, "descanso")!='') {
		$descanso=mysqli_result($query, 0, "descanso").':00';
		}
	$ql1= "SELECT * FROM biometriclog where codusuarios='".$codusuarios."' AND  `datetime` BETWEEN '".$fechainicio."' AND '".$fechafin."'	AND  borrado=0 
	ORDER BY datetime ASC";

$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->setCellValue('A'.$row,date("d-m-Y ", strtotime($fechafin)));

$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('B'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->setCellValue('B'.$row,diasemana(date("Y-m-d ", strtotime($fechafin))));

$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$hingreso);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
$col++;
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$hsalida);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');

$col++;
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$descanso);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');


$col=5;	

////////////////////Aquí viene todo el cálculo de las horas	

    
	$ql1= "SELECT * FROM biometriclog where codusuarios='".$codusuarios."' AND  `datetime` BETWEEN '".$fechainicio."' AND '".$fechafin."'	AND  borrado=0 
	ORDER BY datetime ASC";
	$query1= mysqli_query($GLOBALS["___mysqli_ston"], $ql1);
	$horastotales="00:00:00";
	$comentario='';
    $timeaux=$fechainicio;
    $horaIngreso='';
    $ingreso=true;
    $contador=0;
    $M1menorHi=false;
    $M1mayorHi=false;
	$M1mayorHs=false;
    $considero=0;
    $validado=0;
    $valor='';
    $enddatetimeaux="0000-00-00 00:00:00";
    //Comienzo a recorrer las marcas del día
    
 $spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('G'.$row.":L".$row)->getNumberFormat()->setFormatCode('HH:MM');

	   while($fetch = mysqli_fetch_array($query1,MYSQLI_ASSOC)) {

	   	//No considero las marcas seguidas a menos de 60 segundos
	   	if((strtotime($timeaux)+60) < strtotime($fetch['datetime'])) {
	   		$considero=1;
	   	}else {
	   		$considero=0;
	   	}
	   	if($considero==1) {
	   		//Si se trata de la primer marca $ingreso= true
		    	if(strtotime($fetch['datetime'])>=strtotime($timeaux) and $ingreso==true ) {
		    		//Si la hora datetime de este registro es mayor o igual al enddatetime del registro anterior mas 60 segundos, macro como borrado esta marca
			    	if((strtotime($fetch['datetime'])-strtotime($enddatetimeaux)<=60) and $enddatetimeaux!='0000-00-00 00:00:00' and $fetch['enddatetime']!='NULL' ) {
			    			$sql_del="UPDATE `biometriclog` SET  borrado = '1'	WHERE  `biometriclog`.`codlog` = '".$fetch['codlog']."'";
			    			mysqli_query($GLOBALS["___mysqli_ston"], $sql_del);
			    	}  else {
							  $start = date("H:i", strtotime($fetch['datetime']));
								$usuariocod=$fetch['usuariocod'];
			    			if(strtotime(date("H:i:s", strtotime($fetch['datetime'])))<strtotime($hingreso)) {
			    				if($fetch['validado']==1) {
									if($col<=10) {		
										 $validado=$fetch['validado'];			  
										$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
										$col++;	
									}		
								}		  
								$M1menorHi=true;
								//La marca es mayor a la hora de ingreso y menor a la hora de salida
			    			}elseif(strtotime(date("H:i:s", strtotime($fetch['datetime'])))>strtotime($hingreso) and strtotime(date("H:i:s", strtotime($fetch['datetime'])))<strtotime($hsalida)) {
			    				if($fetch['validado']==1) {
									if($col<=10) {		
										 $validado=$fetch['validado'];			  
										$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
										$col++;	
									}		
								}		  
								$M1mayorHi=true;
			    			}elseif(strtotime(date("H:i:s", strtotime($fetch['datetime'])))>strtotime($hsalida)) {
			    				if($fetch['validado']==1) {
									if($col<=10) {		
										 $validado=$fetch['validado'];			  
										$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$start);
										$col++;	
									}		
								}		  
			    				$M1mayorHs=true;
			    			}
					$ingreso=false;	
					$enddatetimeaux=$fetch['enddatetime'];		    		
				   }
				   
						//*Si dentro de la misma marca tengo la enddatetime
				     	if( $fetch['enddatetime']!='0000-00-00 00:00:00' and $fetch['enddatetime']!='NULL' )  {
				     $enddatetimeaux=$fetch['enddatetime'];  	
				     $ingreso=true;	
				     		if( strtotime(date("H:i:s", strtotime($fetch['enddatetime'])))<strtotime($hingreso)) {
					     		if($fetch['validado']==1) {
				     			$end = date("H:i", strtotime($fetch['enddatetime']));
									if($col<=10) {				     			
									$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
									$col++;	
									$validado=0;
									}				     			
									$ingreso=false;
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;
					     		}				     			
				     		}elseif(strtotime(date("H:i:s", strtotime($fetch['enddatetime'])))==strtotime($hingreso)) {
					     		if($fetch['validado']==1) {
				     			//$end =date("H:i", strtotime($fetch['enddatetime']));
				     			//if($col<=10) {				     			
								//$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
								//$col++;
								//} 		
								$validado=0;	     			
				     			$comentario.="\r\n 30 Ing: ". $start." salida ".$end;				     			
					     		}				     			
				     			$ingreso=false;	
				     			$marcain=0;
				     		}elseif(strtotime(date("H:i:s", strtotime($fetch['enddatetime'])))>strtotime($hingreso) and strtotime(date("H:i:s", strtotime($fetch['enddatetime'])))<strtotime($hsalida)) {
					     		//if($fetch['validado']==1) {
								if($col<=10) {
								$end = date("H:i", strtotime($fetch['enddatetime']));				     			
								$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
								$col++;	
								$validado=0;
								}
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;				     			
					     		//}				
					     			     			
				     			//Marco las horas trabajadas dentro del horario de trabajo
//								$start = date("H:i", strtotime($hingreso));
								//$end = date("H:i", strtotime($fetch['enddatetime']));
								//Marco que se retiro temprano
$ingreso=false;
				     		}elseif(strtotime(date("H:i:s", strtotime($fetch['enddatetime'])))>strtotime($hsalida)) {
						     		if($fetch['validado']==1) {
									if($col<=10) {								  		
							  		$end = date("H:i", strtotime($fetch['enddatetime']));
									$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
									$col++;
$validado=0;										
									}
					     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;								  		
						     		}		
$ingreso=false;						     		
				     		}
				     		//Como el registro contiene la mara 1 y la marca 2, proceso el próximo registro como si fuera el de ingreso
							$timeaux=$fetch['enddatetime'];
						}		
						//////////////////////////////////////////////////////////////////////////////////////////////////////////	
						//Evaluo la siguiente marca	    
						//////////////////////////////////////////////////////////////////////////////////////////////////////////
	      } elseif(strtotime($fetch['datetime'])>strtotime($timeaux) and $ingreso==false) {
		    				//Verifico la segunda marca menor a la hora de ingreso
			    			if($M1menorHi==true and strtotime($hingreso)>strtotime(date("H:i:s", strtotime($fetch['datetime'])))  ){
						     		if($validado==1) {
					     			$end = date("H:i", strtotime($fetch['datetime']));
									if($col<=10) {					     			
									$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
									$col++;	
									}
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
							}elseif($M1menorHi==true and strtotime(date("H:i:s", strtotime($fetch['datetime'])))>strtotime($hingreso) and  strtotime($hsalida)>strtotime(date("H:i:s", strtotime($fetch['datetime'])))) {
				     			//Como la marca 2  está despúes de la hora de ingreso y antes de la hora de salida,
				     			//if($marcain==1) {
						     		//if($validado==1) {
					     			$end = date("H:i", strtotime($fetch['datetime']));
										if($col<=10) {					     			
										$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
										$col++;	
										}
				     			$comentario.="\r\n 33 Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		//}					     			
				     			//}
								//La primer marca es mayor a la hora de salida y la segunda también no se suman 
			    			}elseif(strtotime(date("H:i:s", strtotime($fetch['datetime'])))-strtotime($hsalida)>0 and  $M1mayorHs==true) {
						     		if($validado==1) {
					     			$end = date("H:i", strtotime($fetch['datetime']));
									if($col<=10) {					     			
									$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
									$col++;	
									}
				     			$comentario.="\r\n 35 Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
			    			}	else {
						     		if($validado==1) {
					     			$end = date("H:i", strtotime($fetch['datetime']));
									if($col<=10) {					     			
									$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$end);
									$col++;	
									}
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
			    			}      	
	      	$ingreso=true;	
	      	$validado=0;
	      }

 		}
	   	$timeaux=$fetch['datetime'];
	   	$contador=1;	
    } //fin del while del día
	
$col=11;
$formula = '=((D'.$row.'-C'.$row.')*1440-R'.$row.'-S'.$row.'-T'.$row.'-U'.$row.')/1440*24';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
$col++;
$formula = '=M'.$row.'/24';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
//Extras
$col++;
//Nocturnas
$col++;
//Ex.Noc.
$col++;
//Falta
$col++;
$formula = '=IF(AND(ISBLANK(G'.$row.'), ISBLANK(H'.$row.'),ISBLANK(I'.$row.'),ISBLANK(J'.$row.'),ISBLANK(K'.$row.'),ISBLANK(L'.$row.')),((D'.$row.'<C'.$row.')+D'.$row.'-C'.$row.')*1440,0)';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
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
$formula = '=IF(G'.$row.'>C'.$row.',(G'.$row.'-C'.$row.')*1440,0)';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');

//E.Des
$col++;
$formula = '=IF(AND(H'.$row.'<>"",I'.$row.'<>"",(I'.$row.'-H'.$row.')*1440>E'.$row.'*1440,J'.$row.'<>""),(I'.$row.'-H'.$row.'-E'.$row.')*1440,0)';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');

//Sale Antes
$col++;
$formula = '=IF(AND(ISBLANK(G'.$row.'),ISBLANK(H'.$row.'),ISBLANK(I'.$row.'),ISBLANK(J'.$row.')),0,IF(ISBLANK(J'.$row.'),(IF(ISBLANK(I'.$row.'),(IF(ISBLANK(H'.$row.'),0,IF(D'.$row.'>H'.$row.',(D'.$row.'-H'.$row.')*1440,0))),IF(D'.$row.'>I'.$row.',(D'.$row.'-I'.$row.')*1440,0))),IF(D'.$row.'>J'.$row.',(D'.$row.'-J'.$row.')*1440,0)))';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');


//IF(AND(ISBLANK(G7),ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),0,IF(ISBLANK(J7),(IF(ISBLANK(I7),(IF(ISBLANK(H7),0,IF(D7>H7,(D7-H7)*1440,0))),IF(D7>I7,(D7-I7)*1440,0))),IF(D7>J7,(D7-J7)*1440,0)))
//IF(AND(ISBLANK(G7),ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),0,IF(AND(ISBLANK(H7),ISBLANK(I7),ISBLANK(J7)),IF(D7>G7,(D7-G7)*1440,0),IF(AND(ISBLANK(I7),ISBLANK(J7)),IF(D7>H7,	(D7-H7)*1440,	0),IF(ISBLANK(J7),IF(D7>I7,(D7-I7)*1440;0),IF(D7>J7,(D7-J7)*1440,0),0))))
//Comentarios en la celda
/*
$spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->setAuthor('UYCODEKA');
$commentRichText = $spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->getText()->createTextRun('Horas realizadas:');
$commentRichText->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getComment(columna($col). ($row+1))->getText()->createTextRun($comentario);
*/
//Fin comentarios

/////////////////////////////////

	$row++;
$dateTime->add(new DateInterval('P1D'));
$Timestampfechainicio = $dateTime->getTimestamp();   
 
$dateTimeFin = new DateTime($fechafin);
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');
 
}

	
$col=11;
$formula = '=SUM(M6:M'.($row-1).')';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
$col++;
$formula = '=M'.($row-1).'/24';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('HH:MM');
//Extras
$col++;
//Nocturnas
$col++;
//Ex.Noc.
$col++;
//Falta
$col++;
$formula = '=SUM(R6:R'.($row-1).')';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
//$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');
//Tarde
$col++;
$formula = '=SUM(S6:S'.($row-1).')';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');

//E.Des
$col++;
$formula = '=SUM(T6:T'.($row-1).')';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');

//Sale Antes
$col++;
$formula = '=SUM(U6:U'.($row-1).')';
$spreadsheet->getActiveSheet()->setCellValue(columna($col).$row,$formula);
$spreadsheet->getActiveSheet()->getStyle(columna($col).$row)->getNumberFormat()->setFormatCode('0.00');


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
//header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
//header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->setOffice2003Compatibility(true);
$writer->setPreCalculateFormulas(true);
$writer->save('../tmp/'.$file.'.xlsx');
exit;
