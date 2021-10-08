<?php
define('PW_SALT','(+3%_');

require_once __DIR__ .'/../../../library/conector/consultas.php';
use App\Consultas;



function getCurrentUrl($full = true) {
    if (isset($_SERVER['REQUEST_URI'])) {
        $parse = parse_url(
            (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off') ? 'https://' : 'http://') .
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')) . (($full) ? $_SERVER['REQUEST_URI'] : null)
        );
        $parse['port'] = $_SERVER["SERVER_PORT"]; // Setup protocol for sure (80 is default)
        return http_build_url('', $parse);
    }
}


function full_path()
{
    $s = &$_SERVER;
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    $uri = $protocol . '://' . $host . $s['REQUEST_URI'];
    $segments = explode('?', $uri, 2);
    $url = $segments[0];
    return $url;
}

function checkUNEmail($uname,$email)
{
	$error = array('status'=>false,'userID'=>0);
	if (isset($email) && trim($email) != '') {

		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$obj = new Consultas('usuarios');
		$obj->Select('codusuarios');
		$obj->Where('email', $email);
		$obj->Limit(0,1);
		$usuarios= $obj->Ejecutar();
		$numRows= $usuarios['numfilas'];
		if ($numRows >= 1) {
			$userID =  $usuarios['datos'][0]['codusuarios'];
			return array('status'=>true,'userID'=>$userID);
		} else{
			return $error;
		}

	} elseif (isset($uname) && trim($uname) != '') {
		$uname = filter_var($uname, FILTER_SANITIZE_STRING);

		$obj = new Consultas('usuarios');
		$obj->Select('codusuarios');
		$obj->Where('usuario', $uname);
		$obj->Limit(0,1);
		$usuarios= $obj->Ejecutar();
		$numRows= $usuarios['numfilas'];
		if ($numRows >= 1) {
			$userID =  $usuarios['datos'][0]['codusuarios'];
			return array('status'=>true,'userID'=>$userID);
		} else{
			return $error;
		}
	} else {
		//nothing was entered;
		return $error;
	}
}

function getSecurityQuestion($userID)
{
	$questions = array();
	$questions[1] = "¿En que ciudad nació?";
	$questions[2] = "¿Cúal es su color favorito?";
	$questions[3] = "¿En qué año se graduo de la facultad?";
	$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
	$questions[5] = "¿Cúal es su auto favorito?";
	$questions[6] = "¿Cúal es el nombre de su madre?";

	$obj = new Consultas('usuarios');
	$obj->Select('secQ');
	$obj->Where('codusuarios', $userID);
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if ($numRows >= 1) {
		$secQ =  $usuarios['datos'][0]['secQ'];
		return $questions[$secQ];
	} else{
		return false;
	}

}

function checkSecAnswer($userID,$answer)
{
	$obj = new Consultas('usuarios');
	$obj->Select('secA');
	$obj->Where('codusuarios', $userID);
	$obj->Where('secA', $answer);
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if ($numRows >= 1) {
		return true;
	} else{
		return false;
	}
}

function sendPasswordEmail($userID)
{
    $nombres = array();
    $valores = array();

	$obj = new Consultas('usuarios');
	$obj->Select('usuario,email,contrasenia');
	$obj->Where('codusuarios', $userID);
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if ($numRows >= 1) {
		$uname =  $usuarios['datos'][0]['usuario'];
		$email =  $usuarios['datos'][0]['email'];
	}

	$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+3, date("Y"));
	$expDate = date("Y-m-d H:i:s",$expFormat);
	$key = md5($uname . '_' . $email . rand(0,10000) .$expDate . PW_SALT);

	$nombres[] = '`usuario`';
	$valores[] = $userID;
	$nombres[] = '`Key`';
	$valores[] = $key;
	$nombres[] = '`expDate`';
	$valores[] = $expDate;

	$obj = new Consultas('recoverymail');
	$obj->Insert($nombres, $valores);
	$usuarios = $obj->Ejecutar();
	


	$obj = new Consultas('datos');
	$obj->Select('emailname,emailsend,emailpass,emailhostenvio,emailsslenvio,emailpuertoenvio,emailbody');
	$obj->Where('coddatos', '0');
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if ($numRows >= 1) {
		$emailname =  $usuarios['datos'][0]['emailname'];
		$emailsend =  $usuarios['datos'][0]['emailsend'];
		$emailpass =  $usuarios['datos'][0]['emailpass'];
		$emailhost =  $usuarios['datos'][0]['emailhostenvio'];
		$emailssl =  $usuarios['datos'][0]['emailsslenvio'];
		$emailpuerto =  $usuarios['datos'][0]['emailpuertoenvio'];
		//$emailbody =  $usuarios['datos'][0]['emailbody'];

	$converter = new Encryption;
	$emailpass = $converter->decode($emailpass);	

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
	 $mail->AddReplyTo($emailsend, $emailname);
	 $mail->Subject = "Su nueva contraseña es:";
	 // Cuenta de E-Mail Destinatario  
	 $mail->AddAddress($email,$email);
	 
	 $mail->WordWrap = 900;
	 $mail->IsHTML(true);
	 
	$passwordLink = "<a href=\"". full_path(). "?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "\">". full_path(). "?a=recover&email=" . $key . "&u=" . urlencode(base64_encode($userID)) . "</a>";
	$message = "Estimado/a $uname,<br>";
	$message .= "Para terminar el proceso de recuperación de contraseña visite el siguiente link:<p>";
	$message .= "<br>";
	$message .= "$passwordLink<br>";
	$message .= "<br>";
	$message .= "Asegurece de copiar la dirección en su navegador favorito, la misma expirará luego de 3 dias.<p>";
	$message .= "Si Ud. no solicito el cambio de contraseña, ningun cambio se realizara almeno que visite el link de arriba. De todas formas le recomendamos que ingrese con su cuenta y cambie la contraseña por razones de seguridad.<p>";
	$message .= "Gracias,<p>";
	$message .= "El equipo de UYCodeka";

 	$mail->Body = $message;  
	 //$mail->Send();  
		 
	 // Notificamos al usuario del estado del mensaje  
		if(!$mail->Send()){  
			$message="El envío del mail fallo, pongace en contacto con el administrador! soporte@mcc.com.uy".$mail->ErrorInfo;  
		} else {
			$message="Revise su bandeja de entrada, le hemos enviado un mail con las instrucciones.\r\n";
			$message.="El equipo de Codeka";
		}
		return str_replace("\r\n","<br/ >",$message);
	}
} 



