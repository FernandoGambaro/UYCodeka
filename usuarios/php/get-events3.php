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

$codusuarios=$_POST['codusuarios'] ? $_POST['codusuarios'] : $_GET['codusuarios'] ;
$nempleado=$_POST['nempleado'] ? $_POST['nempleado'] : $_GET['nempleado'] ;

$events = array();


if (!isset($start) || !isset($end)) {
  die("Error, falta rango de fechas.");
}

// Parse the timezone parameter if it is present.
$timezone = null;
if (isset($_POST['timezone'])) {
  $timezone = new DateTimeZone($_POST['timezone']);
}
//Funcion para obtener el nombre y apellido de un usuario de la tabla usuarios usando su código
function UserID($usuarios) {
	if(strlen($usuarios)>0){
		$obj = new Consultas('usuarios');
		$obj->Select();	
		$obj->Where('codusuarios', $usuarios, '=');
		$paciente = $obj->Ejecutar();
		$row=$paciente['datos'][0];
		$nombre=$row["nombre"].' '.$row["apellido"];
	} else{
		$nombre='Sin nombre';
	}
	return $nombre;
}
function ArmoCalendario($id, $start, $title, $className, $validado, $end){
	$e=array();

	$e['id'] =$id;
	$e['start'] = $start;
	$e['title'] =$title;
	$e['className']= $className;
	$e['suma'] = 'n';	  
	if($validado==1) {
		$e['className']= $className;
		$e['title'] =$title;
		$e['suma'] = 's';
		$e['icon']= 'ok.png';
	}									   			
	$e['end'] = $end;
	return $e;
}

$estado='';

$dateTime = new DateTime($start);
$fechainicio= $dateTime->format('Y-m-d H:i:s');
$Timestampfechainicio=$dateTime->getTimestamp();

$dateTimeFin = new DateTime($start);
$dateTimeFin->add(new DateInterval('P1D'));
$fechafin = $dateTimeFin->format('Y-m-d H:i:s');

$dateTime = new DateTime($end);
$endTime=$dateTime->getTimestamp();

$idLicencia=10000000;

