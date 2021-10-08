<?php

require_once '../prueba/consultas.php';
use App\Consultas;

// prevent direct access
/*
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND
strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if(!$isAjax) {
  $user_error = 'Access denied - not an AJAX request...';
  trigger_error($user_error, E_USER_ERROR);
}
*/

$search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
$replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");


$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
$json_invalid = json_encode($a_json_invalid);
 
$tratamiento = $_REQUEST['trata'];
$level = $_REQUEST['level'];

$term = trim($_GET['term']);
$term = preg_replace('/\s+/', ' ', $term);
 
// replace multiple spaces with one
$term = preg_replace('/\s+/', ' ', $term);
 
// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
/*
if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
  print $json_invalid;
  exit;
}
*/
$parts = explode(' ', $term);

  $arr = $data = array();
 
/*
  $arr['value'] = $tratamiento;
  $arr['data'] = $level."~".$tratamiento;
  array_push($data, $arr);
*/  

if($level=='tipoestudio') {
    $obj = new Consultas('tipoestudio');
    $obj->Select();
    $obj->Where('descripcion', $term, 'LIKE' );
    $obj->Orden("descripcion" , "ASC");
    $obj->Limit(0, 10);
    
    $datos = $obj->Ejecutar();
    $rows = $datos["datos"];
    foreach($rows as $row){
        $arr['value'] = $row['descripcion'];
        $arr['data'] = $row['oid']."~".$row['descripcion'];
        array_push($data, $arr);
    } 
} else {
    $obj = new Consultas('contactos');
    $obj->Select();
    if($level == 'mutualista'){
        $obj->Where('tratamiento', '9');
        $obj->Where('nombre', $term, 'LIKE' );
        $datos = $obj->Ejecutar();
        $rows = $datos["datos"];

    } else {
        //Tecnico y otros
        if($tratamiento==4){
            //Médico
            $obj->Where('tratamiento', $tratamiento, '=', 'OR', '(');
            $obj->Where('tratamiento', '3', '=', 'OR');
            $obj->Where('tratamiento', '10', '=', 'OR', ')');
        }elseif($tratamiento==6){
            //Enfermero
            $obj->Where('tratamiento', $tratamiento, '=', 'OR', '(');
            $obj->Where('tratamiento', '12', '=', 'OR', ')');
        }elseif($tratamiento==7){
            //Anestesista
            $obj->Where('tratamiento', $tratamiento, '=', 'OR');
            $obj->Where('tratamiento', '11', '=', 'OR' );
        }else {
            $obj->Where('tratamiento', $tratamiento, '=', 'OR');
        }
        $obj->Where('nombre', $term, 'LIKE', 'AND' , '(' );
        $obj->Where('apellido', $term, 'LIKE', 'OR', ')' );

        $obj->Where('estado', '0', '=', 'AND', '(');
        $obj->Where('estado', 'IS NULL', 'IS NULL', 'OR', ')' );
        $obj->Limit(0, 15);
    
        $datos = $obj->Ejecutar();
        $rows = $datos["datos"];            
    }
    foreach($rows as $row){
        $arr['value'] = $row['nombre']." ".$row['apellido'];
        $arr['data'] = $row['oid']."~".$row['nombre']." ".$row['apellido'];
        array_push($data, $arr);
    } 
}

//$a_json = apply_highlight($data, $parts);
 
$json = json_encode($data);
print $json;
exit;
?>