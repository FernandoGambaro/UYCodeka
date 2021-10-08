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
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.php>.
 */
 /*La estructura de l array es: pagina anterioer, pagina sigiente*/
$pagina=array(0=>'mantenimiento_11.php',1=>'mantenimiento_12.php' );
/*La estructura de l array es: titulo pagina anterioer, titulo pagina sigiente*/
$titulo=array( 0=>'3.11 Locales', 1=>'3.12. Embalajes');

/*Puede haber varias secciones en la ayuda y para cada una sus partes partes
0=> Titulo de la sección
1=> Sub titulo
2=> Imagen a mostrar
3=> Sub titulo imagen
4=> Descripción
5=> Notas u observaciones 

*/
$seccion=array( 0=> array(0=>'3.11.1. Alta nuevo Local.',
													      1=>'Esta ventana nos muestra un formulario para completar los datos y dar de alta un nuevo Local',
													      2=>'../../_images/scrAltaLocal.png',
													      3=>'Partes principales de la pantalla alta Local',
													      4=>'<ol class="arabic simple">
													      <ul class="simple">
															<li>Al hacer clic en el boton <em class="guilabel">Guardar</em> el formulario realiza la comprovación sobre los campos obligatorios.</li>
															<li>El boton <em class="guilabel">Limpiar</em> se borran los datos ingresados en el formulario.</li>
													      <li>El boton <em class="guilabel">Cancelar</em> no se guarda ningún cambio</li>
													      </ul>
													      </ol>',
													      5=>'',
													      ),
											1=> array(0=>'3.11.1.a Acciones sobre cada Local.',
													      1=>'En el listado de Locals, a la derecha están estos botones.',
													      2=>'../../_images/scrAltaArticulosTransito0.png',
													      3=>'Modificar, Ver, Dar de baja',
													      4=>'',
													      5=>'',
													      ),
											2=> array(0=>'1.11.1.c. Modificar un ítem del Local.',
													      1=>'Esta ventana nos permite modificar los datos del Local.',
													      2=>'../../_images/scrModificarLocal.png',
													      3=>'Partes principales de la pantalla modificar Local',
													      4=>'',
													      5=>'
													      <div class="admonition note">
													      <p class="first admonition-title">Nota</p>
													      <p><strong>La ventana ver, muestra los datos, pero no se pueden guardar modificaciones</strong></p>
													      <p class="last"></p>
													      </div>',
													      ),
											3=> array(0=>'3.11.1.d. Dar de baja un ítem del Local.',
													      1=>'Esta ventana nos muestra los datos del Locals para verificar que es el que queremos dar de baja.',
													      2=>'../../_images/scrEliminarLocal.png',
													      3=>'',
													      4=>'<ol class="arabic simple">
													      <li>Al hacer clic en el boton <em class="guilabel">Eliminar</em>, da de baja el item del Locals.</li>
													      <li>El boton <em class="guilabel">Salir</em> no se guarda ningún cambio</li>
													      </ol>',
													      5=>'',
													      ),													      													      
);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>1. Partes de la pantallas &mdash; UYCODEKA</title>
    
    <link rel="stylesheet" href="../../_static/fierrodoc.css" type="text/css" />
    <link rel="stylesheet" href="../../_static/pygments.css" type="text/css" />
    
    <script type="text/javascript">
      var DOCUMENTATION_OPTIONS = {
        URL_ROOT:    '../../',
        VERSION:     '1.9.2',
        COLLAPSE_INDEX: false,
        FILE_SUFFIX: '.php',
        HAS_SOURCE:  true
      };
    </script>
    <script type="text/javascript" src="../../_static/jquery.js"></script>
    <script type="text/javascript" src="../../_static/underscore.js"></script>
    <script type="text/javascript" src="../../_static/doctools.js"></script>
    <script type="text/javascript" src="../../_static/sidebar.js"></script>
    <link rel="top" title="UYCODEKA" href="../../index.php" />
    
    <link rel="up" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    <link rel="next" title="2. Realizar una búsqueda" href="<?php echo $pagina[1];?>" />
    <link rel="prev" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Neuton&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Nobile:regular,italic,bold,bolditalic&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<!--[if lte IE 6]>
