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
 
include ("../conectar.php"); 
    //get search term
    $data=array();
    $searchTerm = $_GET['term'];
    
    //get matched data from skills table
	$sql="SELECT * FROM  `articulos` WHERE `codarticulo` LIKE '%".$searchTerm."%' or `referencia` LIKE '%".$searchTerm."%' or `descripcion` LIKE '%".$searchTerm."%'";    
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos del tipo estudio");

	while ($registro=mysqli_fetch_array($sqlr)){
			$arr['value'] = substr($registro['descripcion'], 0, 40). ' -Stock:'.$registro['stock'];
        	$arr['data'] = $registro['codfamilia'].'~'.$registro['codigobarras'].'~'.$registro['referencia'].'~'.addslashes(str_replace('"','&quot;',$registro['descripcion'])).'~'.
	       $registro['precio_tienda'].'~'.$registro['codarticulo'].'~'.$registro['moneda'].'~ ~'.
	       $registro['descripcion'].'~'.$registro['comision'].'~'.$registro['stock'];
	       $data[] = $arr;
        
   }
    
    //return json data
    echo json_encode($data);
?>