<?php
$page_title="Actividad de usuario";
$codusuarios = isset($_GET['codusuarios']) ? $_GET['codusuarios'] : die('ERROR! ID no encontrado!');
$selec = isset($_GET['seleccionados']) ? $_GET['seleccionados'] : '';
//Defino una variable para cargar solo lo necesario en la seccion que corresponde
//siempre antes de cargar el geader.php

include_once "header.php";

include_once "buscar.php";

?>

<br>
    <iframe src="rejilla.php?oidcontacto=<?php echo $codusuarios;?>&seleccionados=<?php echo $selec;?>" 
    width="98%" height="330" id="log_rejilla" frameborder="0" scrolling="no"  >
    <ilayer width="98%" height="330" id="log_rejilla" ></ilayer>
    </iframe>
    <iframe id="log_datos" name="log_datos" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="log_datos" name="log_datos"></ilayer>
    </iframe>

<div class="container">

    <div class="row">
        <div class="col-xs-3">
        <button class='btn btn-outline-secondary left-margin btn-xs' id="planilla">
         F9&nbsp;Imprime registros</button>   
        </div>
 
    </div>
</div>



<?php
include_once "footer.php";
?>