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

$searchTerm = isset($_GET['term']) ? $_GET['term'] : $_POST['term'];


$obj = new Consultas('articulos');

$obj->Select('codarticulo, descripcion, referencia, datos_producto, precio_compra, moneda, codfamilia, precio_tienda');
$obj->Where('referencia', $searchTerm, 'LIKE', '' );
$obj->Where('descripcion', $searchTerm, 'LIKE', 'or' );
$obj->Where('borrado','0' );
$obj->Limit(0,5);



$paciente = $obj->Ejecutar();
//echo $paciente["consulta"];
$arr=$data=[];
//$data['consulta'] =$paciente["consulta"];
$total_rows=$paciente["numfilas"];
$rows = $paciente["datos"];

//var_dump($rows);

//if($total_rows>=0){
	foreach($rows as $row){
		$arr['value'] = substr($row['descripcion'], 0, 30);
		$arr['data'] = $row['codarticulo'].'~'.$row['referencia'].'~'.$row['descripcion'].'~'.$row['datos_producto'].'~'.$row['precio_compra'].'~'.$row['moneda'].'~'.$row['codfamilia'].'~'.$row['precio_tienda'];
		$data[] = $arr;
	}
//}

    //return $data;
	//var_dump($data);
    echo json_encode($data);
    flush();    
    
?>