<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors
require_once __DIR__ .'/../classes/class_session.php';


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
}

  
$UserID=$s->data['UserID'];

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('usuarios');

//if(isset($_POST['change_color']) and $UserID !='') {
// Write update MySQL query to update background color in MySQL database table
$nombres[] = 'menucolor';
$valores[] = $_POST['background'];
$obj->Update($nombres, $valores);
$obj->Where('codusuarios', $UserID);
$datos_sistema = $obj->Ejecutar();

$s->data['MenuColor'] = $_POST['background'];
$s->save();
//}
?>