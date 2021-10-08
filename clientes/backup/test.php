<?php
include("/var/www/html/servicemcc/conexion.php");

function  cambiaf_a_normal( $fecha ){
 ereg (  "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})" ,  $fecha ,  $mifecha );
 $fechafinal = $mifecha [ 3 ]. "/" . $mifecha [ 2 ]. "/" . $mifecha [ 1 ];
return $fechafinal;
 }
 
function  cambiaf_a_mysql( $fecha ){
 ereg (  "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})" ,  $fecha ,  $mifecha );
 $fechafinal = $mifecha [ 3 ]. "-" . $mifecha [ 2 ]. "-" . $mifecha [ 1 ];
return  $fechafinal ;
}
 
 
function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}

 $message='
19/01/2015 12:46:19 p.m. El fichero de Diario ha sido borrado. Un nuevo Diario ha sido creado
    20/01/2015 09:19:34 a.m. Bienvenido a Cobian Backup Amanita
    20/01/2015 09:19:34 a.m. Versión del motor: 9.5.1.113    Versión del sistema operativo: 5.0.2195    Servicio: No
    20/01/2015 12:45:03 p.m. **** Respaldo de "Fabrica" iniciado ****
    20/01/2015 12:45:06 p.m. Copiando el directorio "\\Admfabrica\DiscoC\Orden" a "\\Jacquelin\XDATA\RespaloFabrica\Orden"
    20/01/2015 12:45:16 p.m. El directorio "\\Admfabrica\DiscoC\Orden"  ha sido copiado a "\\Jacquelin\XDATA\RespaloFabrica\Orden". 1259 fichero(s) fueron procesados
    20/01/2015 12:45:16 p.m. Copiando el directorio "C:\Datos" a "\\Jacquelin\XDATA\RespaloFabrica\Datos"
    20/01/2015 12:45:34 p.m. El directorio "C:\Datos"  ha sido copiado a "\\Jacquelin\XDATA\RespaloFabrica\Datos". 3015 fichero(s) fueron procesados
    20/01/2015 12:45:34 p.m. Copiando el directorio "C:\memory" a "\\Jacquelin\XDATA\RespaloFabrica\memory"
    20/01/2015 12:46:20 p.m. El directorio "C:\memory"  ha sido copiado a "\\Jacquelin\XDATA\RespaloFabrica\memory". 7287 fichero(s) fueron procesados
    20/01/2015 12:46:20 p.m. **** Respaldo de "Fabrica" terminado. 4 fichero(s) han sido respaldados (Duración: 0 hora(s), 1 minuto(s), 13 segundo(s)) ****
    20/01/2015 12:46:21 p.m. Enviando el fichero de Diario a respaldos@mcc.com.uy
    ';

/********** Para CobianBK **********************/

$pos = strpos($message,"Cobian Backup Amanita");
if($pos !== false) {
	$version= "Cobian Backup 9";
	$cliente="";	
	$very=9;	
	/*/echo extract_unit($details["subject"], "[", "]");*/
	$items = array('Copiando el directorio');
	//$items[0]='El directorio de destino';
	//$items[1]='Copiando el directorio';
	$aux=strpos($message,"****");
	$found=1;
}
/*
$pos = strpos($details["subject"],"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	echo extract_unit($details["subject"], "[", "]");
	$items = array("*** La tarea");
	$aux=21;
	$found=1;
}
*/
$pos = strpos($message,"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	/*/echo extract_unit($details["subject"], "[", "]");*/
	$items = array("*** La tarea");
	$aux=21;
	$found=1;
}
$pos = strpos($cliente,"Cobian Backup 10");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 10";
	$items = array("*** La tarea ");
	$aux=21;
	$found=1;
}

$pos = strpos($message,"Cobian Backup 11");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 11";
	$items = array("** Respaldo terminado para la tarea ");
	$aux=21;
	$found=1;
}

/*///////////////////////////////////*/
$pos = strpos($cliente,"Cobian Backup 11");
if($pos !== false and $very!=9) {
	$version= "Cobian Backup 11";
	$items = array("** Respaldo terminado para la tarea ");
	$aux=21;
	$found=1;
}

	$Equipo=extract_unit($details["subject"], "(", ")");

