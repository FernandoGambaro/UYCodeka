<?php
// set page headers
$page_title = _('Datos del equipo'); 
include_once "header-rejilla.php";

// isset() is a PHP function used to verify if ID is there or not
$codequipo = isset($_GET['codequipo']) ? $_GET['codequipo'] : $_POST['codequipo'];

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../../common/verificopermisos.php';   

$mensaje='';
$obj = new Consultas('equipos');

if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';

    $DATA=$_POST['DATA'];
    $xpos=0;
    foreach ($DATA as $key => $item)
    {
        if (!is_array($DATA[$key])) {
            if($xpos==0){
              $attr = trim($key);
              $valor = trim($item); 
              $xpos++; 
            } else {
                if($item!=''){
                    if(strpos($item, '/')>0){
                        $valores[] = explota($item);
                    } else {
                        $valores[] = $item;
                    }
                    $nombres[] = $key;
                }
                if($key=='codequipo'){
                    $codequipo=$item;
                }
            }
        } else {
            for ( $i=0; $i < count($DATA[$key]); $i++ )
            {
                if($xpos==0){
                    $attr = trim($key);
                    $valor = trim($item); 
                    $xpos++; 
                } else {
                    if ( !empty($DATA[$key][$i]) )  {
                        if($item!=''){
                        $nombres[] = $key;
                            if(strpos($item, '/')>0){
                                $valores[] = explota($item);
                            } else {
                                $valores[] = $item;
                            }
                        }
                    }
                    if($key=='codequipos'){
                        $codequipos=$item;
                    }        
                }
            }
            if($item!='') {
            $nombres[] = $key;
                if(strpos($item, '/')>0){
                    $valores[] = explota($item);
                } else {
                    $valores[] = $item;
                }
            }
        }
    }

    $obj->Update($nombres, $valores);
    $obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    //echo $paciente["consulta"];
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;
 
//        echo "<script>parent.document.getElementById('frame_equipos').contentDocument.location.reload(true);
//        parent.$('idOfDomElement').colorbox.close();</script>";

        $objpaciente = new Consultas('equipos');
        $objpaciente->Select();
        $objpaciente->Where(trim($attr), trim($valor));
        $paciente = $objpaciente->Ejecutar();
        $paciente = $paciente["datos"][0];
        $oidestudio = '';
        $oidpaciente = '';
        $hace = _('Modifica datos del equipo '). $codequipo;
        
        logger($UserID, $oidestudio, $oidpaciente, $hace);
          
          
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }
  

    
}else{
$obj = new Consultas('equipos');
$obj->Select();
$obj->Where('codequipo', $codequipo);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];
$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve datos del equipo '). $codequipo;

logger($UserID, $oidestudio, $oidpaciente, $hace);

}
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
        <div class="panel-heading"><?php echo _('Datos del equipo'); ?></div>
        <div class="panel-body">

<div id="exTab3" class="container">	


<div class="tab-content col-xs-12 ">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>



<form class="form-horizontal" action='edit.php' method='post'>
    <input type="hidden" name="DATA[codequipo]" id="codequipo" value="<?php echo $paciente["codequipo"];?>" >
    <input type="hidden" name="codequipo" value="<?php echo $paciente["codequipo"];?>" >
    <div class="form-group">
    <div class="row">
        
