<?php
/**
  UYCODEKA
  Copyright (C) MCC (http://www.mcc.com.uy)
 
  Este programa es software libre: usted puede redistribuirlo y/o
  modificarlo bajo los términos de la Licencia Pública General Affero de GNU
  publicada por la Fundación para el Software Libre, ya sea la versión
  3 de la Licencia, o (a su elección) cualquier versión posterior de la
  misma.
 
  Este programa se distribuye con la esperanza de que sea útil, pero
  SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
  MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
  Consulte los detalles de la Licencia Pública General Affero de GNU para
  obtener una información más detallada.
 
  Debería haber recibido una copia de la Licencia Pública General Affero de GNU
  junto a este programa.
  En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
//Para mostrar página en mantenimiento poner en 1;
$mantenimiento=0;
//Poner la fecha en formato ingles, a partir de esa fecha y hora, tendremos 60 minutos para realizar el mantenimiento
$inicio="2019-09-08 13:10:00";
$mant_msg='Estamos realizando manteniminto, en breve quedará activo';


require_once __DIR__ .'/classes/class_session.php';
require_once __DIR__ .'/common/languages.php';   

require_once __DIR__ .'/library/conector/consultas.php';
use App\Consultas; 



if (!$s = new session()) {
    echo "<h2>"._('Ocurrió un error al iniciar session')."!</h2>";
    echo $s->log;
    exit();
}


function Validate()
{
    global $nombre, $password, $status_msg;
    $ret = true;

    if ($_POST['nombre'] != "") {
        $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($nombre, FILTER_VALIDATE_EMAIL)) {
            $status_msg .= ' <strong>'.$nombre ._('NO válido').'</strong><br>';
            $ret = false;
            $password = "";
        }
    } else {
        $status_msg .= _('Ingrese usuario válido').'<br>';
        $ret = false;
        $password = "";
    }

    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);//strip_tags($_POST['password']);
    if (strlen($password) == 0) {
        $ret = false;
        $password = "";
        $status_msg .= _('Ingrese contraseña.')."<br />";
    }

    return $ret;
}


function Auth()
{
    global $nombre, $password, $status_msg, $UserID, $UserTpo, $UserApe, $UserNom, $UserMail, $UserTra, $ask, $MenuColor;
    
 
    if ($nombre == "" or $password == "") {
        $status_msg .= _('Datos incorrectos')."<br />";
        $nombre = "";
        $password = "";
        return false;
    }

    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_EMAIL);//$_POST['nombre'];
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);//$_POST['password'];
    
    //return false;
    $posicion = strpos($nombre, '@');

    // echo $posicion."<br>";
    // Seguidamente se utiliza ===.  La forma simple de comparacion (==)
    // no funciona como deberia, ya que la posicion de 'a' es el caracter
    // numero 0 (cero)
    if ($posicion === false and $nombre != 'Admin') {
        $status_msg .= _('Datos incorrectos')."<br />";
        $nombre = "";
        $password = "";
        return false;
    } else {
    /**Busco si es usuario en la tabla usuarios*/
    $obj = new Consultas('usuarios');
    $obj->Select("usuario,contrasenia,randomhash,codusuarios,tratamiento,nombre,apellido,ask, email, menucolor");
	$obj->Where('usuario', trim($nombre), 'like', 'AND'); 
	$obj->Where('estado', '0', '=', 'AND'); 
	$obj->Where('borrado', '0'); 
    $paciente = $obj->Ejecutar();
    //var_dump($paciente);
    //echo "<br>--->".$paciente["numfilas"]."<br>";
        if ($paciente["numfilas"] > 0) {

            $r_ok = $paciente["datos"][0];
            $converter = new Encryption;
            if ($r_ok['randomhash'] != '') {
                $encoded = $converter->encode($password . $r_ok['randomhash']);
            } else {
                $encoded = $converter->encode($password);
            }
            //echo $encoded;
            if ($r_ok['usuario'] != $nombre && $r_ok['contrasenia'] != $encoded) {
                $status_msg .= $nombre . _('El usuario o la contraseña que ha ingresado no son correctas');
                $nombre = "";
                $password = "";
                return false;
            } elseif ($r_ok['usuario'] == $nombre && $r_ok['contrasenia'] != $encoded) {
                $status_msg .= $nombre .'<br>' . _('Contraseña ingresada inválida');
                $nombre = "";
                $password = "";
                return false;
            } else {
                $UserID = $r_ok['codusuarios'];
                $UserTra = $r_ok['tratamiento'];
                $UserTpo = $r_ok['tratamiento'];
                $UserNom = $r_ok['nombre'];
                $UserApe = $r_ok['apellido'];
                $MenuColor = $r_ok['menucolor'];
                $ask = $r_ok['ask'];
                $UserMail = $r_ok['email'];
                $nombre = "";
                $password = "";
                ?>
                
            <script type="text/javascript" src="library/js/jquery-3.3.1.min.js"></script>
            <script type="text/javascript">
                sessionStorage.setItem('usuario', "<?php echo $UserNom; ?>");
                //Asignamos el valor al objeto localStorage
                localStorage.setItem('mail', "<?php echo $UserMail; ?>");
                console.log("algo-> <?php echo $UserMail; ?>");
            </script>
            <?php
            return true;
            }			

        } else {
            $status_msg = $nombre . _('El usuario no existe o no esta habilitado');
        }
    }
}



