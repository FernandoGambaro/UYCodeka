<?php
// set page headers
$page_title = _('Datos del Cliente'); 
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not
$codcliente = isset($_GET['codcliente']) ? $_GET['codcliente'] : $_POST['codcliente'];

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

$mensaje='';
$obj = new Consultas('clientes');

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
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }
  
$obj = new Consultas('clientes');
$obj->Select();
$obj->Where('codcliente', $codcliente);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];

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
    $obj->Where('codcliente', $codcliente);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente["datos"][0];
    
    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

$oidestudio = '';
$oidpaciente = '';
$hace = _('Ve Contacto clientes '). $paciente["nombre"]." ".$paciente["apellido"];
$datosguardados=0;
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
    height: 380px;
}
</style>
<form class="form-horizontal" action='edit.php' method='post'>

<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados.'); ?></div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del Cliente')." ".$paciente["nombre"]." ".$paciente["apellido"]." - ".$paciente["empresa"];?></div>
        <?php } ?>

        <div class="panel-body">




<div id="exTab3" class="container">	

    <?php
    $equipos=verificopermisos('Equiposcliente', 'leer', $UserID);
    $respaldos=verificopermisos('Respaldoscliente', 'leer', $UserID);
    $proyectos=verificopermisos('Proyectoscliente', 'leer', $UserID);            

    ?>


<ul class="nav nav-tabs nav-pills tabs">
<li class="active"><a href="#1b" data-toggle="tab"><?php echo _('Datos Básicos'); ?></a></li>
<li><a href="#2b" data-toggle="tab"><?php echo _('Otros datos'); ?></a></li>
<?php if ( $proyectos=="true") { ?>
<li><a href="#3b" data-toggle="tab"><?php echo _('Proyectos'); ?></a></li>
<?php } if ( $equipos=="true") { ?>
<li><a href="#4b" data-toggle="tab"><?php echo _('Equipos'); ?></a></li>
<?php } if ( $respaldos=="true") { ?>
<li><a href="#5b" data-toggle="tab"><?php echo _('Respaldos'); ?></a></li>
<?php } ?>
</ul>

