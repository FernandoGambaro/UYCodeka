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
$codusuarios = isset($_POST['cod']) ? $_POST['cod'] : '';

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($codusuarios != 'undefined'){

$obj = new Consultas('usuarios');
$nom[] = 'estado';
$val[] = 1;
$obj->Update($nom, $val);
$obj->Where('codusuarios', $codusuarios);
$algo= $obj->Ejecutar();
//echo $algo['consulta'];

$oidestudio = '';
$oidpaciente = '';

	if ( $algo['estado']=='ok' ){ 
		echo "Borrado con exito";
		$hace = 'Borra usuario'. $codusuarios;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
	}else{
		$hace = 'Fallo al borra usuario'. $codusuarios;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
		echo "Fallo al borrar";
	}
}
?>
