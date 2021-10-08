<?php
$page_title="Listado de recibos de sueldo";


include_once "header.php";

include_once "buscar.php";

if($UserTpo!=100 and $UserTpo!=2 ) {
$codusuario=$UserID;
}else{
    $codusuario='';
}

?>
<br>
<input type="hidden" id="codrecibo" value="">
<iframe src="rejilla.php?codusuario=<?php echo $codusuario;?>" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="auto" onload="setIframeHeight(this.id)" class="force-iframe">
    </iframe>

<?php
include_once "../common/footer.php";
?>