if (!file_exists(".listo.php")) {
    header('Location: /instalar/index.php');
} else {

if($mantenimiento==1){
?>
    <div style="position:absolute; top: 0px; width:100%; text-align:center; bottom:0px; padding: 0px 0px 0px 0px;">
    <iframe src="/mantenimiento/index.php?inicio='<?php echo $inicio;?>&msg=<?php echo $mant_msg;?>" id="principal" title="mantenimiento" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0"
    scrolling="yes" allowtransparency="true"></iframe>		
    </div>
<?php
die();
} else { 

    $tratamiento = '';
    $status_msg = isset($s->data['msg']) ? $s->data['msg'] : "";

    $act = isset($s->data['act']) ? $s->data['act'] : null;

    if ($act == "logout") {
        @$USERID = $s->data['UserID'];

        $msg = _('Salida normal del sistema');
        //logger($USERID, $msg);
        session_unset();
//session_destroy();

 ?>
<script type="text/javascript" src="library/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    sessionStorage.clear();
    localStorage.clear();
</script>
<?php

        $s->expire();
    } elseif ($act == "timeout") {
        $USERID = $s->data['UserID'];
        $msg = _('Sesión cerrada por tiempo inactividad');
        //logger($USERID, $msg);
        session_unset();
 //session_destroy();
        $s->expire();
    }
//header('Content-Type: text/html; charset=UTF-8'); 
    require(dirname(__FILE__) . '/classes/Encryption.php');

    if (isset($_POST['method'])) {
        session_start();
        $converter = new Encryption;
        $captcha = $converter->encode(filter_var($_POST['captcha'], FILTER_SANITIZE_STRING));

        if ($captcha == $_SESSION['codigo']) {
  /** Formulario remitido */
            if (Validate()) {
                if (Auth()) {
                    $s->data['logged_in'] = true;
                    if (!empty($_SERVER['HTTP_CLIENT_IP']))   /**check ip from share internet*/
                    {
                        $ip = $_SERVER['HTTP_CLIENT_IP'];
                    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   /**to check ip is pass from proxy*/
                    {
                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                    }

                    $serverip = explode('.', $_SERVER['SERVER_ADDR']);
                    $guestip = explode('.', $ip);

                    for($x=1; $x<=255; $x++) {
                    $tmp="192.168.1.".$x;
                    $rango[$tmp]=$tmp;
                    }

                    $extratime = 25000; /** Tiempo duración sesión red remota */
                        if (in_array($ip, $rango) or $ip =="127.0.0.1") {                 
                            if ($serverip[0] == $guestip[0] and $serverip[1] == $guestip[1] and $serverip[2] == $guestip[2]) {
                                $extratime = 1000000; /** Tiempo duración sesión red local */
                            }
                        }
                            $s->data['UserID'] = $UserID;
                            $s->data['IP'] = $ip;
                            $s->data['UserTpo'] = $UserTpo;
                            $s->data['UserTra'] = $UserTra;
                            $s->data['UserNom'] = $UserNom;
                            $s->data['UserApe'] = $UserApe;
                            $s->data['MenuColor'] = $MenuColor;
                            $s->data['language'] = filter_var($_POST['lang'], FILTER_SANITIZE_STRING);//$_POST['lang'];
                            $s->data['paleta'] = 1;
                            $s->data['UserMail'] = $UserMail;
                            $s->data['hora'] = time();
                            $s->data['inactivo'] = 60 * $extratime; /**segundos por la cantidad de minutos*/
                            $s->data['isLoggedIn'] = true;
                            $s->data['timeOut'] = 60 * $extratime;
                            $s->data['loggedAt'] = time();
                            $s->data['version'] = "1.3.8.20";
                            $s->data['ask'] = $ask;
                            $s->save();
                                    
                    ?>
                    <html xmlns="http://www.w3.org/1999/xhtml" lang="es" class=" js no-touch csstransforms3d csstransitions">
                    <head>
                        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
                        <script type="text/javascript">
                            var usuario = '<?php echo $UserNom; ?>';
                            var usermail = '<?php echo $UserMail; ?>';
                            let itemsArray = localStorage.getItem('items') ? JSON.parse(localStorage.getItem('items')) : [];
                            localStorage.setItem('items', JSON.stringify(itemsArray));
                            itemsArray.push(usuario);
                            itemsArray.push(usermail);
                            localStorage.setItem('items', JSON.stringify(itemsArray));
                            setInterval(function () {
                                sessionStorage.setItem('usuario', JSON.stringify(usuario));
                                localStorage.setItem('mail', JSON.stringify(usermail));
                            }, 10);
                            //Asignamos el valor al objeto localStorage
                            function redireccionar() {
                                console.log("algo-> " + usermail);
                                window.location.replace('/menu.php');
                            }
                            setTimeout("redireccionar()", 1);
                        </script>
                    </head>
                    <body></body>
                    </html>
                    <?php

                }
                else{
                    LoginForm($act);
                }
            }else{
                LoginForm($act);
            }
        }else{
            $status_msg = $nombre . _('Captcha no válida');
            LoginForm($act);
        }
    } else {
        $s->expire();
        $nombre = "";
        $password = "";
    }

} 
}
//Aquí comienza el proceso de ingreso
if(!$_POST){
LoginForm($act);
}

function LoginForm($act)
{
    global $status_msg;

    $obj = new Consultas('datos');
    $obj->Select();
	$obj->Where('coddatos', '0'); 
    $row = $obj->Ejecutar();
    $row = $row["datos"][0];

    ?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" class=" js no-touch csstransforms3d csstransitions">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>
        <?php echo $row["nombre"]; ?> / Gestión WEB</title>
    <meta name="description" content="Facturación WEB">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

        <!-- Bootstrap core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="library/estilos/customCSS.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="login/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="login/css/style.css" />
    <link rel="stylesheet" type="text/css" href="login/css/custom.css" />
    <script type="text/javascript" src="login/js/modernizr.custom.79639.js"></script>
    <noscript>
        <link rel="stylesheet" type="text/css" href="login/css/styleNoJS.css" />
    </noscript>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.android-btn').click(function (e) {
                e.preventDefault();
                if (validar()) // Calling validation function.
                {
                    $("#form_id").submit(); // Form submission.
                }
            });//Finaliza trigger

            // Name and Email validation Function.
            function validar() {
                var password = $("#password").val();
                var nombre = $("#nombre").val();
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (password === '' || nombre === '') {

                    $('#msg').text('<?php echo _('Complete los datos'); ?>');

                    return false;
                } else if (!(nombre).match(emailReg)) {
                    $('#msg').text('<?php echo _('Usuario inválido'); ?>');

                    return false;
                } else {
                    return true;
                }
            }

        $(".selectlanguage").on('click', function(event){
            event.stopPropagation();
            event.stopImmediatePropagation();
            $("#lang").val(this.id);
        });


        });


    </script>
