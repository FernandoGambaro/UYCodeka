<?php
include ("../conectar.php");

$sql="";

//header('Content-Type: text/html; charset=UTF-8'); 
  if(isset($_POST['queryString'])) { 
    /*/ establecemos que el mapa de caracteres será UTF8 */ 
    @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  

		$queryString=$_POST['queryString'];
    /*/ ejecutamos nuestra consulta a la base de datos*/

    $sql="SELECT * FROM `sector` WHERE `descripcion` LIKE '%".$queryString."%' ";
	$sqlr=mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die('Error');
	
       
    if ( $sqlr )  
    { 
		  while ($registro=mysqli_fetch_array($sqlr))
        {  
	       echo '<li onClick="fill(\''.$registro['codsector'].'-'.$registro['descripcion'].'-'.$registro['color'].'\'); "><span style="background:'.$registro['color'].'">'.$registro['descripcion'].'</span> </li>';        }  
    }  
    // cerramos la conexión a la base de datos (no importa si la abrimos en modo persistente)  
    //mysqli_close( $conectar );  
}
?>