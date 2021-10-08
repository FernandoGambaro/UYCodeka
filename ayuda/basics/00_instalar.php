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
 /*La estructura de l array es: pagina anterior, pagina sigiente*/
$pagina=array(0=>'../index.php',1=>'install/instalar_01.php' );
/*La estructura de l array es: titulo pagina anterioer, titulo pagina sigiente*/
$titulo=array( 0=>'Manual UYCODEKA', 1=>'0.1. Prepara entorno local Linux');

/*Puede haber varias secciones en la ayuda y para cada una sus partes partes
0=> Titulo de la sección
1=> Sub titulo
2=> Imagen a mostrar
3=> Sub titulo imagen
4=> Descripción
5=> Notas u observaciones 

*/
$seccion=array( 0=> array(0=>'0.0 Instalación.',
													      1=>'',
													      2=>'',
													      3=>'',
													      4=>'
													      <div class="toctree-wrapper compound">
<div class="wiki-content">
        <p>La instalación de UYCODEKA se puede hacer de varias formas, en un hosting contratado, 
        en su PC local o en un PC de la red (en este caso sería un servidor), para ello 
        primero tiene que verificar que cuenta con el entorno adecuado, un servidor web Apache, el intérprete de lenguaje PHP, 
         el servidor de base de datos MySQL y la herramienta phpMyAdmin.
         </p><p>
          Esto se conoce como AMP: Apache + MySQL + PHP.<br> 
          Existe para muchos sistemas operativos; de ahí que se añada otra letra al acrónimo: 
          WAMP (Windows + Apache + MySQL + PHP), MAMP (Mac OS X + ...) y LAMP (Linux + ...).</p>
          
          <h2>Cómo elegir un paquete AMP</h2>
          <p>Esto requerirá un nivel técnico básico y bastante iniciativa.<br>
          Existen muchos paquetes ya preparados que puede instalar fácilmente, depende de su sistema operativo. 
          Puesto que todos los paquetes son de código abierto, estos instaladores suelen ser gratuitos. 
           <br>Aquí una selección de instaladores AMP gratuitos:</p>
           <ul>
           <li>EasyPHP: <a href="http://www.easyphp.org/">http://www.easyphp.org/</a> (Windows)</li>
           <li>MAMP: <a href="http://www.mamp.info/">http://www.mamp.info/</a> (Mac OS X)</li>
           <li>WampServer: <a href="http://www.wampserver.com/en/">http://www.wampserver.com/en/</a>
             (Windows) -En la sección "Prepara entorno local Windows, incluimos una guía para realizar la instalación-</li>
             <li>XAMPP: <a href="http://www.apachefriends.org/en/xampp.html">
             http://www.apachefriends.org/en/xampp.html</a> (Windows, Mac OS X, Linux, Solaris)</li>
             <li>LAMP:  Linux -En la sección "Prepara entorno local Linux", incluimos una guía para realizar la instalación-</li>
             </ul>
             <p>Eliga el paquete que le resulte más cómodo e instálelo.</p>
             
             </div></div>

',
													      5=>'',
													      ),										      
);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title>Manual &mdash; UYCODEKA</title>
    
    <link rel="stylesheet" href="../_static/fierrodoc.css" type="text/css" />
    <link rel="stylesheet" href="../_static/pygments.css" type="text/css" />
    
    <script type="text/javascript">
      var DOCUMENTATION_OPTIONS = {
        URL_ROOT:    '../',
        VERSION:     '1.9.2',
        COLLAPSE_INDEX: false,
        FILE_SUFFIX: '.php',
        HAS_SOURCE:  true
      };
    </script>
    <script type="text/javascript" src="../_static/jquery.js"></script>
    <script type="text/javascript" src="../_static/underscore.js"></script>
    <script type="text/javascript" src="../_static/doctools.js"></script>
    <script type="text/javascript" src="../_static/sidebar.js"></script>
    <link rel="top" title="UYCODEKA" href="../index.php" />
    
    <link rel="up" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    <link rel="next" title="2. Realizar una búsqueda" href="<?php echo $pagina[1];?>" />
    <link rel="prev" title="3. Búsquedas" href="<?php echo $pagina[0];?>" />
    
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Neuton&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Nobile:regular,italic,bold,bolditalic&amp;subset=latin" type="text/css" media="screen" charset="utf-8" />
<!--[if lte IE 6]>
<link rel="stylesheet" href="../_static/ie6.css" type="text/css" media="screen" charset="utf-8" />
<![endif]-->
<style type="text/css">
.table-wrap {
    margin: 10px 0 0 0;
    overflow-x: auto;
}
.confluenceTable:first-child, .table-wrap:first-child {
    margin-top: 0;
}

