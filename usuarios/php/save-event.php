<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors


require_once __DIR__ .'/../../common/funcionesvarias.php';
require_once __DIR__ .'/../../classes/class_session.php';
require_once __DIR__ .'/../../common/verificopermisos.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
 }
  
  $UserID=$s->data['UserID'];
 header('Content-Type: application/json');
 $events=array();
 $e=array();
 $error = 0;
 
	$nombres = array();
	$valores = array();
if($_POST['action']!='borrar'){
	if(strlen($_POST["start"])>0){
		$date = DateTime::createFromFormat('d/m/Y H:i:s', $_POST["start"]);
		$start=$date->format('Y-m-d H:i:s');
	}else{
		$e['view']='Fecha inicio no válida';
		array_push($events, $e);
		$error=1;
	}
	if(strlen(($_POST["end"]))>0){
		$date = DateTime::createFromFormat('d/m/Y H:i:s', $_POST["end"]);
		$end=$date->format('Y-m-d H:i:s');
	}else{
		$e['view']='Fecha fin no válida';
		array_push($events, $e);
		$error=1;
	}

	$nombres[] = 'codusuarios';
	$valores[] = $_POST["codusuarios"];
//	$nombres[] = 'pin';
//	$valores[] = $_POST["pin"];
	$nombres[] = 'state';
	$valores[] = '1';
	$nombres[] = 'datetime';
	$valores[] = $start;
	$nombres[] = 'enddatetime';
	$valores[] = $end;
	if($_POST['comentario']!='[object Object]'){
		$nombres[] = 'comentario';
		$valores[] = $_POST['comentario'];
	}
	
	if(strlen($_POST['descripcion'])>0){
		$nombres[] = 'descripcion';
		$valores[] = $_POST['descripcion'];
	}
	if(strlen($UserID)>0){
		$nombres[] = 'usuariocod';
		$valores[] = $UserID;
	}
}

if($error==0 or $_POST['action']=='borrar'){ 
 	switch($_POST['action']) {
	case "add":     
		if(strtotime($end)>strtotime($start)) {

			$nombres[] = 'validado';
			$valores[] = '1';
			
			$obj = new Consultas('biometriclog');

			$obj->Insert($nombres, $valores);
			//var_dump($obj);
			$paciente = $obj->Ejecutar();
			$e['view']=$paciente['consulta'];
			$e['id'] = $paciente['id'];
			array_push($events, $e); 			        
		}else{
			$e['view']='error';
			array_push($events, $e); 			        
		}
	break;
	case "upd":				
			$obj = new Consultas('biometriclog');

			$obj->Update($nombres, $valores);
			$obj->Where('codlog', $_POST['eventID'], '=');
			//var_dump($obj);
			$paciente = $obj->Ejecutar();
			$e['id'] = $_POST['eventID'];

			$e['className']="custom";
			$e['view']=$paciente['consulta'];
			
			array_push($events, $e);  
	break;
	case "validar":    
			if($_POST['eventID']!='null' ) {
				$nombres = array();
				$valores = array();

				$nombres[] = 'validado';
				$valores[] = '1';
			
				$obj = new Consultas('biometriclog');
				$obj->Update($nombres, $valores);
				$obj->Where('codlog', $_POST['eventID'], '=');
				//var_dump($obj);
				$paciente = $obj->Ejecutar();
				$e['id'] = $_POST['eventID'];

			}else{
				$nombres[] = 'validado';
				$valores[] = '1';
			
				$obj = new Consultas('biometriclog');
				$obj->Insert($nombres, $valores);
				//var_dump($obj);
				$paciente = $obj->Ejecutar();
				$e['id'] = $paciente['id'];				
			}
			$e['className']="custom";
			$e['view']=$paciente['consulta'];
			$e['className']="custom";
			
			array_push($events, $e);  			        
	break;
	case "novalidar":    
			if($_POST['eventID']!='null') {

				$nombres = array();
				$valores = array();
				$nombres[] = 'validado';
				$valores[] = '0';

				$obj = new Consultas('biometriclog');
				$obj->Update($nombres, $valores);
				$obj->Where('codlog', $_POST['eventID'], '=');
				//var_dump($obj);
				$paciente = $obj->Ejecutar();
				$e['id'] = $_POST['eventID'];

				$e['className']="custom";
				$e['view']=$paciente['consulta'];
			}
			//$e['id']=$_POST['eventID'];
			array_push($events, $e);  			        
	break;			
	case 'ajustofecha': 

		$obj = new Consultas('biometriclog');
		$obj->Update($nombres, $valores);
		$obj->Where('codlog', $_POST['eventID'], '=');
		//var_dump($obj);
		$paciente = $obj->Ejecutar();
		$e['id'] = $_POST['eventID'];

		$e['className']="custom";

		if($paciente['estado']){
			$estado="ok";
			} else { 
			$estado="fallo";
			}
			$e['estado']=$estado;
			$e['view']=$paciente['consulta'];
			array_push($events, $e);  			        
	break; 
	case 'borrar': 
		$nombres = array();
		$valores = array();
		
		$nombres[] = 'borrado';
		$valores[] = '1';

		$obj = new Consultas('biometriclog');
		$obj->Update($nombres, $valores);
		$obj->Where('codlog', $_POST['eventID'], '=');
		//var_dump($obj);
		$paciente = $obj->Ejecutar();

		if($paciente['estado']){
			$estado="ok";
			} else { 
			$estado="fallo";
			}
			$e['estado']=$estado;
			$e['view']=$paciente['consulta'];
			$e['id'] = $_POST['eventID'];
			array_push($events, $e); 
	break;			
	}
}
 
echo  json_encode($events);
?>