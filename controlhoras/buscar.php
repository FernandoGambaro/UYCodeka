<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

if(trim($codcliente)!='' ) {
  $obj = new Consultas('clientes');
  
  $obj->Select();
  $obj->Where('codcliente', $codcliente);
  $paciente = $obj->Ejecutar();
  
  $total_rows=$paciente["numfilas"];
  $row = $paciente["datos"][0]; 

  $client=" para el cliente ". $row["nombre"].' '.$row["apellido"];
}else{
  $client='';
}

?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border">Búsqueda <?php echo $client;?></legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
<div class="col-xs-5">
  <div class="row" id="usuariocliente">
    <div class="col-xs-6">
    <?php
        $enable='';
        $nombre='';
        $usuario = $UserID;
            if(($UserTpo!=100 and $UserTpo!=2 ) and trim($usuario)!='') {
            $enable='readonly';
                                            
            $obj = new Consultas('usuarios');
            
            $obj->Select();
            $obj->Where('codusuarios', $usuario);
            $obj->Where('borrado','0' );
            $paciente = $obj->Ejecutar();
            
            $total_rows=$paciente["numfilas"];
            $row = $paciente["datos"][0]; 

            $nombre= $row["nombre"].' '.$row["apellido"];
          }else{
            $usuario='';
          }
        ?>	
      <input placeholder="Usuario" type="text" onfocus="this.select();" class="form-control input-sm" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  <?php echo $enable;?> />
      <input name="codusuario" type="hidden" id="codusuarios" readonly  value="<?php echo $usuario;?>" />

      </div>
      <div class="col-xs-6">
      <input placeholder="Cliente" class="form-control input-sm" type="text" onfocus="this.select();" class="cajaGrande" id="Acliente" size="45" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
			<input name="codcliente" type="hidden" id="Acodcliente" readonly value="<?php echo $codcliente;?>" >
      </div>
  </div>
  <div class="row">
      <div class="col-xs-6">

      </div>
  </div>
  <div class="row">
      <div class="col-xs-6">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="Fecha inicio" class="form-control input-sm" size="10" type="text" value="" name="fechaini" id="fechaini" readonly >
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div> 
      </div>
      <div class="col-xs-6">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2">
            <input placeholder="Fecha fin" class="form-control input-sm" size="10" type="text" value="" name="fechafin" id="fechafin" readonly>
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
<?php
	$leer=verificopermisos('reportes', 'leer', $UserID);
    if ( $UserTpo == 100 or $leer=="true") {
?>
<div class="col-xs-2">
    <div class="row">
      <fieldset><legend>&nbsp;Opciones de gráficos&nbsp;</legend>
						<select id="opcionesgrafico" class="form-control form-control-sm" >
						<option value="1">Anual - Clientes seleccionado</option>
						<option value="2">Anual - Usuario seleccionado</option>
						<option value="3">Anual - Usuario y Cliente seleccionado</option>
						<option value="4">Barras horas asignadas a clientes</option>
						</select>        
    </div>
    <div class="row">
    <button class="btn btn-primary btn-xs" onClick="grafico();">
    <i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp;Gráficar</button>
    </div>
</div>
<div class="col-xs-2">
    <div class="row">
      <fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
						<select id="opcionesprint" class="form-control form-control-sm" onchange="cambioseleccion();">
						<option value="1">Pendientes de registro</option>
						<option value="2">Usuarios, tareas realizadas</option>
						<option value="3">Clientes, tareas realizadas</option>
            <option value="4">Horas realizadas xUsuario xCliente</option>
            <option value="5">Horas real. xUsuario xCliente Fecha</option>
						</select>        
    </div>
    <div class="row">
    <label>A Excel?
  							<input id="opcionesaexcel" type="checkbox" checked="true" value="0">
  							<span></span>
						</label> &nbsp;     
    <button class="btn btn-primary btn-xs" onClick="imprimir();">
                <i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button>
    </div>    
</div>
<?php } ?>
<input id="page" name="page" type="hidden" value="">
  </form>
  
</fieldset>

<script>
  <?php 
  if(strlen($codcliente)>0){ ?>
  $("#usuariocliente").hide();

<?php }
  ?>
$("#cancel").click(function(){
  <?php
	$leer=verificopermisos('usuarios', 'leer', $UserID);
    if ( $UserTpo == 100 or $leer=="true") {
?>
    $("#Ausuarios").val("");
    $("#codusuarios").val("");
    <?php }
  ?>    
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