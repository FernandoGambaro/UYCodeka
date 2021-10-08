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
require_once('../../class/class_session.php');

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
   $s->data['loggedAt']= time();/*/ update last accessed time*/
   $s->save();
}

//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set('America/Montevideo');
session_start();

$status_msg = "";

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
$ip=$s->data['IP'];


$ShowName=$UserNom. " " .$UserApe;


include("../../conectar.php");
include("../../common/funcionesvarias.php");
include("../../common/verificopermisos.php");

//include("../../phpjobscheduler/firepjs.php");

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menú UYCodeka</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">

  <link rel="stylesheet" href="../dist/metisMenu.min.css">
  <link rel="stylesheet" href="demo.css">
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">


<script>
/* free code from dyn-web.com */

function getDocHeight() {
height = window.innerHeight;
document.getElementById("alto").value= height;
return height;
}

function setIframeHeight(id) {

var ifrm = document.getElementById(id);
var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
ifrm.style.visibility = 'hidden';
ifrm.style.height = "10px"; // reset to minimal height in case going from longer to shorter doc
ifrm.style.height = getDocHeight() - 80+"px";
ifrm.style.visibility = 'visible';

}
</script>

<?php
$miVariable =  $_COOKIE["variable"];
	$s->data['alto']= floor($miVariable);
   $s->save();
   
	$_SESSION['alto'] = $miVariable;
?>		
	
<style type="text/css">
body {
   background: url("../background.jpg") repeat-x scroll 0 0 ;
   margin: 0;
   text-align: left;
	padding: 0;
	width: 100%;
	height: 100%;
}
</style>

</head>

