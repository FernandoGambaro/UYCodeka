<?php
$page_title="Listado de compras proveedores";

include_once "header.php";

include_once "buscar.php";

?>
<br>
<input type="hidden" id="codarticulo">
<iframe src="rejilla.php" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" 
frameborder="0" scrolling="auto" onload="setIframeHeight(this.id)" >
    <ilayer width="98%" height="330" id="frame_rejilla" name="frame_rejilla"></ilayer>
    </iframe>
    <iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
    </iframe>


<?php
include_once "../common/footer.php";
?>