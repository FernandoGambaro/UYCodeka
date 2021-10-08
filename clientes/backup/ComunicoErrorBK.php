<?php

//Configuración para ejecutar desde la línea de comando

$path=realpath(dirname(__FILE__));
$largo=strlen($path)-16;
$conexionPath=substr($path,0,$largo);
include(realpath(dirname(__FILE__))."/encrypt_decrypt.php");
include($conexionPath."/conectar.php");
include(realpath(dirname(__FILE__))."/class/class.phpmailer.php");

include($conexionPath."/funciones/fechas.php"); 
include($conexionPath."/common/funcionesvarias.php");
include($conexionPath."/common/verificopermisos.php");

////header('Content-Type: text/html; charset=UTF-8'); 
date_default_timezone_set('America/Montevideo');

/*Datos para la conexión con el mail server */
$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$message="";
$emailname=mysqli_result($rs_query, 0, "emailname");
$emailsend=mysqli_result($rs_query, 0, "emailsend");
$emailpass=encrypt_decrypt('decrypt', mysqli_result($rs_query, 0, "emailpass"));
$emailhost=mysqli_result($rs_query, 0, "emailhost");
$emailssl=mysqli_result($rs_query, 0, "emailssl");
$emailpuerto=mysqli_result($rs_query, 0, "emailpuerto");
$emailbody=mysqli_result($rs_query, 0, "emailbody");


 $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±");
 $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ");

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

echo "Envío Reporte mail<br>";
$total='';

//Día de la semana

	$Qdia=date("w", strtotime(date("Y-m-d")));
if ($Qdia==0) {
	$hoy=date('Y-m-d',time()-(48*60*60));
} elseif ($Qdia==1) {
	$hoy=date('Y-m-d',time()-(72*60*60));
} else {
	$hoy=date('Y-m-d',time()-(24*60*60));
}

//echo 	$dia=date("w");
//echo "<----<br>";
			//echo $Qdia;
