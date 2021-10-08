<?php 
/* 
   MODULO OPTIMIZAR  Depuracion de la Base de Datos.<br />
   Versión 1.0
   Desarrollado por Antonio Pont  bajo licencia GPL.
 
  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
	
	Autor: 	 Antonio Pont
	
	Fecha Liberación del código: 30/08/2008
	Argentina 
	
	*/
header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache");
?>
<html>
<head>
<title>Optimizar Base Datos </title>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<style>
/* ESTILO DE LA TABLA */
.tabla_optimizar {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: normal;
	color: #666666;
	background-color: #e1e1e1; 
	border: 1px dotted #999999; */
}
/* ESTILO DEL BOTON: MOUSE OUT */
.boton_optimizar_off {
border: solid 1px #999999;
color: #999999;
background:#e1e1e1 ;
padding:5px; 

}
/* ESTILO DEL BOTON: MOUSE OVER */
.boton_optimizar_on {
border: solid 1px #999999;
background: #CCCCFF;
color: #333333;
padding:5px; 

}
</style>
</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
			  <div align="center">
				<div id="tituloForm" class="header">Optimizar el sistema </div>
				<div id="frmBusqueda">
<table width="100%" border="0" cellpadding="5" cellspacing="10" class="tabla_optimizar">
                      <tr>
                        <td><img src="../img/restaurar.png" width="16" height="16"> Esta herramienta permite eliminar los residuos que se han generado con la continua utilizacion del sistema. Esta depuraci&oacute;n otorga grandes beneficios entre los que se encuentran un aumento de la velocidad de procesamiento, asi como una mayor estabilidad de trabajo. La depuraci&oacute;n de la base da datos es uno de los procesos que deben ser tomados en cuenta para aprovechar al maximo la potencialidad del sistema. Para proceder, haga click en el boton &quot;optimizar ahora&quot;.</td>
                      </tr>
                      <tr>
                        <td><div align="center">
 <?php   $mensaje = $_GET['mensaje'];
   if($mensaje=="confirmar"){?>
  <img src="../img/error3.png" width="16" height="16"><strong>La base de datos se ha optimizada correctamente. </strong><a href="index.php" style="text-decoration:none; color:#990000"><strong>[X]</strong></a>
  <?php }if($mensaje=="error"){?>
  <img src="../img/error2.png" width="16" height="16"> <strong>Se ha producido un error en la optimizaci&oacute;n. </strong> <a href="index.php" style="text-decoration:none; color:#990000"><strong>[X]</strong></a>
  <?php }if($mensaje==""){?>
                        </div>
                          <form action="optmizar.php">
                            <div align="center">
                              <input type="submit" 
								value="Optimizar ahora &raquo;" 
								class="boton_optimizar_off" 
								onmouseover="this.className = 'boton_optimizar_on'" 
								onmouseout="this.className ='boton_optimizar_off'">
                            </div>
                          </form>
                            <?php } ?></td>
                      </tr>
                      
                  </table>
				</div>
				</div>
				</div>
			    </div>
</body>
</html>
