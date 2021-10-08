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

//Configuración para ejecutar desde la línea de comando

$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);

require_once $conexionPath. '/classes/Encryption.php';
require_once $conexionPath.'/library/conector/consultas.php';
use App\Consultas;

require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/Exception.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require realpath(dirname(__FILE__)).'/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include($conexionPath."/funciones/fechas.php");
include($conexionPath."/common/funcionesvarias.php");
include($conexionPath."/common/verificopermisos.php");

////header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('America/Montevideo');

function GetFileName($file_name)
{
        $newfile = basename($file_name);
        if (strpos($newfile,'\\') !== false)
        {
                $tmp = preg_split("[\\\]",$newfile);
                $newfile = $tmp[count($tmp) - 1];
                return($newfile);
        }
        else
        {
                return($file_name);
        }
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
/*Datos para la conexión con el mail server */
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

$objdatos = new Consultas('foto');
$objdatos->Select();
$objdatos->Where('oid', '1');

$datos = $objdatos->Ejecutar();
$rowdatos = $datos["datos"];
$fotocontent= base64_encode($rowdatos[0]['fotocontent']);

//Día de la semana
	$Qdia=date("w", strtotime(date("d M Y H:i:s")));

if ($Qdia==0) {
	$Hoy=date('d M Y H:i:s',time()-(48*60*60));
} elseif ($Qdia==1) {
	$Hoy=date('d M Y H:i:s',time()-(72*60*60));
} else {
	$Hoy=date('d M Y H:i:s');
}
		//$Hoy = date("d M Y H:i:s");
		//echo $Hoy."<br>";
		$HoynumMas = strtotime($Hoy);
		$HoynumMenos = strtotime($Hoy)-60*60*36;

		$sql="SELECT clientes.codcliente, email,nombre,apellido,empresa,telefono,movil,alias FROM 
		`clientes` INNER JOIN `equipos` ON `clientes`.`codcliente`=`equipos`.`codcliente` WHERE `clientes`.`service` = '2' AND `equipos`.`service`=3
		  GROUP BY `clientes`.`codcliente` ORDER BY `clientes`.`codcliente`";

		$objclientes = new Consultas('clientes');
		$objclientes->Select($sql, 'sql');
								
		$clientes = $objclientes->Ejecutar();
		$rowclientes = $clientes["datos"];
		$total_rows=$clientes["numfilas"];
	
		// check if more than 0 record found
		if($total_rows>=0){
			foreach($rowclientes as $row){
				$nombre ='';
				$telefono='';
				if(strlen($row['nombre'])>0){
					$nombre.= $row['nombre'].' ';
				}
				if(strlen($row['apellido'])>0){
					$nombre.= $row['apellido'].' ';
				}
				if(strlen($row['empresa'])>0){
					$nombre.= ' - '.$row['empresa'];
				}
				$codcliente=$row['codcliente'];
				if(strlen($row['telefono'])>0){
					$telefono.= ' '.$row['telefono'];
				}
				if(strlen($row['movil'])>0){
					$telefono.= ' '.$row['movil'];
				}
				$email=$row['email'];

$message.='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>UYCODEKA - Aviso Respaldo</title>	
<style type="text/css">
* { 
	margin:0;
	padding:0;
}
* { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }
img { 
	max-width: 100%; 
}
.collapse {
	margin:0;
	padding:0;
}
body {
	-webkit-font-smoothing:antialiased; 
	-webkit-text-size-adjust:none; 
	width: 100%!important; 
	height: 100%;
}
a { color: #2BA6CB;}
.btn {
	text-decoration:none;
	color: #FFF;
	background-color: #666;
	padding:10px 16px;
	font-weight:bold;
	margin-right:10px;
	text-align:center;
	cursor:pointer;
	display: inline-block;
	width: 220px;
}
p.callout {
	padding:15px;
	background-color:#aaaaaa;
	margin-bottom: 15px;
}
.callout a {
	font-weight:bold;
	color: #2BA6CB;
}
table.social {
	background-color: #aaaaaa;
}
.social .soc-btn {
	padding: 3px 7px;
	font-size:12px;
	margin-bottom:10px;
	text-decoration:none;
	color: #FFF;font-weight:bold;
	display:block;
	text-align:center;
}
a.fb { background-color: #3B5998!important; }
a.tw { background-color: #1daced!important; }
a.gp { background-color: #DB4A39!important; }
a.ms { background-color: #000!important; }
.sidebar .soc-btn { 
	display:block;
	width:100%;
}
table.head-wrap { width: 100%;}
.header.container table td.logo { padding: 15px; }
.header.container table td.label { padding: 15px; padding-left:0px;}
table.body-wrap { width: 100%;}
table.footer-wrap { width: 100%;	clear:both!important;
}
.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
.footer-wrap .container td.content p {
	font-size:10px;
	font-weight: bold;
}
h1,h2,h3,h4,h5,h6 {
font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
}
h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }
h1 { font-weight:200; font-size: 44px;}
h2 { font-weight:200; font-size: 37px;}
h3 { font-weight:500; font-size: 27px;}
h4 { font-weight:500; font-size: 23px;}
h5 { font-weight:900; font-size: 17px;}
h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}
.collapse { margin:0!important;}
p, ul { 
	margin-bottom: 10px; 
	font-weight: normal; 
	font-size:14px; 
	line-height:1.6;
}
p.lead { font-size:17px; }
p.last { margin-bottom:0px;}
ul li {
	margin-left:5px;
	list-style-position: inside;
}
ul.sidebar {
	background:#ebebeb;
	display:block;
	list-style-type: none;
}
ul.sidebar li { display: block; margin:0;}
ul.sidebar li a {
	text-decoration:none;
	color: #666;
	padding:10px 16px;
/* 	font-weight:bold; */
	margin-right:10px;
/* 	text-align:center; */
	cursor:pointer;
	border-bottom: 1px solid #777777;
	border-top: 1px solid #FFFFFF;
	display:block;
	margin:0;
}
ul.sidebar li a.last { border-bottom-width:0px;}
ul.sidebar li a h1,ul.sidebar li a h2,ul.sidebar li a h3,ul.sidebar li a h4,ul.sidebar li a h5,ul.sidebar li a h6,ul.sidebar li a p { margin-bottom:0!important;}
.container {
	display:block!important;
	max-width:600px!important;
	margin:0 auto!important; /* makes it centered */
	clear:both!important;
}
.content {
	padding:15px;
	max-width:600px;
	margin:0 auto;
	display:block; 
}
.content table { width: 100%; }
.column {
	width: 300px;
	float:left;
}
.column tr td { padding: 15px; }
.column-wrap { 
	padding:0!important; 
	margin:0 auto; 
	max-width:600px!important;
}
.column table { width:100%;}
.social .column {
	width: 280px;
	min-width: 279px;
	float:left;
}
.clear { display: block; clear: both; }
@media only screen and (max-width: 600px) {
	a[class="btn"] { width: 200px!important; display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}
	div[class="column"] { width: auto!important; float:none!important;}
	table.social div[class="column"] {
		width:auto!important;
	}
}
</style>
</head><body bgcolor="#FFFFFF"><table class="head-wrap" bgcolor="#999999">
	<tr><td></td><td class="header container"><div class="content"><table bgcolor="#999999">	<tr>
						<td><img src="data:image/png;base64,'.$fotocontent.'" height="37" alt="" style=" float: left;color: #000;padding: 3px 5px;vertical-align: middle;
    margin: auto; "></td><td align="right"><h6 class="collapse">Aviso de Respaldo</h6></td></tr>
				</table></div></td><td></td></tr></table><table class="body-wrap"><tr><td></td>
		<td class="container" bgcolor="#FFFFFF"><div class="content"><table>	<tr><td>
						<h3>'. $nombre.'</h3><p class="lead">'. $telefono.'<br>'. $email.'</p><p></p>
						<p class="callout">Fecha de reporte: '. diasemana($Hoy). ' - '. $Hoy.'<br>
							En las últimas 24hrs no se ha detectado la realización correcta de respaldos.
						</p><h3>Equipo/Detalles</h3><p>';

		$messageAux='';

		$objequipos = new Consultas('equipos');
		$objequipos->Select();
		$objequipos->Where('codcliente', $codcliente);
		$objequipos->Where('service', '3');

		$equipos = $objequipos->Ejecutar();
		$rowequipos = $equipos["datos"];
		$total_equipos=$equipos["numfilas"];
		//echo $equipos['consulta']."<br>";
		if($total_equipos>=0){
			foreach($rowequipos as $rowequipo){
				$alias=$rowequipo['alias'];
		$i=$Qdia;
		if($Qdia==0) {
			$i=7;
		}
		$diasrespaldo=explode('-',$rowequipo['diasrespaldo']);
		if(in_array($i, $diasrespaldo)) {

		//echo "<br>".date("Y-m-d", $HoynumMenos);
		$sqlrespaldo="SELECT * FROM `respaldospc` WHERE 
				(UNIX_TIMESTAMP(fecha) >= '".$HoynumMenos."' AND UNIX_TIMESTAMP(fecha) <= '".$HoynumMas."') 
				AND `codcliente` = '$codcliente' AND `usuario` like '%".$alias."' limit  1 ";

		$objrespaldospc = new Consultas('respaldospc');
		$objrespaldospc->Select($sqlrespaldo, 'sql');

		$respaldospc = $objrespaldospc->Ejecutar();
		$rowrespaldospc = $respaldospc["datos"];
		$total_respaldospc=$respaldospc["numfilas"];

		//echo "<br>".$respaldospc['consulta']."<br>";
		if($total_respaldospc>0){
			foreach($rowrespaldospc as $rowrespaldospc){
			$version=$rowrespaldospc['version'];
			$errores=(int)$rowrespaldospc['errores'];
			$corregido=$rowrespaldospc['corregido'];
			if($errores>0 and $corregido=='') {
				$messageAux.="Equipo: ".$alias." / ";
				if ( ($errores >=1 and $errores!='' and (int)$errores==$errores ) and strpos($version, "Cobian")!==false ){
						if($rowrespaldospc['procesados'] > 0 and $rowrespaldospc['respaldados'] == 0 ){
							$messageAux.="El equipo donde se realiza el respaldo no está disponible.<br>";
						} elseif ($rowrespaldospc['procesados'] > 0){
							if(strpos($rowrespaldospc['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($rowrespaldospc['message'] , "No se pudo copiar el fichero" );
								if(strpos($rowrespaldospc['message'],"El proceso no tiene acceso al archivo")!==false) {
								$stopI=strpos($rowrespaldospc['message'] , ": El proceso no tiene acceso al archivo" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " El proceso no tiene acceso al archivo");
								} else {
								$stopI=strpos($rowrespaldospc['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								}
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
								if(strpos($Archivo, ".DBF")!==false or strpos($Archivo, ".dbf")!==false) {
									$programa="Memory ";
								} elseif(strpos($Archivo, ".PST")!==false or strpos($Archivo, ".pst")!==false) {
									$programa="Outlook ";
								} elseif(strpos($Archivo, ".DOC")!==false or strpos($Archivo, ".doc")!==false) {
									$programa="WORD ";
								} elseif(strpos($Archivo, ".XLS")!==false or strpos($Archivo, ".xls")!==false) {
									$programa="EXCEL ";
								}
							$messageAux.="<b>Quedo algún programa abierto</b> utilizando archivos a respaldar.
							<br>Archivo: ".$Archivo."
							<br>Por ejemplo: ".$programa."
							<br>Recuerde cerrar todos los programas y dejar encendido el equipo antes de retirarse. <br>";
							} elseif(strpos($rowrespaldospc['message'] , "No se pudo revertir el nombre de" ) !==false ) {
							$startI=strpos($rowrespaldospc['message'] , "usando" );
								$stopI=strpos($rowrespaldospc['message'] , ": No se puede crear un archivo que ya existe" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "Respaldos", " No se puede crear un archivo que ya existe");
								$Archivo=str_replace('"', "", $Archivo);
								$Archivo=str_replace(':', "", $Archivo);
								
							$messageAux.="<b>Nombre del archivo a respaldar no es válido en origen o en destino</b>.
							<br>Archivo: ".$Archivo."
							<br>Pruebe a cambiarle el nombre al archivo. <br>";
							}
						}
						if (strpos($rowrespaldospc['message'] , "No se ha podido crear la carpeta de destino" ) !== false) {
							$messageAux.="<b>El equipo donde se realiza el respaldo esta inaccesible</b>.<br> O bien hay un 
							problema de red o el equipo esta apagado.
							<br>Recuerde verificar que el equipo de respaldo este encendido. <br>";
						} elseif (strpos($rowrespaldospc['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($rowrespaldospc['message'] , "No se pudo copiar el fichero" );
							
							if (strpos($rowrespaldospc['message'],"Acceso denegado") !== false) {
								$stopI=strpos($rowrespaldospc['message'] , ": Acceso denegado" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Acceso denegado");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
								$messageAux.="Se denegó el acceso al archivo ".$Archivo." <b>esta siendo utilizado</b><br>";
							} elseif (strpos($rowrespaldospc['message'],"No se ha encontrado la ruta de acceso de la red") !== false) {
								$stopI=strpos($rowrespaldospc['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" Hay un problema de red o el equipo de respaldo está apagado. Archivo: ".$Archivo."<br>";
							} elseif (strpos($rowrespaldospc['message'],"Se anuló la solicitud") !== false) {
								$stopI=strpos($rowrespaldospc['message'] , ": Se anuló la solicitud" );
								$vero=substr($rowrespaldospc['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Se anuló la solicitud");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" <b>El nombre del archivo</b> ".$Archivo." no es válido o la profundida de directorio
							excede el máximo permitido.<br>";
							}
						} elseif (strpos($rowrespaldospc['message'] , "no existe o no pudo ser accedida" ) !== false) {
							$messageAux.=" Cobian backup se está ejecutando desde un usuario sin prermisos administrativos.<br>";
						}
					}elseif ($errores >=1 and $errores!='' and strpos($version, "Acronis")!==false ){ 
					$messageAux.="";
						if (strpos($rowrespaldospc['message'],"ha fallado en el equipo")!==false) {
							$messageAux.="Se han encontrado errores sin especificar al realizar el respaldo";
						}
						if (strpos($rowrespaldospc['message'],"has failed on machine")!==false) {
							$messageAux.="Se han encontrado errores al realizar el respaldo<br>";
							if (strpos($rowrespaldospc['message'],"failure.")){
							$messageAux.=extract_unit($vero, "failure. ", ".");
							}
						}						
						if (strpos($rowrespaldospc['message'],"Error al leer los datos desde el disco")!==false) {
							$messageAux.="Se han encontrado errores al copiar archivos a destino";
						}						
					}
						$encontrado++;
				}
					$conteresp++;
			}
		}else {
			$messageAux.="<p>Equipo: ".$alias." / ";
			$messageAux.="No se le realizó el respaldo debido a una de las posibles causas:<br>
			<ul><li><strong><u>Falló la conexión con Internet para enviar el reporte</u></strong></li>
			<li><strong><u>El equipo donde se realiza el respaldo está inaccesible</u></strong></li></ul>";
			$encontrado++;
		}
	}
}
$message.=$messageAux;
$message.='</p>
						<br/><br/><table class="social" width="100%"><tr><td>
												
												<table align="left" class="column">
										<tr><td><h5 class="">Contacto:</h5><p>Celular: <strong>096 261 570</strong><br/>
                Email: <strong><a href="emailto:soporte@mcc.com.uy">soporte@mcc.com.uy</a></strong></p>
											</td></tr></table><span class="clear"></span></td></tr></table></td></tr></table></div>
		</td><td></td></tr></table><table class="footer-wrap"><tr><td></td><td class="container"><div class="content">
				<table><tr>	<td align="center"><p>Enviado desde UYCODEKA Facturación WEB<br>Acceda a una demo online en http://uycodeka.epizy.com/<br>
Con UYCODEKA obtenga rápidamente información sobre el estado de su empresa<br /></p></td></tr></table>
				</div></td><td></td></tr></table></body></html>';
//echo $message;
/*
if ( $encontrado>0 ) {
	echo $message."<br>";
}
*/
	if ($email!='' and $encontrado>0) {

//		$ssl=$emailsslenvio."://".$emailhostenvio.":".$emailpuertoenvio;
		$mail = new PHPMailer(); 
		// $mail->SMTPDebug = 3;	
		
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
		$mail->IsHTML(true);
		$mail->AddAddress($email,$nombre);
	

			$mail->WordWrap = 900;
			$mail->IsHTML(true);

			$message.='<table border="0" cellspacing="0" cellpadding="0" width="100%">
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
		} //Si tiene mail válido
$message='';
$encontrado=0;
		}
	}
}
?>
