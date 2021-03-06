<?php
// set page headers
$page_title = _('Datos del Cliente'); 
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('clientes');

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

    $obj->Insert($nombres, $valores);
    //$obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    $codcliente=$paciente['id'];
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
        $datosguardados=1;
        $obj = new Consultas('clientes');
        $obj->Select();
        $obj->Where('codcliente', $codcliente);
        $paciente = $obj->Ejecutar();
        $paciente = $paciente["datos"][0];

        $oidestudio = '';
        $oidpaciente = '';
        $hace = _('Nuevo cliente '). $paciente["nombre"]." ".$paciente["apellido"];

        logger($UserID, $oidestudio, $oidpaciente, $hace);

        echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
 
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }

$dpto = new Consultas('departamentos');
$dpto->Select();
$departamento = $dpto->Ejecutar();
$departamento = $departamento["datos"];

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
<form class="form-horizontal" action='create.php' method='post'>

<div class="panel panel-default">
        <div class="panel-heading"><?php echo _('Nuevo Cliente');?></div>
        <div class="panel-body">

<div id="exTab3" class="container">	
    <?php
    $equipos=verificopermisos('Equiposcliente', 'leer', $UserID);
    $respaldos=verificopermisos('Respaldoscliente', 'leer', $UserID);
    $proyectos=verificopermisos('Proyectoscliente', 'leer', $UserID);            
    ?>

<ul class="nav nav-tabs nav-pills tabs">
<li class="active"><a href="#1b" data-toggle="tab"><?php echo _('Datos B??sicos'); ?></a></li>
<li><a href="#2b" data-toggle="tab"><?php echo _('Otros datos'); ?></a></li>
<?php if ( $proyectos=="true") { ?>
<li><a href="#3b" data-toggle="tab"><?php echo _('Proyectos'); ?></a></li>
<?php } if ( $equipos=="true") { ?>
<li><a href="#4b" data-toggle="tab"><?php echo _('Equipos'); ?></a></li>
<?php } if ( $respaldos=="true") { ?>
<li><a href="#5b" data-toggle="tab"><?php echo _('Respaldos'); ?></a></li>
<?php } ?>
</ul>

