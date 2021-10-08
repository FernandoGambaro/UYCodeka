<?php
require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

include ("../../funciones/fechas.php");
header('Content-Type: application/json');
date_default_timezone_set('America/Montevideo');

$e=array();
$events=array();

$codusuarios=$_POST['codusuarios'] ? $_POST['codusuarios'] : $_GET['codusuarios'];
$start=$_POST['fechafin'] ? $_POST['fechafin'] : $_GET['fechafin'] ;
//$start=explota($start);

$diasemana=date("w", strtotime($start));


if(strlen($codusuarios)>0){
  $obj = new Consultas('horariousuario');
  $obj->Select();
  $obj->Where('diasemana', $diasemana, 'LIKE' );
  $obj->Where('codusuarios', $codusuarios, '=' );
  //$obj->Where('vigencia', date("Y-m-d"), '<=' );
  $obj->Orden('vigencia', 'DESC');
  //var_dump($obj);
  $paciente = $obj->Ejecutar();
  $e['consulta']=$diasemana. ' ooo '.$start.' gggg '.$paciente['consulta'];
  $rows=$paciente['datos'];

  $e['horario']='<tr><td>'.diasemana($start).'</td></tr><tr> ';
  $e['cumplir']='';
  foreach($rows as $row){
    $e['horario'].='<td >'.substr($row['horaingreso'],0, 5)."-".substr($row['horasalida'],0, 5).' </td><td>&nbsp;</td> ';
    $e['cumplir'].=substr($row['horaingreso'],0, 5)."h - ".substr($row['horasalida'],0, 5).'h, ';
  }
  $e['horario'].='</tr>';
}else{
  $e['error']='Error, usuario no válido '. $codusuarios;
}

			array_push($events, $e);
 //Fin de recorrer horario
  // var_dump($events);
   echo  json_encode($events);