while($Timestampfechainicio<=$endTime) {
	$e=array();
	$horarioCumplido=0;
	$noregistro=1; // Digo que no hay registro para este día, si encuentro alguno cambio a 0    
	/*
	Obtengo el horario que debería cumplir el usuario y lo almaceno en un array multidimencional 
	*/

	$dateTime = new DateTime();
	$dateTime->setTimestamp($Timestampfechainicio);
	$fechainicio=$dateTime->format('Y-m-d H:i:s');

$desde=$dateTime->format('Y-m-d');
/*Consulto si el día recorrido está dentro del periodo de licencia*/

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
	$e=array();

	$desde = date('Y-m-d 00:00:00', strtotime("today", $Timestampfechainicio));
	$hasta = date('Y-m-d 23:59:59', (strtotime("tomorrow", $Timestampfechainicio) - 1));

	$e['id'] =$idLicencia;
	$e['start'] = $desde;
	$e['title'] ='Licencia';
	//$e['allDay'] ='true';
	$e['end'] = $hasta;
	array_push($events, $e);
	$idLicencia++;

}else{

	//echo '<p>dia procesado: '.$fechainicio.'<p>';
	$diasemana=$dateTime->format('N');

	if($diasemana==0) {
		$diasemana=7;
	}
	
	$inicio=$dateTime->format('Y-m-d');

		///Para el día busco el horario que debe cumplir
	if(strlen($codusuarios)>0){

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
					$horaIngresoTS[$x]=$Time->format('Y-m-d H:i:s');
					$Time = new DateTime($hsalida);
					$horaSalidaTS[$x]=$Time->format('Y-m-d H:i:s');
					$TSHsalida[$x]=$Time->getTimestamp();
					$x++;
				}
			}
				
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
			foreach($rows as $row){
				
				if($noregistro==0){
					$OldCodlog=$row['codlog'];
					$TSMarcaAux = (new DateTime($row['datetime']))->getTimestamp();
				}
				$TSMarca = (new DateTime($row['datetime']))->getTimestamp();
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
					$objqq->Where('codlog',$row['codlog'] , '=');
					$objqq->Ejecutar();
					
					//$TSMarcaAux=$TSMarca;
				}elseif(((int)$TSMarca-(int)$TSMarcaAux-60*3)>0 and $noregistro>0 and $DeafaultTime==(new DateTime($row['enddatetime']))->getTimestamp() ){
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
					$TSMarcaAux=$TSMarca;
				}
				
				
				$EnddatetimeAux=(new DateTime($row['enddatetime']))->getTimestamp();
				
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
		Recorro nuevamente las marcas del día pero ya depuradas.
		Comparo los horarios.

		*/

		$obj1 = new Consultas('biometriclog');
		$obj1->Select();
		$obj1->Where('codusuarios', $nempleado, '=');
		//$obj1->Where('codusuarios', $codusuarios, '=');
		$obj1->Where('datetime', $fechainicio, '>=');
		$obj1->Where('datetime', $fechafin, '<=');
		$obj1->Orden('codlog', 'ASC');	
		$obj1->Where('borrado', '0');
		$obj1->Where('depurado', '1');
		$pacie=$obj1->Ejecutar();
		$rows = $pacie["datos"];
		//echo "<br>".$pacie['consulta'];
		//echo '<br> <b>  Cantidad de marcas despúes de depurar: '.$CantidadMarcas=$pacie['numfilas'].'</b><br>';

		$TimeAux = new DateTime('2017-01-01 00:00:00');
		$DefaultTime=$TimeAux->getTimestamp();	 				  

		if($pacie['numfilas']>0){


			$MarcaCumplida= array();
			foreach($rows as $row){
			//Trabajo las feha-hora en timestamp

			$TimeAux = new DateTime($row['datetime']);
			$MarcaEntrada=$TimeAux->getTimestamp();

			$TimeAux = new DateTime($row['enddatetime']);
			$MarcaSalida=$TimeAux->getTimestamp();

			if(strlen($fetch['usuariocod'])>0){
				$usuariocod=UserID($row['usuariocod']);
			}else{
				$usuariocod='';
			}
			
			if($CantidadHorarios==0){
				if( $MarcaSalida!=$DefaultTime){
					$e = ArmoCalendario($row['codlog'], $row['datetime'], 'No tiene horario asignado para este día ', 'fueradehora', $row['validado'], $row['enddatetime']);
					array_push($events, $e);
				}else{
					$date= new DateTime( $row['datetime']);
					$date->modify('+30 minute');
					$date=$date->format('Y-m-d H:m');

					$e = ArmoCalendario($row['codlog'], $row['datetime'], ' No tiene horario asignado y no marco salida '  , 'fueradehora', $row['validado'], $date);
					array_push($events, $e);
					$alcamarca++;
				}
			}else{
				//Tengo una sola marca y sin salida
				if($pacie['numfilas']==1 and $MarcaSalida==$DefaultTime){
					$alcamarca=0;
					//Para todos los horarios a cumplir me fijo en que lugar esta la marca
					for($xy=0;$xy<$CantidadHorarios; $xy++){
						//echo '<br> Hora de entrada: '.$TSHingreso[$xy]. ', hora de salida: '.$TSHsalida[$xy].'<br>';
						//Si la marca es menor que la hora ingreso
						if($MarcaEntrada < $TSHingreso[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br> La marca es menor que la hora de ingreso '.$xy;
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes, no marca salida', 'fueradehora', $row['validado'], $horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muestro marca a hora ingreso en azul';
							$MarcaCumplida[]=$xy;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaEntrada < $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br> La marca es mayor que la hora de ingreso y menor a la hora de salida '.$xy;
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Llega tarde, no marca salida', 'fueradehora', $row['validado'], $row['datetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en azul</span>';
							$e = ArmoCalendario($newId, $row['datetime'], 'Llega tarde, no marca salida' , 'tarde', ' ', $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muestro hora de ingreso a marca rojo'; 
							//echo '<br> Muestro marca a hora de salida verde, diciendo que no marco salida'; 
							$MarcaCumplida[]=$xy;
						}elseif($MarcaEntrada > $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br> La marca es mayor que la hora de salida '.$xy;
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], ' Marca salida '  , 'tarde', $row['validado'], $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:red;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime'] .' M-in en rojo (llego tarde)</span>'; 
							//Sumo 15 minutos a la marca...
							if($alcamarca==0){
								$date= new DateTime( $row['datetime']);
								$date->modify('+10 minute');
								$date=$date->format('Y-m-d H:m');

								$e = ArmoCalendario($row['codlog'], $row['datetime'], ' No marco entrada '  , 'custom', $row['validado'], $date);
								array_push($events, $e);
								$alcamarca++;
							}		
							//echo '<br> Muesto el horario no cumplido, diciendo que no marco entrada';
							$MarcaCumplida[]=$xy;
						}elseif(!in_array($xy, $MarcaCumplida)){
							//echo '<br> Muesto el horario como no cumplido<br>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'No cumple horario '. $horaSalidaTS[$xy]  , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muesto el horario como no cumplido';
							$MarcaCumplida[]=$xy;
						}
						
					}
				}elseif($pacie['numfilas']==1 and $MarcaSalida!=$DefaultTime){
					//echo '<br> Para una marca y con la marca de salida<br>';
					$alcamarca=0;
					for($xy=$horarioCumplido;$xy<$CantidadHorarios; $xy++){
						//echo '<br> Hora de entrada: '.$TSHingreso[$xy]. ', hora de salida: '.$TSHsalida[$xy].'<br>';
						//Si la marca es menor que la hora ingreso
						if($MarcaEntrada < $TSHingreso[$xy] and $MarcaSalida < $TSHingreso[$xy] and $alcamarca==0){
							//echo '<br><span style="color:grey;"> La marca entrada es menor que la hora de ingreso y la Marca salida es menor que la hora de salida</span> ';
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes ', 'fueradehora', $row['validado'], $row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en azul</span>';
							$e = ArmoCalendario($newId, $horaIngresoTS[$xy], 'No vumple horario ' , 'tarde', ' ', $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:rojo;"> Muestro '. $horaIngresoTS[$xy] . ' H-in hasta '. $horaSalidaTS[$xy] .' H-out en rojo</span><br>';
							$alcamarca++;
							break;
						}elseif($MarcaEntrada < $TSHingreso[$xy] and  $MarcaSalida > $TSHingreso[$xy] and $MarcaSalida < $TSHsalida[$xy] and $alcamarca==0){
							//echo '<br><span style="color:red;"> La marca entrada es menor que la hora de entrada y la marca salida y menor a la hora de salida </span>';
							//echo '<br> Muestro desde M-in hasta H-in en azul (llego azul'; 
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes ', 'fueradehora', $row['validado'],$horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muestro desde H-in hasta M-out en verde( cumple horario)';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Cumple horario ', 'fueradehora', $row['validado'], $row['enddatetime']);
							array_push($events, $e);
							//echo '<br> Muestro desde M-out hasta H-out en rojo (se fue antes)';
							$e = ArmoCalendario($row['codlog'], $row['enddatetime'], 'Sale antes', 'fueradehora', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							$alcamarca++;
						}elseif($MarcaEntrada < $TSHingreso[$xy] and $MarcaSalida > $TSHsalida[$xy] and $alcamarca==0){
							//echo '<br><span style="color:red;"> La marca entrada es menor que la hora de entrada y la marca salida mayor a la hora de salida </span>';
							//echo '<br> Muestro desde M-in hasta H-in en azul (llego azul'; 
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes ', 'fueradehora', $row['validado'],$horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muestro desde H-in hasta H-out en verde( cumple horario)';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Cumple horario ', 'custom', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muestro desde H-out hasta M-out en azul (se fue tarde)';
							$e = ArmoCalendario($row['codlog'] ,$horaSalidaTS[$xy], 'Sale tarde ', 'fueradehora', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							$alcamarca++;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaSalida < $TSHsalida[$xy] and $alcamarca==0){
							//echo '<br><span style="color:red;"> La marca entrada es mayor que la hora de entrada y la marca salida menor a la hora de salida </span>';
							//echo '<br> Muestro desde H-in hasta M-in en rojo (llego tarde)'; 
							$e = ArmoCalendario($row['codlog'] ,$horaIngresoTS[$xy], 'Ingresa tarde ', 'tarde', $row['validado'],$row['datetime']);
							array_push($events, $e);
							//echo '<br> Muestro desde M-in hasta M-out en verde( cumple horario)';
							$e = ArmoCalendario($row['codlog'] ,$row['datetime'], 'Cumple horario ', 'custom', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							//echo '<br> Muestro desde M-out hasta H-out rojo (se fue azul)';
							$e = ArmoCalendario($row['codlog'] ,$row['enddatetime'], 'Sale antes de hora ', 'fueradehora', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							$alcamarca++;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaSalida > $TSHsalida[$xy] and $MarcaEntrada < $TSHsalida[$xy] and $alcamarca==0 ){
							//echo '<br><span style="color:red;"> La marca entrada es mayor que la hora de entrada y la marca salida mayor a la hora de salida </span> ';
							//echo '<br> Muestro desde H-in hasta M-in en rojo (llego tarde)'; 
							$e = ArmoCalendario($row['codlog'] , $horaIngresoTS[$xy], 'Ingresa tarde ' , 'tarde', $row['validado'],$row['datetime'] );
							array_push($events, $e);
							//echo '<br> Muestro desde M-in hasta H-out en verde( cumple horario)';
							$e = ArmoCalendario($row['codlog'] , $row['datetime'], 'Cumple horario ' , 'custom', $row['validado'],$horaSalidaTS[$xy] );
							array_push($events, $e);
							//echo '<br> Muestro desde H-out hasta M-out azul (se fue tarde)';
							$e = ArmoCalendario($row['codlog'] , $horaSalidaTS[$xy], 'Sale fuera de hora ' , 'fueradehora', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							$alcamarca++;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaSalida > $TSHsalida[$xy] and $MarcaEntrada > $TSHsalida[$xy] and $alcamarca==0 ){
							//echo '<br><span style="color:red;"> La marca entrada es mayor que la hora de entrada y la marca salida mayor a la hora de salida </span> ';
							//echo '<br> Muestro desde H-in hasta M-in en rojo (llego tarde)'; 
							$e = ArmoCalendario($row['codlog'] , $horaIngresoTS[$xy], 'No cumple horario ' , 'tarde', $row['validado'],$horaSalidaTS[$xy] );
							array_push($events, $e);
							//echo '<br> Muestro desde H-out hasta M-out azul (se fue tarde)';
							$e = ArmoCalendario($row['codlog'] ,$row['datetime'], 'Fuera de hora' , 'fueradehora', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							$alcamarca++;
						}elseif( $alcamarca==0 ){
							//echo '<br> Muesto el horario como no cumplido<br>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'No cumple horario ' , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muesto el horario como no cumplido';
							$alcamarca++;
						}
					}

				}elseif($pacie['numfilas']>1 and $MarcaSalida==$DefaultTime){
					$alcamarca2=0;
					//echo '<br> Para mas de una marca y con la marca de salida en default<br>';
					for($xy=$horarioCumplido; $xy<$CantidadHorarios; $xy++){
						//echo '<br> Hora de entrada: '.$TSHingreso[$xy]. ', hora de salida: '.$TSHsalida[$xy].'<br>';
					//Si la marca es menor que la hora ingreso
						if($MarcaEntrada < $TSHingreso[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br><span style="color:grey;"> La marca entrada es menor que la hora de ingreso </span> ';
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes', 'custom', $row['validado'], $horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro '. $row['datetime'] .' M-in hasta '. $horaIngresoTS[$xy] .' M-out en azul</span>';
							$e = ArmoCalendario($newId, $horaIngresoTS[$xy], 'No marca salida ' , 'azul', ' ', $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:rojo;"> Muestro '. $horaIngresoTS[$xy] . ' H-in hasta '. $horaSalidaTS[$xy] .' H-out en rojo</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaEntrada < $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br><span style="color:grey;"> La marca entrada es mayor que la hora de entrada y la marca salida menor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Llega tarde '  , 'tarde', $row['validado'],$row['datetime']);
							array_push($events, $e);
							//echo '<br><span style="color:red;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime'] .' M-in en rojo (llego tarde)</span>'; 
							$e = ArmoCalendario($row['codlog'], $row['datetime'], ' No marca salida '  , 'azul', $row['validado'], $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en verde( cumple horario)</span>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada > $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br><span style="color:grey;"> La marca entrada es mayor que la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'No cumple horario '  , 'tarde', $row['validado'], $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:red;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime'] .' M-in en rojo (llego tarde)</span>'; 
							//Sumo 15 minutos a la marca...
							if($alcamarca2==0){
								$date= new DateTime( $row['datetime']);
								$date->modify('+10 minute');
								$date=$date->format('Y-m-d H:m');

								$e = ArmoCalendario($row['codlog'], $row['datetime'], 'No marca salida ' , 'azul', $row['validado'], $date);
								array_push($events, $e);
							}
							$alcamarca2++;
							//echo '<br><span style="color:green;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en verde( cumple horario)</span>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif(!in_array($xy, $MarcaCumplida)){
							//echo '<br> Muesto el horario como no cumplido';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'No cumple horario ' , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muesto el horario como no cumplido';
							$MarcaCumplida[]=$xy;
						}
					}
				}elseif($pacie['numfilas']>1 and $MarcaSalida!=$DefaultTime){
					//echo '<br> Para mas de una marca y con marca de salida diferencte default: '.$horarioCumplido;
					//echo '<br>Marca entrada '.$row['datetime'].' Salida '.$row['enddatetime'].'<br>';
					//echo '<br> Codlog '.$row['codlog'];
					// $e = ArmoCalendario($row['codlog'], $start, $title, $className, $validado, $end);

					for($xy=$horarioCumplido;$xy<$CantidadHorarios; $xy++){
						//echo '<br>Número de horario: '.$xy .': Hora de entrada: '.$horaIngresoTS[$xy]. ', hora de salida: '.$horaSalidaTS[$xy].'<br>';
						//Si la marca es menor que la hora ingreso
						if($MarcaEntrada < $TSHingreso[$xy] and $MarcaSalida < $TSHingreso[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br><span style="color:grey;"> La marca entrada es menor que la hora de ingreso y la Marca salida es menor que la hora de salida</span> ';
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa temprano ', 'fueradehora', $row['validado'], $row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en azul</span>';
							$e = ArmoCalendario($newId, $horaIngresoTS[$xy], 'No cumple horario ' , 'tarde', ' ', $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:rojo;"> Muestro '. $horaIngresoTS[$xy] . ' H-in hasta '. $horaSalidaTS[$xy] .' H-out en rojo</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada < $TSHingreso[$xy] and $MarcaSalida < $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br><span style="color:grey;"> La marca entrada es menor que la hora de entrada y la marca salida menor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $row['datetime'], ' Ingresa temprano ', 'fueradehora', $row['validado'], $horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $horaIngresoTS[$xy] . ' H-in en azul (llego azul</span>'; 
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], ' Cumple horario '. $row['enddatetime'], 'custom', $row['validado'], $row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['enddatetime'] .' M-out en verde( cumple horario)</span>';
							$e = ArmoCalendario($row['codlog'], $row['enddatetime'], ' Se retira antes ' , 'tarde', $row['validado'], $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:red;"> Muestro desde '. $row['enddatetime'] .' M-out hasta '. $horaSalidaTS[$xy] .' H-out en rojo (se fue antes)</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada < $TSHingreso[$xy] and $MarcaSalida > $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida)){
							//echo '<br><span style="color:grey;"> La marca entrada es menor que la hora de entrada y la marca salida mayor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $row['datetime'], 'Ingresa antes de hora ' , 'azul', $row['validado'], $horaIngresoTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $horaIngresoTS[$xy] . ' H-in en azul (llego azul</span>'; 
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Cumple horario ' , 'custom', $row['validado'], $horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $horaSalidaTS[$xy] .' H-out en verde( cumple horario)</span>';
							$e = ArmoCalendario($row['codlog'], $horaSalidaTS[$xy], 'Se retira fuera de hora '  , 'fueradehora', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro desde '. $horaSalidaTS[$xy] .' H-out hasta '. $row['enddatetime'] .' M-out en azul (se fue tarde)</span><br>';
							
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaSalida < $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br><span style="color:grey;"> La marca entrada es mayor que la hora de entrada y la marca salida menor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime']  , 'tarde', $row['validado'],$row['datetime']);
							array_push($events, $e);
							//echo '<br><span style="color:red;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime'] .' M-in en rojo (llego tarde)</span>'; 
							$e = ArmoCalendario($row['codlog'], $row['datetime'], ' Muestro desde '. $row['datetime'] .' M-in hasta '. $row['enddatetime']  , 'custom', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $row['enddatetime'] .' M-out en verde( cumple horario)</span>';
							$e = ArmoCalendario($row['codlog'], $row['enddatetime'], ' Muestro desde '. $row['enddatetime'] .' M-out hasta '. $horaSalidaTS[$xy]  , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:rojo;"> Muestro desde '. $row['enddatetime'] .' M-out hasta '. $horaSalidaTS[$xy] .' H-out rojo (se fue azul)</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif($MarcaEntrada > $TSHingreso[$xy] and $MarcaSalida > $TSHsalida[$xy] and $MarcaEntrada < $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br><span style="color: grey;"> La marca entrada es mayor que la hora de entrada y la marca salida mayor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Ingresa fuera de hora ' , 'tarde', $row['validado'],$row['datetime']);
							array_push($events, $e);
							//echo '<br><span style="color:rojo;"> Muestro desde '. $horaIngresoTS[$xy] . ' H-in hasta '. $row['datetime'] .' M-in en rojo (llego tarde)</span>'; 
							$e = ArmoCalendario($row['codlog'], $row['datetime'], ' Cumple horario ' , 'custom', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $horaSalidaTS[$xy] .' H-out en verde( cumple horario)</span>';
							$e = ArmoCalendario($row['codlog'], $horaSalidaTS[$xy], 'Sale fuera de hora ' , 'fueradehora', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro desde '. $horaSalidaTS[$xy] .' H-out hasta '. $row['enddatetime'] .' M-out azul (se fue tarde)</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif( $MarcaEntrada > $TSHsalida[$xy] and !in_array($xy, $MarcaCumplida) ){
							//echo '<br><span style="color: grey;"> La marca entrada es mayor que la hora de entrada y la marca salida mayor a la hora de salida </span>';
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], ' Ingresa fuera de hora ' , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br><span style="color:green;"> Muestro desde '. $row['datetime'] .' M-in hasta '. $horaSalidaTS[$xy] .' H-out en verde( cumple horario)</span>';
							$e = ArmoCalendario($row['codlog'],$row['datetime'], 'Cumple horario '  , 'custom', $row['validado'],$row['enddatetime']);
							array_push($events, $e);
							//echo '<br><span style="color:blue;"> Muestro desde '. $horaSalidaTS[$xy] .' H-out hasta '. $row['enddatetime'] .' M-out azul (se fue tarde)</span><br>';
							$MarcaCumplida[]=$xy;
							break;
						}elseif(!in_array($xy, $MarcaCumplida)){
							$e = ArmoCalendario($row['codlog'], $horaIngresoTS[$xy], 'Muesto el horario como no cumplido con alguna marca '. $horaIngresoTS[$xy] .' H-out hasta '. $horaSalidaTS[$xy]  , 'tarde', $row['validado'],$horaSalidaTS[$xy]);
							array_push($events, $e);
							//echo '<br> Muesto el horario como no cumplido';
							$MarcaCumplida[]=$xy;
						}
					}
				}
			}
		}
		}else{
			if($CantidadHorarios>0){
				for($xy=$horarioCumplido;$xy<$CantidadHorarios; $xy++){
					$e = ArmoCalendario($newId, $horaIngresoTS[$xy], 'Muestro no cumplido, sin marcas' , 'tarde', ' ', $horaSalidaTS[$xy]);
					array_push($events, $e);
				}
			}
		}
		//Recorro todos las marcas del día    
		}
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
?>