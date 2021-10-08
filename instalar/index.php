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
 


//header('Content-Type: text/html; charset=UTF-8'); 

                  $install=isset($_POST['install']) ? $_POST['install'] : '' ;
						if($install=='') {
                  	$bienvenido="BIENVENIDO";
 						} else {
                  	$bienvenido="INSTALANDO";
 	               } 

?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" class=" js no-touch csstransforms3d csstransitions"><head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Facturación WEB</title>
    <meta name="description" content="Facturación WEB">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/custom.css" />
        
		<script type="text/javascript" src="js/modernizr.custom.79639.js"></script>
		<noscript>
			<link rel="stylesheet" type="text/css" href="css/styleNoJS.css" />
		</noscript>
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
   <script type="text/javascript" >
   function fin() {
   	location.href="../index.php";
   }
   </script>
</head>

<body>

<div class="container demo-2">


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
                <h3><?php echo $bienvenido;?></h3>
										<div class="cont-img2 fr">
                                      <div class="img-funcionarios"></div>
                              </div>  
                </div>
            </div>
            
            <!-- slider -->
            <div id="slider" class="sl-slider-wrapper">
                <div class="sl-slider" style="width: 1351px; height: 530px;">
                 
                    <?php
						if($install=='') {
                  	LoginForm('');
 						} elseif($install=='paso0' or $install=='paso00' or $install=='paso000') {
                  	LoginForm($install);
 	               } else {
							Migrate();
						}