function checkEmailKey($key,$userID)
{
	$curDate = date("Y-m-d H:i:s");

	$obj = new Consultas('recoverymail');
	$obj->Select();
	$obj->Where('usuario', $userID);
	$obj->Where('key', $key);
	$obj->Where('expDate', $curDate, '>=');
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if ($numRows >= 1 && $userID != '') {
		return array('status'=>true,'userID'=>$userID);
	}
	return false;
}

function updateUserPassword($userID,$password,$key)
{
	require_once __DIR__ .'/../../../common/funcionesvarias.php';   

	if (checkEmailKey($key,$userID) === false) return false;
	$randomhash = RandomString(24);

	$converter = new Encryption;
	$password = $converter->encode($password . $randomhash );

	$nombres[] = 'randomhash';
	$valores[] = $randomhash;
	$nombres[] = 'contrasenia';
	$valores[] = $password;

	$obj = new Consultas('usuarios');
	$obj->Select();
	$obj->Update($nombres, $valores);
	$obj->Where('codusuarios', $userID);
	$usuarios= $obj->Ejecutar();
	return  $usuarios['datos'];
	$numRows= $usuarios['numfilas'];

	if ($numRows >= 1 && $userID != '') {
		return array('status'=>true,'userID'=>$userID);
	}

	$obj = new Consultas('recoverymail');
	$obj->Delete();
	$obj->Where('Key', $key);
	$usuarios= $obj->Ejecutar();
}

function getUserName($userID)
{
	$obj = new Consultas('usuarios');
	$obj->Select('usuario');
	$obj->Where('codusuarios', $userID);
	$obj->Limit(0,1);
	$usuarios= $obj->Ejecutar();
	$numRows= $usuarios['numfilas'];
	if($numRows >= 1){
		$uname =  $usuarios['datos'][0]['usuario'];
		return $uname;
	}else{
		return false;
	}	
}
