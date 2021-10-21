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

require_once __DIR__ . '/common/funcionesvarias.php';
require_once __DIR__ . '/classes/class_session.php';
require_once __DIR__ . '/common/fechas.php';
require_once __DIR__ . '/common/languages.php';

require_once __DIR__ . '/common/verificopermisos.php';

require_once __DIR__ . '/library/conector/consultas.php';
use App\Consultas;

if (!$s = new session()) {
    echo "<h2>"._('Ocurrió un error al iniciar session')."!</h2>";
    echo $s->log;
    exit();
}


if (strlen($_GET['u']) > 0) {
    //Verifico el el usuario esté intentando acceder desde una red local

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) /**check ip from share internet*/ {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) /**to check ip is pass from proxy*/ {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $serverip = explode('.', $_SERVER['SERVER_ADDR']);
    $guestip = explode('.', $ip);
    for ($x = 1; $x <= 255; $x++) {
        //Ajustar la IP según el valor de la red local
        $tmp = "192.168.1." . $x;
        $rango[$tmp] = $tmp;
    }

    $extratime = 25000; /** Tiempo duración sesión red remota */
    if (in_array($ip, $rango) or $ip == "127.0.0.1") {
        if ($serverip[0] == $guestip[0] and $serverip[1] == $guestip[1] and $serverip[2] == $guestip[2]) {
            $extratime = 1000000; /** Tiempo duración sesión red local */

            $obj = new Consultas('usuarios');
            $obj->Select("usuario,contrasenia,randomhash,codusuarios,tratamiento,nombre,apellido,ask, email, menucolor");
            $obj->Where('codusuarios', $_GET['u'], '=', 'AND');
            $obj->Where('estado', '0', '=', 'AND');
            $obj->Where('borrado', '0');
            $paciente = $obj->Ejecutar();
            $r_ok = $paciente["datos"][0];
            if ($paciente['numfilas'] > 0) {
                $s->data['UserID'] = $r_ok['codusuarios'];
                $s->data['IP'] = $ip;
                $s->data['UserTpo'] = $r_ok['tratamiento'];
                $s->data['UserTra'] = $r_ok['tratamiento'];
                $s->data['UserNom'] = $r_ok['nombre'];
                $s->data['UserApe'] = $r_ok['apellido'];
                $s->data['MenuColor'] = $r_ok['menucolor'];
                $s->data['paleta'] = 1;
                $s->data['UserMail'] = $r_ok['email'];
                $s->data['hora'] = time();
                $s->data['inactivo'] = 60 * $extratime; /**segundos por la cantidad de minutos*/
                $s->data['isLoggedIn'] = true;
                $s->data['timeOut'] = 60 * $extratime;
                $s->data['loggedAt'] = time();
                $s->data['version'] = "1.22.7.19";
                $s->save();
            } else {
                echo "<script>location.href='index.php'; </script>";
            }
        }
    }
}

