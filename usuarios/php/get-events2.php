<?php
ini_set('memory_limit', '28M');

require_once __DIR__ .'/../../common/funcionesvarias.php';
require_once __DIR__ .'/../../classes/class_session.php';


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
// Este script extrea las marcas almacenadas en la tabla de biometriclog, mostrando para el usuario seleccionado 
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
	if(strlen($codusuarios)>0){
		$obj = new Consultas('usuarios');
		$obj->Select();	
		$obj->Where('codusuarios', $codusuarios, '=');
		$paciente = $obj->Ejecutar();
		$row=$paciente['datos'][0];
		$nombre=$row["nombre"].' '.$row["apellido"];
	} else{
		$nombre='Sin nombre';
	}
	return $nombre;
}

function agrego($datetime, $enddatetime,$codusuarios, $UserID, $codbiometric ) {

	$obj = new Consultas('biometriclog');
	$obj->Select();	
	$obj->Where('datetime', $datetime, '=');
	$obj->Where('enddatetime', $enddatetime, '=');
	$obj->Where('codusuarios', $codusuarios, '=');
	$obj->Orden('datetime', 'DESC');
	$paciente = $obj->Ejecutar();
	$row=$paciente['datos'][0];
	//$filas=$paciente["id"];


	if($paciente['numfilas']==0){
	
		$nombres = array();
		$valores = array();
		$nombres[] = 'datetime';
		$valores[] = $datetime;
		$nombres[] = 'codbiometric';
		$valores[] = $codbiometric;
		$nombres[] = 'enddatetime';
		$valores[] = $enddatetime;
		$nombres[] = 'codusuarios';
		$valores[] = $codusuarios;
		$nombres[] = 'state';
		$valores[] = '1';
		$nombres[] = 'validado';
		$valores[] = '2';
		$nombres[] = 'usuariocod';
		$valores[] = $UserID;
		$obj = new Consultas('biometriclog');
		$obj->Insert($nombres,$valores);	
		$paciente = $obj->Ejecutar();
				
	   $id=$paciente["id"];	
	}else {
	$id=$row["id"];	
	}
	//echo "<br>".$sql2;
	return $id;
}

function updatedata($codlog, $datetime, $enddatetime, $validado, $color, $borrado){
//echo $color;
$nombres = array();
$valores = array();

	if($datetime!='' and $enddatetime!='') {
		$nombres[] = 'datetime';
		$valores[] = $datetime;
		$nombres[] = 'validado';
		$valores[] = $validado;		
		$nombres[] = 'enddatetime';
		$valores[] = $enddatetime;
		$nombres[] = 'validado';
		$valores[] = '2';

//		$sql2="UPDATE `biometriclog` SET  `datetime`= '".$datetime."', `enddatetime` = '".$enddatetime."' ,`validado`='".$state."'
//		 WHERE `biometriclog`.`codlog` = '".$codlog."';";
	}else {
		//$sql2="UPDATE `biometriclog` SET  `borrado`='".$borrado."' WHERE `biometriclog`.`codlog` = '".$codlog."';";
		$nombres[] = 'borrado';
		$valores[] = $borrado;
	}
		   
	$obj = new Consultas('biometriclog');
	//$obj->Select($sql2, 'sql');
	$obj->Update($nombres, $valores);
	$obj->Where('codlog', $codlog, '=');
	$obj->Ejecutar();
   
}

$estado='';
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
Obtengo el horario que debería cumplir el usuario y lo almaceno en un array multidimencional 
*/

$dateTime = new DateTime();
$dateTime->setTimestamp($Timestampfechainicio);
$fechainicio=$dateTime->format('Y-m-d H:i:s');

$diasemana=$dateTime->format('N');

if($diasemana==0) {
	$diasemana=7;
}$inicio=$dateTime->format('Y-m-d');

///Para el día busco el horario que debe cumplir

//$sql="SELECT * FROM horariousuario WHERE  diasemana LIKE '%".$diasemana."%'  AND vigencia<='".$fechainicio."' AND  codusuarios='".$codusuarios."'
//  ORDER BY vigencia DESC limit 0,1  ";

