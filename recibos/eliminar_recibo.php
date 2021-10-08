<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
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

/* Actualizo el estado de las facturas */
$sql="SELECT * FROM recibosfactura WHERE codrecibo='$codrecibo'";
$res=mysqli_query($GLOBALS["___mysqli_ston"], $sql);
$contador=0;
while($contador< mysqli_num_rows($res)) {
	$codfactura=mysqli_result($res,  $contador,  "codfactura");
		$act_factura="UPDATE facturas SET estado='1' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);
				
$contador++;
}
	$act_recibo="DELETE FROM recibosfactura WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_recibo);
	
	

	$act_recibo="DELETE FROM recibospago WHERE codrecibo='$codrecibo'";
	$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_recibo);


?>
<script type="text/javascript">
parent.$('idOfDomElement').colorbox.close();
</script>
</body>
</html>
