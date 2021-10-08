<?php
$page_title="Listado de Evaluaciones";

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
<input type="hidden" id="codhoras" value="">
<iframe src="../feedback/rejilla.php?codusuario=<?php echo $codusuario;?>" width="98%" height="330" id="frame_rejilla" name="frame_rejilla" frameborder="0" scrolling="no" onload="setIframeHeight(this.id)" class="force-iframe">
    </iframe>
    <input type="hidden" id="devo" name="devo" />		
    <input type="hidden" id="codfeedback" name="codfeedback" >		
    <input type="hidden" id="fechaform" name="fechaform">
    <input type="hidden" id="colaboradorform" name="colaboradorform">

<?php
include_once "../common/footer.php";
?>