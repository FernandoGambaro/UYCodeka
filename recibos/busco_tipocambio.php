<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


	if(isset($_POST['fecha'])) {
		$fecha=explota($_POST['fecha']);
		$query="SELECT * FROM tipocambio WHERE fecha<'$fecha' order by `fecha` DESC";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if(mysqli_num_rows($rs_query)>0){
			echo mysqli_result($rs_query, 0, "valor");
		}
	}
?>