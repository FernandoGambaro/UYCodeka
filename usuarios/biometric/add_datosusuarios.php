<?php
/*
Agrego o quito usuarios de los equipos biometricos
Los usuarios que son deseleccionados de sistema para gestionar en los equipos 
*/
require_once __DIR__ .'/../../common/funcionesvarias.php';

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

$codbiometric = isset($_GET['codbiometric']) ? $_GET['codbiometric'] : $_POST['codbiometric'];
$mensaje='';

function availableUrl($host, $port=80, $timeout=10) { 
	$fp = fSockOpen($host, $port, $errno, $errstr, $timeout); 
	return $fp!=false;
}
header('Content-Type: application/json');
date_default_timezone_set('Americas/Montevivideo'); //Default Timezone Of Your Country
$events=array();
$e=array();
 
include 'zklib/zklib/ZKLib.php';

$obj = new Consultas('biometric');
$obj->Select('direccionip,udp_port');	
$obj->Where('codbiometric', $codbiometric, '=');
$paciente = $obj->Ejecutar();

$row=$paciente['datos'][0];

$ip=$row["direccionip"];
$udp_port=$row["udp_port"];
$quitados=0;
$agregados=0;
$fallos=0;
$actualizados=0;
$numerouid=$numeroname=$userid=array();


if(availableUrl($ip, $udp_port)==1) {

$zk = new ZKLib($ip, $udp_port );

$ret = $zk->connect();
	if ($ret) {

		$zk->disableDevice();
		$zk->setTime(date('Y-m-d H:i:s')); // Synchronize time

		$obj = new Consultas('biometricuser');
		$obj->Select();
		//$obj->Where('borrado','0', '='); No lo uso pues si se borra un usuario no se actualizaria en el reloj
		$obj->Orden('codusuarios', 'ASC');
		$pac = $obj->Ejecutar();
		//echo $pac['consulta'];
		$Datosobtenidos = $pac["datos"];

		foreach($Datosobtenidos as $fila){
		
			$users = $zk->getUser();
			
			foreach ($users as $uItem) {
				$userid[(int)$uItem['userid']] = (int)$uItem['userid'];
				$numeroname[(int)$uItem['userid']]=$uItem['name'];
				$numerouid[(int)$uItem['userid']] = (int)$uItem['uid'];
				$numerorole[(int)$uItem['userid']] = (int)$uItem['role'];
			}
			ksort($userid);
			//print_r($numerorole);
			/*
			$pin = (int)array_keys($userid)[count($userid)-1];
		*/

			$exito=0;$quito=0;$accion=0;$finish=0;$ret=false;
			$nombres = array();
			$valores = array();

			$codusuarios = $fila['codusuarios'];
			$name=remove_accents($fila['name']);
			$role=$fila['role'];
			//echo '<br>'. $fila['pin'] . ' /// ' . $fila['role'].'<br>';
			//sleep(1);
			if($fila['borrado']==1 and $role!=14 and strlen(trim($fila['pin']))>0 and $fila['codusuarios']!=1){
				//Borro usuario del reloj menos aquellos que sean administradores.
				$ret=$zk->removeUser($codusuarios);
				//echo 'quito.<br>';
				//var_dump($ret);
				if($ret!==false){
					$quito==1;
					$quitados++;
					$exito=1;
					$pin='';
					$password='';	
				}
				//$zk->testVoice(32);
			}elseif($fila['borrado']==0){
				//Actualizao los datos de los usuarios en el reloj


				if( strlen(trim($fila['pin']))>0){
					if($fila['pin']== $userid[$codusuarios]
						and remove_accents($fila['name']) == $numeroname[$codusuarios]
						and $fila['codusuarios']==$numerouid[$codusuarios]
						and $fila['role'] == $numerorole[$codusuarios] ){
						$finish=1;
					}
					$pinnum=$codusuarios;//$fila['pin'];
					$password=$fila['password'];
					$role=$fila['role'];
					$accion=1;
					
					
				}else{
					$pinnum=$codusuarios; //++;
					$password="123456";
					$role=0;
					$accion=2;
				}
				//echo '<br> '.$finish. '<br>';
				if($finish==0){
					try {
						
						//setUser($uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
						$ret=$zk->setUser($codusuarios, (int)$codusuarios, $name, $password, $role, 00000);
						//echo 'agrego<br>';
						//var_dump($ret);
						if($ret!==false){
							//echo '<br>tttt '. $codusuarios. ' -#- '. $pinnum. ' -#- '. $name. ' -#- '. $password. ' -#- '. $role;
							if($accion==1){
								$actualizados++;
							}elseif($accion==2){
								$agregados++;
							}
							//$zk->testVoice(0);
						}
						sleep(1);
						$pin=$pinnum;
						$exito=1;
					} catch (Exception $e) {
						$fallos++;
						//$zk->testVoice(30);
						break;
					}
				}
			}	
			if($exito==1){
				$nombres[] = 'pin';
				if ($quito==1){
					$valores[] = 'NULL';
				}else{
					$valores[] = $pin;
				}

				$objnew = new Consultas('usuarios');
				$objnew->Update($nombres, $valores);
				$objnew->Where('codusuarios', $fila['codusuarios'], '=');
				$objnew->Ejecutar();

				$nombres[] = 'password';
				if ($quito==1){
					$valores[] = 'NULL';
				}else{
					$valores[] = $password;
				}
				

				$objnew = new Consultas('biometricuser');
				$objnew->Update($nombres, $valores);
				$objnew->Where('codusuarios', $fila['codusuarios'], '=');
				$objnew->Ejecutar();
			}		
		}
	$zk->enableDevice();
	$zk->disconnect();
	}
}

$e['quitados']= $quitados;
$e['agregados'] = $agregados;
$e['fallos'] = $fallos;
$e['actualizados'] = $actualizados;

array_push($events, $e); 

echo json_encode($events);
?>