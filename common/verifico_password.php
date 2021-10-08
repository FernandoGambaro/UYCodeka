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
 
include ("../conectar.php");
require_once('../class/Encryption.php');

$estado='0';

if(isset($_POST['password']) ) {
		/*Busco si es usuario adminstrador y la contraseña es válida*/	
	    $str =  htmlspecialchars($_POST['password']);
	    $converter = new Encryption;
	    $encoded = $converter->encode($str );
 		$c_usuario = "SELECT * FROM `usuarios` WHERE  `estado`=0 AND `tratamiento`=2";
	    $r_usuario = mysqli_query($GLOBALS["___mysqli_ston"], $c_usuario) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	    if(mysqli_num_rows($r_usuario)>0) {
 			$contador=0;
		   while ($contador < mysqli_num_rows($r_usuario)) {
		   	if(mysqli_result($r_usuario, $contador, "contrasenia")==$encoded) {
		   		$estado= '1';
		   		break;
		   	} 
		   $contador++;	
		   } 	    	 
	} 
} 
echo $estado;
?>