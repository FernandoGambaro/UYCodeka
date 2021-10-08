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


$page_title = "index";
include_once "header.php";

include_once "buscar.php";

$oidestudio = '';
$oidpaciente = '';
$hace = _('Lista equipos biometricos');

logger($UserID, $oidestudio, $oidpaciente, $hace);

?>
<br>
<iframe src="rejilla.php" width="98%" height="300" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="no" onload="setIframeHeight(this.id)" >
    </iframe>
<input type="hidden" value="" id="codbiometric"/>

  
<?php
include_once "../../common/footer.php";
?>