<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */


ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$searchTerm = $_GET['term'];


$obj = new Consultas('plandecuentas');

$obj->Select();
if(is_numeric($searchTerm)) {
    $obj->Where('codplan', $searchTerm, '>=', );
}else{
    $obj->Where('nombre', $searchTerm, 'LIKE' );
}
    $obj->Where('borrado','0' );


$paciente = $obj->Ejecutar();
//echo $paciente["consulta"];
$arr= $data = array();
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

if($total_rows>=0){

	foreach($rows as $row){
			$nombre=$row['nombre'];
			$arr['value'] = substr($row['nombre'], 0, 20);

            $arr['data'] = $row['codplan'].'~'.$row['nombre'];
	       $data[] = $arr;
	}
}

    //return json data
    echo json_encode($data);
    flush();    
    
?>