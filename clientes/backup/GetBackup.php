<?php
date_default_timezone_set("America/Montevideo"); 
//esta configuración es para poder ejecutarlo desde línea de comando
$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);

include(realpath(dirname(__FILE__))."/class/class.phpmailer.php");
require_once $conexionPath. '/classes/Encryption.php';

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


/*Extraigo datos del mail */


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

$emailhostenvio=$rowdatos[0]['emailhostenvio'];
$emailsslenvio=$rowdatos[0]['emailsslenvio'];
$emailpuertoenvio=$rowdatos[0]['emailpuertoenvio'];
$emailbody=$rowdatos[0]['emailbody'];

$imap='';
$verifico='';
$Tamano=0;
$Errores=0;
$displaycount='';
$Procesados=$objrespaldos=0;

error_reporting(E_ALL);
ini_set('display_errors', true);

header("Content-Type: text/html; charset=iso-8859-1 ");
header("Content-Type: text/html; charset=utf-8");

function  cambiaf_a_normal( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $year. "/" . $month. "/" . $day;
	return $fechafinal;
}

function  cambiaf_barra( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $day. "/" . $month. "/" . $year;
	return $fechafinal;
}

function  cambiaf_orden( $fecha ){
	list($month,$day,$year)=explode("-",$fecha);
	$fechafinal = $month. "/" . $day. "/" . $year;
	return $fechafinal;
} 

function  cambiaf_a_mysql( $fecha ){
	list($month,$day,$year)=explode("/",$fecha);
	$fechafinal = $year. "-" . $month. "-" . $day;	
	return  $fechafinal ;
}
/*Esta línea es la que permite conectar con el servidro de mails*/
	$imapaddress = "{barullo.zonaexterior.org:993/imap/ssl/novalidate-cert}";
	$imapmainbox = "INBOX";
	$maxmessagecount = 400;
	$user=$emailsend;
	$password=$emailpass;

    display_mail_summary($imapaddress, $imapmainbox, $user, $password, $maxmessagecount);


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

function reverse_extract_unit($string, $start, $end)
{
	$pos = strrpos($string, $start);
	$str = substr($string, $pos);
	$str_two = substr($str, strlen($start));
	$second_pos = stripos($str_two, $end);
	$str_three = substr($str_two, 0, $second_pos);
	$unit = trim($str_three); /*/ remove whitespaces*/
	return $unit;
}

function getBody($uid, $imap) {
    $body = get_part($imap, $uid, "TEXT/HTML");
    /*/ if HTML body is empty, try getting text body*/
    if ($body == "") {
        $body = get_part($imap, $uid, "TEXT/PLAIN");
    }
    return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
    if (!$structure) {
           $structure = imap_fetchstructure($imap, $uid, FT_UID);
    }
    if ($structure) {
        if ($mimetype == get_mime_type($structure)) {
            if (!$partNumber) {
                $partNumber = 1;
            }
            $text = @imap_fetchbody($imap, $uid, $partNumber, FT_UID);
            switch ($structure->encoding) {
                case 3: return imap_base64($text);
                case 4: return imap_qprint($text);
                default: return $text;
           }
       }

        /*/ multipart */
        if ($structure->type == 1) {
            foreach ($structure->parts as $index => $subStruct) {
                $prefix = "";
                if ($partNumber) {
                    $prefix = $partNumber . ".";
                }
            	 $imap='';

                $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                if ($data) {
                    return $data;
                }
            }
        }
    }
    return false;
}

function get_mime_type($structure) {
    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

    if ($structure->subtype) {
       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
    }
    return "TEXT/PLAIN";
}

