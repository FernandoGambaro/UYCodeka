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
 
include ("../conectar.php");

$sql_datos="SELECT servidorfactura,impresorafactura FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$server=mysqli_result($rs_datos, 0, "servidorfactura");
$printer=mysqli_result($rs_datos, 0, "impresorafactura");

if(isset($_POST['execGpsDiag'])) {
$numDoc=$_POST['numDoc'];
	
$output = shell_exec('lpstat -l -o '.$printer);

	if ($output!='' and $_POST['execGpsDiag']>0 ) {
		echo "imprimiendo ";
	} elseif($output!='' and $_POST['execGpsDiag']==0) {
		shell_exec('cancel '.$printer.'-'.$numDoc);
		echo "cancelando impresión";
	} else {
		if($_POST['execGpsDiag']==0) {
			shell_exec('cancel  '.$printer.'-'.$numDoc);
		}
		echo "Fin";
	}
}

?>