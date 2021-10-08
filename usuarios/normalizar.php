<?php
// set page headers
$page_title = "Normalizar datos";
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not
$codusuarios = isset($_GET['oid']) ? $_GET['oid'] : die('ERROR! ID no encontrado!');

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../prueba/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';


$obj = new Consultas('contactos');

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
                        if($key=='contrasenia'){
                            $nombres[] = 'randomhash';
                            $valores[] = $randomhash;
                            $converter = new Encryption;
                            $valores[] = $converter->encode($item.$randomhash);
                            
                        }else{
                            $valores[] = $item;
                        }
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

    $obj->Update($nombres, $valores);
    $obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    //////////////////////////Actualizo permisos
    if($UserTpo==100){
    $permisos=$_POST['PERMISOS'];
    $controltask=array();
    if(is_array($permisos) && $permisos!=''){
        $oldtask="";
        $leer=0; $escribir=0; $modificar=0; $eliminar=0;
        //Recorro el array con la definición de los permisos			
        foreach ($permisos as $key )
        {
        $task=explode('_',$key);
            if ($oldtask=='' xor $task[0]==$oldtask) {
                //echo $task[0]." -> ".$task[1]."<br>";
                    if ($task[1]=="v") {
                    $leer = '1';
                    } elseif ($task[1]=="c") {
                    $escribir = '1';
                    } elseif ($task[1]=="m") {
                    $modificar = '1';
                    } elseif ($task[1]=="e") {
                    $eliminar = '1';
                    }
                    $oldtask=$task[0];
            } else {
                $controltask[$oldtask]=$oldtask;
                $taskName[$oldtask][]='leer';
                $taskValue[$oldtask][]=$leer;
                $taskName[$oldtask][]='escribir';
                $taskValue[$oldtask][]=$escribir;
                $taskName[$oldtask][]='modificar';
                $taskValue[$oldtask][]=$modificar;
                $taskName[$oldtask][]='eliminar';
                $taskValue[$oldtask][]=$eliminar;
                
                //echo $inserttask[$oldtask]."<br>";
                $leer=0; $escribir=0; $modificar=0; $eliminar=0;			
                //echo $task[0]." -> ".$task[1]."<br>";
                    if ($task[1]=="v") {
                    $leer = '1';
                    } elseif ($task[1]=="c") {
                    $escribir = '1';
                    } elseif ($task[1]=="m") {
                    $modificar = '1';
                    } elseif ($task[1]=="e") {
                    $eliminar = '1';
                    }
                $oldtask=$task[0];
            }
        }
        $controltask[$oldtask]=$oldtask;
        $taskName[$oldtask][]='leer';
        $taskValue[$oldtask][]=$leer;
        $taskName[$oldtask][]='escribir';
        $taskValue[$oldtask][]=$escribir;
        $taskName[$oldtask][]='modificar';
        $taskValue[$oldtask][]=$modificar;
        $taskName[$oldtask][]='eliminar';
        $taskValue[$oldtask][]=$eliminar;
    }
        //var_dump($taskName);
            foreach($controltask as $key) {
                $objpermisos = new Consultas('permisos');
                $objpermisos->Select('seccion');
                $objpermisos->Where('seccion', $key, 'LIKE', 'AND');
                $objpermisos->Where('codusuarios', $valor);
                $permisos = $objpermisos->Ejecutar();

                if($permisos['numfilas']>0) {
                    $objpermisos = new Consultas('permisos');
                    $objpermisos->Update($taskName[$key], $taskValue[$key]);
                    $objpermisos->Where('seccion', $key, 'LIKE', 'AND');
                    $objpermisos->Where('codusuarios', $valor);
                    $permisos = $objpermisos->Ejecutar();
        
                } else {
                //echo $inserttask[$key];
                $taskName[$key][]='seccion';
                $taskValue[$key][]=$key;            
                $taskName[$key][]='codusuarios';
                $taskValue[$key][]=$valor;            
                $objpermisos = new Consultas('permisos');            
                $objpermisos->Insert($taskName[$key], $taskValue[$key]);
                $permisos = $objpermisos->Ejecutar();
                }
            }
    }
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;
 
        //echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
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
  
$obj = new Consultas('contactos');
$obj->Select();
$obj->Where('oid', $codusuarios);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];

$dpto = new Consultas('departamentos');
$dpto->Select();
$departamento = $dpto->Ejecutar();
$departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = 'Modifica datos de'. $paciente["nombre"]." ".$paciente["apellido"];

logger($UserID, $oidestudio, $oidpaciente, $hace);

    
} else {

    $obj->Select();
    $obj->Where('oid', $codusuarios);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente["datos"][0];
    
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = 'Ve Contacto Usuarios'. $codusuarios;
$datosguardados=0;
logger($UserID, $oidestudio, $oidpaciente, $hace);


}
?>
<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success">Cambios guardados.</div>
        <?php }else{ ?>
        <div class="panel-heading">&nbsp;Normalizar&nbsp;datos</div>
        <?php } ?>

        <div class="panel-body">
<div class="container-fluid">

    <form class="form-horizontal" action='edit.php?oid=<?php echo $codusuarios; ?>' method='post'>
    <input type="hidden" name="DATA[oid]" id="oid" value="<?php echo $paciente["oid"];?>" >    
    <div class="form-group">
        <label class="control-label col-xs-1">Nombre:</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="<?php echo $paciente["nombre"];?>" placeholder="Nombre" required>
        </div>

    </div>

</div>
        <br>
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-9">
                <input type="submit" class="btn btn-primary left-margin btn-xs" value="Guarar">
                <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
                <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> Salir</button>
            </div>

        </div>
    </form>
    </div>
	<div class="clearfix"></div>
</div>

<?php
include_once "footer.php";
?>