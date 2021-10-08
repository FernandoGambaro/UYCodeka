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
$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$tipo=$_POST["Atipo"];
$numerico=$_POST["Anumerico"];
$descripcion=$_POST["Adescripcion"];
$simbolo=$_POST["Asimbolo"];
$fraccion=$_POST["fraccion"];

if ($accion=="alta") {
	$moneda=$_POST["Amoneda"];
	$query_operacion="INSERT INTO `monedas` (`codmoneda`, `moneda`, `numerico`, `descripcion`, `simbolo`, `fraccion`, `orden`, `borrado`) 
	VALUES (null, '$moneda', '$numerico', '$descripcion', '$simbolo', '$fraccion', '$tipo', '0');";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="La moneda ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> Moneda &gt;&gt; Nueva Moneda ";
	$cabecera2="INSERTAR MONEDA ";
	$sel_maximo="SELECT max(codmoneda) as maximo FROM monedas";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codmoneda=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codmoneda=$_POST["Zid"];
	$query="UPDATE `monedas` SET `numerico` = '$numerico', `descripcion` = '$descripcion', 
	`simbolo` = '$simbolo', `fraccion` = '$fraccion', `orden` = '$tipo'
	 WHERE `monedas`.`codmoneda` = '$codmoneda';";
	 
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos de la moneda han sido modificados correctamente"; }
	$cabecera1="Inicio >> Moneda &gt;&gt; Modificar Moneda ";
	$cabecera2="MODIFICAR MONEDA ";
}

if ($accion=="baja") {
	$codmoneda=$_POST["Zid"];
	$query="UPDATE monedas SET borrado=1 WHERE codmoneda='$codmoneda'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="La moneda ha sido eliminada correctamente"; }
	$cabecera1="Inicio >> Monedas &gt;&gt; Eliminar Moneda ";
	$cabecera2="ELIMINAR MONEDA ";
}

if($tipo!=0) {
	$sql="SELECT * FROM monedas WHERE orden = '".$tipo."' and codmoneda !=".$codmoneda;
	$res=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	$contador=0;
		   while ($contador < mysqli_num_rows($res)) {
		   	$codmon=mysqli_result($res, $contador, "codmoneda");
		   	$query="UPDATE `monedas` SET  `orden` = '3' WHERE `monedas`.`codmoneda` = '".$codmon."'";
	 			$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		   	$contador++;
		   } 		
}

	$query_mostrar="SELECT * FROM monedas WHERE codmoneda='$codmoneda'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
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
						<button class="boletin" onClick="aceptar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
			  </div>
			 </div>
		  </div>
		</div>
		
	</body>
</html>
