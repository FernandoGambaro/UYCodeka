<?php
// set page headers
$page_title = "Datos del Usuario";
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not
$codusuarios = isset($_GET['codusuarios']) ? $_GET['codusuarios'] : $_POST['codusuarios'];

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('usuarios');

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
                }elseif($key=='huella'){
                    $nombres[] = $key;
                    $valores[] = '0';
                    $nombres[] = 'pin';
                    $valores[] = ' ';
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
                        } elseif($key=='huella'){
                            $nombres[] = $key;
                            $valores[] = '0';
                            $nombres[] = 'pin';
                            $valores[] = ' ';
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
  //echo $paciente['consulta'];
    //////////////////////////Actualizo permisos
    if($UserTpo==100 or $UserTpo==2){
    $permisos=$_POST['PERMISOS'];
    $controltask=array();
    if(is_array($permisos) && $permisos!=''){
        $oldtask="";
        $leer=0; $escribir=0; $modific=0; $eliminar=0;
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
                    $modific = '1';
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
                $taskValue[$oldtask][]=$modific;
                $taskName[$oldtask][]='eliminar';
                $taskValue[$oldtask][]=$eliminar;
                
                //echo $inserttask[$oldtask]."<br>";
                $leer=0; $escribir=0; $modific=0; $eliminar=0;			
                //echo $task[0]." -> ".$task[1]."<br>";
                    if ($task[1]=="v") {
                    $leer = '1';
                    } elseif ($task[1]=="c") {
                    $escribir = '1';
                    } elseif ($task[1]=="m") {
                    $modific = '1';
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
        $taskValue[$oldtask][]=$modific;
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
 
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo "Error! No se pudieron guardar los cambios.";
        echo "</div>";
    }
  
    
$obj = new Consultas('usuarios');
$obj->Select();
$obj->Where('codusuarios', $codusuarios);
$pac = $obj->Ejecutar();
$paciente = $pac["datos"][0];

if($paciente['huella']==1 and $paciente['estado']==1){
    $objhuella = new Consultas('biometricuser');
    $objhuella->Select();	
    $objhuella->Where('codusuarios', $codusuarios, '=');
    $pachuella = $objhuella->Ejecutar();
    $row=$pachuella['datos'][0];
    $filas=$pachuella['numfilas'];;
    if($filas==0) {
        $nombres = array();
        $valores = array();
        $nombres[] = 'codusuarios';
        $valores[] = $codusuarios;
        $nombres[] = 'name';
        $valores[] = $paciente['nombre'].' '.$paciente['apellido'];
        $nombres[] = 'role';
        $valores[] = isset($paciente['role']) ? $paciente['role'] : '0';

        $objnew = new Consultas('biometricuser');
        $objnew->Insert($nombres, $valores);
        $ejecutado=$objnew->Ejecutar();
        //echo 'inserto nuevo usuario<br>'. $ejecutado['consulta'];
    } else {
        $nombres = array();
        $valores = array();
        $nombres[] = 'name';
        $valores[] = $paciente['nombre'].' '.$paciente['apellido'];

        $nombres[] = 'role';
        $valores[] = isset($paciente['role']) ? $paciente['role'] : '0';
        $nombres[] = 'borrado';
        $valores[] = '0';
                    
        $objnew = new Consultas('biometricuser');
        $objnew->Update($nombres, $valores);
        $objnew->Where('codusuarios', $codusuarios, '=');
        $ejecutado=$objnew->Ejecutar();
        //echo 'Actualizo usuario existente<br>'. $ejecutado['consulta'];
    }
}elseif($paciente['role']!=14){
    $nombres = array();
    $valores = array();
    $nombres[] = 'borrado';
    $valores[] = '1';

    $nombres[] = 'password';
    $valores[] = ' ';


    $objnew = new Consultas('biometricuser');
    $objnew->Update($nombres, $valores);
    $objnew->Where('codusuarios', $codusuarios, '=');

    $ejecutado=$objnew->Ejecutar();
   // echo '    borrado<br>'.$ejecutado['consulta'];
}


$dpto = new Consultas('departamentos');
$dpto->Select();
$departamento = $dpto->Ejecutar();
$departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Modifica datos de '). $paciente["nombre"]." ".$paciente["apellido"];

logger($UserID, $oidestudio, $oidpaciente, $hace);

    
} else {

    $obj->Select();
    $obj->Where('codusuarios', $codusuarios);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente["datos"][0];

    //echo $paciente['consulta'];
    //echo '<br>huella '.$paciente['huella'];

    
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve Contacto Usuarios '). $codusuarios;
$datosguardados=0;
logger($UserID, $oidestudio, $oidpaciente, $hace);


}
?>


<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados');?>.</div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del Usuario');?></div>
        <?php } ?>

        <div class="panel-body" style="min-height: 390px;">

    <form class="form-horizontal" action='edit.php' method='post'>
    <input type="hidden" name="DATA[codusuarios]" id="codusuarios" value="<?php echo $paciente["codusuarios"];?>" >
    <input type="hidden" name="codusuarios" id="Acodusuarios" value="<?php echo $paciente["codusuarios"];?>" >


