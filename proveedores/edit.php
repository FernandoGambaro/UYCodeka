<?php
// set page headers
$page_title = _('Datos del Proveedores'); 
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not
$codproveedor = isset($_GET['codproveedor']) ? $_GET['codproveedor'] : $_POST['codproveedor'];

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('proveedores');

if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';
    $randomhash = RandomString(24);

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
                        if($key=='contrasenia'){
                            $nombres[] = 'randomhash';
                            $valores[] = $randomhash;
                            $converter = new Encryption;
                            $valores[] = $converter->encode($item.$randomhash);
                            
                        }else{
                            $valores[] = $item;
                        }
                    }
                    $nombres[] = $key;
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
    
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;
 
        //echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";

    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }
  
$obj = new Consultas('proveedores');
$obj->Select();
$obj->Where('codproveedor', $codproveedor);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];

$dpto = new Consultas('departamentos');
$dpto->Select();
$departamento = $dpto->Ejecutar();
$departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Modifica datos de '). $paciente["nombre"];

logger($UserID, $oidestudio, $oidpaciente, $hace);

} else {
    $obj->Select();
    $obj->Where('codproveedor', $codproveedor);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente["datos"][0];
    
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve Contacto proveedores '). $paciente["nombre"];
$datosguardados=0;
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
    height: 380px;
}
</style>
<form class="form-horizontal" action='edit.php' method='post'>

<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados.'); ?></div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del Proveedor')." ".$paciente["nombre"];?></div>
        <?php } ?>
        <div class="panel-body">

<div id="exTab3" class="container">	

<ul class="nav nav-tabs nav-pills tabs">
<li class="active"><a href="#1b" data-toggle="tab"><?php echo _('Datos Básicos'); ?></a></li>
</ul>

