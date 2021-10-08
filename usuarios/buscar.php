<?php 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border"><?php echo _('Búsqueda');?></legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
      <div class="col-xs-6">
        <div class="row">
          <div class="col-xs-6">
            <input placeholder="Nombre" class="form-control input-sm" name="nombre" id="nombre" value="" type="text">
          </div>
          <div class="col-xs-6">
            <input placeholder="Apellido" class="form-control input-sm" name="apellido" id="apellido" value="" type="text">
          </div>
        </div>
        <div class="row">
          <div class="col-xs-3">
            <input placeholder="Teléfono" class="form-control input-sm" name="telefono" id="telefono" value="" type="text">
          </div>
          <div class="col-xs-3">
            <label>
              <input type="hidden" name="huella" id="huella" value="">
              <input type="checkbox" id="huellaBox" value="1" checked>
              <span></span>
            </label>
          </div>
          <div class="col-xs-3">
            <label>
              <input type="hidden" name="estado" id="estado" value="">
              <input type="checkbox" id="estadoBox" value="0" checked >
              <span></span>
            </label>
          </div>          
          <div class="col-xs-3">
          <select name="tratamiento" id="tratamiento" class="form-control">
              <?php 

                require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema
                echo "<option value=''>Seleccione uno</option>"; 
                
                if($UserTpo==100){
                  echo "<option value=".$UserTpo.">Good boy</option>"; 
                }
                foreach($tipo as $key => $value){
                  if($key>0){
                    if($key!=100){
                      echo "<option value=".$key.">".$value."</option>"; 
                    }
                  }
                }
              ?>
            </select> 
          </div>

        </div>
      </div>
      <div class="col-xs-6">

        <div class="col-xs-2">
            <div class="input-group-btn">
              <button type="reset" id="cancel" class="btn btn-default"><i class="glyphicon glyphicon-erase"></i></button>	
              <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        <div class="col-xs-10">
        <fieldset><legend>&nbsp;<?php echo _('Opciones de impresión'); ?>&nbsp;</legend>
          <div class="row">
            <div class="col-xs-4">
              <div class="row">
                <div class="col-xs-12">
                  <select id="opcionesimpresion" class="form-control" >
                  <option value="">Seleccione una opción.</option>
                  <option value="1">Según selección.</option>
                  <option value="2">Horario todo los usuarios</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                <label>Exportar a Excel?
                    <input id="opcionesaexcel" type="checkbox" value="0">
                    <span></span>
                </label>						                
                </div>
              </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                  <div class="col-xs-6" style="display:none" id="mesBox">
                  <select id="mes" class="form-control">
                  <option value="" selected>Mes</option>
                    <?php
                    for($x=1; $x<=12; $x++) {
                      if ($x<10) {
                      echo "<option value='0".$x."'>".mes($x)."</option>";
                      } else {
                      echo "<option value='".$x."'>".mes($x)."</option>";
                      }
                    }
                    ?>
                  </select>
                  </div>
                  <div class="col-xs-6" style="display:none" id="anioBox">
                    <select id="anio" class="form-control">
                      <option value="" selected>Año</option>
                        <?php
                        for($x=2015; $x<=2030; $x++) {
                          echo "<option value='".$x."'>".$x."</option>";
                        }
                        ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                <div class="col-xs-9">

                  </div>          
                  <div class="col-xs-3">
                      <button class="btn btn-primary btn-xs" onClick="imprimir();">
                      <i class="fa fa-print" aria-hidden="true"></i>&nbsp;<?php echo _('Imprimir'); ?></button>
                  </div>
                </div>
            </div>
            </div>

          </fieldset>
        </div>
        
        <input name="page" value="" type="hidden">

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
<script>
$(function() {
    $('#estadoBox').bootstrapToggle({
    on: 'Todos',
    off: 'No Activo',
    size: 'mini'
    });

    $('#estadoBox').change(function() {
      if($('#estado').val()==0){
        $('#estado').val(1);
      }else{
        $('#estado').val(0);
      }
    });
})
$(function() {
    $('#huellaBox').bootstrapToggle({
    on: 'Con/Sin Marcas',
    off: 'Con Marcas',
    size: 'mini'
    });

    $('#huellaBox').change(function() {
      if($('#huella').val()==1){
        $('#huella').val(0);
      }else{
        $('#huella').val(1);
      }
    });
})
</script>

</script> 
