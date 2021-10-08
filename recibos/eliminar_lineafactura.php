<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../conectar.php");

$codfactura=$_GET["codfactura"];
$codrecibo=$_GET["codrecibo"];
$estado=$_GET["estado"];

$consulta = "DELETE FROM recibosfacturatmp WHERE codfactura ='".$codfactura."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);

if($estado!='3' and $estado!=4 ) {
$estado=1;
}
 $act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura' ";
$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);		

echo "<script>parent.parent.document.formulario_facturas.submit();</script>
";
echo "<script>parent.location.href='frame_facturas_sel.php?codrecibo=".$codrecibo."';</script>";

?>