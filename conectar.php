<?php
 //  UYCODEKA
 //  Copyright (C) MCC (http://www.mcc.com.uy)
 // 
 //  Este programa es software libre: usted puede redistribuirlo y/o
 //  modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 //  publicada por la Fundación para el Software Libre, ya sea la versión
 //  3 de la Licencia, o (a su elección) cualquier versión posterior de la
 //  misma.
 // 
 //  Este programa se distribuye con la esperanza de que sea útil, pero
 //  SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 //  MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 //  Consulte los detalles de la Licencia Pública General Affero de GNU para
 //  obtener una información más detallada.
 // 
 //  Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 //  junto a este programa.
 //  En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 // 

include "configuro.php"; 
////header('Content-Type: text/html; charset=UTF-8'); 

  $conexion=($GLOBALS["___mysqli_ston"] = mysqli_connect(@$Servidor, @$Usuario, @$Password)) or die("Error: El servidor no puede conectar con la base de datos");
  $descriptor=mysqli_query($conexion, "USE ". $BaseDeDatos);

if (!$descriptor) {
    printf("Error: %s\n", mysqli_error($conexion));
    exit();
}
	mysqli_set_charset($conexion, "utf-8");
	mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8"); //Soluciona el tema de las ñ y los tildes


function mysqli_result($res,$row=0,$col=0){ 
    $numrows =mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

?>