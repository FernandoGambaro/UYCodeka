<?php
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


	if(isset($_GET['fecha'])) {
		$fecha=explota($_GET['fecha']);
		$query="SELECT * FROM tipocambio WHERE fecha<'$fecha' order by `fecha` DESC";
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if(mysqli_num_rows($rs_query)>0){
			echo mysqli_result($rs_query, 0, "valor");
		}
	}
?>