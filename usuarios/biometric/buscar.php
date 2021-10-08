<?php 

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Búsqueda');?></legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
    <div class="col-xs-2">
        <input placeholder="Equipo" class="form-control input-sm" name="devicename" id="devicename" value="" type="text">
      </div>
      <div class="col-xs-5">
        <select name="codubicacion" id="codubicacion" class="from-control input-sm">
          <option value="" >Seleccione una</option>;
          <?php
          $obj = new Consultas('ubicaciones');
          $obj->Select();
          $obj->Where('borrado', '0', '=');

          $Eje = $obj->Ejecutar();
          $Eje_rows=$Eje["numfilas"];
          if($Eje_rows>0){
            $rows = $Eje["datos"]; 
            foreach($rows as $row){
            echo "<option value=".$row['codubicacion']." >".$row['nombre']."</option>";
            }
          }
          ?>
        </select>
                
      </div>
      <input name="page" value="" type="hidden">

      <div class="col-xs-1">
        <div class="input-group-btn">
          <button type="reset" id="cancel" class="btn btn-default"><i class="glyphicon glyphicon-erase"></i></button>	
          <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </div>
      </div>

    </div>
  </form>
  
</fieldset>

<script>
$("#cancel").click(function(){
    $("#devicename").val("");
    $("#tratamiento").val("");
    $('#form')[0].submit();
});
</script>
