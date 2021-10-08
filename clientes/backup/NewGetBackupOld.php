<?php
namespace program;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/Exception.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/SMTP.php';


require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/ImapClientException.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/ImapConnect.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/ImapClient.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/Section.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/Helper.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/IncomingMessage.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/IncomingMessageAttachment.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/TypeAttachments.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/TypeBody.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/SubtypeBody.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/OutgoingMessage.php";
require_once realpath(dirname(__FILE__))."/php-imap-client-master/ImapClient/AdapterForOutgoingMessage.php";

use SSilence\ImapClient\ImapClientException;
use SSilence\ImapClient\ImapConnect;
use SSilence\ImapClient\ImapClient as Imap;

$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);
include(realpath(dirname(__FILE__))."/encrypt_decrypt.php");
include($conexionPath."/conectar.php");

ini_set('default_socket_timeout', 6000);

// set default timezone
date_default_timezone_set('America/Montevideo');

/*Defino funciones útiles*/
function  cambiaf_a_normal( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $year. "/" . $month. "/" . $day;
	return $fechafinal;
}

function extract_unit($string, $start, $end)
{
	$pos = stripos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}
//Cargo los datos de la cuenta de correo para la consulta de los mails
$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$emailname=mysqli_result($rs_query, 0, "emailname");
$emailsend=mysqli_result($rs_query, 0, "emailsend");
$emailpass=encrypt_decrypt('decrypt', mysqli_result($rs_query, 0, "emailpass"));
$emailhost=mysqli_result($rs_query, 0, "emailhost");
$emailssl=mysqli_result($rs_query, 0, "emailssl");
$emailpuerto=mysqli_result($rs_query, 0, "emailpuerto");
$emailbody=mysqli_result($rs_query, 0, "emailbody");

$emailsslenvio=mysqli_result($rs_query, 0, "emailsslenvio");
$emailpuertoenvio=mysqli_result($rs_query, 0, "emailpuertoenvio");
$emailhostenvio=mysqli_result($rs_query, 0, "emailhostenvio");


