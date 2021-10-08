<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
 

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

include ("../conectar.php");

$codncredito=$_GET["codncreditotmp"];
$numlinea=$_GET["numlinea"];
$codservice=$_GET['codservice'];

if($codservice!='') {
		$sel_actualiza="UPDATE service SET service.factura='0' WHERE service.codservice='$codservice'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
}

echo $consulta = "DELETE FROM ncreditolineatmp WHERE codncredito ='".$codncredito."' AND numlinea='".$numlinea."'";
$rs_consulta = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
echo "<script>parent.location.href='frame_lineas.php?codncreditotmp=".$codncredito."';</script>";

?>