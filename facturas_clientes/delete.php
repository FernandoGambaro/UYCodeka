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
$codcliente = isset($_POST['codcliente']) ? $_POST['codcliente'] : '';
$tabla=$_POST['tabla'];

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined' and strlen($tabla) >0){


	if($tabla=='facturas'){
		$obj = new Consultas('facturas');
		$obj->Delete();
		$obj->Where('codfactura', $cod, '=');
		$hace = _('Borra factura de compra'). $cod. ', cliente '. $codcliente;
		$algo = $obj->Ejecutar();

		$obj = new Consultas('factulinea');
		$obj->Delete();
		$obj->Where('codfactura', $cod, '=');
		$algo = $obj->Ejecutar();

	}	

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito').$algo['consulta'];
		$hace = _('Da de baja articulo: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
	}else{
		$hace = _('Fallo al borrar factura: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
		echo _('Fallo al borrar');
	}
}else{
	echo _('Fallo');
}
?>