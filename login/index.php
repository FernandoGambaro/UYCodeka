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

include("common/funcionesvarias.php");

$tratamiento='';
$status_msg = "";

$act=isset($s->data['act']) ? $s->data['act'] : null ;

if ($act=="logout") {
   @$USERID=$s->data['UserID'];

   $msg="Salida normal del sistema";
   logger($USERID, $msg);
	@conectado(0, $USERID, $s->data['UserNom']);
   session_unset();
 //session_destroy();
 $s->expire();
} elseif ($act=="timeout") {
   $USERID=$s->data['UserID'];
   $msg="Sesión cerrada por tiempo inactividad";
   logger($USERID, $msg);
   session_unset();
 //session_destroy();
 $s->expire();
}
//header('Content-Type: text/html; charset=UTF-8'); 

if (isset($_POST['method'])) {
  /* Formulario remitido */
  if (Validate()) {
    if (Auth()) {
      $s->data['logged_in'] = true;
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   /*/check ip from share internet*/
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   /*/to check ip is pass from proxy*/
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
for($x=1; $x<=255; $x++) {
$tmp="192.168.1.".$x;
$rango[$tmp]=$tmp;
}

if (in_array($ip, $rango) or $ip =="127.0.0.1") {
	$extratime=1000000; /* Tiempo duración sesión red local */
} else {
	$extratime=25000; /* Tiempo duración sesión red remota */
}
      $s->data['UserID'] = $UserID;
      $s->data['IP'] = $ip;
      $s->data['UserTpo'] = $UserTpo;
      $s->data['UserTra'] = $UserTra;
      $s->data['UserNom'] = $UserNom;
      $s->data['UserApe'] = $UserApe;
      $s->data['hora'] = time();
      $s->data['inactivo'] = 60*$extratime; /*/segundos por la cantidad de minutos*/
      $s->data['isLoggedIn'] = true;
      $s->data['timeOut'] = 60*$extratime;
      $s->data['loggedAt'] = time();
      $s->data['version']="1.9.0";
      $s->save();
		header ("Location:../menu.php");
//		header ("Location:index2.php");
    }
  }
} else {
  $s->expire();
  $nombre = "";
  $password = "";
}

LoginForm($act);

function Validate() {
  global $nombre, $password, $status_msg;
  $ret = true;
  $nombre      = strip_tags($_POST['nombre']) ;
  if (strlen($nombre) == 0) {
    $ret = false;
    $password="";
    $status_msg .= "Ingrese nombre de usuario.<br />";
  }
  
  $password = strip_tags($_POST['password']);
  if (strlen($password) == 0) {
    $ret = false;
    $password="";
    $status_msg .= "Ingrese contraseña.<br />";
  }
  
  return $ret;
}

function Auth() {
  global $nombre, $password, $status_msg, $UserID, $UserTpo, $UserApe, $UserNom, $UserTra;
  include("conexion.php");
  /*/echo "autorizo<br>";*/
  require(dirname(__FILE__).'/class/Encryption.php');

	if($nombre=="" or $password ==""){
		$status_msg .= " Datos incorrectos<br />";
		$nombre="";
		$password="";
		return false;
        }

    $nombre   = htmlspecialchars($_POST['nombre']) ;
    $password = md5 (htmlspecialchars($_POST['password']));
	//return false;
	 $posicion = strpos($nombre, '@');

	 /*echo $posicion."<br>";*/
	 /*/ Seguidamente se utiliza ===.  La forma simple de comparacion (==)*/
	 /*/ no funciona como deberia, ya que la posicion de 'a' es el caracter*/
	 /*/ numero 0 (cero)*/
	 if ($posicion === false and $nombre!='Admin') {
		     $status_msg .= " Datos incorrectos<br />";
		     $nombre="";
		     $password="";
		     return false;
	 } else {
	
		/*Busco si es usuario*/	
	    $str = $_POST['password'];
	    $converter = new Encryption;
	    $encoded = $converter->encode($str );
		$c_usuario = "SELECT * FROM `usuarios` WHERE `usuario`='$nombre' AND `contrasenia`='$encoded' AND `estado`=0";
	    $r_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	    if(mysqli_num_rows($r_usuario)>0) { 
	    $r_ok = @mysqli_fetch_array($r_usuario);
			    if($r_ok['usuario'] != $nombre && $r_ok['contrasenia'] != $encoded){
				     $status_msg .= $nombre." Datos incorrectos<br />";
				     $nombre="";
				     $password="";
				     return false;
			    } else  {	
				     $UserID = $r_ok['codusuarios'];
				     $UserTra =$r_ok['tratamiento'];
				     $UserTpo =-1;
				     $UserNom = $r_ok['nombre'];
				     $UserApe = $r_ok['apellido'];
				     $nombre="";
				     $password="";
				     return true;
				}			
		/**/	
			if ( $_POST['password']=='admin' and $nombre=='Admin') {
			     $UserID = '2';
			     $UserTpo ='2' ;
			     $UserNom = $nombre;
			     $UserApe = $nombre;
			     return true;
			}			
		//}
	 } else {
	 	$status_msg="El usuario no existe o no esta habilitado";
	 }
}
}

