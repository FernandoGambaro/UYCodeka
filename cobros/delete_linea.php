<?php
require_once __DIR__ .'/../common/funcionesvarias.php';

// isset() is a PHP function used to verify if ID is there or not
$cod = isset($_POST['cod']) ? $_POST['cod'] : '';
$codrecibo=$_POST['codrecibo'];
$tabla=isset($_POST['tabla']) ? $_POST['tabla'] : '';

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined' and strlen($tabla) >0){

	$obj = new Consultas($tabla);

	$obj->Delete();
	if($tabla=='recibosfacturatmp'){
		$obj->Where('codfactura', $cod);
	}
	if($tabla== 'recibospagotmp'){
		$obj->Where('	codrecibopago', $cod);
		$obj->Where('	codrecibo', $codrecibo);
	}
    $algo = $obj->Ejecutar();

if($tabla=='recibosfacturatmp'){
	$nombres = array();
	$valores = array();
	$nombres[] = 'estado';
	$valores[ ]= '1';
	$objTmp = new Consultas('facturas');
	$objTmp->Update($nombres, $valores);
	$objTmp->Where('codfactura', $cod);
	$resultado=$objTmp->Ejecutar();
}


	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito');
	}else{
		echo _('Fallo al borrar');
	}
}
?>