<?php
 /**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */
require_once __DIR__ .'/../classes/class_session.php';

if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;


// set page headers
$page_title = "Nuevo Detalles de horas";
include_once "header.php";


require_once '../common/fechas.php';   
require_once '../common/funcionesvarias.php';   


$codcliente=isset($_GET["codcliente"]) ? $_GET["codcliente"] : ''; 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('horas');

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

    if($paciente["estado"]=="ok"){
        //echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";  

        $codhorasestudio = '';
        $codhoraspaciente = '';
        $hace = _('Crea nuevo registro de horas ').$paciente['id'];
        
        logger($oidcontacto, $codhorasestudio, $codhoraspaciente, $hace);
                
        
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo "Error! No se pudieron guardar los cambios.";
        echo "</div>";
    }
  
    
} else {

 
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

}
?>
<div class="panel panel-default">
    	
        <div class="panel-heading">&nbsp;Detalles&nbsp;horas&nbsp;</div>
        <div class="panel-body">

    <form id="form" class="form-horizontal" action='../controlhoras/create.php' method='post'>
    <input type="hidden" name="DATA[codhoras]" id="codhoras" value="" >

<div class="form-group"> 
<div class="col-xs-5">
    <div class="form-group">
        <label class="control-label col-xs-12" for="usuario">Usuario:</label>
            <?php
            $objusuarios = new Consultas('usuarios');
                  
            $objusuarios->Select();
            $objusuarios->Where('codusuarios', $oidcontacto);
            $objusuarios->Where('borrado','0' );
            $usuarios = $objusuarios->Ejecutar();
            
            $total_usuarios=$usuarios["numfilas"];
            $rowusuarios = $usuarios["datos"][0]; 

            $nombre= $rowusuarios["nombre"].' '.$rowusuarios["apellido"];	

		    ?>	
            <input placeholder="Usuario" type="text" onfocus="this.select();" class="form-control input-sm" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  />
            <input name="DATA[codusuario]" type="hidden" id="codusuarios" readonly  value="<?php echo $oidcontacto;?>" />

    </div>
</div>
<div class="col-xs-1">
</div>
<div class="col-xs-6">
    <div class="form-group"> <!-- Name field -->
        <label class="control-label col-xs-12" for="cliente">Cliente:</label>  
                 
        <input placeholder="Cliente" class="form-control input-sm" type="text" onfocus="this.select();" class="cajaGrande" id="Acliente" size="45" maxlength="45" value="" onFocus="this.style.backgroundColor='#FFFF99'" required>
			<input name="DATA[codcliente]" type="hidden" id="Acodcliente" readonly value="" >
    </div>
</div>
</div>


<div class="form-group"> 
<div class="col-xs-12">

    <div class="form-group"> <!-- Name field -->
        <label class="control-label col-xs-2" for="cliente">Proyecto:</label>  
  
            <div class="col-xs-10">                 
        <input placeholder="Proyecto" class="form-control input-sm" type="text" onfocus="this.select();" class="cajaGrande" id="proyectos" size="45" maxlength="45" value="" onFocus="this.style.backgroundColor='#FFFF99'">
            <input name="DATA[codproyectos]" type="hidden" id="codproyectos" readonly value="" >
        </div>
    </div>
</div>
</div>

    <div class="form-group">
            <label class="control-label col-xs-2">Fecha</label>
            <div class="col-xs-5">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                <input placeholder="Fecha inicio" class="form-control input-sm" size="26" type="text" name="DATA[fecha]" id="fecha" value="" autocomplete="off" required autocomplete="false">
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div> 
            </div>

    </div>

    <div class="form-group">
        <label class="control-label col-xs-2">Hora&nbsp;inicio:</label>
        <div class="col-xs-3">
            <span style="display:inline-block;" class="time-input-container">
	        <input onblur="restarHoras();" class="time-input-field time-input is-timeEntry Sel_hora time" type="text" name="DATA[horaini]" id="horaini" value="" autocomplete="off"  placeholder="00:00" >
        	<span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click" ></span>
            </span>
        </div>
        <label class="control-label col-xs-1">Fin:</label>
        <div class="col-xs-3">
	        <span style="display:inline-block;" class="time-input-container">
	        <input onblur="restarHoras();" class="time-input-field time-input is-timeEntry  Sel_hora time"  type="text" name="DATA[horafin]" id="horafin" value="" autocomplete="off" placeholder="00:00" >
	        <span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click"></span>
            </span>
        </div>
        <div class="col-xs-3">
            <input id="horasminutos"  name="DATA[horas]" type="hidden">
            <select id="combohorasminutos" class="form-control input-sm" onchange="document.getElementById('horasminutos').value =this.value;">
            <?php
            //$horas;
            $h='';
            $h1='';
            $hora='';
            for ($i=0; $i<12; $i++)
            {
                if(strlen($i)==1){
                    $h.="0".$i;
                } else {
                    $h.=$i;
                }
                    
                for ($x=0; $x<=11; $x++) {
                    if ($x==0){
                    $h1.=":00";
                    } elseif($x==1) {
                    $h1.=":0". $x*5;
                    }else {
                    $h1.=":". $x*5;
                    }
                    if($hora==$h.$h1) {
                    ?>
                    <option value="<?php echo $h.$h1; ?>" selected><?php echo $h.$h1; ?></option>
                    <?php
                    }else {
                    ?>
                    <option value="<?php echo $h.$h1; ?>" ><?php echo $h.$h1; ?></option>
                    <?php
                    }
                    $h1='';
                }
                $h='';
            }
            ?>
            </select>			


        </div>
        </div>
        <div class="form-group">
                    
        <label class="control-label col-xs-2">Descripción:</label>
        <div class="col-xs-8">
            <textarea rows="3" class="form-control" id="descripcion" name="DATA[descripcion]" placeholder="Detalles"></textarea>
        </div>
    </div>

    </fieldset>				
				<div class="clearfix"></div>
            </div>
            </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
            <?php
        		$modificar=verificopermisos('ControlHoras', 'modificar', $UserID);
		        if ( $UserTpo == 100 or $modificar=="true") { ?>	            
                <input type="submit" class="btn btn-primary left-margin btn-xs" value="Guardar" onclick="validar();">
                <?php } ?>
                <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
                <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> Salir</button>
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
        widgetPositioning: {
	        horizontal: 'auto',
	        vertical: 'auto'
	        },
    });
</script>

<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="../library/bootstrap-clockpicker/bootstrap-clockpicker.min.css">

<!-- jQuery and Bootstrap scripts -->
<script type="text/javascript" src="../library/bootstrap/js/bootstrap.min.js"></script>

<!-- ClockPicker script -->
<script type="text/javascript" src="../library/bootstrap-clockpicker/bootstrap-clockpicker.js"></script>


<script type="text/javascript">

$('.Sel_hora').click(function(e){

    var input = $(this).clockpicker({
    placement: 'middle',
    align: 'left',
    autoclose: true,
    'default': 'now',

    });
    
    e.stopPropagation();
    input.clockpicker('show')
            .clockpicker();

});


</script>
<?php
include_once "../common/footer.php";
?>