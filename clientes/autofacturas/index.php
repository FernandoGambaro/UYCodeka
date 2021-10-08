<?php
$page_title="Listado de clientes";

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

<div class="row">
    <div class="col-xs-10"></div>
<label class="control-label col-xs-1"><?php echo $moneda1;?>
    <span id="moneda1"></span></label>
    <label class="control-label col-xs-1"><?php echo $moneda2;?>
    <span id="moneda2"></span></label>
</div>

<?php
include_once "../../common/footer.php";
?>