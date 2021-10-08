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
require_once __DIR__ .'/../classes/class_session.php';

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

if (!$s = new session()) {
	echo "<h2>Ocurrió un error al iniciar session!</h2>";
	echo $s->log;
	exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	//*user is not logged in*/
	//echo "<script>window.top.location.href='../index.php'; </script>";  
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
	   $s->data['act']="timeout";
		$s->save();  	
		  //header("Location:../index.php");	
		//echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];

$obj = new Consultas('usuarios');

$randomhash = RandomString(24);

$nombres[] = 'randomhash';
$valores[] = $randomhash;

$nombres[] = 'contrasenia';
$converter = new Encryption;
$valores[] = $converter->encode($_POST['NewPassword'].$randomhash);

$obj->Update($nombres, $valores);
$obj->Where('codusuarios', $UserID); 
//var_dump($obj);
$paciente = $obj->Ejecutar();

	if ($paciente['ok'])	{	
   	$s->data['pass']="ok";
    	$s->save(); 
    }
	?>
	<script type="text/javascript" >
	parent.$('idOfDomElement').colorbox.close();
	</script>