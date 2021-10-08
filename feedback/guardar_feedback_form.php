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

if (!$s = new session()) {
	  echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
	  echo $s->log;
	  exit();
  }

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

require_once '../common/fechas.php';   
require_once '../common/funcionesvarias.php';   

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


//$fechaproxima=explota($_POST['fechaproxima']);
$fechafeedback=isset($_POST["fechafeedback"]) ? explota($_POST['fechafeedback']) : date("Y-m-d") ;
$fechaproxima=isset($_POST["fechaproxima"]) ? explota($_POST['fechaproxima']) : date("Y-m-d") ;

$desempenio=$_POST['desempenio'];
$DATA=@$_POST['DATA'];
$colaborador=$_POST['colaborador'];
$codformulario=$_POST['codformulario'];

$fecha=explota($_POST["fecha"]);
$total=count($desempenio);

$query_insert="INSERT INTO `feedback` (`codfeedback`, `codusuarios`, `colaborador`, `fecha`,`fechaproxima`, `fechafeedback`, `codformulario`, `fila`, `nivel`, `aspectos`, `borrado`) 
VALUES ";


function inserto_Data($matriz, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback){
$sql="";
for($x=$inicio;$x<$total;$x++) {
	if(is_array($matriz[$x] )) {
		
	foreach($matriz[$x] as $key=>$value){
			//echo 'Primer- '.$key.'-valor-'.$value.'<br>';
		if (is_array($value)){  
                        //si es un array sigo recorriendo
                        $sql.= inserto_Data($value, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
		}else{  
			//echo 'Segundo- '.$x.'-valor-'.$value.'<br>';
			//si es un elemento lo muestro
			$nombres=array();
			$valores=array();
			$nombres[] = 'codusuarios';
			$valores[] = $UserID;
			$nombres[] = 'colaborador';
			$valores[] = $colaborador;
			$nombres[] = 'fecha';
			$valores[] = $fecha;
			$nombres[] = 'fechaproxima';
			$valores[] = $fechaproxima;
			$nombres[] = 'fechafeedback';
			$valores[] = $fechafeedback;	 			   
			$nombres[] = 'codformulario';
			$valores[] = $codformulario;	 
			$nombres[] = 'fila';
			$valores[] = ($x+1);	 			   

			switch($value) {
				case 0:
				$nombres[] = 'nivel';
				$valores[] = '1-0-0-0';	 			   
				break;
			case 1:
				$nombres[] = 'nivel';
				$valores[] = '0-1-0-0';	 			   
			break;
			case 2:
				$nombres[] = 'nivel';
				$valores[] = '0-0-1-0';	 			   
			break;
			case 3:
				$nombres[] = 'nivel';
				$valores[] = '0-0-0-1';	 			   
			break;
			}
			$obj = new Consultas('feedback');
			$obj->Insert($nombres, $valores);
			$feedback=$obj->Ejecutar();
			echo $feedback['consulta']."<br>";
		}
	}
	} else {
		$nombres=array();
		$valores=array();
		$nombres[] = 'codusuarios';
		$valores[] = $UserID;
		$nombres[] = 'colaborador';
		$valores[] = $colaborador;
		$nombres[] = 'fecha';
		$valores[] = $fecha;
		$nombres[] = 'fechaproxima';
		$valores[] = $fechaproxima;
		$nombres[] = 'fechafeedback';
		$valores[] = $fechafeedback;	 			   
		$nombres[] = 'codformulario';
		$valores[] = $codformulario;	 
		$nombres[] = 'fila';
		$valores[] = ($x+1);
		$nombres[] = 'nivel';
		$valores[] = '0-0-0-0';		   
		$obj = new Consultas('feedback');
		$obj->Insert($nombres, $valores);
		$feedback=$obj->Ejecutar();
		echo $feedback['consulta']."<br>";
        //$sql.= " (null, '$UserID', '$colaborador', '$fecha', '$fechaproxima', '$fechafeedback',  '$codformulario', '".($x+1)."', '0-0-0-0', '', '0'),";
	}
}
	//return $sql;
}

function inserto_Desempenio($matriz, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback){
$sql="";
for($x=$inicio;$x<$total;$x++) {
	if(@is_array($matriz[$x])) {
	foreach($matriz[$x] as $key=>$value){
			//echo 'Primer- '.$key.'-valor-'.$value.'<br>';
		if (is_array($value)){  
			//si es un array sigo recorriendo
			$sql.= inserto_Desempenio($value, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
		}else{  
			//echo 'Segundo- '.$x.'<br>';
			   //si es un elemento lo muestro
			   $nombres=array();
			   $valores=array();
			   $nombres[] = 'codusuarios';
			   $valores[] = $UserID;
			   $nombres[] = 'colaborador';
			   $valores[] = $colaborador;
			   $nombres[] = 'fecha';
			   $valores[] = $fecha;
			   $nombres[] = 'fechaproxima';
			   $valores[] = $fechaproxima;
			   $nombres[] = 'fechafeedback';
			   $valores[] = $fechafeedback;	 			   
			   $nombres[] = 'codformulario';
			   $valores[] = $codformulario;	 
			   $nombres[] = 'fila';
			   $valores[] = ($x+1);
			   $nombres[] = 'aspectos';
			   $valores[] = $value;		   
			   $obj = new Consultas('feedback');
			   $obj->Insert($nombres, $valores);
			   $feedback=$obj->Ejecutar();
			   echo $feedback['consulta']."<br>";			   
		/*
		if($x<$total-1 ) {
         $sql.= " (null, '$UserID', '$colaborador', '$fecha', '$fechaproxima', '$fechafeedback', '$codformulario', '".($x+1)."',  '', '$value', '0'), ";
        } else { 
         $sql.= " (null, '$UserID', '$colaborador', '$fecha', '$fechaproxima', '$fechafeedback',  '$codformulario', '".($x+1)."',  '', '$value', '0');";
		}
		*/
			//echo 'Key- '.$key.' valor =>'.$value.'<br>';
		}
	}
	}else {
		$nombres=array();
		$valores=array();
		$nombres[] = 'codusuarios';
		$valores[] = $UserID;
		$nombres[] = 'colaborador';
		$valores[] = $colaborador;
		$nombres[] = 'fecha';
		$valores[] = $fecha;
		$nombres[] = 'fechaproxima';
		$valores[] = $fechaproxima;
		$nombres[] = 'fechafeedback';
		$valores[] = $fechafeedback;	 			   
		$nombres[] = 'codformulario';
		$valores[] = $codformulario;	 
		$nombres[] = 'fila';
		$valores[] = ($x+1);
		$obj = new Consultas('feedback');
		$obj->Insert($nombres, $valores);
		$feedback=$obj->Ejecutar();
		echo $feedback['consulta']."<br>";	
		/*
		if($x<$total-1 ) {
         $sql.= " (null, '$UserID', '$colaborador', '$fecha', '$fechaproxima', '$fechafeedback',  '$codformulario', '".($x+1)."',  '', '', '0'),";
        } else { 
         $sql.= " (null, '$UserID', '$colaborador', '$fecha', '$fechaproxima', '$fechafeedback',  '$codformulario', '".($x+1)."',  '', '', '0');";
		}
		*/
	}
	
}
	//return $sql;
}

$query_update="";

function actualizo_Data($matriz, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback){
for($x=$inicio;$x<$total;$x++) {

	foreach($matriz[$x] as $key=>$value){
			//echo 'Primer- '.$key.'-valor-'.$value.'<br>';
		if (is_array($value)){  
                        //si es un array sigo recorriendo
                        actualizo_Data($value, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
		}else{  
			//echo 'Segundo- '.$x.'-valor-'.$value.'<br>';
			   //si es un elemento lo muestro
			   $nombres=array();
			   $valores=array();
			   $nombres[] = 'codusuarios';
			   $valores[] = $UserID;
			   $nombres[] = 'fechaproxima';
			   $valores[] = $fechaproxima;
			   $nombres[] = 'fechafeedback';
			   $valores[] = $fechafeedback;	   
		       switch($value) {
		       	case 0:
				   $nombres[] = 'nivel';
				   $valores[] = '1-0-0-0';
				break;
		       	case 1:
				   $nombres[] = 'nivel';
				   $valores[] = '0-1-0-0';
		       	break;
		       	case 2:
				   $nombres[] = 'nivel';
				   $valores[] = '0-0-1-0';
		       	break;
		       	case 3:
				   $nombres[] = 'nivel';
				   $valores[] = '0-0-0-1';

		       	break;
			   }
			   
			   $obj = new Consultas('feedback');
			   $obj->Update($nombres, $valores);
			   $obj->Where('fecha', $fecha, '=');
			   $obj->Where('colaborador', $colaborador, '=');
			   $obj->Where('codformulario', $codformulario, '=');
			   $obj->Where('fila', ($x+1), '=');
			   $feedback=$obj->Ejecutar();
			   //echo $feedback['consulta']."<br>";

			//echo 'Key- '.$key.' valor =>'.$value.'<br>';
		}
	}
	//echo $sql."<p><br>";
}

}

function actualizo_Desempenio($matriz, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback){
for($x=$inicio;$x<$total;$x++) {
	foreach($matriz[$x] as $key=>$value){
		if (is_array($value)){  
                        //si es un array sigo recorriendo
       actualizo_Desempenio($value, $inicio, $total,$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
		}else{ 
			//echo "<br>".$key."----".$value."*"; 
			$nombres=array();
			$valores=array();
			$nombres[] = 'codusuarios';
			$valores[] = $UserID;
			$nombres[] = 'aspectos';
			$valores[] = $value;			
			$nombres[] = 'fechaproxima';
			$valores[] = $fechaproxima;
			$nombres[] = 'fechafeedback';
			$valores[] = $fechafeedback;

			
			$obj = new Consultas('feedback');
			$obj->Update($nombres, $valores);
			$obj->Where('fecha', $fecha, '=');
			$obj->Where('colaborador', $colaborador, '=');
			$obj->Where('codformulario', $codformulario, '=');
			$obj->Where('fila', ($x+1), '=');
			$feedback=$obj->Ejecutar();
			//echo $feedback['consulta']."<br>";

/*
$sql= " UPDATE `feedback` SET `codusuarios` = '$UserID', `aspectos` = '$value' ,`feedback`.`fechaproxima`='$fechaproxima' 
,`feedback`.`fechafeedback`='$fechafeedback'
WHERE  `feedback`.`fecha`='$fecha' AND `feedback`.`colaborador`=$colaborador AND `feedback`.`codformulario` = $codformulario AND `feedback`.`fila`= '".($x+1)."' ;";
*/
		}
	}
	//echo $sql."<p><br>";	
}

}

$accion=$_POST["accion"];


if ($accion=="alta" and $colaborador!='') {
	$query_insert.=inserto_Data($DATA,0,count($DATA),$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
	$query_insert.=inserto_Desempenio($desempenio,count($DATA),(count($DATA)+count($desempenio)),$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);

/*	echo $query_insert."<br>";

	$obj = new Consultas('feedback');
	$obj->Select($query_insert, 'sql');
	$feedback=$obj->Ejecutar();
*/
	if ($feedback["estado"]=="ok") { $mensaje="El formularios ha sido dada de alta correctamente"; }
}

if($accion=="modificar") {
	actualizo_Data($DATA,0,count($DATA),$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
	actualizo_Desempenio($desempenio,count($DATA),(count($DATA)+count($desempenio)),$codformulario,$UserID,$colaborador,$fecha,$fechaproxima,$fechafeedback);
}


if($accion=="baja") {

	$nombres[] = 'borrado';
	$valores[] = '1';
	

	$obj = new Consultas('feedback');
	$obj->Update($nombres, $valores);
	$obj->Where('fecha', $fecha, '=');
	$obj->Where('colaborador', $colaborador, '=');
	$obj->Where('codformulario', $codformulario, '=');
	$feedback=$obj->Ejecutar();

/*
$sql="UPDATE `feedback` SET `borrado` = '1' WHERE `feedback`.`fecha`='$fecha' AND `feedback`.`colaborador`=$colaborador
 AND `feedback`.`codformulario` = $codformulario";
*/ 
}
//echo $query_insert;

?>
	<!-- script type="text/javascript" >
	parent.$('idOfDomElement').colorbox.close();
	</script -->