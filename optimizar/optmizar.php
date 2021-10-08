<?php
/* 
   MODULO OPTIMIZAR  Depuracion de la Base de Datos
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
	 
	Modificaciones  : Grupo CodeKa Mx 
	 
	Fecha Liberación del código: 30/08/2008
	Argentina 
	
	*/
header ("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
header ("Pragma: no-cache");
// Conexcion Base de Datos 
     include ("../conectar.php"); 

// Tablas que serán afectadas por la depuración.
$sql_opt="OPTIMIZE TABLE `albalinea` , `albalineap` , `albalineaptmp` , `albalineatmp` , `albaranes` , `albaranesp` , `albaranesptmp` , `albaranestmp` , `articulos` , `artpro` , `clientes` , `cobros` , `embalajes` , `entidades` , `factulinea` , `factulineap` , `factulineaptmp` , `factulineatmp` , `facturas` , `facturasp` , `facturasptmp` , `facturastmp` , `familias` , `formapago` , `impuestos` , `librodiario` , `pagos` , `proveedores` , `provincias` , `tabbackup` , `ubicaciones`";

// Chequeo de la realización del proceso.
if(mysqli_query($conexion, $sql_opt))

// Redireción en caso de confirmación de proceso.
{header("Location: index.php?mensaje=confirmar");}
else

// Redireción en caso de error.
{header("Location: index.php?mensaje=error");}
?>