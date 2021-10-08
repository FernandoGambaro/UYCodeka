<?php
include ("../conectar.php"); 
	if(isset($_GET['num'])) {
		$query="SELECT `codfactura` FROM `facturas` WHERE `codfactura` = ".$_GET['num'];
		$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if(mysqli_num_rows($rs_query)>0){
			echo "true";
		} else {
			echo "false";
		}
	}
 
?>