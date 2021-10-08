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
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php';   

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


require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head>
				<link href="../library/estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../library/calendario/jscal2.js"></script>
    <script src="../library/calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../library/calendario/css/win2k/win2k.css" />		

<script src="../library/js/jquery.min.js"></script>
<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../library/toastmessage/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="../library/colorbox/colorbox.css" />
<script src="../library/colorbox/jquery.colorbox.js"></script>

<script src="../library/js/jquery.maskedinput.js" type="text/javascript"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">

<title>Inicio</title>
<script type="text/javascript">
function validate_password()
{
	//Cogemos los valores actuales del formulario
	pasActual=document.formName.passwordActual;
	pasNew1=document.formName.passwordNew1;
	pasNew2=document.formName.passwordNew2;
	//Cogemos los id's para mostrar los posibles errores
	id_epassActual=document.getElementById("epasswordActual");
	id_epassNew=document.getElementById("epasswordNew1");
	//Patron para los numeros
	var patron1=new RegExp("[0-9]+");
	//Patron para las letras
	var patron2=new RegExp("[a-zA-Z]+");
	if(pasNew1.value==pasNew2.value && pasNew1.value.length>=6 && pasActual.value!="" && pasNew1.value.search(patron1)>=0 && pasNew1.value.search(patron2)>=0){
		//Todo correcto!!!
		document.formName.submit();
		return true;
	}else{
		if(pasNew1.value.length<6){
		showWarningToast("La longitud mínima tiene que ser de 6 caracteres.");
			id_epassNew.innerHTML="La longitud mínima tiene que ser de 6 caracteres";
		}else if(pasNew1.value!=pasNew2.value){
			id_epassNew.innerHTML="La copia de la nueva contraseña no coincide";
		}else if(pasNew1.value.search(patron1)<0 || pasNew1.value.search(patron2)<0){
			id_epassNew.innerHTML="La contraseña tiene que tener numeros y letras";
		}else{
			id_epassNew.innerHTML="";
		}
		if(pasActual.value==""){
			id_epassActual.innerHTML="Indicar tu contraseña actual";
			showWarningToast("Indicar tu contraseña actual");
		}else{
			id_epassActual.innerHTML="";
		}
		return false;
	}
}
</script>    
    

</head>

</head>
<body >
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">

<div id="frmBusqueda" style="font-family: Droid Sans, sans-serif;font-size: 12px; top: 5px;">

<form name="formName" action="newpassadd.php" method="post" onsubmit='return validate_password()'>
  <table class="stat" align="center" border="0" cellpadding="3" cellspacing="3">
     <tbody>
     <tr>
      <td colspan="3" class="FormEncabeza" style=" "><?php echo _('Cambio de Contraseña');?></td>
	  </tr>
	  <tr><td colspan="3"><div id="epasswordActual" style="color:#f00;"></div></td></tr>
	  <tr>
			<td width="24">&nbsp;</td>
			<td width=""><?php echo _('Contraseña actual');?> </td>
			<td><input name="Password" id="passwordActual" value="" maxlength="15" class="comboMedio" type="PASSWORD"></td>
		</tr>
		<tr><td colspan="3"><div id="epasswordNew1" style="color:#f00;"></div></td></tr>
    <tr>
			<td width="24">&nbsp;</td>
			<td width=""><?php echo _('Nueva Contraseña');?> </td>
			<td><input name="NewPassword" id="passwordNew1" value="" maxlength="15" class="comboMedio" type="PASSWORD"></td>
		</tr>
    <tr>
			<td width="24">&nbsp;</td>
			<td width=""><?php echo _('Confirmar Nueva Contraseña');?></td>
			<td><input name="NewPassword2" id="passwordNew2" value="" maxlength="15" class="comboMedio" type="PASSWORD"></td>
		</tr>
    <tr>
			<td width="24">&nbsp;</td>
			<td colspan="2" align="center">
			<button onClick="validate_password();" class="boletin"><?php echo _('Guardar');?></button>
			</td>
		</tr>
  </tbody></table>

</form>

</div>
<input type="hidden" name="alto" id="alto" value=""></input>

<div id="cal" style="position:absolute; z-index:2;">&nbsp;</div>
</div></div></div>
</body>
</html>