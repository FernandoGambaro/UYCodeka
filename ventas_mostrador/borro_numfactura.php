<?php
include ("../conectar.php"); 

	if(isset($_GET['codfactura'])) {
		$sel_borrar = "DELETE FROM factulineatmp WHERE codfactura ='".$_GET['codfactura']."' ";
		$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	}

?>