<link rel="stylesheet" href="../../_static/ie6.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->

  </head>
  <body>
<div class="header">
  <div class="logo">
    <a href="../../index.php">
      <img class="logo" src="../../_static/logo-uycodeka.png" alt="Logo"/>
    </a>
  </div>
</div>

    <div class="related">
      <h3>Navegación</h3>
      <ul>
        <li class="right" style="margin-right: 10px">
          <a href="../../index.php" title="Índice General" accesskey="I">índice</a></li>
        <li class="right" >
          <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>" accesskey="N">siguiente</a> |</li>
        <li class="right" >
          <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" accesskey="P">anterior</a> |</li>
<li><a href="../../index.php">Manual UYCODEKA</a> &raquo;</li>
          <li><a href="<?php echo $pagina[0];?>" accesskey="U"><?php echo $titulo[0];?></a> &raquo;</li> 
      </ul>
    </div>  

    <div class="document">
       <div class="documentwrapper">
        <div class="bodywrapper">
          <div class="body">
<?php 
foreach($seccion as  $key=>$value) {
?>            
  <div class="section" id="index-<?php echo $key;?>">
  <!-- Titulo  págiona-->
  <?php if($seccion[$key][0]!='') { ?>
<h1><?php echo $seccion[$key][0];?><a class="headerlink" href="<?php echo "#".$key;?>" title="Enlazar permanentemente con este título">¶</a></h1>
<?php } 
if($seccion[$key][0]!='') { 
?>
<span class="target" ></span><p><?php echo $seccion[$key][1];?></p>
<?php
if(count($seccion)>1 and $key==0) {
?>
		<div class="toctree-wrapper compound">
<ul>
<?php
	foreach($seccion as  $keyy=>$valor) {
		if($keyy>=1) {
	?>
	<li class="toctree-l1"><a class="reference internal" href="<?php echo "#index-".$keyy;?>"><?php echo $seccion[$keyy][0];?></a></li>
	<?php
		}
	}
	?>
</ul>
</div>
	<?php
}	
?>
<?php } if($seccion[$key][2]!='') { ?>
<div class="figure">
<img alt="<?php echo $seccion[$key][2];?>" src="<?php echo $seccion[$key][2];?>" />
<p class="caption"><?php echo $seccion[$key][3];?></p>
</div>
<?php } ?>
<?php echo $seccion[$key][4];?>
<p>
<?php if($seccion[$key][5]!='') {   
echo $seccion[$key][5];
 } 
 ?>
</div>
<?php
		if($key>=1) {  
		?>
<div  style="text-align: right;">
<a class="reference internal" href="#index-0">Arriba</a>
</div>
<?php

		}
 } ?>
          </div>
        </div>
      </div>
      <div class="sphinxsidebar">
        <div class="sphinxsidebarwrapper">
  <h4>Tema anterior</h4>
  <p class="topless"><a href="<?php echo $pagina[0];?>"  title="Capítulo anterior"><?php echo $titulo[0];?></a></p>
  <h4>Próximo tema</h4>
  <p class="topless"><a href="<?php echo $pagina[1];?>"  title="Próximo capítulo"><?php echo $titulo[1];?></a></p>

        </div>
      </div>
      <div class="clearer"></div>
    </div>
    <div class="related">
      <h3>Navegación</h3>
      <ul>
        <li class="right" style="margin-right: 10px">
          <a href="../../index.php" title="Índice General"
             >índice</a></li>
        <li class="right" >
          <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>">siguiente</a> |</li>
        <li class="right" >
          <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" >anterior</a> |</li>
<li><a href="../../index.php">Manual UYCODEKA</a> &raquo;</li>
          <li><a href="<?php echo $pagina[0];?>" ><?php echo $titulo[0];?></a> &raquo;</li> 
      </ul>
    </div>
    <div class="footer">&copy; Copyright 2017, UYCODEKA </div>
  </body>
</html>