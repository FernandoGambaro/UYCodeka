<?php
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
?>
<html>
<head>
</head>
<?php include ("../conectar.php"); 
include ("../funciones/fechas.php");
?>
<body>
<?php
	$codrecibo=$_GET["codrecibo"];

	$act_recibo="DELETE FROM recibos WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_recibo);
	
	$act_recibo="DELETE FROM recibosfactura WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_recibo);

	$act_recibo="DELETE FROM recibospago WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_recibo);


?>
</body>
</html>
