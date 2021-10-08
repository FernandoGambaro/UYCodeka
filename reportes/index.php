<?php
$page_title="Opciones de reportes";

include_once "header.php";

include_once "buscar.php";

?>
<br>

<iframe src="cierremes.php" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="auto" onload="setIframeHeight(this.id)" >
    </iframe>

<?php
include_once "../common/footer.php";
?>