<div class="tab-content col-xs-12 ">
<div class="tab-pane fade in active" id="1b">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>
<div class="container-fluid">
<div class="row">

    <input type="hidden" name="DATA[codcliente]" id="codcliente" value="" >
    <input type="hidden" name="codcliente" value="" >
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Nombre'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="" placeholder="<?php echo _('Nombre'); ?>" required>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Apellido'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="apellido" name="DATA[apellido]" value="" placeholder="<?php echo _('Apellido'); ?>"> 
        </div>

        <label class="control-label col-xs-1"><?php echo _('Tel??fono'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono" name="DATA[telefono]" value="" placeholder="<?php echo _('Tel??fono'); ?>">
        </div>
    </div>
    <!-- /////////////// -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Empresa'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="empresa" name="DATA[empresa]" value="" placeholder="<?php echo _('Empresa'); ?>">
        </div>
        <label class="control-label col-xs-1"><?php echo _('RUT'); ?></label>
        <div class="col-sm-1">
        <input name="DATA[tiponif]" id="tiponif" value="" type="hidden" />
                <select type="text" onchange="document.getElementById('tiponif').value=this.value;" class="form-control input-sm" >
                <?php
                $tiponif = array(0=>_("Seleccione uno"), 1=>_("RUT"), 2=>_("CI"), 3=>_("Pasaporte") );
                foreach($tiponif as $key=>$valor) {
                    echo "<option value='$key'>$valor</option>";
                }
                ?>
                </select>
        </div>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="nif" name="DATA[nif]" value="" placeholder="<?php echo _('RUT/Documento'); ?>">
        </div>
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Tel??fono'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono2" name="DATA[telefono2]" value="" placeholder="<?php echo _('Tel??fono secundario'); ?>"> 
        </div>

    </div>
    <!-- //////////// -->    
    <div class="form-group">        
        <label class="control-label col-xs-1"><?php echo _('Movil'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="movil" name="DATA[movil]" value="" placeholder="<?php echo _('Movil'); ?>"> 
        </div>
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Fax'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="fax" name="DATA[fax]" value="" placeholder="<?php echo _('N??mero fax'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('eMail primario'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email" name="DATA[email]" value="" placeholder="<?php echo _('Email primario'); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('eMail secundario'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email2" name="DATA[email2]" value="" placeholder="<?php echo _('Email secundario'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Web'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="web" name="DATA[web]" value="" placeholder="<?php echo _('Web'); ?>"> 
        </div>
    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Direcci??n'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="direccion" name="DATA[direccion]" value="" placeholder="<?php echo _('Direcci??n'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Departamento'); ?>&nbsp;</label>            
        <div class="col-xs-3">
        <select name="DATA[codprovincia]" class="form-control input-sm">
        <?php
        foreach ($departamento as $key) {
                echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
        }
        ?>            
        </select>
        </div> 
        <label class="control-label col-xs-1"><?php echo _('Localidad'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="localidad" name="DATA[localidad]" value="" placeholder="<?php echo _('Localidad'); ?>"> 
        </div>                
    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group"> 
    <label class="control-label col-xs-1"><?php echo _('C??digo postal'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="codpostal" name="DATA[codpostal]" value="" placeholder="<?php echo _('C??digo postal'); ?>"> 
        </div>                   
        <label class="control-label col-xs-2" for="usuario"><?php echo _('Ejecutivo de cuenta'); ?>&nbsp;</label>
        <div class="col-xs-2">

            <input placeholder="Usuario" type="text" onfocus="this.select();" class="form-control input-sm" id="Ausuarios" size="45" value="" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  />
            <input name="DATA[codusuarios]" type="hidden" id="codusuarios" readonly  value="" />
        </div>
    </div> 
    <!-- ////////////////////////////////  -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Abonado/Service'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <select name="DATA[service]" class="form-control input-sm">
        <?php
        $tipo = array(0=>"Seleccione un tipo", 1=>"Com??n", 2=> "Abonado A", 3=> "Abonado B");
        foreach ($tipo as $key=>$valor) {
            echo "<option value='".$key."'>".$valor."</option>";
        }
        ?>            
        </select>
        </div>
        <label class="control-label col-xs-2"><?php echo _('Horas Asig./Mes'); ?>&nbsp;</label>            
        <div class="col-xs-1">
        <input type="text" class="form-control input-sm" id="horas" name="DATA[horas]" value="" placeholder="<?php echo _('Horas al mes'); ?>"> 

        </div> 
        <label class="control-label col-xs-2"><?php echo _('C??digo Plan de Cuentas'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="plancuenta" name="DATA[plancuenta]" value="" placeholder="<?php echo _('Plan de cuentas'); ?>"> 
        </div>                
    </div>     
    <!-- ////////////////////////////////  -->
    <div class="form-group">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo _('Acceso web'); ?></legend>
        <label class="control-label col-xs-1"><?php echo _('Password'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="contrasenia" name="DATA[contrasenia]" value="" placeholder="<?php echo _('Contrase??a'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Pregunta'); ?>&nbsp;</label>            
        <div class="col-xs-3">
                <input name="DATA[secQ]" id="secQ" value="<?php echo $paciente["secQ"];?>" type="hidden" />
                <select id="Pregunta" type="text" onchange="document.getElementById('secQ').value=this.value;" class="form-control input-sm" >
                <?php
                    $questions = array();
                    $questions[0] = _("Seleccione uno");
                    $questions[1] = _("??En que ciudad naci???");
                    $questions[2] = _("??C??al es su color favorito?");
                    $questions[3] = _("??En qu?? a??o se graduo de la facultad?");
                    $questions[4] = _("??Cual es el segundo nombre de su novio/novia/marido/esposa?");
                    $questions[5] = _("??C??al es su auto favorito?");
                    $questions[6] = _("??C??al es el nombre de su madre?");
                foreach($questions as $pregunta) {
                        echo "<option value='$xx'>$pregunta</option>";
                }
                ?>
                </select>
        </div>
        <label class="control-label col-xs-1"><?php echo _('Respuesta'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[secA]" id="secA" value="" class="form-control input-sm" >
        </div>
    </fieldset>                 
    </div>
            <!-- ////////////////////////////////  -->            
</div>
</div>
</div> <!--Fin 1b  *****************************************************************-->
<div class="tab-pane" id="2b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  -->
    <div class="form-group">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Formas de pago'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('D??as'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="pagodia" name="DATA[pagodia]" value="" placeholder="<?php echo _('D??as de pago'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="pagohora" name="DATA[pagohora]" value="" placeholder="<?php echo _('Horario de pago'); ?>"> 
        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[pagocontacto]" id="pagocontacto" value="" class="form-control input-sm" >
        </div>
    </div>

    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Forma de pago'); ?>&nbsp;</label>
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
                ?>
                    <option value="<?php echo $rowformapago['codformapago'];?>" ><?php echo $rowformapago['nombrefp'];?></option>
                <?php
            }
        }
        ?>
        </select>
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Banco'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input name="DATA[codentidad]" id="entidades" value="" type="hidden" />
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
                ?>
                    <option value="<?php echo $rowentidades['codentidad'];?>" ><?php echo $rowentidades['nombreentidad'];?></option>
                <?php
            }
        }
        ?>
        </select>

        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('N?? cuenta'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[cuentabancaria]" id="tacto" value="" class="form-control input-sm" >
        </div>
    </div>
        

    </fieldset>                 
    </div>    

<!-- ////////////////////////////////  -->
<div class="form-group">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Datos de entrega'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Agencia de cargas'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="agencia" name="DATA[agencia]" value="" placeholder="<?php echo _('Agencia'); ?>"> 
        </div>        
    </div>
    <div class="row">
        <label class="control-label col-xs-1"><?php echo _('D??as'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="recepciondia" name="DATA[recepciondia]" value="" placeholder="<?php echo _('D??as de recepci??n'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="recepcionhora" name="DATA[recepcionhora]" value="" placeholder="<?php echo _('Horario de recepci??n'); ?>"> 
        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[recepcioncontacto]" id="pontacto" value="" class="form-control input-sm" >
        </div>
    </div>
    </fieldset>                 
    </div>    


</div>  
</div> 
</div> <!-- fin 2b **************************************************-->
<?php if ( $proyectos=="true") { ?>
<div class="tab-pane" id="3b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  Proyectos -->
    <div class="row">

        
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Nuevo Proyecto'); ?></legend>

</fieldset>

    </div>
<div class="row">

    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de Proyectos'); ?></legend>
        <div class="col-xs-12"><?php echo _('Debe guardar los datos antes de agregar proyectos');?></div>
</div>        
</div>
</div>  
</div><!-- fin 3b **************************************************-->
<?php }

if ( $equipos=="true") { ?>
<div class="tab-pane" id="4b">
<div class="container-fluid">
<div class="row">

<!-- ////////////////////////////////  Equipos -->
<div class="row">
        
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Nuevo Equipo'); ?></legend>

</fieldset>


</div>
<div class="row">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de equipos'); ?></legend>
    <div class="col-xs-12"><?php echo _('Debe guardar los datos antes de agregar equipo');?></div>
</div>
</div>
</div>
</div><!-- fin 4b **************************************************-->

<?php } if ( $respaldos=="true") { ?>

<div class="tab-pane" id="5b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  Respaldos -->
 
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de respaldos'); ?></legend>
    <iframe src="backup/rejilla.php?codcliente=<?php echo $codcliente;?>" width="98%" height="280" id="frame_backups" name="frame_backupsf" frameborder="0" scrolling="no" >
    <ilayer width="98%" height="280" id="frame_backups" name="frame_backups"></ilayer>
    </iframe>
    <iframe id="frame_datos_backups" name="frame_datos_backups" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos_backups" name="frame_datos_backups"></ilayer>
    </iframe>

        
    </div>        
 

</div>  
</div><!-- fin 5b **************************************************-->
<?php } ?>

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
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guardar'); ?>">
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
        Name: 'S??bado',
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