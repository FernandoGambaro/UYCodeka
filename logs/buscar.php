<?php

?>
<fieldset class="scheduler-border">
  <legend class="scheduler-border">Búsqueda</legend>

<form id="form" name="form" method="post" action="rejilla.php" target="log_rejilla">
    <div class="input-group input-group-sm">
<?php if(strlen($codusuarios)<=0){ ?>
      <div class="col-xs-2">
        <label for="ex1">Nombre</label>
        <input class="form-control input-sm" id="nombre" name="nombre" type="text">
      </div>
      <div class="col-xs-2">
        <label for="ex1">Apellido</label>
        <input class="form-control input-sm" id="apellido" name="apellido" type="text" >
      </div>
<?php } ?>
      <div class="col-xs-3">
        <label for="ex1">Fecha ingreso</label>
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input placeholder="Fecha" class="form-control input-sm" size="16" type="text" value="" name="fecha" id="fecing" readonly  required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
      </div>         
      
      <div class="input-group-btn">
        <button type="reset" id="cancel" class="btn btn-default"><i class="glyphicon glyphicon-erase"></i></button>	
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </div>
  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
    $("#nombre").val("");
    $("#apellido").val("");
    $("#ci").val("");
    $("#telefono").val("");
    $("#celular").val("");
    $("#fecultest").val("");
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