/*Muesto los respaldos solo los días entre semana mas el sábado, al realizarce los 
respaldos en la madrugada, el aviso aparece al día siguiente */

	if (@$Qdia <=6 and @$Qdia > 0) {
		
		// Recorro los clientes que tienen servicio de abonado con control de respaldos.
	 	$sql="SELECT codcliente,service,email,nombre,apellido,empresa,telefono,movil FROM `clientes` WHERE `service` = '2'";
		$rs = mysqli_query($GLOBALS["___mysqli_ston"], $sql);

		$contador=0;
		while ($contador < mysqli_num_rows($rs)) { 		
		$codcliente=mysqli_result($rs, $contador, "codcliente");
		$nombre=mysqli_result($rs, $contador, "empresa").' / '. mysqli_result($rs, $contador, "nombre"). ' '. mysqli_result($rs, $contador, "apellido");
		$telefono=mysqli_result($rs, $contador, "telefono").' - '. mysqli_result($rs, $contador, "movil");
		$topmessage='';
		$email=mysqli_result($rs, $contador, "email");

		
		$topmessage='<TABLE CELLSPACING="0" CELLSPADING="1" COLS="6" BORDER="0"><COLGROUP SPAN="5" WIDTH="185"></COLGROUP><COLGROUP WIDTH="129"></COLGROUP>
		<TR><TD COLSPAN=5 ALIGN="LEFT" BGCOLOR="#FFFFFF"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif">'.$nombre.'<BR></FONT></B>
		<B><FONT FACE="Geneva,Arial,Helvetica,sans-serif">'.$telefono.'<BR><p></FONT></B></TD>
		<TD ALIGN="LEFT"><BR></TD></TR><TR>
		<TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Apreciado cliente este es un aviso automático de control de respaldo.</FONT></TD>
		</TR>
		<TR><TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Se han encontrado <b>errores</b> al realizar el respaldo.</FONT><br>&nbsp;</TD>
		</TR>
		<TR>
		<TD HEIGHT="18" ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Fecha</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Versión</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Equipo</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Tarea</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Detalles</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
		</TR>	';
	
		$encontrado=0;
		
			/*echo "<br>".*/
			$sqlequipo="SELECT codcliente,service,diasrespaldo,alias FROM `equipos` WHERE `codcliente` = '$codcliente' and `service`='3'";
		 	$rsequipo = mysqli_query($GLOBALS["___mysqli_ston"], $sqlequipo);
			$contadoAux=0;
			while ($contado=mysqli_fetch_array($rsequipo)) { 		
			
			$i=$Qdia+1;
$diasrespaldo=explode('-',$contado["diasrespaldo"]);
if(in_array($i, $diasrespaldo)) {	
			
			$alias=trim($contado["alias"]);
			$analizo=0;
				/*echo "<br>".*/
				$sqlrespaldo="SELECT * FROM `respaldospc`	WHERE `fecha`='$hoy' and `codcliente` = '$codcliente' and `usuario` like '%".$alias."'";
				//echo "<p>";
			 	$rsrespaldo = mysqli_query($GLOBALS["___mysqli_ston"], $sqlrespaldo);

				/*Si encuentro algún respaldo para el día de hoy sigo caso contrario busco el día anterior*/
				if (mysqli_num_rows($rsrespaldo)>0){
					$analizo=1;
					$nuevafecha=$hoy;
				} else {
					$nuevafecha = strtotime ( '-1 day' , strtotime ( $hoy ) ) ;
					$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
					/*echo "<br>".*/
					$sqlrespaldo="SELECT * FROM `respaldospc`	 WHERE  `fecha`='$nuevafecha' and `codcliente` = '$codcliente' and `usuario` like '%".$alias."'";
					//echo "<p>";
				 	$rsrespaldo = mysqli_query($GLOBALS["___mysqli_ston"], $sqlrespaldo);
	
				}
//echo $sqlrespaldo."<br>";
				/*echo "<br>". mysql_num_rows($rsrespaldo);*/
				if (mysqli_num_rows($rsrespaldo) > 0 ){
					
				$contaAux=0;
				$errores='';
				$messageAux='';
				while ($conta=mysqli_fetch_array($rsrespaldo)) { 

					$versione=$conta["version"];
					$errores=$conta["errores"];
					if ( $errores >=1 and $errores!='' and (int)$errores==$errores ) {
						if($conta['procesados'] > 0 and $conta['respaldados'] == 0 ){
							$messageAux.="El equipo donde se realiza el respaldo no está disponible.<br>";						
						} elseif ($conta['procesados'] > 0){
							if(strpos($conta['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($conta['message'] , "No se pudo copiar el fichero" );
								$stopI=strpos($conta['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.="<b>Quedo algún programa abierto</b> utilizando archivos a respaldar.
							<br>Archivo: ".$Archivo."
							<br>Recuerde cerrar todos los programas y dejar encendido el equipo antes de retirarse. <br>";
							} elseif(strpos($conta['message'] , "No se pudo revertir el nombre de" ) !==false ) {
							$startI=strpos($conta['message'] , "usando" );
								$stopI=strpos($conta['message'] , ": No se puede crear un archivo que ya existe" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "Respaldos", " No se puede crear un archivo que ya existe");
								$Archivo=str_replace('"', "", $Archivo);
								$Archivo=str_replace(':', "", $Archivo);
								
							$messageAux.="<b>Nombre del archivo a respaldar no es válido en origen o en destino</b>.
							<br>Archivo: ".$Archivo."
							<br>Pruebe a cambiarle el nombre al archivo. <br>";
							}
													
						}
						if (strpos($conta['message'] , "No se ha podido crear la carpeta de destino" ) !== false) {
							$messageAux.="<b>El equipo donde se realiza el respaldo esta inaccesible</b>.<br> O bien hay un 
							problema de red o el equipo esta apagado.
							<br>Recuerde verificar que el equipo de respaldo este encendido. <br>";						
						} elseif (strpos($conta['message'] , "No se pudo copiar el fichero" ) !== false) {
							$startI=strpos($conta['message'] , "No se pudo copiar el fichero" );
							
							if (strpos($conta['message'],"Acceso denegado") !== false) {
								$stopI=strpos($conta['message'] , ": Acceso denegado" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Acceso denegado");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
								$messageAux.="Se denegó el acceso al archivo ".$Archivo." <b>esta siendo utilizado</b><br>";
							} elseif (strpos($conta['message'],"No se ha encontrado la ruta de acceso de la red") !== false) {
								$stopI=strpos($conta['message'] , ": No se ha encontrado la ruta de acceso de la red" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", " No se ha encontrado la ruta de acceso de la red");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" Hay un problema de red o el equipo de respaldo está apagado. Archivo: ".$Archivo."<br>";					
							} elseif (strpos($conta['message'],"Se anuló la solicitud") !== false) {
								$stopI=strpos($conta['message'] , ": Se anuló la solicitud" );
								$vero=substr($conta['message'], $startI, $stopI-2);
								$Archivo=extract_unit($vero, "No se pudo copiar el fichero", ": Se anuló la solicitud");
								$Archivo=str_replace('"', "", GetFileName($Archivo));
								$Archivo=str_replace(':', "", $Archivo);
							$messageAux.=" <b>El nombre del archivo</b> ".$Archivo." no es válido o la profundida de directorio
							excede el máximo permitido.<br>";					
							}
						} elseif (strpos($conta['message'] , "no existe o no pudo ser accedida" ) !== false) {
							$messageAux.=" Cobian backup se está ejecutando desde un usuario sin prermisos administrativos.<br>";					

						}
						$BGCOLOR="#FFFFFF";
						$COLOR="#000000";
							$message.='<TR>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" HEIGHT="17" ALIGN="CENTER" SDVAL="41629" SDNUM="3082;0;DD/MM/AA">
		<FONT COLOR="'.$COLOR.'">'. implota($conta['fecha']).'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="CENTER" VALIGN=MIDDLE SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">'.$versione.'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="CENTER" VALIGN=MIDDLE SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">'.$alias.'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="LEFT" SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">&nbsp;'.$conta["tarea"].'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE SDNUM="3082;0;@">
		<FONT COLOR="'.$COLOR.'">'.$messageAux.'</FONT></TD></TR>';
						
						$encontrado=1;
						$messageAux='';
					} elseif ( !is_numeric($errores) )  {
				$BGCOLOR="#FFFFFF";
				$COLOR="#000000";

						$messageAux='';
						if (trim($conta["errores"]) == "ha fallado en el equipo") {
							$encontrado=1;
							$message.='<TR>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT="16" ALIGN="CENTER" SDVAL="41629" SDNUM="3082;0;DD/MM/AA">
							' .implota($conta['fecha']).'</TD>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="CENTER" VALIGN=MIDDLE SDNUM="3082;0;@">
							'.$versione.'</TD>
					
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN="LEFT" VALIGN=MIDDLE><B>
							&nbsp;'.$alias.'</B></TD>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE>
							&nbsp;Se han encontrado errores sin especificar al realizar el respaldo</TD></TR>	';
						} elseif(trim($conta["errores"]) == "Error al leer los datos desde el disco.") {
							$encontrado=1;
							$message.='<TR>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT="16" ALIGN="CENTER" SDVAL="41629" SDNUM="3082;0;DD/MM/AA">
							' .implota($conta['fecha']).'</TD>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" BGCOLOR="'.$BGCOLOR.'" ALIGN="CENTER" VALIGN=MIDDLE SDNUM="3082;0;@">
							'.$versione.'</TD>
					
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN="LEFT" VALIGN=MIDDLE><B>
							&nbsp;'.$alias.'</B></TD>
							<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE>
							&nbsp;Se han encontrado errores al copiar archivos a destino</TD></TR>	';
						}
					}
				$contaAux++;
				$errores='';
				$versione='';		
				}
			} else {
				$encontrado=1;

					
		$topmessage='<TABLE CELLSPACING="0" CELLSPADING="1" COLS="6" BORDER="0"><COLGROUP SPAN="5" WIDTH="185"></COLGROUP><COLGROUP WIDTH="129"></COLGROUP>
		<TR><TD COLSPAN=5 ALIGN="LEFT" BGCOLOR="#FFFFFF"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif">'.$nombre.'<BR><p></FONT></B>
		<B><FONT FACE="Geneva,Arial,Helvetica,sans-serif">'.$telefono.'<BR><p></FONT></B></TD>
		<TD ALIGN="LEFT"><BR></TD></TR><TR>
		<TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Apreciado cliente este es un aviso automático de control de respaldo.</FONT></TD>
		</TR>
		<TR><TD COLSPAN=6 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#FFFFFF"><FONT FACE="Geneva,Arial,Helvetica,sans-serif">Se han encontrado <b>errores</b> al realizar el control de respaldo.</FONT><br>&nbsp;</TD>
		</TR>
		<TR>
		<TD HEIGHT="18" ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Fecha</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Equipo</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Tarea</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF">Detalles</FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
		<TD ALIGN="CENTER" VALIGN=TOP BGCOLOR="#000000"><B><FONT FACE="Geneva,Arial,Helvetica,sans-serif" COLOR="#FFFFFF"><BR></FONT></B></TD>
		</TR>	';
	
					
				$message.='
		<TR>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" HEIGHT="16" ALIGN="CENTER" BGCOLOR="#CC0000" SDVAL="41629" SDNUM="3082;0;DD/MM/AA"><FONT COLOR="#FFFFFF">
		'.implota($nuevafecha).'</FONT></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" ALIGN="CENTER" VALIGN=MIDDLE BGCOLOR="#CC0000"><B><FONT COLOR="#FFFFFF">'.$alias.'</FONT></B></TD>
		<TD STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" COLSPAN=4 ALIGN="LEFT" VALIGN=MIDDLE BGCOLOR="#CC0000"><FONT COLOR="#FFFFFF">
		&nbsp;No se le realizó el respaldo debido a una de las posibles causas:<br>
		<ul><li><strong><u>Falló la conexión con Internet para enviar el reporte</u></strong></li>
				<li><strong><u>El equipo donde se realiza el respaldo está inaccesible</u></strong></li></FONT></TD>
		</TR>		';		
			}	
			$contadoAux++;	
			$alias='';
			}
			}
			if ($encontrado==1 and ($Qdia<=6 and $Qdia>=0)) {
				$message=$topmessage.$message."</TABLE>";
				$total++;
			}
			//echo $message."<br>";
					if ($email!='' and $encontrado==1 ) {
						
				
					$ssl="ssl://".$emailhost.":".$emailpuerto."";
							// Instanciando el Objeto  
							// Instanciando el Objeto  
							$mail = new PHPMailer(); 
							$mail->IsSMTP(); 
							 //Servidor SMTP - GMAIL usa SSL/TLS  
							 //como protocolo de comunicación/autenticación por un puerto 465.
			 				 $mail->Host = $ssl;							   
//							 $mail->Host = 'ssl://smtp.gmail.com:465';  
							 // True para que verifique autentificación  
							 $mail->SMTPAuth = true;  
							 // Cuenta de E-Mail & Password  
							 $mail->Username = $emailsend;
							 $mail->Password = $emailpass;

							 $mail->From = $emailsend;
							 $mail->FromName = $emailname;
					
							 $mail->Subject = "Aviso sobre respaldos";
							 // Cuenta de E-Mail Destinatario  
							 //$mail->AddAddress($email,$email);
							 $mail->CharSet = "UTF-8";
							 $mail->IsHTML(true);
							 $mail->AddAddress($email,$nombre);
							 
/* Genero lista de usuarios que pueden controlar  respaldos. */							 

$sql_usuarios="select * from `usuarios` ";
$res_usuarios=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuarios);

		$contusuario=0;
		while ($contusuario < mysqli_num_rows($res_usuarios)) { 		
		$codusuarios=mysqli_result($res_usuarios, $contusuario, "codusuarios");
		$nom_usuario=mysqli_result($res_usuarios, $contusuario, "Nombre"). " ". mysqli_result($res_usuarios, $contusuario, "Apellido");
		$email_usuario=mysqli_result($res_usuarios, $contusuario, "email");
		
		$leer=verificopermisos('respaldoscliente', 'leer', $codusuarios);
			if ($email_usuario!='' and $leer=="true") {
				$mail->AddAddress($email_usuario,$nom_usuario);
		 	}
		$contusuario++;
		}
/* Fin */

							 $mail->WordWrap = 900;
							 $mail->IsHTML(true);

							$message.='<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tbody>
							<tr>
							<td width="100%">
							<p>&nbsp;</p>
							<p>MCC - Soporte t&eacute;cnico <br />Mobile : (+598) (0) 96-261570 <br />Montevideo: (+598) 2486.3046 &nbsp;&nbsp;</p>
							<p style="font-size: 0pt; line-height: 0pt; height: 30px;"><span style="color: #1f1f1f; font-family: Tahoma; font-size: 20px; font-weight: bold; line-height: 24px; text-align: center;"><br /></span></p>
							<div style="font-size: 0pt; line-height: 0pt; height: 10px;"><img style="height: 10px;" src="./datos_sistema/images/empty.gif" alt="" width="1" height="10" /></div>
							<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">Enviado desde UYCODEKA Facturaci&oacute;n WEB<br /> Con UYCODEKA obtenga r&aacute;pidamente informaci&oacute;n sobre el estado de su empresa</div>
							<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">&nbsp;</div>
							<hr> <font size="-1">
								Por favor considere el medio ambiente y no imprima este correo a menos que lo necesite.
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
		$contador++;
		}

}

/*echo "<br><center> ------------- Total de fallas -------------------<br>
		------------------ ".$total." -----------------------</center>"; 
*/		
?>
