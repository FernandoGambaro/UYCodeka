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
$codproyecto = isset($_GET['codproyecto']) ? $_GET['codproyecto'] : '';

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($codproyecto != 'undefined'){

$obj = new Consultas('proyectos');
$nom[] = 'borrado';
$val[] = 1;
$obj->Update($nom, $val);
$obj->Where('codproyectos', $codproyecto);
$algo= $obj->Ejecutar();
//echo $algo['consulta'];

$oidestudio = '';
$oidpaciente = '';
$hace = 'Borra proyecto'. $codproyecto;

logger($UserID, $oidestudio, $oidpaciente, $hace);

}
?>
<script>parent.$('idOfDomElement').colorbox.close()</script>