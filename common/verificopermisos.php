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
 
require_once __DIR__ . '/../library/conector/consultas.php';
use App\Consultas; 

function verificopermisos($seccion, $tipo, $usuario){
	//return $seccion."---".$tipo."...".$usuario;
	$obj = new Consultas('permisos');
	$obj->Select($tipo);
	$obj->Where('seccion', trim($seccion), '=', 'AND'); 
	$obj->Where('codusuarios', trim($usuario)); 
	$paciente = $obj->Ejecutar();
	//echo $paciente["consulta"];
	if($paciente["numfilas"]>0){
		if($paciente["estado"]=="ok"){
			$pac = $paciente["datos"][0];
			if($pac[$tipo]==1) {
				return 'true';
			} else {
				return 'false';
			}
		}
	}
}

function errorHandler($errno, $errstr, $errfile, $errline) {
	if(strpos($errstr, 'isLoggedIn')>0) {
		echo "<script>window.top.location.href='../index.php'; </script>";
		die();
		}else{
	 
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
				//echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
			case E_STRICT:
				echo("STRICT error $errstr at $errfile:$errline n");
				break;
			case E_WARNING:
				echo "<b>Advertencia</b> [$errno] $errstr<br />\n";
				break;			
			case E_USER_WARNING:
				echo("WARNING error $errstr at $errfile:$errline n");
				break;
	
			case E_ERROR:
			case E_USER_ERROR:
				echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
				echo "  Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				echo "Aborting...<br />\n";
				exit(1);
				break;			
			case E_RECOVERABLE_ERROR:
				exit("FATAL error $errstr at $errfile:$errline n");
	
			default:
				exit("Unknown error at $errfile:$errline n");
		}
	}
	return true;
}

set_error_handler("errorHandler");

?>
