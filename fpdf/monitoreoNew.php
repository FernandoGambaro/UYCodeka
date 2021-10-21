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
 

if(isset($_POST['exeDiag'])) {
$numDoc=$_POST['numDoc'];
$printer=$_POST['printer'];

$output = shell_exec('lpstat -l -o '.$printer);

	if ($output!='' and $_POST['exeDiag']>0 ) {
		echo "imprimiendo ";
	} elseif($output!='' and $_POST['exeDiag']==0) {
		shell_exec('cancel '.$printer.'-'.$numDoc);
		echo "cancelando impresión";
	} else {
		if($_POST['exeDiag']==0) {
			shell_exec('cancel  '.$printer.'-'.$numDoc);
		}
		echo "Fin";
	}
}

?>