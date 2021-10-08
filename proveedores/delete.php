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

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined'){

$obj = new Consultas('proveedores');
$nom[] = 'borrado';
$val[] = 1;
$obj->Update($nom, $val);
$obj->Where('codproveedor', $cod);
$algo= $obj->Ejecutar();
//echo $algo['consulta'];

$oidestudio = '';
$oidpaciente = '';
$hace = 'Borra proveedor'. $cod;

logger($UserID, $oidestudio, $oidpaciente, $hace);

if ( $algo['estado']=='ok' ){ 
	echo "Borrado con exito";
}else{
	echo "Fallo al borrar";
}
}
?>
