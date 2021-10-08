<?php
// set page headers
$page_title = _('Datos del equipo'); 
include_once "header-rejilla.php";

// isset() is a PHP function used to verify if ID is there or not
$codlicencia = isset($_GET['codlicencia']) ? $_GET['codlicencia'] : $_POST['codlicencia'];

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../../common/verificopermisos.php';   

$mensaje='';

$obj = new Consultas('usuarioslicencia');
$obj->Select();
$obj->Where('codlicencia', $codlicencia);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];
$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve datos del equipo '). $codlicencia;

logger($UserID, $oidestudio, $oidpaciente, $hace);


?>
<style>
.btn,.input-group-addon {
    min-width: 47px;
}
.toggle-on.btn-mini {
    padding-right: 66px;
}
.panel-body{
    height: auto;
}
</style>

<div class="panel panel-default">
        <div class="panel-heading"><?php echo _('Licencia asignada'); ?></div>
        <div class="panel-body">
<div id="exTab3" class="container">	


<div class="tab-content col-xs-12 ">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>

<form class="form-horizontal" action='edit.php' method='post'>

    <input type="hidden" id="codlicencia" value="<?php echo $paciente["codlicencia"];?>" >
    <div class="form-group">
    <div class="row">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Ver/Modificar datos &nbsp;'); ?></legend>
    <input type="hidden" name="DATA[codusuarios]" id="codusuarios" value="<?php echo $paciente["codusuarios"];?>" >

    <div class="col-xs-12">
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Desde'); ?></label>
            <div class="col-xs-4">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control input-sm" size="26" type="text" value="<?php echo implota($paciente["desde"]);?>"  id="desde" readonly  required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>   			
            </div>
    </div>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Hasta'); ?></label>
            <div class="col-xs-4">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input class="form-control input-sm" size="26" type="text" value="<?php echo implota($paciente["hasta"]);?>" id="hasta" readonly  required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>   			
            </div>
    </div>

    </div>
</fieldset>
            </div>
        </div>
    </div>
            <!-- ////////////////////////////////  -->
</div>			
</div><!-- Fin  -->


	<div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-xs-12 mx-auto">
    <div class="text-center">
    <?php 
    if(!$_POST)
    { ?>
    <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();guardolicencia();">
        &nbsp;Guardar</button>
    <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
         <?php echo _('Salir'); ?></button>
    </div>
    </div>
</div>
</form>

<script type="text/javascript">
 	$('.form_date').datetimepicker({
        minView: 2, pickTime: false,
        format: 'dd/mm/yyyy',
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
        forceParse: 0,
    });
</script>

<?php
include_once "../../common/footer.php";
?>