<style>
body {
    /*padding-top: 20px;*/
    padding-bottom: 5px;
}

.navbar {
    margin-bottom: 5px;
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



</style>

</head>

<body>

    <div class="container demo-2">

        <header class="clearfix">
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
                <span class="logo_">
                    <img id="logo_" src="library/images/central.png" height="84" alt="logo">
                </span>
                <div class="collapse navbar-collapse" id="navbarsExample09">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <?php 
                            $languages=array('es'=>array('utf8'=>'es_ES.utf8', 'flag'=>'es', 'lang'=>'Español'),
                                             'en'=>array('utf8'=>'en_US.utf8', 'flag'=>'us', 'lang'=>'English')
                            );
                            $lang = isset($_REQUEST['lang'])? $_REQUEST['lang']: 'es';

                            foreach($languages as $key=>$language) : ?>
                            <?php if($lang == $key) :?>
                            <a class="nav-link dropdown-toggle" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
                            <?php endif;?>
                            <?php endforeach;?>                                
                        <div class="dropdown-menu" aria-labelledby="dropdown09">
                            <?php

                            foreach($languages as $key=>$language) : ?>
                            <?php if($lang == $key) :?>
                            <a class="dropdown-item selectlanguage" id="<?=$key;?>" href="?lang=<?=$key;?>"><span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
                            <?php else : ?>
                            <a class="dropdown-item selectlanguage" id="<?=$key;?>" href="?lang=<?=$key;?>"><span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
                            <?php endif;?>
                            <?php endforeach;?>                                

                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>


        <div class="md-overlay"></div>

        <div id="pestanas" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
            <br>

            <div id="slide" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel"
                aria-expanded="true" aria-hidden="false">
                <!-- Log -->
                <div class="cont-right2">

                    <!--[if lte IE 8]>
                <div class="cont-log-ie9">
                    <div class="cont-log9">
                        <h3>Advertencia</h3>
                        <p><?php echo _('Estás usando una Versión de Internet Explorer incompatible con UYCODEKA.'); ?></p>
                        <p><?php echo _('Te invitamos a utilizar un navegador moderno para que puedas utilizar este sitio'); ?>
                            </p>
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

                        <img src="datos_sistema/loadimage.php?id=11&default=1" width="259" alt="logo">

                        <div id="msg" class="errors">
                            <?php echo $status_msg; ?>
                        </div>

                        <form id="form_id" class="form-signin" name="login" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                            <div>
                                <br>
                                <div class="input-group">
                                    <span class="input-group-addon "><i class="icon-user icon-color"></i></span>
                                    <input id="nombre" name="nombre" class="input form-control" tabindex="1"
                                        placeholder="<?php echo _('Usuario'); ?>" accesskey="n" type="text" value="" size="25" autocomplete="off">
                                </div>
                                <div class="input-group mt10">
                                    <span class="input-group-addon "><i class="icon-lock"></i></span>
                                    <input id="password" name="password" class="input form-control" tabindex="2"
                                        placeholder="<?php echo _('Contraseña'); ?>" accesskey="c" type="password" value="" size="25"
                                        autocomplete="off">
                                </div>

                                <img src="common/captcha.php"><!-- Se puede observar dónde se realiza la inclusón del fichero superior. -->
                                <input type="text" name="captcha" autocomplete="off" placeholder="<?php echo _('Código de seguridad');?>" >

                                <input type="hidden" name="method" value="login" />
                                <input type="hidden" name="lang" id="lang" value="<?=$lang;?>" />
                            </div>
                            <div id="button" class="android-btn">
                            </div>
                            <input style="display:none" class="submit" name="OKsubmit" accesskey="l" value="<?php echo _('Ingresar');?>" tabindex="4" type="submit">
                        </form>
                        <span class="link-passwd"><a href="pw/forgotPass.php"><?php echo _('Olvidé mi nombre de usuario/contraseña'); ?></a></span>

                    </div>
                </div>


                <!-- slider -->
                <div id="slider" class="sl-slider-wrapper">

                    <div class="sl-slider" style="width: 1351px; height: 560px;">


                        <div class="sl-slide sl-slide-vertical sl-trans-elems" data-orientation="vertical"
                            data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5"
                            data-slice2-scale="1.5" style="display: block; z-index: 1;">
                            <div class="sl-content-wrapper" style="width: 1351px; height: 560px;">
                                <div class="sl-content">
                                    <div class="sl-slide-inner">
                                        <div class="bg-img bg-img-2"></div>
                                        <div class="blur">
                                            <div id="bg-blur-2" class="bg-blur-2"></div>
                                            <div class="blur-container">
                                                <div class="cont-left">
                                                    <div class="cont-block">
                                                        <div class="cont-funcionarios ml5">
                                                            <h3>
                                                                <?php echo $row["nombre"]; ?><br><?php echo _('Gestión WEB'); ?> </h3>

                                                            <p><?php echo _('Desde cualquier parte del mundo.'); ?>
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

                                    </div>
                                </div>
                            </div>
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
                <a href="http://www.mcc.com.uy" target="_blank">MCC <?php echo _('Soporte Técnico'); ?></a>

                <span>
                    +598 9626 1570
                </span>
                <div>
                    <img src="datos_sistema/loadimage.php?id=11&default=1" height="46" alt="logo">
                </div>
            </div>
        </div>

    </div>


<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" crossorigin="anonymous"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 
    <script type="text/javascript" src="login/js/jquery.ba-cond.min.js"></script>
    <script type="text/javascript" src="login/js/jquery.slitslider.js"></script>
    <script type="text/javascript">

        $(function () {

            var Page = (function () {

                var $nav = $('#nav-dots > span'),
                    slitslider = $('#slider').slitslider({
                        onBeforeChange: function (slide, pos) {

                            $nav.removeClass('nav-dot-current');
                            $nav.eq(pos).addClass('nav-dot-current');

                        }
                    }),

                    init = function () {

                        initEvents();

                    },
                    initEvents = function () {

                        $nav.each(function (i) {

                            $(this).on('click', function (event) {

                                var $dot = $(this);

                                if (!slitslider.isActive()) {

                                    $nav.removeClass('nav-dot-current');
                                    $dot.addClass('nav-dot-current');

                                }

                                slitslider.jump(i + 1);
                                return false;

                            });

                        });

                    };

                return { init: init };

            })();

            Page.init();

        });
    </script>

</body>

</html>
<?php

}
?>