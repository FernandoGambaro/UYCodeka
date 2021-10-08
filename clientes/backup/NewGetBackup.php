<?php


require realpath(dirname(__FILE__)).'/vendor/autoload.php';

use Ddeboer\Imap\Server;

$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);

require_once $conexionPath. '/classes/Encryption.php';


ini_set('default_socket_timeout', 6000);
set_time_limit(120);
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

/*Extraigo datos del mail */
require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$objdatos = new Consultas('datos');
$objdatos->Select();
$objdatos->Where('coddatos', '0'); 
						
$datos = $objdatos->Ejecutar();
$rowdatos = $datos["datos"]; 

$emailname= $rowdatos[0]['emailname'];
$emailsend= $rowdatos[0]['emailsend'];
$emailreply=$rowdatos[0]['emailreply'];

$converter = new Encryption;
$emailpass = $converter->decode($rowdatos[0]['emailpass']);					

$emailhost=$rowdatos[0]['emailhost'];
$emailssl=$rowdatos[0]['emailssl'];
$emailpuerto=$rowdatos[0]['emailpuerto'];
$emailhostenvio=$rowdatos[0]['emailhostenvio'];
$emailsslenvio=$rowdatos[0]['emailsslenvio'];
$emailpuertoenvio=$rowdatos[0]['emailpuertoenvio'];

$emailbody=$rowdatos[0]['emailbody'];


//$mailbox =$emailhost;
$username =$emailsend;
$password = $emailpass;

