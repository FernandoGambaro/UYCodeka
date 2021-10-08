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
$UserMail=$s->data['UserMail'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set("America/Montevideo"); 
include("../class/PHPMailerAutoload.php");
//include("../clientes/backup/class/class.smtp.php");
include("../funciones/fechas.php");
require(dirname(__FILE__) . '/../classes/Encryption.php');

$codfactura='';
$codcliente=$_GET['codcliente'];
$documento=$_GET['documento'];
$file=$_GET['file'];
$cod=$_GET['cod'];
$tipo=$_GET['tipo'];

/*Extraigo datos del mail */
$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$emailname=mysqli_result($rs_query, 0, "emailname");
$emailsend=mysqli_result($rs_query, 0, "emailsend");
$emailreply=mysqli_result($rs_query, 0, "emailreplay");

$emailhostenvio=mysqli_result($rs_query, 0, "emailhostenvio");
$emailsslenvio=mysqli_result($rs_query, 0, "emailsslenvio");
$emailpuertoenvio=mysqli_result($rs_query, 0, "emailpuertoenvio");
$emailbody=mysqli_result($rs_query, 0, "emailbody");

$converter = new Encryption;
$emailpass = $converter->decode(mysqli_result($rs_query, 0, "emailpass"));

$subject="Envio archivo adjunto ".$file." - No responder este mail";
$usuario=$UserNom." ".$UserApe;
/*Extraigo datos del cliente*/
$query="SELECT * FROM clientes WHERE codcliente='$codcliente'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$nombre=mysqli_result($rs_query, 0, "nombre");
$apellido=mysqli_result($rs_query, 0, "apellido");
$empresa=mysqli_result($rs_query, 0, "empresa");
$uemail=mysqli_result($rs_query, 0, "email");


 $text = array("*empresa*", "*nombre*", "*apellido*", "*usuario*", "*documento*", "<strong>", "</strong>", "<title>Untitled document</title>","<p>Untitled document</p>"); 
 $replace = array($empresa, $nombre, $apellido, $usuario, $documento, "<b>", "</b>","","");
 $emailbody = str_replace($text, $replace, $emailbody); 

	if ($uemail!='') {
		
	//
	$uname=$nombre." ".$apellido;

		$ssl=$emailsslenvio."://".$emailhostenvio.":".$emailpuertoenvio."";
		$mail = new PHPMailer(); 
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		//$mail->SMTPDebug = 2;
		$mail->IsSMTP(); 
		$mail->Host = $emailhostenvio;	
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $emailsslenvio;	
		$mail->Port =$emailpuertoenvio;		
							
		$mail->Username = $emailsend;
		$mail->Password = $emailpass;
		$mail->From = $emailsend;
		$mail->FromName = $emailname;
		//$mail->SMTPSecure = $emailssl;
		//$mail->Port = $emailpuerto;


		$mail->From = $emailsend;
		$mail->FromName = $emailname;
		$mail->Subject = $subject;
		// Cuenta de E-Mail Destinatario  
		$mail->CharSet = "UTF-8";
		$mail->ClearReplyTos();
		if($emailreply!='') {
		$mail->addReplyTo($emailreply, $emailname);
		} else {
		$mail->addReplyTo($UserMail,$usuario);
		}
		/*Datos del destinatario*/
		$mail->AddAddress($uemail,$uname);

		/*Adjunto archivo*/
		$mail->AddAttachment("../tmp/".$file, $file);
		$mail->WordWrap = 900;
		$mail->IsHTML(true);

		$message = $emailbody;
			
		$mail->MsgHTML($message);
		
				if($mail->Send()){
					if($tipo=='F' and $cod!='') {
						$query="UPDATE facturas SET `enviada`=1 WHERE codfactura='$cod'";
						$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
					} elseif($tipo=='R' and $cod!='') {
						$query="UPDATE recibos SET `enviado`=1 WHERE codrecibo='$cod'";
						$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
					}
					echo '<br><img src="../img/enviook.jpg" width="240" height="70" alt=""><br><center>Envio exitoso</center><br> <script>setTimeout(function() {window.close()}, 1000);
					top.parent.showModal(0);</script>';
				}else{
					//error_reporting(E_ALL);
					//$command='zenity --error --text="Error al chequear mail de respaldos!" --title="Alerta!"';
					exec('/usr/bin/notify-send notify-send -u critical -t 2500 "Error al enviar mail!"');

					echo 'Fallo el envío!'. $mail->ErrorInfo;
					echo "<script>setTimeout(function() {window.close()}, 1000);</script>";
				}		 

	} else {
	echo "Destinatario no válido";
	// echo "<script>setTimeout(function() {window.close()}, 1000);</script>";
	}
	((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
?>