function display_mail_summary($imapaddress, $imapmainbox, $imapuser, $imappassword, $maxmessagecount)
{
    $imapaddressandbox = $imapaddress . $imapmainbox;

    $connection = imap_open ($imapaddressandbox, $imapuser, $imappassword)
        or die("No se puede conectar a '" . $imapaddress . 
        "' con el usuario '" . $imapuser . 
        "' y la contraseña '" . $imappassword .
        "': " . imap_last_error());

    if (@!$connection) {
        print imap_last_error();
    }  
  
	$totalmessagecount = imap_num_msg($connection) 
		or die("No hay mensajes para mostrar: " . imap_last_error());    

	if ($totalmessagecount<$maxmessagecount)
		$displaycount = $totalmessagecount;
	else
		$displaycount = $maxmessagecount;


$check = imap_mailboxmsginfo($connection);
    //$size = number_format($check->Size / 1024, 2);

	$CobianCount=0;
	$MccCount=0;
	$AcroniCount=0;  
	$Tamano=0;
/*///////--------------------------------------------------------------------------------------*/
//$imap=$connection;
$numMessages = imap_num_msg($connection);
/*////////////Comienzo a recorrer todos los mails*/
$z=0;
$xz=0;
for ($i = $numMessages; $i > ($numMessages - $totalmessagecount); $i--) {
	$very='';
	$z++;
    $header = imap_header($connection, $i);

/*/if ($header->Unseen == "U") {*/
	
    $fromInfo = $header->from[0];
    $replyInfo = $header->reply_to[0];

    $details = array(
        "fromAddr" => (isset($fromInfo->mailbox) && isset($fromInfo->host))
            ? $fromInfo->mailbox . "@" . $fromInfo->host : "",
        "fromName" => (isset($fromInfo->personal))
            ? $fromInfo->personal : "",
        "replyAddr" => (isset($replyInfo->mailbox) && isset($replyInfo->host))
            ? $replyInfo->mailbox . "@" . $replyInfo->host : "",
        "subject" => (isset($header->subject))
            ? $header->subject : "",
        "udate" => (isset($header->udate))
            ? $header->udate : ""
    );

    $uid = imap_uid($connection, $i);
    
	// $class = ($header->Unseen == "U") ? "unreadMsg" : "readMsg";
	$cliente=$details["fromName"];

	 
 // $message =imap_body($imap, $i)	or die("Can't fetch body for message " . $i . " : " . imap_last_error());
  
 $message = getBody($uid, $connection);
//echo $message."<br><p>";
$found='';

//echo "<br>".$details["subject"]."<br>";


/********** Para CobianBK **********************/
$pos=false;
$pos = strpos($details["subject"],"Cobian Backup 11");
if($pos !== false) {
	$version= "Cobian Backup 11";
	/*/echo extract_unit($details["subject"], "(", ")");*/
	$items = array("** Respaldo terminado para la tarea ");
	$aux=21;
	$found=1;
}


//Cobian en ingles
$pos=false;
$pos = strpos($details["subject"],"Cobian Backup 11");
if($pos !== false) {
	$pos=false;
	$pos = strpos($message,"task");
	if($pos !== false) {	
	$version= "Cobian Backup 11";
	$items = array("** Backup done for the task ");
	$aux=21;
	$found=1;
	}
}

	$Equipo=extract_unit($details["subject"], "(", ")");




/************* Para acronis ******************************/

$pos=false;
$pos2=false;
$pos = strpos($details["subject"],"[ABR11.5]");
$pos2 = strpos($details["subject"],"[Acronis Backup]");

if($pos !== false or $pos2 !== false) {
	$version= "Acronis 11.5";

	$pos=false;
	$pos = strpos($details["subject"],"Task ");
	/* Ingles */
	if($pos !== false) {
		$items = array("Task '");
		$aux=21;
		$found=3;
	}else{
	/* Español */
		//$ClienteEquipo=extract_unit($details["subject"], "'", "'");
		$items = array("Tarea '");
		$aux=21;
		$found=2;
	}
	//$ClienteEquipo=extract_unit($details["subject"], ". ", "");
	$ClienteEquipo= strrchr($details["subject"], '.');
	$ClienteEquipo = ltrim($ClienteEquipo, '. ');
	//echo "Equipo-> ".$ClienteEquipo."<br>";

}

/********** Para MCC***********************/
$pos=false;
$pos = strpos($cliente,"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}
$pos=false;
$pos = strpos($details["subject"],"[MCC]");
if($pos !== false) {
	$version= "MCC BK";
	$Equipo=extract_unit($details["subject"], "(", ")");
	$items = array("Tarea '");
	$aux=21;
	$found=4;
	//echo "encontré mcc<br>";
}



/*///////////////////////////////////////////////////*/
   $appearsCount = 0;
   $Count=0;

if ($found===4) {   
/////////////////// MCC //////////////////////
	$NombreCliente=explode("-", $Equipo);
	$buscar=$NombreCliente[0];
	$EquipoUsuario=$NombreCliente[1];


if ($buscar!='') { 
	$objdatos = new Consultas('clientes');
	$objdatos->Select();
	$objdatos->Where('empresa', $buscar, 'LIKE'); 
	$datos = $objdatos->Ejecutar();

	//echo "<br>MCC".$datos['consulta']."<br>";

	if($datos['numfilas']>0){
	$rowdatos = $datos["datos"]; 
	//$clientenom = $rowdatos[0]['nombre']." ".$rowdatos[0]['apellido'];
	$codcliente= $rowdatos[0]['codcliente'];
	}

		
		 /*echo $message."<br>------------------<p>";*/ 
$pos = strpos($message,"-");
$Fecha = trim(substr($message, $pos-2,10));
	 
$FechaAux = cambiaf_barra($Fecha);
$FechaAux = cambiaf_a_mysql($FechaAux);
$pos = strpos($message,"'Copia de seguridad diaria'");
$rest = substr($message, $pos);
$rest = str_replace("'",'|',$rest);
$Detalle=explode("|", $rest);

	//$equipo=@explode(".", $Detalle[3])[0];

$pos = strpos($message,"530");
if ($pos !== false) {
 	$Errores=1;
 	$Procesados=0;
 	$Respaldados=0;
}
$pos = strpos($message,"230");
if ($pos !== false) {
 	$Errores=0;
 	$Procesados=1;
 	$Respaldados=1;
}

//Archivos Respaldados:

$Tamano=extract_unit($message, "Tamaño:", "/");

 	$Tarea=$Detalle[1];
		
	$message=str_replace("'", "|", $message);
/*	$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? 
	mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message) : 
	((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
*/

	if ($codcliente>0){

	$objrespaldos = new Consultas('respaldospc');
	$objrespaldos->Select();
	$objrespaldos->Where('fecha', $FechaAux, '=', 'AND'); 
	$objrespaldos->Where('tarea', $Tarea, '=', 'AND'); 
	$objrespaldos->Where('errores', $Errores, '=', 'AND'); 
	$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
	$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
	$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
	$objrespaldos->Where('usuario', $EquipoUsuario, '='); 

	$respaldos = $objrespaldos->Ejecutar();

	if($respaldos['numfilas']==0){
		$valores=array();
		$nombres=array();

		$nombres[] = 'fecha';
		$valores[] = $FechaAux;
		$nombres[] = 'message';
		$valores[] = $message;
		$nombres[] = 'tarea';
		$valores[] = $Tarea;
		$nombres[] = 'errores';
		$valores[] = $Errores;
		$nombres[] = 'procesados';
		$valores[] = $Procesados;
		$nombres[] = 'tamano';
		$valores[] = $Tamano;
		$nombres[] = 'codcliente';
		$valores[] = $codcliente;

		$nombres[] = 'respaldados';
		$valores[] = $Respaldados;

		$nombres[] = 'usuario';
		$valores[] = $EquipoUsuario;
		$nombres[] = 'version';
		$valores[] = $version;

		$objrespaldos = new Consultas('respaldospc');
		$objrespaldos->Insert($nombres, $valores);
		$respaldos = $objrespaldos->Ejecutar();
		if($respaldos['estado']=='ok'){
			$MccCount++;
		}
	
	}

	}


	}
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	$version="";
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $codcliente='';
		    $message='';
/****************** Fin MCC ******************/
   
   
} elseif ($found===3) {
/////////////////// Acronis ingles /////////////////////
$NombreCliente=explode("-", $ClienteEquipo);
$buscar=$NombreCliente[0];
$EquipoUsuario=$NombreCliente[1];
$EquipoUsuario = str_replace("'",'',$EquipoUsuario);

if ($buscar!='') { 

	$objdatos = new Consultas('clientes');
	$objdatos->Select();
	$objdatos->Where('empresa', $buscar, 'LIKE'); 
	$datos = $objdatos->Ejecutar();

	//echo "<br>Acronis ingles ".$datos['consulta']."<br>";


	if($datos['numfilas']>0){
	$rowdatos = $datos["datos"]; 
	//echo  $rowdatos[0]['nombre']." ".$rowdatos[0]['apellido'];
	$codcliente= $rowdatos[0]['codcliente'];
	}
		//echo $EquipoUsuario."<br><p>";
		$message."<br>------------------<p>"; 
	$pos = strpos($message,"/");
	//echo "<br>";
	$Fecha = trim(substr($message, $pos-2,10));
	//echo "<br>";		 
			
	if (strpos($EquipoUsuario, "2") > 0 and strpos($message, "PM") > 0 ) {
	$FechaAux = cambiaf_orden($Fecha);
	} else {		 
	$FechaAux = $Fecha;
	}
	$Procesados=$Respaldados='';
	//echo "Pos fecha " . strpos($message, "PM") . "<br>";
	$FechaAux = cambiaf_a_mysql($FechaAux);
	//echo "<br><p>";

	$pos = strpos($message,'Command ');
	$rest = substr($message, $pos);
	$pos1 = strpos($rest,'\'\'')+$pos;
	$Tarea = substr($message, $pos, $pos1-$pos);
	$Tarea = str_replace("'",'',$Tarea);
	$pose=strpos($message, 'Task ');

	$pos=false;
	$pos = strpos($message,"succeeded");
	if($pos !== false) {
		$Errores='';
	}else{
		$Errores='Error en el respaldo';
	}
	

//echo "<br><p>";	
	$message=str_replace("'", "|", $message);

	if ($codcliente>0 and $FechaAux!="0000-00-00"){	

	$objrespaldos = new Consultas('respaldospc');
	$objrespaldos->Select();
	$objrespaldos->Where('fecha', $FechaAux, '=', 'AND'); 
	$objrespaldos->Where('tarea', $Tarea, '=', 'AND'); 
	$objrespaldos->Where('errores', $Errores, '=', 'AND'); 
	$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
	$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
	$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
	$objrespaldos->Where('usuario', $EquipoUsuario, '='); 

	$respaldos = $objrespaldos->Ejecutar();

	//echo "<br>".$respaldos['consulta']."<br>";


	if($respaldos['numfilas']==0){
		$valores=array();
		$nombres=array();

		$nombres[] = 'fecha';
		$valores[] = $FechaAux;
		$nombres[] = 'message';
		$valores[] = $message;
		$nombres[] = 'tarea';
		$valores[] = $Tarea;
		$nombres[] = 'errores';
		$valores[] = $Errores;
		$nombres[] = 'procesados';
		$valores[] = $Procesados;
		$nombres[] = 'tamano';
		$valores[] = $Tamano;
		$nombres[] = 'codcliente';
		$valores[] = $codcliente;

		$nombres[] = 'respaldados';
		$valores[] = $Respaldados;

		$nombres[] = 'usuario';
		$valores[] = $EquipoUsuario;
		$nombres[] = 'version';
		$valores[] = $version;

		$objrespaldos = new Consultas('respaldospc');
		$objrespaldos->Insert($nombres, $valores);
		$respaldos = $objrespaldos->Ejecutar();
		if($respaldos['estado']=='ok'){
			$AcroniCount++;
		}
	
	}		

	}


	}

	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";

	$Procesados="";
	$version="";
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $codcliente='';
		    $message='';
		    $FechaAux='';
/****************** Fin Acronis ******************/
} elseif ($found===2) {
/////////////////// Acronis español /////////////////////
$NombreCliente=explode("-", $Equipo);
$buscar=$NombreCliente[0];
$EquipoUsuario=@$NombreCliente[1];


if ($buscar!='') { 

	$objdatos = new Consultas('clientes');
	$objdatos->Select();
	$objdatos->Where('empresa', $buscar, 'LIKE'); 						
	$datos = $objdatos->Ejecutar();

	//echo "<br>Acronis español ".$datos['consulta']."<br>";

	if($datos['numfilas']>0){
	$rowdatos = $datos["datos"]; 
	//$clientenom = $rowdatos[0]['nombre']." ".$rowdatos[0]['apellido'];
	$codcliente= $rowdatos[0]['codcliente'];
	}

$pos = strpos($message,"/");
//echo "<br>";
 $Fecha = trim(substr($message, $pos-2,10));
//echo "<br>";		 
		 
if (strpos($EquipoUsuario, "2") > 0 and strpos($message, "PM") > 0 ) {
$FechaAux = cambiaf_orden($Fecha);
} else {		 
$FechaAux = $Fecha;
}


//echo "Pos fecha " . strpos($message, "PM") . "<br>";
$FechaAux = cambiaf_a_mysql($FechaAux);
//echo "<br><p>";

$pos = strpos($message,'"Copia');
$rest = substr($message, $pos+1);
$pos1 = strpos($rest,'"')+$pos;
$Tarea = substr($message, $pos+1, $pos1-$pos);

$pose=strpos($message, 'Tarea ');

$Errores=substr($message,$pose);
$pose1=strpos($Errores, '.');

$Errores=substr($Errores,$pose1-$pose);
$Errores = str_replace("'",'',$Errores);

$Respaldados='';
$Procesados='';
if(strpos($message,'Necesita interacción')!==FALSE) {
$FechaAux=date("Y-m-d", $details["udate"]);
$largo_message=strlen($message);
$pos_punto=strpos($message,'.');
$Tarea=substr($message,0,$pos_punto);
$pos_detalle=strpos($message, "Detalles:");
$Errores=substr($message,$pos_detalle+9,$largo_message-$pos_detalle-9);
$Procesados=0;
$Respaldados=0;
$Tamano='';
}
		
	$message=str_replace("'", "|", $message);
	//$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	if ($codcliente>0 and $FechaAux!="0000-00-00"){

	$objrespaldos = new Consultas('respaldospc');
	$objrespaldos->Select();
	$objrespaldos->Where('fecha', $FechaAux, '=', 'AND'); 
	$objrespaldos->Where('tarea', $Tarea, '=', 'AND'); 
	$objrespaldos->Where('errores', $Errores, '=', 'AND'); 
	$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
	$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
	$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
	$objrespaldos->Where('usuario', $EquipoUsuario, '='); 

	$respaldos = $objrespaldos->Ejecutar();

	if($respaldos['numfilas']==0){
		$valores=array();
		$nombres=array();

		$nombres[] = 'fecha';
		$valores[] = $FechaAux;
		$nombres[] = 'message';
		$valores[] = $message;
		$nombres[] = 'tarea';
		$valores[] = $Tarea;
		$nombres[] = 'errores';
		$valores[] = $Errores;
		$nombres[] = 'procesados';
		$valores[] = $Procesados;
		$nombres[] = 'tamano';
		$valores[] = $Tamano;
		$nombres[] = 'codcliente';
		$valores[] = $codcliente;

		$nombres[] = 'respaldados';
		$valores[] = $Respaldados;

		$nombres[] = 'usuario';
		$valores[] = $EquipoUsuario;
		$nombres[] = 'version';
		$valores[] = $version;

		$objrespaldos = new Consultas('respaldospc');
		$objrespaldos->Insert($nombres, $valores);
		$respaldos = $objrespaldos->Ejecutar();
		if($respaldos['estado']=='ok'){
			$AcroniCount++;
		}
	
	}	

	}


	}
	$Tam="";
	$Errores="";
	$Tamano="";
	$Fecha="";
	$Tarea="";
	$Respaldados="";
	$Procesados="";
	$version="";
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $codcliente='';
		    $message='';
		    $FechaAux='';
/****************** Fin Acronis ******************/
/****************** Empieza Cobian ***************/
} elseif ($found===1) {
	
$NombreCliente=explode("-", $cliente);
$buscar=$NombreCliente[0];

$EquipoUsuario=@$NombreCliente[1];


if ($buscar!='') { 
	$codcliente='';
	$objdatos = new Consultas('clientes');
	$objdatos->Select();
	$objdatos->Where('empresa', $buscar, 'LIKE'); 
							
	$datos = $objdatos->Ejecutar();

//	echo "<br>Cobian ".$datos['consulta']."<br>";

	if($datos['numfilas']>0){
	$rowdatos = $datos["datos"]; 
	//echo $rowdatos[0]['nombre']." ".$rowdatos[0]['apellido']."<br>";
	$codcliente= $rowdatos[0]['codcliente'];
	}	
		foreach($items as $item)
		{
		$string=$message;
		 $t_message=$message;
	    $Count=0;
	    $appearsCount += substr_count($string, $item);
		/*/ echo "<br>--$item aparece $appearsCount veces<br>";*/
		$fin=strlen($string);
		 
			for ($Count=1;$Count<=$appearsCount;$Count++) {
				$xz++;
				$fin=strlen($string);
				$stop="*"; 
				$startI = strpos($string, $item); 
				$stopI = strpos($string, $stop, $startI+4); 
							
				$pos=false;
				$pos = strpos($string,"successfully");
				if($pos === false) {
					if ($stopI > $startI) 
					$vero=substr($string, $startI-$aux, $stopI - $startI+$aux);

						$Fecha=extract_unit($vero, "", " *");
						$FechaAux=trim(substr($Fecha, 0,10));
						
						$Fecha= cambiaf_a_normal(substr($Fecha, 0,10));
						$Tarea= extract_unit($vero, "\"", "\"");

						$Errores=extract_unit($vero, "Errores:", " ");
						
						if ($Errores==''){
						$Errores=extract_unit($vero, "Errores:", ".");
						}
						$Procesados =extract_unit($vero, "Ficheros procesados:", ".");
						$Respaldados=extract_unit($vero, "Ficheros respaldados:", ".");
						
						$Tam=extract_unit($vero, " Tamaño total: ", "bytes");
						$Tamano=$Tam." bytes";
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Tamaño total: ", "KB");
						$Tamano=(int)$Tam." KB";
						}
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Tamaño total: ", "MB");
						$Tamano=(int)$Tam." MB";
						}
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Tamaño total: ", "GB");
						$Tamano=(int)$Tam." GB";
						}
						if ($Tam=='') {
						$Tamano=" 0 bytes ";
						}
				}else{

					if ($stopI > $startI) 
					$vero=substr($string, $startI-$aux, $stopI - $startI+$aux);
				
						$Fecha=extract_unit($vero, "", " *");
						$FechaAux=trim(substr($Fecha, 0,10));
						
						$Fecha= cambiaf_a_normal(substr($Fecha, 0,10));
						$Tarea= extract_unit($vero, "\"", "\"");
				
						$Errores=extract_unit($vero, "Errors:", " ");
						
						if ($Errores==''){
						$Errores=extract_unit($vero, "Errors:", ".");
						}
						$Procesados =extract_unit($vero, "Processed files:", ".");
						$Respaldados=extract_unit($vero, "Backed up files:", ".");
						
						$Tam=extract_unit($vero, " Total size: ", "bytes");
						$Tamano=$Tam." bytes";
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Total size: ", "KB");
						$Tamano=(int)$Tam." KB";
						}
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Total size: ", "MB");
						$Tamano=(int)$Tam." MB";
						}
						if ($Tam=='') {
						$Tam=extract_unit($vero, " Total size: ", "GB");
						$Tamano=(int)$Tam." GB";
						}
						if ($Tam=='') {
						$Tamano=" 0 bytes ";
						}
				}
				$string=substr($string, $stopI, $fin);   
				/*echo $codcliente." --- Errores ".$Errores."<br>";*/
				if ($Errores==1){
			/*echo $message."<br>--------------------<p>";*/ 				
					if(strpos($message, "ya existe")!==false){
						
					$Errores=0;
					}
			}
						
						
				$found='';			
				/*//////////*****************************************/
				$Errores=(int)$Errores;
				//$Procesados=$Procesados;
				//$Respaldados=$Respaldados;

			if (strlen($message) > 200001) {
				$string=substr($string, 0, 200);
				$t_message=str_replace("'", "|", $string);
				$Errores="Mensaje generado por cobian demasiado largo";
				$message=str_replace("'", "|", $t_message); 

			} else {
				$message=str_replace("'", "|", $message); 
			}
				//$message=((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $message) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

				if ($codcliente>0 and $FechaAux!="0000-00-00"){

				$objrespaldos = new Consultas('respaldospc');
				$objrespaldos->Select();
				$objrespaldos->Where('fecha', $FechaAux, '=', 'AND'); 
				$objrespaldos->Where('tarea', $Tarea, '=', 'AND'); 
				$objrespaldos->Where('errores', $Errores, '=', 'AND'); 
				$objrespaldos->Where('procesados', $Procesados, '=', 'AND'); 
				$objrespaldos->Where('respaldados', $Respaldados, '=', 'AND'); 
				$objrespaldos->Where('codcliente', $codcliente, '=', 'AND'); 
				$objrespaldos->Where('usuario', $EquipoUsuario, '='); 

				$respaldos = $objrespaldos->Ejecutar();

				echo "<br>".$respaldos['consulta']."<br>";

				if($respaldos['numfilas']==0){
					$valores=array();
					$nombres=array();

					$nombres[] = 'fecha';
					$valores[] = $FechaAux;
					$nombres[] = 'message';
					$valores[] = $message;
					$nombres[] = 'tarea';
					$valores[] = $Tarea;
					$nombres[] = 'errores';
					$valores[] = $Errores;
					$nombres[] = 'procesados';
					$valores[] = $Procesados;
					$nombres[] = 'tamano';
					$valores[] = $Tamano;
					$nombres[] = 'codcliente';
					$valores[] = $codcliente;

					$nombres[] = 'respaldados';
					$valores[] = $Respaldados;

					$nombres[] = 'usuario';
					$valores[] = $EquipoUsuario;
					$nombres[] = 'version';
					$valores[] = $version;

					$objrespaldos = new Consultas('respaldospc');
					$objrespaldos->Insert($nombres, $valores);
					$respaldos = $objrespaldos->Ejecutar();
					if($respaldos['estado']=='ok'){
						$CobianCount++;
					}
				
				}		


				}
				
				$Tam="";
				$Errores="";
				$Tamano="";
				$Fecha="";
				$Tarea="";
				$Respaldados="";
				$Procesados="";
				/*/////////*****************************************/
			}
	$version="";
	$codcliente="";		
		    $appearsCount = 0;   
		    $pos=0;
		    $stopI=0;
		    $startI=0;
		    $codcliente='';
		    
		}
		$items=array();
		$very=0;
	}
	$message="";
}

		if ($totalmessagecount>60 and $i<($totalmessagecount -57)){
			imap_delete($connection, $i);
		}

	}

	imap_expunge($connection);
	imap_close($connection);

echo "Cantidad Respaldos MCC ". $MccCount. "<br>";
echo "Cantidad Respaldos Cobian ". $CobianCount. "<br>";
echo "Cantidad Respaldos Acronis ". $AcroniCount;

}


?>