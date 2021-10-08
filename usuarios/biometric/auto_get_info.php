<?php

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

function availableUrl($host, $port=80, $timeout=10) { 
	$fp = fSockOpen($host, $port, $errno, $errstr, $timeout); 
	return $fp!=false;
  }

include 'zklib/zklib/ZKLib.php';

header('Content-Type: application/json');
 $events=array();
 $e=array();



$obj = new Consultas('biometric');
$obj->Select();	
$obj->Where('activo', '1', '=');
$paciente = $obj->Ejecutar();

$rows=$paciente['datos'];

foreach($rows as $row){

	$registros='error';
	$data=$procesados=0;
	$ip=$row["direccionip"];
	$udp_port=$row["udp_port"];
	
	if(availableUrl($ip, $udp_port)==1) {


		$zk = new ZKLib($ip, $udp_port, 'UDP');

		$data = $zk->ping();	
		$e['estado']=$data;

		if ($zk->ping()>=0){
		sleep(1);
		$zk->setTimeout(60);
		$zk->connect();
		$zk->disableDevice();
		$attendance =$zk->getAttendance();
		$procesados=count($attendance);
		$registros=0;
		//var_dump($attendance );
				
		foreach($attendance as $attItem){
			$obj = new Consultas('biometriclog');
			$obj->Select();	
			$obj->Where('codbiometric', $row['codbiometric'], '=');
			$obj->Where('codusuarios', $attItem['id'], '=');
			$obj->Where('datetime', $attItem['timestamp'], '=');
			$paciente = $obj->Ejecutar();

			$filas=$paciente['numfilas'];
			
				if($filas==0) {
				
				$nombres = array();
				$valores = array();
				$nombres[] = 'codbiometric';
				$valores[] = $row['codbiometric'];
				$nombres[] = 'codusuarios';
				$valores[] = $attItem['id'];
				$nombres[] = 'uid';
				$valores[] = $attItem['uid'];
				$nombres[] = 'state';
				$valores[] = $attItem['state'];
				$nombres[] = 'comentario';
				$valores[] = ' ';
				$nombres[] = 'datetime';
				$valores[] = $attItem['timestamp'];

				$objnew = new Consultas('biometriclog');
				$objnew->Insert($nombres, $valores);
				$objnew->Ejecutar();
				$registros++;
				}
			}
			//$zk->clearAttendance();
		$zk->enableDevice();
		$zk->disconnect();
		}
	}
}
?>