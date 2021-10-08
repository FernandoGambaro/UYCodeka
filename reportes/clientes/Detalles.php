<?php
require_once __DIR__ .'/../../classes/class_session.php';

require_once __DIR__ .'/../../common/funcionesvarias.php';   
require_once __DIR__ .'/../../common/fechas.php';   


if (!$s = new session()) {
    echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
    echo $s->log;
    exit();
}

if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
    //*user is not logged in*/
    //echo "<script>window.top.location.href='../index.php'; </script>";  
} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
       $s->data['act']="timeout";
        $s->save();  	
          //header("Location:../index.php");	
        //echo "<script>window.top.location.href='../index.php'; </script>";
       exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];
$ShowName=$UserNom." ".$UserApe;

require_once __DIR__ .'/../../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('clientes');
$obj->Select();

$service=isset($_GET['service']) ? $_GET['service'] : '';

// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page is the current page, if there's nothing set, default is page 1
$records_per_page = 10; // set records or rows of data per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query limit clause

$alldata="";
$data=array();
$alldata=isset($_GET['alldata']) ? $_GET['alldata'] : null ;
$alldata=array_recibe($alldata);

//var_dump($alldata);


foreach($_REQUEST as $key => $value){
    if($key!='page' and $key!='alldata'){
        $temp = is_array($value) ? $value : trim($value);
        $data[$key] = $$key = $temp;
        if(strlen(trim($value)) >0 and $key=='fechaini'){ 
            $obj->Where('fecha', explota($value)." 00:00:00", '>=');    
        }elseif(strlen(trim($value)) >0 and $key=='fechafin'){ 
            $obj->Where('fecha', explota($value)." 00:00:00", '<=');    
        } elseif(strlen(trim($value)) >0 and $key=='nombre') {          
            if(strlen(trim($value)) >0){ 
                $obj->Where('nombre', $value, 'LIKE', '', '(');    
                $obj->Where('apellido', $value, 'LIKE', 'or', ')');    
            }   
        }elseif(strlen(trim($value)) >0 and $key=='codcliente'){ 
            $obj->Where('codcliente', $value, '=');    
        } else{          
            if(strlen(trim($value)) >0){ 
                $obj->Where($key, $value, 'LIKE');    
            }   
        }
    }         
}


$data=array_envia($data); 

$obj->Where('borrado', '0');    

$paciente = $obj->Ejecutar();
//echo "<br>".$paciente["consulta"]."<br>-->";
$total_rows=$paciente["numfilas"];
$paciente = $paciente["datos"][0];

$dpto = new Consultas('departamentos');
$dpto->Select();
$departamento = $dpto->Ejecutar();
$departamento = $departamento["datos"];


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 div class="row"ansitional//EN" "http://www.w3.org/div class="row"/html4/loose.dtd">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script src="../../library/autocomplete/jquery-1.8.3.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../../library/bootstrap/bootstrap.min.css" />
        <link rel="stylesheet" href="../../library/bootstrap/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />
        <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

        <script src="../../library/js/html2canvas.js"></script>

        <style>

#body {
  all: initial;
  * {
    all: unset;
  }
}
            
@media print {
    @page {
        size: portrait;
        margin-top: 1cm;
        margin-bottom: 1cm;
        margin-left: 0.5cm;
        margin-right: 0.5cm;
        font-size: 9px;
        @bottom-right-corner {
            content: "Página " counter(page);
        }
        #header { 
        position: fixed; 
        width: 100%; 
        top: 0; 
        left: 0; 
        right: 0;
        }
    }
    .custom-page-start {
        margin-top: 10px;
    }  

    #footer { 
    position: fixed; 
    width: 100%; 
    bottom: 0; 
    left: 0;
    right: 0;
    } 
    counter-increment: section; 
    counter-reset: page 1;     

    @page:right {
        @bottom-right {
        content: counter(page);
        }
    }      

}


  </style>

	<style TYPE="text/css">

	</style>
