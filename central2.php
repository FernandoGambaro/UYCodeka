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
 

/*
Include the session class. Modify path according to where you put the class
file.
*/

require_once __DIR__ .'/classes/class_session.php';
require_once __DIR__ .'/common/languages.php';
require_once __DIR__ .'/common/verificopermisos.php';   

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
  echo "<h2>".'Ocurrió un error al iniciar session!'."</h2>";
  echo $s->log;
  exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	?>
<script language="JavaScript" type="text/javascript">
   parent.changeURL('index.ph' );
</script>
	<?php
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
	       header("Location:index.php");	
	       exit;
   }
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

date_default_timezone_set('America/Montevideo');
session_start();

$status_msg = "";

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
$ip=$s->data['IP'];
$ask= $s->data['ask'];

$paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

$hoy=date("Y-m-d");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="library/bootstrap/bootstrap.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <script src="library/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

    <link rel="stylesheet" href="library/colorbox/colorbox.css?u=<?php echo time();?>" />
    <script src="library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

    <script src="library/js/OpenWindow.js" type="text/javascript"></script>

<link href="library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="library/toastmessage/message.js" type="text/javascript"></script>

<script src="library/js/jquery.msgBox.js?u=<?php echo time();?>" type="text/javascript"></script>
    <link href="library/js/msgBoxLight.css?u=<?php echo time();?>" rel="stylesheet" type="text/css">	
    

<script type="text/javascript" src="library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="library/estilos/customCSS.css?u=<?php echo time();?>" rel="stylesheet">

 
<script  src="library/js/jquery-ui.js"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="library/estilos/font-awesome.min.css">

<script type="text/javascript">
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;<?php echo 'Inicio';?>");


</script>

<style type="text/css">

