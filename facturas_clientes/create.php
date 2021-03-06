<?php
// set page headers
$page_title = _('Datos de la factura'); 
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
$obj = new Consultas('facturas');

if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';

$codfacturatmp=$_POST['codfacturatmp'];

    $DATA=$_POST['DATA'];
    $xpos=0;
    foreach ($DATA as $key => $item)
    {
        if (!is_array($DATA[$key])) {
            if(strlen($item)>0){
                if(strpos($item, '/')>0){
                    $valores[] = explota($item);
                }else {
                    $valores[] = $item;
                }
                if($key=='codcliente'){
                    $codcliente=$item;
                }
                if($key=='codfactura'){
                    $codfactura=$item;
                }
                $nombres[] = $key;
            }
            
        } else {
            for ( $i=0; $i < count($DATA[$key]); $i++ )
            {
                if ( !empty($DATA[$key][$i]) )  {
                    if($item!=''){
                    $nombres[] = $key;
                        if(strpos($item, '/')>0){
                            $valores[] = explota($item);
                        } else {
                            $valores[] = $item;
                        }
                        if($key=='codcliente'){
                            $codcliente=$item;
                        }
                        if($key=='codfactura'){
                            $codfactura=$item;
                        }
                    }
                }
            }
        }
    }

    $obj->Insert($nombres, $valores);
    //$obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){
       $datosguardados=1;

        //Agrego el articulo a la l??nea de la factura
        $lineaFactura = new Consultas('factulineatmp');
        $lineaFactura->Select();
        $lineaFactura->Where('codfactura', $codfacturatmp);

        $lineaFactura->Orden('numlinea', 'ASC');
        
        $lineaConsulta = $lineaFactura->Ejecutar();
        $lineaDatos = $lineaConsulta['datos'];
        
        if($lineaConsulta['numfilas']>0){
            foreach($lineaDatos as $linea){

                $lineaArticulo = new Consultas('articulos');
                $lineaArticulo->Select('stock');
                $lineaArticulo->Where('codarticulo', $linea['codigo']);
                $ConsultaArticulo = $lineaArticulo->Ejecutar();
                $ConsultaDatos = $ConsultaArticulo['datos'][0];


                if($linea['borrado']==0){
                    $nombres = array();
                    $valores = array();

                    $nombres[] = "codfactura";
                    $valores[] = $codfactura;
                    $nombres[] = "codfamilia";
                    $valores[] = $linea["codfamilia"];
                    $nombres[] = "codigo";
                    $valores[] = $linea["codigo"];
                    $nombres[] = "detalles";
                    $valores[] = $linea["detalles"];
                    $nombres[] = "moneda";
                    $valores[] = $linea["moneda"];
                    $nombres[] = "cantidad";
                    $valores[] = $linea["cantidad"];
                    $nombres[] = "precio";
                    $valores[] = $linea["precio"];
                    $nombres[] = "importe";
                    $valores[] = $linea["importe"];
                    $nombres[] = "dcto";
                    $valores[] = $linea["dcto"];
            
                    $objTmp = new Consultas('factulinea');
                    $objTmp->Insert($nombres, $valores);
                    $Resultado=$objTmp->Ejecutar();

                    $NuevoStock = (int)$ConsultaDatos['stock'] - (int)$linea['cantidad'];

                }else{

                    $NuevoStock = (int)$ConsultaDatos['stock'] + (int)$linea['cantidad'];
    
                }
                    //Actualizo stock en articulos.
                    $nombres = array();
                    $valores = array();
                    $nombres[] = "stock";
                    $valores[] = $NuevoStock;
        
                    $lineaArticulo = new Consultas('articulos');
                    $lineaArticulo->Update($nombres, $valores);
                    $lineaArticulo->Where('codarticulo', $linea['codigo']);
                    $ConsultaArticulo = $lineaArticulo->Ejecutar();   
                
            }
        }

        //elimino las l??neas de la tabla temporal
        $lineaFactura = new Consultas('factulineatmp');
        $lineaFactura->Select();
        $lineaFactura->Where('codfactura', $codfactura);

        $lineaFactura->Orden('numlinea', 'ASC');

        $lineaConsulta = $lineaFactura->Ejecutar();
        $lineaDatos = $lineaConsulta['datos'];
        if($lineaConsulta['numfilas']>0){
            $lineaFactura = new Consultas('factulineatmp');
            $lineaFactura->Delete();
            $lineaFactura->Where('codfactura', $codfactura);

            $lineaConsulta = $lineaFactura->Ejecutar();
        }

        echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                    </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }
  
