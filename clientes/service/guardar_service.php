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
 



include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 
date_default_timezone_set("America/Montevideo"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codservice=$_POST["codservice"];
$codequipo=$_POST["acodequipo"];
$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$facturado=$_POST["facturado"];
if ($facturado<>"") { $facturado=explota($facturado); } else { $facturado="0000-00-00"; }

$solicito=$_POST["asolicito"];
$estado=$_POST["aestado"];
$tipo=$tiposervice=$_POST["atipo"];
$horas=@$_POST["horas"];
$detalles=isset($_POST["Adetalles"]) ? @$_POST["Adetalles"] : @$_POST["detalles"];
$des=$_POST["descripcion"];
$realizado=$_POST["arealizado"];
$importe=$_POST["nimporte"];

$codcliente=$_POST['e'];


if ($accion=="alta") {
	echo $query_operacion="INSERT INTO service (`codservice` ,`codcliente` ,`fecha` ,`codequipo` ,`tipo` ,`detalles` ,`realizado`, `solicito`,
	`horas`, `estado`, `facturado`, `importe`, `borrado`) VALUES 
	(null, '$codcliente', '$fecha', '$codequipo', '$tipo', '$detalles', '$realizado', '$solicito',
	'$horas', '$estado', '$facturado', '$importe', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El service ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Service &gt;&gt; Nuevo Service ";
	$cabecera2="INSERTAR SERVICE ";
	$sel_maximo="SELECT max(codservice) as maximo FROM service";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codservice=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$query="UPDATE service SET codequipo='$codequipo', `codcliente`='$codcliente', `fecha`='$fecha', `codequipo`='$codequipo',
 `tipo`='$tipo', `detalles`='$detalles', `realizado`='$realizado', `solicito`='$solicito',
	`horas`='$horas', `estado`='$estado', `facturado`='$facturado', `importe`='$importe', borrado=0 WHERE `codservice`='$codservice'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del service han sido modificados correctamente"; } else {
	 $mensaje="Error al modificar datos";	}
	$cabecera1="Inicio >> Service &gt;&gt; Modificar Service ";
	$cabecera2="MODIFICAR SERVICE ";
}

if ($accion=="baja") {
	$codservice=$_GET["codservice"];
	$query="UPDATE service SET borrado=1 WHERE codservice='$codservice'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El service ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Service &gt;&gt; Eliminar Service ";
	$cabecera2="ELIMINAR SERVICE ";
	$query_mostrar="SELECT * FROM service WHERE codservice='$codservice'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	
	$codequipo=mysqli_result($rs_mostrar, 0, "codequipo");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$fecha=explota($fecha);
	//$service=mysql_result($rs_mostrar,0,"service");
	$solicito=mysqli_result($rs_mostrar, 0, "solicito");
	$horas=mysqli_result($rs_mostrar, 0, "horas");
	$estado=mysqli_result($rs_mostrar, 0, "estado");
	$desc=mysqli_result($rs_mostrar, 0, "descripcion");
	$realizado=mysqli_result($rs_mostrar, 0, "realizado");
	$importe=mysqli_result($rs_mostrar, 0, "importe");

}

$tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
$contador=0;
if($codequipo!='' and $codequipo!=0) {
	$consulta="SELECT * FROM equipos WHERE borrado=0 AND `codequipo`='".$codequipo."'";
	$res_resultado=$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$nrs=mysqli_num_rows($rs_tabla);

$service=$tipo[mysqli_result($res_resultado, $contador, "service")];
$desc=mysqli_result($res_resultado, $contador, "alias")." - ".mysqli_result($res_resultado, $contador, "descripcion");
} else {
	$desc='';
}
/*
if ($accion=="alta" ) {
?>
	<script type="text/javascript" >
	//event.preventDefault();
	parent.$('idOfDomElement').colorbox.close();
	</script>

<?php
}
*/
?>