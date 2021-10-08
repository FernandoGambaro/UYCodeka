<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$fechainicio = data_first_month_day(date('Y-m-d'));
$fechafin = data_last_month_day(date('Y-m-d'));


$objMon = new Consultas('monedas');
$objMon->Select();
$objMon->Where('orden', '3', '<');
$objMon->Where('borrado', '0');
$objMon->Orden('orden', 'ASC');
$selMon=$objMon->Ejecutar();
$filasMon=$selMon['datos'];


?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Búsqueda'); ?></legend>

  <form name="form" id="form" action="cierremes.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
<div class="col-xs-8">
  <div class="row">
    <div class="col-xs-4">
    <input type="hidden" id="codcliente" name="codcliente" value="">
      <input placeholder="<?php echo _('Nombre'); ?>" type="text" class="form-control input-sm" id="nombre" size="45" value="" maxlength="45" autocomplete="off" onFocus="this.style.backgroundColor='#FFFF99'" />
    </div>
    <div class="col-xs-3">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
          <input placeholder="<?php echo _('Fecha inicio'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo implota($fechainicio);?>" id="fechaini" name="fechaini" readonly  required data-index="17">
          <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
        </div>
    </div>
    <div class="col-xs-3">
      <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
        <input placeholder="<?php echo _('Fecha fin'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo implota($fechafin);?>" id="fechafin" name="fechafin" readonly  required data-index="17">
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
      </div>
    </div>
    <div class="col-xs-2">
    <div class="input-group-btn">
      <button type="reset" id="cancel" class="btn btn-default" onclick='$(this).trigger("reset");'><i class="glyphicon glyphicon-erase"></i></button>	
      <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
    </div>
    </div>
  </div>
  <div class="row">
    <label class="control-label col-xs-1">&nbsp;<?php echo _('Moneda'); ?></label>
    <div class="col-xs-2">
      <select name="DATA[moneda]" id="Amoneda" class="form-control" onchange="cambio();" data-index="5">
        <option value="-1" >Moneda</option>
        <?php
          foreach($filasMon as $fila){
              if ($xmon==$paciente["moneda"]) {
                  ?> <option value="<?php echo $xmon;?>" selected><?php echo $fila['simbolo'];?></option> <?php
              }else{
                  ?> <option value="<?php echo $xmon;?>"><?php echo $fila['simbolo'];?></option> <?php
              }
              $xmon++;
          }
        ?>
    </select>
  </div> 
  </div>
</div>

<div class="col-xs-4">
    <div class="row">
      <fieldset><legend>&nbsp;<?php echo _('Opciones de impresión'); ?>&nbsp;</legend>
      <div class="col-xs-8">
						<select id="opcionesprint" class="form-control form-control-sm" onchange="submitformrepo();" >
              <option value="1">Detalles compra/venta</option>
							<option value="2">Estado de cuenta cliente</option>
							<option value="5">Estado de cuenta todos los cliente</option>
							<option value="3">Liquidación de comisiones</option>
							<option value="4">Cierre anualizado</option>							
						</select> 
            <label>En lugar de imprimir, exportar a Excel?
  							<input id="opcionesaexcel" name="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label>       
      </div>
      <div class="col-xs-4">
          <button class="btn btn-primary btn-xs" onClick="imprimir();">
          <i class="fa fa-print" aria-hidden="true"></i>&nbsp;<?php echo _('Imprimir'); ?></button>
      </div>
      </fieldset>
    </div>    
</div>

  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
    $("#Ausuarios").val("");
    $("#codusuarios").val("");
    $("#Acliente").val("");
    $("#Acodproveedor").val("");
    $("#fechaini").val("");
    $("#fechafin").val("");
    $('#form')[0].submit();
});
</script>
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