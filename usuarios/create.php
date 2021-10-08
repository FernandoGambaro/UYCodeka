<?php
// set page headers
$page_title = "Datos del Usuario";
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('usuarios');
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

    $obj->Insert($nombres, $valores);
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    $codusuarios = $paciente['id'];
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
                $objpermisos->Where('codusuarios', $codusuarios);
                $permisos = $objpermisos->Ejecutar();

                if($permisos['numfilas']>0) {
                    $objpermisos = new Consultas('permisos');
                    $objpermisos->Update($taskName[$key], $taskValue[$key]);
                    $objpermisos->Where('seccion', $key, 'LIKE', 'AND');
                    $objpermisos->Where('codusuarios', $codusuarios);
                    $permisos = $objpermisos->Ejecutar();
        
                } else {
                //echo $inserttask[$key];
                $taskName[$key][]='seccion';
                $taskValue[$key][]=$key;            
                $taskName[$key][]='codusuarios';
                $taskValue[$key][]=$codusuarios;            
                $objpermisos = new Consultas('permisos');            
                $objpermisos->Insert($taskName[$key], $taskValue[$key]);
                $permisos = $objpermisos->Ejecutar();
                }
            }
    }
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
$hace = _('Crea nuevo usuario');

logger($UserID, $oidestudio, $oidpaciente, $hace);
    
} else {
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];
}
?>


<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados');?>.</div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del Usuario');?></div>
        <?php } ?>

        <div class="panel-body">

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