<div class="tab-content col-xs-12 ">
<div class="tab-pane fade in active" id="1b">
<?php if ($mensaje!=""){ ?>
<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
<?php } ?>
<div class="container-fluid">
<div class="row">


    <input type="hidden" name="DATA[codcliente]" id="codcliente" value="<?php echo $paciente["codcliente"];?>" >
    <input type="hidden" name="codcliente" value="<?php echo $paciente["codcliente"];?>" >
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Nombre'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="nombre" name="DATA[nombre]" value="<?php echo $paciente["nombre"];?>" placeholder="<?php echo _('Nombre'); ?>" required>
        </div>

        <label class="control-label col-xs-1"><?php echo _('Apellido'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="apellido" name="DATA[apellido]" value="<?php echo $paciente["apellido"];?>" placeholder="<?php echo _('Apellido'); ?>"> 
        </div>

        <label class="control-label col-xs-1"><?php echo _('Teléfono'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono" name="DATA[telefono]" value="<?php echo $paciente["telefono"];?>" placeholder="<?php echo _('Teléfono'); ?>">
        </div>
    </div>
    <!-- /////////////// -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Empresa'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="empresa" name="DATA[empresa]" value="<?php echo $paciente["empresa"];?>" placeholder="<?php echo _('Empresa'); ?>">
        </div>
        <label class="control-label col-xs-1"><?php echo _('RUT'); ?></label>
        <div class="col-sm-1">
        <input name="DATA[tiponif]" id="tiponif" value="<?php echo $paciente["tiponif"];?>" type="hidden" />
                <select type="text" onchange="document.getElementById('tiponif').value=this.value;" class="form-control input-sm" >
                <?php
                    $tiponif = array(0=>_("Seleccione uno"), 1=>_("RUT"), 2=>_("CI"), 3=>_("Pasaporte") );
                foreach($tiponif as $key=>$valor) {
                    if ($key==$paciente["tiponif"]) {
                        echo "<option value='$key' selected>$valor</option>";
                    } else {
                        echo "<option value='$key'>$valor</option>";
                    }
                }
                ?>
                </select>
        </div>
        <div class="col-xs-2">
        <input type="text" class="form-control input-sm" id="nif" name="DATA[nif]" value="<?php echo $paciente["nif"];?>" placeholder="<?php echo _('RUT/Documento'); ?>">
        </div>
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Teléfono'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="telefono2" name="DATA[telefono2]" value="<?php echo $paciente["telefono2"];?>" placeholder="<?php echo _('Teléfono secundario'); ?>"> 
        </div>

    </div>
    <!-- //////////// -->    
    <div class="form-group">        
        <label class="control-label col-xs-1"><?php echo _('Movil'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="movil" name="DATA[movil]" value="<?php echo $paciente["movil"];?>" placeholder="<?php echo _('Movil'); ?>"> 
        </div>
        <label class="control-label col-xs-1">&nbsp;<?php echo _('Fax'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="fax" name="DATA[fax]" value="<?php echo $paciente["fax"];?>" placeholder="<?php echo _('Número fax'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('eMail primario'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email" name="DATA[email]" value="<?php echo $paciente["email"];?>" placeholder="<?php echo _('Email primario'); ?>">
        </div>

    </div>

    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('eMail secundario'); ?></label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="email2" name="DATA[email2]" value="<?php echo $paciente["email2"];?>" placeholder="<?php echo _('Email secundario'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Web'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="web" name="DATA[web]" value="<?php echo $paciente["web"];?>" placeholder="<?php echo _('Web'); ?>"> 
        </div>

    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Dirección'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="direccion" name="DATA[direccion]" value="<?php echo $paciente["direccion"];?>" placeholder="<?php echo _('Dirección'); ?>"> 
        </div>
        <label class="control-label col-xs-1"><?php echo _('Departamento'); ?>&nbsp;</label>            
        <div class="col-xs-3">
        <select name="DATA[codprovincia]" class="form-control input-sm">
        <?php
        foreach ($departamento as $key) {
            
            if ($key["departamentosid"] == $paciente["codprovincia"]) {
                echo "<option value='".$key["departamentosid"]."' selected>".$key["departamentosdesc"]."</option>";
            } else {
                echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
            }
        }
        ?>            
        </select>
        </div> 
        <label class="control-label col-xs-1"><?php echo _('Localidad'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="localidad" name="DATA[localidad]" value="<?php echo $paciente["localidad"];?>" placeholder="<?php echo _('Localidad'); ?>"> 
        </div>                
    </div>
    <!-- ////////////////////////////////  -->
    <div class="form-group"> 
    <label class="control-label col-xs-1"><?php echo _('Código postal'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="codpostal" name="DATA[codpostal]" value="<?php echo $paciente["codpostal"];?>" placeholder="<?php echo _('Código postal'); ?>"> 
        </div>                   
        <label class="control-label col-xs-2" for="usuario"><?php echo _('Ejecutivo de cuenta'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <?php
            $nombre='';
            if(strlen($paciente["codusuarios"])>0){
            $objusuarios = new Consultas('usuarios');
                  
            $objusuarios->Select();
            $objusuarios->Where('codusuarios', $paciente["codusuarios"]);
            $objusuarios->Where('borrado','0' );
            $usuarios = $objusuarios->Ejecutar();
            
            $total_usuarios=$usuarios["numfilas"];
                if($total_usuarios>0){
                $rowusuarios = $usuarios["datos"][0]; 
                $nombre= $rowusuarios["nombre"].' '.$rowusuarios["apellido"];
                }
            }	
		    ?>	
            <input placeholder="Usuario" type="text" onfocus="this.select();" class="form-control input-sm" id="Ausuarios" size="45" value="<?php echo $nombre;?>" maxlength="45" onFocus="this.style.backgroundColor='#FFFF99'"  />
            <input name="DATA[codusuarios]" type="hidden" id="codusuarios" readonly  value="<?php echo $paciente["codusuarios"];?>" />
        </div>
    </div> 
    <!-- ////////////////////////////////  -->
    <div class="form-group">
        <label class="control-label col-xs-1"><?php echo _('Abonado/Service'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <select name="DATA[service]" class="form-control input-sm">
        <?php
        $tipo = array(0=>"Seleccione un tipo", 1=>"Común", 2=> "Abonado A", 3=> "Abonado B");
        foreach ($tipo as $key=>$valor) {
            if ($key == $paciente["service"]) {
                echo "<option value='".$key."' selected>".$valor."</option>";
            } else {
                echo "<option value='".$key."'>".$valor."</option>";
            }
        }
        ?>            
        </select>
        </div>
        <label class="control-label col-xs-2"><?php echo _('Horas Asig./Mes'); ?>&nbsp;</label>            
        <div class="col-xs-1">
        <input type="text" class="form-control input-sm" id="horas" name="DATA[horas]" value="<?php echo $paciente["horas"];?>" placeholder="<?php echo _('Horas al mes'); ?>"> 

        </div> 
        <label class="control-label col-xs-2"><?php echo _('Código Plan de Cuentas'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input type="text" class="form-control input-sm" id="plancuenta" name="DATA[plancuenta]" value="<?php echo $paciente["plancuenta"];?>" placeholder="<?php echo _('Plan de cuentas'); ?>"> 
        </div>                
    </div>     
    <!-- ////////////////////////////////  -->
    <div class="form-group">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?php echo _('Acceso web'); ?></legend>
        <label class="control-label col-xs-1"><?php echo _('Password'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="contrasenia" name="DATA[contrasenia]" value="" placeholder="<?php echo _('Contraseña'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Pregunta'); ?>&nbsp;</label>            
        <div class="col-xs-3">
                <input name="DATA[secQ]" id="secQ" value="<?php echo $paciente["secQ"];?>" type="hidden" />
                <select id="Pregunta" type="text" onchange="document.getElementById('secQ').value=this.value;" class="form-control input-sm" >
                <?php
                    $questions = array();
                    $questions[0] = _("Seleccione uno");
                    $questions[1] = _("¿En que ciudad nació?");
                    $questions[2] = _("¿Cúal es su color favorito?");
                    $questions[3] = _("¿En qué año se graduo de la facultad?");
                    $questions[4] = _("¿Cual es el segundo nombre de su novio/novia/marido/esposa?");
                    $questions[5] = _("¿Cúal es su auto favorito?");
                    $questions[6] = _("¿Cúal es el nombre de su madre?");
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
        <label class="control-label col-xs-1"><?php echo _('Respuesta'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[secA]" id="secA" value="<?php echo $paciente["secA"];?>" class="form-control input-sm" >
        </div>
    </fieldset>                 
    </div>

            <!-- ////////////////////////////////  -->            
</div>
</div>
</div> <!--Fin 1b  *****************************************************************-->
<div class="tab-pane" id="2b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  -->
    <div class="form-group">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Formas de pago'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Días'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" id="pagodia" name="DATA[pagodia]" value="<?php echo $paciente["pagodia"];?>" placeholder="<?php echo _('Días de pago'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="pagohora" name="DATA[pagohora]" value="<?php echo $paciente["pagohora"];?>" placeholder="<?php echo _('Horario de pago'); ?>"> 
        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[pagocontacto]" id="pagocontacto" value="<?php echo $paciente["pagocontacto"];?>" class="form-control input-sm" >
        </div>
    </div>

    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Forma de pago'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <input name="DATA[codformapago]" id="codformapago" value="<?php echo $paciente["codformapago"];?>" type="hidden" />
        <select type="text" onchange="document.getElementById('codformapago').value=this.value;" class="form-control input-sm" >
        <?php
        $objformapago = new Consultas('formapago');
		$objformapago->Select();
    
        $objformapago->Where('borrado', '0');    
        $formapago = $objformapago->Ejecutar();
        $total_formapago=$formapago["numfilas"];
        $rowsformapago = $formapago["datos"];
        //echo $formapago["consulta"];
        // check if more than 0 record found
        if($total_formapago>=0){
            foreach($rowsformapago as $rowformapago){
                if ($rowformapago['codformapago'] == $paciente["codformapago"]) { ?>
                    <option value="<?php echo $rowformapago['codformapago'];?>" selected="selected"><?php echo $rowformapago['nombrefp'];?></option>
                <?php } else { ?>
                    <option value="<?php echo $rowformapago['codformapago'];?>" ><?php echo $rowformapago['nombrefp'];?></option>
                <?php
                }
            }
        }
        ?>
        </select>
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Banco'); ?>&nbsp;</label>
        <div class="col-xs-3">
        <input name="DATA[codentidad]" id="entidades" value="<?php echo $paciente["codentidad"];?>" type="hidden" />
        <select type="text" onchange="document.getElementById('entidades').value=this.value;" class="form-control input-sm" >
        <?php
        $objentidades = new Consultas('entidades');
		$objentidades->Select();
    
        $objentidades->Where('borrado', '0');    
        $entidades = $objentidades->Ejecutar();
        $total_entidades=$entidades["numfilas"];
        $rowsentidades = $entidades["datos"];
        //echo $entidades["consulta"];
        // check if more than 0 record found
        if($total_entidades>=0){
            foreach($rowsentidades as $rowentidades){
                if ($rowentidades['codentidad'] == $paciente["codentidad"]) { ?>
                    <option value="<?php echo $rowentidades['codentidad'];?>" selected="selected"><?php echo $rowentidades['nombreentidad'];?></option>
                <?php } else { ?>
                    <option value="<?php echo $rowentidades['codentidad'];?>" ><?php echo $rowentidades['nombreentidad'];?></option>
                <?php
                }
            }
        }
        ?>
        </select>

        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Nº cuenta'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[cuentabancaria]" id="tacto" value="<?php echo $paciente["cuentabancaria"];?>" class="form-control input-sm" >
        </div>
    </div>
        

    </fieldset>                 
    </div>    

<!-- ////////////////////////////////  -->
<div class="form-group">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Datos de entrega'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-2"><?php echo _('Agencia de cargas'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="agencia" name="DATA[agencia]" value="<?php echo $paciente["agencia"];?>" placeholder="<?php echo _('Agencia'); ?>"> 
        </div>        
    </div>
    <div class="row">
        <label class="control-label col-xs-1"><?php echo _('Días'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="recepciondia" name="DATA[recepciondia]" value="<?php echo $paciente["recepciondia"];?>" placeholder="<?php echo _('Días de recepción'); ?>"> 
        </div>        
        <label class="control-label col-xs-1"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" id="recepcionhora" name="DATA[recepcionhora]" value="<?php echo $paciente["recepcionhora"];?>" placeholder="<?php echo _('Horario de recepción'); ?>"> 
        </div>        
        
        <label class="control-label col-xs-1"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" name="DATA[recepcioncontacto]" id="pontacto" value="<?php echo $paciente["recepcioncontacto"];?>" class="form-control input-sm" >
        </div>
    </div>
    </fieldset>                 
    </div>    


</div>  
</div> 
</div> <!-- fin 2b **************************************************-->
<?php if ( $proyectos=="true") { ?>
<div class="tab-pane" id="3b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  Proyectos -->
    <div class="row">

        
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Nuevo Proyecto'); ?></legend>
 <div class="row">
      <div class="col-xs-2">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="<?php echo _('Fecha inicio'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo date("d/m/Y");?>" id="fechaini_proyecto" readonly  required>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div> 
      </div>
      <div class="col-xs-2">
        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
            <input placeholder="<?php echo _('Fecha fin'); ?>" class="form-control input-sm" size="26" type="text" value="" id="fechafin_proyecto" readonly  required>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
        </div> 
      </div>
      <label class="control-label col-xs-1"><?php echo _('Descripción'); ?></label>
    <div class="col-xs-5">
        <input type="text" class="cajaGrande" id="descripcion_proyecto" size="70" maxlength="100" data-index="7">
    </div>
    <div class="col-xs-1">
        <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();GuardarProyecto();"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
    </div>
    <div class="col-xs-1">
        <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();limpiarProyectos();"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
    </div>

</div>

</fieldset>

    </div>
<div class="row">

    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de Proyectos'); ?></legend>
    <iframe src="proyectos/rejilla.php?codcliente=<?php echo $codcliente;?>" width="98%" height="210" id="frame_proyectos" name="frame_proyectos" frameborder="0" scrolling="no" >
    <ilayer width="98%" height="210" id="frame_proyectos" name="frame_proyectos"></ilayer>
    </iframe>
    <iframe id="frame_datos_proyectos" name="frame_datos_proyectos" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos_proyectos" name="frame_datos_proyectos"></ilayer>
    </iframe>       
</div>        
</div>
</div>  
</div><!-- fin 3b **************************************************-->
<?php }

if ( $equipos=="true") { ?>
<div class="tab-pane" id="4b">
<div class="container-fluid">
<div class="row">

<!-- ////////////////////////////////  Equipos -->
<div class="row">
        
<fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Nuevo Equipo'); ?></legend>
    <div class="row">
        <div class="col-xs-8">

        <div class="row">
            <div class="col-xs-3">
                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                    <input placeholder="<?php echo _('Fecha'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo date("d/m/Y");?>" id="fecha_equipos">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div> 
            </div>
            <label class="control-label col-xs-1"><?php echo _('Descripción'); ?></label>
            <div class="col-xs-3">
                <input type="text" class="form-control input-sm" id="descripcion_equipos" size="70" maxlength="100" data-index="7">
            </div>
            <label class="control-label col-xs-1"><?php echo _('Alias'); ?></label>
            <div class="col-xs-4">
                <input type="text" class="form-control input-sm" id="alias_equipos" size="70" maxlength="100" data-index="7">
            </div>

        </div>
        <div class="row">
            <label class="control-label col-xs-1"><?php echo _('Número'); ?></label>
            <div class="col-xs-2">
                <input type="text" class="form-control input-sm" id="numero_equipos" size="70" maxlength="100" data-index="7">
            </div>     
            <label class="control-label col-xs-1"><?php echo _('Service'); ?></label>
            <div class="col-xs-3">
                <select size="1" id="service_equipos" class="form-control input-sm service" >
                <?php
                    $tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
                    $xx=0;
                    foreach($tipo as $tpo) {
                            echo "<option value='$xx'>$tpo</option>";
                    $xx++;
                    }
                    ?>
                </select>

            </div>
            <label class="control-label col-xs-1"><?php echo _('Detalles'); ?></label>
            <div class="col-xs-4">
            <textarea cols="41" rows="4" id="detalles_equipos" class="form-control input-sm"></textarea>
            </div>    
            
        </div>
        <div class="row">
            <div class="col-xs-1">
                <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();GuardarEquipo();"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
            </div>
            <div class="col-xs-1">
                <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();limpiarEquipo();"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp;Limpiar</button>
            </div>
        </div>
        </div>
        <div class="col-xs-4" id="SelectRespaldos">
        <fieldset><legend>Días de respaldos</legend>
            <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
                <input class="form-control dias-semana" id="diasemana_equipos" type="text" value="0123456" data-bind="value: WorkWeek">
            </div>
            </div>
            </div>
        </fieldset>
        </div>
    </div>
</fieldset>


</div>
<div class="row">
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de equipos'); ?></legend>
    <iframe src="equipos/rejilla.php?codcliente=<?php echo $codcliente;?>" width="98%" height="210" id="frame_equipos" name="frame_equipos" frameborder="0" scrolling="no" >
    <ilayer width="98%" height="210" id="frame_equipos" name="frame_equipos"></ilayer>
    </iframe>
    <iframe id="frame_datos_equipos" name="frame_datos_equipos" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos_equipos" name="frame_datos_equipos"></ilayer>
    </iframe>
</div>
</div>
</div>
</div><!-- fin 4b **************************************************-->

<?php } if ( $respaldos=="true") { ?>

<div class="tab-pane" id="5b">
<div class="container-fluid">
<div class="row">

 <!-- ////////////////////////////////  Respaldos -->
 
    <fieldset class="scheduler-border">
    <legend class="scheduler-border"><?php echo _('Listdo de respaldos'); ?></legend>
    <iframe src="backup/rejilla.php?codcliente=<?php echo $codcliente;?>" width="98%" height="280" id="frame_backups" name="frame_backupsf" frameborder="0" scrolling="no" >
    <ilayer width="98%" height="280" id="frame_backups" name="frame_backups"></ilayer>
    </iframe>
    <iframe id="frame_datos_backups" name="frame_datos_backups" width="0" height="0" frameborder="0">
    <ilayer width="0" height="0" id="frame_datos_backups" name="frame_datos_backups"></ilayer>
    </iframe>

        
    </div>        
 

</div>  
</div><!-- fin 5b **************************************************-->
<?php } ?>

</div> 
 



</div>			
</div><!-- Fin  -->

	<div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-xs-12 mx-auto">
    <div class="text-center">
    <?php if(!$_POST)
        { ?>
        <input type="submit" class="btn btn-primary left-margin btn-xs" value="<?php echo _('Guardar'); ?>">
        <?php } ?>
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

$(document).ready(function (){
    $(window).bind('load', function () {
        var frameWidth = $(document).width();
        var frameHeight = $(document).height();
        parent.$.fn.colorbox(frameWidth, frameHeight);
    });
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
    
    var options = '<div class="col-xs-6">';
    var n = 0;
    while($('.days' + n).length) {
      n = n + 1;
    }
    
    var optionsContainer = 'days' + n;
    $field.before('<div class="days ' + optionsContainer + '"></div>');
    
    for(var i = 0; i < days.length; i++) {
      var day = days[i];
      var id = 'day' + day.Name + n;
      var checked = day.Checked ? 'checked="checked"' : '';
      if(i==3){
          options = options + '</div><div class="col-xs-6">'
      }
      options = options + '<div><input type="checkbox" value="' + day.Value + '" id="' + id + '" ' + checked + ' /><label for="' + id + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
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

<script>
$(document).ready(function(){
    $("#SelectRespaldos").children().prop('disabled',true);

    $("select.service"). change(function(){
    var selectedService = $(this). children("option:selected"). val();
    console.log(selectedService);
    if(selectedService==3){
        $("#SelectRespaldos").children().prop('disabled',false);
        
    }else{
        $("#SelectRespaldos").children().prop('disabled',true);

    }
    });
});
</script>
<?php
include_once "../common/footer.php";

?>