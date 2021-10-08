<?php
//Defino una variable para cargar solo lo necesario en la seccion que corresponde
//siempre antes de cargar el geader.php
$section='Borro';

require_once __DIR__ .'/../common/funcionesvarias.php';
require_once __DIR__ .'/../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }
  
  $UserID=$s->data['UserID'];


// isset() is a PHP function used to verify if ID is there or not
$cod = isset($_POST['cod']) ? $_POST['cod'] : '';
$tabla=$_POST['tabla'];

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined' and strlen($tabla) >0){

	$obj = new Consultas($tabla);
	if($tabla=='clientes'){
		$nom[] = 'borrado';
		$val[] = 1;
		$obj->Update($nom, $val);
			$obj->Where('codcliente', $cod);
		$hace = _('Borra usuario'). $cod;
	}elseif($tabla=='proyectos'){
		$nom[] = 'borrado';
		$val[] = 1;
		$obj->Update($nom, $val);
			$obj->Where('codproyectos', $cod);
		$hace = _('Borra proyecto'). $cod;
	}
	elseif($tabla=='equipos'){
		$nom[] = 'borrado';
		$val[] = 1;
		$obj->Update($nom, $val);
			$obj->Where('codequipo', $cod);
		$hace = _('Borra equipo'). $cod;
	}	
	$algo = $obj->Ejecutar();

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito');
		$hace = _('Borra cliente: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
	}else{
		$hace = _('Fallo dar de baja cliente: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
		echo _('Fallo al borrar');
	}
}
?>