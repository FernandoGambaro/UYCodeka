<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../conectar.php");

$codpresupuesto=$_GET["codpresupuestotmp"];
$numlinea=$_GET["numlinea"];

$consulta = "DELETE FROM presulineatmp WHERE codpresupuesto ='".$codpresupuesto."' AND numlinea='".$numlinea."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
echo "<script>parent.location.href='frame_lineas.php?codpresupuestotmp=".$codpresupuesto."';</script>";

?>