function LoginForm($accion) {
global $nombre, $password, $status_msg;

	if (!file_exists( "../configuro.php" ) ) {
		include("../conectar.php");
		$query="SELECT * FROM datos WHERE coddatos='0'";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		$row = mysqli_fetch_array($rs_query);	
		//header( "Location: ./index2.php" );
		//exit();
	}

		$DBname="test";
		$DBpassword="test123";
		if($accion=='') {
                    ?>
							<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-1"></div>
                            <div class="blur">
                                <div id="bg-blur-1" class="bg-blur-1"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">
                                                <h3>UYCODEKA</h3>
                                                <p>El sistema UY-CodeKa es una aplicación WEB para gestionar la facturación y el stock de
                                                 una pequeña o mediana empresa, está basada en codeka.net. 
                                                 Su gran virtud está en la facilidad de uso y en cubrir las necesidades de las 
                                                 PYMES que brindan venta de artículos y service.                                                
                                                </p>
                                                <br>
                                                <p>UY-CodeKa es una aplicación bajo licencia GPL. Está desarrollada sobre entorno Web, lo que la hace ser muy versátil. 
                                                Es independiente del sistema operativo y además permite el trabajo en red. </p>
                                                <p>Las funciones principales del sistema son:
                                                <ul >
															    <li>Gestión de Clientes y proveedores</li>
															    <li>Gestión de Artículos y Familias</li>
															    <li>Gestión de Facturas y Ordenes de Compra de los clientes</li>
															    <li>Gestión de Facturas y Ordenes de Compra de los proveedores</li>
															    <li>Ventas en mostrador, terminal de punto de venta [TPV]</li>
															    <li>Gestión de los cobros y pagos [Tesorería]</li>
															    <li>Reportes de Ventas y Service.</li>
															    <li>Creación y configuración de códigos de barras de los artículos</li>
															    <li>Gestión de copias de seguridad</li>
															    <li>Listados en formato PDF</li>
															    </ul>
															    </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>

							<div class="sl-slide sl-slide-vertical sl-trans-elems" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-2"></div>
                            <div class="blur">
                                <div id="bg-blur-2" class="bg-blur-2"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">
                                                <h3>UYCODEKA</h3>

                                                <p>El funcionamiento a través de entorno Web permite su uso multiplataforma, 
                                                tanto en sistemas operativos Windows como Linux y MAC.
                                                 <br>
El software ha sido desarrollado en lenguaje PHP, HTML, Javascript, jQuery y utilizando como motor de base de datos MySQL.                                                
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>
             
							<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-3"></div>
                            <div class="blur">
                                <div id="bg-blur-3" class="bg-blur-3"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                       <div class="cont-block">
                                       <div class="cont-funcionarios ml5"> 
                                       <h3>Validación de licencia</h3>
                                       <p style="color: #194685;">
															<p><strong>Para poder utilizar el sistema UYCODEKA, por favor lea los términos de la licencia a continuación, UYCODEKA está disponible bajo
															 la licencia OSL 3.0, mientras que algunas secciones estan licenciados bajo AFL 3.0 </strong></p>
															<div style="height:250px; border:1px solid #ccc; margin-bottom:8px; padding:5px; background:#fff; 
															overflow: auto; color: #000000;	 overflow-x:hidden; overflow-y:scroll;">
															<?php echo file_get_contents('licencia.php'); ?>
															</div>
														<form id="form0" name="form0" method="post" action="index.php">
																<input type="hidden" value="paso0" name="install" />
																<div align="center">
															<div style="width: 600px; float:left;margin-left:8px">

																<input type="checkbox" id="set_license"
																 name="licence_agrement" value="1" onclick="javascript:toggleDiv('button0');" />
																<label for="set_license"><strong>Acepto los términos y condiciones arriba indicados</strong>
																</label>
															</div>
															        <div id="button0" class="android0-btn" style="display: none;">
															        </div>
															          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Siguiente">
															 </div>
												     </form>                                       
													</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>
                    <!-- /sl-slider -->
<?php 
} elseif($accion=='paso0') {
?>

					<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-3"></div>
                            <div class="blur">
                                <div id="bg-blur-3" class="bg-blur-3"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                       <div class="cont-block">
                                       <div class="cont-funcionarios ml5"> 
                                       <h3>Verificando requerimientos</h3>
                                       <p>&nbsp;</p>
                                       <p style="color: #194685;">
															 <?php 
															if (!extension_loaded('SimpleXML') || !extension_loaded('zip') || PHP_VERSION_ID < 50400
															 || !is_writable('../tmp') || !is_writable('../') || !is_writable('../instalar/myBackups') 
															 || !is_writable('../copias/') || !is_writable('../fotos/') || !is_writable('../reportes/tmp/')) {
																?>
															<p><strong>La instalación no puede continuar</strong></p>
															  <ol>
															    <?php if (!extension_loaded('SimpleXML')): ?>
															    <li>
															        UYCODEKA requiere la <b>Extensión SimpleXML</b> habilitada.
															    </li>
															    <?php endif; ?>
															    <?php if (!extension_loaded('zip')): ?>
															      <li>
															          UYCODEKA requirere la <b>Extensión zip</b> habilitada.
															      </li>
															    <?php endif; ?>
															    <?php if (PHP_VERSION_ID < 50400): ?>
															      <li>
															          UYCODEKA requirere por lo menos PHP 5.4 o posterior.
															      </li>
															    <?php endif; ?>
															        <?php if (!is_writable('../tmp')): ?>
															      <li>
															          UYCODEKA requiere poder escribir en la carpeta /tmp.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>
															        <?php if (!is_writable('../instalar/myBackups')): ?>
															      <li>
															          UYCODEKA requiere poder escribir en la carpeta /instalar/myBackups.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>	
															        <?php if (!is_writable('../copias/')): ?>
															      <li>
															          UYCODEKA requiere poder escribir en la carpeta /copias/.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>	
															        <?php if (!is_writable('../fotos/')): ?>
															      <li>
															          UYCODEKA requiere poder escribir en la carpeta /fotos/.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>	
															        <?php if (!is_writable('../reportes/tmp/')): ?>
															      <li>
															          UYCODEKA requiere poder escribir en la carpeta /reportes/tmp/.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>																	    																    															    														    
																<?php if (!is_writable('../')): ?>
															      <li>
															          Para la instalación, UYCODEKA requiere poder escribir en la carpeta raíz.
															          <i>Consulte con su proveedor.</i>
															      </li>
															    <?php endif; ?>															    
															  </ol>
															<?php
															  } else {													 
															?>
															  <ol>
															    <?php if (extension_loaded('SimpleXML')): ?>
															    <li>
															        <i class="fa fa-check-circle-o" aria-hidden="true"></i><b>&nbsp;Extensión SimpleXML</b> habilitada.
															    </li>
															    <?php endif; ?>
															    <?php if (extension_loaded('zip')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i><b>&nbsp;Extensión zip</b> habilitada.
															      </li>
															    <?php endif; ?>
															    <?php if (PHP_VERSION_ID >= 50400): ?>
															      <li>
															         <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;Encontrado <b>PHP 5.4 o posterior</b>.
															      </li>
															    <?php endif; ?>
															        <?php if (is_writable('../tmp')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;Escritura en la carpeta <b>/tmp</b> habilitada.
															      </li>
															    <?php endif; ?>
															        <?php if (is_writable('../')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;La carpeta <b>raíz</b> es escribible,
															          recuerde luego de finalizada la instalación hacerla de solo lectura.
															      </li>
															    <?php endif; ?>	
															        <?php if (is_writable('../copias/')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;La carpeta <b>copias</b> es escribible.
															      </li>
															    <?php endif; ?>
															        <?php if (is_writable('../fotos/')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;La carpeta <b>fotos</b> es escribible.
															      </li>
															    <?php endif; ?>	
															        <?php if (is_writable('../reportes/tmp/')): ?>
															      <li>
															          <i class="fa fa-check-circle-o" aria-hidden="true"></i>&nbsp;La carpeta <b>reportes/tmp</b> es escribible.
															      </li>
															    <?php endif; ?>																    																    																	    														    
															  </ol>
															  <br>												
														<p><strong>Se ha verificado los requerimientos para el correcto funcionamiento de UYCODEKA, puede continuar</strong></p>
														<br>
														<form id="form00" name="form00" method="post" action="index.php">
															 <div align="right">
															<div>
															 <input type="hidden" value="paso00" name="install" />
															        <div id="button00" class="android00-btn">&nbsp; </div>
															          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Siguiente">
															 </div>
												     </form>                                       
													</p>
<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>
                    <!-- /sl-slider -->

<?php 
} elseif($accion=='paso00') {
?>
							<div class="sl-slide sl-slide-vertical sl-trans-elems" data-orientation="vertical" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="1.5" data-slice2-scale="1.5" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">
                            <div class="bg-img bg-img-3"></div>
                            <div class="blur">
                                <div id="bg-blur-3" class="bg-blur-3"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">   
                                            <h3>INSTALACIÓN</h3>

                                                <p style="color: #194685;">
                     <?php 
                     if(!file_exists("../.listo.php")) {
                     ?>
                     <form action="index.php" method="post" name="form" id="form">
                     <input type="hidden" value="install" name="install" />
							<table border="0" align="center">
							  <tr>
							    <td><table class="fuente8" width="609" border="0" align="center" cellpadding="0" cellspacing="0">
							        <tr> 
							          <td> <table class="fuente8" width="100%" border="0" align="center" cellpadding="0" cellspacing="4">
							              <tr> 
							                <td colspan="2"> <p><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Para 
							                    configurar UYCodeka correctamente, debe tener bien 
							                    configurada la conexi&oacute;n con la base de datos en MySQL.<br />
							                    <br />
							                    </font></p></td>
							              </tr>
							              <tr> 
							                <td width="35%"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">Servidor 
							                  de Base de Datos</font></td>
							                <td width="65%" align="left"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							                  <input class="inputbox" type="text" id="DBhostname" name="DBhostname" value="localhost">
							                  </font></td>
							              </tr>
							              <tr> 
							                <td><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">Usuario 
							                  MySQL</font></td>
							                <td align="left"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							                  <input name="DBusername" type="text" class="inputbox" id="DBusername" value="test">
							                  </font></td>
							              </tr>
							              <tr> 
							                <td><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">Contrase&ntilde;a 
							                  MySQL</font></td>
							                <td align="left"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							                  <input class="inputbox" type="password" name="DBpassword" id="DBpassword" value="<?php echo @$DBpassword; ?>" />
							                  </font></td>
							              </tr>
							              <tr> 
							                <td><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nombre 
							                  de la BD MySQL</font></td>
							                <td align="left"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
							                  <input class="inputbox" type="text" name="DBname" id="DBname" value="<?php echo @$DBname; ?>" />
							                  </font></td>
							              </tr>
							              <tr> 
							                <td height="43" colspan="2" align="left">
							                <div style="width: 600px; float:left;margin-left:8px">
							                <font color="#ffffff"><br />
							                <input  type="checkbox"  value="1" name="actualizar" id="actualizar">
							                <label for="actualizar">Si existe, actualizar&nbsp; </label>
														<br>NOTA: La actualizaci&oacute;n genera una Base de Datos de respaldo, dentro de la carpeta myBackups
							                </font>
							                </div>
							                    </td>
							              </tr>
							              <tr> 
							                <td height="43" colspan="2" align="right"> 
							                <div align="center">
 												<div id="comprobar" class="android20-btn" ></div>
							                <font color="#ffffff">
							                <div id="button" class="android-btn" style="display: none;"></div>
							                <input  style="display:none" class="submit" name="OKsubmit" accesskey="l" value="Siguiente" tabindex="4" type="submit">
							                </font></div></td>
							              </tr>
							            </table></td>
							        </tr>
							      </table>
							</td><td>
							<div id="resultado" style="color:#000000; position: relative; float: left; 
							bottom:63px; z-index: 999; background-color: #FFFFFF;"></div>
							</td>
							  </tr>
							</table>
							</form>    
							                           
							<?php } else { ?>
							<p>UYCODEKA ya se encuentra instalado, para volver a instalar o actualizar refiérase al manual de instalación.</p>
							<div id="button2" class="android2-btn">
        					</div>
							<?php } ?>                 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>
							<?php 	
} elseif($accion=='paso000') {
?>

							<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">                        
                            <div class="bg-img bg-img-3"></div>
                            <div class="blur">
                                <div id="bg-blur-3" class="bg-blur-3"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                       <div class="cont-block">
                                       <div class="cont-funcionarios ml5"> 
                                       <h3>Configurar usuario Administrador</h3>
                                       <p style="color: #194685;">
													</p>
													 <link rel="stylesheet" type="text/css" href="css/reset-this.css" />
		
													<div  class="reset-this">
					<iframe  src="nuevo_usuarios.php" width="95%" frameborder="1" style="height:350px; 
					 margin-bottom:8px; padding:5px; background:#fff; color: #000000; overflow-y: scroll; " scrolling="auto" >

					</iframe>		
													</div>
													
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cont-right"></div>
                                </div>
                            </div>
                        </div></div></div>
                    </div>
                    <!-- /sl-slider -->
<?php 

}


} 

function crear_db($DBname, $sqlfile='uycodeka.sql') {
	((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $DBname));
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen("./".$sqlfile, "r"), filesize("./".$sqlfile));
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);
	$errors = array();
	for ($i=0; $i<count($pieces); $i++) {
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($pieces[$i]) && $pieces[$i] != "#") {
			if (!$result = mysqli_query($GLOBALS["___mysqli_ston"], $pieces[$i])) {
				$errors[] = array ( ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)), $pieces[$i] );
			}
		}
	}
}

function split_sql($sql) {
	$sql = trim($sql);
	$sql = @ereg_replace("\n#[^\n]*\n", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;
	for($i=0; $i<strlen($sql)-1; $i++) {
		if($sql[$i] == ";" && !$in_string) {
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}
		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}
	if(!empty($sql)) {
		$ret[] = $sql;
	}
	return($ret);
}

function update_db($DBhostname,$DBusername,$DBpassword,$DBname) {
	/*Primero realizo respaldo*/
$mensaje='';
$dir  = dirname(__file__)."/myBackups"; // directory files
$name = 'backup-'.time(); // name sql backup
$DBnameFile=$dir .'/'.$name;
$cmd = "(mysqldump --skip-opt --no-create-db --complete-insert --insert-ignore  --hex-blob --routines --lock-tables --no-create-info --skip-add-drop-table --log-error={$dir}/mysqldump_error.log -h {$DBhostname} -u {$DBusername} -p{$DBpassword} {$DBname} > {$DBnameFile}.sql) 2>&1";
$arr_out = array();
unset($return);
exec($cmd, $arr_out, $return);
if($return !== 0) {
    $mensaje.= "mysqldump for {$DBhostname} : {$DBname} failed with a return code of {$return}\n\n";
    $mensaje.= "Error message was:\n";
    $file = escapeshellarg("mysqldump_error.log");
    $message = `tail -n 1 $file`;
    $mensaje.= "- $message\n\n";
}

	$enlace = ($GLOBALS["___mysqli_ston"] = mysqli_connect($DBhostname, $DBusername, $DBpassword));
	if (!$enlace) {
	    die('No pudo conectarse: ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	/*Elimino la base de datos antigua*/
	$sql = 'DROP DATABASE '.$DBname;
	if (mysqli_query( $enlace, $sql)) {
	    $mensaje="La base de datos ".$DBname." fue eliminada con &eacute;xito\n<br>";
	} else {
	   $mensaje='Error al eliminar la base de datos: ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . "\n<br>";
	}

	$var=$DBname.".*";
	$var2=$DBusername."@".$DBhostname;
	$consulta2="GRANT ALL PRIVILEGES ON $var TO $var2 IDENTIFIED BY $DBpassword WITH GRANT";
	$query2=mysqli_query($GLOBALS["___mysqli_ston"], $consulta2);
	$consulta = "CREATE DATABASE $DBname";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$test = ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false));

	crear_db($DBname, $sqlfile='uycodeka.sql');

 	/*Importo los datos respaldados a la base de datos con la nueva estructura.*/
	if(filesize($DBnameFile.'.sql') > 0.5) {

/*Import DATA*/
$cmd = "(mysql  -f -h {$DBhostname} -u {$DBusername} -p{$DBpassword} {$DBname} < {$DBnameFile}.sql) 2>&1";

$arr_out = array();
unset($return);
exec($cmd, $arr_out, $return);
if($return !== 0) {
    $mensaje= "mysql for {$DBhostname} : {$DBname} failed with a return code of {$return}\n\n";
}

	} else {
		$correcto="no";
		$test="Error al abrir el archivo de respaldo, datos no importados";
		return false;
	}
}

function Migrate() {
include("funciones.php");

$mensaje="";
$test=0;
$prueba=false;
global $correcto;
global $test;

/*Comienzo el proceso de importación*/
$DBhostname = trim($_POST['DBhostname']);
$DBusername = trim($_POST['DBusername']);
$DBpassword = trim($_POST['DBpassword']);
$DBname  	= trim($_POST['DBname']);
$BK=trim($_POST['actualizar']);

$correcto="si";
$existe=false;
if (!($mysql_link = ($GLOBALS["___mysqli_ston"] = mysqli_connect( $DBhostname,  $DBusername,  $DBpassword )))) {
		$mensaje= "El usuario y la clave introducida son incorrectos<br>";
		$correcto="no"; 
}
		
if($DBname == "") {
		$mensaje=$mensaje."El nombre de la base de datos est&aacute; vacio<br>";
		$correcto="no";
}
	
if ($correcto=="si") {
	/*Verifico si existe la base de datos*/
	$mysql_link = @($GLOBALS["___mysqli_ston"] = mysqli_connect( $DBhostname,  $DBusername,  $DBpassword ));
	if (!((bool)mysqli_query( $mysql_link, "USE " . $DBname))) {
	
	$var=$DBname.".*";
	$var2=$DBusername."@".$DBhostname;
	$consulta2="GRANT ALL PRIVILEGES ON $var TO $var2 IDENTIFIED BY $DBpassword WITH GRANT";
	$query2=mysqli_query($GLOBALS["___mysqli_ston"], $consulta2);
	$consulta = "CREATE DATABASE $DBname";
	$query = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$test = ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false));
	
	} elseif($BK==1) {
		/*Verifico si exista la tabla datos */
		$slq_test="SHOW TABLES FROM `".$DBname."` LIKE 'datos'";
		$query_test = mysqli_query($GLOBALS["___mysqli_ston"], $slq_test);
		$test = ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false));
		if($query_test) {
			update_db($DBhostname,$DBusername,$DBpassword,$DBname);
			$correcto="si";
		} else {
			$correcto="no";
		}
	}

	if ($test <> 0 && $test <> 1007) {
		$mensaje= $mensaje."<br>Error creando la base de datos. Error nº: ".$test."<br>";
		$correcto="no";
	}
	if ($correcto=="si" )
	{
		if ( $prueba==true ) { crear_db($DBname,'uycodeka.sql'); } else  { crear_db($DBname,'uycodeka.sql'); }
		if($BK!=1) {
		$mensaje=$mensaje."La instalaci&oacute;n de UYCodeka se ha realizado con &eacute;xito. 
		<br>Ahora hay que configurar el usuario administrador<p>&nbsp;</p>";
		$estado=0;
		} else {
		$mensaje=$mensaje."La instalaci&oacute;n de UYCodeka se ha realizado con &eacute;xito. 
		<br>Para acceder al sistema utilice sus credenciales o bien configure un nuevo usuario aministrador<p>&nbsp;</p>";
		$estado=1;
		}
		$fp = fopen("../configuro.php", "w"); 
		if (!$fp)
			die(" ERROR: No se tiene acceso al archivo de configuraci&oacute;n: configuro.php. Instalaci&oacute;n a medias");

		fputs ($fp, "<?php\r\n"); 
		fputs ($fp, "\$Usuario=\"$DBusername\";\r\n");
		fputs ($fp, "\$Password=\"$DBpassword\";\r\n");
		fputs ($fp, "\$Servidor=\"$DBhostname\";\r\n"); 
		fputs ($fp, "\$BaseDeDatos=\"$DBname\";\r\n");
		fputs ($fp, "?>\r\n"); 

		fclose($fp);
		chmod("../configuro.php",0777);
				$fp = fopen("../.listo.php", "w"); 
		if (!$fp)
			die(" ERROR: No se tiene acceso a archivo de configuraci&oacute;n: configuro.php. Instalaci&oacute;n a medias");
		$fecha=date("d-m-Y");
		fputs ($fp, "<?php\r\n"); 
		fputs ($fp, "\$Fecha_Instalacion=\"$fecha\";\r\n");
		fputs ($fp, "?>\r\n"); 
		fclose($fp);
		chmod("../.listo.php",0777);
	}
	else
	{
		$mensaje=$mensaje."<br>Error la base de datos ya existe. Error nº: ".$test."<br>";
		$correcto="no";
	}

}