if ((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn'])) {
    //*user is not logged in*/
    $s->data['msg'] = _('Acceso externo detectado');
    $s->save();
    echo "<script>location.href='index.php'; </script>";
} else {
    $loggedAt = $s->data['loggedAt'];
    $timeOut = $s->data['timeOut'];
    if (isset($loggedAt) && (time() - $loggedAt > $timeOut)) {
        $s->data['act'] = "timeout";
        $s->save();
        //header("Location:../index.php");
        echo "<script>window.top.location.href='index.php'; </script>";
        exit;
    }
    $s->data['loggedAt'] = time();
    $s->save();
}

$UserID = $s->data['UserID'];
$UserNom = $s->data['UserNom'];
$UserApe = $s->data['UserApe'];
$UserTpo = $s->data['UserTpo'];
$MenuColor = $s->data['MenuColor'];
$paleta = $s->data['paleta'];
$ShowName = $UserNom . " " . $UserApe;

$oidestudio = '';
$oidpaciente = '';
$hace = _('Ingreso al sistema');

logger($UserID, $oidestudio, $oidpaciente, $hace);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
		<meta charset="utf-8">
		<title>UYCodeka Menú</title>


<link rel="stylesheet" href="library/bootstrap/bootstrap.min.css">
<script src="library/js/jquery.min.js"></script>

<link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
<script src="library/bootstrap/js/bootstrap.min.js"  crossorigin="anonymous"></script>

<link rel="stylesheet" href="library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="library/toastmessage/message.js" type="text/javascript"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">

<link rel="stylesheet" href="library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="library/toastmessage/message.js" type="text/javascript"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='library/estilos/style-theme.php?col=<?php echo $paleta; ?>' />
<link href="library/estilos/customCSS.css" rel="stylesheet">


<script>

function invertColor(hex) {
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    // invert color components
    var r = (255 - parseInt(hex.slice(0, 2), 16)).toString(16),
        g = (255 - parseInt(hex.slice(2, 4), 16)).toString(16),
        b = (255 - parseInt(hex.slice(4, 6), 16)).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
}

function padZero(str, len) {
    len = len || 2;
    var zeros = new Array(len).join('0');
    return (zeros + str).slice(-len);
}
</script>

<!-- iconos para los botones -->
<link rel="stylesheet" href="library/estilos/font-awesome.min.css">


<?php
/*
$miVariable =  @$_COOKIE["variable"];
$s->data['alto']= floor($miVariable);
$s->save();
 */
//$_SESSION['alto'] = $miVariable;
?>

<script type="text/javascript">
		function setIframeHeight(iframeName) {
		  var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
		  if (iframeEl) {
		  iframeEl.style.height = "auto";
		  var h = alertSize();
		  var new_h = (h-80);
		  document.getElementById("alto").value=new_h;
		  iframeEl.style.height = new_h + "px";

		  }
		}

		function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }

		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}

function opinion() {
	var noteId="ayuda/opinion.php?ask=1&control=0";
	var w='800px';
	var h='90%';
	OpenNote(noteId,w,h, scroll=false);
}

function pass() {
	var noteId="usuarios/cambiopass.php";
	var w='400px';
	var h='200px';
	OpenNote(noteId,w,h, scroll=false);
}
</script>


<script>
 $(document).ready(function () {

$(".nav-item").on("click", function(){ 
	//console.log('pl');
	
	$(".navbar").find(".active").removeClass("active");  $(this).addClass("active");});

});

</script>


<?php
/*
$miVariable = @ $_COOKIE["variable"];
$s->data['alto']= floor($miVariable);
$s->save();
 */
$_SESSION['alto'] = $miVariable;
?>


<link rel="stylesheet" href="library/estilos/jquery.loadingModal.css">
<style >

.navbar {
	min-height: 25px;
}


@media (min-width: 768px){
.navbar-nav>li>a {
    padding-top: 2px;
    padding-bottom: 2px;
}
}
.nav>li>a {
    position: relative;
    display: block;
    padding: 2px 5px;
}

body {
    font-size: 1.3rem;
}

.dropdown-menu {
    font-size: 1.3rem;
    text-align: left;
	min-width: 15rem;
}

.navbar-nav li:hover > ul.dropdown-menu {
    display: block;
}
.dropdown-submenu {
    position:relative;
}
.dropdown-submenu>.dropdown-menu {
    top:0;
	right:100%;
    margin-top:-6px;
}

.navbar {
    position: relative;
    /* min-height: 50px; */
    /* margin-bottom: 20px; */
    border: 0px solid transparent;
}

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  right: 100%;
  margin-left: .1rem;
  margin-right: .1rem;
}

</style>

	</head>
<body onload="setIframeHeight('principal');" onresize="setIframeHeight('principal');">
<input type="hidden" id="MenuColor" value="<?php echo $MenuColor; ?>">
<!--Navbar -->
<nav class="navbar navbar-expand-xl fixed-top navbar-dark bg-dark py-0 py-md-0">
		<a class="navbar-brand" href="#">
			<img src="datos_sistema/loadimage.php?id=11&default=1" height="37"  style="float: left; top:-10px; padding: 1px 1px;">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	<!-- Collapsible content -->
   <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
      <li class="nav-item active">
	  <a class="nav-link" href="central2.php" target="principal"><span class="fa-stack"><i class="fa fa-home fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Inicio'); ?></a>
	  </li>

	  <?php $secciones = array('controlhoras', 'feedback', 'ordendepedido', 'presupuestos');