<body onload="setIframeHeight('principal');">


  <div class="clearfix">
    <aside class="sidebar">
      <nav class="sidebar-nav">
        <ul class="metismenu" id="menu">
          <li class="active">
            <a href="#" aria-expanded="true">
            
             <i class="fa fa-th fa-1x" aria-hidden="true"></i>

              <span class="sidebar-nav-item">Menú</span>
              <span class="fa arrow fa-fw"></span>
              
            </a>
            <ul class="collapse" aria-expanded="true">
              <li>
                <a href="../../central2.php" target="principal" class="trigger" id="inicio">
						<span class="fa-stack"><i class="fa fa-home fa-1x" aria-hidden="true"></i></span>&nbsp;Inicio</a>
                 
                </a>
              </li>
              
            </ul>
          </li>
          <li>
            <a href="#" aria-expanded="false" class="trigger" id="menudoc">
            <span class="fa-stack"><i class="fa fa-book fa-1x" aria-hidden="true"></i></span>&nbsp;Documentos<span class="glyphicon arrow"></span></a>
            <ul style="height: 0px;" class="collapse" aria-expanded="false">
              <li><a href="#" aria-expanded="false" class="trigger" id="menuventas">Ventas<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
              <li><a href="../../facturas_clientes/index.php" target="principal" class="trigger" id="ventas"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Ventas</a></li>
              <li><a href="../../cobros/index.php" target="principal" class="trigger" id="cobrosrapidos"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Cobros rápido</a></li>
              <li><a href="../../recibos/index.php" target="principal" class="trigger" id="cobros"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Cobros</a></li>
              <li><a href="../../clientes/autofacturas/index.php" target="principal" class="trigger" id="autofac"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Prog. auto facturas</a></li>
            </ul>
            </li>
				<li><a href="#" aria-expanded="false" class="trigger" id="menucompras">Compras<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
              <li><a href="../../facturas_proveedores/index.php" target="principal" class="trigger" id="compras"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Compras</a></li>
              <li><a href="../../pagos/index.php" target="principal" class="trigger" id="pagoscompras"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Pagos</a></li>
            </ul>
            </li>
            
				<li><a href="#" aria-expanded="false" class="trigger" id="menuotros">Otros<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
              <li><a href="../../pagosdgi/index.php" target="principal" class="trigger" id="pagodgi"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Pago DGI</a></li>
            </ul>
            </li>                         
            </ul>
          </li>
          
          
			 <li>
            <a href="#" aria-expanded="false" class="trigger" id="reportes">
            <span class="fa-stack"><i class="fa fa-book fa-1x" aria-hidden="true"></i></span>&nbsp;Reporte<span class="glyphicon arrow"></span></a>
            <ul style="height: 0px;" class="collapse" aria-expanded="false">
              <li><a href="#" aria-expanded="false" class="trigger" id="repven">Ventas<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
              <li><a href="../../reportes/index.php" target="principal" class="trigger" id="repcomisiones"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Comisiones</a></li>
              <li><a href="../../ventas/index.php" target="principal" class="trigger" id="repventas"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Deudores x ventas</a></li>
              <li><a href="../../abonados/index.php" target="principal" class="trigger" id="abonados"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Abonados</a></li>
              <li><a href="../../ventas_articulos/index.php" target="principal" class="trigger" id="ventaarticulos"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Artículos vendidos</a></li>
              <li><a href="../../cierremes/index.php" target="principal" class="trigger" id="cierremes"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Cierre mes</a></li>
            </ul>
            </li>
				<li><a href="#" aria-expanded="false" class="trigger" id="comprasmenu">Compras<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
              <li><a href="../../facturas_proveedores/index.php" target="principal" class="trigger" id="comprasprov"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>Compras</a></li>
              <li><a href="../../pagos/index.php" target="principal" class="trigger" id="pagosprov"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span>Pagos</a></li>
            </ul>
            </li>            
            </ul>
          </li>          
          
          <li>
            <a href="#" aria-expanded="false" class="trigger" id="mantenimiento"><span class="fa-stack"><i class="fa fa-cog fa-1x" aria-hidden="true"></i></span>&nbsp;Mantenimiento<span class="glyphicon arrow"></span></a>
            <ul class="collapse" aria-expanded="false">
              <li><a href="#" aria-expanded="false" class="trigger" id="ajentes">Ajentes&nbsp;Comerciales<span class="fa plus-times"></span></a>
               <ul class="collapse" aria-expanded="false">              
               <li><a href="../../clientes/index.php" target="principal" class="trigger" id="clientes"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>&nbsp;Clientes</a></li>
               <li><a href="../../proveedores/index.php" target="principal" class="trigger" id="proveedores"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>Proveedores</a></li>
               <li><a href="../../usuarios/index.php" target="principal" class="trigger" id="usuarios"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>Usuarios</a></li>
               </ul>
              </li>
				  <li>
                <a href="#" aria-expanded="false" class="trigger" id="arti">Artículos<span class="fa plus-times"></span></a>
                <ul class="collapse" aria-expanded="false">
	              <li><a href="../../articulos/index.php" target="principal" class="trigger" id="articulos"><span class="fa-stack"><i class="fa fa-truck" aria-hidden="true"></i></span>Artículos</a></li>
   	           <li><a href="../../familias/index.php" target="principal" class="trigger" id="familias"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>Familia artículos</a></li>
                </ul>
              </li>              
               <li>
               <li>
                
                </li>

            </ul>
          </li>
          <li>
            <a href="#" aria-expanded="false" class="trigger" id="config"><span class="fa-stack"><i class="fa fa-wrench" aria-hidden="true"></i></span>&nbsp;Configuración<span class="glyphicon arrow"></span></a>
                <ul class="collapse" aria-expanded="false">
						<li>
                	<a href="#" aria-expanded="false" class="trigger" id="datosmenu">Datos de la empresa<span class="fa plus-times"></span></a>
                		<ul class="collapse" aria-expanded="false">
                <li><a href="../../datos_sistema/index.php" target="principal" class="trigger" id="datos"><span class="fa-stack"><i class="fa fa-assistive-listening-systems" aria-hidden="true"></i></span>Datos del sistema</a></li>
                <li><a href="../../sectores/index.php" target="principal" class="trigger" id="sectores"><span class="fa-stack"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></span>Sectores empresa</a></li>
                <li><a href="../../ubicaciones/index.php" target="principal" class="trigger" id="locales"><span class="fa-stack"><i class="fa fa-shopping-basket" aria-hidden="true"></i></span>Locales</a></li>
                		</ul>
              		</li>                 
                <li><a href="../../entidades/index.php" target="principal" class="trigger" id="bancos"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span>Bancos</a></li>
					 <li><a href="../../embalajes/index.php" target="principal" class="trigger" id="embalaje"><span class="fa-stack"><i class="fa fa-suitcase" aria-hidden="true"></i></span>Embalajes</a></li>
					 <li><a href="../../formaspago/index.php" target="principal" class="trigger" id="formapago"><span class="fa-stack"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>Forma de pago</a></li>
					 <li><a href="../../tipocambio/index.php" target="principal" class="trigger" id="tipocambio"><span class="fa-stack"><i class="fa fa-exchange" aria-hidden="true"></i></span>Tipo de cambio</a></li>
					 <li><a href="../../impuestos/index.php" target="principal" class="trigger" id="impuestos"><span class="fa-stack"><i class="fa fa-tasks" aria-hidden="true"></i></span>Impuestos</a></li>
					 <li><a href="../../etiquetas/index.php" target="principal" class="trigger" id="etiquetas"><span class="fa-stack"><i class="fa fa-tags" aria-hidden="true"></i></span>Etiquetas</a></li>
                <li><a href="../../phpjobscheduler/index.php" target="principal" class="trigger" id="cron"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>PhpJobScheduler</a></li>
                </ul>
          </li>
        </ul>
      </nav>
    </aside>
    <section class="content" style="height:100%; background-color: transparent;">
    
		<div class="col-xs-12" style="height:100%; background-color: transparent;">
        <div class="panel panel-default"style="height:100%; background-color: transparent; border:0px;">
          <div class="panel-heading">
          	<div>
					<img src="../../datos_sistema/loadimage.php?id=11&default=1" height="37" alt="" style="position:relative; float: left; top:-7px; padding: 3px 0 0 3px; margin-right:10px; margin-left:-5px; ">
				</div>
		
				<div id="msganio" style="display:flex; float: left;text-align: left; color: #194685; font-size: 15px;	 margin:0 auto; text-align: center; font-weight: bold;"></div>
				&nbsp;&nbsp;
				<div style="position:inherit; float: left; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#000; display:flex; ">
					<div id="check" style="display:none;">
					<div style="display:flex;left:10px; border: 1px solid #0099CC; padding: 1px;position: relative;border-radius: 2px; text-align: left; background: #fff; box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.12); width: 199px; height: 18px;">
				  		<div id="estado"></div><div id="estadotxt" style="position: relative;"></div>
					</div>
					</div>&nbsp;
				<div id="cancel" style="display: none; left:10px; ">
				<button class="boletin" style=" background-color: #194685;"> Cancelar</button>
				</div>
				<div style="clear:both; font-size:1px;"></div>
				</div>    
				
				<span class="pull-right">
             <img src="../minilogo.png" width="64" height="27" alt="">
            </span>  
            <br>          
          </div>
          
				<iframe src="../../central2.php" name="principal" id="principal" title="principal" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0" 
				scrolling="yes" align="center" allowtransparency="true"></iframe>	
        </div>
      </div>    

    </section>
    
  </div>
