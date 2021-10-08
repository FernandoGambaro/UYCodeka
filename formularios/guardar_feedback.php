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
$formulario=$_POST["tipoformulario"];
$fila=$_POST["afila"];
$competencias=$_POST["Acompetencia"];
$definicion=$_POST["definicion"];

if ($accion=="alta") {
	$query_operacion="INSERT INTO `feedbackform` (`codfeedback`, `codusuarios`, `fecha`, `codformulario`, `fila`, `competencias`, `definicion`, `nivel`, `aspectos`, `borrado`) 
VALUES (NULL, '$UserID', CURRENT_TIMESTAMP, '$formulario', '$fila', '$competencias', '$definicion', '0-0-0-0', '', '0');	";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El formularios ha sido dada de alta correctamente"; }
	$codfeedback = mysqli_insert_id($GLOBALS["___mysqli_ston"]);
}

if ($accion=="modificar") {
	$codfeedback=$_POST["codfeedback"];
	$query="UPDATE `feedbackform` SET  `codformulario` = '$formulario', `fila` = '$fila', 
`competencias` = '$competencias', `definicion` = '$definicion' WHERE `feedbackform`.`codfeedback` = '$codfeedback';	";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del formularios han sido modificados correctamente"; }
}

if ($accion=="baja") {
	$codfeedback=$_POST["codfeedback"];
		$query="UPDATE feedbackform SET borrado=1 WHERE codfeedback='$codfeedback'";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if ($rs_query) { $mensaje="El formularios ha sido eliminado correctamente"; }
}

?>
	<script type="text/javascript" >
	parent.document.getElementById("codformulario").value=<?php echo $formulario;?>;
	parent.$('idOfDomElement').colorbox.close();
	</script>
