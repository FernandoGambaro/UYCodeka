<?php

require_once '../library/conector/consultas.php';
use App\Consultas;


if(isset($_GET['id']))
{
$id=$_GET['id'];
$default=$_GET['default'];


$obj = new Consultas('foto');

/*/ Configurar las dos lineas siguientes*/

$obj->Select();
$obj->Where('oid', $id);
$paciente = $obj->Ejecutar();
$datos = $paciente["datos"][0];


    header("Content-type:".$datos['fototype']);
    header('Content-Disposition: inline; filename="'.$datos['fotoname'].'"');
    echo $datos['fotocontent'];			
}
?>