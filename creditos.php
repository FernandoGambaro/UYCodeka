<?php 
session_start();
require_once('class/class_session.php');
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
	 header("Location:index.php");
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
		$s->save();  	
		 header("Location:index.php");
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$USERID = $_SESSION['USERID'];
$tipo = $_SESSION['USERTIPO'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<style type="text/css">

.Estilo4 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.Estilo5 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
}
.Estilo6 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif;}
.Estilo7 {font-size: 12px;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 background:#840f0f; 
 font-weight: bold; 
 color:#FFFFFF;
 padding: 3px;
 }

</style>
</head>

<body>
<p>&nbsp;</p>
<table width="90%" border="0" align="center">
<tr><td width="50%" valign="top"><table width="90%" border="0" align="center">
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Personalización a Services</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Fernando Gámbaro</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Diseño Extendido, Reportes</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Fernando Gámbaro</div></td>
    <td>&nbsp;</td>
  </tr>  
  <tr height="200px">
    <td>&nbsp;</td>
    <td><div align="center">
    <img src="img/central.png" width="300" height="124" alt="" /></div></td>
    <td>&nbsp;</td>
  </tr>
</table></td><td width="50%" valign="top">
<table width="90%" border="0" align="center">
    <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Creadores</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Pedro Obregon Mejias</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Dise&ntilde;o&nbsp;Base</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Manuel Sauceda Garcia</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Libreria FPDF</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Oliver Plathey</div></td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">Libreria Barcode</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Harish Chauhan</div></td>
    <td>&nbsp;</td>
  </tr>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo7">PHP Graph Plotting library. Base module.</div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="center" class="Estilo6">Asial Corporation</div></td>
    <td>&nbsp;</td>
  </tr>  
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><table width="50%" border="0" align="center">
        <tr>
          <td><div align="center"><span class="Estilo5">Resolución óptima 1024 x 768 píxeles  </span></div></td>
        </tr>
      </table></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="27">&nbsp;</td>
      <td><table width="50%" border="0" align="center">
        <tr>
          <td width="38%"><div align="right"><img src="img/firefox.gif" width="80" height="15" /></div></td>
          <td width="62%"><span class="Estilo5">Aplicación optimizada para Firefox </span></td>
        </tr>
      </table></td>
      <td>&nbsp;</td>
    </tr>
</table>
</td></tr></table>
</body>
</html>