$objhora = new Consultas('horariousuario');
$objhora->Select();
$objhora->Where('diasemana', $diasemana, 'LIKE');
$objhora->Where('vigencia', $fechainicio, '<=');
$objhora->Where('codusuarios', $codusuarios, '=');
$objhora->Orden('vigencia', 'DESC');
$paciente=$objhora->Ejecutar();

    	$HorarioTrabajo=0;
    	$hingreso='';    $hsalida='';    $descanso='';
    	if( $paciente['numfila']>0){
    	$HorarioTrabajo=1;    	
    	}
	    $hingreso=date("Y-m-d", strtotime($inicio)).' '.$paciente['datos'][0]["horaingreso"];
		$hsalida=date("Y-m-d", strtotime($inicio)).' '.$paciente['datos'][0]["horasalida"];
		
$Time = new DateTime($hingreso);
$TSHingreso=$Time->getTimestamp();
$horaIngresoTS=$Time->format('Y-m-d H:i:s');
$Time = new DateTime($hsalida);
$horaSalidaTS=$Time->format('Y-m-d H:i:s');
$TSHsalida=$Time->getTimestamp();
		
//fin    
 
//Recorro todos las marcas del día    
	//$ql1= "SELECT * FROM biometriclog where codusuarios='".$codusuarios."' AND  `datetime` BETWEEN '".$fechainicio."' AND '".$fechafin."'	AND  borrado=0 
	//ORDER BY datetime ASC";

$obj1 = new Consultas('biometriclog');
$obj1->Select();
$obj1->Where('codusuarios', $codusuarios);
$obj1->Where('datetime', $fechainicio, '>=');
$obj1->Where('datetime', $fechafin, '<=');
//$obj1->Orden('datetime', 'DESC'); No se usan pues cambia el orden de las marcas
//$obj1->Orden('enddatetime', 'DESC');
$obj1->Where('borrado', '0');