?>
							<div class="sl-slide sl-slide-horizontal" data-orientation="horizontal" data-slice1-rotation="10" data-slice2-rotation="-15" data-slice1-scale="2" data-slice2-scale="2" style="display: block; z-index: 1;">
							<div class="sl-slide-inner">
                        <div class="sl-content-wrapper" style="width: 1351px; height: 560px;"><div class="sl-content">
                            <div class="bg-img bg-img-1"></div>
                            <div class="blur">
                                <div id="bg-blur-1" class="bg-blur-1"></div>
                                <div class="blur-container">
                                    <div class="cont-left">
                                        <div class="cont-block">
                                            <div class="cont-funcionarios ml5">
                                                <h3>UYCODEKA</h3>
                                                <p>
                                                <?php print $mensaje; 
	if ($correcto=="no") { ?> <br>
	    <a href="index.php"><button>Atr&aacute;s</button></a>
	      <?php }
	  if ($correcto=="si") { ?>
	      <br>
   
	    	    <p>

		<form id="form1" name="form1" method="post" action="index.php">
		 <input type="hidden" value="paso000" name="install" />

        <div align="center">
        <?php
         if($estado==1) { ?>
        <div id="button2" class="android2-btn">
        </div>  &nbsp;&nbsp;
        <?php } ?>      
        <div id="button1" class="android1-btn">
        </div>
          <input   style="display:none" class="submit"  type="submit" name="Submit" value="Salir">
        </div>
      </form>
	    <?php }?>
	    </p>

<?php
//fin Migrate();
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
                    
                    <!-- /sl-slider -->
                </div>
                <!-- /slider-wrapper -->
                </div>
            </div>
                <nav id="nav-dots" class="nav-dots">
                <?php
                if($install=='') {
                ?>
                    <span class="nav-dot-current"></span>
                    <span class=""></span>
                    <span class=""></span>
                <?php
                 }  ?>
                </nav>
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
            <img src="images/logo.png" height="46" alt="logo">
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../js3/message.js" type="text/javascript"></script>

<?php
if($install=='') {
	?>

		<script type="text/javascript" src="js/jquery.ba-cond.min.js"></script>
		<script type="text/javascript" src="js/jquery.slitslider.js"></script>
		<script type="text/javascript">	
			$(function() {
			
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
		<?php } ?>
		
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
	     location.href="../index.php";
	 });
});
function toggleDiv(divId) {
   $("#"+divId).toggle();
}
</script>				
</body></html>