<fieldset class="scheduler-border">
    <div class="row">
        <div class="col-xs-8">

        <div class="row">
            <div class="col-xs-3">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input placeholder="<?php echo _('Fecha'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo implota($paciente["fecha"]);?>" name="DATA[fecha]">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div> 
            </div>
            <label class="control-label col-xs-1"><?php echo _('Descripción'); ?></label>
            <div class="col-xs-3">
                <input type="text" class="form-control input-sm" name="DATA[descripcion]" value="<?php echo $paciente["descripcion"];?>" size="70" maxlength="100" data-index="7">
            </div>
            <label class="control-label col-xs-1"><?php echo _('Alias'); ?></label>
            <div class="col-xs-4">
                <input type="text" class="form-control input-sm" name="DATA[alias]" value="<?php echo $paciente["alias"];?>" size="70" maxlength="100" data-index="7">
            </div>

        </div>
        <div class="row">
            <label class="control-label col-xs-1"><?php echo _('Número'); ?></label>
            <div class="col-xs-2">
                <input type="text" class="form-control input-sm" name="DATA[numero]" value="<?php echo $paciente["numero"];?>" size="70" maxlength="100" data-index="7">
            </div>     
            <label class="control-label col-xs-1"><?php echo _('Service'); ?></label>
            <div class="col-xs-3">
                <select size="1" name="DATA[service]" class="form-control input-sm service" id="service" >
                <?php
                    $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
                    $xx=0;
                   
                    foreach($tipo as $tpo) {
                        if($xx==$paciente["service"]){
                            echo "<option value='$xx' selected>$tpo</option>";
                        }else{
                            echo "<option value='$xx'>$tpo</option>";
                        }
                    $xx++;
                    }
                    ?>
                </select>

            </div>
            <label class="control-label col-xs-1"><?php echo _('Detalles'); ?></label>
            <div class="col-xs-4">
            <textarea cols="41" rows="4" name="DATA[detalles]" class="form-control input-sm"><?php echo $paciente["detalles"];?></textarea>
            </div>    
            
        </div>

        </div>
        <div class="col-xs-4" id="SelectRespaldos">
        <fieldset><legend>Días de respaldos</legend>
            <div class="row">
            <div class="col-xs-12">
                <?php 
                //Para mantener compatibilidad hacia atrás
                $dia='';
                $diatmp = array();
                if(strpos($paciente["diasrespaldo"], '-')!== false){
                    $diasrespaldo=explode('-', $paciente['diasrespaldo']);
                    for($i=0; $i<=6; $i++){
                        if($i==0){
                            if(in_array(7, $diasrespaldo)){
                                $dia.=0;
                            }else{
                                $dia.=" ";    
                            }
                        }else{
                            if(in_array($i, $diasrespaldo)){
                                $dia.=$i;
                            }else{
                                $dia.=" ";
                            }
                        }
                    }
                }else{
                    for($i=0; $i<strlen($paciente["diasrespaldo"]); $i++){
                        $diatmp[]=$paciente["diasrespaldo"][$i];
                    }
                    for($xi=0; $xi<=6; $xi++){
                        if(in_array($xi, $diatmp)){
                            $dia.=$diatmp[$xi];
                        }else{
                            $dia.=' ';
                        }
                    }
                }

                ?>
                <input class="form-control dias-semana" name="DATA[diasrespaldo]" type="text" value="<?php echo $dia;?>" data-bind="value: WorkWeek">
            </div>
            </div>
        </fieldset>
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
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guarar'); ?>">
    <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
        <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> <?php echo _('Salir'); ?></button>
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


<script type="text/javascript" >
(function( $ ){

"use strict";

$.fn.daysOfWeekInput = function() {
  return this.each(function(){
    var $field = $(this);
    
    var days = [
      {
        Name: 'Domingo',
        Value: '0',
        Checked: false
      },
      {
        Name: 'Lunes',
        Value: '1',
        Checked: false
      },
      {
        Name: 'Martes',
        Value: '2',
        Checked: false
      },
      {
        Name: 'Miercoles',
        Value: '3',
        Checked: false
      },
      {
        Name: 'Jueves',
        Value: '4',
        Checked: false
      },
      {
        Name: 'Viernes',
        Value: '5',
        Checked: false
      },
      {
        Name: 'Sábado',
        Value: '6',
        Checked: false
      }
    ];
    
    var currentDays = $field.val().split('');
    for(var i = 0; i < currentDays.length; i++) {
      var dayA = currentDays[i];
      for(var n = 0; n < days.length; n++) {
        var dayB = days[n];
        if(dayA === dayB.Value) {
          dayB.Checked = true;
        }
      }
    }
    
    // comentar para pruebas
    $field.attr('type','hidden');
    
    var options = '<div class="col-xs-6">';
    var n = 0;
    while($('.days' + n).length) {
      n = n + 1;
    }
    
    var optionsContainer = 'days' + n;
    $field.before('<div class="days ' + optionsContainer + '"></div>');
    
    for(var i = 0; i < days.length; i++) {
      var day = days[i];
      var id = 'day' + day.Name + n;
      var checked = day.Checked ? 'checked="checked"' : '';
      if(i==3){
          options = options + '</div><div class="col-xs-6">'
      }
      options = options + '<div><input type="checkbox" value="' + day.Value + '" id="' + id + '" ' + checked + ' /><label for="' + id + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
    }
    options = options + '</div>'
    
    $('.' + optionsContainer).html(options);
    
    $('body').on('change', '.' + optionsContainer + ' input[type=checkbox]', function () {
        console.log(optionsContainer);
      var value = $(this).val();
      var index = getIndex(value);
      if(this.checked) {
        updateField(value, index);
      } else {
        updateField(' ', index);
      }
    });
    
    function getIndex(value) {
      for(i = 0; i < days.length; i++) {
        if(value === days[i].Value) {
          return i;
        }
      }
    }
    
    function updateField(value, index) {
      $field.val($field.val().substr(0, index) + value + $field.val().substr(index+1)).change();
    }
  });
}

})( jQuery );

$('.dias-semana').daysOfWeekInput();


</script>

<script>
$(document).ready(function(){
    $("select.service"). change(function(){
    var selectedService = $(this). children("option:selected"). val();
    console.log(selectedService);
    if(selectedService==3){
        $("#SelectRespaldos").children().prop('disabled',false);
        
    }else{
        $("#SelectRespaldos").children().prop('disabled',true);

    }
    });
});
</script>
<?php
include_once "../../common/footer.php";
?>