<?php
 include("../configuro.php"); 
include("assets/php/functions.php");
include("../class/PHPMailerAutoload.php");
//include('../classes/Encryption.php');
require(dirname(__FILE__) . '/../classes/Encryption.php');

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

//header('Content-Type: text/html; charset=UTF-8'); 

date_default_timezone_set('America/Montevideo');

$show = 'emailForm'; //which form step to show by default
if (@$_SESSION['lockout'] == true && (mktime() > @$_SESSION['lastTime'] + 900))
{
	@$_SESSION['lockout'] = false;
	@$_SESSION['badCount'] = 0;
}
if (isset($_POST['subStep']) && !isset($_GET['a']) && @$_SESSION['lockout'] != true)
{
	switch($_POST['subStep'])
	{
		case 1:
			//we just submitted an email or username for verification
			$result = checkUNEmail($_POST['uname'],$_POST['email']);
			if ($result['status'] == false )
			{
				$error = true;
				$show = 'userNotFound';
			} else {
				$error = false;
				$show = 'securityForm';
				$securityUser = $result['userID'];
			}
		break;
		case 2:
			//we just submitted the security question for verification
			if ($_POST['userID'] != "" && $_POST['answer'] != "")
			{
				$result = checkSecAnswer($_POST['userID'],$_POST['answer']);
				if ($result == true)
				{
					//answer was right
					$error = false;
					$show = 'successPage';
					$passwordMessage = sendPasswordEmail($_POST['userID']);
					$_SESSION['badCount'] = 0;
				} else {
					//answer was wrong
					$error = true;
					$show = 'securityForm';
					$securityUser = $_POST['userID'];
					$_SESSION['badCount']++;
				}
			} else {
				$error = true;
				$show = 'securityForm';
			}
		break;
		case 3:
			//we are submitting a new password (only for encrypted)
			if ($_POST['userID'] == '' || $_POST['key'] == '') header("location: ../index.php");
			if (strcmp($_POST['pw0'],$_POST['pw1']) != 0 || trim($_POST['pw0']) == '')
			{
				$error = true;
				$show = 'recoverForm';
			} else {
				$error = false;
				$show = 'recoverSuccess';
				updateUserPassword($_POST['userID'],$_POST['pw0'],$_POST['key']);
			}
		break;
	}
} elseif (isset($_GET['a']) && $_GET['a'] == 'recover' && $_GET['email'] != "") {
	$show = 'invalidKey';
	$result = checkEmailKey($_GET['email'],urldecode(base64_decode($_GET['u'])));
	if ($result == false)
	{
		$error = true;
		$show = 'invalidKey';
	} elseif ($result['status'] == true) {
		$error = false;
		$show = 'recoverForm';
		$securityUser = $result['userID'];
	}
}
if (@$_SESSION['badCount'] >= 3)
{
	$show = 'speedLimit';
	@$_SESSION['lockout'] = true;
	@$_SESSION['lastTime'] = '' ? mktime() : @$_SESSION['lastTime'];
}

$obj = new Consultas('datos');
$obj->Select('nombre');
$obj->Where('coddatos', '0');
$obj->Limit(0,1);
$usuarios= $obj->Ejecutar();
$numRows= $usuarios['numfilas'];
if ($numRows >= 1) {
	$nombre =  $usuarios['datos'][0]['nombre'];
}

?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="es" class=" js no-touch csstransforms3d csstransitions"><head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Facturación WEB / Recuperar contraseña</title>
    <meta name="description" content="Facturación WEB">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="../instalar/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="../instalar/css/style.css" />
        <link rel="stylesheet" type="text/css" href="../instalar/css/custom.css" />
        
		<script type="text/javascript" src="../instalar/js/modernizr.custom.79639.js"></script>
		<noscript>
			<link rel="stylesheet" type="text/css" href="../instalar/css/styleNoJS.css" />
		</noscript>
<link rel="stylesheet" href="../library/css3/css/font-awesome.min.css">
   <script type="text/javascript" >
   function fin() {
   	location.href="../index.php";
   }
   </script>
   <style type="text/css">
   .android0-btn:after {
  content: "Siguiente";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  text-align: center;
}
a {
  background-color: red;
  color: white;
  padding: .5em 1em;
  text-decoration: none;
  text-transform: uppercase;
}

a:hover {
  background-color: #555;
}

a:active {
  background-color: black;
}

a:visited {
  background-color: #ccc;
}

.cont-funcionarios{
    min-height: 350px;
  }

   </style>


</head>

<body>

<div class="container demo-2" style="width: 100%; ">

    <header class="clearfix">
        <span class="logo_">
            <img id="logo_" src="../img/central.png" height="94" alt="logo">
        </span>
    </header>

