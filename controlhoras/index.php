<?php
$page_title="Listado de horas clientes/proyectos";

$codcliente = isset($_GET['codcliente']) ? $_GET['codcliente'] : "";


include_once "header.php";

include_once "buscar.php";

if($UserTpo!=100 and $UserTpo!=2 ) {
$codusuario=$UserID;
}else{
    $codusuario='';
}

?>
<br>
<input type="hidden" id="codhoras" value="">
<iframe src="../controlhoras/rejilla.php?codcliente=<?php echo $codcliente;?>&codusuario=<?php echo $codusuario;?>" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="no" onload="setIframeHeight(this.id)" class="force-iframe">
    </iframe>


<?php
include_once "../common/footer.php";
?>