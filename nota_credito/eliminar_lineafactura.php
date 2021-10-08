<?php
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

include ("../conectar.php");

$codfactura=$_GET["codfactura"];
$codrecibo=$_GET["codrecibo"];


$consulta = "DELETE FROM recibosfacturatmp WHERE codfactura ='".$codfactura."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);

		$act_factura="UPDATE facturas SET estado='1' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);	

echo "<script>parent.parent.document.formulario_facturas.submit();</script>
";
echo "<script>parent.location.href='frame_facturas_sel.php?codrecibo=".$codrecibo."';</script>";

?>