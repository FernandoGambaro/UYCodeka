<?php
// set page headers
$page_title = _('Datos del Proyecto'); 
include_once "header-rejilla.php";

// isset() is a PHP function used to verify if ID is there or not
$codproyectos = isset($_GET['codproyectos']) ? $_GET['codproyectos'] : $_POST['codproyectos'];

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../../common/verificopermisos.php';   

$mensaje='';
$obj = new Consultas('proyectos');

if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';

    $DATA=$_POST['DATA'];
    $xpos=0;
    foreach ($DATA as $key => $item)
    {
        if (!is_array($DATA[$key])) {
            if($xpos==0){
              $attr = trim($key);
              $valor = trim($item); 
              $xpos++; 
            } else {
                if($item!=''){
                    if(strpos($item, '/')>0){
                        $valores[] = explota($item);
                    } else {
                        $valores[] = $item;
                    }
                    $nombres[] = $key;
                }
                if($key=='codproyectos'){
                    $codproyectos=$item;
                }
            }
        } else {
            for ( $i=0; $i < count($DATA[$key]); $i++ )
            {
                if($xpos==0){
                    $attr = trim($key);
                    $valor = trim($item); 
                    $xpos++; 
                } else {
                    if ( !empty($DATA[$key][$i]) )  {
                        if($item!=''){
                        $nombres[] = $key;
                            if(strpos($item, '/')>0){
                                $valores[] = explota($item);
                            } else {
                                $valores[] = $item;
                            }
                        }
                    }
                    if($key=='codproyectos'){
                        $codproyectos=$item;
                    }        
                }
            }
            if($item!='') {
            $nombres[] = $key;
                if(strpos($item, '/')>0){
                    $valores[] = explota($item);
                } else {
                    $valores[] = $item;
                }
            }
        }
    }

    $obj->Update($nombres, $valores);
    $obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;
 
        echo "<script>parent.document.getElementById('frame_proyectos').contentDocument.location.reload(true);
        parent.$('idOfDomElement').colorbox.close();</script>";

        $objpaciente = new Consultas('proyectos');
        $objpaciente->Select();
        $objpaciente->Where(trim($attr), trim($valor));
        $paciente = $objpaciente->Ejecutar();
        $paciente = $paciente["datos"][0];
  
          
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }
  
$oidestudio = '';
$oidpaciente = '';
$hace = _('Modifica datos del proyecto '). $paciente["descripcion"];
logger($UserID, $oidestudio, $oidpaciente, $hace);

    
}else{
$obj = new Consultas('proyectos');
$obj->Select();
$obj->Where('codproyectos', $codproyectos);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve datos del proyecto '). $paciente["nombre"];

logger($UserID, $oidestudio, $oidpaciente, $hace);



}
?>
<style>
.btn,.input-group-addon {
    min-width: 47px;
}
.toggle-on.btn-mini {
    padding-right: 66px;
}
.panel-body{
    height: auto;
}
</style>

<div class="panel panel-default">
        <div class="panel-heading"><?php echo _('Datos del proyecto'); ?></div>
        <div class="panel-body">

<div id="exTab3" class="container">	


<div class="tab-content col-xs-12 ">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>



<form class="form-horizontal" action='edit.php' method='post'>
    <input type="hidden" name="DATA[codproyectos]" id="codproyectos" value="<?php echo $paciente["codproyectos"];?>" >
    <input type="hidden" name="codproyectos" value="<?php echo $paciente["codproyectos"];?>" >
    <div class="row">
      <div class="col-xs-6">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="<?php echo _('Fecha inicio'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo $paciente["fechaini"];?>" name="DATA[fechaini]" id="fechaini" readonly  required>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div> 
      </div>
      <div class="col-xs-6">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="<?php echo _('Fecha fin'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo $paciente["fechafin"];?>" name="DATA[fechafin]" id="fechafin" readonly  required>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
        </div> 
      </div>
  </div>
 
  <div class="row">        
        <label class="control-label col-xs-2"><?php echo _('Descripción'); ?></label>
        <div class="col-xs-10">
        <input type="text" class="form-control input-sm" id="descripcion" name="DATA[descripcion]" value="<?php echo $paciente["descripcion"];?>" placeholder="<?php echo _('Descripcion'); ?>"> 
        </div>
    </div>


    <div class="row">
    <label class="control-label col-xs-2"><?php echo _('Estado'); ?></label>
        <div class="col-xs-3">
                <input name="DATA[borrado]" id="borrado" value="<?php echo $paciente["borrado"];?>" type="hidden" />
                <select id="Pregunta" type="text" onchange="document.getElementById('borrado').value=this.value;" class="form-control input-sm" >
                <?php
                    $questions = array();
                    $questions[0] = _("Activo");
                    $questions[1] = _("Borrado");
                $xx=0;
                foreach($questions as $pregunta) {
                    if ($xx==$paciente["borrado"]) {
                        echo "<option value='$xx' selected>$pregunta</option>";
                    } else {
                        echo "<option value='$xx'>$pregunta</option>";
                    }
                $xx++;
                }
                ?>
                </select>
        </div>
        <div class="col-xs-7">
        </div>
    </div>
            

    </div>

            <!-- ////////////////////////////////  -->            


</div>			
</div><!-- Fin  -->


	<div class="clearfix"></div>
</div>
<div class="row">
    <div class="col-xs-12 mx-auto">
    <div class="text-center">
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guarar'); ?>">
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
        <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> <?php echo _('Salir'); ?></button>
    </div>
    </div>
</div>
</form>

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

<?php
include_once "../../common/footer.php";
?>