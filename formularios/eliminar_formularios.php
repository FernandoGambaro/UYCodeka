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
$codformulario=$_GET["codformulario"];

$query="SELECT * FROM formularios WHERE codformulario='$codformulario'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/calendario/jscal2.js"></script>
		<script src="../js3/calendario/lang/es.js"></script>
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/jscal2.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/border-radius.css" />
		<link rel="stylesheet" type="text/css" href="../js3/calendario/css/win2k/win2k.css" />		
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">				
		<script language="javascript">
		
		function cancelar() {
			event.preventDefault();
			parent.$('idOfDomElement').colorbox.close();
		}
		
		function limpiar() {
			document.getElementById("nombre").value="";
		}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
			
		</script>
	</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Modificar formularios </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_formularios.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						  <td>Descripción</td>
						  <td><input name="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="50" maxlength="50" value="<?php echo mysqli_result($rs_query, 0, "descripcion");?>"></td>
						  </tr><tr>
						  <td>Tipo</td><td>
						   <select id="tipo" name="atipo" class="cajaPequena" data-index="2">

					<?php 
					$tipo=array(0=>'Feed Back', 1=>'Reunión devolución');
					if (mysqli_result($rs_query, 0, "tipo")==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($tipo as $i) {
					  	if ( $x==mysqli_result($rs_query, 0, "tipo")) {
							echo "<option value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<option value=$x>$i</option>";
						}
						$x++;
					}
					?>
								</select>
								</td>
						  </tr><tr>
					  </tr>						
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Eliminar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
					<input id="accion" name="accion" value="baja" type="hidden">
					<input id="id" name="codformulario" value="<?php echo $codformulario?>" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
	</body>
</html>