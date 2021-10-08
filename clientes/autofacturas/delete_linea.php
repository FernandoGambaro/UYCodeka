<?php
require_once __DIR__ .'/../../common/funcionesvarias.php';

// isset() is a PHP function used to verify if ID is there or not
$cod = isset($_POST['cod']) ? $_POST['cod'] : '';
$linea=$_POST['linea'];

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

if($cod != 'undefined' and strlen($linea) >0){

	$nombres = array();
	$valores = array();
	$nombres[] = "borrado";
	$valores[] = '1';


	$obj = new Consultas('autofactulineatmp');

    $obj->Delete();
    $obj->Where('codautofactura', $cod);
    $obj->Where('numlinea', $linea);
    $algo = $obj->Ejecutar();

	if ( $algo['estado']=='ok' ){ 
		echo _('Borrado con exito');
	}else{
		echo _('Fallo al borrar');
	}
}
?>