<input type="hidden" id="selid" name="selid">

<div style="position: fixed; bottom: 0; left: 0; background:#2A2A2A; width:100%; height:27px; margin: 0 0 0 0; padding-buttom: 2px;" >
<div style="position: fixed; bottom:4px; left:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff;  padding: 4px 10px 4px 10px;">
<font color="white"> MCC © 2016 <a href="http://www.mcc.com.uy" title="MCC - Soporte Técnico">MCC</a></font>&nbsp;
<?php echo $ip;?></div>
</div>

<div style="position: fixed; bottom:4px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px; left:0; right: 0;  margin: 0 auto;" align="center">versión <?php echo $s->data['version'];?></div>


<div id="UserData" align="right" style="position: fixed; bottom:4px; right:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px;">Bienvenido
 <?php echo $ShowName;?></div>
<input type="hidden" name="alto" id="alto" value=""></input> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="../dist/metisMenu.min.js"></script>

  <script>
    $(function() {
      $('#menu').metisMenu();
    });
  </script>
  
<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var idstyle='';

$(document).ready(function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();
$('.trigger').click(function(e){
  	if (idstyle!="") {	
		var el = document.getElementById(idstyle);
		el.setAttribute('style', '');    	
  	}
      list=this.id;
      idstyle=this.id;
      oidd='#n'+this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo);    
		document.getElementById("selid").value=list;
   });   
});

</script>  
  
</body>

<script>   

   jQuery.ajaxSetup({cache: false});       
	var xx;
   var numDoc;    //callGpsDiag(xx);             

    function callGpsDiag(xy,num){
		xx=xy;
		numDoc=num;
	  jQuery.ajax({
	    type: "POST",
	    url: "../../fpdf/monitoreo.php",
	    data: {execGpsDiag:xx, numDoc:numDoc},
	    async: true,
    	 cache: false,
	    success: function(data){ 
	    	if (data!="Fin") {
	        if (xx==1) {
	        	$("#check").toggle();
  				$("#cancel").toggle();
	        }
			move();
	        document.getElementById("estadotxt").innerHTML=" "+data;
	        setTimeout(function(){ 
	        xx=xx+1;
	            callGpsDiag(xx,numDoc);
	        },3000);
	        
	    	} else {
				var $p = $("#estado");
    				$p.stop()
      			.css("background-color","green");
     				$("#check").toggle();
     				$("#cancel").toggle();
	    	}
	   },
	     error: function() {
	     	     	$("#check").toggle();
     				$("#cancel").toggle();

	        //alert('fail');
	     }
  	 });        
	}

$("#cancel").click(function() {
	xx=0;
  	  jQuery.ajax({
	    type: "POST",
	    url: "../../fpdf/monitoreo.php",
	    data: {execGpsDiag:xx, numDoc:numDoc},
	    success: function(data){ 
	    	if (data=="Fin") {
	        	//$("#check").toggle();
      			$("#cancel").toggle();
	        		$("#check").toggle();
//	        	callGpsDiag(0);
	        }
	   },
	     error: function() {
	        alert('fallo al cancelar');
	     }
  	 });
});
            

function move() {
    var elem = document.getElementById("estado"); 
    var width = 1;
    var id = setInterval(frame, 30);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
            
        }
    }
}
  
</script>
</html>
