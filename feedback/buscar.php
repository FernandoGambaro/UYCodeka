<?php 
require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

?>
<fieldset class="scheduler-border">
  <legend class="scheduler-border">Opciones <?php echo $client;?></legend>
  <form id="form_busqueda" name="form_busqueda" method="post" action="rejilla.php" target="frame_rejilla">

<div class=row>
  <div class="col-xs-2">
    <div class="row">
      <div class="col-xs-12">
        <button class="btn btn-default" onClick="nuevo();" onMouseOver="style.cursor=cursor">
        <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nueva&nbsp;evaluación</button>
      </div>  
    </div>
    <div class="row">
      <div class="col-xs-12">
          <button class="btn btn-default" onClick="devolucion();" onMouseOver="style.cursor=cursor">
					  <i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nueva&nbsp;devolución</button>					
      </div>
    </div>
  </div>
  <div class="col-xs-6">
    <div class="row">
      <div class="col-xs-2">Evaluador:</div>
      <div class="col-xs-3">
        <input type="hidden" name="codusuarios" id="codusuario" value="" />
        <input type="text" id="Ausuarios" class="form-control input-sm" />						
      </div>
      <div class="col-xs-6">
        <?php
        $obj = new Consultas('formularios');
        $obj->Select();
        $obj->Where('borrado', '0');    
        $obj->Orden("descripcion" , "ASC");
        $paciente = $obj->Ejecutar();
        $rows = $paciente["datos"];

        $total_rows=$paciente["numfilas"];
        ?>
          <input id="Aformulario" name="formulario" value="" type="hidden" />
          <select size="1"  class="form-control input-sm" onchange="document.getElementById('Aformulario').value =this.value;">					
          <option value="">Seleccione un formulario</option>
            <?php 
            if($total_rows>=0){
              foreach($rows as $row){ ?>
                    <option value="<?php echo $row['codformulario'];?>"><?php echo $row['descripcion'];?></option>
              <?php } 
            } ?>				
          </select>							
      </div>
    </div>
    <div class="row">
      <div class="col-xs-2">Colaborador</div>
      <div class="col-xs-3">
      <input type="hidden" name="colaborador" id="nempleado" value="" />
        <input type="text" id="Anempleado"  class="form-control input-sm" />
      </div>
      <div class="col-xs-1">Fecha </div>
      <div class="col-xs-5">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2">
            <input placeholder="Fecha fin" class="form-control input-sm" size="10" type="text" value="<?php echo $fecha?>" name="fecha" id="fecha" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
          <button type="reset" id="cancel" class="btn btn-default" onclick='$(this).trigger("reset");'><i class="glyphicon glyphicon-erase"></i></button>	
          <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
					<button class="btn btn-default" onClick="guia();" onMouseOver="style.cursor=cursor"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Guía</button>
					<?php 
					if (verificopermisos('usuarios', 'modificar', $UserID)=="true") {?>
					<button class="btn btn-default" onClick="grafico();" onMouseOver="style.cursor=cursor"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráficar</button>
						<?php } ?>													
         </div>
    </div>

  </div>
  <div class="col-xs-4">
    <?php  if (verificopermisos('usuarios', 'modificar', $UserID)=="true") {?>
      <div class="col-xs-12">
      <div class="row">
        <fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
          <select id="opcionesprint" class="form-control form-control-sm" onchange="cambioseleccion();">
          <option value="1">Pendientes de registro</option>
          <option value="2">Usuarios, tareas realizadas</option>
          <option value="3">Clientes, tareas realizadas</option>
          <option value="4">Horas realizadas xUsuario xCliente</option>
          </select>        
      </div>
      <div class="row">
      <label>A Excel?	<input id="opcionesaexcel" type="checkbox" checked="true" value="0">
      <span></span>
      </label> &nbsp;     
      <button class="btn btn-primary btn-xs" onClick="imprimir();">
        <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
      </div>    
      </div>					
    <?php } ?>													
  </div>							
</div>
      
<input id="page" name="page" type="hidden" value="">
  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
    $("#Aformulario").val("");
    $("#nempleado").val("");    
    $("#fecha").val("");
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