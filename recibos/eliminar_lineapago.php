<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../conectar.php");

$codrecibopago=$_GET["codrecibopago"];
$codrecibo=$_GET["codrecibo"];


$consulta = "DELETE FROM recibospagotmp WHERE codrecibopago='".$codrecibopago."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
echo "<script>parent.location.href='frame_pagos.php?codrecibo=".$codrecibo."';</script>";

?>