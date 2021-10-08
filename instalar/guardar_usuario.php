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
 

$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8');

require('../class/Encryption.php');
$contrasenia='';

$accion =isset($_POST["accion"]) ? $_POST["accion"] : $_GET["accion"];
$codusuarios =isset($_POST["codusuarios"]) ? @$_GET["codusuarios"] : @$_POST["codusuarios"];

//if (!isset($accion)) { $accion=$_GET["accion"]; }
//echo ".".$codusuarios;
$permisos=$_POST['PERMISOS'];

//$codusuarios=$_POST["codusuarios"];
$tratamiento=$_POST["Atratamiento"];
$nombre=$_POST["Anombre"];
$apellido=$_POST["aapellido"];
$estado=$_POST["estado"];
$tipo='';//$_POST["atipo"];
$departamento='';
$telefono=$_POST["atelefono"];
$celular=$_POST["acelular"];
$direccion=$_POST["adireccion"];

$email=$_POST["aemail"];
$usuario=$_POST["Eusuario"];

$contra=$_POST["Acontrasenia"];

$sessionid=isset($_POST["sessionid"]) ? $_POST["sessionid"] : null ;
$secQ=isset($_POST["secQ"]) ? $_POST["secQ"] : null ;
$secA=isset($_POST["AsecA"]) ? $_POST["AsecA"] : null ;

$sector=isset($_POST['sector']) ? implode('-', $_POST['sector']) : '';
if ($sector!=''){
$saveSector=", `sector`='".$sector."'";
} else {
$saveSector='';
}

if ($accion=="alta") {
	if ($contra!=''){
	$converter = new Encryption;
	$contrasenia = $converter->encode($contra);
	}
	$query_operacion="INSERT INTO `usuarios`(`codusuarios`, `tratamiento`, `nombre`, `apellido`, `estado`, `tipo`, `telefono`, `celular`, `direccion`, 
	`departamento`, `email`, `usuario`, `contrasenia`, `sessionid`, `secQ`, `secA`, `sector`, `borrado`) VALUES ('','$tratamiento','$nombre','$apellido','$estado',
	'$tipo','$telefono','$celular','$direccion','$departamento','$email','$usuario','$contrasenia','$sessionid','$secQ','$secA', '$sector', 0)";					
	$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_operacion);
	if ($rs_operacion) { $mensaje="El usuarios ha sido dado de alta correctamente"; }
	$cabecera1="Inicio >> usuarios &gt;&gt; Nuevo usuarios ";
	$cabecera2="INSERTAR usuarios ";
	$sel_maximo="SELECT max(codusuarios) as maximo FROM usuarios";
	$rs_maximo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_maximo);
	$codusuarios=mysqli_result($rs_maximo, 0, "maximo");
}

if ($accion=="modificar") {
	$codusuarios=$_POST["id"];
	if ($contra!=''){
	$converter = new Encryption;
	$contrasenia = $converter->encode($contra);
	$contrasenia="`contrasenia`='$contrasenia',";
	}	
	$queryup="
UPDATE `usuarios` SET `tratamiento`='$tratamiento',`nombre`='$nombre',`apellido`='$apellido',`estado`='$estado',`tipo`='$tipo',
`telefono`='$telefono',`celular`='$celular',`direccion`='$direccion',`departamento`='$departamento',`email`='$email',`usuario`='$usuario',
$contrasenia `sessionid`='$sessionid',`secQ`='$secQ',`secA`='$secA' $saveSector WHERE codusuarios='$codusuarios'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $queryup);
	if ($rs_query) { $mensaje="Los datos del usuarios han sido modificados correctamente"; }
	$cabecera1="Inicio >> usuarios &gt;&gt; Modificar usuarios ";
	$cabecera2="MODIFICAR usuarios ";
}

