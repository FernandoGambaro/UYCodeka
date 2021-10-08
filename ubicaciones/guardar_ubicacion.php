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
$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$nombre=$_POST["Anombre"];

if ($accion=="alta") {
	$query_operacion="INSERT INTO ubicaciones (codubicacion, nombre, borrado) 
					VALUES ('', '$nombre', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="La ubicaci&oacute;n ha sido dada de alta correctamente"; }
	$cabecera1="Inicio >> Ubicaciones &gt;&gt; Nueva Ubicaci&oacute;n ";
	$cabecera2="INSERTAR UBICACI&Oacute;N ";
	$sel_maximo="SELECT max(codubicacion) as maximo FROM ubicaciones";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codubicacion=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codubicacion=$_POST["Zid"];
	$query="UPDATE ubicaciones SET nombre='$nombre', borrado=0 WHERE codubicacion='$codubicacion'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos de la ubicaci&oacute;n han sido modificados correctamente"; }
	$cabecera1="Inicio >> Ubicaciones &gt;&gt; Modificar Ubiaci&oacute;n ";
	$cabecera2="MODIFICAR UBICACI&Oacute;N ";
}

if ($accion=="baja") {
	$codubicacion=$_GET["codubicacion"];
	$query_comprobar="SELECT * FROM articulos WHERE codubicacion='$codubicacion' AND borrado=0";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
		?><script>
			alert ("No se puede eliminar esta ubicacion porque tiene articulos asociados.");
			location.href="eliminar_ubicacion.php?codubicacion=<?php echo $codubicacion?>";
		</script>
		<?php
	} else {
		$query="UPDATE ubicaciones SET borrado=1 WHERE codubicacion='$codubicacion'";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($rs_query) { $mensaje="La ubicaci&oacute;n ha sido eliminada correctamente"; }
		$cabecera1="Inicio >> Ubicaciones &gt;&gt; Eliminar Ubicaci&oacute;n ";
		$cabecera2="ELIMINAR UBICACI&Oacute;N ";
		$query_mostrar="SELECT * FROM ubicaciones WHERE codubicacion='$codubicacion'";
		$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
		$codubicacion=mysqli_result($rs_mostrar, 0, "codubicacion");
		$nombre=mysqli_result($rs_mostrar, 0, "nombre");
	}
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">				<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			event.preventDefault();
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
							<td>C&oacute;digo</td>
							<td  colspan="2"><?php echo $codubicacion?></td>
					    </tr>
						<tr>
							<td >Nombre</td>
						    <td  colspan="2"><?php echo $nombre?></td>
					    </tr>						
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick=" aceptar() ;" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			 </div>
		  </div>
		</div>
	</body>
</html>
