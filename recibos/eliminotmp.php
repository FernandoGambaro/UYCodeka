<?php
include ("../conectar.php"); 

$codrecibo=$_POST['codrecibo'];
$codcliente=$_POST['codcliente'];
if($codrecibo!='') {
$sel_borrar = "DELETE FROM recibosfacturatmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$sel_borrar = "DELETE FROM recibospagotmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
}
if($codcliente!='') {
$act_factura="UPDATE facturas SET estado='1' WHERE estado='5' AND codcliente='$codcliente' ";
$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);
}		
?>