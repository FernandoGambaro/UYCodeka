<?php
ini_set('memory_limit', '28M');

require_once __DIR__ .'/../common/funcionesvarias.php';
require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/verificopermisos.php';   


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }
  
  $UserID=$s->data['UserID'];

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

include ("../../funciones/fechas.php");

date_default_timezone_set('America/Montevideo');
//--------------------------------------------------------------------------------------------------
// Este script extrae las marcas almacenadas en la tabla de biometriclog, mostrando para el usuario seleccionado 
// Las marcas que realizo
//
// Trabaja las fechas principalmente en formato timestamp.
//
// Requiere PHP 5.2.0 en adelante.
//--------------------------------------------------------------------------------------------------

$start=$_POST['start'] ? $_POST['start'] : $_GET['start'] ;
$end=$_POST['end'] ? $_POST['end'] :  $_GET['end'] ;
$codusuarios=$_POST['codusuarios'] ? $_POST['codusuarios'] : 1 ;

if (!isset($start) || !isset($end)) {
  die("Error, falta rango de fechas.");
}

// Parse the timezone parameter if it is present.
$timezone = null;
if (isset($_POST['timezone'])) {
  $timezone = new DateTimeZone($_POST['timezone']);
}
//Funcion para obtener el nombre y apellido de un usuario de la tabla usuarios usando su código
function UserID($codusuarios) {
	$obj = new Consultas('usuarios');
	$obj->Select();	
	$obj->Where('codusuarios', $codusuarios, '=');
	$paciente = $obj->Ejecutar();
	$row=$paciente['datos'][0];
	$nombre=$row["nombre"].' '.$row["apellido"];
	return $nombre;
}

function control($startDate, $endDate,$codusuarios,$UserID) {

	$obj = new Consultas('biometriclog');
	$obj->Select();	
	$obj->Where('datetime', $startDate, '=');
	$obj->Where('enddatetime', $endDate, '=');
	$obj->Where('codusuarios', $codusuarios, '=');
	$obj->Orden('datetime', 'DESC');
	$paciente = $obj->Ejecutar();
	$row=$paciente['datos'][0];
	$filas=$row["id"];


	if($filas==0){	

		$nombres = array();
		$valores = array();

		$nombres[] = 'codusuarios';
		$valores[] = $codusuarios;
		$nombres[] = 'status';
		$valores[] = '1';
		$nombres[] = 'datetime';
		$valores[] = $startDate;
		$nombres[] = 'enddatetime';
		$valores[] = $endDate;
		$nombres[] = 'descripcion';
		$valores[] = '';
		$nombres[] = 'validado';
		$valores[] = '2';
		$nombres[] = 'usuariocod';
		$valores[] = $UserID;

		$obj = new Consultas('usuarios');
		
		$obj->Insert($nombres, $valores);
		//var_dump($obj);
		$paciente = $obj->Ejecutar();
		$id = $paciente['id'];

	}else {
	$id=$filas;
	}
	return $id;
}

function update($codlog,$endDate,$validado=2) {

	$obj = new Consultas('biometriclog');
	$obj->Select();	
	$obj->Where('codlog', $codlog, '=');
	$paciente = $obj->Ejecutar();
	$horaaux=$paciente['datos'][0]['datetime'];

	$Time = new DateTime($horaaux);
	$TSHi=$Time->getTimestamp();
	$Time = new DateTime($endDate);
	$TSHs=$Time->getTimestamp();	
	
	if($TSHi>$TSHs) {

		$nombres = array();
		$valores = array();
		$nombres[] = 'datetime';
		$valores[] = $endDate;
		$nombres[] = 'enddatetime';
		$valores[] = $horaaux;
		$nombres[] = 'validado';
		$valores[] = $validado;
		$obj = new Consultas('biometriclog');
		$obj->Update($nombres,$valores);	
		$obj->Where('codlog', $codlog, '=');
		$paciente = $obj->Ejecutar();
			
	}	else {
		$nombres = array();
		$valores = array();
		$nombres[] = 'enddatetime';
		$valores[] = $endDate;
		$nombres[] = 'validado';
		$valores[] = $validado;
		$obj = new Consultas('biometriclog');
		$obj->Update($nombres,$valores);	
		$obj->Where('codlog', $codlog, '=');
		$paciente = $obj->Ejecutar();
	}
}

