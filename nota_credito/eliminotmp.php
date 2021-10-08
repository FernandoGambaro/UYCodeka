<?php
include ("../conectar.php"); 

$codrecibo=$_POST['codrecibo'];

$sel_borrar = "DELETE FROM recibosfacturatmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

$sel_borrar = "DELETE FROM recibospagotmp WHERE codrecibo='$codrecibo'";
$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);

?>