$obj = new Consultas('facturas');
$obj->Select();
$obj->Where('codfactura', $codfactura);
$paciente = $obj->Ejecutar();
$paciente = $paciente["datos"][0];


$oidestudio = '';
$oidpaciente = '';
$hace = _('Crea factura venta');

logger($UserID, $oidestudio, $oidpaciente, $hace);

    
} else {

$preciototal=0;
$codfacturatmp=strtotime(date('Y-m-d H:m:t'));

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

<div class="panel panel-default">
        <div class="panel-body">

<div id="exTab3" class="container">	

    <?php if ($mensaje!=""){ ?>
    <div id="tituloForm" class="header"><?php echo $mensaje;?></div>
    <?php } ?>
    <div class="container-fluid">
        <div class="row">
        <form class="form-horizontal" id="formulario" action='create.php' method='post' enctype="multipart/form-data">
            <input type="hidden" name="codfacturatmp" value="<?php echo $codfacturatmp;?>" >
            <input id="id" name="id" value="" type="hidden">
            <div class="col-xs-12">
                <div class="row">                            
                    <label class="control-label col-xs-1"><?php echo _('Cliente'); ?></label>
                    <div class="col-xs-2">
                        <input type="hidden" id="codcliente" name="DATA[codcliente]" value="">

                        <input type="text" class="form-control input-sm" id="Acodcliente" value="" placeholder="<?php echo _('Cliente'); ?>" required  data-index="1" >
                    </div>
                    <label class="control-label col-xs-1"><?php echo _('Cod. Factura'); ?></label>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" id="Acodfactura" name="DATA[codfactura]" data-index="2" value="" onblur="compruebonumerofactura();">
                    </div>
                    <label class="control-label col-xs-1"><?php echo _('Tipo'); ?>&nbsp;</label>
                    <div class="col-xs-2">
                        <select name="DATA[tipo]" id="Atipo" class="form-control" data-index="3">
                            <option value="" selected="selected">Seleccione</option>
                            <?php
                            $tipof=array(0=>'Contado', 1=>'Credito', 2=>'Nota Credito');
                            foreach($tipof as $tip=>$texto){
                                    ?><option value="<?php echo $tip;?>"><?php echo $texto;?></option><?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input placeholder="<?php echo _('Fecha'); ?>" class="form-control input-sm" size="26" type="text" value="" id="Afecha" name="DATA[fecha]" readonly  required data-index="4">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
                        </div> 
                    </div>
                </div>

                <div class="row">
                    
                    <label class="control-label col-xs-1">&nbsp;<?php echo _('Moneda'); ?></label>
                    <div class="col-xs-1">
                        <select name="DATA[moneda]" id="Amoneda" class="form-control" onchange="cambio();" data-index="5">
                            <option value="-1" >Moneda</option>
                            <?php
                            $nombre='';

                                $objMon = new Consultas('monedas');
                                $objMon->Select();
                                $objMon->Where('orden', '3', '<');
                                $objMon->Where('borrado', '0');
                                $objMon->Orden('orden', 'ASC');
                                $selMon=$objMon->Ejecutar();
                                $filasMon=$selMon['datos'];
                                $xmon=1;
                                foreach($filasMon as $fila){
                                        ?> <option value="<?php echo $xmon;?>"><?php echo $fila['simbolo'];?></option> <?php
                                    $xmon++;
                                }	
                            ?>
                        </select>
                    </div>                    
                    <label class="control-label col-xs-1"><?php echo _('RUT'); ?></label>
                    <div class="col-xs-2">
                        <input type="text" class="form-control input-sm" id="nrut" value="" placeholder="<?php echo _('RUT'); ?>" data-index="6" readonly>
                    </div>
                    <label class="control-label col-xs-1"><?php echo _('IVA'); ?></label>
                    <div class="col-xs-1">
                    <select name="DATA[iva]" id="Aiva" class="form-control" data-index="7" onchange="cambio();">
                            <option value="-1" >Seleccione</option>
                            <?php
                            $objMon = new Consultas('impuestos');
                            $objMon->Select();
                            $objMon->Where('borrado', '0');
                            $selMon=$objMon->Ejecutar();
                            $filasMon=$selMon['datos'];
                            foreach($filasMon as $fila){
                                    ?> <option value="<?php echo $fila['codimpuesto'];?>"><?php echo $fila['nombre'].'~'.$fila["valor"];?></option> <?php
                            }                                    
                            ?>
                        </select>
                    </div>
                    <div class="col-xs-2">
                    <select name="DATA[codformapago]" id="codformapago" class="form-control" data-index="8" >
                            <option value="-1" >Forma de pago</option>
                            <?php
                            $objMon = new Consultas('formapago');
                            $objMon->Select();
                            $objMon->Where('borrado', '0');
                            $selMon=$objMon->Ejecutar();
                            $filasMon=$selMon['datos'];
                            foreach($filasMon as $fila){
                                if ($fila['codformapago']==$paciente["codformapago"]) {
                                    ?> <option value="<?php echo $fila['codformapago'];?>" selected><?php echo $fila['nombrefp'];?></option> <?php
                                }else{
                                    ?> <option value="<?php echo $fila['codformapago'];?>"><?php echo $fila['nombrefp'];?></option> <?php
                                }
                            }                                    
                            ?>
                        </select>                       
                    </div>                    
                    <label class="control-label col-xs-1"><?php echo _('Tipo cambio'); ?></label>
                        <?php echo $moneda1;?>&nbsp;->&nbsp;	<?php echo $moneda2;?>
                    <div class="col-xs-1">
                        <input name="tipocambio" type="text" class="form-control" id="tipocambio" size="5" maxlength="5" onchange="cambio();" onblur="cambio();" data-index="8" readonly>
                    </div>
                </div>
            </div> 
            <input name="DATA[totalfactura]" type="hidden" id="totalfactura" value=""> 
            <?php
                $objDat = new Consultas('datos');
                $objDat->Select('bancos');
                $objDat->Where('coddatos', '0');
                $selDat=$objDat->Ejecutar();
                $filasDat=$selDat['datos'][0]['bancos'];
                $Datos_banco = explode('#', $filasDat);
                $banco[0] = explode('-', $Datos_banco[0]);
                $banco[1] = explode('-', $Datos_banco[1]);
                $observacion ='Puede hacer efectivo el pago de esta factura: '. $banco[0][0].' '.$banco[0][1].' '.$banco[0][2].'-'.$banco[0][3]. ' / '. $banco[1][0].' '.$banco[1][1].' '.$banco[1][2].'-'.$banco[1][3];
                //BROU-Pesos-N?? cuenta:CA 000608603-00001-Si#BROU-U$S-N?? cuenta:CA 000608603-00002-Si


            ?>
                <div style="position:relative; bottom: -420px; left: -30px; " >
                <label class="control-label col-xs-2"><?php echo _('Observaci??n'); ?></label>
                    <div class="col-xs-6">
                        <textarea name="DATA[observacion]" rows="2" cols="50" class="form-control" id="observacion"><?php echo $observacion;?></textarea>
                    </div>
                </div>

            </form>
        </div>
        <div class="row">
            <fieldset class="scheduler-border">
            <legend>Nuevo art??culo a la factura</legend>
            <div class="col-xs-12">
            <form id="form_lineas" method="post" action="frame_lineas.php" target="frame_lineas">
                <div class="row">
                    <label class="control-label col-xs-1"><?php echo _('C??digo'); ?></label>
                    <div class="col-xs-2">
                        <input type="hidden" name="codarticulo" class="form-control" id="codarticulo" size="15" maxlength="15">
                        <input type="text" id="articulos" autocomplete="off" class="form-control" data-index="9"/>
                    </div>                    
                    <label class="control-label col-xs-1"><?php echo _('Descripcion'); ?></label>
                    <div class="col-xs-4">
                        <input name="descripcion" type="text" class="form-control" id="descripcion" size="45" maxlength="50" data-index="10">
                    </div>
                    <label class="control-label col-xs-1"><?php echo _('Moneda'); ?></label>
                    <div class="col-xs-2">
                        <input name="monedaShow" type="text" class="form-control" id="monedaShow" readonly>
                        <input name="moneda" id="moneda" type="hidden" >
                    </div>
                    <div class="col-xs-1" >
                        <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();validar();" ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
                    </div>
                </div>
                <div class="row">
                    <label class="control-label col-xs-1"><?php echo _('Detalles'); ?></label>
                    <div class="col-xs-2">
                        <textarea name="detalles" rows="2" cols="50" class="form-control" id="detalles" data-index="11"> </textarea>
                    </div>
                    <label class="control-label col-xs-1"><?php echo _('Precio'); ?></label>
                    <div class="col-xs-1">
                        <input name="precio" type="text" class="form-control" id="precio" onChange="actualizar_importe()" data-index="12">
                    </div>

                    <label class="control-label col-xs-1"><?php echo _('Cantidad'); ?></label>
                    <div class="col-xs-1">
                        <input name="cantidad" type="text" class="form-control" id="cantidad" value="1" onChange="actualizar_importe()" data-index="13">
                    </div>

                    <label class="control-label col-xs-1"><?php echo _('Dcto. %'); ?></label>
                    <div class="col-xs-1">
                        <input name="descuento" type="text" class="form-control" id="descuento" onChange="actualizar_importe()" data-index="14">
                    </div>

                    <label class="control-label col-xs-1"><?php echo _('Importe'); ?></label>
                    <div class="col-xs-1">
                        <input name="importe" type="text" class="form-control" id="importe" value="0" readonly>
                    </div>
                    <div class="col-xs-1">
                    <button class="btn btn-primary left-margin btn-xs" onClick="limpiar();" ><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
                    </div>
                </div>
                <input id="codfacturatmp" name="codfacturatmp" value="<?php echo $codfacturatmp;?>" type="hidden">
                <input id="codfamilia" name="codfamilia" value="" type="hidden">
                <input type="hidden" id="Bcodcliente" name="codcliente" value="">
            
            </div>
            </fieldset>
        </div>
        <div class="row">
            <div class="col-xs-12">
            <iframe src="frame_lineas.php" width="100%" height="200" scrolling="auto"
             id="frame_lineas" name="frame_lineas" frameborder="0">
                <ilayer width="100%" height="200" id="frame_lineas" name="frame_lineas"></ilayer>
            </iframe>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-8">

            </div>
            <div class="col-xs-4">

            <div class="row">
            <label class="control-label col-xs-6"><?php echo _('Sub-Total'); ?></label>
                <div class="col-xs-3">
                    <input type="text" class="form-control" id="monShow" readonly>
                </div>
                <div class="col-xs-3">
			        <input class="form-control" name="baseimponible" type="text" id="baseimponible" size="12" readonly> 
		        </div>
			</div>
			<div class="row">
                <label class="control-label col-xs-6"><?php echo _('IVA'); ?></label>
				<div class="col-xs-3">
                    <input type="text" class="form-control" id="monSho" readonly>
                </div>
                <div class="col-xs-3">                    
			        <input class="form-control" name="baseimpuestos" type="text" id="baseimpuestos" size="12" readonly>
		        </div>
            </div>
            <div class="row">
              <label class="control-label col-xs-6"><?php echo _('Importe total'); ?></label>
				<div class="col-xs-3">
                    <input type="text" class="form-control" id="monSh" readonly>
                </div>
                <div class="col-xs-3">   
			        <input class="form-control" name="preciototal" type="text" id="preciototal" size="12" readonly> 
		        </div>
			</div>

            </div>
        </div>


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
        <button class="btn btn-primary left-margin btn-xs" onclick="event.preventDefault();validar_cabecera();"><?php echo _('Guardar'); ?></button>
        <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" onclick="
        <?php
$arr = [ 'codfactura' => $codfacturatmp];
?>
        event.preventDefault();


        cancelar('factulineatmp', <?=htmlentities(json_encode($arr))?>);  " >        
        
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
    }).on('changeDate', function (ev) {
        busco_tipocambio()
    });


</script>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


//busco_tipocambio();
//cambio();
</script>

<?php
include_once "../common/footer.php";

?>