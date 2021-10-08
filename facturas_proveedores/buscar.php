<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Búsqueda'); ?></legend>

<form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
<div class="input-group input-group-sm">
<div class="col-xs-12">
  <div class="row">
    <div class="col-xs-6">
      <div class="row">
        <div class="col-xs-4">
          <input type="hidden" id="codproveedor" name="codproveedor" value="">

          <input type="text" class="form-control input-sm" id="Acodproveedor" value="" placeholder="<?php echo _('Proveedor'); ?>" data-index="1" >
        </div>
        <div class="col-xs-4">
          <input placeholder="<?php echo _('Descripción'); ?>" class="form-control input-sm keyupsubmit" type="text" id="descripcion" name="descripcion" autocomplete="off" onFocus="this.style.backgroundColor='#FFFF99'">
        </div>
        <div class="col-xs-4">
          <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
              <input placeholder="<?php echo _('Fecha inicio'); ?>" class="form-control input-sm" size="26" type="text" value="" id="fechainicio" name="fechainicio" readonly  data-index="16">
              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
          </div> 
        </div>
      </div>
      <div class="row">
        <div class="col-xs-4">
          <input placeholder="<?php echo _('Num. factura'); ?>" type="text" class="form-control input-sm keyupsubmit" id="codfactura" name="codfactura" autocomplete="off" onFocus="this.style.backgroundColor='#FFFF99'"  />
        </div>

        <div class="col-xs-4">
            <select name="estado" id="estado" class="form-control onchangesubmit" >
              <option value="" selected>Todos los estados</option>
              <option value="1">Sin Pagar</option>
              <option value="2">Pagada</option>	
            </select>
        </div>      

        <div class="col-xs-4">
          <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
              <input placeholder="<?php echo _('Fecha fin'); ?>" class="form-control input-sm" size="26" type="text" value="" id="fechafin" name="fechafin" readonly  data-index="17">
              <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
          </div>
        </div>
      </div>

    </div>
    <div class="col-xs-2">
      <div class="input-group-btn">
        <button type="reset" id="cancel" class="btn btn-default" onclick='$(this).trigger("reset");'><i class="glyphicon glyphicon-erase"></i></button>	
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </div>
    <div class="col-xs-4">
      <div class="row">
        <fieldset><legend>&nbsp;<?php echo _('Opciones de impresión'); ?>&nbsp;</legend>
        <div class="col-xs-8">
              <select id="opcionesprint" class="form-control form-control-sm" >
              <option value="1">Ninguna</option>
              </select>        
        </div>
        <div class="col-xs-4">
            <button class="btn btn-primary btn-xs" onClick="imprimir();">
            <i class="fa fa-print" aria-hidden="true"></i>&nbsp;<?php echo _('Imprimir'); ?></button>
        </div>
        </fieldset>
      </div>    
    </div>

  </div>
</div>

<input id="page" name="page" type="hidden" value="">
  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
  $("#proveedor").val("");
  $("#codproveedor").val("");
  
  $("#descripcion").val("");
  $("#codfactura").val("");
  $("#estado").val("");
  $("#fechafin").val("");
  $("#page").val("");
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