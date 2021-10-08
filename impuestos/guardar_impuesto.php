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
         /*echo "<script>parent.changeURL('../../index.php' ); </script>";*/
	 header("Location:../index.php");
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
date_default_timezone_set("America/Montevideo");

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_POST["accion"]; }

$nombre=$_POST["Anombre"];
$valor=$_POST["Qvalor"];

if ($accion=="alta") {
	$query_operacion="INSERT INTO impuestos (codimpuesto, nombre, valor, borrado) 
					VALUES ('', '$nombre', '$valor', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El impuesto ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Impuestos &gt;&gt; Nuevo Impuesto ";
	$cabecera2="INSERTAR IMPUESTO ";
	$sel_maximo="SELECT max(codimpuesto) as maximo FROM impuestos";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codimpuesto=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codimpuesto=$_POST["Zid"];
	$query="UPDATE impuestos SET nombre='$nombre', valor='$valor', borrado=0 WHERE codimpuesto='$codimpuesto'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del impuesto han sido modificados correctamente"; }
	$cabecera1="Inicio >> Impuestos &gt;&gt; Modificar Impuesto ";
	$cabecera2="MODIFICAR IMPUESTO ";
}

if ($accion=="baja") {
	$query="UPDATE impuestos SET borrado=1 WHERE codimpuesto='$codimpuesto'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El impuesto ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> Impuestos &gt;&gt; Eliminar Impuesto ";
	$cabecera2="ELIMINAR IMPUESTO ";
	$query_mostrar="SELECT * FROM impuestos WHERE codimpuesto='$codimpuesto'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codimpuesto=mysqli_result($rs_mostrar, 0, "codimpuesto");
	$nombre=mysqli_result($rs_mostrar, 0, "nombre");
	$valor=mysqli_result($rs_mostrar, 0, "valor");
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../common/timeout.js"></script>

		<script src="../calendario/jscal2.js"></script>
		<script src="../calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		
		<script src="../js3/jquery.maskedinput.js" type="text/javascript"></script>
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
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codimpuesto?></td>
					    </tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="85%" colspan="2"><?php echo $nombre?></td>
					    </tr>
						<tr>
							<td width="15%">Valor</td>
							<td width="85%" colspan="2"><?php echo $valor?> %</td>
					    </tr>						
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
