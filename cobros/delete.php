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

	if($tabla=='recibos'){
		$obj = new Consultas('recibos');
		$obj->Delete();
		$obj->Where('codrecibo', $cod, '=');
		$algo = $obj->Ejecutar();

		$obj = new Consultas('recibosfactura');
		$obj->Delete();
		$obj->Where('codrecibo', $cod, '=');
		$algo = $obj->Ejecutar();

		$obj = new Consultas('recibospago');
		$obj->Delete();
		$obj->Where('codrecibo', $cod, '=');
		$algo = $obj->Ejecutar();

		$hace = _('Borra recibo'). $cod;
	}	

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito').$algo['consulta'];
		$hace = _('Elimina recibo: '). $cod;
		logger($UserID, '', '', $hace);
	}else{
		$hace = _('Fallo al eliminar recibo '). $cod;
		logger($UserID, '', '', $hace);
		echo _('Fallo al borrar');
	}
}else{
	echo _('Fallo');
}
?>