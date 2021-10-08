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
$codequipo = isset($_GET['codequipo']) ? $_GET['codequipo'] : '';

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($codequipo != 'undefined'){

$obj = new Consultas('equipos');
$nom[] = 'borrado';
$val[] = 1;
$obj->Update($nom, $val);
$obj->Where('codequipo', $codequipo);
$algo= $obj->Ejecutar();
//echo $algo['consulta'];

$oidestudio = '';
$oidpaciente = '';
$hace = 'Borra equipo '. $codequipo;

logger($UserID, $oidestudio, $oidpaciente, $hace);

}
?>
<script>parent.$('idOfDomElement').colorbox.close()</script>