// Paso las fechas a timestamp
$dateTime = new DateTime($start);
$fechainicio= $dateTime->format('Y-m-d H:i:s');
$Timestampfechainicio=$dateTime->getTimestamp();

$dateTimeFin = new DateTime($start);
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');

$dateTime = new DateTime($end);
$endTime=$dateTime->getTimestamp();

$events = array();
$AutoId=10000000000000000; //Un Id para los campos que no tienen registro en la base de datos en caso de querer guardar dicho registro le asigno un nuevo id

while($Timestampfechainicio<$endTime) {

$marcain=1;
$noregistro=1; // Digo que no hay registro para este día, si encuentro alguno cambio a 0    
/*
Obtengo el horario que debería cumplir el usuario y lo almaveno en un array multidimencional 
*/

$dateTime = new DateTime();
$dateTime->setTimestamp($Timestampfechainicio);
$fechainicio=$dateTime->format('Y-m-d H:i:s');

$diasemana=$dateTime->format('N');

if($diasemana==0) {
	$diasemana=7;
}$inicio=$dateTime->format('Y-m-d');

///Para el día busco el horario que debe cumplir
$obj = new Consultas('horariousuario');
$obj->Select();	
$obj->Where('diasemana', $diasemana, 'LIKE');
$obj->Where('vigencia', $fechainicio, '<=');
$obj->Where('codusuarios', $codusuarios, '=');
$obj->Orden('vigencia', 'DESC');
$paciente = $obj->Ejecutar();
$row=$paciente['datos'][0];

    	$HorarioTrabajo=0;
    	$hingreso='';    $hsalida='';    $descanso='';
    	if( $paciente['numfilas']>0){
    	$HorarioTrabajo=1;    	
    	}
	   $hingreso=date("Y-m-d", strtotime($inicio)).' '.$row["horaingreso"];
		$hsalida=date("Y-m-d", strtotime($inicio)).' '.$row["horasalida"];
		
$Time = new DateTime($hingreso);
$TSHingreso=$Time->getTimestamp();
$Time = new DateTime($hsalida);
$TSHsalida=$Time->getTimestamp();
		
//fin    
 
//Recorro todos las marcas del día
$obj = new Consultas('biometriclog');
$obj->Select();	
$obj->Where('datetime', $fechainicio, '>=');
$obj->Where('datetime', $fechafin, '<=');
$obj->Where('codusuarios', $codusuarios, '=');
$obj->Where('borrado', '0', '=');
$obj->Orden('datetime', 'ASC');
$paciente = $obj->Ejecutar();
$rows=$paciente['datos'];

	$ql1= "SELECT * FROM biometriclog where codusuarios='".$codusuarios."' AND `datetime` BETWEEN '".$fechainicio."' AND '".$fechafin."' AND borrado=0
	ORDER BY datetime ASC";
	$query1= mysqli_query($GLOBALS["___mysqli_ston"], $ql1);
	
    $FechaInicioAnterior=$fechainicio; //formato fecha
    //echo $FechaInicioAnterior;
   // echo "<br>";
    $horaIngreso='';
    $ingreso=1;
    $contador=0;
    $M1menorHi=0;
    $M1mayorHi=0;
	$M1mayorHs=0;
    $considero=0;
    $pasada=0;
    $SiguienteMarca=0;

$dateTimeAux = new DateTime('0000-00-00 00:00:00');
$TSEnddatetimeAux=$Timestamp0=$EndTimeaux=$dateTimeAux->getTimestamp();	 				  
    
    
	   foreach($rows as $fetch) {

			$dateTimeAux = new DateTime($FechaInicioAnterior);
//echo "<br><br> Date Aux ".$dateTimeAux->format('Y-m-d H:i:s');
			$TSFechaInicioAnterior=$dateTimeAux->getTimestamp();
			$dateTimeAux = new DateTime($fetch['datetime']);
			$TSMarca=$dateTimeAux->getTimestamp();
//echo "<br> datetime ".$dateTimeAux->format('Y-m-d H:i:s');			
			$dateTimeAux = new DateTime($fetch['enddatetime']);
			$TSEnddatetime=$dateTimeAux->getTimestamp();

//echo "<br>".($TSMarca-($TSFechaInicioAnterior+60))." > 60 ";
 				     			
	   	//No considero las marcas seguidas a menos de 60 segundos
	   	if(($TSFechaInicioAnterior+60) < $TSMarca) {
	   		$considero=1;
	   	}else {
    			//$sql_del="UPDATE `biometriclog` SET  borrado = '1'	WHERE  `biometriclog`.`codlog` = '".$fetch['codlog']."'";
//echo "<br>";    			
    			//mysqli_query($GLOBALS["___mysqli_ston"], $sql_del);	   		
	   		$considero=0;
	   	}
	   	if($considero==1) {
	   		//Si se trata de la primer marca $ingreso= 1
  		
		    	if( $ingreso==1 ) {
		    		//Si la hora datetime de este registro es mayor o igual al enddatetime del registro anterior mas 60 segundos, macro como borrado esta marca
			    	if(($TSMarca-$TSEnddatetimeAux)<=60 and $Timestamp0!=$EndTimeaux and $fetch['enddatetime']!='NULL' ) {
			    			$sql_del="UPDATE `biometriclog` SET  borrado = '1'	WHERE  `biometriclog`.`codlog` = '".$fetch['codlog']."'";
			    			mysqli_query($GLOBALS["___mysqli_ston"], $sql_del);
			    	}  else {
							$horaIngreso=date("H:i:s", strtotime($fetch['datetime']));
							//Ingresa temprano o en hora.
							if($TSMarca<$TSHingreso) {
//							echo "<br> Fetch datetime TSM<=TSHi ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$M1menorHi=1;
								$e = array();
								$e['id'] =$fetch['codlog'];   								
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];							  	    				
								$validado=$fetch['validado'];
								$usuariocod=$fetch['usuariocod'];
								$marcain=1;
								//La marca es mayor a la hora de ingreso y menor a la hora de salida
			    			}elseif($TSMarca=$TSHingreso) {
//							echo "<br> Fetch datetime TSM<=TSHi ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$e = array();
								$e['id'] =$fetch['codlog'];   								
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];							  	    				
								//La marca es mayor a la hora de ingreso y menor a la hora de salida
			    			}elseif($TSMarca>$TSHingreso and $TSMarca<=$TSHsalida) {
								$e = array();
								$horaIngreso=date("H:i:s", strtotime($hingreso));
								$horaSalida=date("H:i:s", strtotime($fetch['datetime']));
								
								$e['view']=$e['id'] =control($hingreso, $fetch['datetime'],$codusuarios,$UserID); //$AutoId++;   								
								$e['start'] = $hingreso;
								$e['title'] = $SiguienteMarca."->". ($pasada++)."-10 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	     			
								$e['end'] = $fetch['datetime'];
								array_push($events, $e);								    				
								$M1mayorHi=1;
								$e = array();
								$e['id'] =$fetch['codlog'];   								
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];		
							  		$e['title'] = $SiguienteMarca."->". ($pasada++)."-11 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;													  	    				
								//$validado=$fetch['validado'];
								//$usuariocod=$fetch['usuariocod'];
								$marcain=0;
			    			}elseif($TSMarca>$TSHsalida) {
						    		$e = array();
							     $e['id'] =$fetch['codlog'];   								
								  $e['start'] = $fetch['datetime'];
								  $e['descripcion']=$fetch['descripcion'];	
								$e['title'] =$SiguienteMarca."->". ($pasada++)."-12 Ing.: ".$fetch['datetime'].", sal.: ".$hsalida;								  						  	    				
									//$validado=$fetch['validado'];
									//$usuariocod=$fetch['usuariocod'];			    				
			    				$M1mayorHs=1;
			    			}
			    			/*elseif($hingreso=='' and $hsalida) {
						    	$e = array();
							   $e['id'] =$fetch['codlog'];   								
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];	
			    			}
			    			*/
					$ingreso=0;	
$dateTimeAux = new DateTime($fetch['enddatetime']);
$TSEnddatetimeAux=$dateTimeAux->getTimestamp();
				   }
				   	///////////////////////////////////////////////////////////////////
						//*Si dentro de la misma marca tengo la enddatetime
						//////////////////////////////////////////////////////////////////
				     	if( $TSEnddatetime!=$EndTimeaux and $fetch['enddatetime']!=NULL)  {
						$dateTimeAux = new DateTime($fetch['enddatetime']);
						$TSEnddatetimeAux=$dateTimeAux->getTimestamp();
						$horaSalida=$dateTimeAux->format('H:i:s');
				     $ingreso=1;
					  		$e['title'] = $SiguienteMarca."->". ($pasada++)."-13 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
				     		if( $TSEnddatetime<$TSHingreso){
				     			$e['title'] = $SiguienteMarca."->". ($pasada++)."-14 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'tarde';
					     		$e['suma'] = 'n';
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] =$SiguienteMarca."->". ($pasada++)."-14.1 Validado por: ". UserID($fetch['usuariocod']) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
							  		$e['suma'] = 's';
					     		}				     			
				     			$e['end'] = $fetch['enddatetime'];
				     			array_push($events, $e);
				     			$M1menorHi=0;
				     		}elseif($TSEnddatetime==$TSHingreso) {
				     			$e['title'] = $SiguienteMarca."->". ($pasada++)."-15 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'tarde';
								$e['suma'] = 'n';
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] = $SiguienteMarca."->". ($pasada++)."-15.1 Validado por: ". UserID($fetch['usuariocod']) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
							  		$e['suma'] = 's';
					     		}				     			
				     			$e['end'] = $fetch['enddatetime'];
				     			array_push($events, $e);
				     			$ingreso=0;	
				     			$marcain=0;
				     		}elseif($TSEnddatetime>$TSHingreso and $TSEnddatetime<$TSHsalida) {
				     			//Como la marca enddatetime esté despues de la hora de ingreso y antes de la hora de salida, marco el ingreso como temprano y
				     			//la salida con rojo, 
				     			$e['title'] =$SiguienteMarca."->". ($pasada++)."-16 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'temprano';
					     		$e['suma'] = 'n';
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] = $SiguienteMarca."->". ($pasada++)."-16.1 Validado por: ". UserID($fetch['usuariocod']) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
							  		$e['suma'] = 's';
					     		}					     			
				     			$e['end'] = $hingreso;
				     			array_push($events, $e);
				     			/*	
				     			//Marco las horas trabajadas dentro del horario de trabajo
				     			$e['view']=$e['id'] =control($hingreso, $fetch['enddatetime'],$codusuarios);//$AutoId++;  
								$e['start'] = $hingreso;
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'custom';
								$e['title'] = $SiguienteMarca."->". ($pasada++)."-17 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
								$e['end'] = $fetch['enddatetime'];
								$e['suma'] = 's';
								array_push($events, $e);	
								//Marco que se retiro temprano
								$e['view']=$e['id'] =control($fetch['enddatetime'], $hsalida,$codusuarios);//$AutoId++;  
								$e['start'] = $fetch['enddatetime'];
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'tarde';
								$e['title'] =$SiguienteMarca."->". ($pasada++)."-18 Se retiró antes de hora";
								$e['end'] = $hsalida;
								array_push($events, $e);
				     				*/
				     		}elseif($TSEnddatetime==$TSHsalida) {
					     			$e['title'] =$SiguienteMarca."->". ($pasada++)."-19 Ing.: ".$fetch['enddatetime']." -2- ".$hsalida ." - - ".$horaIngreso.", sal.: ".$horaSalida;
						     		$e['className']= 'custom';
					     			$e['end'] = $fetch['enddatetime'];
					     			array_push($events, $e);	
					     			update($e['id'] ,$e['end']);
					     			//$noregistro=0;		     			
				     		}elseif($TSEnddatetime>$TSHsalida) {
					     			$e['title'] = $SiguienteMarca."->". ($pasada++)."-20 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
						     		$e['className']= 'custom';
						     		if($fetch['validado']==1) {
								  		$e['title'] =$SiguienteMarca."->". ($pasada++)."-20.1 Validado por: ". UserID($fetch['usuariocod']) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
						     		}					     			
					     			$e['end'] = $fetch['enddatetime'];
					     			array_push($events, $e);
					     			$noregistro=1;
									//Marco que se retiro tarde
									/*
									$e['start'] = $hsalida;
									$e['descripcion']=$fetch['descripcion'];
									$e['className']= 'temprano';
									$e['title'] = "26 Salió tarde";
						     		if($fetch['validado']==1) {
								  		$e['title'] = "26.1 Validado por: ". UserID($fetch['usuariocod']) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
						     		}					     			
									$e['end'] = $fetch['enddatetime'];
									array_push($events, $e);
									*/			     			
				     		}
				     		
				     		//Como el registro contiene la mara 1 y la marca 2, proceso el próximo registro como si fuera el de ingreso
							
							$FechaInicioAnterior=$fetch['enddatetime'];
						}		
						//////////////////////////////////////////////////	
						///Evaluo la segunda marca
						//////////////////////////////////////////////////	    
	      } elseif($TSMarca>$TSFechaInicioAnterior and $ingreso==0) {
	      	$ingreso=1;
		    				$horaSalida=date("H:i:s", strtotime($fetch['datetime']));
//		    				echo "<br>TSM > TSFIA".date("H:i:s", strtotime($fetch['datetime']))."<br>";
//		    				echo "M1 ".$M1menorHi."<br>";
//		    				echo "Marca H ini ".($TSMarca-$TSHingreso)."<br>";
//		    				echo "H sal Marca ".($TSHsalida-$TSMarca)."<br>";
		    				//Verifico la segunda marca menor a la hora de ingreso
			    			if($M1menorHi==1 and $TSMarca<$TSHingreso ){
			    				//Llego temprano y se retiro antes de la hora de ingreso
					     			$e['title'] = $SiguienteMarca."->". ($pasada++)."-22 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
						     		$e['className']= 'tarde';
					     			$e['suma'] = 'n';
						     		if($fetch['validado']==1) {
						     			$e['className']= 'custom';
								  		$e['title'] =  $SiguienteMarca."->". ($pasada++)."-22.1 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
										$e['suma'] = 's';
						     		}					     			
					     			$e['end'] = $fetch['datetime'];
					     			array_push($events, $e);
									update($e['id'] ,$e['end']);
									$noregistro=0;
							}elseif($M1menorHi==1 and $TSMarca>$TSHingreso and  $TSHsalida>$TSMarca) {
				     			//Como la marca 2  está despúes de la hora de ingreso y antes de la hora de salida, marco el ingreso como temprano y
				     			//la salida con rojo, no lo sumo a las horas del día salvo si está validado
				     			
				     			$e['title'] = $SiguienteMarca."->". ($pasada++)." 23 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'temprano';
				     			$e['suma'] = 'n';
						     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] =$SiguienteMarca."->". ($pasada++)." 23.1 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
									$e['suma'] = 's';
					     		}					     			
				     			$e['end'] = $hingreso;
				     			//Éste id es de la marca anterior
				     			update($e['id'] ,$e['end']);
				     			array_push($events, $e);	

				     			//Marco las horas trabajadas dentro del horario de trabajo, siendo que ingreso antes de la hora de ingreso
				     			//Inserto un nuevo registro dentro de la base
								$e['id'] =control($hingreso, $fetch['datetime'],$codusuarios, $UserID);//$AutoId++;   								
								$e['start'] = $hingreso;
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'custom';
								$e['title'] =$SiguienteMarca."->". ($pasada++)." -24 Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
								$e['end'] = $fetch['datetime'];
								$e['suma'] = 's';
								array_push($events, $e);	
								
								//Marco que se retiro temprano
								//Actualizo el registro actual.
								$e['id'] =$fetch['codlog'];//$AutoId++;
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'tarde';
								$e['title'] =$SiguienteMarca."->". ($pasada++)."24.1 Se retiró antes de hora";
								$e['end'] = $hsalida;
								$e['suma'] = 'n';
								update($e['id'] ,$e['end']);
								array_push($events, $e);
								$noregistro=0;
								//La primer marca es menor a la hora de ingreso y la segunda es mayor a la hora de salida
							}elseif($M1menorHi==1 and $TSMarca>$TSHsalida) {
				     			$e['title'] = $SiguienteMarca."->". ($pasada++)." 25  Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'temprano';
				     			$e['suma'] = 'n';
						     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] =$SiguienteMarca."->". ($pasada++)." 25.1 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
									$e['suma'] = 's';
					     		}					     			
				     			$e['end'] = $hingreso;
				     			update($e['id'] ,$e['end']);
				     			array_push($events, $e);
				     			//Marco las horas trabajadas dentro del horario de trabajo, siendo que ingreso antes de la hora de ingreso
								$e['view']=$e['id'] =control($hingreso, $hsalida,$codusuarios,$UserID);//$AutoId++;   								
								$e['start'] = $hingreso;
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'custom';
								$e['title'] = $SiguienteMarca."->". ($pasada++)." 26 Ing.:".$M1menorHi."==1 and ".$TSMarca.">".$TSHsalida."-- ".$horaIngreso.", sal.: ".$horaSalida;
								$e['end'] = $hsalida;
								$e['suma'] = 's';
								array_push($events, $e);	
								//Marco que se retiro temprano
								$e['view']=$e['id'] =control($hsalida, $fetch['datetime'],$codusuarios,$UserID);//$AutoId++;
								$e['start'] = $hsalida;
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'tarde';
								$e['title'] =$SiguienteMarca."->". ($pasada++)." 27 Se retira despues de hora, aún no validado";
								$e['suma'] = 'n';								
						     	if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] =$SiguienteMarca."->". ($pasada++)." 27.1 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
									$e['suma'] = 's';
					     		}									
								$e['end'] = $fetch['datetime'];
								array_push($events, $e);
								$noregistro=0;
								
								//La primer marca es mayor a la hora de entrada y la segunda es menor a la hora de salida		
							}elseif($M1mayorHi==1 and $TSMarca<$TSHsalida) {
								$e['className']= 'custom';
								$e['title'] =$SiguienteMarca."->". ($pasada++)." 28  horas trabajadas";
								$e['end'] = $fetch['datetime'];
								$e['suma'] = 'n';
								update($e['id'] ,$e['end']);
								array_push($events, $e);
								
								$e['view']=$e['id'] =control($fetch['datetime'], $hsalida,$codusuarios,$UserID);//$AutoId++;
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'tarde';
								$e['title'] =$SiguienteMarca."->". ($pasada++)."29 Se retiró antes de hora";
								$e['end'] = $hsalida;
								$e['suma'] = 'n';
								array_push($events, $e);
								$noregistro=0;
								//La primer marca es mayor a la hora de entrada y la segunda es mayor a la hora de salida
							}elseif($M1mayorHi==1 and $TSMarca>$TSHsalida  ) {
								$e['title'] =$SiguienteMarca."->". ($pasada++)." 30 horas trabajadas";
								$e['end'] = $hsalida;
								$e['suma'] = 'n';
								update($e['id'] ,$e['end']);
								array_push($events, $e);
								$e['view']=$e['id'] =control($hsalida, $fetch['datetime'],$codusuarios,$UserID);//$AutoId++;
								$e['start'] = $hsalida;
								$e['descripcion']=$fetch['descripcion'];
								$e['className']= 'tarde';
								$e['title'] =$SiguienteMarca."->". ($pasada++)."31 Se retiró tarde";
								$e['end'] = $fetch['datetime'];
								$e['suma'] = 'n';
								array_push($events, $e);
								$noregistro=0;
								
								//La primer marca es mayor a la hora de salida y la segunda también no se suman 
			    			}elseif($TSMarca>$TSHsalida and  $M1mayorHs==1) {
				     			$e['title'] =$SiguienteMarca."->". ($pasada++)." -<31-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
					     		$e['className']= 'fueradehora';
				     			$e['suma'] = 'n';
						     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] = $SiguienteMarca."->". ($pasada++)." -<32.2 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
				     				$e['suma'] = 's';
					     		}					     			
				     			$e['end'] = $fetch['datetime'];
			    				update($e['id'] ,$e['end']);
				     			array_push($events, $e);				    				

			    			}  elseif($TSHsalida==$TSMarca) {
					     		$e['className']= 'custom';
				     			$e['suma'] = 's';
						  		$e['title'] = $SiguienteMarca."->". ($pasada++)." -<33.2 Validado por: ". UserID($usuariocod) ."-> Ing.: ".$horaIngreso.", sal.: ".$horaSalida;
				     			update($e['id'] ,$e['end']);
				     			$e['end'] = $fetch['datetime'];
			    			}   	

	      	$ingreso=1;	
	      	$M1mayorHi=$M1mayorHs=$M1menorHi=0;
	      }
	      		
 		} //Fin del considero

	   	/*Para el primer registro del día*/
	   	$FechaInicioAnterior=$fetch['datetime'];

		$SiguienteMarca++;
    } //fin del while del día

    if($ingreso==0) {
		     $e['title'] = $SiguienteMarca."->". ($pasada++)." 34 Ing.: ".date("H:i:s", strtotime($FechaInicioAnterior)).", No marco salida o marco salida a 10 minutos de ingresar ";
		     $e['end'] = date("Y-m-d H:i:s", strtotime($FechaInicioAnterior)+30*60);
		     $e['suma'] = 'n';
		     if($marcain==1) {
		     $e['className']= 'fueradehora';
		     }else {
		     $e['className']= 'tarde';
		     }
		     array_push($events, $e);   	
    }elseif($noregistro==1 and $HorarioTrabajo==1 ) {
				$e = array();				
				$e['view']=$e['id'] =control($hingreso, $hsalida,$codusuarios,$UserID);//$AutoId++;    	
			  $e['start'] = $hingreso;
			  $e['descripcion']='Ninguna';
			  $e['className']= 'tarde';
		    $e['title'] =$SiguienteMarca."->". ($pasada++).' 35  No hay registro/No cumplío horario - No se suman a las horas realizadas';
		    $e['suma'] = 'n';
		    $e['end'] = $hsalida;
		    array_push($events, $e);	

    }  
    
//echo "va <br>".$dateTime->format('Y-m-d');   
$dateTime->add(new DateInterval('P1D'));
//echo "<br>va2 <br>".$dateTime->format('Y-m-d H:i:s');
$Timestampfechainicio = $dateTime->getTimestamp();   

 
$dateTimeFin = new DateTime($fechafin);
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');
    
 } //Fin de recorrer las fechas
    //var_dump($events);
    echo  json_encode($events);
