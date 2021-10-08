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

date_default_timezone_set("America/Montevideo"); 
require_once '../class/swiftmailer/lib/swift_required.php';

include("../funciones/fechas.php");
include("encrypt_decrypt.php");

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
$emailreply=mysqli_result($rs_query, 0, "emailreply");
$emailpass=encrypt_decrypt('decrypt', mysqli_result($rs_query, 0, "emailpass"));
$emailhost=mysqli_result($rs_query, 0, "emailhost");
$emailssl=mysqli_result($rs_query, 0, "emailssl");
$emailpuerto=mysqli_result($rs_query, 0, "emailpuerto");
$emailbody=mysqli_result($rs_query, 0, "emailbody");

$subject="Envio archivo adjunto ".$file;

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
	$uname=$nombre." ".$apellido;		
// Create the Transport
$transport = Swift_SmtpTransport::newInstance($emailhost,$emailpuerto);
$transport->setUsername($emailsend);
$transport->setPassword($emailpass);
		
$mailer = Swift_Mailer::newInstance($transport);		

// Create the message
$message = Swift_Message::newInstance();

  // Give the message a subject
$message ->setSubject($subject);

  // Set the From address with an associative array
$message ->setFrom(array($emailsend => $emailname));

  // Set the To addresses with an associative array
$message->setTo(array($uemail =>$uname ));

  // Give it a body
$message->setBody($emailbody, 'text/html');
  // And optionally an alternative body
$message->addPart('<q>Here is the message itself</q>', 'text/html');

  // Optionally add any attachments
$message->attach(Swift_Attachment::fromPath("../tmp/".$file)->setFilename($file)) ;		
		
$message->setMaxLineLength(1000);

// Send the message
$result = $mailer->send($message);		
	
	/*	
	//$uemail="soporte@mcc.com.uy";
	$uname=$nombre." ".$apellido;

	$ssl="ssl://".$emailhost.":".$emailpuerto."";
			// Instanciando el Objeto  
			$mail = new PHPMailer(); 
			$mail->IsSMTP(); 
			 //Servidor SMTP - GMAIL usa SSL/TLS  
			 //como protocolo de comunicación/autenticación por un puerto 465.  
			 $mail->Host = $ssl;
			 //$mail->Host = 'ssl://smtp.gmail.com:465';  
			 // True para que verifique autentificación  
			 $mail->SMTPAuth = true;  
			 // Cuenta de E-Mail & Password  
			 $mail->Username = $emailsend;
			 $mail->Password = $emailpass;
		
			 $mail->From = $emailsend;
			 $mail->FromName = $emailname;
			 $mail->Subject = $subject;
			 // Cuenta de E-Mail Destinatario  
			 $mail->CharSet = "UTF-8";
			 if($emailreply!='') {
			 $mail->AddReplyTo($emailreply, $emailname);
			 } else {
			 $mail->AddReplyTo($emailsend, $emailname);
			 }
				/*Datos del destinatario*/
				/*
			 $mail->AddAddress($uemail,$uname);
			 
			 /*Adjunto archivo*/
			 /*
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
				 	  echo '<br><img src="../img/enviook.jpg" width="240" height="70" alt=""><br><center>Envio exitoso</center><br> <script>setTimeout(function() {window.close()}, 1000);</script>';
					}else{
					   echo 'Fallo el envío!'. $mail->ErrorInfo;
				 	 //  echo "<script>setTimeout(function() {window.close()}, 10000);</script>";
					}		 

		} else {
		echo "Destinatario no válido";
 	  // echo "<script>setTimeout(function() {window.close()}, 1000);</script>";
		}
		((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
		*/
		}
?>