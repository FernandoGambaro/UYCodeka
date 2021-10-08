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
	  echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
	  echo $s->log;
	  exit();
  }

$oidcontacto = $s->data['UserID'] ;


// set page headers
$page_title = _('Detalles de familias');
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not
$codfamilia = isset($_GET['codfamilia']) ? $_GET['codfamilia'] : die(_('ERROR! ID no encontrado!'));

require_once '../common/fechas.php';   
require_once '../common/funcionesvarias.php';   


require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

$obj = new Consultas('familias');

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
                        $valores[] =filter_var($item, FILTER_SANITIZE_STRING);
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
                                    $valores[] =filter_var($item, FILTER_SANITIZE_STRING);
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
                    $valores[] =filter_var($item, FILTER_SANITIZE_STRING);
                }
            }
        }
    }

    $obj->Update($nombres, $valores);
    $obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();

    if($paciente["estado"]=="ok"){

        $obj->Select();
        $obj->Where('codfamilia', $codfamilia);
        $paciente = $obj->Ejecutar();
        $paciente = $paciente["datos"][0];
    
        $codfamiliaestudio = '';
        $codfamiliapaciente = $codfamilia;
        $hace = _('Detalles de horas del usuario ').$paciente['codusuario'];
        
        logger($oidcontacto, $codfamiliaestudio, $codfamiliapaciente, $hace);
                
 
       echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";

 
     return false;
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _('Error! No se pudieron guardar los cambios.');
        echo "</div>";
    }
  
    
} else {

    $obj->Select();
    $obj->Where('codfamilia', $codfamilia);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente["datos"][0];

    $codfamiliaestudio = '';
    $codfamiliapaciente = $codfamilia;
    $hace = _('Detalles de horas del usuario').$paciente['codusuario'];
    
    logger($oidcontacto, $codfamiliaestudio, $codfamiliapaciente, $hace);
    

}
?>
<div class="panel panel-default">
    	
        <div class="panel-heading">&nbsp;Detalles&nbsp;horas&nbsp;</div>
        <div class="panel-body">

    <form id="form" class="form-horizontal" action='edit.php?codfamilia=<?php echo $codfamilia; ?>' method='post'>
    <input type="hidden" name="DATA[codfamilia]" id="codfamilia" value="<?php echo $paciente["codfamilia"];?>" >

<div class="form-group"> 
<div class="col-xs-12">

    <div class="form-group"> <!-- Name field -->
        <label class="control-label col-xs-2" for="cliente">Código:</label>  
            <div class="col-xs-10">                 
            <input readonly value="<?php echo $paciente["codfamilia"]; ?>" >
        </div>
    </div>
</div>
</div>

    <div class="form-group">
                
    <label class="control-label col-xs-2">Descripción:</label>
    <div class="col-xs-8">
        <textarea rows="3" class="form-control" id="nombre" name="DATA[nombre]" placeholder="Descripción"><?php echo $paciente["nombre"];?>   
        </textarea>
    </div>
    </div>

    </fieldset>				
				<div class="clearfix"></div>
            </div>
            </div>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
            <?php
        		$modificar=verificopermisos('FamiliasArticulos', 'modificar', $UserID);
		        if ( $UserTpo == 100 or $modificar=="true") { ?>	            
                <input type="submit" class="btn btn-primary left-margin btn-xs" value="Guardar" onclick="validar();">
                <?php } ?>
                <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
                <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> Salir</button>
            </div>
        </div>
    </form>             

<?php
include_once "../common/footer.php";
?>