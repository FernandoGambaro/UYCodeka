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
$pagina=array(0=>'mantenimiento_011.php',1=>'mantenimiento_02.php' );
/*La estructura de l array es: titulo pagina anterioer, titulo pagina sigiente*/
$titulo=array( 0=>'3.1.1. Alta, acciones sobre cliente.', 1=>'3.2. Pantalla principal proveedores ');

/*Puede haber varias secciones en la ayuda y para cada una sus partes partes
0=> Titulo de la sección
1=> Sub titulo
2=> Imagen a mostrar
3=> Sub titulo imagen
4=> Descripción
5=> Notas u observaciones 

*/
$seccion=array( 0=> array(0=>'3.1.2. Equipos, Service, Respaldos de cliente',
													      1=>'A la izquierda del listado de cliente nos muestra los siguientes botones',
													      2=>'../../_images/scrEquiposCliente0.png',
													      3=>'Equipos, Service, Respaldos',
													      4=>'',
													      5=>'',
													      ),
												1=> array(0=>'3.1.2.a.1. Equipos del cliente.',
													      1=>'Resumen de datos del cliente y listado de equipos.',
													      2=>'../../_images/scrEquiposCliente.png',
													      3=>'Listado de los equipos del cliente',
													      4=>'<ol class="arabic simple">
													      <li>Al hacer clic en el boton <em class="guilabel">Nuevo equipo</em> nos abre un formulario para registrar los datos del equipo.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Imprimir</em> genera un reporte en PDF con un listado de los equipos del cliente.</li>
													      </ol>',
													      5=>'',
													      ),
												2=> array(0=>'3.1.2.a.2. Nuevo, Modificar',
													      1=>'Esta ventana muestra un formulario para el registro de los datos del equipo del cliente.',
													      2=>'../../_images/scrEquiposClienteModificar.png',
													      3=>'',
													      4=>'',
													      5=>'<div class="admonition note" id="index-2">
													      <p class="first admonition-title">Nota</p>
													      <p><strong>Formulario de registro</strong></p>
													      <p class="last">Es el mismo formulario de registro tanto para dar de alta como para modificar o eliminar un equipo.</p>
													      </div>',
													      ),
												3=> array(0=>'3.1.2.a.3. Imprimir',
													      1=>'',
													      2=>'../../_images/scrEquiposClienteImprimir.png',
													      3=>'',
													      4=>'',
													      5=>'',
													      ),
												4=> array(0=>'3.1.2.b.1. Service.',
													      1=>'Resumen de datos del cliente y listado de equipos.',
													      2=>'../../_images/scrServiceCliente.png',
													      3=>'Listado de los service del cliente',
													      4=>'<ol class="arabic simple">
													      <li>Al hacer clic en el boton <em class="guilabel">Limpiar</em> pone las fechas de búsqueda en los valores predefinidos.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Buscar</em> busca los service según la fecha seleccionada.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Nuevo Service</em> abre una ventana para el registro de nuevo service por equipo.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Nuevo Service Horas</em> abre una ventana para el registro de nuevo service por por hora destinadas.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Imprimir</em> abre una nueva ventana con un reporte en PDF con un listado de service según la fecha seleccionada.</li>
													      </ol>',
													      5=>'<div class="admonition note" id="index-2">
													      <p class="first admonition-title">Nota</p>
													      <p><strong>Estado del service</strong></p>
													      <p class="last">En la columna Estado nos muestra en que está el service (Rojo - Pendiente, Azul- Asignado, Verde - Terminado).</p>
													      </div>',
													      ),	
												5=> array(0=>'3.1.2.b.1. Nuevo Service Equipo.',
													      1=>'',
													      2=>'../../_images/scrServiceNuevoEquipoCliente.png',
													      3=>'Nuevo service sobre un equipo',
													      4=>'<ol class="arabic simple">
													      <li>Al hacer clic en el boton <em class="guilabel">Guardar</em> Guarda los datos ingresados.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Salir</em> Sale sin guardar datos.</li>
													      </ol>',
													      5=>'',
													      ),	
												6=> array(0=>'3.1.2.b.2. Nuevo Service Horas.',
													      1=>'',
													      2=>'../../_images/scrServiceNuevoEquipoHorasCliente.png',
													      3=>'Nuevo service sobre un equipo',
													      4=>'<ol class="arabic simple">
													      <li>Al hacer clic en el boton <em class="guilabel">Guardar</em> Guarda los datos ingresados.</li>
													      <li>Al hacer clic en el boton <em class="guilabel">Salir</em> Sale sin guardar datos.</li>
													      </ol>',
													      5=>'<div class="admonition note" id="index-2">
													      <p class="first admonition-title">Nota</p>
													      <p><strong>Service Equipo vs. Service Horas</strong></p>
													      <p class="last">Las ventanas para el registro de service equipos y service horas son muy similares, se diferencian en que el horas no se asocia a un equipo en particular.</p>
													      </div>',
													      ),	
												7=> array(0=>'3.1.2.b.3. Imprimir.',
													      1=>'',
													      2=>'../../_images/scrServiceImprimirCliente.png',
													      3=>'Reporte de service',
													      4=>'',
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