// Open connection
try{

$server = new Server(
    $emailhost, // required
    $emailpuerto,     // defaults to '993'
    '/imap/ssl/novalidate-cert'    // defaults to '/imap/ssl/validate-cert'
);
	/*Para pruebas habilito cada sector*/
	 $pruebaA="N";
	 $pruebaB="N";
	 $pruebaC="N";
	 $pruebaD="N";
	 $pruebaE="N";
	 $Hoynum=strtotime(date("Y/m/d H:i:s"));
	 
//echo "The current read timeout is " . imap_timeout(IMAP_READTIMEOUT) . "\n";
	imap_timeout(2,120);
//echo "The current read timeout is " . imap_timeout(IMAP_READTIMEOUT) . "\n";

// $connection is instance of \Ddeboer\Imap\Connection
$connection = $server->authenticate($username, $password);	
	
$mailbox = $connection->getMailbox('INBOX');

$messages = $mailbox->getMessages();


$today = new DateTimeImmutable();
$thirtyDaysAgo = $today->sub(new DateInterval('P30D'));

$messages = $mailbox->getMessages(
    new Ddeboer\Imap\Search\Date\Since($thirtyDaysAgo),
    \SORTDATE, // Sort criteria
    true // Descending order
);

foreach ($messages as $message) {
		
		/*Recorro todos los mail y extraigo la info*/
		/*De toda la info que trae el mail solo me interesa*/
		//var_dump($message->getBodyText());
		$body=$message->getBodyText();
		$from=$message->getFrom()->getName();
		//echo "<br>";
		//var_dump($message->getDate()->format("Y-m-d"));

/*Para saber que métodos tiene esta clase */
/*
$mtodos_clase = get_class_methods($message->getDate());
foreach ($mtodos_clase as $nombre_mtodo) {
    echo "$nombre_mtodo <br>";
}		
*/
		/*Reviso y asigno a cobian backup*/
		$CobianSubject = strpos($message->getSubject(),"Cobian Backup 11");
		$CobianFrom = strpos($message->getFrom()->getName(),"Cobian Backup 11");
		$CobianBody = strpos($body,"Cobian Backup 11");
		
		if(($CobianSubject !== false or $CobianBody!==false or $CobianFrom!==false) and $pruebaA=="N") {
			$Fecha=$udate=$messagero=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version=$FechaAux='';
			$Seccion="Cobian_Backup_11"; //Respaldos realizados con cobian backup.
			$version= "Cobian Backup 11";
			$tmp_aux=explode("-",$from );
			$Cliente=$tmp_aux[0];
			/*Busco el código del cliente*/
			$objdatos = new Consultas('clientes');
			$objdatos->Select();
			$objdatos->Where('empresa', $Cliente, 'LIKE'); 
			$datos = $objdatos->Ejecutar();
			if($datos['numfilas']>0){
			$rowdatos = $datos["datos"]; 
			$codcliente= $rowdatos[0]['codcliente'];
			}
			/*Fin busqueda cliente*/
			if(is_array($tmp_aux) and count($tmp_aux)>1) {
		 	$Equipo=$tmp_aux[1];
		 	}
		if(strpos($Equipo,$emailname)) {
			$Equipo=str_replace($emailname, '', $Equipo);
		}
			$Equipo=trim($Equipo);
			$udate=$message->getDate()->getTimestamp();
		$lines = explode("\n", $body);
		$Tarea='';
		
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);
		
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
		Proceso cada uno y lo grabo*/
		
					if(strtotime($FechaAux)!=strtotime($FechaControl)) {
						/*Guardo en base de datos*/
						
						if ($codcliente>1 and $Equipo !=''){
								
						$Controlados[$Seccion]++;
						
						$objrespaldos = new Consultas('respaldospc');
						$objrespaldos->Select();
						$objrespaldos->Where('fecha', $Fecha, '=', 'AND'); 
						$objrespaldos->Where('udate', $udate, '=', 'AND'); 
						$objrespaldos->Where('tarea', $Tarea, 'LIKE', 'AND'); 
						$objrespaldos->Where('errores', $Errores, 'LIKE', 'AND'); 
						$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
						$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
						$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
						$objrespaldos->Where('usuario', $Equipo, '='); 
					
						$respaldos = $objrespaldos->Ejecutar();
						
							if($respaldos['numfilas']==0){
								$valores=array();
								$nombres=array();

								$nombres[] = 'fecha';       $valores[] = $Fecha;
								$nombres[] = 'udate';       $valores[] = $udate;
								$nombres[] = 'message';     $valores[] = $messagero;
								$nombres[] = 'tarea';       $valores[] = $Tarea;
								$nombres[] = 'errores';     $valores[] = $Errores;
								$nombres[] = 'procesados';  $valores[] = $Procesados;
								$nombres[] = 'respaldados'; $valores[] = $Respaldados;
								$nombres[] = 'tamano';      $valores[] = $Tamano;
								$nombres[] = 'codcliente';  $valores[] = $codcliente;
								$nombres[] = 'usuario';     $valores[] = $Equipo;
								$nombres[] = 'version';     $valores[] = $version;
						
								$objrespaldos = new Consultas('respaldospc');
								$objrespaldos->Insert($nombres, $valores);
								$respaldos = $objrespaldos->Ejecutar();
								if($respaldos['estado']=='ok'){
									$Guardados[$Seccion]++;
									$save="Si";
								}else{
									$save='No';
								}

							} else {
							$save="existe";	
							}
						}
					}
		      $FechaAux=$FechaControl;
			}
		} 	
		
		} elseif((strpos($message->getSubject(),"Tarea")!==false) and $pruebaB=="N"){
			$Fecha=$udate=$messagero=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Acronis_Espaniol"; //Se asigno 1 a los respaldos realizados con Acronis.
			$version= "Acronis 11.5";
		
			$Fecha=$message->getDate()->format("Y/m/d H:i:s");
			$udate=$message->getDate()->getTimestamp();
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);	
		
			$largo=strlen($message->getSubject());
			$inipos=strpos($message->getSubject(),"'.");
			$ClienteEquipo= substr($message->getSubject(), $inipos+2, $largo-$inipos);
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
			$objdatos = new Consultas('clientes');
			$objdatos->Select();
			$objdatos->Where('empresa', $Cliente, 'LIKE'); 
			$datos = $objdatos->Ejecutar();
			if($datos['numfilas']>0){
			$rowdatos = $datos["datos"]; 
			$codcliente= $rowdatos[0]['codcliente'];
			}
			/*Fin busqueda cliente*/
			if(is_array($tmp_aux) and count($tmp_aux)>1) {
			$Equipo=trim($tmp_aux[1]);
			}
		$Tarea= extract_unit($message->getSubject(), "[ABR11.5]: Tarea '", "' se");
		if(strpos($message->getSubject(),"correctamente")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		} elseif((strpos($message->getSubject(),"Task")!==false) and $pruebaC=="N"){
			$Fecha=$udate=$messagero=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Acronis_Ingles"; //Respaldos realizados con Acronis en ingles.
			if(strpos($message->getSubject(),"'[ABR11.5]: Task")!==false) {
				$version= "Acronis 11.5";
			} else {
				$version="Acronis 11.7";
			}
		
			$Fecha=$message->getDate()->format("Y/m/d H:i:s");
			$udate=$message->getDate()->getTimestamp();
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);
			
			$largo=strlen($message->getSubject());
			$inipos=strpos($message->getSubject(),"'. ");
			$ClienteEquipo= substr($message->getSubject(), $inipos+2, $largo-$inipos);
		
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
			$objdatos = new Consultas('clientes');
			$objdatos->Select();
			$objdatos->Where('empresa', $Cliente, 'LIKE'); 
			$datos = $objdatos->Ejecutar();
			if($datos['numfilas']>0){
			$rowdatos = $datos["datos"]; 
			$codcliente= $rowdatos[0]['codcliente'];
			}

			/*Fin busqueda cliente*/
			$Equipo=trim($tmp_aux[1]);
		
		$Tarea= extract_unit($message->getSubject(), "Task '", "'" );
		
		if(strpos($message->getBodyText(),"succeeded")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		}elseif((strpos($message->getSubject(),"Duplicati")!==false) and $pruebaD=="N"){
			$Fecha=$udate=$messagero=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion="Duplicati"; //Respaldos realizados con Duplicati
			$version= "Duplicati";
		
			$Fecha=$message->getDate()->format("Y/m/d H:i:s");
			$udate=$message->getDate()->getTimestamp();
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);	
			$largo=strlen($message->getSubject());
			$inipos=strpos($message->getSubject(),"for");
			$ClienteEquipo= substr($message->getSubject(), $inipos+4, $largo-$inipos);
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=trim($tmp_aux[0]);
			/*Busco el código del cliente*/
			$objdatos = new Consultas('clientes');
			$objdatos->Select();
			$objdatos->Where('empresa', $Cliente, 'LIKE'); 
			$datos = $objdatos->Ejecutar();
			if($datos['numfilas']>0){
			$rowdatos = $datos["datos"]; 
			$codcliente= $rowdatos[0]['codcliente'];
			}
			/*Fin busqueda cliente*/
			$Equipo=trim($tmp_aux[1]);
			$Tarea= trim($tmp_aux[2]);
		
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);
		if(strpos($message,"FilesWithError: 0")) {
			$Errores= 0;
		} else {
			$Errores= 1;
		}
		
		
		}elseif( $pruebaE=="N") {
			$Fecha=$udate=$messagero=$Tarea=$Errores=$Procesados=$Respaldados=$Tamano=$codcliente=$Equipo=$version='';
			$Seccion='MCC'; //Respaldos realizados con MCC.
			$version= "MCC 1.05.8.19";
		
			$Fecha=$message->getDate()->format("Y/m/d H:i:s");
			$udate=$message->getDate()->getTimestamp();
			$msgAux=str_replace("\\", "\\\\",$message->getBodyText());
			$messagero=str_replace("'", '"', $msgAux);
				
			$largo=strlen($message->getSubject());
			$inipos=strpos($message->getSubject(),"(");
			$ClienteEquipo= extract_unit($message->getSubject(), "[MCC] (", ")");
			$tmp_aux=explode("-",$ClienteEquipo );
			$Cliente=$tmp_aux[0];
			if($Cliente!='') {
			/*Busco el código del cliente*/
			$objdatos = new Consultas('clientes');
			$objdatos->Select();
			$objdatos->Where('empresa', $Cliente, 'LIKE'); 
			$datos = $objdatos->Ejecutar();
			if($datos['numfilas']>0){
			$rowdatos = $datos["datos"]; 
			$codcliente= $rowdatos[0]['codcliente'];
			}
			/*Fin busqueda cliente*/
			$inipos = strpos($msgAux,"Tamaño:");
			$Tamano = substr($msgAux, $inipos+7, 6);
			$inipos = strpos($msgAux,"Respaldados:");
			$Respaldados = substr($msgAux, $inipos+13, 5);
		$Equipo=trim($tmp_aux[1]);
		$Tarea= extract_unit($message->getSubject(), "[MCC] (", ")");
		$Errores=0;
			$Controlados[$Seccion]++;
		
			}
		}

		/*Guardo en base de datos*/
		if ($codcliente>1 and $Equipo !='' and strlen($Fecha)>0 and strlen($udate)>0 and $pruebaA="N" and $pruebaB="N" and $pruebaC="N" and $pruebaD="N" and $pruebaD="N"){
			
			$Controlados[$Seccion]++;
					
			$objrespaldos = new Consultas('respaldospc');
			$objrespaldos->Select();
			$objrespaldos->Where('fecha', $Fecha, '=', 'AND'); 
			$objrespaldos->Where('udate', $udate, '=', 'AND'); 
			$objrespaldos->Where('tarea', $Tarea, 'LIKE', 'AND'); 
			$objrespaldos->Where('errores', $Errores, 'LIKE', 'AND'); 
			$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
			$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
			$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
			$objrespaldos->Where('usuario', $Equipo, '='); 
		
			$respaldos = $objrespaldos->Ejecutar();
			
				if($respaldos['numfilas']==0){
					$valores=array();
					$nombres=array();

					$nombres[] = 'fecha';       $valores[] = $Fecha;
					$nombres[] = 'udate';       $valores[] = $udate;
					$nombres[] = 'message';     $valores[] = $messagero;
					$nombres[] = 'tarea';       $valores[] = $Tarea;
					$nombres[] = 'errores';     $valores[] = $Errores;
					$nombres[] = 'procesados';  $valores[] = $Procesados;
					$nombres[] = 'respaldados'; $valores[] = $Respaldados;
					$nombres[] = 'tamano';      $valores[] = $Tamano;
					$nombres[] = 'codcliente';  $valores[] = $codcliente;
					$nombres[] = 'usuario';     $valores[] = $Equipo;
					$nombres[] = 'version';     $valores[] = $version;
			
					$objrespaldos = new Consultas('respaldospc');
					$objrespaldos->Insert($nombres, $valores);
					$respaldos = $objrespaldos->Ejecutar();
					if($respaldos['estado']=='ok'){
						$Guardados[$Seccion]++;
						$save="Si";
					}else{
						$save='No';
					}					
				} else {
				$save="existe";	
				}
			
		}
		$Seccion='';
			
$message->markAsSeen();			
//$message->setFlag('\\Seen ');
		//	var_dump($proceso);
		//echo $proceso['header']['uid']."<br>";
		/*Elimino los mail mas viejos 48 horas */
		//echo "<br>ID ".$message->getNumber();
		$diff=$Hoynum-$message->getDate()->getTimestamp();
				if ($diff>= 172800) {
					echo " ";
						$mailbox->getMessage($message->getNumber())->delete();
						$connection->expunge();
				} 
			
				/*else {
					echo "";
				}*/
		}
		
}catch (ImapClientException $error){
    echo "hay error ".$error->getMessage().PHP_EOL;
    die(); // Oh no :( we failed
}

/*
$result = dns_get_record($emailhostenvio, DNS_ALL - DNS_PTR);
var_dump($result);
echo "<br><p>";
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/Exception.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/SMTP.php';


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
									if(strlen($key)>0) {
									$message.="<br>".$key." :".$item;
									}
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