<?php
include("../conexion.php");
$otro=$_GET['otro'];
$sql="";
// PHP5 Implementation - uses MySQLi.// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
//header('Content-Type: text/html; charset=UTF-8'); //header('Content-Type: text/html; charset=iso-8859-1');

  if(isset($_POST['queryString'])) { 
    /*/ establecemos que el mapa de caracteres será UTF8 */ 
    @mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES 'utf8'");  

		$queryString=trim($_POST['queryString']);
    
      
    /*/ ejecutamos nuestra consulta a la base de datos*/
    if($otro=='plancuentac' or $otro=='plancuentav') {
		$sql="SELECT * FROM  `plandecuentas` WHERE borrado=0 AND  ( `descripcion` LIKE '%".$queryString."%' or `definicion` LIKE '%".$queryString."%' )  LIMIT 0 , 8";
    }  
	$sqlr=mysqli_query( $conectar, $sql) or die ("Error al obtener datos");
	
       
    if ( $sqlr )  
    { 
		  while ($registro=mysqli_fetch_array($sqlr))
        {  
               if ($otro=='plancuentac' or $otro=='plancuentav') {
	       echo '<li onClick="otro(\''.$registro['codplan'].'-'.$registro['definicion'].'\'); ">'.$registro['definicion'].' - '.$registro['descripcion'].' </li>';               }
        }  
    }  
    // cerramos la conexión a la base de datos (no importa si la abrimos en modo persistente)  
    //mysqli_close( $conectar );
    
}

?>