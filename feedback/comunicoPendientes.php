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



require_once __DIR__ .'/../classes/Encryption.php';
require_once __DIR__.'/../library/conector/consultas.php';
use App\Consultas;

require __DIR__.'/../clientes/backup/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__.'/../clientes/backup/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__.'/../clientes/backup/vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__."/../funciones/fechas.php";
require __DIR__."/../common/funcionesvarias.php";
require __DIR__."/../common/verificopermisos.php";

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

if(strlen($_GET["fechaini"])>0){ $fechaini=trim($_GET["fechaini"]);}else{ $fechaini=date("Y-m-d");}

$ano=date("Y", strtotime($fechaini));
$mes=date("n", strtotime($fechaini));
//Día de la semana
$Qdia=date("w", strtotime(date("d M Y H:i:s")));

$asuntos=array('MENSAJE DE SILVIA', 'ME ACORDÉ DE ALGO', 'TENES QUE HACER ALGO IMPORTANTE');
$cuerpo=array('recordá completar las horas', 'pedirte que completes las horas', 'completar las horas');

if ($Qdia==0) {
	$Hoy=date('d M Y',time()-(48*60*60));
} elseif ($Qdia==1) {
	$Hoy=date('d M Y',time()-(72*60*60));
} else {
	$Hoy=date('d M Y');
}

	//$Descripcion='Todos los usuarios activos';
	$objuser = new Consultas('usuarios');
	$objuser->Select();

//    $objuser->Where('tratamiento', '100', '!=');
//    $objuser->Where('tratamiento', '2', '!=');
    $objuser->Where('estado', '0', '=');
	//$objuser->Where('estado', 'NULL', '=', 'or', ')');
    $objuser->Where('borrado', '0');
    $user = $objuser->Ejecutar();
	$total_rowsuser=$user["numfilas"];
    $rowsuser = $user["datos"];
    //echo "<br>".$user['consulta']."<br>";

    if($total_rowsuser>=0){
        foreach($rowsuser as $rowuser){

			$random=rand(0, 2);
            $email=$rowuser['email'];
            $nombre=$rowuser['nombre']." ".$rowuser['apellido'];
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
    margin: auto; "></td><td align="right"><h6 class="collapse">'. $cuerpo[$random] .'.</h6></td></tr>
                </table></div></td><td></td></tr></table>
                <table class="body-wrap"><tr><td></td>
		<td class="container" bgcolor="#FFFFFF"><div class="content"><table>	<tr><td>
						<h3>'. $rowuser['nombre']." ".$rowuser['apellido'].'</h3><p class="lead">'. $rowuser['email'].'</p><p></p>
						<p class="callout">Fecha envío: '. diasemana($Hoy). ' - '. $Hoy.'<br>
							
                        </p>';

        $messageAux='
        
        <div class="container-fluid">
<div class="table-responsive">
    <table class="table table-hover table-responsive table-bordered table-condensed ">
    <thead class="bg-primary">
        <tr><th colspan="2" class="fit">'. _('Mes: ').genMonth_Text(date("m"));

$messageAux.='</th></tr></thead><tbody>';

if ($mes==2){
    $numerodedias=28;
}
elseif (($mes==4)or ($mes==6) or ($mes==9) or ($mes==11)){
    $numerodedias=30;
}
else{
    $numerodedias=31;
}

$dia=array(
    0=>"Domingo",
    1=>"Lunes",
    2=>"Martes",
    3=>"Mi&eacute;rcoles",
    4=>"Jueves",
    5=>"Viernes",
    6=>"Sábado",
);

$i=1;

$codusuario=$rowuser['codusuarios'];


$diaHoy=date('d');
while($i<=$numerodedias and $i<=$diaHoy){
	
$w= date("w",mktime(0,0,0,$mes,$i,$ano));	
$fecha=$ano."-".$mes."-".$i;

	if($w!=0 and $w!=6) {
		
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
        $ok=1;

                $objhoras = new Consultas('horas');
                $objhoras->Select();
                $objhoras->Where('borrado', '0');    
                $objhoras->Where('codusuario', $rowuser['codusuarios']);    
                $objhoras->Where("fecha" , $fecha, '=');
                $objhoras->Group('codusuario');

                $horas = $objhoras->Ejecutar();
                $total_horas=$horas["numfilas"];
                //$rowshoras = $horas["datos"];
                //echo $horas['consulta'];

                if($total_horas<=0){
                    if($ok==1) {
                        $messageAux.="<tr class=\"btn-inverse\" >";
                        $messageAux.= "<td>";                        
                        $messageAux.= $dia[$w];
                        $messageAux.= "</td><td>";
                        $messageAux.= $i;
                        $messageAux.= "</td></tr>";
                        $ok = 0;
                    }				
                }

    }
    $i++;
}

$messageAux.='</tbody></table></div>';


$message.=$messageAux;
$message.='</p>
						<br/><br/><table class="social" width="100%"><tr><td>
												
												<table align="left" class="column">
										<tr><td><h5 class="">Contacto:</h5><p>Celular: <strong>096 261 570</strong><br/>
                Email: <strong><a href="emailto:soporte@mcc.com.uy">soporte@mcc.com.uy</a></strong></p>
											</td></tr></table><span class="clear"></span></td></tr></table></td></tr></table></div>
		</td><td></td></tr></table><table class="footer-wrap"><tr><td></td><td class="container"><div class="content">
				<table><tr>	<td align="center"><p>Enviado desde UYCODEKA<br>Acceda a una demo online en http://uycodeka.epizy.com/<br>
Con UYCODEKA obtenga rápidamente información sobre el estado de su empresa<br /></p></td></tr></table>
				</div></td><td></td></tr></table></body></html>';

	if ($email!='' and $ok==0) {

        echo $message;

			
				
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

		$mail->Subject = $asuntos[$random];

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
    }
}
?>