<div id="exTab3" class="container-fluid">	

<ul class="nav nav-tabs nav-pills tabs">
<li class="active"><a href="#1b" data-toggle="tab"><?php echo _('Datos Generales');?></a></li>
<li><a href="#2b" data-toggle="tab">Horarios</a></li>
<li><a href="#3b" data-toggle="tab">Licencia/Asuetos</a></li>
</ul>

<div class="tab-content col-xs-12 ">
<div class="tab-pane fade in active" id="1b">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>
<div class="container-fluid">
<div class="row">

    <div class="form-group">
    <div class="row">
        <div class="col-xs-8">
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Tratamiento');?></label>
        <div class="col-xs-2">
        <input  name="DATA[tratamiento]" id="tratamiento" value="" type="hidden" />
        <?php 
            $modificar=verificopermisos('Usuarios', 'modificar', $UserID);
            if($UserTpo==100 or $modificar=='true'){ ?>
                <select  type="text" size="1"  id="Atratamiento" class="form-control input-sm" onchange="document.getElementById('tratamiento').value =this.value;" required>					
            <?php }else{ ?>
                <select type="text" size="1" class="form-control input-sm" disabled>					
            <?php } ?>
            <?php

            $xx = 0;
            if($UserTpo==100){
                echo "<option value=".$UserTpo.">Good boy</option>"; 
            }
            foreach($tipo as $key => $value){
                if($key!=100){
                    if ($key == $paciente["tratamiento"]) {
                        echo "<option value='".$key."' selected>".$value."</option>"; 
                    } else {
                        echo "<option value='".$key."'>".$value."</option>"; 
                    }

                }
            }            

            ?>
            </select>       
        </div>
        <div class="col-xs-1"></div>
        <?php if($UserTpo==100 or $UserTpo==2){ ?>
        <label class="control-label col-xs-2"><?php echo _('Estado');?></label>
        <div class="col-xs-2">
        <input type="hidden" name="DATA[estado]" value="1">
            <label>
            <?php
            $estado=$paciente["estado"];
                if ( $estado=='' or $estado==0) {
            ?>
            <input type="checkbox" name="DATA[estado]" value="0" checked id="buscardentro">
            <?php } else {
            ?>
            <input type="checkbox" name="DATA[estado]" value="0" id="buscardentro">
            <?php }
            ?>
                <span></span>
            </label>

        </div>
        <script>
        $(function() {
            $('#buscardentro').bootstrapToggle({
            on: 'Activo',
            off: 'No Activo',
            size: 'mini'
            });
        })
  
        </script>  
        <?php } ?>
            <label class="control-label col-xs-1"><?php echo _('Inicio');?></label>
            <div class="col-xs-2">
            <input name="DATA[initialpage]" id="initialpage" value="<?php echo $paciente["initialpage"]?>" hidden >
            <?php 
                $page= $paciente["initialpage"];
                $op_page = array();
                $op_page['0'] = _('Seleccione uno');
                $op_page['./controlhoras/index.php'] = _('Control de horas');
                $op_page['./usuarios/cambiopass.php'] = _('Cambio contraseña');
            ?>
                <select type="text" class="form-control input-sm" onchange="document.getElementById('initialpage').value =this.value;">
                <?php 
                foreach($op_page as $key=>$opt) {
                    if ($key==$page) {
                        echo "<option value='$key' selected>$opt</option>";
                    } else {
                        echo "<option value='$key'>$opt</option>";
                    }
                }
                ?>						
                </select> 
            </div>
        </div>

        <div class="row">
            <label class="control-label col-xs-2"><?php echo _('Nombre');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="<?php echo $paciente["nombre"];?>" placeholder="Nombre" required>
            </div>

            <label class="control-label col-xs-2"><?php echo _('Apellido');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="apellido" name="DATA[apellido]" value="<?php echo $paciente["apellido"];?>" placeholder="Apellido"> 
            </div>
            <label class="control-label col-xs-2"><?php echo _('Teléfono');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="telefono" name="DATA[telefono]" value="<?php echo $paciente["telefono"];?>" placeholder="Teléfono">
            </div>
        </div>
        <div class="row">
            <label class="control-label col-xs-2"><?php echo _('Celular');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="celular" name="DATA[celular]" value="<?php echo $paciente["celular"];?>" placeholder="Celular"> 
            </div>
            <label class="control-label col-xs-2"><?php echo _('Nº Documento');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="ci" name="DATA[ci]" value="<?php echo $paciente["ci"];?>" placeholder="Documento"> 
            </div>

            <label class="control-label col-xs-2"><?php echo _('Nº empleado');?></label>
            <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="empleado" name="DATA[empleado]" value="<?php echo $paciente["empleado"];?>" placeholder="Número empleado"> 
            </div>
        </div>

    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Medio pago');?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="mediopago" name="DATA[mediopago]" value="<?php echo $paciente["mediopago"];?>" placeholder="Medio Pago"> 
        </div>
        <label class="control-label col-xs-2"><?php echo _('Nº Cta.');?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="cta" name="DATA[cta]" value="<?php echo $paciente["cta"];?>" placeholder="Nº cuenta"> 
        </div>
        <label class="control-label col-xs-2"><?php echo _('eMail');?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="email" name="DATA[email]" value="<?php echo $paciente["email"];?>" placeholder="Email">
        </div>
    </div>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Dirección');?></label>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="direccion" name="DATA[direccion]" value="<?php echo $paciente["direccion"];?>" placeholder="Dirección"> 
        </div>
                <label class="control-label col-xs-2"><?php echo _('Departamento');?></label>            
                <div class="col-xs-2">
                <select name="DATA[departamento]" class="form-control input-sm">
                <?php
                foreach ($departamento as $key) {
                    
                    if ($key["departamentosid"] == $paciente["departamento"]) {
                        echo "<option value='".$key["departamentosid"]."' selected>".$key["departamentosdesc"]."</option>";
                    } else {
                        echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
                    }
                    $xx++;
                }
                ?>            
                </select>
                </div> 
            </div>

            <div class="row">
                <label class="control-label col-xs-2"><?php echo _('Usuario');?></label>
                <div class="col-xs-4">
                    <input type="text" class="form-control input-sm" id="usuario" name="DATA[usuario]" value="<?php echo $paciente["usuario"];?>" placeholder="Usuario"> 
                </div>

                <label class="control-label col-xs-2"><?php echo _('Password');?></label>
                <div class="col-xs-4">
                    <input type="text" class="form-control input-sm" id="contrasenia" name="DATA[contrasenia]" value="" placeholder="Contraseña"> 
                </div>
            </div>

            <div class="row">
                <label class="control-label col-xs-2"><?php echo _('Pregunta');?></label>            
                <div class="col-xs-4">
						<input name="DATA[secQ]" id="secQ" value="<?php echo $paciente["secQ"];?>" type="hidden" />
						<select id="Pregunta" type="text" onchange="document.getElementById('secQ').value=this.value;" class="form-control input-sm" >
						<?php
							$questions = array();
							$questions[0] = _('Seleccione uno');
							$questions[1] = _('¿En que ciudad nació?');
							$questions[2] = _('¿Cúal es su color favorito?');
							$questions[3] = _('¿En qué año se graduo de la facultad?');
							$questions[4] = _('¿Cual es el segundo nombre de su novio/novia/marido/esposa?');
							$questions[5] = _('¿Cúal es su auto favorito?');
							$questions[6] = _('¿Cúal es el nombre de su madre?');
						$xx=0;
						foreach($questions as $pregunta) {
						   if ($xx==$paciente["secQ"]) {
						      echo "<option value='$xx' selected>$pregunta</option>";
						   } else {
						      echo "<option value='$xx'>$pregunta</option>";
						   }
						$xx++;
						}
						?>
						</select>
                </div>
                <label class="control-label col-xs-2"><?php echo _('Respuesta');?></label>
                <div class="col-xs-4">
                    <input type="text" name="DATA[secA]" id="secA" value="<?php echo $paciente["secA"];?>" class="form-control input-sm" >
                </div>                 
            </div>

            <div class="row">           
            <label class="control-label col-xs-2"><?php echo _('Con huella');?></label>
                <div class="col-xs-1">        
                <input type="hidden" name="DATA[huella]" value="<?php echo $paciente["huella"];?>" id="huellaaux" >            
                    <?php
                        if ( $paciente["huella"]==1) {
                            $huellack='checked';
                        }else{
                            $huellack='';
                        }
                    ?>
                    <label><input type="checkbox" name="DATA[huella]" value="1" <?php echo $huellack;?> id="huella" ><span></span></label>
                    <script>
                    $(function() {
                        $('#huella').bootstrapToggle({
                        on: 'Si',
                        off: 'No',
                        size: 'mini'
                        });
                    });
                    
                    $(function() {

                        //$('#huella').prop('checked', true).change();
                        $('#huella').change(function() {
                            //console.log($('#huella').prop('checked')+$('#huella').val());
                            if($('#huella').prop('checked')){
                                $('#huellaaux').val('1');
                            }else{
                                $('#huellaaux').val('0');
                            }
                            //console.log($('#huellaaux').val());
                        });
                    })
                    </script>
                </div>
                <label class="control-label col-xs-1"><?php echo _('Nº');?></label>
                <div class="col-xs-1">                    
                        <input type="text" size="10" value="<?php echo $paciente["pin"]?>" class="form-control input-sm" disabled>
                </div>
					<label class="control-label col-xs-1"><?php echo _('Rol');?></label>
                    <div class="col-xs-3">
                    <input name="DATA[role]" id="rol" value="<?php echo $paciente["role"]?>" hidden >
                    <?php 
						//$modificar=verificopermisos('Usuarios', 'modificar', $UserID);
                        $role= $paciente["role"];
                        $opciones = array();
                        $opciones[0] = _('Nivel Usuario');
                        if($modificar=='true') {
                        $opciones[14] = _('Nivel Super Manager');
                        }                    
                    ?>
						<select id="role" type="text" class="form-control input-sm" onchange="document.getElementById('rol').value =this.value;">
						<?php 
						foreach($opciones as $key=>$opt) {
						   if ($key==$role) {
						      echo "<option value='$key' selected>$opt</option>";
						   } else {
						      echo "<option value='$key'>$opt</option>";
						   }
						}
						?>						
                        </select> 
                    </div>
            </div>
            </div>

          <!-- ////////////////////////////////  -->            

        <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-12">
        <fieldset  style="height: 300px;">
            <legend > &nbsp;<?php echo _('PERMISOS');?>&nbsp;</legend>
        <?php
            $modificar=verificopermisos('Usuarios', 'modificar', $UserID);
            //$modificar='true';
            if($modificar=='true') {
        ?>						
            <div style="height: 180px; width:100%; overflow:disable; top:-35px;">
                    
                        

<nav class="navbar navbar-default">
<div class="header-inner">
		<div class="container">
				<div class="row">
						<div class="col-md-12">
								<nav  id="nav-wrap" class="main-nav">
                                
                    <div class="col-xs-3">
                        &nbsp;
                    </div>
                    <div class="col-xs-2"><input type="checkbox" id="select_ver" class="Select_All" data-size="mini" data-toggle="toggle" data-on="Ver" data-off="Ver">
                    </div>
                    <div class="col-xs-2">
                        <input type="checkbox" id="select_crear" data-size="mini" data-toggle="toggle" data-on="Crear" data-off="Crear" class="Select_All">
                    </div>
                    <div class="col-xs-2">
                        <input type="checkbox" id="select_mod" data-size="mini" data-toggle="toggle" data-on="Mod." data-off="Mod." class="Select_All">
                    </div>
                    <div class="col-xs-2">
                        <input type="checkbox" id="select_del" data-size="mini" data-toggle="toggle" data-on="Elim." data-off="Elim."  class="Select_All">
                    </div>
               
								</nav> </div>
				</div>
		</div>
</div>
</nav>
<div style="height: 200px; width:100%; overflow:auto;">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                    <script>
                    $(function() {
                        $('.Select_All').change(function() {
                            var id="."+this.id;
                            if(this.checked){
                                $(id).each(function(){
                                    this.checked = true;
                                });
                            }else{
                                $(id).each(function(){
                                    this.checked = false;
                                });
                            }
                        });
                    })  
                    </script> 
                                    <?php 
                                    foreach($sectores as $xsector) {
                                    ?>
                                    <div class="row">
                                    <label class="control-label col-xs-4"><?php echo ucwords($xsector);?></label>
                                    <?php
                                    if(strpos($xsector, ' ')!==false){
                                        $xsector=str_replace(' ', '', $xsector);
                                    }
                                    $leer=''; $escribir=''; $modi=''; $eliminar='';
                                    $objpermisos = new Consultas('permisos');
                                    $objpermisos->Select('*');
                                    $objpermisos->Where('seccion', trim($xsector), '=', 'AND'); 
                                    $objpermisos->Where('codusuarios', trim($codusuarios)); 
                                    $permisos = $objpermisos->Ejecutar();
                                    //echo $permisos["consulta"];
                                    //echo $permisos["datos"][0]['leer'];
                                    if($permisos["estado"]=="ok"){							
                                        if ( $permisos["numfilas"] > 0 ) {
                                            if ($permisos["datos"][0]['leer']==1) {
                                                $leer="checked";
                                            }
                                            if ($permisos["datos"][0]['escribir']==1) {
                                                $escribir="checked";                                              
                                            }
                                            if ($permisos["datos"][0]['modificar']==1) {
                                                $modi="checked";
                                            }
                                            if ($permisos["datos"][0]['eliminar']==1) {
                                                $eliminar="checked";
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="col-xs-2"><input type="hidden" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_0">
                                    <label><input class="checkbox1 select_ver" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_v" <?php echo $leer;?>><span></span></label></div>
                                    <div class="col-xs-2"><label><input class="checkbox1 select_crear" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_c]" value="<?php echo $xsector;?>_c" <?php echo $escribir;?>><span></span></label></div>
                                    <div class="col-xs-2"><label><input class="checkbox1 select_mod" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_m]" value="<?php echo $xsector;?>_m" <?php echo $modi;?>><span></span></label></div>
                                    <div class="col-xs-2"><label><input class="checkbox1 select_del" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_e]" value="<?php echo $xsector;?>_e" <?php echo $eliminar;?>><span></span></label></div>
                                    </div>
                                    <?php } ?>
                            </div>
                            </div>
                        </div>
                    </div>
                
            </div>
        <?php } ?>
            </fieldset>
            </div>
        </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        var $ = jQuery.noConflict();