$mailbox = $emailhost;
$username =$emailsend;
$password = $emailpass;
if($emailssl=='ssl') {
	$encryption = Imap::ENCRYPT_SSL;
} elseif($emailssl=='tls') {
	$encryption =Imap::ENCRYPT_TLS;
} else {
	$encryption ='';
}
// Open connection
try{
    $imap = new Imap($mailbox, $username, $password, $encryption);
		// Selecciono la carpeta inbox
		$imap->selectFolder('INBOX');
		// Count the messages in current folder
		$overallMessages = $imap->countMessages();
		$unreadMessages = $imap->countUnreadMessages();
		// Fetch all the messages in the current folder
		$emails = $imap->getMessages();
		
		$Controlados=array();
		$Controlados['Cobian_Backup_11']='';
		$Controlados['Acronis_Espaniol']='';
		$Controlados['Acronis_Ingles']='';
		$Controlados['Duplicati']='';
		$Controlados['MCC']='';
		$version='';
		$PalabraClave='';
		$Encontrado='';
		$Guardados=array();
		$tmp_aux=array();
		$Hoy = date("d M Y H:i:s");
		$Hoynum = (int)strtotime($Hoy);
		
		/*Recorro todos los mail y extraigo la info*/
		for($xy=0; $xy<$overallMessages; $xy++ ){
		$proceso=json_decode(json_encode($emails[$xy]), true);
		//var_dump($proceso);
		/*De toda la info que trae el mail solo me interesa*/
		$body=$proceso['message']["plain"]['body'];
		$from=$proceso['header']['from'];
		
		/*Reviso y asigno a cobian backup*/
		$CobianSubject = strpos($proceso['header']['subject'],"Cobian Backup 11");
		$CobianFrom = strpos($proceso['header']['from'],"Cobian Backup 11");
		$CobianBody = strpos($body,"Cobian Backup 11");
		
		if($CobianSubject !== false or $CobianBody!==false or $CobianFrom!==false) {
			$Fecha=$udate=$message=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version=$FechaAux='';
			$Seccion="Cobian_Backup_11"; //Respaldos realizados con cobian backup.
			$version= "Cobian Backup 11";
			$tmp_aux=explode("-",$from );
			$Cliente=$tmp_aux[0];
			/*Busco el código del cliente*/
				$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$Cliente."%'";
				$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
				if ($row=mysqli_fetch_array($con_cliente)) {
					$codcliente=$row['codcliente'];
				}
			/*Fin busqueda cliente*/
			if(is_array($tmp_aux) and count($tmp_aux)>1) {
		 	$Equipo=$tmp_aux[1];
		 	}
		if(strpos($Equipo,"<respaldos@mcc.com.uy>")) {
			$Equipo=str_replace("<respaldos@mcc.com.uy>", '', $Equipo);
		}
			$Equipo=trim($Equipo);
			$udate=$proceso['header']['udate'];
		$lines = explode("\n", $body);
		$Tarea='';
		
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);
		
		foreach ($lines as $line) {
			
		      if(strpos($line,"Errores:")!==false and $Tarea!=extract_unit($line, "Respaldo terminado para la tarea", ".") and strpos($line, "*** Respaldo terminado.")===false) {
		
						$Fecha= extract_unit($line, "", "**").":00";
						$FechaControl=substr(extract_unit($line, "", "**"), 0, 10);
						$Tarea =str_replace('"', '',extract_unit($line, "Respaldo terminado para la tarea", "."));
						$Errores=extract_unit($line, "Errores:", ".");
						$Procesados =extract_unit($line, "Ficheros procesados:", ".");
						$Respaldados=extract_unit($line, "Ficheros respaldados:", ".");
		
						if(strpos($line,"bytes")!==false){
							$Tam=extract_unit($line, " Tamaño total: ", "bytes");
							$Tamano=$Tam." bytes";
						}elseif(strpos($line,"KB")!==false){
							$Tam=extract_unit($line, " Tamaño total: ", "KB");
							$Tamano=(int)$Tam." KB";
						} elseif(strpos($line,"MB")!==false){
							$Tam=extract_unit($line, " Tamaño total: ", "MB");
							$Tamano=(int)$Tam." MB";
						} elseif(strpos($line,"GB")!==false){
							$Tam=extract_unit($line, " Tamaño total: ", "GB");
						 	$Tamano=(int)$Tam." GB";
						} else {
						 $Tamano=" 0 bytes ";
						}
		/*Como cobian backup puede traer en el cuerpo del mensaje varios reportes de respaldo
		Proceso cada no y lo grabo*/
					if(strtotime($FechaAux)!=strtotime($FechaControl)) {
						/*Guardo en base de datos*/
							if ($codcliente>1 and $Equipo !=''){
								$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$Fecha."' AND `udate` ='".$udate."' AND `tarea` LIKE '".$Tarea."'
								AND `errores` like '".$Errores."' 	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' 
							 	AND `codcliente` ='".$codcliente."' AND 	`usuario` LIKE '".$Equipo."'";	
							$Controlados[$Seccion]++;
							$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
							
								if (mysqli_num_rows($con_check) ==0){	
								mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");
						
								$sql="INSERT INTO `respaldospc` 
								(`codrespaldos`, `fecha`, `udate`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
								 VALUES (NULL, '".$Fecha."', '".$udate."',  '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
								  '".$Equipo."', '".$version."')";
										$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
										if ($cons == false){
											$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
											echo "Error al guardar datos ".$Seccion. " - ". $sql."<br>";
											mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
											$save="No";
										} else {
											mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
											$save="Si";	
											//echo "<p>Guardo <p>";
											@$Guardados[$Seccion]++;
										}
								} else {
								$save="existe";	
								}
							}										
					}      
		      
		      $FechaAux=$FechaControl;
			}
		} 	
		
		} elseif(strpos($proceso['header']['subject'],"Tarea")!==false){
			$Fecha=$udate=$message=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Acronis_Espaniol"; //Se asigno 1 a los respaldos realizados con Acronis.
			$version= "Acronis 11.5";
		
			$Fecha=date("Y/m/d H:i:s", $proceso['header']['udate']);
			$udate=$proceso['header']['udate'];
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);	
		
			$largo=strlen($proceso['header']['subject']);
			$inipos=strpos($proceso['header']['subject'],"'.");
			$ClienteEquipo= substr($proceso['header']['subject'], $inipos+2, $largo-$inipos);
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
				$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$Cliente."%'";
				$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
				if ($row=mysqli_fetch_array($con_cliente)) {
					$codcliente=$row['codcliente'];
				}
			/*Fin busqueda cliente*/
			if(is_array($tmp_aux) and count($tmp_aux)>1) {
			$Equipo=trim($tmp_aux[1]);
			}
		$Tarea= extract_unit($proceso['header']['subject'], "[ABR11.5]: Tarea '", "' se");
		if(strpos($proceso['header']['subject'],"correctamente")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		} elseif(strpos($proceso['header']['subject'],"Task")!==false){
			$Fecha=$udate=$message=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Acronis_Ingles"; //Respaldos realizados con Acronis en ingles.
			if(strpos($proceso['header']['subject'],"'[ABR11.5]: Task")!==false) {
				$version= "Acronis 11.5";
			} else {
				$version="Acronis 11.7";
			}
		
			$Fecha=date("Y/m/d H:i:s", $proceso['header']['udate']);
			$udate=$proceso['header']['udate'];
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);
			
			$largo=strlen($proceso['header']['subject']);
			$inipos=strpos($proceso['header']['subject'],"'. ");
			$ClienteEquipo= substr($proceso['header']['subject'], $inipos+2, $largo-$inipos);
		
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
				$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$Cliente."%'";
				$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
				if ($row=mysqli_fetch_array($con_cliente)) {
					$codcliente=$row['codcliente'];
				}
			/*Fin busqueda cliente*/
			$Equipo=trim($tmp_aux[1]);
		
		$Tarea= extract_unit($proceso['header']['subject'], "Task '", "'" );
		
		if(strpos($proceso['header']['subject'],"succeeded")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		}elseif(strpos($proceso['header']['subject'],"Duplicati")!==false){
			$Fecha=$udate=$message=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Duplicati"; //Respaldos realizados con Duplicati
			$version= "Duplicati";
		
			$Fecha=date("Y/m/d H:i:s", $proceso['header']['udate']);
			$udate=$proceso['header']['udate'];
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);	
			$largo=strlen($proceso['header']['subject']);
			$inipos=strpos($proceso['header']['subject'],"for");
			$ClienteEquipo= substr($proceso['header']['subject'], $inipos+4, $largo-$inipos);
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
				$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$Cliente."%'";
				$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
				if ($row=mysqli_fetch_array($con_cliente)) {
					$codcliente=$row['codcliente'];
				}
			/*Fin busqueda cliente*/
			$Equipo=trim($tmp_aux[1]);
			$Tarea= trim($tmp_aux[2]);
		
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);
		if(strpos($message,"FilesWithError: 0")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		
		}else {
			$Fecha=$udate=$message=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion='MCC'; //Respaldos realizados con MCC.
			$version= "MCC";
		
			$Fecha=date("Y/m/d H:i:s", $proceso['header']['udate']);
			$udate=$proceso['header']['udate'];
			$msgAux=str_replace("\\", "\\\\",$proceso['message']["plain"]['body']);
			$message=str_replace("'", '"', $msgAux);
				
			$largo=strlen($proceso['header']['subject']);
			$inipos=strpos($proceso['header']['subject'],"(");
			$ClienteEquipo= extract_unit($proceso['header']['subject'], "[MCC] (", ")");
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=$tmp_aux[0];
			if($Cliente!='') {
			/*Busco el código del cliente*/
				$sql_cliente="SELECT * FROM  `clientes` WHERE  `empresa` LIKE  '%".$Cliente."%'";
		
				$con_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
				if ($row=mysqli_fetch_array($con_cliente)) {
					$codcliente=$row['codcliente'];
				}
			/*Fin busqueda cliente*/
		$Equipo=trim($tmp_aux[1]);
		$Tarea= extract_unit($proceso['header']['subject'], "[MCC] (", ")");
		$Errores=0;
			$Controlados[$Seccion]++;
		
			}
		}
		
		
		/*Guardo en base de datos*/
			if ($codcliente>1 and $Equipo !=''){
				$check="SELECT * FROM `respaldospc` WHERE `fecha` = '".$Fecha."' AND `udate` ='".$udate."' AND `tarea` LIKE '".$Tarea."'
				AND `errores` like '".$Errores."' 	AND `procesados` ='".$Procesados."' AND `respaldados` ='".$Respaldados."' 
			 	AND `codcliente` ='".$codcliente."' AND 	`usuario` LIKE '".$Equipo."'";	
				
		//echo $check."<br><p>";
			$Controlados[$Seccion]++;
			$con_check=mysqli_query($GLOBALS["___mysqli_ston"], $check);
			
				if (mysqli_num_rows($con_check) ==0){	
				mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");
				$message=mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message);
		
				$sql="INSERT INTO `respaldospc` 
				(`codrespaldos`, `fecha`, `udate`, `message`, `tarea`, `errores`, `procesados`, `respaldados`, `tamano`, `codcliente`, `usuario`, `version`)
				 VALUES (NULL, '".$Fecha."', '".$udate."',  '".$message."', '".$Tarea."', '".$Errores."', '".$Procesados."', '".$Respaldados."', '".$Tamano."', '".$codcliente."',
				  '".$Equipo."', '".$version."')";
				 //echo "-><- ".$sql."<br><p>";
						$cons=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
						if ($cons == false){
							$Falla.=((is_object($conectar)) ? mysqli_error($conectar) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
							echo "Error al guardar datos ".$Seccion. " - ". $sql."<br>";
							mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
							$save="No";
						} else {
							mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");		
							$save="Si";	
							@$Guardados[$Seccion]++;
						}
				} else {
				$save="existe";	
				}
			}
			$Seccion='';
		//	var_dump($proceso);
		//echo $proceso['header']['uid']."<br>";
		/*Elimino los mail mas viejos 48 horas */
				$diff=$Hoynum-$proceso['header']['udate'];
				if ($diff>= 172800) {
					echo " ";
					$imap->deleteMessage($proceso['header']['uid']);
				} /*else {
					echo "";
				}*/
		}
		
}catch (ImapClientException $error){
    echo $error->getMessage().PHP_EOL;
    die(); // Oh no :( we failed
}