$habilito = 0;
foreach ($secciones as $valor) {
    if (verificopermisos($valor, 'leer', $UserID) == 'true') {
        $habilito = 1;
        break;
    }
}
if ($habilito == 1) {
    ?>
      <li class="nav-item dropdown">
	  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="fa-stack"><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Trabajos'); ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?php
$leer = verificopermisos('controlhoras', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./controlhoras/index.php" target="principal"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span><?php echo _('Control Horas'); ?></a>
			<?php }
    $leer = verificopermisos('feedback', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./feedback/index.php" target="principal">
				<span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span><?php echo _('Evaluación/Feedback'); ?></a>
				<div class="dropdown-divider"></div>
			<?php }
    $leer = verificopermisos('ordendepedido', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./albaranes_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-gears" aria-hidden="true"></i></span><?php echo _('Orden de pedido'); ?></a>
			<?php }
    $leer = verificopermisos('presupuestos', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./presupuestos_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></span><?php echo _('Presupuestos'); ?></a>
			<?php }?>

		</div>
	  </li>
	  <?php
}
$secciones = array('prog.autofacturas', 'ventas', 'cobrosrápidos', 'cobros', 'compras', 'pagos', 'pagosdgi', 'RecibosdeSueldo');
$habilito = 0;
foreach ($secciones as $valor) {
    if (verificopermisos($valor, 'leer', $UserID) == 'true') {
        $habilito = 1;
        break;
    }
}
if ($habilito == 1) {
    ?>
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="fa-stack"><i class="fa fa-book fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Documentos'); ?>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown2">

				<?php
$leer = verificopermisos('prog.autofacturas', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./clientes/autofacturas/index.php" target="principal"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span><?php echo _('Prog. auto facturas'); ?></a>
				<?php }
    $leer = verificopermisos('ventas', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./facturas_clientes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span><?php echo _('Ventas'); ?></a>
				<?php }
    $leer = verificopermisos('cobrosrápidos', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./cobros/index.php?estado=1" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span><?php echo _('Cobros rápido'); ?></a>
				<?php }
    $leer = verificopermisos('cobros', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./cobros/index.php?estado=2" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span><?php echo _('Recibos cobros'); ?></a>
				<?php }
    $leer = verificopermisos('compras', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./facturas_proveedores/index.php" target="principal"><span class="fa-stack"><i class="fa fa-shopping-cart" aria-hidden="true"></i></span><?php echo _('Compras'); ?></a>
				<?php }
    $leer = verificopermisos('pagos', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./pagos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span><?php echo _('Pagos'); ?></a>
					<div class="dropdown-divider"></div>
				<?php }
    $leer = verificopermisos('pagosdgi', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./pagosdgi/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span><?php echo _('Pagos DGI'); ?></a>
				<?php }

    $leer = verificopermisos('RecibosdeSueldo', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./recibosSueldos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-money" aria-hidden="true"></i></span><?php echo _('Recibos de Sueldo'); ?></a>
				<?php }

    $leer = verificopermisos('RecibosdeSueldo', 'modificar', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
					<a class="dropdown-item" href="./recibosSueldos/data/index.php" target="principal">
					<span class="fa-stack"><i class="fa fa-handshake-o" aria-hidden="true"></i></span><?php echo _('Subir archivos de recibos'); ?></a>
				<?php }?>
			</div>
		</li>
		<?php
}
$leer = verificopermisos('reportes', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
			<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa-stack"><i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Reportes'); ?>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown3">
			<?php
$leer = verificopermisos('reportesvarios', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./reportes/index.php" target="principal"><span class="fa-stack"><i class="fa fa-shopping-basket" aria-hidden="true"></i></span><?php echo _('Varios'); ?></a>
			<?php }
    $leer = verificopermisos('reportesventas', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./ventas/index.php" target="principal"><span class="fa-stack"><i class="fa fa-flag-o" aria-hidden="true"></i></span><?php echo _('Ventas'); ?></a>
				<?php }
    $leer = verificopermisos('reportesabonados', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./abonados/index.php" target="principal"><span class="fa-stack"><i class="fa fa-users" aria-hidden="true"></i></span><?php echo _('Abonados'); ?></a>
				<?php }
    $leer = verificopermisos('reportesarticulos', 'leer', $UserID);
    if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./ventas_articulos/index.php" target="principal"><span class="fa-stack"><i class="fa fa-exchange" aria-hidden="true"></i></i></span><?php echo _('Articulos'); ?></a>
				<?php }?>
			</div>
			</li>
		<?php }?>

	</ul>


	<div id="msganio" style="display:flex; float: left;text-align: left; color: #FFF000; font-size: 15px;padding: 0px 25px;  margin:auto auto;
	 text-align: center; font-weight: bold; z-index: 15;"><?php echo _('UYCodeka'); ?>&nbsp;
	</div>

	<div style="position:inherit; float: left; margin-top:1px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#000; display:flex; top:10px; ">
		<div id="check" style="display:none; ">
		<div style="display:flex; top:-3px; border: 1px solid #0099CC; padding: 1px;
		position: relative;border-radius: 2px; text-align: left; background: #fff;
		box-shadow: inset 1px 1px 1px rgba(0, 0, 0, 0.12); width: 139px; height: 18px;">
			<div id="estado"></div>
			<div id="estadotxt" style="position: relative; top:5px; z-index: 99;"></div>
		</div>
		<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?php echo _('Cancelar'); ?> </button>
		</div>
	</div>
	<ul class="navbar-nav ml-auto ">
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown4" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="fa-stack"><i class="fa fa-cog fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Mantenimiento'); ?>
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown4">

			<a class="dropdown-item" href="#" onclick="pass();"><?php echo _('Cambio contraseña'); ?></a>
				<?php
