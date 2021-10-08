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
	$zk->setTimeout(60,0);
	$zk->connect();

	$zk->disableDevice();
	sleep(1);

	$all_user_info=$zk->getUser();
	/*Extraigo los datos de usuarios y lo almaceno en un array()*/
	
	foreach($all_user_info as $key=>$user){
			
		$obj = new Consultas('biometricuser');
		$obj->Select();	
		$obj->Where('pin', $key, '=');
		$paciente = $obj->Ejecutar();

		$filas=$paciente['numfilas'];
		$ejecutado='';
		if($filas==0) {
			/*el pin corresponde el ID del usuraio en el equipo
			el codusuario corresponde al UID en el equipo ($key)
			name, nombre del usuario
			*/
			$nombres = array();
			$valores = array();
			$nombres[] = 'pin';
			$valores[] = $user[0];
			$nombres[] = 'codusuarios';
			$valores[] = $key;
			$nombres[] = 'name';
			$valores[] = addslashes($user[1]);
			$nombres[] = 'role';
			$valores[] = $user[2];
			$nombres[] = 'password';
			$valores[] = addslashes($user[3]);

			$objnew = new Consultas('biometricuser');
			$objnew->Insert($nombres, $valores);
			$ejecutado=$objnew->Ejecutar();

			//$sql="INSERT INTO `biometricuser` (`codbiometricuser`, `pin`, `codusuarios`, `name`, `role`, `password`) VALUES
			//( null, '$user[0]', '$key',   'addslashes($user[1])', '$user[2]', 'addslashes($user[3])');";

		} else {
			$nombres = array();
			$valores = array();
			$nombres[] = 'codusuarios';
			$valores[] = $key;
			$nombres[] = 'name';
			$valores[] = addslashes($user[1]);
			$nombres[] = 'role';
			$valores[] = $user[2];
			$nombres[] = 'password';
			$valores[] = addslashes($user[3]);
					
			$objnew = new Consultas('biometricuser');
			$objnew->Update($nombres, $valores);
			$objnew->Where('pin', $user[0], '=');
			$ejecutado=$objnew->Ejecutar();

			//$sql="UPDATE `biometricuser` SET  `name` = '". addslashes($user[1])."', `role` = '".$user[2]."',  `password` = '". addslashes($user[3])."' 
			//WHERE `biometricuser`.`pin` = '".$key."'";
		}
				//$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $sql);

				if ($ejecutado=['estado']=='ok') {
					$nombres = array();
					$valores = array();
					$nombres[] = 'pin';
					$valores[] = $key;
					$nombres[] = 'role';
					$valores[] = $user[2];
							
					$objnew = new Consultas('usuarios');
					$objnew->Update($nombres, $valores);
					$objnew->Where('codusuarios', $key, '=');
					$ejecutado=$objnew->Ejecutar();

					//$sql_user="UPDATE `usuarios` SET `pin` = '".$key."', `role` = '".$user[2]."' WHERE `usuarios`.`codusuarios` = '".$key."';";
					//$rs_bus=mysqli_query($GLOBALS["___mysqli_ston"], $sql_user);
				} 
			$sql='';
			$x=0;
			
			$template= array();
			while($x<9){
				$template=$zk->getUserTemplate($key, $x);
				//var_dump($template);

					$query_busqueda="SELECT count(*) as filas FROM `biometricusertemplate` WHERE `pin`='".$key."' AND `fingerid`='".$x."'";
					$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
					$filas=mysqli_result($rs_busqueda, 0, "filas");
					
						if($filas==0 ) {
							//array($template_size, $uid, $finger, $valid, $user_data);
					$sql="INSERT INTO `biometricusertemplate` (`codtemplate`, `size`, `pin`, `fingerid`, `valid`,  `template`) VALUES
							( null,  '".$template[0]."',  '".$template[1]."',  '".$x."', '".$template[3]."', '".mysqli_real_escape_string($GLOBALS["___mysqli_ston"],$template[4])."');";
						} elseif($filas>0) {
						$sql="UPDATE `biometricusertemplate` SET  `size` = '". $template[0]."', `valid` = '". $template[3]."', `template` = '". mysqli_real_escape_string($GLOBALS["___mysqli_ston"],$template[4])."' 
							WHERE `pin` = '".$key."' AND `fingerid`='". $x."'";
						}
						
						if($template[3]<>'') {
								$rs_opera=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
								if ($rs_opera) { $data="ok"; } else { $data="error";}
						}
						
					//}
					
					$x++;
			}
			
	}
	
$zk->enableDevice();
$zk->disconnect();
}

    echo json_encode($data);
    flush();  

?>