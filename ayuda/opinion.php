<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php';   


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
$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

date_default_timezone_set("America/Montevideo"); 

include("../classes/PHPMailerAutoload.php");
include("../funciones/fechas.php");
require_once __DIR__ . '/../classes/Encryption.php';

/*Extraigo datos del mail */

require_once __DIR__ .'/../library/conector/consultas.php';

use App\Consultas;

    $objdatos = new Consultas('datos');
    $objdatos->Select();
    $objdatos->Where('coddatos', '0');    
    $datos = $objdatos->Ejecutar();
    $total_rows=$datos["numfilas"];
    $rows = $datos["datos"][0];
    $ShowName=$razonsocial=$rows['razonsocial'];
    $direccion=$rows['direccion'];
    $telefono=$rows['telefono1'];
    $fax=$rows['fax'];
    $email=$rows['mailv'];
	$web=$rows['web'];

	$emailname =  $rows['emailname'];
	$emailsend =  $rows['emailsend'];
	$emailpass =  $rows['emailpass'];
	$emailhost =  $rows['emailhostenvio'];
	$emailssl =  $rows['emailsslenvio'];
	$emailpuerto =  $rows['emailpuertoenvio'];

$converter = new Encryption;
$emailpass = $converter->decode($emailpass);	

$emailreply=$rows["emailreply"];

$usuario=$UserNom." ".$UserApe;

//$nombre=isset($_POST["nombre"]) ? @$_POST["nombre"] : null ;


if($_POST)
{
	$message=isset($_POST["message"]) ? @$_POST["message"] : null ;
	$control=isset($_GET["control"]) ? $_GET["control"] : 1 ;
	$agree=isset($_POST["agree"]) ? $_POST["agree"] : null ;
}else{
	$message='' ;
	$control= 1 ;
	$agree='';

}
$emailbody=$message;

//echo $UserID."<>".$message."<>".$ask."<>".$agree."<- ->".$control;

	if ($UserNom!='' and $agree==1) {
	$uname=$UserNom." ".$UserApe;


	$uemail="soporte@mcc.com.uy";

//Servidor SMTP - GMAIL usa SSL/TLS  
//como protocolo de comunicación/autenticación por un puerto 465.  
$ssl=$emailssl."://".$emailhost.":".$emailpuerto."";
// Instanciando el Objeto  
$mail = new PHPMailer(); 
$mail->IsSMTP(); 
//Servidor SMTP - GMAIL usa SSL/TLS  
//como protocolo de comunicación/autenticación por un puerto 465.  
 $mail->Host = $ssl;
// True para que verifique autentificación  
 $mail->SMTPAuth = true;  
// Cuenta de E-Mail & Password  
 $mail->Username = $emailsend;
 $mail->Password = $emailpass;
 $mail->From = $emailsend;
 $mail->FromName = $emailname;
 $mail->CharSet = "UTF-8";

			 $mail->Subject = "Sugerencia sobre el sistema UYCODEKA ".$ShowName;
			 // Cuenta de E-Mail Destinatario  
			 $mail->CharSet = "UTF-8";
			 if($emailreply!='') {
			 $mail->AddReplyTo($emailreply, $emailname);
			 } else {
			 $mail->AddReplyTo($emailsend, $emailname);
			 }
				/*Datos del destinatario*/
			 $mail->AddAddress($uemail,$uname);
			 
			 $mail->WordWrap = 900;
			 $mail->IsHTML(true);
		
						$message = $emailbody;
				
			$mail->MsgHTML($message);
			
        if (!$mail->send()) {
            $msg = 'Estamos realizando ajustes, intente mas tarde.';
        } else {
			$msg = 'Mesaje enviado! Gracias por su tiempo.';
			

        } 
/*		
							$query="UPDATE usuarios SET `ask`=0 WHERE codusuarios=$UserID";
							$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
							   $s->data['ask']=0;
    							$s->save(); 		
		((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
*/       
		}
?>				
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Valoramos su opinión</title>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

    <link href="../library/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="../library/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>


	<link rel="stylesheet" href="../library/colorbox/colorbox.css?u=<?php echo time();?>" />
	<script src="../library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

    <script src="../library/js/cargadatos.js" type="text/javascript"></script>
    <script src="../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
<script src="../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>


<link rel="stylesheet" href="../library/js/msgBoxLight.css?u=<?php echo time();?>" type="text/css">
<script type="text/javascript" src="../library/js/jquery.msgBox.js"></script>

<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>


<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">

</head>
<body>
<div class="content-fluid">
	<fieldset class="scheduler-border">
		<legend class="scheduler-border">Valoramos su opinión para seguir mejorando el sistema</legend>
		<div class="input-group input-group-sm">
			
		<?php
		if (!empty($msg)) {
			echo "<h2>$msg</h2>";
		}  else {
		?>
				
			<form method="post" action="opinion.php" >
			<div class="row">
				<div class="col-xs-4">
					Nombre:&nbsp;<input name="nombre" id="nombre" value="<?php echo $UserNom." ".$UserApe;?>" maxlength="50" class="cajaGrande" type="text" readonly="">
				</div>
				<div class="col-xs-8">
					<label><input type="checkbox" name="agree" value="1" checked> Acepto enviar estas sugerencias&nbsp;<span></span></label>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<textarea id="message" name="message" rows="25" cols="80" style="width:100%; top:-100px;z-index:12;">    
					</textarea>       
					<script src="../library/ckeditor/ckeditor.js?ver<%=DateTime.Now.Ticks.ToString()%>"></script>
					<script src="../library/nanospell/autoload.js?ver<%=DateTime.Now.Ticks.ToString()%>"></script>
					<script src="../library/ckeditor/config.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>
					<script src="../library/ckeditor/lang/es.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>
					<script src="../library/ckeditor/styles.js?ver<%=DateTime.Now.Ticks.ToString()%>" type="text/javascript"></script>
					<script type="text/javascript">
						var editor = CKEDITOR.replace( 'message', {
							customConfig: 'config.js',
							width: '100%',
							height: 220,                    
							fullPage: true,
							allowedContent: true,
							language: 'es',
							coreStyles_bold: { element: 'b' },
							coreStyles_italic: { element: 'i' },
							extraPlugins: 'enterkey',
							removePlugins : 'easyimage, cloudservices',
							enterMode: CKEDITOR.ENTER_BR,
							shiftEnterMode: 2,    
							extraPlugins: 'notification',
							removePlugins: 'autosave,scayt,wsc',
							disableNativeSpellChecker: false
						});
					</script>

					<script>
					nanospell.ckeditor('estudio_text',{
						dictionary : "es",  // 24 free international dictionaries  
						server : "php"      // can be php, asp, asp.net or java
					}); 
					</script>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<input type="submit" value="Enviar">
				</div>
			</div>
				
			</form>
		<?php 
		}
		?>
		</div>
	</fieldset>
</div>
</body>
</html>