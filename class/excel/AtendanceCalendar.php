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

include ("../conectar.php");
include ("../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

$fechafin=data_first_month_day(date("Y-m-d")). " 23:59:59";
$fechainicio=data_first_month_day(date("Y-m-d")). " 00:00:00";;
$endTime=data_last_month_day($fechainicio). " 23:59:59";;


function columna($diasemana) {
	$col=array(1=>'C',2=>'D', 3=>'E', 4=>'F',5=>'G',6=>'H',7=>'B');
	return $col[$diasemana];
}

$codusuarios=$_GET['codusuarios'];
$file=$_GET['file'];

$reader = IOFactory::createReader('Xls');
$spreadsheet = $reader->load(__DIR__ . '/PhpSpreadsheet-develop/samples/templates/CalendarTemplate.xls');


$spreadsheet->getActiveSheet()->setCellValue('B1' , genMonth_Text(date("m"))." ".date("Y"));
$spreadsheet->getActiveSheet()->setTitle(genMonth_Text(date("m"))." ".date("Y"));
$row=3;

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


$sql="SELECT * FROM horariousuario WHERE  diasemana LIKE '%".$diasemana."%'  AND vigencia<='".date("Y-m-d", strtotime($inicio))."' 
AND  codusuarios='".$codusuarios."'  ORDER BY vigencia DESC limit 0,1  ";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

    	$hingreso='';    $hsalida='';    $descanso='';
	   $hingreso=mysqli_result($query, 0, "horaingreso");
		$hsalida=mysqli_result($query, 0, "horasalida");
		//$descanso=mysqli_result($query, 0, "descanso");
/*fin*/    
 
    
	$ql1= "SELECT * FROM biometriclog where codusuarios='".$codusuarios."' AND datetime >= '".$fechainicio."' AND datetime < '".$fechafin."' 
	and  borrado=0 ORDER BY datetime ASC";
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
    $enddatetimeaux="0000-00-00 00:00:00";
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
			    		$horaIngreso=date("H:i:s", strtotime($fetch['datetime']));
					  $start = date("H:i:s", strtotime($fetch['datetime']));
						$validado=$fetch['validado'];
						$usuariocod=$fetch['usuariocod'];
			    			if(strtotime($fetch['datetime'])<strtotime($hingreso)) {
								$M1menorHi=true;
								//La marca es mayoa a la hora de ingreso y menor a la hora de salida
			    			}elseif(strtotime($fetch['datetime'])>strtotime($hingreso) and strtotime($fetch['datetime'])<strtotime($hsalida)) {
								$M1mayorHi=true;
			    			}elseif(strtotime($fetch['datetime'])>strtotime($hsalida)) {
			    				$M1mayorHs=true;
			    			}
					$ingreso=false;	
					$enddatetimeaux=$fetch['enddatetime'];		    		
				   }
				   
						//*Si dentro de la misma marca tengo la enddatetime
				     	if( $fetch['enddatetime']!='0000-00-00 00:00:00' and $fetch['enddatetime']!='NULL' )  {
				     $horaSalida=date("H:i:s", strtotime($fetch['enddatetime']));	  
				     $enddatetimeaux=$fetch['enddatetime'];  	
				     $ingreso=true;	
					  		//$e['title'] = "Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
				     		//*A efectos de mostrar un background de diferente color
				     		if( strtotime($fetch['enddatetime'])<strtotime($hingreso)) {
					     		if($fetch['validado']==1) {
				     			$end = date("H:i:s", strtotime($fetch['enddatetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;
					     		}				     			
				     		}elseif(strtotime($fetch['enddatetime'])==strtotime($hingreso)) {
					     		if($fetch['validado']==1) {
				     			$end =date("H:i:s", strtotime($fetch['enddatetime']));
				     			$restahoras=RestaHoras($end,$start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n 30 Ing: ". $start." salida ".$end;				     			
					     		}				     			
				     			$ingreso=false;	
				     			$marcain=0;
				     		}elseif(strtotime($fetch['enddatetime'])>strtotime($hingreso) and strtotime($fetch['enddatetime'])<strtotime($hsalida)) {
				     			//Como la marca enddatetime esté despues de la hora de ingreso y antes de la hora de salida, marco el ingreso como temprano y
				     			//la salida con rojo, 
					     		if($fetch['validado']==1) {
				     			$end = date("H:i:s", strtotime($fetch['enddatetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;				     			
					     		}					     			
				     			//Marco las horas trabajadas dentro del horario de trabajo
								$start = date("H:i:s", strtotime($hingreso));
								$end = date("H:i:s", strtotime($fetch['enddatetime']));
								//Marco que se retiro temprano
					     		if($fetch['validado']==1) {
								$start = date("H:i:s", strtotime($fetch['enddatetime']));
				     			$end =date("H:i:s", strtotime($hsalida));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;				     			
					     		}					     			

				     		}elseif(strtotime($fetch['enddatetime'])>strtotime($hsalida)) {
						     		if($fetch['validado']==1) {
								  		$end = date("H:i:s", strtotime($hsalida));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;								  		
						     		}					     			
									//Marco que se retiro tarde
						     		if($fetch['validado']==1) {
									$start = date("H:i:s", strtotime($hsalida));
									$end = date("H:i:s", strtotime($fetch['enddatetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;									
						     		}					     			
				     		}
				     		
				     		//Como el registro contiene la mara 1 y la marca 2, proceso el próximo registro como si fuera el de ingreso
							
							$timeaux=$fetch['enddatetime'];
						}		
							
						//Evaluo la segunda marca	    
	      } elseif(strtotime($fetch['datetime'])>strtotime($timeaux) and $ingreso==false) {
		    				$horaSalida=date("H:i:s", strtotime($fetch['datetime']));
		    				//Verifico la segunda marca menor a la hora de ingreso
			    			if($M1menorHi==true and strtotime($hingreso)>strtotime($fetch['datetime'])  ){
						     		if($validado==1) {
					     			$end = date("H:i:s", strtotime($fetch['datetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
							}elseif($M1menorHi==true and strtotime($fetch['datetime'])>strtotime($hingreso) and  strtotime($hsalida)>strtotime($fetch['datetime'])) {
				     			//Como la marca 2  esté despues de la hora de ingreso y antes de la hora de salida, marco el ingreso como temprano y
				     			//la salida con rojo, no lo sumo a las horas del día salvo si está validado
				     			if($marcain==1) {
						     		if($validado==1) {
					     			$end = date("H:i:s", strtotime($hingreso));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n 33 Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
				     			}
				     			//Marco las horas trabajadas dentro del horario de trabajo
								$start = date("H:i:s", strtotime($hingreso));
								$end = date("H:i:s", strtotime($fetch['datetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n 34 Ing: ".$start ." salida ".$end;									
								//La primer marca es mayor a la hora de salida y la segunda también no se suman 
			    			}elseif(strtotime($fetch['datetime'])-strtotime($hsalida)>0 and  $M1mayorHs==true) {
						     		if($validado==1) {
					     			$end = date("H:i:s", strtotime($fetch['datetime']));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n 35 Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
			    			}	else {
						     		if($validado==1) {
					     			$end = date("H:i:s", strtotime($hingreso));
				     			$restahoras=RestaHoras($end, $start);
				     			$horastotales=SumaHoras($horastotales,$restahoras);
				     			$comentario.="\r\n Ing: ".$horaIngreso." salida ".$horaSalida;					     			
						     		}					     			
			    			}      	
	      	$ingreso=true;	
	      }

 		}
	   	$timeaux=$fetch['datetime'];
	   	$contador=1;
    } //fin del while del día
 $spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle($col. ($row+1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$spreadsheet->getActiveSheet()->setCellValue($col. ($row+1),$horastotales);
//Comentarios en la celda
$spreadsheet->getActiveSheet()->getComment($col. ($row+1))->setAuthor('UYCODEKA');
$commentRichText = $spreadsheet->getActiveSheet()->getComment($col. ($row+1))->getText()->createTextRun('Horas realizadas:');
$commentRichText->getFont()->setBold(true);
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
