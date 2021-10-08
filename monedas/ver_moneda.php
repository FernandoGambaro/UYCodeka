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

$codmoneda=$_GET["codmoneda"];

$query="SELECT * FROM monedas WHERE codmoneda='$codmoneda'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

?>
<html>
	<head>
		<title>Ver Monedas</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = 'js/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>		<script language="javascript">
	
		function limpiar() {
			document.getElementById("nombre").value="";
			document.getElementById("valor").value="";
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
				<div id="tituloForm" class="header">MODIFICAR MONEDA </div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
						<td>&nbsp;Tipo&nbsp;</td><td>
						<select  type="text" size="1" name="Atipo" id="tipo" class="comboMedio" >
						<?php
						$Tipox = array(
						1=>"Principal",
						2=>"Secundaria",
						3=>'No seleccionado',
						 );
						$xx=1;
						$orden= mysqli_result($rs_query, 0, "orden");
						foreach($Tipox as $ii) {
						  	if ( $xx== $orden)	{
								echo "<option value=$xx selected>$ii</option>";
							}	else 	{
								echo "<option value=$xx>$ii</option>";
							}
							$xx++;
						}	
						?>
						</select>
						</td>
						</tr><tr>						
							<td>C&oacute;digo</td>
							<td><?php echo mysqli_result($rs_query, 0, "moneda");?></td>
						</tr>
						<tr>
							<td width="15%">Númerico</td>
						    <td width="43%"><input name="Anumerico" type="text" class="cajaPequena" id="numerico" size="3" maxlength="3" value="<?php echo mysqli_result($rs_query, 0, "numerico")?>"></td>
				        </tr>
						<tr>
							<td width="15%">Descripción</td>
						    <td width="43%"><input name="Adescripcion" type="text" class="cajaGrande" id="descripcion" size="20" maxlength="20" value="<?php echo mysqli_result($rs_query, 0, "descripcion")?>"></td>
				        </tr>
						<tr>
							<td width="15%">Símbolo</td>
						    <td width="43%"><input name="Asimbolo" type="text" class="cajaPequena" id="valor" size="15" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "simbolo")?>"> </td>
				        </tr>
						<tr>
							<td width="15%">Fracción</td>
						    <td width="43%"><input name="fraccion" type="text" class="cajaPequena" id="valor" size="15" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "fraccion")?>"> </td>
				        </tr>
					</table>
			  </div>
			  <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Aceptar</button>
			  </div>

			 </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">	
	</body>
</html>
