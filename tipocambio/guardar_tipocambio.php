<?php
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
/*/user is not logged in*/
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
              echo "<script>window.parent.changeURL('../index.php' ); </script>";
	       /*/header("Location:../index.php");	*/
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php"); 
$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$valor=$_POST["valor"];


if ($accion=="alta") {
	$query_operacion="INSERT INTO tipocambio (codtipocambio, fecha, valor) 
					VALUES (null, '$fecha', '$valor')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El tipo de cambio ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Nuevo tipo de cambio ";
	$cabecera2="INSERTAR tipo de cambio ";
	$sel_maximo="SELECT max(codtipocambio) as maximo FROM tipocambio";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codtipocambio=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codtipocambio=$_POST["codtipocambio"];
	$query="UPDATE tipocambio SET fecha='$fecha', valor='$valor' WHERE codtipocambio='$codtipocambio'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del tipo de cambio han sido modificados correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Modificar tipocambio ";
	$cabecera2="MODIFICAR tipo de cambio ";
}

if ($accion=="baja") {

	$codtipocambio=$_POST["codtipocambio"];
	$query_mostrar="SELECT * FROM tipocambio WHERE codtipocambio='$codtipocambio'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);

	$fecha= implota(mysqli_result($rs_mostrar, 0, "fecha"));
	$valor=mysqli_result($rs_mostrar, 0, "valor");
	
	$query="DELETE FROM `tipocambio` WHERE `codtipocambio` = '".$codtipocambio."'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El tipo de cambio ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> tipo de cambio &gt;&gt; Eliminar tipocambio ";
	$cabecera2="ELIMINAR tipo de cambio ";
}

?>

<html>
	<head>
		<title>Guardar T.C.</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
			<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
		
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codtipocambio?></td>
					    </tr>
						<tr>
							<td width="15%">Fecha</td>
						    <td width="85%" colspan="2"><?php echo $fecha?></td>
						   
					    </tr>
						<tr>
							<td width="15%">Valor</td>
							<td width="85%" colspan="2"><?php echo $valor?></td>
					    </tr>						
					</table>
			  </div>
				<div>
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Aceptar</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