$(document).ready(function() {

 		var header = $(".header-inner");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 10 ) {
            header.addClass("navbar-fixed-top");
        } else {
            header.removeClass('navbar-fixed-top');
        }
    });	  

});  
 
</script>

</div>
</div> <!--Fin 1b  *****************************************************************-->
<div class="tab-pane" id="2b">
<div class="container-fluid">
<div class="row">
<div class="col-xs-6">
       
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Nuevo horario '); ?></legend>

    <div class="form-group">
    <div class="row">
        <label class="control-label col-xs-2">Hora&nbsp;ingreso:</label>
        <div class="col-xs-3">
            <div class="input-group Sel_hora" data-placement="left" data-align="top" data-autoclose="true">
                <input type="text" class="form-control"  name="horaingreso" id="horaingreso" value="" autocomplete="off"  placeholder="00:00">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
        <label class="control-label col-xs-2">Hora salida:</label>
        <div class="col-xs-3">
            <div class="input-group Sel_hora" data-placement="left" data-align="top" data-autoclose="true">
                <input type="text" class="form-control"  name="horasalida" id="horasalida" value="" autocomplete="off"  placeholder="00:00">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-time"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Descanso'); ?></label>
        <div class="col-xs-3">
        <input id="descanso" name="descanso" type="hidden">
            <select id="combodescanso" class="form-control input-sm" onchange="document.getElementById('descanso').value =this.value;">
            <?php
            //$horas;
            $h='';
            $h1='';
            for ($i=0; $i<3; $i++)
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

                    ?>
                    <option value="<?php echo $h.$h1; ?>" ><?php echo $h.$h1; ?></option>
                    <?php
                    $h1='';
                }
                $h='';
            }
            ?>
            </select>			
        </div>
            <label class="control-label col-xs-2"><?php echo _('Vigencia'); ?></label>
            <div class="col-xs-4">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input placeholder="<?php echo _('Fecha vigencia'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo date("d/m/Y");?>" id="vigencia" readonly  required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>   			
            </div>
    </div>
    </div>


        <div class="row">
  
            <div class="col-xs-12" id="SelectDiasAsignados">
            <fieldset><legend>Días asignados para este horario</legend>
                <div class="row">
                <div class="col-xs-10">
                <div class="form-group">
                    <input class="form-control dias-semana" id="diasemana_horario" type="text" value="12345" data-bind="value: WorkWeek">
                </div>
                </div>
                </div>
            </fieldset>
            </div>            
        </div>
        
        <div class="row">
            <div class="col-xs-6" >
                <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();guardohorario();"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
            </div>
            <div class="col-xs-2">
                <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();limpiarhorario();"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
            </div>
        </div>

