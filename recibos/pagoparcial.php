<?php
include ("../conectar.php"); 

$codfactura=$_POST['codfactura'];
$estado=$_POST['estado'];

 $act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura' ";
$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);		
/*
header('Content-Type: application/json');
echo json_encode($act_factura);
exit();
*/
?>