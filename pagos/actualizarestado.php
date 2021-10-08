<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
</head>
<?php include ("../conectar.php"); ?>
<body>
<?php
	$estado=$_GET["estado"];
	$codfactura=$_GET["codfactura"];
	$codproveedor=$_GET["codproveedor"];
	$act_factura="UPDATE facturasp SET estado='$estado' WHERE codfactura='$codfactura' AND codproveedor='$codproveedor'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);
?>
</body>
</html>
