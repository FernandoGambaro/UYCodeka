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
$codproveedor = isset($_POST['codproveedor']) ? $_POST['codproveedor'] : '';
$tabla=$_POST['tabla'];

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined' and strlen($tabla) >0){

	$obj = new Consultas($tabla);
	if($tabla=='facturasp'){
		$nom[] = 'borrado';
		$val[] = 1;
		$obj->Update($nom, $val);
		$obj->Where('codfactura', $cod, 'LIKE');
		$obj->Where('codproveedor', $codproveedor);
		$hace = _('Borra factura de compra'). $cod. ', proveedor '. $codproveedor;
	}	
	$algo = $obj->Ejecutar();

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito').$algo['consulta'];
		$hace = _('Da de baja articulo: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
	}else{
		$hace = _('Fallo dar de baja articulo: '). $cod;
		logger($UserID, $oidestudio, $oidpaciente, $hace);
		echo _('Fallo al borrar');
	}
}else{
	echo _('Fallo');
}
?>