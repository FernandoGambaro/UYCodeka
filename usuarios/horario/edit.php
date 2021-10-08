<?php
// set page headers
$page_title = _('Datos del equipo'); 
include_once "header-rejilla.php";

// isset() is a PHP function used to verify if ID is there or not
$codhorarios = isset($_GET['codhorarios']) ? $_GET['codhorarios'] : $_POST['codhorarios'];

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../../common/verificopermisos.php';   

$mensaje='';
$obj = new Consultas('horariousuario');

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
                if($key=='codhorarios'){
                    $codhorarios=$item;
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
                    if($key=='codhorarios'){
                        $codhorarioss=$item;
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

        $objpaciente = new Consultas('horariousuario');
        $objpaciente->Select();
        $objpaciente->Where(trim($attr), trim($valor));
        $paciente = $objpaciente->Ejecutar();
        $paciente = $paciente["datos"][0];
        $oidestudio = '';
        $oidpaciente = '';
        $hace = _('Modifica datos del equipo '). $codhorarios;
        
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
$obj = new Consultas('horariousuario');
$obj->Select();
$obj->Where('codhorarios', $codhorarios);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];
$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve datos del equipo '). $codhorarios;

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
        <div class="panel-heading"><?php echo _('Horario asignado'); ?></div>
        <div class="panel-body">
<div id="exTab3" class="container">	


<div class="tab-content col-xs-12 ">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>



<form class="form-horizontal" action='edit.php' method='post'>
    <input type="hidden" name="DATA[codhorarios]" id="codhorarios" value="<?php echo $paciente["codhorarios"];?>" >
    <input type="hidden" name="codhorarios" value="<?php echo $paciente["codhorarios"];?>" >
    <div class="form-group">
    <div class="row">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Ver/Modificar datos &nbsp;'); ?></legend>

    <div class="form-group">
    <div class="row">
        <label class="control-label col-xs-2">Hora&nbsp;ingreso:</label>
        <div class="col-xs-3">
            <div class="input-group Sel_hora" data-placement="left" data-align="top" data-autoclose="true">
                <input type="text" class="form-control" name="DATA[horaingreso]" id="horaingreso" value="<?php echo $paciente["horaingreso"];?>" autocomplete="off">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
        <label class="control-label col-xs-2">Hora salida:</label>
        <div class="col-xs-3">
            <div class="input-group Sel_hora" data-placement="left" data-align="top" data-autoclose="true">
                <input type="text" class="form-control" name="DATA[horasalida]" id="horasalida" value="<?php echo $paciente["horasalida"];?>" autocomplete="off">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Descanso'); ?></label>
        <div class="col-xs-3">
        <input id="descanso" name="DATA[descanso]" type="hidden">
            <select id="combodescanso" class="form-control input-sm" onchange="document.getElementById('descanso').value =this.value;">
            <?php
            //$horas;
            $h='';
            $h1='';
            $hora=$paciente["descanso"];
            for ($i=0; $i<3; $i++)
            {
                if(strlen($i)==1){
                    $h.="0".$i;
                } else {
                    $h.=$i;
                }
                    
                for ($x=0; $x<=11; $x++) {
                    if ($x==0){
                    $h1.=":00";
                    } elseif($x==1) {
                    $h1.=":0". $x*5;
                    }else {
                    $h1.=":". $x*5;
                    }
                    if($hora==$h.$h1) {
                    ?>
                    <option value="<?php echo $h.$h1; ?>" selected><?php echo $h.$h1; ?></option>
                    <?php
                    }else {
                    ?>
                    <option value="<?php echo $h.$h1; ?>" ><?php echo $h.$h1; ?></option>
                    <?php
                    }
                    $h1='';
                }
                $h='';
            }
            ?>
            </select>			
        </div>

        </div>
    </div>


        <div class="row">
  
            <div class="col-xs-12" id="SelectDiasAsignados">
            <fieldset><legend>Días asignados para este horario &nbsp; </legend>
                <div class="row">
                <div class="form-group">
                <div class="col-xs-10">
                <?php 
                //Para mantener compatibilidad hacia atrás
                $dia='';
                $diatmp = array();
                if(strpos($paciente["diasemana"], '-')!== false){
                    $diasemana=explode('-', $paciente['diasemana']);
                    for($i=0; $i<7; $i++){
                        if($i==0){
                            if(in_array(7, $diasemana)){
                                $dia.=0;
                            }else{
                                $dia.=" ";    
                            }
                        }else{
                            if(in_array($i, $diasemana)){
                                $dia.=$i;
                            }else{
                                $dia.=" ";
                            }
                        }
                    }
                }else{
                    for($i=0; $i<strlen($paciente["diasemana"]); $i++){
                        if(strlen($paciente["diasemana"][$i])>0)
                        $dia.=$paciente["diasemana"][$i];
                    }

                }
                ?>
                <input class="form-control dias-semana" name="DATA[diasemana]" type="text" value="<?php echo $dia;?>" data-bind="value: WorkWeek">
            </div>
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
    
    var options = '<div class="row"><div class="col-xs-3">';
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
      if(i==2 || i==4 || i==6){
          options = options + '</div><div class="col-xs-3">'
      }
      options = options + '<div><input type="checkbox" value="' + day.Value + '" id="' + id + '" ' + checked + ' /><label for="' + id + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
    }
    options = options + '</div></div>'
    
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


<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="../../library/clockpicker-gh-pages/src/clockpicker.css">

<!-- ClockPicker script -->
<script type="text/javascript" src="../../library/clockpicker-gh-pages/src/clockpicker.js"></script>


<script type="text/javascript">

$('.Sel_hora').click(function(e){

    var input = $(this).clockpicker({
    placement: 'middle',
    align: 'left',
    autoclose: true,
    'default': 'now',

    });
    
    e.stopPropagation();
    input.clockpicker('show')
            .clockpicker();

});

</script>
<?php
include_once "../../common/footer.php";
?>