$leer = verificopermisos('clientes', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./clientes/index.php" target="principal"><?php echo _('Clientes'); ?></a>
				<?php }
$leer = verificopermisos('proveedores', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./proveedores/index.php" target="principal"><?php echo _('Proveedores'); ?></a>
				<?php	}
$leer = verificopermisos('usuarios', 'leer', $UserID);
$leer2 = verificopermisos('usuariossinpermisos', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true" or $leer2 == "true") {?>
				<a class="dropdown-item" href="./usuarios/index.php" target="principal"><?php echo _('Usuarios'); ?></a>
				<?php	}
$leer = verificopermisos('articulosentransito', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./articulos_transito/index.php" target="principal"><?php echo _('Artículos en tránsito'); ?></a>
				<div class="dropdown-divider"></div>
				<?php	}
$leer = verificopermisos('articulos', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./articulos/index.php" target="principal"><?php echo _('Artículos'); ?></a>
				<?php	}
$leer = verificopermisos('familiasarticulos', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./familias/index.php" target="principal"><?php echo _('Familias artículos'); ?></a>
				<?php	}
$leer = verificopermisos('sectoresdelaempresa', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./sectores/index.php" target="principal"><?php echo _('Sectores de la empresa'); ?></a>
				<?php	}
$leer = verificopermisos('datosdelsistema', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./datos_sistema/index.php" target="principal"><?php echo _('Datos del sistema'); ?></a>
				<?php	}
$leer = verificopermisos('equiposhuelladigital', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./usuarios/biometric/index.php" target="principal"><?php echo _('Equipos huella digital'); ?></a>
				<?php	}
$leer = verificopermisos('bancos', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./entidades/index.php" target="principal"><?php echo _('Bancos'); ?></a>
				<?php	}
$leer = verificopermisos('plandecuentas', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./plandecuentas/index.php" target="principal"><?php echo _('Plan de cuentas'); ?></a>
				<div class="dropdown-divider"></div>
				<?php	}
$leer = verificopermisos('locales', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./ubicaciones/index.php" target="principal"><?php echo _('Locales'); ?></a>
				<?php	}
$leer = verificopermisos('formularios', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./formularios/index.php" target="principal"><?php echo _('Formularios'); ?></a>
				<?php	}
$leer = verificopermisos('embalajes', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./embalajes/index.php" target="principal"><?php echo _('Embalajes'); ?></a>
				<?php	}
$leer = verificopermisos('formasdepago', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./formaspago/index.php" target="principal"><?php echo _('Formas de pago'); ?></a>
				<?php	}
$leer = verificopermisos('tipodecambio', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./tipocambio/index.php" target="principal"><?php echo _('Tipo de cambio'); ?></a>
				<?php	}
$leer = verificopermisos('impuestos', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./impuestos/index.php" target="principal"><?php echo _('Impuestos'); ?></a>
				<?php	}
