<?php
// set page headers
$page_title = "Datos del Usuario";
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../../common/fechas.php';   
require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../../common/verificopermisos.php';   
require_once __DIR__ .'/../../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('biometric');
$datosguardados=0;
if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';
    $randomhash = RandomString(24);

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

    $obj->Insert($nombres, $valores);
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    $codusuarios = $paciente['id'];
    echo $paciente['consulta'];
    //////////////////////////Actualizo permisos
    
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;
 
       // echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
/*
        $obj = new Consultas('contactos');
        $obj->Select();
        $obj->Where(trim($attr), trim($valor));
        $paciente = $obj->Ejecutar();
        $paciente = $paciente["datos"][0];
        
        $dpto = new Consultas('departamentos');
        $dpto->Select();
        $departamento = $dpto->Ejecutar();
        $departamento = $departamento["datos"];   
        */  
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo "Error! No se pudieron guardar los cambios.";
        echo "</div>";
    }

$oidestudio = '';
$oidpaciente = '';
$hace = _('Alta equipo biometrico');

logger($UserID, $oidestudio, $oidpaciente, $hace);
    
} 
?>



<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados');?>.</div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del equipo');?></div>
        <?php } ?>

        <div class="panel-body">

<div id="exTab3" class="container-fluid">	


<div class="tab-content col-xs-12 ">

<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>
<div class="container-fluid">
<div class="row">


<form class="form-horizontal" action='create.php' method='post'>
<input type="hidden" name="DATA[codbiometric]" id="codbiometric" value="" >
<input type="hidden" name="codbiometric" value="" >
     <div class="form-group">
        <label class="control-label col-xs-2"><?php echo _('Nombre');?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="" placeholder="Nombre" required>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Principal');?></label>
        <div class="col-xs-2">

        <input type="hidden" name="DATA[internal_id]" value="1">
            
            <input type="checkbox" name="DATA[internal_id]" value="0" id="buscardentro" onclick="showWarningToast('Si cambia a activo, éste equipo será el principal para setear los datos de usuario');"> Si
                <span></span>            
                <script>
                $(function() {
                    $('#buscardentro').bootstrapToggle({
                    on: 'Si',
                    off: 'No ',
                    size: 'mini'
                    });
                })
                $(function() {
                    $('#buscardentro').change(function() {
                        if($('#valor').val()==1){
                            $('#valor').val('');
                        }else{
                            $('#valor').val(1);
                        }
                    });
                })  
                </script>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Ubicación');?></label>
        <div class="col-xs-3">
            <select id="codubicacion" name="DATA[codubicacion]" class="form-control input-sm" data-index="8">
            <option value="0">Todas las ubicaciones</option>
            <?php
            $obj = new Consultas('ubicaciones');
            $obj->Select();
            $obj->Where('borrado', '0', '=');

            $Eje = $obj->Ejecutar();
            $Eje_rows=$Eje["numfilas"];
            if($Eje_rows>0){
                $Ejerows = $Eje["datos"]; 
                foreach($Ejerows as $Ejerow){
                    echo "<option value=".$Ejerow['codubicacion']." >".$Ejerow['nombre']."</option>";
                }
            }
            ?>
        </select>
        </div>
    </div>
    <div class="form-group">        
        <label class="control-label col-xs-2"><?php echo _('Lugar');?></label>
        <div class="col-xs-2">
        <input type="text" size="30" name="DATA[lugar]" id="alugar" value=""  class="form-control input-sm"></input>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Dirección IP/WEB');?></label>
        <div class="col-xs-2">
        <input type="text" size="30" name="DATA[direccionip]" id="direccionip" value=""  class="form-control input-sm"></input>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Puerto UDP');?></label>
        <div class="col-xs-1">
        <input  type="text" size="30" name="DATA[udp_port]" id="udpport" value="" class="form-control input-sm" ></input>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Soap');?></label>
        <div class="col-xs-1">
        <input  type="text" size="30" name="DATA[soap_port]" id="soapport" value="" class="form-control input-sm" ></input>
        </div>

        <div class="col-xs-1">                
        <button class="boletin" onClick="event.preventDefault();consultar();">Consultar </button>
						

        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-2"><?php echo _('Com key');?></label>
        <div class="col-xs-2">
        <input type="text" size="30" name="DATA[com_key]" id="comkey" value="" class="form-control input-sm"></input>
        </div>
        <label class="control-label col-xs-1"><?php echo _('Encodding');?></label>
        <div class="col-xs-2">
        <input type="text" size="30" name="DATA[encoding]" id="encoding" value="" class="form-control input-sm"></input>
        </div>
        <label class="control-label col-xs-1"><?php echo _('Firmware');?></label>
        <div class="col-xs-2">
        <input type="text" size="30" name="DATA[firmware]" id="firmware" value="" class="form-control input-sm"></input>
        </div>
    </div>
      <div class="form-group row" >
        <div class="col-xs-8">

            <!-- ////////////////////////////////  -->
            <div class="form-group">
                <label class="control-label col-xs-2"><?php echo _('Número de serie');?></label>
                <div class="col-xs-4">
                <input type="text" size="30" name="DATA[serialnumber]" id="serialnumber" value="" class="form-control input-sm"></input>
                </div>
                <label class="control-label col-xs-2"><?php echo _('Plataforma');?></label>            
                <div class="col-xs-4">
                <input  type="text" size="30" name="DATA[plataform]" id="aplataform" value="" class="form-control input-sm"></input>
                </div> 
           
                <label class="control-label col-xs-2"><?php echo _('Device name');?></label>
                <div class="col-xs-4">
                <input type="text" size="30" name="DATA[devicename]" id="adevicename" value="" class="form-control input-sm" ></input>
                </div>

            </div>

            </div>
            <!-- ////////////////////////////////  -->            

        </div>

        
        </div>
      </div>



</div>

<div class="form-group">
    <div class="col-xs-offset-3 col-xs-9">
        <?php
        if(!$_POST)
        { ?>
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guardar');?>">
        <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
        <span class='glyphicon glyphicon-ban-circle' data-dismiss="modal"></span> <?php echo _('Salir');?></button>
    </div>
</div>
</div>

</div> <!--Fin 1b  *****************************************************************-->

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