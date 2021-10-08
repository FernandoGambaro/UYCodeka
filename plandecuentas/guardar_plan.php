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
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');


$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codplan=$_POST["Acodplan"];
$nombre=$_POST["Adescripcion"];
$m=$_POST['m'];
$operaen=$_POST['operaen'];

if ($accion=="alta") {
	$sel_comp="SELECT * FROM `plandecuentas` WHERE `codplan`='$codplan'";
	$rs_comp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_comp);
	if($rs_comp!==FALSE) {
		if (mysqli_num_rows($rs_comp) > 0) {
		?><script>
				alert ("No se puede dar de alta a ese plan de cuenta ya existe uno con ese código.");
				parent.$('idOfDomElement').colorbox.close();

			</script><?php
		}
	} else {
		$consultaprevia = "SELECT max(`codplan`) as maximo FROM `plandecuentas`";
		$rs_consultaprevia=mysqli_query($GLOBALS["___mysqli_ston"], $consultaprevia);
		if($rs_consultaprevia!==FALSE) {
		$codarticulo=mysqli_result($rs_consultaprevia, 0, "maximo");
		}
		if ($codarticulo=="") { $codarticulo=0; }
		$codarticulo++;

		$query_operacion="INSERT INTO plandecuentas (`codplan`, `nombre`, `m`, `operaen`, `borrado`)	VALUES ('$codplan', '$nombre', '$m','$operaen', '0')";				
		$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
		
		if ($rs_operacion) { $mensaje="El plan de cuentas ha sido dado de alta correctamente"; }
		$cabecera1="Inicio >> Plan de cuentas &gt;&gt; Nuevo Articulo ";
		$cabecera2="INSERTAR Plan de cuentas ";
		}
}

if ($accion=="modificar") {

	$query="UPDATE  `plandecuentas` SET `codplan`='$codplan', nombre='$nombre', `m`='$m', `operaen`='$operaen', borrado=0 WHERE `plandecuentas`.`codplan` ='$codplan'";
	
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="Los datos del plan de cuenta han sido modificados correctamente"; }
	$cabecera1="Inicio >> Plan de cuenta &gt;&gt; Modificar Articulo ";
	$cabecera2="MODIFICAR Plan de cuenta ".$fileerror;
}

if ($accion=="baja") {
	$codarticulo=$_POST["codarticulo"];
	$query="UPDATE plandecuentas SET borrado=1 WHERE codplan='$codplan'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El plan de cuenta ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> PLAN DE CUENTAS &gt;&gt; Eliminar Articulo ";
	$cabecera2="ELIMINAR PLAN DE CUENTAS ";
	$query_mostrar="SELECT * FROM plandecuentas WHERE codplan='$codplan'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	$codplan=mysqli_result($rs_mostrar, 0, "codplan");
	$nombre=mysqli_result($rs_mostrar, 0, "nombre");
	$m=mysqli_result($rs_mostrar, 0, "m");
	$operaen=mysqli_result($rs_mostrar, 0, "operaen");
	
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		

<script src="../js3/jquery.min.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../js3/colorbox.css" />
<script src="../js3/jquery.colorbox.js"></script>

<script src="../js3/jquery.maskedinput.js" type="text/javascript"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
				<?php echo $mensaje;?>
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
				<tr>
					<td valign="top">Código</td>
			      <td  valign="top"><input name="Acodplan" id="codplan" maxlength="70" class="cajaGrande" type="text" value="<?php echo $codplan;?>"></td>
					<td valign="top">Descripci&oacute;n</td>
					<td valign="top"><textarea name="Adescripcion" cols="31" rows="2" id="descripcion" class="areaTexto"><?php echo $nombre;?></textarea></td>
				</tr>
		      <tr>
					<td valign="top">M</td>
			      <td valign="top"><input name="m" id="m" maxlength="60" class="cajaGrande" type="text" value="<?php $m;?>"></td>
					<td valign="top">Opera en</td>
			      <td valign="top"><input name="operaen" id="operaen" maxlength="60" class="cajaGrande" type="text" value="<?php echo $operaen;?>"></td>
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
