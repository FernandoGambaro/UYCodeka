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


    $codrecibo=isset($_GET['codrecibo']) ? $_GET['codrecibo'] : '';

    $obj = new Consultas('recibos');
    $obj->Select('recibos.codcliente, recibos.fecha, recibos.moneda, recibos.importe');

    $obj->Where('codrecibo', $codrecibo);

    //print_r($obj);
    $paciente = $obj->Ejecutar();
    $paciente = $paciente['datos'][0];

    $objTmp = new Consultas('recibosfactura');
    $objTmp->Select();
    $objTmp->Where('codrecibo', $codrecibo, '=');

    $resultado=$objTmp->Ejecutar();

    $rows = $resultado['datos'];

    if($resultado["numfilas"]>0){

        $objTmp = new Consultas('recibosfacturatmp');
        $objTmp->Delete();
        $objTmp->Where('codrecibo', $codrecibo);
        $resul=$objTmp->Ejecutar();    

        foreach($rows as $row){

            $nombres = array();
            $valores = array();
            
            $nombres[] = 'totalfactura';
            $valores[] =  $row['totalfactura'];            
            $nombres[] = 'codrecibo';
            $valores[] =  $row['codrecibo'];
            $nombres[] = 'codfactura';
            $valores[] =  $row['codfactura'];


            $objTmp = new Consultas('recibosfacturatmp');
            $objTmp->Insert($nombres, $valores);

            $resul=$objTmp->Ejecutar();    
        }
    }

    $objtmp = new Consultas('recibospagotmp');
    $objtmp->Delete();
    $objtmp->Where('codrecibo', $codrecibo);    

    $objtmp->Ejecutar();

    $objTmp = new Consultas('recibospago');
    $objTmp->Select();
    $objTmp->Where('codrecibo', $codrecibo);
    $resultado=$objTmp->Ejecutar();
    $rows=$resultado['datos'];

    foreach($rows as $row){

    $nombres = array();
    $valores = array();

    $nombres[] = 'codrecibo';
    $valores[] =  $row['codrecibo'];
    $nombres[] = 'tipo';
    $valores[] =  $row['tipo'];
    $nombres[] = 'codentidad';
    $valores[] =  $row['codentidad'];
    $nombres[] = 'numeroserie';
    $valores[] =  $row['numero'];
    $nombres[] = 'moneda';
    $valores[] =  $row['moneda'];
    $nombres[] = 'tipocambio';
    $valores[] =  $row['tipocambio'];
    $nombres[] = 'importe';
    $valores[] =  $row['importe'];

    $nombres[] = 'fecha';
    $valores[] =  $row['fecha'];
    $nombres[] = 'observaciones';
    $valores[] =  $row['observaciones'];

    $objTmp = new Consultas('recibospagotmp');
    $objTmp->Insert($nombres, $valores);
    $resultado=$objTmp->Ejecutar();
}