function LoginForm($act) {
  global $nombre, $password, $status_msg;

include("../conectar.php");
$query="SELECT * FROM datos WHERE coddatos='0'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$row = mysqli_fetch_array($rs_query);

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" class=" js no-touch csstransforms3d csstransitions"><head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $row["nombre"];?> / Facturación WEB</title>
    <meta name="description" content="Facturación WEB">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">

    
		<link rel="stylesheet" type="text/css" href="login/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="login/css/style.css" />
        <link rel="stylesheet" type="text/css" href="login/css/custom.css" />
		<script type="text/javascript" src="login/js/modernizr.custom.79639.js"></script>
		<noscript>
			<link rel="stylesheet" type="text/css" href="login/css/styleNoJS.css" />
		</noscript>
		    
    <script>
        $(function () {
            $('#pestanas').tabs();
            $('#button').on('click',function(){
                $('#submit2').click();
            });
        });

    </script>
    
    <script>
        //<![CDATA[
        $(document).ready(function(){
            try {
                if ($("#username").val().length > 0) {
                    $("#password").focus();
                }else{
                    $("#username").focus();
                }

                if(($("#msg").html()+"")!="undefined"){

                    $("#username").val()

                }
            }catch (ex){
                if(window.console){
                    console.log(ex);
                }
            }



        });
        //]]>
    </script>

</head>

<body>

<div class="container demo-2">

    <header class="clearfix">
        <a class="logo_">
            <img src="img/central.png" height="94" alt="logo">
        </a>
    </header>


<div class="md-overlay"></div>

    <div id="pestanas" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <br>
        
        <div id="slide" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
            <!-- Log -->
            <div class="cont-right2">

                <!--[if lte IE 8]>
                <div class="cont-log-ie9">
                    <div class="cont-log9">
                        <h3>Advertencia</h3>
                        <p>Estás usando una Versión de Internet Explorer incompatible con UYCODEKA.</p>
                        <p>Te invitamos a utilizar un navegador moderno para que puedas utilizar este sitio:</p>
                        <ul class="navegadores">
                            <li>
                                <span class="logo_chrome"></span><p>Chrome</p><a class="btn-descarga btn-outline-inverse fr" href="https://www.google.com/chrome/browser/"><span class="descarga"></span>Descargar</a>
                            </li>
                            <li>
                                <span class="logo_firefox"></span><p>Firefox</p><a class="btn-descarga btn-outline-inverse fr" href="https://www.mozilla.org/es-CL/firefox/"><span class="descarga"></span>Descargar</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <![endif]-->

                <div class="cont-log">
                <img src="datos_sistema/loadimage.php?id=11&default=1" height="119" alt="logo">
		        <form id="formLogin" class="form-signin" name="loga" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                        <div>
											<br>
                                <div class="input-group">
                                    <span class="input-group-addon "><i class="icon-user icon-color"></i></span>
                                    <input id="nombre" name="nombre" class="input form-control" tabindex="1" placeholder="Usuario" accesskey="n" type="text" value="" size="25" autocomplete="false">
                                </div>
                             <input type="hidden" name="method" value="login" />

                            
                             <div class="input-group mt10">
                                <span class="input-group-addon "><i class="icon-lock"></i></span>
                                <input id="password" name="password" class="input form-control" tabindex="2" placeholder="Contraseña" accesskey="c" type="password" value="" size="25" autocomplete="off">
                            </div>
                        </div>
                        <div id="button" class="android-btn">
                        </div>
                        <input id="submit2" style="display:none" class="submit" name="submit" accesskey="l" value="Ingresar" tabindex="4" type="submit">
                    </form>
                    <span class="link-passwd"><a href="pw/forgotPass.php">Olvidé mi nombre de usuario/contraseña</a></span>
                    
                </div>
            </div>


            <!-- slider -->
            <div id="slider" class="sl-slider-wrapper">

                <div class="sl-slider" style="width: 1351px; height: 560px;">

                    <div class="sl-slides-wrapper">
                    
                    <div class="sl-slide sl-slide-vertical sl-trans-elems" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5" style="display: block; z-index: 1;">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content"><div class="sl-slide-inner">
                            <div class="bg-img bg-img-2"></div>
                            <div class="blur">
                                <div id="bg-blur-2" class="bg-blur-2"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">
                                                <h3><?php echo $row["nombre"];?> Facturación WEB</h3>

                                                <p>Desde cualquier parte del mundo.
                                               
                                                </p>
                                            </div>
                                        </div>
                                        <div class="cont-img2 fr">
                                            <div class="img-funcionarios"></div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>

                                </div>
                            </div>

                        </div></div></div>
                    </div>

                    </div>
                    <!-- /sl-slider -->
                </div>
                <!-- /slider-wrapper -->
                <!--
                <nav id="nav-dots" class="nav-dots">
                    <span class=""></span>
                    <span class="nav-dot-current"></span>
                    <span class=""></span>
                </nav>
                -->
            </div>
        </div>
        

    </div>
</div>


<div class="footer">

    <!-- Codrops top bar -->
    <div class="codrops-top clearfix">
        <div class="right">
            <a href="http://www.mcc.com.uy" target="_blank">MCC Soporte Técnico</a>

                    <span>
                        +598 9626 1570
                    </span>
            <div>
            <img src="datos_sistema/loadimage.php?id=11&default=1" height="46" alt="logo">
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript" src="login/js/jquery.ba-cond.min.js"></script>
		<script type="text/javascript" src="login/js/jquery.slitslider.js"></script>
		<script type="text/javascript">	
			$(function() {
			
				var Page = (function() {

					var $nav = $( '#nav-dots > span' ),
						slitslider = $( '#slider' ).slitslider( {
							onBeforeChange : function( slide, pos ) {

								$nav.removeClass( 'nav-dot-current' );
								$nav.eq( pos ).addClass( 'nav-dot-current' );

							}
						} ),

						init = function() {

							initEvents();
							
						},
						initEvents = function() {

							$nav.each( function( i ) {
							
								$( this ).on( 'click', function( event ) {
									
									var $dot = $( this );
									
									if( !slitslider.isActive() ) {

										$nav.removeClass( 'nav-dot-current' );
										$dot.addClass( 'nav-dot-current' );
									
									}
									
									slitslider.jump( i + 1 );
									return false;
								
								} );
								
							} );

						};

						return { init : init };

				})();

				Page.init();

				/**
				 * Notes: 
				 * 
				 * example how to add items:
				 */

				/*
				
				var $items  = $('<div class="sl-slide sl-slide-color-2" data-orientation="horizontal" data-slice1-rotation="-5" data-slice2-rotation="10" data-slice1-scale="2" data-slice2-scale="1"><div class="sl-slide-inner bg-1"><div class="sl-deco" data-icon="t"></div><h2>some text</h2><blockquote><p>bla bla</p><cite>Margi Clarke</cite></blockquote></div></div>');
				
				// call the plugin's add method
				ss.add($items);

				*/
			
			});
		</script>

</body></html>
  <?php
}
?>