</fieldset>

</div>
<div class="col-xs-6">

<div class="row">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listado de horas asignadas '); ?></legend>
    <iframe src="horario/rejilla.php?codusuarios=<?php echo $codusuarios;?>" width="98%" height="260" id="frame_horario" name="frame_horario" frameborder="0" scrolling="no" >
    <ilayer width="98%" height="260" id="frame_horario" name="frame_horario"></ilayer>
    </iframe>
    <iframe id="frame_datos_horario" name="frame_datos_horario" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos_horario" name="frame_datos_horario"></ilayer>
    </iframe>
</div>

</div>

</div>
</div> 
</div> <!-- fin 2b **************************************************-->

<div class="tab-pane" id="3b">
<div class="container-fluid">
<div class="row">
    <div class="col-xs-6">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo _('Nuevo Licencia/Asueto '); ?></legend>

        <div class="form-group">
        <div class="row">
            <label class="control-label col-xs-1"><?php echo _('Desde'); ?></label>
            <div class="col-xs-4">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input placeholder="<?php echo _('Desde'); ?>" class="form-control input-sm" size="26" type="text" value="" id="desde" readonly  required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>   			
            </div>
            <label class="control-label col-xs-1"><?php echo _('hasta'); ?></label>
                <div class="col-xs-4">
                    <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                        <input placeholder="<?php echo _('Hasta'); ?>" class="form-control input-sm" size="26" type="text" value="" id="hasta" readonly  required>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>   			
                </div>
        </div>

        </div>
        
            <div class="row">
                <div class="col-xs-2" >
                    <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();guardolicencia();"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
                </div>
                <div class="col-xs-2">
                    <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();limpiarlicencia();"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
                </div>
            </div>

    </fieldset>

    </div>
    <div class="col-xs-6">

    <div class="row">
        <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo _('Listado de licencias/Asueto '); ?></legend>
        <iframe src="licencia/rejilla.php?codusuarios=<?php echo $codusuarios;?>" width="98%" height="260" id="frame_licencia" name="frame_licencia" frameborder="0" scrolling="no" >
        <ilayer width="98%" height="260" id="frame_licencia" name="frame_licencia"></ilayer>
        </iframe>
        <iframe id="frame_datos_licencia" name="frame_datos_licencia" width="0" height="0" frameborder="0">
        <ilayer width="0" height="0" id="frame_datos_licencia" name="frame_datos_licencia"></ilayer>
        </iframe>
    </div>

    </div>

 
