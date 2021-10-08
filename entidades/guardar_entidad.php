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
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8');

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$nombreentidad=$_POST["Anombreentidad"];

if ($accion=="alta") {
	$query_operacion="INSERT INTO entidades (codentidad, nombreentidad, borrado) 
					VALUES ('', '$nombreentidad', '0')";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="La entidad ha sido dada de alta correctamente"; }
	$cabecera1="Inicio >> Entidades bancarias &gt;&gt; Nueva Entidad Bancaria ";
	$cabecera2="INSERTAR ENTIDAD BANCARIA ";
	$sel_maximo="SELECT max(codentidad) as maximo FROM entidades";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codentidad=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codentidad=$_POST["codentidad"];
	$query="UPDATE entidades SET nombreentidad='$nombreentidad', borrado=0 WHERE codentidad='$codentidad'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos de la entidad bancaria han sido modificados correctamente"; }
	$cabecera1="Inicio >> Entidades Bancarias &gt;&gt; Modificar Entidad Bancaria ";
	$cabecera2="MODIFICAR ENTIDAD BANCARIA ";
}

if ($accion=="baja") {
	$codentidad=$_POST["codentidad"];
	$query_comprobar="SELECT * FROM clientes WHERE codentidad='$codentidad' AND borrado=0";
	$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
	if (mysqli_num_rows($rs_comprobar) > 0 ) {
		?><script>
			alert ("No se puede eliminar esta entidad bancaria porque tiene clientes asociados.");
			location.href="eliminar_entidad.php?codentidad=<?php echo $codentidad?>";
		</script>
		<?php
	} else {
		$query_comprobar="SELECT * FROM proveedores WHERE codentidad='$codentidad' AND borrado=0";
		$rs_comprobar=mysqli_query($GLOBALS["___mysqli_ston"], $query_comprobar);
		if (mysqli_num_rows($rs_comprobar) > 0 ) {
			?><script>
				alert ("No se puede eliminar esta entidad bancaria porque tiene proveedores asociados.");
				location.href="eliminar_entidad.php?codentidad=<?php echo $codentidad?>";
			</script>
		<?php } else {
				$query="UPDATE entidades SET borrado=1 WHERE codentidad='$codentidad'";
				$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
				if ($rs_query) { $mensaje="La entidad ha sido eliminada correctamente"; }
				$cabecera1="Inicio >> Entidades Bancarias &gt;&gt; Eliminar Entidad Bancaria ";
				$cabecera2="ELIMINAR ENTIDAD BANCARIA ";
				$query_mostrar="SELECT * FROM entidades WHERE codentidad='$codentidad'";
				$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
				$codentidad=mysqli_result($rs_mostrar, 0, "codentidad");
				$nombreentidad=mysqli_result($rs_mostrar, 0, "nombreentidad");
			}
	}
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
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
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="85%" colspan="2"><?php echo $codentidad?></td>
					    </tr>
						<tr>
							<td width="15%">Nombre</td>
						    <td width="85%" colspan="2"><?php echo $nombreentidad?></td>
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
