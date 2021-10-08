<?php
$page_title="Listado de familias de artículos";

if(strlen($_GET["codcliente"])>0){ $codcliente=$_GET["codcliente"]; } else { $codcliente='';}

include_once "header.php";

include_once "buscar.php";

if($UserTpo!=100 and $UserTpo!=2 ) {
$codusuario=$UserID;
}else{
    $codusuario='';
}

?>
<br>
<input type="hidden" id="codfamilia" value="">
<iframe src="rejilla.php" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="auto" onload="setIframeHeight(this.id)" class="force-iframe">
    </iframe>


<?php
include_once "../common/footer.php";
?>