//Cargo los datos del recibo en las tablas temporales


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
            <div class="col-xs-12">
                <div class="row">                            
                    <label class="control-label col-xs-1"><?php echo _('Cliente'); ?></label>
                    <div class="col-xs-2">
                        <input type="hidden" id="codcliente" value="<?php echo $paciente["codcliente"];?>">
                        <?php
                            $objclientes = new Consultas('clientes');
                            $objclientes->Select();
                            $objclientes->Where('codcliente', $paciente["codcliente"]);
                            $objclientes->Where('borrado', '0');

                            $Ejeclientes = $objclientes->Ejecutar();
                            $Ejeclientes_rows=$Ejeclientes["numfilas"];
                            $rowclientes = $Ejeclientes["datos"][0];
                            if(strlen($rowclientes["empresa"])>0){
                                $nombre =$rowclientes["empresa"];
                            }else{
                                $nombre = $rowclientes["nombre"]. ''.$rowclientes["apellido"];
                            }

                            ?>
                        <input type="text" class="form-control input-sm" id="Acodcliente" value="<?php echo $nombre;?>" placeholder="<?php echo _('Cliente'); ?>" required  data-index="1" readonly>
                    </div>

                    <label class="control-label col-xs-1"><?php echo _('Nº recibo'); ?></label>
                    <div class="col-xs-1">
                        <input type="text" class="form-control" id="codrecibo" data-index="2" value="<?php echo $codrecibo;?>" readonly>
                    </div>
                       

                    <div class="col-xs-2">
                        <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input placeholder="<?php echo _('Fecha'); ?>" class="form-control input-sm" size="26" type="text" value="<?php echo implota($paciente['fecha']);?>" id="fecha" readonly  required data-index="4">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
                        </div> 
                    </div>

                    
                    <label class="control-label col-xs-1">&nbsp;<?php echo _('Moneda'); ?></label>
                    <div class="col-xs-1">
                    <select id="Amoneda" class="form-control" onchange="cambio();" data-index="5">
                            <option value="-1" >Moneda</option>
                            <?php
                            $nombre='';
                            if(strlen($paciente["moneda"])>0){
                                $xmon=1;
                                foreach($filasMon as $fila){
                                    if ($xmon==$paciente["moneda"]) {
                                        ?> <option value="<?php echo $xmon;?>" selected><?php echo $fila['simbolo'];?></option> <?php
                                    }else{
                                        ?> <option value="<?php echo $xmon;?>"><?php echo $fila['simbolo'];?></option> <?php
                                    }
                                    $xmon++;
                                }
                            }	
                            ?>
                        </select>
                    </div>                    

                    <label class="control-label col-xs-1"><?php echo _('Tipo cambio'); ?></label>
                        <?php echo $moneda1;?>&nbsp;->&nbsp;	<?php echo $moneda2;?>
                    <div class="col-xs-1">
                        <input type="text" class="form-control" id="tipocambio" size="5" maxlength="5" onchange="cambio();" onblur="cambio();" data-index="8" readonly>
                    </div>
                </div>
            </div>   
            </form>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <iframe src="rejilla_facturas.php?codcliente=<?php echo $paciente["codcliente"];?>" width="100%" height="200" scrolling="auto"
                id="frame_lineas" name="frame_lineas" frameborder="0">
                    
                </iframe>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <fieldset class="scheduler-border">
                    <legend>Comprobante de pago</legend>
                    <form id="formulario_pago" method="post" action="rejilla_pago.php" target="frame_pago">
                    <div class="col-xs-12">
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Forma pago'); ?></label>
                            <div class="col-xs-2">
                                <select name="tipo" id="Atipo" class="form-control" data-index="6">
                                <?php 
                                $tipofa = array( 0=>"Seleccione uno", 1=>"Contado", 2=>"Cheque",3=>"Giro Bancario", 4=>"Giro RED cobranza", 5=>"Resguardo");
                                foreach ($tipofa as $key => $i ) {
                                    if ( 0==$key ) {
                                        echo "<option value='$key' selected>$i</option>";
                                    } else {
                                        echo "<option value='$key'>$i</option>";
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <label class="control-label col-xs-1"><?php echo _('Moneda'); ?></label>                            
                            <div class="col-xs-2">
                                <select name="moneda" id="moneda" class="form-control"  data-index="5">
                                    <option value="-1" >Moneda</option>
                                    <?php
                                    $nombre='';
                                    if(strlen($paciente["moneda"])>0){
                                        $xmon=1;
                                        foreach($filasMon as $fila){
                                            if ($xmon==$paciente["moneda"]) {
                                                ?> <option value="<?php echo $xmon;?>" selected><?php echo $fila['simbolo'];?></option> <?php
                                            }else{
                                                ?> <option value="<?php echo $xmon;?>"><?php echo $fila['simbolo'];?></option> <?php
                                            }
                                            $xmon++;
                                        }
                                    }	
                                    ?>
                                </select>
                            </div>
                            <label class="control-label col-xs-1"><?php echo _('Vencimiento'); ?></label>
                            <div class="col-xs-4">
                                <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input placeholder="<?php echo _('Fecha'); ?>" class="form-control input-sm" size="26" type="text"
                                     value="<?php echo implota($paciente['fecha']);?>" id="Afecha" name="fecha" readonly  required data-index="4">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-5">
                            <select id="codentidad" name="codentidad" class="form-control" data-index="10">
							<option value="-1">Seleccione&nbsp;una&nbsp;Entidad&nbsp;Bancaria</option>
								<?php
                                $objentidades = new Consultas('entidades');
                                $objentidades->Select();
                                $objentidades->Where('borrado', '0');
                                $objentidades->Orden('nombreentidad', 'ASC');

                                $Ejeentidades = $objentidades->Ejecutar();
                                $Ejeentidades_rows=$Ejeentidades["numfilas"];
                                $rowentidades = $Ejeentidades["datos"];

								foreach($rowentidades as $row) {

									if ($paciente["codentidad"] == $row["codentidad"]) { ?>
								<option value="<?php echo $row["codentidad"]?>" selected="selected"><?php echo $row["nombreentidad"];?></option>
								<?php } else { ?>
								<option value="<?php echo $row["codentidad"]?>"><?php echo $row["nombreentidad"];?></option>
								<?php } 
								} ?>
								</select>

                            </div>
                            <label class="control-label col-xs-1"><?php echo _('Nº&nbsp;serie'); ?></label>
                            <div class="col-xs-2">
                                <input id="numeroserie" type="text" class="form-control" name="numeroserie" maxlength="30" data-index="11">
                            </div>
                            <label class="control-label col-xs-2"><?php echo _('Nº&nbsp;Doc.'); ?></label>
                            <div class="col-xs-2">
                                <input id="numero" type="text" class="form-control" name="numero" maxlength="30" data-index="12">
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Detalles'); ?></label>
                            <div class="col-xs-4">
                                <textarea name="observaciones" rows="2" cols="50" class="form-control" id="detalles" data-index="11"> </textarea>
                            </div>
                            <label class="control-label col-xs-2"><?php echo _('Importe'); ?></label>
                            <div class="col-xs-2">
                                <input name="importe" type="text" class="form-control" id="importe" value="0">
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-xs-2" >
                                <button class="btn btn-primary left-margin btn-xs" onClick="event.preventDefault();validar();" ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar</button>
                            </div>
                        <div class="col-xs-2">
                            <button class="btn btn-primary left-margin btn-xs" onClick="limpiar();" ><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Limpiar</button>
                            </div>                        
                        </div>
                        <input id="codrecibotmp" name="codrecibo" value="<?php echo $codrecibo;?>" type="hidden">                    
                    </div>
                    </form>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
            <legend>Facturas en el recibo</legend>
            <form id="formulario_facturas_sel" name="formulario_facturas_sel" method="post" action="rejilla_facturas_sel.php" target="frame_facturas_sel">
                <input name="codfactura" id="acodfactura" value="<?php echo $codfactura;?>" type="hidden">
                <input name="codrecibo" id="acodrecibo" value="<?php echo $codrecibo;?>" type="hidden">
                <input name="totalfactura" id="totalfacura" value="<?php echo $paciente['totalfactura'];?>" type="hidden">
                
                <iframe width="100%" height="100" id="frame_facturas_sel" name="frame_facturas_sel" frameborder="0">
					<ilayer width="100%" height="100" id="frame_facturas_sel" name="frame_facturas_sel"></ilayer>
				</iframe>	
            </form>
            </div>
            <div class="col-xs-6">
            <legend>Comprobantes en el recibo</legend>
                <iframe src="rejilla_pago.php?codrecibo=<?php echo $codrecibo;?>" width="550" height="160" id="frame_pago" name="frame_pago" frameborder="0">
					<ilayer width="550" height="160" id="frame_pago" name="frame_pago"></ilayer>
				</iframe>	
            </div>
        </div>
        <div class="row">
            <label class="control-label col-xs-2"><?php echo _('Total a pagar'); ?></label>
            <div class="col-xs-1">
                <input class="form-control" type="text" id="totalfacturas" size="12" value="" readonly> 
            </div>

            <label class="control-label col-xs-2"><?php echo _('Total recibo'); ?></label>
            <div class="col-xs-1">                    
                <input class="form-control" type="text" id="totalrecibo" size="12" readonly>
            </div>

            <label class="control-label col-xs-1"><?php echo _('Saldo'); ?></label>
            <div class="col-xs-1">   
                <input class="form-control" type="text" id="saldo" size="12" value="" readonly> 
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
        <button class="btn btn-primary left-margin btn-xs" onclick="event.preventDefault();Guardodatos();"><?php echo _('Guardar'); ?></button>
        <?php } ?>
        <button class='btn btn-danger left-margin btn-xs' data-dismiss="modal" >
        <span class='glyphicon glyphicon-ban-circle' data-dismiss="modal" onclick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();"></span> <?php echo _('Cancelar'); ?></button>
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


document.formulario_facturas_sel.submit();


</script>

<?php
include_once "../common/footer.php";

?>