<div class="md-overlay"></div>

    <!--<div id="pestanas" class="ui-tabs ui-widget ui-widget-content ui-corner-all">-->
    <br>
        <!--
        <div id="slide" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
        -->
            <!-- Log -->
            <div class="cont-right2">

                <!--[if lte IE 8]>
                <div class="cont-log-ie9">
                    <div class="cont-log9">
                        <h3>Advertencia</h3>
                        <p>Está usando una Versión de Internet Explorer incompatible con UYCODEKA.</p>
                        <p>Le invitamos a utilizar un navegador moderno para que puedas utilizar este sistema:</p>
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
                <h3>Recuperación de contraseña</h3>
										<div class="cont-img2 fr">
                                      <div class="img-funcionarios"></div>
                              </div>  
                </div>
            </div>
            
            <!-- slider -->
            <div id="slider" class="sl-slider-wrapper" style="width: 100%; height: 530px;">
                <div class="sl-slider">

							<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 2351px; height: 430px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-1"></div>
                            <div class="blur" style="width: 2351px;">
                                <div id="bg-blur-1" class="bg-blur-1"></div>
                                <div class="blur-container">
                                    <div class="cont-left" style="width: 651px;height: 430px;">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">
                                            
                                            

<?php switch($show) {
	case 'emailForm': ?>
	
    <p>Ud. puede utilizar este formulario para recuperar su contraseña, para ello le estaremos enviando por mail un link para que genero una nueva. Ingrese su usuario o dirección de correo eléctronico en los campos siguientes.</p>
    <?php if (@$error == true) { ?>
    <span class="error">Debe ingresar un usuario o una dirección de correo para continuar.</span><?php } ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form0">
		<ul id="UPDiv">
			<li>
        <label for="userLogin">Usuario</label>
					<div class="rhs"><input type="text" name="uname" id="uname" value="" maxlength="20"></div>
			</li>
					<div class="rhs"><input type="hidden" name="subStep" value="1" /></div>
			<li>
        <label for="userLogin">Email</label>
				<div class="rhs"><input type="text" name="email" id="email" value="" maxlength="255"></div>
			</li>
			<li id="submit">        
			<br>
        <div class="rhs">
        
							</div>
							        <div id="button0" class="android0-btn">
							        </div>
							          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Siguiente">
			</li>
		</ul>
    </form>
    <?php break; case 'securityForm': ?>

    <p>Tengase a bien responder la siguiente pregunta:</p>
    <?php if ($error == true) { ?><span class="error">Debe responder en forma correcta para recibir la nueva contraseña.</span><?php } ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form00">
		<ul id="UPDiv">
			<li>
        <label for="userLogin">Pregunta</label>
				<label for="userLogin"><div class="rhs"><?php echo getSecurityQuestion($securityUser); ?></div></label>
			</li>
			<li><label for="userLogin">Respuesta</label>
			<div class="rhs"><input type="text" name="answer" id="answer" value="" maxlength="255"></div>
			</li>
			<li id="submit">
        <input type="hidden" name="subStep" value="2" />
        <input type="hidden" name="userID" value="<?php echo $securityUser; ?>" />
        <br />
        <div class="rhs">
						<div>
						        <div id="button00" class="android00-btn">&nbsp; </div>
						          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Siguiente">
						 </div>
        </div>
        <div class="clear"></div>
			</li>
		</ul>
    </form>

	 <?php break; case 'userNotFound': ?>

    <p>Lo sentimos pero el usuario o la dirección de correo que escribió no son correctos.<br /><br />
    <a href="?">click aquí</a> para intentar nuevamente.</p>
    <?php break; case 'successPage': ?>
	
    <p>Un correo electrónico le ha sido enviado con las instrucciones para crear una nueva contraseña<br /><br /><a href="../index.php">Regresar</a> a la página de login. </p>

    <br>
    <div class="message"><?php echo $passwordMessage;?></div>

    <?php break; case 'recoverForm': ?>

    <p>Bienvenido, <?php echo getUserName($securityUser=='' ? $_POST['userID'] : $securityUser); ?>.</p>
    <p>En el campo debajo, inserte una nueva contraseña.</p>
    <?php if ($error == true) { ?><span class="error">La nueva contraseña debe coincidir y no ser nula.</span><?php } ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="form1">
		<ul id="UPDiv">
			<li>
        <div class="rhs"><label for="password">Nueva&nbsp;contraseña</label><div class="field"><input type="password" class="input" name="pw0" id="pw0" value="" maxlength="20"></div></div>
			</li><li>
        <div class="rhs"><label for="password">Confirmar&nbsp;contraseña</label><div class="field"><input type="password" class="input" name="pw1" id="pw1" value="" maxlength="20"></div></div>
        <input type="hidden" name="subStep" value="3" />
        <input type="hidden" name="userID" value="<?php echo $securityUser=='' ? $_POST['userID'] : $securityUser; ?>" />
        <input type="hidden" name="key" value="<?php echo $_GET['email']=='' ? $_POST['key'] : $_GET['email']; ?>" />
				</li><li id="submit">
				<br>
        <div class="rhs">
						<div>
						        <div id="button1" class="android1-btn">&nbsp; </div>
						          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Siguiente">
						 </div>        
        </div>
        <div class="clear"></div>
				</li>
		</ul>
    </form>

    <?php break; case 'invalidKey': ?>
    <p>El link que Ud. ingreso no es correcto, o bien copió mal la dirección que le enviamos, o el tiempo de vigencia de ese link caduco o actualmente ya realizo el cambio de contraseña<br /><br /><a href="../index.php">Volver</a> al login. </p>
    <?php break; case 'recoverSuccess': ?>
    Contraseña cambiada
	<p>Le felicitamos, Ud. ha cambiado su contraseña de forma exitosa.</p><br /><br />
	<a href="../index.php"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>Volver</a> al login. </p>
    <?php break; case 'speedLimit': ?>
    <h2>ATENCIÓN</h2>
    <p>La respuesta a que dio es incorrecta y supero la cantidad de intentos habilitados, Ud. será bloqueado por los próximos 15 minutos, luego de ello podrá intentar nuevamente.</p><br />
    <br /><a href="../index.php">Volver</a> al login. </p>
    <?php break;
    }
