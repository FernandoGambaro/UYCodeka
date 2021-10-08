<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

//header('Content-Type: text/event-stream');
////header('Cache-Control: no-cache'); 

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

?>
<html>
	<head>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">

    <title>Obtener Cotización del Dolar</title>

<style type="text/css">
#content {
   position : absolute;    
    width:320px;
    height:130px;
    left:5%;
    top:50%;

}
</style>
</head>
	<body >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Aguarde mientras obtenemos la/s última/s cotización del Dolar</div>



<div id="content">
<!-- Progress bar holder -->
<div id="progress" style="width:280px;border:1px solid #ccc;background-color:#fff;"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

</div>
<?php 
 
sleep(1);
    
$fechaInicio=strtotime($nuevafecha)+86400;
$fechaFin=strtotime($fecha);

$total=dias_transcurridos($nuevafecha,$fecha);
$x=1;

	
$wsdl = 'https://cotizaciones.bcu.gub.uy/wscotizaciones/servlet/awsbcucotizaciones?WSDL';
//require_once("../efactura/nusoap/lib/nusoap.php");	

require_once("../efactura/nusoap/vendor/autoload.php");	
//require_once($conexionPath."/efactura/nusoap/vendor/autoload.php");	
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
	$percent = intval(($x/$total) * 100)."%";
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
		var_dump($respuesta);
		$codigoerror= $respuesta['Salida']['respuestastatus']['codigoerror'];
		$valor=  $respuesta['Salida']['datoscotizaciones']['datoscotizaciones.dato']['TCC'];

    if (!empty($valor) and $valor != 0 and $codigoerror==0){
    $sql_chek="SELECT * FROM `tipocambio` WHERE `fecha` = '".$fecha."' LIMIT 0 , 1 ";
    $rs_chek=mysqli_query($GLOBALS["___mysqli_ston"], $sql_chek);
    if (mysqli_num_rows($rs_chek)==0){
			$query_operacion="INSERT INTO tipocambio (codtipocambio, fecha, valor) VALUES (null, '$fecha', '$valor')";		
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
			if ($rs_operacion) { $mensaje="El tipo de cambio ha sido dado de alta correctamente"; }else{$mensaje="Error<br>";}
		echo $mensaje. "<br>";
		}
	}  

    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#000; text-align: left;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="<br><p><center>'. implota(date("Y-m-d", $i)).' finaliza el '. 
     implota(date("Y-m-d", $fechaFin)).' </center>";
    </script>';

// This is for the buffer achieve the minimum size in order to flush data
    echo str_repeat(' ',1024*64);

    
// Send output to browser immediately
    flush();

// Sleep one second so we can see the delay
    sleep(1);
	  
    
} 
// Tell user that the process is completed
echo '<script language="javascript">document.getElementById("information").innerHTML="Proceso completado"</script>';
sleep(3);
echo "<script language=\"javascript\"> parent.$('idOfDomElement').colorbox.close();</script>";
?>
</div></div></div></div>
</body>
</html>