$leer = verificopermisos('monedas', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./monedas/index.php" target="principal"><?php echo _('Monedas'); ?></a>
				<?php	}
$leer = verificopermisos('etiquetas', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<a class="dropdown-item" href="./etiquetas/index.php" target="principal"><?php echo _('Etiquetas'); ?></a>
				<?php	}
$leer = verificopermisos('tareasprogramadas', 'leer', $UserID);
if ($UserTpo == 100 or $leer == "true") {?>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="./cron/index.php" target="principal"><span class="fa-stack"><i class="fa fa-clock-o" aria-hidden="true"></i></span>&nbsp;<?php echo _('Tareas programadas'); ?></a>
				<?php }?>
				<a class="dropdown-item" href="./ayuda/index.php" target="_blank"><?php echo _('Guía de uso'); ?></a>

			</div>
		</li>

		<li class="nav-item">
		<a class="nav-link" href="#" onclick="opinion();"><span class="fa-stack"><i class="fa fa-envelope fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Sugerencias'); ?></a>
		</li>

        <li class="nav-item dropdown hidden-md-down">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		  <span class="fa-stack">
				<?php if ($UserTpo == 100) {?>
				<img src="library/images/fernando.png" class="rounded-circle z-depth-0" alt="avatar image" style="position: relative; top:-5px; height: 35px; box-shadow: none !important; border-radius: 50% !important; border-style: none;">
				<?php } else {?>
					<i class="fa fa-bars" aria-hidden="true"></i>
				<?php }?>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
		  <a class="dropdown-item" href="usuarios/edit.php?codusuarios=<?php echo $UserID; ?>" target="principal">
				<span class="fa-stack"><i class="fa fa-user-circle-o fa-lg" aria-hidden="true"></i>&nbsp;<?php echo _('Datos personales'); ?></span></a>
				<a href="salir.php" class="dropdown-item">
            	<div class="dropdown-divider"></div>
            	<span class="fa-stack"><i class="fa fa-sign-out fa-lg" aria-hidden="true"></i></span>&nbsp;<?php echo _('Salir'); ?>
				</a>

				<a class="nav-item dropdown">
					<?php
$languages = array('es' => array('utf8' => 'es_ES.utf8', 'flag' => 'es', 'lang' => 'Español'),
    'en' => array('utf8' => 'en_US.utf8', 'flag' => 'us', 'lang' => 'English'),
);
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'es';

foreach ($languages as $key => $language): ?>
					<?php if ($lang == $key): ?>
					<a class="nav-link dropdown-toggle dropdown-item" href="#" id="navbarDropdown7" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#000;">
					<span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
					<?php endif;?>
					<?php endforeach;?>


					<div class="dropdown-menu" aria-labelledby="navbarDropdown7">
					<?php
foreach ($languages as $key => $language): ?>
					<?php if ($lang == $key): ?>
					<a class="dropdown-item selectlanguage" id="<?=$key;?>" href="?lang=<?=$key;?>"><span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
					<?php else: ?>
					<a class="dropdown-item selectlanguage" id="<?=$key;?>" href="?lang=<?=$key;?>"><span class="flag-icon flag-icon-<?=$language['flag']?>"> </span><?=$language['lang']?></a>
					<?php endif;?>
					<?php endforeach;?>
					</div>

				</a>
          </div>
        </li>
      </ul>
			</div>
		</li>
	</ul>
	</div>
	</nav>
<!-- FIN Navbar -->



<div style="position:absolute; top: 45px; width:100%; text-align:center; bottom:0px; padding: 0px 0px 0px 0px; z-index:-99;">


<iframe src="central2.php" name="principal" id="principal" title="principal" frameborder="0" width="100%" height="100%" marginheight="0" marginwidth="0"
 scrolling="yes" allowtransparency="true"></iframe>

</div>

	<div class="cd-panel from-right" style="z-index: 9999; display: none;">
		<header class="cd-panel-header">
			<h1><div class="helptext"><?php echo _('Ayuda'); ?></div></h1>
			<a href="#0" class="cd-panel-close"><?php echo _('Close'); ?></a>
		</header>

		<div class="cd-panel-container">
			<div class="cd-panel-content">

   <!-- the player -->
