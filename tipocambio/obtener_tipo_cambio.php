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

//Configuración para ejecutar desde la línea de comando

$path=realpath(dirname(__FILE__));
$largo=strlen($path)-11;
$conexionPath=substr($path,0,$largo);

include($conexionPath."/conectar.php");
include($conexionPath."/funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

 
$serverTime = time(); 
$nuevafecha='';
 
$fecha = date('Y-m-j');
/* Busco cual fue la última fecha donde se inserto cotización*/
    $sql_chek="SELECT * FROM `tipocambio` Order by fecha DESC LIMIT 0 , 1 ";
    $rs_chek=mysqli_query($GLOBALS["___mysqli_ston"], $sql_chek);
   if (mysqli_num_rows($rs_chek)!=0){ 
	$nuevafecha=mysqli_result($rs_chek, 0, "fecha");
	}
	if (empty($nuevafecha)){
	$nuevafecha="2018-01-01";	
	}
/*
$nuevafecha = strtotime ( '-1 month' , strtotime ( $fecha ) ) ;
$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
*/
 
 function dias_transcurridos($fecha_i,$fecha_f)
{
	$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
	$dias 	= abs($dias); $dias = floor($dias);		
	return $dias;
}

    
$fechaInicio=strtotime($nuevafecha)+86400;
$fechaFin=strtotime($fecha);

$total=dias_transcurridos($nuevafecha,$fecha);
$x=1;
	
$wsdl = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet/awsbcucotizaciones?WSDL';

require_once($conexionPath."/efactura/nusoap/lib/nusoap.php");	
	//Web Services Cotizaciones
$client = new nusoap_client($wsdl , 'wsdl');
//display errors
$err = $client->getError();
if ($err)
{
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
	exit();
}


for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
 	$x++;  
	$fecha=date("Y-m-d", $i);

		//Consulto cotización -------------------------------------------------------Formato de Fecha YYYY-mm-dd
		$parameters = array(
							'Entrada'=>array(
										'Moneda' =>array('item'=>2225),
										'FechaDesde' => $fecha,
										'FechaHasta' => $fecha,
										'Grupo' => '2'
										)
							);
		$respuesta=$client->call('Execute', $parameters);
		print_r($respuesta);
		$codigoerror= $respuesta['Salida']['respuestastatus']['codigoerror'];
		$valor=  $respuesta['Salida']['datoscotizaciones']['datoscotizaciones.dato']['TCC'];

    if (!empty($valor) and $valor != 0 and $codigoerror==0){
    $sql_chek="SELECT * FROM `tipocambio` WHERE `fecha` = '".$fecha."' LIMIT 0 , 1 ";
    $rs_chek=mysqli_query($GLOBALS["___mysqli_ston"], $sql_chek);
    if (mysqli_num_rows($rs_chek)==0){
			$query_operacion="INSERT INTO tipocambio (codtipocambio, fecha, valor) VALUES (null, '$fecha', '$valor')";		
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
			if ($rs_operacion) { $mensaje="El tipo de cambio ha sido dado de alta correctamente"; }
		//echo $mensaje. "<br>";
		}
	}  
  
    
} 

?>