/************* Para acronis ******************************/
//echo "<br>".$details["subject"]."<br>";
/*
$pos = strpos($details["subject"],"[ABR11.5]");
if($pos !== false) {
	$version= "Acronis 11.5";
	$Equipo=extract_unit($details["subject"], "(", ")");
	$items = array("Tarea '");
	$aux=21;
	$found=2;
}
*/
/*
$pos = strpos($details["subject"],"Task");
if($pos !== false) {
	$version= "Acronis 11.5";
	$porciones = explode("machine ", $details["subject"]);
 	$Equipo=$porciones[1];

	$items = array("Task '");
	$aux=21;
	$found=3;
}
*/
$pos = strpos($cliente,"[ABR11.5]");
if($pos !== false) {
	$version= "Acronis 11.5";
	$items = array("Tarea '");
	$aux=21;
	$found=2;
}
/********** Para MCC***********************/
 $pos = strpos($cliente,"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}
/*
 $pos = strpos($details["subject"],"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$Equipo=extract_unit($details["subject"], "(", ")");
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}
*/
//echo $very . " ----> ".$version. " ----> ". $found . " ----> ". $aux. " ---> ".$porciones;


		//echo $message."<br>--------------------<p>"; 	 		 
	
	
 $NombreCliente=explode("-", $cliente);
 $buscar="Liderblex";

 $EquipoUsuario="Fabrica";

$Tamano='-';

if ($buscar!='') { 
$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$buscar."%'";

	$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
	if ($row=mysqli_fetch_array($con_cliente)) {
		$clientenom=$row['nombre']." ".$row['apellido'];
		$codcliente=$row['codcliente'];
	}	
		$criterio="";
	

		/* Comienzo búsqueda para cobian backup 9 */

		
	
		foreach($items as $item) {
		$string=$message;
		 $t_message=$message;
	    $Count=0;
	    $appearsCount += substr_count($string, $item);
		echo "<br>--$item aparece $appearsCount veces<br>";
		$fin=strlen($string);
		$NuevaCadena=$string;
		
			$startIni=0;
			for ($Count=1;$Count<=$appearsCount;$Count++) {
				$xz++;
				$save="No";
				
				
			/*Extraigo el texto a partir de donde encontre la cadena*/				
			/*para ello averiguo el largo total del string y le resto la posición inicial de la cadena*/				
			$pos_ini=strpos($NuevaCadena, $item);
			$largoItem=strlen($item);
			$NuevaCadena=substr($NuevaCadena, $pos_ini+$largoItem, $fin-$pos_ini-$largoItem);
			$fin=strlen($NuevaCadena);
			/*Recorro la nueva cadena hasta encontrar \ */
			$pos_fecha=strpos($NuevaCadena, '/');
			/*Obtengo la fecha*/
			$fecha_nueva=substr($NuevaCadena, $pos_fecha-2,10);
			$FechaAux=cambiaf_a_mysql($fecha_nueva);

			 $Tarea=extract_unit($NuevaCadena, "\"", "\"");

			$punto=strpos($NuevaCadena,'".');
			$parentesis=strpos($NuevaCadena,")");
			$Procesados=substr($NuevaCadena, $punto+2, $parentesis-$punto);
			$pos_control=strpos($NuevaCadena, $item);
			$pos_terminado=strpos($NuevaCadena, "terminado.");

			$largo_terminado=strlen("terminado.");

			if( $pos_terminado < $pos_control or $pos_control=='' ) {
				$CadenaFinal=substr($NuevaCadena, $pos_terminado+$largo_terminado, $fin- $pos_terminado-$largo_terminado);
				$parentesis=strpos($CadenaFinal,")");
				$Respaldados=substr($CadenaFinal, 0, $parentesis+1);
			}
	/*Fin de seccion para Cobian Backup 9*/		
			
	/*//////////*****************************************/
	$Errores=(int)$Errores;
	$Procesados=(int)$Procesados;
	$Respaldados=(int)$Respaldados;

 if (strlen($t_message) > 200001 ) {
	$message=str_replace("'", "|", $string);
	$Errores="Mensaje generado por cobian demasiado largo";
 } else {
	$message=str_replace("'", "|", $t_message); 
 }
	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0 and $FechaAux!="0000-00-00"){
	$con_check=0;	
	/*echo "<br>".*/
	$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$FechaAux."' AND `tarea` LIKE '".$Tarea."' AND `errores` like '".$Errores."'
	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' AND `codcliente` ='".$codcliente."' AND
	`usuario` LIKE '".$EquipoUsuario."' ";	

	$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
		if (mysqli_num_rows($con_check) == false){	
		mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");
		$sql="INSERT INTO `respaldospc` 
		(`codrespaldos`, `fecha`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
		 VALUES (NULL, '".$FechaAux."', '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
		  '".$EquipoUsuario."', '".$version."')";
		 echo "+++ ".$sql."<br>";

		} else {
		 $save="existe";	
		}
	}
	
	
	$save="No";
	$Tam="";
	$Errores="";
	$Tamano="-";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	/*/////////*****************************************/
			}
	$version="";
	$clientenom="";		
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $Falla="";
		    $codcliente='';
		    
		}

		$very=0;
	}
	$message="";
?>