if ($accion=="baja" and $_GET['codusuarios']!="" ) {
	$codusuarios=$_GET['codusuarios'];
	$query="UPDATE usuarios SET borrado=1 WHERE codusuarios='$codusuarios'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
	if ($rs_query) { $mensaje="El usuarios ha sido eliminado correctamente"; }
	$cabecera1="Inicio >> usuarios &gt;&gt; Eliminar usuarios ";
	$cabecera2="ELIMINAR usuarios ";
}

	if(is_array($permisos) && $permisos!=''){
	$oldtask="";
	$leer=0; $escribir=0; $modificar=0; $eliminar=0;			
	foreach ($permisos as $key )
	{
	$task=explode('_',$key);
		if ($oldtask=='' xor $task[0]==$oldtask) {
			//echo $task[0]." -> ".$task[1]."<br>";
				if ($task[1]=="v") {
				$leer = '1';
				} elseif ($task[1]=="c") {
				$escribir = '1';
				} elseif ($task[1]=="m") {
				$modificar = '1';
				} elseif ($task[1]=="e") {
				$eliminar = '1';
				}
				$oldtask=$task[0];
		} else {
			$controltask[$oldtask]=$oldtask;
			$updatetask[$oldtask]="update `permisos` set `leer`='$leer', `escribir`='$escribir', `modificar`='$modificar', `eliminar`='$eliminar' where `seccion` = '$oldtask' and `codusuarios` ='$codusuarios'";
			$inserttask[$oldtask]="insert into `permisos` (`codpermisos`, `codusuarios`, `seccion`, `leer`, `escribir`, `modificar`, `eliminar`) values ('null', '$codusuarios', '$oldtask', '$leer', '$escribir', '$modificar', '$eliminar')";
			//echo $inserttask[$oldtask]."<br>";
			$leer=0; $escribir=0; $modificar=0; $eliminar=0;			
			//echo $task[0]." -> ".$task[1]."<br>";
				if ($task[1]=="v") {
				$leer = '1';
				} elseif ($task[1]=="c") {
				$escribir = '1';
				} elseif ($task[1]=="m") {
				$modificar = '1';
				} elseif ($task[1]=="e") {
				$eliminar = '1';
				}
			$oldtask=$task[0];
		}
	}
	$controltask[$oldtask]=$oldtask;
	$updatetask[$oldtask]="update `permisos` set `leer`='$leer', `escribir`='$escribir', `modificar`='$modificar',
	 `eliminar`='$eliminar' where `seccion` = '$oldtask' and `codusuarios` ='$codusuarios'";
	$inserttask[$oldtask]="insert into `permisos` (`codpermisos`, `codusuarios`, `seccion`, `leer`, `escribir`, `modificar`, 
	`eliminar`) values ('null', '$codusuarios', '$oldtask', '$leer', '$escribir', '$modificar', '$eliminar')";
	//echo $updatetask[$oldtask]."<br>";
}
	  
		foreach($controltask as $key) {
			mysqli_query($GLOBALS["___mysqli_ston"], "BEGIN");
			//verifico si existe en la tabla
			$sql_existe="select * from `permisos` where `seccion` like '$key' and `codusuarios`=$codusuarios";
			//echo $sql_existe."<br>";
			$res_existe=mysqli_query($GLOBALS["___mysqli_ston"], $sql_existe) ;
			if($existe=mysqli_fetch_array($res_existe)) {
				$res=mysqli_query($GLOBALS["___mysqli_ston"], $updatetask[$key]);
			} else {
				$res=mysqli_query($GLOBALS["___mysqli_ston"], $inserttask[$key]);
			}
			 if ($res == false) {
			  mysqli_query($GLOBALS["___mysqli_ston"], "ROLLBACK");
			 } else {
			  mysqli_query($GLOBALS["___mysqli_ston"], "COMMIT");			
				}
		}
		
$query="SELECT * FROM usuarios WHERE codusuarios='$codusuarios'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);


?>
<html>
<head>

<title>Usuario</title>
</head>
<body >
<script type="text/javascript" >
parent.fin();
</script>
</body></html>