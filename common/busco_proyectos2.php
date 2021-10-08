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
    $codcliente= $_POST['codcliente'];
    $where=" 1=1 ";
    if($codcliente<>'') {
    	$where.= " and `codcliente`='".$codcliente."'";
    }
 $data =array();
	$sql="SELECT * FROM `proyectos` WHERE ".$where . " and borrado=0";     
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos de proyectos");


	while ($registro=mysqli_fetch_array($sqlr)){
			$arr['descripcion'] = substr($registro['descripcion'], 0, 20);
        	$arr['id'] = $registro['codproyectos'];
	      $data[] = $arr;
   }
    
    //return json data
    echo json_encode($data);
    flush();    
  
?>