/*
$result = dns_get_record($emailhostenvio, DNS_ALL - DNS_PTR);
var_dump($result);
echo "<br><p>";
*/
$email="fernandogambaro@gmail.com";
$nombre="Soporte";						
				
							//echo $ssl=$emailsslenvio."://".$emailhostenvio.":".$emailpuertoenvio;
							//echo "<br>";
							$mail = new PHPMailer(); 
			 				 //$mail->SMTPDebug = 3;	
			 				 //$mail->isSendmail();
							$mail->IsSMTP(); 
			 				$mail->Host = $emailhostenvio;	
			 				 $mail->SMTPAuth = true;
							 $mail->SMTPSecure = $emailsslenvio;	
							 $mail->Port =$emailpuertoenvio;			   

  
							 $mail->Username = $emailsend;
							 $mail->Password = $emailpass;
							 $mail->From = $emailsend;
							 $mail->FromName = $emailname;
					
							 $mail->Subject = "Aviso sobre respaldos";

							 $mail->CharSet = "UTF-8";
							 $mail->AddAddress($email,$nombre);
							 
							 $mail->WordWrap = 900;
							 $mail->IsHTML(true);

							$message="Respaldos procesados: ";
							if(isset($Controlados)) {
								foreach($Controlados as $key=>$item){
									$message.="<br>".$key." :".$item;
								}
							}							
							$message.="<p>Respaldos guardados: ";
							if(isset($Guardados)) {
								foreach($Guardados as $key=>$item){
								$message.="<br>".$key." :".$item;
								}	
							}						
							$message.='<p><table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tbody>
							<tr>
							<td width="100%">
							<hr> <font size="-1">
								Estamos Comprometidos con el medio ambiente - Antes de imprimir este e-mail piensa si es necesario.
								<p>
								El presente correo electrónico y cualquier posible archivo adjunto está dirigido 
								únicamente al destinatario del mismo y contiene información que puede ser confidencial. 
								Si Ud. no es el destinatario correcto por favor notifique al remitente respondiendo 
								este mensaje y elimine inmediatamente de su sistema, el correo electrónico y los posibles 
								archivos adjuntos al mismo. Está prohibida cualquier utilización, difusión o copia de 
								este correo electrónico por cualquier persona o entidad que no sean las específicas 
								destinatarias del mensaje. MCC - Soporte Técnico no acepta ninguna responsabilidad 
								con respecto a cualquier comunicación que haya sido emitida incumpliendo lo previsto 
								en la Ley 18.331 de Protección de Datos Personales.</font>
							</td>
							</tr>
							</tbody>
							</table>';

						 	 $mail->Body = $message;  
							 /*$mail->Send();*/
							if($mail->Send()){
							    echo 'Aviso enviado con exito!<br>';
							}else{
							    echo 'Fallo el envío!'. $mail->ErrorInfo."<br>";
							}

echo $message;
?>