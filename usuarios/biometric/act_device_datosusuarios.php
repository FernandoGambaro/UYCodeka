<?php
require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

$codbiometric = isset($_GET['codbiometric']) ? $_GET['codbiometric'] : $_POST['codbiometric'];
$mensaje='';

function availableUrl($host, $port=80, $timeout=10) { 
	$fp = fSockOpen($host, $port, $errno, $errstr, $timeout); 
	return $fp!=false;
}

include "zklibrary.php";

$codbiometric=$_POST['codbiometric'];

$obj = new Consultas('biometric');
$obj->Select('direccionip,udp_port');	
$obj->Where('codbiometric', $codbiometric, '=');
$paciente = $obj->Ejecutar();

$row=$paciente['datos'][0];

$registros='error';
$data=$procesados=0;
$ip=$row["direccionip"];
$udp_port=$row["udp_port"];
 

$zk = new ZKLibrary($ip, $udp_port);
if ($zk->ping()>=0){
	$zk->setTimeout(60);
	$zk->connect();
	$zk->disableDevice();
	//$zk->clearData();
	sleep(1);

	$obj = new Consultas('biometricuser');
	$obj->Select();	
	$obj->Where('borrado', '0', '=');
	$paciente = $obj->Ejecutar();

	$rows=$paciente['datos'];

		foreach($rows as $row) { 
			$pin=$row["pin"];
			$password=$row["password"];
			$name=$row["name"];
			$codusuarios=$row["codusuarios"];
			$role=$row["role"];
			$contador++;
			$zk->setUser( $codusuarios, $pin, $name, $password, $role);
			/*
				for($x=0;$x<9;$x++) {
					$zk->deleteUserTemp($pin, $x);
				}*/
				}

	$zk->testVoice();
	$zk->enableDevice();
	$zk->disconnect();
}
$data = $zk->ping();	

echo json_encode($data);
?>