<script language="Javascript">
function imprSelec(nombre)
{
  var ficha = document.getElementById(nombre);
  var ventimp = window.open(' ', 'popimpr');
  ventimp.document.write( ficha.innerHTML );
  ventimp.document.close();
  ventimp.print( );
  ventimp.close();
}

</script>
<script language="Javascript">
$(document).ready(function(){

    $('.print').on('click', function(){	
    //alert('pl');
    $("#imprimo").remove();
        html2canvas(container).then(function(canvas) {
        //document.body.appendChild(canvas);
        window.print();
	            $('canvas').remove();
	            $("#container").show();
                parent.$('idOfDomElement').colorbox.close();
        });
    
    });
});    
</script>
</head>
<body LANG="es-UY" TEXT="#000000" id="body">
<button class='print' id="imprimo">Imprimir</button>

        <!-- container -->
<div class="container text-center" id="container">    
         <!-- For the following code look at footer.php -->
<?php
// check if more than 0 record found
if($total_rows>=0){
?>
<div id="header">

  <div class="row">
    <div class="col-xs-12">
        <div class="">
            <div class="row">
                <div class="col-xs-4 align-top text-left">
                <img src="../../datos_sistema/loadimage.php?id=11&default=1" height="80" hspace="7" vspace="3" >
                </div>
                <div class="col-xs-8 align-middle text-right">
                <legend class="scheduler-border"><?php echo _('FICHA CLIENTE');?></legend>
                <div class="text-right"><?php echo date("d/m/Y");?></div>
                </div>
                
            </div>
        </div>
    </div>
  </div>
</div>

<div id="footer"> 
    <div class="row">

    <div style="position: fixed; bottom: 0; left: 0; background:#2A2A2A; width:100%; height:27px; margin: 0 0 0 0; padding-buttom: 2px;" >
<div style="position: fixed; bottom:4px; left:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff;  padding: 4px 10px 4px 10px;">
<font color="white"> MCC © 2018 <a href="https://www.mcc.com.uy" title="MCC - Soporte Técnico">MCC</a></font>&nbsp;
<?php echo $ip;?></div>
</div>
<div style="position: fixed; bottom:4px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px; left:0; right: 0;  margin: 0 auto;" align="center">versión <?php echo $s->data['version'];?></div>
<div id="UserData" align="right" style="position: fixed; bottom:4px; right:20px; font-family: Tahoma; font-weight: bold; font-size: 11px; color:#fff; padding: 4px 10px 4px 10px;">Impreso por
 <?php echo $ShowName;?> </div>
        <div class="custom-page-start" style="page-break-before: always;"></div> 
    </div>
</div>

    <fieldset class="scheduler-border">
    <legend class="scheduler-border text-left" style="background-color: #333; color:#fff; "><?php echo _('DATOS GENERALES'); ?></legend>

<div class="row">

    
    <label class="control-label col-xs-4 text-left"><?php echo _('Nombre'); ?></label>
    <label class="control-label col-xs-4 text-left"><?php echo _('Apellido'); ?></label>
    <label class="control-label col-xs-4 text-left"><?php echo _('Teléfono'); ?></label>
</div>
<div class="row">
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["nombre"];?>" > 
    </div>

    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["apellido"];?>" > 
    </div>

    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["telefono"];?>"">
    </div>

</div>
<!-- /////////////// -->
<div class="row">
    <label class="control-label col-xs-4 text-left"><?php echo _('Empresa'); ?></label>
    <label class="control-label col-xs-4 text-left"><?php echo _('RUT/CI'); ?></label>
    <label class="control-label col-xs-4 text-left">&nbsp;<?php echo _('Teléfono'); ?></label>
</div><div class="row">
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm"  value="<?php echo $paciente["empresa"];?>">
    </div>
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["nif"];?>">
    </div>
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["telefono2"];?>"> 
    </div>

</div>
<!-- //////////// -->    
<div class="row">        
    <label class="control-label col-xs-4 text-left"><?php echo _('Movil'); ?></label>
    <label class="control-label col-xs-4 text-left">&nbsp;<?php echo _('Fax'); ?></label>
    <label class="control-label col-xs-4 text-left"><?php echo _('eMail primario:'); ?></label>
</div><div class="row">
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["movil"];?>" > 
    </div>
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm"  value="<?php echo $paciente["fax"];?>"> 
    </div>
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["email"];?>" >
    </div>

</div>

<div class="row">
    <label class="control-label col-xs-4 text-left"><?php echo _('eMail secundario'); ?></label>
    <label class="control-label col-xs-4 text-left"><?php echo _('Web'); ?>&nbsp;</label>
</div><div class="row">

    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["email2"];?>"> 
    </div>
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["web"];?>" > 
    </div>

</div>
<!-- ////////////////////////////////  -->
<div class="row">
    <label class="control-label col-xs-4 text-left"><?php echo _('Dirección'); ?>&nbsp;</label>
    <label class="control-label col-xs-4 text-left"><?php echo _('Departamento'); ?>&nbsp;</label>            
    <label class="control-label col-xs-2 text-left"><?php echo _('Código postal'); ?>&nbsp;</label>
</div>
<div class="row">
    <div class="col-xs-4">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["direccion"];?>"> 
    </div>
    <div class="col-xs-4">
    <?php
    foreach ($departamento as $key) {        
        if ($key["departamentosid"] == $paciente["codprovincia"]) {
            echo '<input type="text" class="form-control input-sm" value="'.$key["departamentosdesc"].'">';
        }
    }
    ?>            
    </div>     
    <div class="col-xs-2">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["codpostal"];?>"> 
    </div>  
               
</div>
<div class="row">
<label class="control-label col-xs-2 text-left"><?php echo _('Localidad'); ?>&nbsp;</label>
</div><div class="row">
    <div class="col-xs-2">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["localidad"];?>"> 
    </div> 
</div>
    </fieldset>
<br>
    <fieldset class="scheduler-border">
    <legend class="scheduler-border text-left" style="background-color: #333333; color:#fff; "><?php echo _('INFORMACIÓN EXTRA'); ?></legend>

<!-- ////////////////////////////////  -->
<div class="row"> 

    <label class="control-label col-xs-3 text-left" for="usuario"><?php echo _('Ejecutivo de cuenta:'); ?>&nbsp;</label>
    <div class="col-xs-3">
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
        <input type="text" class="form-control input-sm" value="<?php echo $nombre;?>" />
    </div>
    <label class="control-label col-xs-2"><?php echo _('Abonado/Service'); ?>&nbsp;</label>
    <div class="col-xs-2">
    <?php
    $tipo = array(0=>"Seleccione un tipo", 1=>"Común", 2=> "Abonado A", 3=> "Abonado B");
    foreach ($tipo as $key=>$valor) {
        if ($key == $paciente["service"]) {
            echo '<input type="text" class="form-control input-sm" value="'.$valor.'">';
        } 
    }
    ?> 
    </div>

</div> 
    <br>
<!-- ////////////////////////////////  -->
<div class="row">
    <label class="control-label col-xs-4"><?php echo _('Horas Asig./Mes'); ?>&nbsp;</label>            
    <div class="col-xs-2">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["horas"];?>"> 

    </div> 
    <label class="control-label col-xs-4"><?php echo _('Código Plan de Cuentas'); ?>&nbsp;</label>
    <div class="col-xs-2">
    <input type="text" class="form-control input-sm" value="<?php echo $paciente["plancuenta"];?>"> 
    </div>                
</div> 
</fieldset> 
<br>   
<!-- ////////////////////////////////  -->
<fieldset class="scheduler-border">
    <legend class="scheduler-border text-left" style="background-color: #333; color:#fff; "><?php echo _('Formas de pago'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-1 text-left"><?php echo _('Días'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" value="<?php echo $paciente["pagodia"];?>"> 
        </div>        
        <label class="control-label col-xs-1 text-left"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" value="<?php echo $paciente["pagohora"];?>" > 
        </div>        
        
        <label class="control-label col-xs-2 text-left"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" value="<?php echo $paciente["pagocontacto"];?>" class="form-control input-sm" >
        </div>
    </div>
<br>
    <div class="row">
        <label class="control-label col-xs-3 text-left"><?php echo _('Forma de pago'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <?php
        if(strlen($paciente["codformapago"])>0){
        $objformapago = new Consultas('formapago');
		$objformapago->Select();
    
        $objformapago->Where('codformapago', $paciente["codformapago"]);    
        $objformapago->Where('borrado', '0');    
        $formapago = $objformapago->Ejecutar();
        $total_formapago=$formapago["numfilas"];
        $rowsformapago = $formapago["datos"][0];
        //echo $formapago["consulta"];
        // check if more than 0 record found
        $nombrefp=$rowsformapago['nombrefp'];
        }else{
            $nombrefp='';
        }
        ?>
            <input type="text" class="form-control input-sm" value="<?php echo $nombrefp;?>">
        </select>
        </div>        
        <label class="control-label col-xs-1 text-left"><?php echo _('Banco'); ?>&nbsp;</label>
        <div class="col-xs-2">
        <?php
        if(strlen($paciente["codentidad"])>0){
        $objentidades = new Consultas('entidades');
		$objentidades->Select();
        $objentidades->Where('codentidad', $paciente["codentidad"]);    
    
        $objentidades->Where('borrado', '0');    
        $entidades = $objentidades->Ejecutar();
        $total_entidades=$entidades["numfilas"];
        $rowsentidades = $entidades["datos"][0];
        //echo $entidades["consulta"];
        // check if more than 0 record found
        $nombreentidad=$rowsentidades['nombreentidad'];
        }else{
            $nombreentidad='';
        }
        ?>
            <input type="text" class="form-control input-sm" value="<?php echo $nombreentidad;?>">
        </select>

        </div>        
        
        <label class="control-label col-xs-2 text-left"><?php echo _('Nº cuenta'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" name="DATA[cuentabancaria]" id="tacto" value="<?php echo $paciente["cuentabancaria"];?>" class="form-control input-sm" >
        </div>
    </div>
</fieldset>                 

<!-- ////////////////////////////////  -->
<br>
<fieldset class="scheduler-border">
    <legend class="scheduler-border text-left" style="background-color: #333; color:#fff; "><?php echo _('Datos de entrega'); ?></legend>
    <div class="row">
        <label class="control-label col-xs-3 text-left"><?php echo _('Agencia de cargas'); ?>&nbsp;</label>
        <div class="col-xs-3">
            <input type="text" class="form-control input-sm" value="<?php echo $paciente["agencia"];?>"> 
        </div>        
    </div>
    <br>
    <div class="row">
        <label class="control-label col-xs-1 text-left"><?php echo _('Días'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" value="<?php echo $paciente["recepciondia"];?>" > 
        </div>        
        <label class="control-label col-xs-1 text-left"><?php echo _('Horario'); ?>&nbsp;</label>
        <div class="col-xs-2">
            <input type="text" class="form-control input-sm" value="<?php echo $paciente["recepcionhora"];?>" > 
        </div>        
        
        <label class="control-label col-xs-2 text-left"><?php echo _('Encargado'); ?>&nbsp;</label>
        <div class="col-xs-4">
            <input type="text" value="<?php echo $paciente["recepcioncontacto"];?>" class="form-control input-sm" >
        </div>
    </div>
    </fieldset>                 


</div>
    </div>

        <?php
    }

// if there are no user
else{
    echo "<div>". _('No se encontraron registros.'). "</div>";
    }
?>