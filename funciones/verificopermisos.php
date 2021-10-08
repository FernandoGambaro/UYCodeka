<?php
function verificopermisos($seccion, $tipo, $usuario) {
	echo $sql="select * from `permisos` where `seccion` = '$seccion' and `codusuarios` = '$usuario'";
	$con=mysqli_query( $conectar, $sql) or die('Error obteniendo permisos');
	while ($res=mysqli_fetch_array($con)) {
		if($res[$tipo]==1) {
			return 'true';
		} else {
			return 'false';
		}
	}

}

?>