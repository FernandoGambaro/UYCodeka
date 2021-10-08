<?php 


?>
  <fieldset class="scheduler-border">
  <legend class="scheduler-border">Búsqueda </legend>

  <form name="form" id="form" action="rejilla.php" target="frame_rejilla" method="POST">
    <div class="input-group input-group-sm">
<div class="col-xs-5">
  <div class="row" >
    <div class="col-xs-6">
 
      <input placeholder="Descripción" type="text" class="form-control input-sm" id="descripcion" size="45" value="" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  />

      </div>
      <div class="col-xs-6">
      <input placeholder="Código" class="form-control input-sm" type="text" id="codigo" size="45" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'">
      </div>
  </div>
  <div class="row">
      <div class="col-xs-6">

      </div>
  </div>

</div>
<div class="col-xs-2">
<div class="input-group-btn">
        <button type="reset" id="cancel" class="btn btn-default" onclick='$(this).trigger("reset");'><i class="glyphicon glyphicon-erase"></i></button>	
        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
      </div>
</div>

<input id="page" name="page" type="hidden" value="">
  </form>
  
</fieldset>

<script>

$("#cancel").click(function(){
    $("#descripcion").val("");
    $("#codigo").val("");
  
    $("#page").val("");
    $('#form')[0].submit();
});
</script>