</div> 
</div>
</div> <!-- fin 3b **************************************************-->


</div>			
</div><!-- Fin  -->

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

<script type="text/javascript" >
(function( $ ){

"use strict";

$.fn.daysOfWeekInput = function() {
  return this.each(function(){
    var $field = $(this);
    
    var days = [
      {
        Name: 'Domingo',
        Value: '0',
        Checked: false
      },
      {
        Name: 'Lunes',
        Value: '1',
        Checked: false
      },
      {
        Name: 'Martes',
        Value: '2',
        Checked: false
      },
      {
        Name: 'Miercoles',
        Value: '3',
        Checked: false
      },
      {
        Name: 'Jueves',
        Value: '4',
        Checked: false
      },
      {
        Name: 'Viernes',
        Value: '5',
        Checked: false
      },
      {
        Name: 'Sábado',
        Value: '6',
        Checked: false
      }
    ];
    
    var currentDays = $field.val().split('');
    for(var i = 0; i < currentDays.length; i++) {
      var dayA = currentDays[i];
      for(var n = 0; n < days.length; n++) {
        var dayB = days[n];
        if(dayA === dayB.Value) {
          dayB.Checked = true;
        }
      }
    }
    
    // Make the field hidden when in production.
    $field.attr('type','hidden');
    
    var options = '<div class="col-xs-3">';
    var n = 0;
    while($('.days' + n).length) {
      n = n + 1;
    }
    var optionsContainer = 'days' + n;
    $field.before('<div class="days ' + optionsContainer + '"></div>');
    
    for(var i = 0; i < days.length; i++) {
      var day = days[i];
      var id = 'day' + day.Name + n;
      var checked = day.Checked ? 'checked="checked' : '"';
      if(i==2 || i==4 || i==6){
          options = options + '</div><div class="col-xs-3">'
      }
      options = options + '<div><input class="checkbox_check" type="checkbox" value="' + day.Value + '" id="' + id + '" ' + checked + ' /><label for="' + id + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
    }
    options = options + '</div>'
    
    $('.' + optionsContainer).html(options);
    
    $('body').on('change', '.' + optionsContainer + ' input[type=checkbox]', function () {
      var value = $(this).val();
      var index = getIndex(value);
      if(this.checked) {
        updateField(value, index);
      } else {
        updateField(' ', index);
      }
    });
    
    function getIndex(value) {
      for(i = 0; i < days.length; i++) {
        if(value === days[i].Value) {
          return i;
        }
      }
    }
    
    function updateField(value, index) {
      $field.val($field.val().substr(0, index) + value + $field.val().substr(index+1)).change();
    }
  });
}

})( jQuery );

$('.dias-semana').daysOfWeekInput();
</script>

<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="../library/clockpicker-gh-pages/src/clockpicker.css">

<!-- ClockPicker script -->
<script type="text/javascript" src="../library/clockpicker-gh-pages/src/clockpicker.js"></script>


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