<div class="player-frame-wrapper">

   <iframe id="help" src=""   class="player-frame"   mozallowfullscreen   webkitallowfullscreen    allowfullscreen></iframe>

    <div class="player-frame-wrapper-ratio"></div>
</div>

			</div> <!-- cd-panel-content -->
		</div> <!-- cd-panel-container -->
	</div> <!-- cd-panel -->
<script src="library/js/jquery-2.1.1.js"></script>

<script type="text/javascript" >
	function help(id,texto){
		$( "div.helptext" ).text( texto );
		$("#help").attr("src", "ayuda/ayuda.php?id="+id);
		$('.cd-panel').addClass('is-visible');
	}

jQuery(document).ready(function($){
	//open the lateral panel

	//clode the lateral panel
	$('.cd-panel').on('click', function(event){
		if( $(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close') ) {
			$('.cd-panel').removeClass('is-visible');
			$( "div.helptext" ).text('--');
			event.preventDefault();
			$("#help").attr("src", "ayuda/ayuda.php");
		}
	});
});
</script>


<div style="position: fixed; bottom: 0; left: 0; background:#2A2A2A; width:100%; height:27px; margin: 0 0 0 0;" >
<div style="position: fixed; bottom:4px; left:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff;  padding: 4px 10px 4px 10px;">
<font color="white"> MCC © 2021 <a href="http://www.mcc.com.uy" title="MCC - Soporte Técnico">MCC</a></font>&nbsp;
<?php echo $ip; ?></div>
</div>

<div style="position: fixed; bottom:4px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px; left:0; right: 0;  margin: 0 auto;" align="center">versión <?php echo $s->data['version']; ?></div>


<div id="UserData" align="right" style="position: fixed; bottom:4px; right:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px;">Bienvenido
 <?php echo $ShowName; ?></div>

<input type="hidden" name="alto" id="alto" value=""></input>


</div>

<script>
/* Función para mostrar una barra de estado en la barra de menú*/

   jQuery.ajaxSetup({cache: false});

   var exeDiag;
   var print_id;
   var printer;

    function CallexeDiag(exeDiag,print_id,cod, printer){
		exeDiag=exeDiag;
		print_id=print_id;
		cod = cod;
		printer=printer;

	  jQuery.ajax({
	    type: "POST",
	    url: "fpdf/monitoreoNew.php",
	    data: {exeDiag:exeDiag, numDoc:print_id, printer:printer},
	    async: true,
    	 cache: false,
	    success: function(data){
	    	if (data!="Fin") {
	        if (exeDiag==1) {
	        	$("#check").show();
  				$("#cancel").show();
				  //AvisarImpresion("F", cod, 1);
				  showToast("Se inicio la impresión de Factura nº:"+cod, 'info');
	        }
			move();
	        document.getElementById("estadotxt").innerHTML=" "+data;
	        setTimeout(function(){
				exeDiag=exeDiag+1;
				CallexeDiag(exeDiag,print_id,cod, printer)

	        },3500);

	    	} else {
				var $p = $("#estado");
    				$p.stop()
      			.css("background-color","green");
     				$("#check").hide();
     				$("#cancel").hide();
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
	    url: "fpdf/monitoreo.php",
	    data: {exeDiag:exeDiag, numDoc:print_id, printer:printer},
	    success: function(data){
	    	if (data=="Fin") {
      			$("#cancel").toggle();
	        		$("#check").toggle();
//	        	CallexeDiag(0);
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
    var id = setInterval(frame, 50);
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

 <script>
    jQuery.ajaxSetup({cache: false});
		var xx=1;
		var file='';
        progressExcelBar(xx, file);

    function progressExcelBar(xx, file){
    	if (file!='') {
	  jQuery.ajax({
	    type: "POST",
	    url: "excel/monitoreo.php",
	    data: {Estado: xx, File: file} ,
	    success: function(data){
	    	if (data!="Fin") {
	        if (xx==1) {
	        	$("#check").show();
  				$("#cancel").show();
	        }
			movelo();
			document.getElementById("estadotxt").innerHTML=" Generando Excel... ";
	        setTimeout(function(){
	        xx=xx+1;
	            progressExcelBar(xx, file);
	        },3100);

	    	} else {
				var $p = $("#estado");
    				$p.stop()
      			.css("background-color","green");
      			if (xx>1) {
	        	$("#check").hide();
  				$("#cancel").hide();
		        	}
	    	}
	   },
	     error: function() {
	        //alert('fail');
	     }
  	 });
	}
}
function movelo() {
    var elem = document.getElementById("estado");
    var width = 1;
    var id = setInterval(frame, 25);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++;
            elem.style.width = width + '%';
            //document.getElementById("label").innerHTML = width * 1 + '%';
        }
    }
}

</script>
<?php
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
?>

		<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script-->
<script src="library/js/jquery-2.1.1.js"></script>

		<link rel="stylesheet" href="library/colorbox/colorbox.css">
		<style type="text/css">
[data-notify="container"][class*="alert-pastel-"] {
	background-color: rgb(255, 255, 238);
	border-width: 0px;
	border-left: 15px solid rgb(255, 240, 106);
	border-radius: 0px;
	box-shadow: 0px 0px 5px rgba(51, 51, 51, 0.3);
	font-family: 'Old Standard TT', serif;
	letter-spacing: 1px;
}
[data-notify="container"].alert-pastel-info {
	border-left-color: rgb(255, 179, 40);
}
[data-notify="container"].alert-pastel-danger {
	border-left-color: rgb(255, 103, 76);
}
[data-notify="container"][class*="alert-pastel-"] > [data-notify="title"] {
	color: rgb(80, 80, 57);
	display: block;
	font-weight: 70;
	margin-bottom: 15px;
}
[data-notify="container"][class*="alert-pastel-"] > [data-notify="message"] {
	font-weight: 70;
}
		</style>
<link href="library/css/animate.min.css" rel="stylesheet">

		<script src="library/js/jquery.min.js"></script>

<script src="library/bootstrap/js/bootstrap-notify.min.js"></script>

<script type="text/javascript" >
function AvisarImpresion(tipo, num, estado) {
	if (estado==1) {
		if (tipo=="F") {
			var textoTipo="Factura";
		}
		if (tipo=="R") {
			var textoTipo="Recibo";
		}
		$.notify({
			title: textoTipo+' nº: '+num,
			message: '<?php echo _('Impresión iniciada'); ?>',
		},{
			type: 'pastel-warning',
			delay: 5000,
			template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
				'<span data-notify="title">{1}</span>' +
				'<span data-notify="message">{2}</span>' +
			'</div>'
		});
	}
}
function mensaje(msg) {
		$.notify({
			title: msg,
		},{
			type: 'pastel-info',
			delay: 5000,
			template: '<div data-notify="container" class=" alert alert-{0}" role="alert">' +
				'<span data-notify="title">{1}</span>' +

			'</div>'
		});
}
</script>

		<link rel="stylesheet" href="../library/colorbox/colorbox.css" />
		<script src="../library/colorbox/jquery.colorbox.js"></script>

<script type="text/javascript" >

function OpenNote(noteId,w,h, scroll=false){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: scroll,
	    });
}
</script>

<script src="library/js/jquery.loadingModal.js"></script>
<script>
    function showModal(action, texto='Procesando') {
    	if (action==1) {
        $('body').loadingModal({text: texto});
        var delay = function(ms){ return new Promise(function(r) { setTimeout(r, ms) }) };
        var time = 3000;
        delay(time)
                .then(function() { $('body').loadingModal('animation', 'rotatingPlane').loadingModal('backgroundColor', 'red'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'wave'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'wanderingCubes').loadingModal('backgroundColor', 'green'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'spinner'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'chasingDots').loadingModal('backgroundColor', 'blue'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'threeBounce'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'circle').loadingModal('backgroundColor', 'black'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'cubeGrid'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'fadingCircle').loadingModal('backgroundColor', 'gray'); return delay(time);})
                .then(function() { $('body').loadingModal('animation', 'foldingCube'); return delay(time); } )
                .then(function() { $('body').loadingModal('color', 'black').loadingModal('backgroundColor', 'yellow');  return delay(time); } )
                .then(function() { $('body').loadingModal('hide'); return delay(time); } )
                .then(function() { $('body').loadingModal('destroy') ;} );
    }else {
    	$('body').loadingModal('destroy');
    }
 }

</script>
 </body>
</html>