<div class="tab-content col-xs-12 ">
<div class="tab-pane fade in active" id="1b">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>
<div class="container-fluid">
<div class="row">


    <input type="hidden" name="DATA[codproveedor]" id="codproveedor" value="<?php echo $paciente["codproveedor"];?>" >
    <input type="hidden" name="codproveedor" value="<?php echo $paciente["codproveedor"];?>" >
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Nombre:'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="<?php echo $paciente["nombre"];?>" placeholder="<?php echo _('Nombre'); ?>" required>
        </div>
        <label class="control-label col-xs-1"><?php echo _('RUT:'); ?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="nif" name="DATA[nif]" value="<?php echo $paciente["nif"];?>" placeholder="<?php echo _('RUT/Documento'); ?>">
        </div>

        <label class="control-label col-xs-1"><?php echo _('Teléfono:'); ?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="telefono" name="DATA[telefono]" value="<?php echo $paciente["telefono"];?>" placeholder="<?php echo _('Teléfono'); ?>">
        </div>
    </div>
    <!-- /////////////// -->
    <div class="form-group">
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Teléfono'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono2" name="DATA[telefono2]" value="<?php echo $paciente["telefono2"];?>" placeholder="<?php echo _('Teléfono secundario'); ?>"> 
        </div>
       
        <label class="control-label col-xs-1"><?php echo _('Movil:'); ?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="movil" name="DATA[movil]" value="<?php echo $paciente["movil"];?>" placeholder="<?php echo _('Movil'); ?>"> 
        </div>
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Fax:'); ?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="fax" name="DATA[fax]" value="<?php echo $paciente["fax"];?>" placeholder="<?php echo _('Número fax'); ?>"> 
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('eMail&nbsp;primario:'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email" name="DATA[email]" value="<?php echo $paciente["email"];?>" placeholder="<?php echo _('Email primario'); ?>">
        </div>
        <label class="control-label col-xs-1"><?php echo _('eMail secundario:'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email2" name="DATA[email2]" value="<?php echo $paciente["email2"];?>" placeholder="<?php echo _('Email secundario'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Web.'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="web" name="DATA[web]" value="<?php echo $paciente["web"];?>" placeholder="<?php echo _('Web'); ?>"> 
        </div>

    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Dirección:'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="direccion" name="DATA[direccion]" value="<?php echo $paciente["direccion"];?>" placeholder="<?php echo _('Dirección'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Departamento:'); ?>&nbsp;</label>            
        <div class="col-xs-2">
        <select name="DATA[codprovincia]" class="form-control input-sm">
        <?php
        foreach ($departamento as $key) {
            
            if ($key["departamentosid"] == $paciente["codprovincia"]) {
                echo "<option value='".$key["departamentosid"]."' selected>".$key["departamentosdesc"]."</option>";
            } else {
                echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
            }
        }
        ?>            
        </select>
        </div> 
        <label class="control-label col-xs-1"><?php echo _('Localidad:'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="localidad" name="DATA[localidad]" value="<?php echo $paciente["localidad"];?>" placeholder="<?php echo _('Localidad'); ?>"> 
        </div>                
    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group"> 
    <label class="control-label col-xs-1"><?php echo _('Código&nbsp;postal:'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="codpostal" name="DATA[codpostal]" value="<?php echo $paciente["codpostal"];?>" placeholder="<?php echo _('Código postal'); ?>"> 
        </div>                   
    </div>  

    <div class="form-group">
    <fieldset class="scheduler-border">

    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Forma de pago:'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <input name="DATA[codformapago]" id="codformapago" value="<?php echo $paciente["codformapago"];?>" type="hidden" />
        <select type="text" onchange="document.getElementById('codformapago').value=this.value;" class="form-control input-sm" >
        <?php
        $objformapago = new Consultas('formapago');
		$objformapago->Select();
    
        $objformapago->Where('borrado', '0');    
        $formapago = $objformapago->Ejecutar();
        $total_formapago=$formapago["numfilas"];
        $rowsformapago = $formapago["datos"];
        //echo $formapago["consulta"];
        // check if more than 0 record found
        if($total_formapago>=0){
            foreach($rowsformapago as $rowformapago){
                if ($rowformapago['codformapago'] == $paciente["codformapago"]) { ?>
                    <option value="<?php echo $rowformapago['codformapago'];?>" selected="selected"><?php echo $rowformapago['nombrefp'];?></option>
                <?php } else { ?>
                    <option value="<?php echo $rowformapago['codformapago'];?>" ><?php echo $rowformapago['nombrefp'];?></option>
                <?php
                }
            }
        }
        ?>
        </select>
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Banco:'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input name="DATA[codentidad]" id="entidades" value="<?php echo $paciente["codentidad"];?>" type="hidden" />
        <select type="text" onchange="document.getElementById('entidades').value=this.value;" class="form-control input-sm" >
        <?php
        $objentidades = new Consultas('entidades');
		$objentidades->Select();
    
        $objentidades->Where('borrado', '0');    
        $entidades = $objentidades->Ejecutar();
        $total_entidades=$entidades["numfilas"];
        $rowsentidades = $entidades["datos"];
        //echo $entidades["consulta"];
        // check if more than 0 record found
        if($total_entidades>=0){
            foreach($rowsentidades as $rowentidades){
                if ($rowentidades['codentidad'] == $paciente["codentidad"]) { ?>
                    <option value="<?php echo $rowentidades['codentidad'];?>" selected="selected"><?php echo $rowentidades['nombreentidad'];?></option>
                <?php } else { ?>
                    <option value="<?php echo $rowentidades['codentidad'];?>" ><?php echo $rowentidades['nombreentidad'];?></option>
                <?php
                }
            }
        }
        ?>
        </select>

        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Nº cuenta:'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[cuentabancaria]" id="tacto" value="<?php echo $paciente["cuentabancaria"];?>" class="form-control input-sm" >
        </div>
    </div>
        

    </fieldset>                 
    </div>    
  


</div>  
</div> 
</div> <!-- fin 2b **************************************************-->
 

</div> 
 



</div>			
</div><!-- Fin  -->

	<div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-xs-12 mx-auto">
    <div class="text-center">
        <?php if(!$_POST)
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

$(document).ready(function (){
    $(window).bind('load', function () {
        var frameWidth = $(document).width();
        var frameHeight = $(document).height();
        parent.$.fn.colorbox(frameWidth, frameHeight);
    });
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
    
    // Make the field hidden when in production.
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
<?php
include_once "../common/footer.php";

?>