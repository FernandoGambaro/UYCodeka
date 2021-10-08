<?php


ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../common/fechas.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$fecha = $_GET['fecha'];


$obj = new Consultas('tipocambio');

$obj->Select();
$obj->Where('fecha', explota($fecha), '<');
$obj->Orden('fecha', 'DESC');



$paciente = $obj->Ejecutar();
//echo $paciente["consulta"];
$arr= $data = array();
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"][0];

echo $rows['valor'];
   
?>