$paciente=$obj1->Ejecutar();
$rows = $paciente["datos"];

    $FechaInicioAnterior=$fechainicio; //formato fecha
    //echo $FechaInicioAnterior;
   // //echo "<br>";
    $horaIngreso='';
    $ingreso=1;
    $contador=0;
    $M1mayorHi=$M1menorHi=$M1igualHi=$M1mayorHimenorHs=0;
	$M1igualHs=$M1mayorHs=0;
    $considero=0;
    $MostrarHorario=1;
    $pasada=0;
    $SiguienteMarca=1;
	$horarioCumplido=1;
	$M1=$M2='';
	$dateTimeAux = new DateTime('2017-01-01 00:00:00');
	$TSEnddatetimeAux=$Timestamp0=$EndTimeaux=$dateTimeAux->getTimestamp();	 				  
	$dateTimeAux = new DateTime($FechaInicioAnterior);
	$FechaInicioAnteriorTS = $dateTimeAux->getTimestamp();  
	//echo "<br>**********************************************<br>";  
    //echo "<br>próxima fecha ".$dateTimeAux->format('Y-m-d H:i:s');
    $horariocumplido=0;
	foreach($rows as $fetch) {
		$codbiometric=$fetch['codbiometric'];
		$dateTimeAux = new DateTime($FechaInicioAnterior);
		//echo "<br>***********************************************<br>  ++++".$dateTimeAux->format('Y-m-d H:i:s');
		//echo "<br>Hi * ".$horaIngresoTS;
		//echo "<br>Hs * ".$horaSalidaTS;

		$TSFechaInicioAnterior=$dateTimeAux->getTimestamp();
		$dateTimeAux = new DateTime($fetch['datetime']);
		$TSMarca=$dateTimeAux->getTimestamp();
		////echo "<br> datetime ".$dateTimeAux->format('Y-m-d H:i:s');			
		////echo "<br>".($TSMarca-($TSFechaInicioAnterior+60))." > 60 ";
 				     			
	   	//No considero las marcas seguidas a menos de 60 segundos
	   	if(($TSFechaInicioAnterior+60*2) < $TSMarca) {
	   		$considero=1;
	   	}else {
			   if(strlen($fetch['codlog'])>0){

				$nombres = array();
				$valores = array();

				$nombres[] = 'borrado';
				$valores[] = '1';
  	
				$objqq = new Consultas('biometriclog');
				$objqq->Update($nombres,$valores);
				$objqq->Where('codlog',$fetch['codlog'] , '=');
				$objqq->Ejecutar();
			   }
	   		$considero=0;
	   	}
	   	if($considero==1) {
	   		//Si se trata de la primer marca $ingreso= 1
		   if( $ingreso==1 ) {
			$M1=$fetch['datetime'];
			$codlogAnt=$fetch['codlog'];
			
			$dateTimeAux = new DateTime($fetch['enddatetime']);
			$TSdatetimeAux=$dateTimeAux->getTimestamp();		    		
		    		
  		//echo "<br><br>Lectura ".($SiguienteMarca++)." -*-Primer Marca-------fecha ".$fetch['datetime']." <br>";
  		//$entrada=$fetch['datetime'];
		    		//Si la hora datetime de este registro es mayor o igual al enddatetime del registro anterior mas 60 segundos, macro como borrado esta marca
			    	if(($TSMarca-$TSEnddatetimeAux)<=60*2 and $Timestamp0==$TSdatetimeAux and $fetch['enddatetime']!='NULL' ) {
						$nombres = array();
						$valores = array();
		
						$nombres[] = 'borrado';
						$valores[] = '1';
			  
						$objqq = new Consultas('biometriclog');
						$objqq->Update($nombres,$valores);
						$objqq->Where('codlog',$fetch['codlog'] , '=');
						$objqq->Ejecutar();
			    	}  else {
							$horaIngreso=date("H:i:s", strtotime($fetch['datetime']));
							//Ingresa temprano.
								$e = array();
								$e['id'] =$fetch['codlog'];   								
								$e['start'] = $fetch['datetime'];
								$e['descripcion']=$fetch['descripcion'];							  	    				
								$validado=$fetch['validado'];
							if($TSMarca<$TSHingreso) { //1er caso
								//echo "<br>1 M1 menor que Hi ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$M1menorHi=1;
			    			}elseif($TSMarca==$TSHingreso) { //2do caso
								//echo "<br>2 M1 igual Hi ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$M1igualHi=1;
			    			}elseif($TSMarca>$TSHingreso and $TSMarca<$TSHsalida) { //3er caso
								//echo "<br>3 M1 mayor que Hi y M1 menor Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$M1mayorHimenorHs=1;
			    			}elseif($TSMarca==$TSHsalida) { //4to caso
								//echo "<br>2.1 M1 igual Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
			    				$M1igualHs=1;
			    			}elseif($TSMarca>$TSHsalida) {//5to caso
								//echo "<br>4 M1 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
				    			$M1mayorHs=1;
			    			}
					$dateTimeAux = new DateTime($fetch['enddatetime']);
					$TSEnddatetimeAux=$dateTimeAux->getTimestamp();
					$ingreso=0;			    			
				   }
					$dateTimeAux = new DateTime($fetch['enddatetime']);		
					$TSEnddatetime=$dateTimeAux->getTimestamp();

					if(strlen($fetch['usuariocod'])>0){
						$usuariocod=UserID($fetch['usuariocod']);
					}else{
						$usuariocod='';
					}

				   	///////////////////////////////////////////////////////////////////
						//*Si dentro de la misma marca tengo la enddatetime
						//////////////////////////////////////////////////////////////////
				     	if( $TSEnddatetime!=$Timestamp0 and $fetch['enddatetime']!=NULL)  {
				     	$ingreso=1;
				     	$M2=$fetch['enddatetime'];
						$dateTimeAux = new DateTime($fetch['enddatetime']);
						$TSEnddatetimeAux=$dateTimeAux->getTimestamp();
						$horaSalida=$dateTimeAux->format('H:i:s');
								$e['id'] =$codlogAnt;   								
								$e['start'] = $M1;
								$e['suma'] = 'n';	  								   			
								$e['end'] = $M2;


				     		if($M1menorHi==1 and $TSEnddatetime<$TSHingreso){ //B 1er caso
					     		if($fetch['validado']==1) {
									$e['className']= 'custom';

							  		$e['title'] ="M1 menor Hi y M1b menor Hi Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 menor Hi y M1b menor Hi Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}	  								   							     		
								//echo "<br>5 M1 menor Hi y M1b menor Hi ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1menorHi==1 and $TSEnddatetime==$TSHingreso) { //B 2do caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
									$e['title'] ="M1 menor Hi y M1b igual Hi Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 menor Hi y M1b igual Hi Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}	 	  								   							     		
								//echo "<br>6 M1 menor Hi y M1b igual Hi ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1menorHi==1 and $TSEnddatetime> $TSHingreso and $TSEnddatetime<$TSHsalida) { //B 3er caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 menor Hi y M2 entre Hi y Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 menor Hi y M2 entre Hi y Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'tarde';
									  $e['suma'] = 'n';
									  
					     		}			     		
								//echo "<br>7 M1 menor Hi y M2 entre Hi y Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1menorHi==1 and $TSEnddatetime==$TSHsalida) { //B 4to Caso
					     		//if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 menor Hi y M2 igual Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
									  $e['icon']= 'ok.png';
									  $horariocumplido=1;
							  	/*	
					     		}	else {
									$e['title'] ="M1 menor Hi y M2 igual Hs  Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'tarde';
							  		$e['suma'] = 'n';
							  		$e['icon']= 'error.png';
					     		}
					     		*/									
								//echo "<br>8 M1 menor Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
								$MostrarHorario=0;
				     		}elseif($M1menorHi==1 and $TSEnddatetime>$TSHsalida) { //B 5to Caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 menor Hi y M2 mayor Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 menor Hi y M2 mayor Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}				     		
								//echo "<br>9 M1 menor Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1igualHi==1 and $TSEnddatetime>$TSHingreso and $TSEnddatetime<$TSHsalida) { //C 1ero caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 igual a Hi y M2 entre Hi y Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 igual a Hi y M2 entre Hi y Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}	
								//echo "<br>10 M1 igual a Hi y M2 entre Hi y Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1igualHi==1 and $TSEnddatetime==$TSHsalida) { //C 2do caso
									$e['title'] ="M1 igual Hi y M2 igual Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'custom';
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
								//echo "<br>11 M1 igual Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
								$MostrarHorario=0;
				     		}elseif($M1igualHi==1 and $TSEnddatetime>$TSHsalida) { //C 3er caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 igual Hi y M2 mayor Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 igual Hi y M2 mayor Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}				     		
								//echo "<br>12 M1 igual Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
								$MostrarHorario=0;
				     		}elseif($M1mayorHi==1 and $TSEnddatetime<$TSHsalida) { //D 1er caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 mayor Hi y M2 menor Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 mayor Hi y M2 menor Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}					     		
								//echo "<br>13 M1 mayor Hi y M2 menor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
								$MostrarHorario=0;
				     		}elseif($M1mayorHi==1 and $TSEnddatetime==$TSHsalida) { //D 2do caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 mayor Hi y M2 igual Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 mayor Hi y M2 igual Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}					     		
								//echo "<br>14 M1 mayor Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
								$MostrarHorario=0;
				     		}elseif($M1mayorHi==1 and $TSEnddatetime>$TSHsalida) { //D 3er caso
								$e['title'] ="M1 mayor Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))." Ing.: ".$M1.", sal.: ".$M2;
								$e['className']= 'fueradehora';
								$e['suma'] = 'n';					     		
								//echo "<br>15 M1 mayor Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1igualHs==1 and $TSEnddatetime>$TSHsalida) { //E 1er caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 igual Hs y M2 mayor Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 igual Hs y M2 mayor Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}					     		
								//echo "<br>16 M1 igual Hs y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}elseif($M1mayorHs==1 and $TSEnddatetime>$TSHsalida) { //E 1er caso
					     		if($fetch['validado']==1) {
						     		$e['className']= 'custom';
							  		$e['title'] ="M1 mayor Hs y M2 mayor Hs Validado por: ". $usuariocod ."-> Ing.: ".$M1.", sal.: ".$M2;
							  		$e['suma'] = 's';
							  		$e['icon']= 'ok.png';
					     		}	else {
									$e['title'] ="M1 mayor Hs y M2 mayor Hs Ing.: ".$M1.", sal.: ".$M2;
									$e['className']= 'fueradehora';
							  		$e['suma'] = 'n';
					     		}	
				     		//echo "<br> ";
								//echo "<br>17 M1 mayor Hs y M2 mayor Hs ".date("H:i:s", strtotime($fetch['enddatetime']))."<br>";
				     		}
							$FechaInicioAnterior=$fetch['enddatetime'];
							array_push($events, $e);							
							$ingreso=1;
						}		

						//////////////////////////////////////////////////	
						///Evaluo la segunda marca
						//////////////////////////////////////////////////	    
	      } elseif($TSMarca>$TSFechaInicioAnterior and $ingreso==0) {
	      	
	      	$codlog=$fetch['codlog'];
	      	$M2=$fetch['datetime'];
	      	if($FechaInicioAnteriorTS==$TSHingreso) {
	      		//echo "<br> No hay horario asignado<br>";
	      		$estado=updatedata($codlogAnt, $M1, $M2, 2, "red",0);
					$e['id'] =$codlogAnt; //$AutoId++;   								
					$e['start'] = $M1;
					$e['title'] ="Actualizo registro anterior, datetime=M1, enddatetime=M2 Ing.: ".$M1.", sal.: ".$M2;
					$e['className']= 'fueradehora';
					$e['suma'] = 'n';	  
					if($fetch['validado']==1) {
						$e['className']= 'custom';
						$e['title'] ="->". ($pasada++)."-10.1 Validado por: ". $usuariocod ."-> Ing.: ".$hingreso.", sal.: ".$hsalida;
						$e['suma'] = 's';
						$e['icon']= 'ok.png';
					}									   			
					$e['end'] = $M2;
					array_push($events, $e);			      		
	      		//echo "<br> Actualizo registro anterior, datetime=M1, enddatetime=M2. ";
	      		$estado=updatedata($codlog, '', '', '', '', 1);
	      		//echo "<br>Actualizo registro actual, borrado = 1 ";
	      		$horarioCumplido=0;
	      		$ingreso=1;
	      	}else {
	      		//echo "<br>".$FechaInicioAnteriorTS."==".$TSHingreso."<br>";
					$dateTimeAux = new DateTime($fetch['datetime']);		
					$TSEnddatetime=$dateTimeAux->getTimestamp();	 
					//echo "<br> M1 -> ".  $M1;
					//echo "<br> M2 -> ".  $M2;
	      	$ingreso=1;
	      	$salida=$fetch['datetime'];
		  		//echo "<br><br>Lectura ".($SiguienteMarca++)." -*-Segunda Marca ------- Entrada ".$entrada." Salida ".$salida." <br>";
			    			if($M1menorHi==1 and $TSEnddatetime<$TSHingreso){ //B 1er caso
			    			//$estado=updatedata($codlog, $datetime, $enddatetime, $state, $color);
			    			$estado=updatedata($codlogAnt, $M1, $M2, 2, "red",0);
								$e['id'] =$codlogAnt; //$AutoId++;   								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1, enddatetime = M2, no validado, color rojo Ing.: ".$M1.", sal.: ".$M2;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);				    			
			    			//echo "<br> Actualizo registro anterior, datetime = M1, enddatetime = M2, no validado, color rojo";
								//echo "<br>18 M1 menor Hi y M2 menor Hi ".date("H:i:s", strtotime($fetch['datetime']));
								//echo "<br> Actualizo registro actual, borrado = 1";
								$estado=updatedata($codlog, '', '', '', '', 1);
								$MostrarHorario=1;
				     		}elseif($M1menorHi==1 and $TSEnddatetime==$TSHingreso) { //B 2do caso
								$estado=updatedata($codlogAnt, $M1, $M2, 2, "red",0);
								$e['id'] =$codlogAnt; //$AutoId++;   								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1 y enddatetime = M2 Ing.: ".$M1.", sal.: ".$M2;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);				    			
				     		//echo "<br>Actualizo registro anterior, datetime = M1 y enddatetime = M2";
								//echo "<br>19 M1 menor Hi y M2 igual Hi ".date("H:i:s", strtotime($fetch['datetime']));
								//echo "<br>Actualizo registro actual, borrado = 1";
								$estado=updatedata($codlog, '', '', '', '', 1);
								$MostrarHorario=1;
				     		}elseif($M1menorHi==1 and $TSEnddatetime> $TSHingreso and $TSEnddatetime<$TSHsalida) { //B 3er caso
								$estado=updatedata($codlogAnt, $M1, $hingreso, 2, "red",0);
								$e['id'] =$codlogAnt; //$AutoId++;   								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1 y enddatetime = Hi Ing.: ".$M1.", sal.: ".$hingreso;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $hingreso;
								$e['icon']= 'error.png';
								array_push($events, $e);					     		
				     		//echo "<br>Actualizo registro anterior, datetime = M1 y enddatetime = Hi";
				     		$estado=updatedata($codlog, $hingreso, $M2, 2, "red",0);
								$e['id'] =$codlog; //$AutoId++;   								
								$e['start'] = $hingreso;
								$e['title'] ="Modifico registro actual, datetime = Hi y enddatetime = M2 Ing.: ".$hingreso.", sal.: ".$M2;
								$e['className']= 'custom';
								$e['suma'] = 's';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'ok.png';
								array_push($events, $e);					     		
				     		//echo "<br> Modifico registro actual, datetime = Hi y enddatetime = M2";
								//echo " -> 20 M1 menor Hi y M2 entre Hi y Hs ".date("H:i:s", strtotime($fetch['datetime']))."";
								$e['id'] =agrego($M2, $hsalida,$codusuarios, $UserID, $codbiometric); //$AutoId++;   								
								$e['start'] = $M2;
								$e['title'] ="Agrego registro, datetime = M2 y enddatetime = Hs, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);									
								//echo "<br>Agrego registro, datetime = M2 y enddatetime = Hs, no válido, color rojo <br>";
								$MostrarHorario=0;
				     		}elseif($M1menorHi==1 and $TSEnddatetime==$TSHsalida) { //B 4to Caso
								$estado=updatedata($codlogAnt, $M1, $hingreso, 2, "red",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1, enddatetime = Hi Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hingreso;
								$e['icon']= 'error.png';
								array_push($events, $e);			    			
				     		//echo "<br>Actualizo registro anterior, datetime = M1, enddatetime = Hi";
								//echo "<br>21 M1 menor Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$estado=updatedata($codlog, $hingreso, $hsalida, 2, "red",0);
								$e['id'] =$codlog;    								
								$e['start'] = $hingreso;
								$e['title'] ="Atualizo registro, datetime = Hi, enddatetime = Hs Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	  								   			
								$e['end'] = $hsalida;
								$e['icon']= 'ok.png';
								array_push($events, $e);										
								//echo "<br>Atualizo registro, datetime = Hi, enddatetime = Hs";
								$MostrarHorario=0;
							 }elseif($M1menorHi==1 and $TSEnddatetime>$TSHsalida) { //B 5to Caso
								$estado=updatedata($codlogAnt, $M1, $hingreso, 2, "red",0);
								
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1, enddatetime = Hi Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hingreso;
								$e['icon']= 'error.png';
								array_push($events, $e);					     		
				     		//echo "<br> Actualizo registro anterior, datetime = M1, enddatetime = Hi";
								//echo "<br>22 M1 menor Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$estado=updatedata($codlog, $hingreso, $hsalida, 2, "red",0);
								$e['id'] =$codlog;    								
								$e['start'] = $hingreso;
								$e['title'] ="Actualizo registro actual, datime=Hi, enddatetime=Hs Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	  								   			
								$e['end'] = $hsalida;
								$e['icon']= 'ok.png';
								array_push($events, $e);										
								//echo "<br> Actualizo registro actual, datime=Hi, enddatetime=Hs";
								$e['id'] =agrego($hsalida, $M2, $codusuarios,$UserID, $codbiometric);  								
								$e['start'] = $hsalida;
								$e['title'] ="Agrego  registro, datetime= Hs, enddatetime=M2 Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);	
								$MostrarHorario=0;							
								//echo "<br>Agrego  registro, datetime= Hs, enddatetime=M2";
				     		}elseif($M1igualHi==1 and $TSEnddatetime>$TSHingreso and $TSEnddatetime<$TSHsalida) { //C 1ero caso
								$estado=updatedata($codlogAnt, $M1, $M2, 2, "verde",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime= M1, enddatetime=M2, válido, color verde  Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';								   			
								$e['end'] = $M2;
								$e['icon']= 'ok.png';
								array_push($events, $e);					     		
				     		//echo "<br> Actualizo registro anterior, datetime= M1, enddatetime=M2, válido, color verde ";
								//echo "<br>23 M1 igual a Hi y M2 entre Hi y Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$estado=updatedata($codlog, $M2, $hsalida, 2, "rojo",0);
								$e['id'] =$codlog;    								
								$e['start'] = $M2;
								$e['title'] ="Actualizo registro actual, datetime=M2, enddatetime=Hs, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);									
								//echo "<br>Actualizo registro actual, datetime=M2, enddatetime=Hs, no válido, color rojo ";
								$MostrarHorario=0;
				     		}elseif($M1igualHi==1 and $TSEnddatetime==$TSHsalida) { //C 2do caso
								$estado=updatedata($codlogAnt, $M1, $M2, 2, "verde",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1 y enddatetime = M2 Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	  								   			
								$e['end'] = $M2;
								$e['icon']= 'ok.png';
								array_push($events, $e);					     		
				     		//echo "<br> Actualizo registro anterior, datetime = M1 y enddatetime = M2";
								//echo "<br>24 M1 igual Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$estado=updatedata($codlog, '', '', '', '',1);
								//echo "<br>Actualizo registro actual, borrado =1 ";
								$MostrarHorario=0;
				     		}elseif($M1igualHi==1 and $TSEnddatetime>$TSHsalida) { //C 3er caso
								$estado=updatedata($codlogAnt, $M1, $hsalida, 2, "verde",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime =M1 enddatetime = Hs Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	  								   			
								$e['end'] = $hsalida;
								$e['icon']= 'ok.png';
								array_push($events, $e);					     		
				     		//echo "<br>Actualizo registro anterior, datetime =M1 enddatetime = Hs";
								//echo "<br>25 M1 igual Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']))."";
								$estado=updatedata($codlog, $hsalida, $M2, 2, "rojo",0);
								$e['id'] =$codlog;    								
								$e['start'] = $hsalida;
								$e['title'] ="Actualizo registro actual, endtime = Hs, enddatetime = M2, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);									
								//echo "<br> Actualizo registro actual, endtime = Hs, enddatetime = M2, no válido, color rojo<br> ";
								$MostrarHorario=0;
				     		}elseif($M1mayorHimenorHs==1 and $TSEnddatetime<$TSHsalida) { //D 1er caso
								$estado=updatedata($codlogAnt, $hingreso, $M1, 2, "rojo",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $hingreso;
								$e['title'] ="Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M1;
								$e['icon']= 'error.png';
								array_push($events, $e);					     		
				     		//echo "<br>Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo";
								//echo "<br>26 M1 mayor Hi y M2 menor Hs ".date("H:i:s", strtotime($fetch['datetime']));
								$estado=updatedata($codlog, $M1, $M2, 2, "verde",0);
								$e['id'] =$codlog;    								
								$e['start'] = $M2;
								$e['title'] ="Actualizo registro actual, datetime = M1, enddatetime = M2 Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'ok.png';
								array_push($events, $e);									
								//echo "<br>Actualizo registro actual, datetime = M1, enddatetime = M2";
								$e['id'] =agrego($M2, $hsalida, $codusuarios,$UserID, $codbiometric);    								
								$e['start'] = $M2;
								$e['title'] ="Agrego registro datetime= M2 enddatetime = Hs, no validado color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);	
								//echo "<br> Agrego registro datetime= M2 enddatetime = Hs, no validado color rojo ";
				     		}elseif($M1mayorHimenorHs==1 and $TSEnddatetime==$TSHsalida) { //D 2do caso
								$estado=updatedata($codlogAnt, $hingreso, $M1, 2, "rojo",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $hingreso;
								$e['title'] ="Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M1;
								$e['icon']= 'error.png';
								array_push($events, $e);									
				     		//echo "<br>Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo";
								//echo "<br>27 M1 mayor Hi y M2 igual Hs ".date("H:i:s", strtotime($fetch['datetime']));
								$estado=updatedata($codlog, $M1, $M2, 2, "verde",0);
								$e['id'] =$codlog;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro actual, datetime = M1, enddatetime = M2 Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'ok.png';
								array_push($events, $e);									
								//echo "<br>Actualizo registro actual, datetime = M1, enddatetime = M2";
				     		}elseif($M1mayorHimenorHs==1 and $TSEnddatetime>$TSHsalida) { //D 3er caso
								$estado=updatedata($codlogAnt, $hingreso, $M1, 2, "rojo",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $hingreso;
								$e['title'] ="Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M1;
								$e['icon']= 'error.png';
								array_push($events, $e);													     		
				     		//echo "<br>Actualizo registro anterior, datime = Hi, enddatetime = M1, no validado, color rojo";
								//echo "<br>28 M1 mayor Hi y M2 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']));
								$estado=updatedata($codlog, $M1, $hsalida, 1, "rojo",0);
								$e['id'] =$codlog;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro actual, datetime = M1, enddatetime = Hs Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'custom';
								$e['suma'] = 's';	  								   			
								$e['end'] = $hsalida;
								$e['icon']= 'ok.png';
								array_push($events, $e);									
								//echo "<br>Actualizo registro actual, datetime = M1, enddatetime = Hs";
								$e['id'] =agrego($hsalida, $M2, $codusuarios, $UserID, $codbiometric);    								
								$e['start'] = $hsalida;
								$e['title'] ="Agrego registro datetime= Hs enddatetime = M2, no validado color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);									
								//echo "<br> Agrego registro datetime= Hs enddatetime = M2, no validado color rojo ";
								$MostrarHorario=0;
				     		}elseif($M1igualHs==1 and $TSEnddatetime>$TSHsalida) { //E 1er caso
				     		/*
								$e['id'] =agrego($hingreso, $hsalida, $codusuarios, $codbiometric);    								
								$e['start'] = $hingreso;
								$e['title'] ="Agrego registro, datetime=Hi, enddatetime=M1, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);	
								*/				     		
				     		//echo "<br>Agrego registro, datetime=Hi, enddatetime=M1, no válido, color rojo"; 
				     		//echo "<br> Actualizo registro anterior, datetime = M1 enddatetime = M2, no válido, color rojo";
				     		$estado=updatedata($codlogAnt, $M1, $M2, 2, "rojo",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo registro anterior, datetime = M1 enddatetime = M2, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  								   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);				     		
								//echo "<br>M1 igual Hs y M2 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']))."<br>";
								$estado=updatedata($codlog, '', '', '', '',1); 
								//echo "<br> Actualizo registro actual, borrado = 1";
				     		}elseif($M1mayorHs==1 and $TSEnddatetime>$TSHsalida) { //E 1er caso
				     		/*
								$e['id'] =agrego($hingreso, $hsalida, $codusuarios, $codbiometric);    								
								$e['start'] = $hingreso;
								$e['title'] ="Agrego registro, datetime=Hi, enddatetime=M1, no válido, color rojo Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);	
								*/			     		
				     		//echo "<br>Agrego registro, datetime=Hi, enddatetime=M1, no válido, color rojo"; 
				     		$estado=updatedata($codlogAnt, $M1, $M2, 2, "rojo",0);
								$e['id'] =$codlogAnt;    								
								$e['start'] = $M1;
								$e['title'] ="Actualizo marca anterior, datetime = M1, enddatetime = M2:  Ing.: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $M2;
								$e['icon']= 'error.png';
								array_push($events, $e);				     		
				     		//echo "<br>Actualizo marca anterior, datetime = M1, enddatetime = M2";
								//echo "<br>30 M1 mayor Hs y M2 mayor Hs ".date("H:i:s", strtotime($fetch['datetime']));
								$estado=updatedata($codlog, '', '', '', '',1); 
								//echo "<br>Actualizo marca actual, borrado = 1";
								//$MostrarHorario=1;
				     		}   	
	      	$ingreso=1;	
    $M1menorHi=$M1igualHi=$M1mayorHimenorHs=0;
	$M1igualHs=$M1mayorHs=0;
				}
			} 		
 		} //Fin del considero

	   	/*Para el primer registro del día*/
	   	$FechaInicioAnterior=$fetch['datetime'];
		$e['comentario']= $fetch['comentario'];
    } //fin del while del día

		if($MostrarHorario==1 and $TSHingreso!=$TSHsalida) {
			//echo "<br>Agrego nuevo registro, datetime = Hi, enddatetime = Hs";
			//echo "<br>Muestro horario asignado, pero no cumplido<br>";
								$e['id'] ='';    								
								$e['start'] = $hingreso;
								$e['title'] ="Muestro horario asignado, pero no cumplido: ".$hingreso.", sal.: ".$hsalida;
								$e['className']= 'tarde';
								$e['suma'] = 'n';	  									   			
								$e['end'] = $hsalida;
								$e['icon']= 'error.png';
								array_push($events, $e);	 
		}

	if($ingreso==0) {
		//Si la marca esta entre la Marca y la salida mas 10 minutos, considero que no no marco entrada
			$dateTimeAux = new DateTime($M1);
			$M1TS=$dateTimeAux->getTimestamp();	
			$e['id'] =''; 			
		if($M1TS > $horaIngresoTS and ($horaSalidaTS-$M1TS<10*60) and $MostrarHorario==1) {
			$e['start'] =date("Y-m-d H:i:s", strtotime($FechaInicioAnterior)-30*60);
			$e['title'] = "No marco entrada ";
			$e['end'] =  $M1;
			$e['className']= 'temprano';			
		} else { 
		     //echo "<br>No marco salida <br>";
			$e['start'] = $M1;
			$e['title'] = "No marco salida ";
			$e['end'] = date("Y-m-d H:i:s", strtotime($FechaInicioAnterior)+30*60);
			$e['className']= 'fueradehora';			
		} 
			$e['icon']= 'ver.png';				     
			$e['suma'] = 'n';
			array_push($events, $e);
	}
    
////echo "va <br>".$dateTime->format('Y-m-d');   
$dateTime->add(new DateInterval('P1D'));
////echo "<br>va2 <br>".$dateTime->format('Y-m-d H:i:s');
$Timestampfechainicio = $dateTime->getTimestamp();   

 
$dateTimeFin = new DateTime($fechafin);
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');
    
 } //Fin de recorrer las fechas
    //var_dump($events);
    echo  json_encode($events);
