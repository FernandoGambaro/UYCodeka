<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Búsqueda'); ?></legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
<div class="col-xs-6">
  <div class="row">
    <div class="col-xs-6">
      <input placeholder="<?php echo _('Descripción'); ?>" type="text" class="form-control input-sm keyupsubmit" id="descripcion" name="descripcion" size="45" value="" maxlength="45" autocomplete="off" onFocus="this.style.backgroundColor='#FFFF99'"  />
      </div>
      <div class="col-xs-6">
      <input placeholder="<?php echo _('Código'); ?>" class="form-control input-sm keyupsubmit" type="text" id="codigobarras" name="codigobarras" size="45" autocomplete="off" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
      </div>
  </div>

  <div class="row">
    <div class="col-xs-6">
        <select name="codproveedor" id="codproveedor" class="form-control onchangesubmit" >
        <option value="" >Proveedor</option>
        <?php
          $objBaja = new Consultas('proveedores');
          $objBaja->Select('codproveedor,nombre,nif');
          $objBaja->Where('borrado', '0', '=');

          $EjeBaja = $objBaja->Ejecutar();
          $EjeBaja_rows=$EjeBaja["numfilas"];
          $rowsBaja = $EjeBaja["datos"];
  
          foreach($rowsBaja as $rowBaja){
            if ($rowBaja['nombre']!=' ') {
            ?>
            <option value="<?php echo $rowBaja['codproveedor'];?>"><?php echo $rowBaja['nombre'];?></option>
            <?php
            }
          }
        ?>
        </select>
      </div>


      <div class="col-xs-4">
        <select name="codfamilia" id="familia" class="form-control onchangesubmit" >
        <option value="" >Familia</option>
        <?php
          $objBaja = new Consultas('familias');
          $objBaja->Select();
          $objBaja->Where('borrado', '0', '=');

          $EjeBaja = $objBaja->Ejecutar();
          $EjeBaja_rows=$EjeBaja["numfilas"];
          $rowsBaja = $EjeBaja["datos"];
  
          foreach($rowsBaja as $rowBaja){
            if ($rowBaja['nombre']!=' ') {
            ?>
            <option value="<?php echo $rowBaja['codfamilia'];?>"><?php echo $rowBaja['nombre'];?></option>
            <?php
            }
          }
        ?>
        </select>
      </div>
      <div class="col-xs-2">
      <input id="stock" name="stock" type="text" class="form-control" maxlength="60"  placeholder="<?php echo _('Stock >'); ?>">
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
						<option value="1">Listado de artículos activos</option>
						<option value="2">Lista de precios general</option>
						<option value="3">Lista de precios por familias</option>
						<option value="4">Lista de precios por familias con logos</option>
						<option value="5">Listado de artículos con comisión</option>
						<option value="6">Listado de artículos de baja</option>
						<option value="7">Listado de artículos stock bajo mínimo</option>
						<option value="8">Listado de artículos por proveedor</option>
						</select>        
      </div>
      <div class="col-xs-4">
          <button class="btn btn-primary btn-xs" onClick="imprimir();">
          <i class="fa fa-print" aria-hidden="true"></i>&nbsp;<?php echo _('Imprimir'); ?></button>
      </div>
      </fieldset>
    </div>    
</div>
<input id="page" name="page" type="hidden" value="">
  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
    $("#Ausuarios").val("");
    $("#codusuarios").val("");
    $("#Acliente").val("");
    $("#Acodcliente").val("");
    $("#fechaini").val("");
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