<form class="form-horizontal" action='create.php' method='post'>
    <input type="hidden" name="DATA[codusuarios]" id="codusuarios" value="" >
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Tratamiento');?></label>
        <div class="col-xs-3">
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
                        echo "<option value='".$key."'>".$value."</option>"; 
                }
            }            

            ?>
            </select>       
        </div>
        <?php if($UserTpo==100 or $UserTpo==2){ ?>
        <label class="control-label col-xs-1"><?php echo _('Estado');?></label>
        <div class="col-xs-3">
        <input type="hidden" name="DATA[estado]" value="1">
            <input type="checkbox" name="DATA[estado]" value="0" id="buscardentro" checked>
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
        <?php } ?> 
        <label class="control-label col-xs-1"><?php echo _('Página de inicio');?></label>
            <div class="col-xs-3">
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
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Nombre');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="" placeholder="Nombre" required>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Apellido');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="apellido" name="DATA[apellido]" value="" placeholder="Apellido"> 
        </div>

        <label class="control-label col-xs-1"><?php echo _('Teléfono');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono" name="DATA[telefono]" value="" placeholder="Teléfono">
        </div>
    </div>
    <div class="form-group">        
        <label class="control-label col-xs-1"><?php echo _('Celular');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="celular" name="DATA[celular]" value="" placeholder="Celular"> 
        </div>

        <label class="control-label col-xs-1"><?php echo _('Nº Documento');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="ci" name="DATA[ci]" value="" placeholder="Documento"> 
        </div>

        <label class="control-label col-xs-1"><?php echo _('Nº empleado');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="empleado" name="DATA[empleado]" value="" placeholder="Número empleado"> 
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Medio pago');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="mediopago" name="DATA[mediopago]" value="" placeholder="Medio Pago"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Nº Cta.');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="cta" name="DATA[cta]" value="" placeholder="Nº cuenta"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('eMail');?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email" name="DATA[email]" value="" placeholder="Email">
        </div>
    </div>
    

      <div class="form-group row" >
        <div class="col-xs-8">

            <!-- ////////////////////////////////  -->
            <div class="form-group">
                <label class="control-label col-xs-2"><?php echo _('Dirección');?></label>
                <div class="col-xs-4">
                <input type="text" class="form-control input-sm" id="direccion" name="DATA[direccion]" value="" placeholder="Dirección"> 
                </div>
                <label class="control-label col-xs-2"><?php echo _('Departamento');?></label>            
                <div class="col-xs-4">
                <select name="DATA[departamento]" class="form-control input-sm">
                <?php
                foreach ($departamento as $key) {
                    echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
                }
                ?>            
                </select>
                </div> 
            </div>
            <!-- ////////////////////////////////  -->
            <div class="form-group">            
                <label class="control-label col-xs-2"><?php echo _('Usuario');?></label>
                <div class="col-xs-4">
                    <input type="text" class="form-control input-sm" id="usuario" name="DATA[usuario]" value="" placeholder="Usuario"> 
                </div>

                <label class="control-label col-xs-2"><?php echo _('Password');?></label>
                <div class="col-xs-4">
                    <input type="text" class="form-control input-sm" id="contrasenia" name="DATA[contrasenia]" value="" placeholder="Contraseña"> 
                </div>
            </div>  
            <!-- ////////////////////////////////  -->
            <div class="form-group">
                <label class="control-label col-xs-2"><?php echo _('Pregunta');?></label>            
                <div class="col-xs-4">
						<input name="DATA[secQ]" id="secQ" value="" type="hidden" />
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
					      echo "<option value='$xx'>$pregunta</option>";
						$xx++;
						}
						?>
						</select>
                </div>
                <label class="control-label col-xs-2"><?php echo _('Respuesta');?></label>
                <div class="col-xs-4">
                    <input type="text" name="DATA[secA]" id="secA" value="" class="form-control input-sm" >
                </div>                 
            </div>

            <!-- ////////////////////////////////  -->            
            <div class="form-group">            
            <label class="control-label col-xs-2"><?php echo _('Con huella');?></label>
                <div class="col-xs-1">                    
                    <input type="hidden" name="DATA[huella]" value="1">
                    <label> 
                    <input type="checkbox" name="DATA[huella]" value="0" id="huella">
                        <span></span>
                    </label>
                </div>
                <script>
                    $(function() {
                        $('#huella').bootstrapToggle({
                        on: 'Si',
                        off: 'No',
                        size: 'mini'
                        });
                    })
                    $(function() {
                        $('#huella').change(function() {
                            if($('#valor').val()==1){
                                $('#valor').val('');
                            }else{
                                $('#valor').val(1);
                            }

                        });
                    })  
                    </script>                 
                <label class="control-label col-xs-1"><?php echo _('Nº');?></label>
                <div class="col-xs-3">                    
                        <input type="text" size="10" name="DATA[pin]" id="pin" value="" class="form-control input-sm" disabled>
                </div>
					<label class="control-label col-xs-1"><?php echo _('Rol');?></label>
                    <div class="col-xs-4">
                    <input name="DATA[role]" id="rol" value="" hidden >
                    <?php 
						//$modificar=verificopermisos('Usuarios', 'modificar', $UserID);
                        $opciones = array();
                        $opciones[0] = _('Nivel Usuario');
                        if($modificar=='true') {
                        $opciones[2] = _('Nivel Enroller');
                        $opciones[12] = _('Nivel Manager');
                        $opciones[14] = _('Nivel Super Manager');
                        }                    
                    ?>
						<select id="role" type="text" class="form-control input-sm" onchange="document.getElementById('rol').value =this.value;">
						<?php 
						foreach($opciones as $key=>$opt) {
						      echo "<option value='$key'>$opt</option>";
						}
						?>						
                        </select> 
                    </div>
            </div>
            <!-- ////////////////////////////////  -->            

        </div>

        <div class="col-xs-4">
        <div class="row">
        <?php
           // $modificar=verificopermisos('Usuarios', 'modificar', $UserID);
            if($modificar=='true') {
        ?>						
            <div style="height: 160px; width:305px; overflow:disable; top:-35px;">
            <table width="300px" border="0" ><tr><td valign="top">
            <div class="header" style="width:305px; top:-33px; padding: 4px 0 4px 0;"><?php echo _('PERMISOS');?>
            &nbsp;<input type="checkbox" id="select_ver" class="Select_All" data-size="mini" data-toggle="toggle" data-on="Ver" data-off="Ver">&nbsp;
            <input type="checkbox" id="select_crear" data-size="mini" data-toggle="toggle" data-on="Crear" data-off="Crear" class="Select_All">
            &nbsp;<input type="checkbox" id="select_mod" data-size="mini" data-toggle="toggle" data-on="Mod." data-off="Mod." class="Select_All">&nbsp;
            <input type="checkbox" id="select_del" data-size="mini" data-toggle="toggle" data-on="Elim." data-off="Elim."  class="Select_All">
                        

            </div>
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
            <div class="fixed-table-container" style="position: relative;top:0px; width:305px; ">
                    <div class="header-background cabeceraTabla"> </div>      			
            <div class="fixed-table-container-inner">
            <div style="height: 160px; width:305px; overflow:auto;">
                <table class='table table-hover table-responsive table-bordered table-condensed' style="font-size: 7px;">
                    <thead class="bg-primary">
                        <tr>
                        <th>&nbsp;</th>
                        <th width="30px"><div class="th-inner"><?php echo _('Ver');?></div></th>
                        <th width="30px"><div class="th-inner"><?php echo _('Crear');?></div></th>
                        <th width="30px"><div class="th-inner"><?php echo _('Mod.');?></div></th>
                        <th width="30px"><div class="th-inner"><?php echo _('Elim.');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($sectores as $xsector) {
                        ?>
                        <tr>
                        <td><div><?php echo ucwords($xsector);?></div></td>
                        <?php
                        if(strpos($xsector, ' ')!==false){
                            $xsector=str_replace(' ', '', $xsector);
                        }
                        $leer=''; $escribir=''; $modi=''; $eliminar='';
                        $objpermisos = new Consultas('permisos');
                        $objpermisos->Select('*');
                        $objpermisos->Where('seccion', trim($xsector), '=', 'AND'); 
                        $permisos = $objpermisos->Ejecutar();
                        //echo $permisos["consulta"];
                        //echo $permisos["datos"][0]['leer'];
                        if($permisos["estado"]=="ok"){							
                            if ( $permisos["numfilas"] > 0 ) {
                                if ($permisos["datos"][0]['leer']==1) {
                                    $leer="checked";
                                    ?>
                                    <script>
                                        $('#select_ver').bootstrapToggle('on')
                                    </script>                                                
                                    <?php
                                }
                                if ($permisos["datos"][0]['escribir']==1) {
                                    $escribir="checked";
                                    ?>
                                    <script>
                                        $('#select_crear').bootstrapToggle('on')
                                    </script>                                                
                                    <?php                                                
                                }
                                if ($permisos["datos"][0]['modificar']==1) {
                                    $modi="checked";
                                    ?>
                                    <script>
                                        $('#select_mod').bootstrapToggle('on')
                                    </script>                                                
                                    <?php
                                }
                                if ($permisos["datos"][0]['eliminar']==1) {
                                    $eliminar="checked";
                                    ?>
                                    <script>
                                        $('#select_del').bootstrapToggle('on')
                                    </script>                                                
                                    <?php
                                }
                            }
                        }
                        ?>
                        <td><input type="hidden" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_0">
                        <label><input class="checkbox1 select_ver" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_v" <?php echo $leer;?>><span></span></label></td>
                        <td><label><input class="checkbox1 select_crear" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_c]" value="<?php echo $xsector;?>_c" <?php echo $escribir;?>><span></span></label></td>
                        <td><label><input class="checkbox1 select_mod" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_m]" value="<?php echo $xsector;?>_m" <?php echo $modi;?>><span></span></label></td>
                        <td><label><input class="checkbox1 select_del" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_e]" value="<?php echo $xsector;?>_e" <?php echo $eliminar;?>><span></span></label></td>
                        </tr>
                        <?php } ?>
                            
                        </tbody>
                        </table>	
            </div></div></div>
            </td></tr></table></div>
        <?php } ?>
        </div>
        </div>
      </div>



</div>
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
<div class="form-group">
    <div class="col-xs-offset-3 col-xs-9">
        <?php
        if(!$_POST)
        { ?>
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guardar');?>">
        <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="event.preventDefault();cancelar();">
        <span class='glyphicon glyphicon-ban-circle'  data-dismiss="modal"></span> <?php echo _('Salir');?></button>
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
include_once "../common/footer.php";
?>