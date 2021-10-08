<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border">Búsqueda</legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
<div class="col-xs-7">
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
              $nempleado=$row["nempleado"];
            $nombre= $row["nombre"].' '.$row["apellido"];
          }else{
            $usuario='';
          }
        ?>	
      <input placeholder="Usuario" type="text" onfocus="this.select();" class="form-control input-sm" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  <?php echo $enable;?> />

      </div>

      <?php
	$leer=verificopermisos('reportes', 'leer', $UserID);
    if ( $UserTpo == 100 or $UserTpo == 2 or $leer=="true") {
?>
      <div class="col-xs-3">
        <input id="ci" name="ci" type="text" class="form-control input-sm" maxlength="45" value="<?php echo $ci;?>" placeholder="C.I.">
      </div>
      <div class="col-xs-2">
        <input id="nempleado" name="nempleado" type="text" class="form-control input-sm" maxlength="45" value="<?php echo $empleado;?>" placeholder="Nº empleado">
      </div> 
<?php }else { ?>     
<input name="nempleado" type="hidden" id="nempleado" readonly  value="<?php echo $nempleado;?>" />
<?php } ?>     

</div>
  <div class="row">
      <div class="col-xs-3">
        <input id="mes" name="mes" type="text" class="form-control input-sm" maxlength="45" value="<?php echo $mes;?>" placeholder="Mes"> 
      </div>
      <div class="col-xs-3">
        <input id="anio" name="anio" type="text" class="form-control input-sm" maxlength="45" value="<?php echo $anio;?>" placeholder="Año">
      </div>
      <div class="col-xs-3">
        <select name="tipoliq" id="tipoliq" class="form-control input-sm">
          <option value="">Todos</option>
          <option value="Sueldos">Sueldos</option>
          <option value="Aguinaldo">Aguinaldo</option>
          <option value="IRPF">IRPF</option>
        </select>
        
      </div>
  </div>
</div>
<div class="col-xs-1">
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
      <fieldset><legend>&nbsp;Opciones de impresión&nbsp;</legend>
						<select id="opcionesprint" class="form-control form-control-sm" onchange="cambioseleccion();">
						<option value="1">Listado de recibos</option>
						<option value="2">Listado de recibos ordenados por CI</option>
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

<div class="col-xs-2">
    <div class="row">
    Para acceder a ver e imprimir su recibo de sueldo, hacer clic en la lupa (columna Acción) y luego dar clic en el botón imprimir.
    </div>
</div>
<input id="page" name="page" type="hidden" value="">

  </form>
  
</fieldset>

<script>

$("#cancel").click(function(){
    <?php
	    $leer=verificopermisos('recibodesueldos', 'leer', $UserID);
      if ( $UserTpo == 100 or $leer=="true") {
    ?>
    $("#Ausuarios").val("");
    $("#nempleado").val("");
    <?php } ?>   
    $("#fechaini").val("");
    $("#fechafin").val("");
    $("#page").val("");
    $("#tipoliq").val("");
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