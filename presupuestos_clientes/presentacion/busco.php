<?php
header('Content-Type: text/html; charset=ISO-8859-1');

include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

include ("encrypt_decrypt.php"); 

$empresa=$_GET['empresa'];
$nombre=$_GET['nombre'];
$apellido=$_GET['apellido'];
$usuario=$_GET['usuario'];
$documento=$_GET['documento'];
$fecha=$_GET['fecha'];

function strtoentity($input)
{
	$largo=strlen($input);
	for($x=0; $x<=$largo; $x++)  
    { 
    	$resultado = substr($input, $x, 1);
      $output .=  ord($resultado).','; 
    }
    return $output;
}
$text = array("[empresa]", "[nombre]", "[apellido]", "[usuario]", "[documento]", "[fecha]"); 
$replace = array($empresa, $nombre, $apellido, $usuario, $documento, $fecha);
$sql="";
// PHP5 Implementation - uses MySQLi.// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
//header('Content-Type: text/html; charset=UTF-8'); 
    // establecemos que el mapa de caracteres será UTF8  
   @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  
	$queryString=$_POST['queryString'];
	$sql="SELECT * FROM `modelo` WHERE `descripcion` LIKE '%".$queryString."%' LIMIT 6";
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die ("Error al obtener datos");
    if ( $sqlr )  
    { 
		  while ($registro=mysqli_fetch_array($sqlr))
        { 
       		$contenido = str_replace($text, $replace, $registro['texto']);
        		$contenido = strtoentity( $contenido)."0";
        		//$contenido = preg_replace('<','&lt;',$contenido);
        		//$contenido = preg_replace('&gt;','>', $contenido);
	       echo '<li onClick="fill(\''.$registro['descripcion'].'-'. $contenido .'\');">'.$registro['descripcion'].' </li>';        }  
    }


?>