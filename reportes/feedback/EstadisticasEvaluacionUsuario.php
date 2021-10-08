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
 
session_start();
require_once('../../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}


if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

include ("../../conectar.php");
include ("../../funciones/fechas.php");
date_default_timezone_set('America/Montevideo');

$codusuario=@$_POST['codusuario'];
$codformulario=@$_POST['codformulario'];
$fila=@$_POST['fila'];
$fecha=@$_POST['fecha'];

//$codusuario=1;
//$codformulario=1;
//$fila=1;

$contador=0;
$total=0;
 $pro="[ ";	
 
 				 $sel_resultado="SELECT * FROM feedback WHERE borrado=0 AND codformulario='".$codformulario."' AND  colaborador='".$codusuario."'
 				  AND fila='".$fila."'";
				$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
$rows=mysqli_num_rows($res_resultado);

		while ($contador < mysqli_num_rows($res_resultado)) {
			$nivel=explode("-",mysqli_result($res_resultado, $contador, "nivel"));
					$y=0;
					$Tipo = array(0,	1,2,3);
					
			foreach($Tipo as $i) {		
			if($nivel[$y]!=0) {
					$level=$y;
				}		
				$y++;	
			}
			
   if($level!=0) {		   
			$pro.='["'.implota(mysqli_result($res_resultado, $contador, "fecha")).'",'.($level+1);
   } else {
			$pro.='["'.implota(mysqli_result($res_resultado, $contador, "fecha")).'",0';
   }
   if($contador<($rows-1)) {
   	$pro.='],';
   } else {
   	$pro.=']';
   }
$contador++;
}
$pro.=" ]";
			$arr['codformulario'] = $codformulario;
        	$arr['data']= $pro;
			$arr['fila']= $fila;

        	
	       //$da[] = $arr;
	       //echo "<br>";
//var_dump($arr);

header("Content-Type: application/json");
echo json_encode($arr);
  
?>