?>

	 </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>

					</div>        
        
        </div>
 
                    <!-- /sl-slider -->
                </div>
                <!-- /slider-wrapper -->
                </div>
            </div>
            </div>
                <nav id="nav-dots" class="nav-dots">

                    <span class="nav-dot-current"></span>
 
                </nav>                
</div>
                
<div class="footer">

    <!-- Codrops top bar -->
    <div class="codrops-top clearfix">
        <div class="right">
            <a href="https://www.mcc.com.uy" target="_blank">MCC Soporte Técnico</a>

                    <span>
                        +598 9626 1570
                    </span>
            <div>
            <img src="../instalar/images/logo.png" height="46" alt="logo">
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>


<script type="text/javascript" src="../instalar/js/jquery.ba-cond.min.js"></script>
<script type="text/javascript" src="../instalar/js/jquery.slitslider.js"></script>
<script type="text/javascript">	
		var Page = (function() {
			var $nav = $('#nav-dots > span'),
				slitslider = $('#slider').slitslider({
					onBeforeChange : function(slide, pos) {
						$nav.removeClass( 'nav-dot-current' );
						$nav.eq( pos ).addClass( 'nav-dot-current' );
					}
				}),
				init = function() {
					initEvents();
					
				},
				initEvents = function() {
					$nav.each( function( i ) {
					
						$(this ).on( 'click', function( event ) {
							
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
	
	
</script>

		
<script type="text/javascript" >
$(function() {
	$('#comprobar').on('click',function(e){
		 e.preventDefault();
             Servidor = $("#DBhostname").val();
             BaseDeDatos = $("#DBname").val();
             Password = $("#DBpassword").val();
             Usuario = $("#DBusername").val();
             //hace la búsqueda
             $("#resultado").html('<div style="position: absolute; width: 50%; margin: 0 auto;"><img  src="images/ajax-loader.gif" /></div>');
             $("#resultado").delay(1000).queue(function(n) {      
                        $.ajax({
                              type: "POST",
                              url: "comprobar.php",
                              data: {BaseDeDatos: BaseDeDatos, Servidor: Servidor, Password : Password, Usuario: Usuario },
                              dataType: "html",
                              error: function(){
                                    alert("error petición ajax");
                              },
                              success: function(data){   
                              	if (data=='0') {
                              		showWarningToast('Datos incorrectos...');
                                    $("#resultado").html('');
                              	}
                              	if (data=='1') {
                              		$("#resultado").html('');
                                    toggleDiv('button');
                                    toggleDiv('comprobar');
                              	}                                                   
                              	if (data=='2') {
                              		showWarningToast('Existe base de datos, se sugiere respaldar&nbsp;');
                              		$("#actualizar").prop('checked', true);
                                    $("#resultado").html('');
                                    toggleDiv('button');
                                    toggleDiv('comprobar');
                              	}                                                   
                                    n();
                              }
                  });
	 		});
	 });
});
$(function() {
	$('#button').on('click',function(e){
		 e.preventDefault();
	     $('#form').submit();
	 });
	 });
$(function() {
	$('#button0').on('click',function(e){
		 e.preventDefault();
	     $('#form0').submit();
	 });
});
$(function() {
	$('#button00').on('click',function(e){
		 e.preventDefault();
	     $('#form00').submit();
	 });
});
$(function() {
	$('#button1').on('click',function(e){
		 e.preventDefault();
	     $('#form1').submit();
	 });
});
$(function() {
	$('#button2').on('click',function(e){
		 e.preventDefault();
	     location.href="../../index.php";
	 });
});
function toggleDiv(divId) {
   $("#"+divId).toggle();
}
</script>				
</body></html>

<?php
	ob_flush();
	//$mySQL->close();
?>