.sprite {
    background-image: url("library/images/imagenes-cotizacion-dolar.png");
    background-repeat: no-repeat;
    display: block;
    float: left;
    top: -5px;

}
.sprite-Argentinax24 {
    background-position: -5px -5px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Argentinax32 {
    background-position: -39px -5px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-Brazilx24 {
    background-position: -81px -5px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Brazilx32 {
    background-position: -115px -5px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-Chilex24 {
    background-position: -5px -47px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Chilex32 {
    background-position: -39px -47px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-European-Unionx24 {
    background-position: -81px -47px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-European-Unionx32 {
    background-position: -115px -47px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-United-StatesUnited-Statesx32 {
    background-position: -5px -89px;
    height: 32px;
    width: 32px;
    top: -5px;
}
.sprite-United-Statesx24 {
    background-position: -47px -89px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Uruguayx24 {
    background-position: -81px -89px;
    height: 24px;
    margin-top: -4px;
    width: 24px;
    top: -5px;
}
.sprite-Uruguayx32 {
    background-position: -115px -89px;
    height: 32px;
    width: 32px;
    top: -5px;
}


div.cotizacion-contenido {
    background-image: linear-gradient(to bottom, #d4d4d4 21%, #f5f5f5 100%);
    border: 1px solid rgb(204, 204, 204);
    border-radius: 7px;
    box-shadow: 0 -1px 1px #aaa;
    display: table;
    margin-left: auto;
    margin-right: auto;
    padding-top: 2px;
    width: 100%;
}
div.cc-1 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    color: white;
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
    font-weight: bold;
    height: 16px;
    margin-bottom: -3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 40%;
    padding: 5px 1%;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
}
div.cc-2 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    float: left;
    height: 16px;
    margin-bottom: 3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 10px;*/
    text-align: center;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
    width: 26%;
}
div.cc-3 {
    background-color: rgb(72, 72, 72);
    border: 1px solid rgb(153, 153, 153);
    float: left;
    height: 16px;
    margin: 3px 1%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 10px;*/
    text-align: center;
    text-shadow: -2px 2px 3px rgb(0, 0, 0);
    width: 26%;
}
div.cc-1b {
    background-color: #989b87;
    border: 1px solid rgb(153, 153, 153);
    color: white;
    float: left;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 1em;
    font-weight: bold;
    height: 24px;
    margin-bottom: 3px;
    margin-left: 1%;
    margin-top: 3px;
    min-width: 40%;
    padding-left: 1%;
    padding-right: 1%;
    padding-top: 10px;
    text-shadow: -2px 2px 3px rgb(91, 107, 73);
}
div.cc-2b {
    background-color: rgb(234, 234, 234);
    border: 1px solid rgb(204, 204, 204);
    float: left;
    height: 24px;
    margin-bottom: 10px;
    margin-left: 1%;
    margin-top: 3px;
    max-width: 26%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 5px;*/
    text-align: center;
}
div.cc-3b {
    background-color: rgb(234, 234, 234);
    border: 1px solid rgb(204, 204, 204);
    float: left;
    height: 24px;
    margin-bottom: 10px;
    margin-left: 1%;
    margin-top: 3px;
    max-width: 26%;
    min-width: 26%;
    padding-left: 1%;
    padding-right: 1%;
    /*padding-top: 5px;*/
    text-align: center;
    width: 26%;
}
div.cc-1, div.cc-2, div.cc-3, div.cc-1b, div.cc-2b, div.cc-3b {
    border-radius: 7px;
}
span.cotizacion-num {
    color: blue;
    font-size: 16px;
    font-weight: bold;
    text-shadow: -1px 1px 1px rgb(153, 153, 153);
}
div.cotizacion-billete {
    display: table;
    font-size: 12px;
    min-width: 100px;
    position: relative;
/*    position: absolute;*/
    visibility: visible;
    top: -18px;
    float: left; 
}
div.cotizacion-billete b {
   position: relative;
   float: left;
   left: 5px;
	top: 10px;
}
div.cotizacion-titulo1 {
    display: table;
    min-width: 100px;
    position: relative;
    visibility: visible; 
    top: -8px;   
}
span.cotizacion-titulo1 {
    color: rgb(255, 165, 0);
    font-size: 12px;
    font-weight: bold;
    margin-left: 5px;
}
span.cotizacion-titulo2 {
    color: rgb(255, 165, 0);
    font-size: 10px;
    font-weight: bold;
    top: -8px;     
}
div.cotizaciones-a {
    background: linear-gradient(to bottom, #f5f5f5 10%, #d4d4d4 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid rgb(204, 204, 204);
    border-radius: 7px;
    box-shadow: 0 2px 6px #aaa;
    display: table;
    font-family: Arial,Helvetica,sans-serif;
    margin-left: 1%;
    min-width: 180px;
    padding: 10px;
    visibility: visible;
    width: 86%;
}
@media all and (max-width: 783px) {
div.cotizacion-billete {
    font-size: 16px;
}
@media all and (max-width: 602px) {
div.cotizacion-billete {
    font-size: 16px;
}
@media all and (max-width: 480px) {
div.cotizaciones-a {
    display: block;
    margin-left: 1px;
    min-width: 142px;
    overflow: visible;
    padding: 1px;
    width: 94%;
}
span.cotizacion-num {
    color: blue;
    font-size: 16px;
    font-weight: bold;
    text-shadow: -1px 1px 1px rgb(153, 153, 153);
}
div.cotizacion-billete {
    box-sizing: border-box;
    display: table;
    font-size: 10px;
    overflow: visible;
/*    position: absolute;*/
    visibility: visible;
    top: -15px;
    float: left;
}
div.cc-1, div.cc-1b, div.cc-2b, div.cc-3b {
    height: 38px;
    margin-left: 1px;
    /*min-width: 126px;*/
    overflow: visible;
    padding-left: 1px;
    padding-right: 1px;
    width: 95%;
    float: left;
}
div.cc-1b {
    height: 32px;
}
div.cc-2b, div.cc-3b {
    min-width: 45%;
}
span.cotizacion-titulo1 {
    font-size: 12px;
}
span.cotizacion-titulo2 {
    font-size: 11px;
}
div.cc-1, div.cc-2, div.cc-3, div.cc-1b, div.cc-2b, div.cc-3b {
    float: left;
    overflow: visible;
}
#cc-2a1, #cc-2a2 {
    float: left;
    overflow: visible;
    width: 45%;
}
}
}
}

</style>
</head>

<body>
<div class="content-fluid">
    <div class="row">
        <div class="col-xs-12">
        <div class="d-flex justify-content-center">
            <img id="logo_" src="library/images/central.png" width="200" height="108" border="0" usemap="#map" />
            <map name="map">
            <!-- #$-:Image map file created by GIMP Image Map plug-in -->
            <!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
            <!-- #$-:Please do not edit lines starting with "#$" -->
            <!-- #$VERSION:2.3 -->
            <!-- #$AUTHOR:fernando -->
            <area shape="rect" coords="0,0,268,149" href="https://codeka-uy.blogspot.com" target="_blank" />
            </map>
        </div>
        </div>
    </div>

  <div class="row">
    <div class="col-xs-4">
        <div class="row">
        <div class="col-xs-12">
        <?php echo 'Fuente';?> <a href="https://uy.cotizacion-dolar.com/cotizacion_hoy_uruguay.php" target="_blank">https://uy.cotizacion-dolar.com/</a>
        </div>
        </div>
        <div class="row">
        <div class="col-xs-12">  
            <div class="cotizaciones">
            <?php
            require_once "./funciones/simple_html_dom.php";
//            $html = file_get_contents('https://uy.cotizacion-dolar.com/cotizacion_hoy_uruguay.php'); //get the html returned from the following url
            function getHTML($url,$timeout)
            {
                $ch = curl_init($url); // initialize curl with given url
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
                curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
                $page =@curl_exec($ch);
                return $page;
            }

            $html=getHTML("https://uy.cotizacion-dolar.com/cotizacion_hoy_uruguay.php",10);

//            preg_match('{<div\s+class="cotizaciones-a"\s*>((?:(?:(?!<div[^>]*>|</div>).)++|<div[^>]*>(?1)</div>)*)</div>}si', $html, $match);
        $pattern = '~<!-- div_cotizaciones -->(.*)<!-- /div_cotizaciones -->~iUs';
            preg_match_all($pattern, $html, $match);
            $amostrar= @$match[0][0];
            //$amostrar= str_replace('cc-1b', 'col-xs-4', $amostrar);
            //$amostrar= str_replace('cc-2b', 'col-xs-4', $amostrar);
            //$amostrar= str_replace('cc-3b', 'col-xs-4', $amostrar);
            echo $amostrar;
            ?>    
            </div>       
        </div>
        </div>
    </div>
    <div class="col-xs-8">
    <?php
        if($UserTpo!=100 and $UserTpo!=2){
        ?>
    <div class="col-xs-6">
        <script>
        $(document).ready(function(){
        
        $('#pendientes').load("reportes/horas/DiasPendientesDeRegistro.php?codusuario=<?php echo $UserID;?>");
        $('#pendientesanterior').load("reportes/horas/DiasPendientesDeRegistro.php?codusuario=<?php echo $UserID;?>&fechaini=<?php echo date("d-m-Y",strtotime(date("Y-m-d")."- 1 month"));?>");

        });
        </script>
            <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?php echo 'Falta registrar horas mes anterior'; ?></legend>
                    <div id="pendientesanterior"></div>
            </fieldset>
    </div>
    <div class="col-xs-6">
    <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?php echo 'Falta registrar horas mes en curso'; ?></legend>
                    <div id="pendientes"></div>
            </fieldset>
    </div>
    </div>
  </div>
</div>
<?php
   }
include ("funciones/fechas.php");
$fechain = date ('Y-m-d', strtotime('-1 month')) ;
$startTime =data_first_month_day($fechain); 
$endTime = data_last_month_day($fechain);

 if(file_exists(".listo.php")) {
include(".listo.php");
$fechafin = date('Y-m-d', strtotime('+1 month')) ;
 $fechacomparar=date('Y-m-d', strtotime(explota($Fecha_Instalacion)));

if(($fechafin-$fechacomparar)>0 and $ask==1) {

  ?>
<script type="text/javascript" >
var noteId="ayuda/opinion.php?ask=<?php echo $ask;?>";
var w='800px';
var h='90%';
parent.OpenNote(noteId,w,h, scroll=false);
</script>
<?php } 
}
?>
</body>
</html>