.confluenceTable, .table-wrap {
    margin: 10px 0 0 0;
    overflow-x: auto;
}
.confluenceTable {
    border-collapse: collapse;
}

body, p, table, tr, td, .bodytext, .stepfield, .wiki-content p, .panelContent {
    color: #333;
}
table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: grey;
}
tr {
    display: table-row;
    vertical-align: inherit;
    border-color: inherit;
}

.wiki-content table.tablesorter>thead>tr>th.tablesorter-headerSortUp {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAsAAAAKBAMAAACQ3rmwAAAAA3NCS…UAAAAdSURBVAiZY2AgGlTOnA6iNGY2giiWmQZg0VQQAQBOSAQedpgI7AAAAABJRU5ErkJggg==);
}

.wiki-content table.tablesorter>thead>tr>th {
    background: #f0f0f0 center right no-repeat;
    padding-right: 15px;
    cursor: pointer;
}

table.confluenceTable th.confluenceTh, table.confluenceTable th.confluenceTh>p {
    font-weight: bold;
}

table.confluenceTable th.confluenceTh, table.confluenceTable th.confluenceTh>p, table.confluenceTable td.confluenceTd.highlight, table.confluenceTable td.confluenceTd.highlight>p, table.confluenceTable th.confluenceTh.highlight-grey, table.confluenceTable th.confluenceTh.highlight-grey>p, table.confluenceTable td.confluenceTd.highlight-grey, table.confluenceTable td.confluenceTd.highlight-grey>p {
    background-color: #f0f0f0;
}

.confluenceTh, .confluenceTd {
    border: 1px solid #ddd;
    padding: 7px 10px;
    vertical-align: top;
    text-align: left;
}

th {
    font-weight: bold;
    text-align: -internal-center;
}

td, th {
    display: table-cell;
    vertical-align: inherit;
}
</style>
  </head>
  <body>
<div class="header">
  <div class="logo">
    <a href="../index.php">
      <img class="logo" src="../_static/logo-uycodeka.png" alt="Logo"/>
    </a>
  </div>
</div>
    <div class="related">
      <h3>Navegación</h3>
      <ul>
        <li class="right" style="margin-right: 10px">
        <a href="../index.php" title="Índice General" accesskey="I">índice</a></li>
        <li class="right" >
        <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>" accesskey="N">siguiente</a> |</li>
        <li class="right" >
        <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" accesskey="P">anterior</a> |</li>
			<li><a href="../index.php">Manual UYCODEKA</a> &raquo;</li>
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
 } ?>
</div>
<?php } ?>
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
          <a href="../index.php" title="Índice General"
             >índice</a></li>
        <li class="right" >
          <a href="<?php echo $pagina[1];?>" title="<?php echo $titulo[1];?>">siguiente</a> |</li>
        <li class="right" >
          <a href="<?php echo $pagina[0];?>" title="<?php echo $titulo[0];?>" >anterior</a> |</li>
<li><a href="../index.php">Manual UYCODEKA</a> &raquo;</li>
          <li><a href="<?php echo $pagina[0];?>" ><?php echo $titulo[0];?></a> &raquo;</li> 
      </ul>
    </div>
    <div class="footer">&copy; Copyright 2017, UYCODEKA </div>
  </body>
</html>