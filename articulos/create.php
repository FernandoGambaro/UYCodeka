<?php
// set page headers
$page_title = _('Datos del Cliente'); 
include_once "header.php";

// isset() is a PHP function used to verify if ID is there or not

require_once __DIR__ .'/../common/fechas.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   
require_once __DIR__ .'/../common/sectores.php';   //Array con lista de los sectores del sistema

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;

require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../classes/Encryption.php';

function fn_resize($image_resource_id,$width,$height) {
    $target_width =200;
    $target_height =200;
    $target_layer=imagecreatetruecolor($target_width,$target_height);
    imagecopyresampled($target_layer,$image_resource_id,0,0,0,0,$target_width,$target_height, $width,$height);
    return $target_layer;
    }
    

$mensaje='';
$obj = new Consultas('articulos');

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
    //$obj->Where(trim($attr), trim($valor)); 
    //var_dump($obj);
    $paciente = $obj->Ejecutar();
    $codarticulo=$paciente['id'];
    /////////////////////////////////////////////
    if($paciente["estado"]=="ok"){

        $valid_extensions = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
        $path = '../fotos/'; // upload directory
        if($_FILES['foto']["error"] == 0){
            $img = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $type = $_FILES["foto"]["type"];
            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            // can upload same image using rand function
            $final_image = rand(1000,1000000).$img;
            // check's valid format
 
            if(!in_array($type, $valid_extensions)) { 
             $mensaje.= " - El archivo que subio no es una imagen válida";
            } else {
 
             $target= "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"];
 
             list($width, $height, $type) = getimagesize($tmp);
             $old_image = load_image($tmp, $type);
             
             //$new_image = resize_image(200, 180, $old_image, $width, $height);
             $image_width_fixed = resize_image_to_width(200, $old_image, $width, $height);
             //$image_height_fixed = resize_image_to_height(900, $old_image, $width, $height);
             //$image_scaled = scale_image(0.8, $old_image, $width, $height);
             
             //save_image($new_image, 'wallpapers/resized-'.basename($filename), 'jpeg', 75);
             save_image($image_width_fixed, $target, 'jpeg', 75);
             //save_image($image_height_fixed, 'wallpapers/fixed-height-'.basename($filename), 'jpeg', 75);
             //save_image($image_scaled, 'wallpapers/scaled-'.basename($filename), 'jpeg', 75);
 
                 //move_uploaded_file($thumb, "../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"]);
                 //$mensaje.=resize_image('max',"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],"../fotos/"."foto".$codarticulo . $_FILES["foto"]["name"],200,200);
 
                 if(file_exists($target)) 
                 {
                     $obj = new Consultas('articulos');
                     $nom[] = 'imagen';
                     $val[] = strtolower("foto".$codarticulo . $_FILES["foto"]["name"]);
 
                     $obj->Update($nom, $val); 
                     $obj->Where(trim($attr), trim($valor)); 
                     $paciente = $obj->Ejecutar();
                 }
             }
        }


        $datosguardados=1;


        $oidestudio = '';
        $oidpaciente = '';
        $hace = _('Nuevo cliente '). $paciente["nombre"]." ".$paciente["apellido"];

        logger($UserID, $oidestudio, $oidpaciente, $hace);

        echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
 
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo _("Error! No se pudieron guardar los cambios.");
        echo "</div>";
    }


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
<form class="form-horizontal" id="formulario" action='create.php' method='post' enctype="multipart/form-data">

<div class="panel panel-default">
        <?php if($datosguardados==1){ ?>
            <div class="alert alert-success"><?php echo _('Cambios guardados.'); ?></div>
        <?php }else{ ?>
        <div class="panel-heading"><?php echo _('Datos del artículo');?></div>
        <?php } ?>

        <div class="panel-body">


<div id="exTab3" class="container">	


<ul class="nav nav-tabs nav-pills tabs">
<li class="active"><a href="#1b" data-toggle="tab"><?php echo _('Datos Básicos'); ?></a></li>
<li><a href="#2b" data-toggle="tab"><?php echo _('Otros datos'); ?></a></li>
</ul>



<div class="tab-content col-xs-12 ">
<div class="tab-pane fade in active" id="1b">
    <?php if ($mensaje!=""){ ?>
    <div id="tituloForm" class="header"><?php echo $mensaje;?></div>
    <?php } ?>
    <div class="container-fluid">


                <div class="row">
                    <input type="hidden" name="DATA[codarticulo]" id="codarticulo" value="" >
                    <input type="hidden" name="codarticulo" value="" >
                    <input id="id" name="id" value="<?php echo $codarticulo?>" type="hidden">
                    <div class="col-xs-4">
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Referencia'); ?></label>
                            <div class="col-xs-10">
                            <input type="text" class="form-control input-sm" id="Areferencia" name="DATA[referencia]" value="" placeholder="<?php echo _('referencia'); ?>" required  data-index="1">
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Descripción'); ?></label>
                            <div class="col-xs-10">
                            <textarea type="text" class="form-control" id="tdescripcion" name="DATA[descripcion]" data-index="2" ></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Familia'); ?></label>
                            <div class="col-xs-10">
                                <select name="DATA[codfamilia]" id="familia" class="form-control" data-index="3">
                                <option value="-1" >Familia</option>
                                <?php
                                $objBaja = new Consultas('familias');
                                $objBaja->Select();
                                $objBaja->Where('borrado', '0', '=');

                                $EjeBaja = $objBaja->Ejecutar();
                                $EjeBaja_rows=$EjeBaja["numfilas"];
                                $rowsBaja = $EjeBaja["datos"];
                        
                                foreach($rowsBaja as $rowBaja){
                                    ?> <option value="<?php echo $rowBaja['codfamilia'];?>" ><?php echo $rowBaja['nombre'];?></option><?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Proveedor'); ?></label>
                            <div class="col-xs-10">
                                <select name="DATA[codproveedor1]" id="codproveedor" class="form-control" data-index="4" >
                                <option value="-1" >Proveedor</option>
                                <?php
                                $objBaja = new Consultas('proveedores');
                                $objBaja->Select('codproveedor,nombre,nif');
                                $objBaja->Where('borrado', '0', '=');

                                $EjeBaja = $objBaja->Ejecutar();
                                $EjeBaja_rows=$EjeBaja["numfilas"];
                                $rowsBaja = $EjeBaja["datos"];
                                foreach($rowsBaja as $rowBaja){
                                    ?> <option value="<?php echo $rowBaja['codproveedor'];?>"><?php echo $rowBaja['nombre'];?></option> <?php
                                }

                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Proveedor'); ?></label>
                            <div class="col-xs-10">
                            <select name="DATA[codproveedor2]" id="codproveedor2" class="form-control" data-index="5" >
                                <option value="-1" >Proveedor</option>
                                <?php
                                $objBaja = new Consultas('proveedores');
                                $objBaja->Select('codproveedor,nombre,nif');
                                $objBaja->Where('borrado', '0', '=');

                                $EjeBaja = $objBaja->Ejecutar();
                                $EjeBaja_rows=$EjeBaja["numfilas"];
                                $rowsBaja = $EjeBaja["datos"];
                                foreach($rowsBaja as $rowBaja){
                                    ?> <option value="<?php echo $rowBaja['codproveedor'];?>"><?php echo $rowBaja['nombre'];?></option> <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4">&nbsp;<?php echo _('Stock'); ?></label>
                            <div class="col-xs-8">
                            <input type="text" class="form-control input-sm" id="nStock" name="DATA[stock]" value="" placeholder="<?php echo _('Stok'); ?>" data-index="6"> 
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Stock mínimo'); ?></label>
                            <div class="col-xs-3">
                            <input type="text" class="form-control input-sm" id="Wstock_minimo" name="DATA[stock_minimo]" value="" placeholder="<?php echo _('Stock mínimo'); ?>" data-index="7">
                            </div>

                            <label class="control-label col-xs-2"><?php echo _('Avisar'); ?>&nbsp;</label>
                            <div class="col-xs-3">
                                <select name="DATA[aviso_minimo]" id="Waviso_minimo" class="form-control" data-index="8">
                                    <option value="0">No</option>
                                    <option value="1" selected="selected">Si</option>
                                </select>
                            </div>

                        </div>
                        <div class="row">

                            <label class="control-label col-xs-2"><?php echo _('Ubicación'); ?></label>
                            <div class="col-xs-10">

                            <select name="DATA[codubicacion]" id="Aubicacion" class="form-control" data-index="9" >
                                <option value="-1" >Ubicación</option>
                                <?php
                                $objBaja = new Consultas('ubicaciones');
                                $objBaja->Select('*');
                                $objBaja->Where('borrado', '0', '=');

                                $EjeBaja = $objBaja->Ejecutar();
                                $EjeBaja_rows=$EjeBaja["numfilas"];
                                $rowsBaja = $EjeBaja["datos"];
                                foreach($rowsBaja as $rowBaja){
                                    ?> <option value="<?php echo $rowBaja['codubicacion'];?>"><?php echo $rowBaja['nombre'];?></option> <?php
                                }
                                ?>
                                </select>

                            </div>
                        </div>

                    </div>
                    <!-- /////////////// -->
                    <div class="col-xs-4">
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Datos&nbsp;del&nbsp;producto'); ?>&nbsp;</label>
                            <div class="col-xs-8">
                            <textarea name="DATA[datos_producto]" cols="41" rows="2" id="datos_producto" class="form-control" data-index="10"></textarea>
                            </div> 
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4">&nbsp;<?php echo _('Descripci&oacute;n&nbsp;corta'); ?></label>
                            <div class="col-xs-8">
                            <input type="text" class="form-control input-sm" id="descripcion_corta" name="DATA[descripcion_corta]" value="" placeholder="<?php echo _('Descripcion corta'); ?>" data-index="11"> 
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Impuesto'); ?></label>
                            <div class="col-xs-8">
                                <select name="DATA[impuesto]" id="impuesto" class="form-control" onchange="precioiva();" data-index="12">
                                <option value="-1" >Impuestos</option>
                                <?php
                                $objBaja = new Consultas('impuestos');
                                $objBaja->Select();
                                $objBaja->Where('borrado', '0', '=');

                                $EjeBaja = $objBaja->Ejecutar();
                                $EjeBaja_rows=$EjeBaja["numfilas"];
                                $rowsBaja = $EjeBaja["datos"];
                        
                                foreach($rowsBaja as $rowBaja){
                                    ?> <option value="<?php echo $rowBaja['codimpuesto'];?>"><?php echo $rowBaja['nombre'].'~'.$rowBaja["valor"];?></option> <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4" ><?php echo _('Embalaje'); ?>&nbsp;</label>
                            <div class="col-xs-8">
                            <select name="DATA[codembalaje]" id="codembalaje" class="form-control" data-index="13" >
                                <option value="-1" >Embalaje</option>
                                <?php
                                $embalajes = new Consultas('embalajes');
                                $embalajes->Select();
                                $embalajes->Where('borrado','0' );
                                $Ejeembalajes=$embalajes->Ejecutar();

                                $rowsembalajes = $Ejeembalajes["datos"]; 

                                    foreach($rowsembalajes as $rowBaja){
                                        ?> <option value="<?php echo $rowBaja['codembalaje'];?>"><?php echo $rowBaja['descripcion'];?></option> <?php
                                    }	
                                
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Unidades&nbsp;por&nbsp;caja'); ?>&nbsp;</label>
                            <div class="col-xs-8">
                            <input type="text" class="form-control input-sm" id="unidades_caja" name="DATA[unidades_caja]" value="" placeholder="<?php echo _('Unidades x caja'); ?>" data-index="14">
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Código barras'); ?>&nbsp;</label>            
                            <div class="col-xs-8">
                                <input type="text" class="form-control input-sm" id="codigobarras" name="DATA[codigobarras]" value="" placeholder="<?php echo _('Código de barras'); ?>" data-index="15"> 
                            </div> 
                        </div>
                        <div class="row">
                        <label class="control-label col-xs-4"><?php echo _('Código QR'); ?>&nbsp;</label>            
                        </div>
                        <div class="row">
                            <img src="../barcode/gd.php?text=<?php echo $codigobarras;?>&height=25"></td>
                                
                                <?php
                                $texto="Código-".$codigobarras."-";
                                $texto.="Sector-".$paciente["sector"]."-Pasillo-".$paciente["pasillo"]."-Modulo-".$paciente["modulo"]."-Estante-".$paciente["estante"];
                                $width = $height = 50;
                                $file = "../tmp/qr".$codigobarras.".png";
                                
                                echo "<img src='../barcode/qrcode.php?texto=".$texto."&width=".$width."&height=".$height."&file=".$file."&accion=u'>"; ?>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Fecha alta'); ?></label>
                            <div class="col-xs-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input placeholder="<?php echo _('Fecha alta'); ?>" class="form-control input-sm" size="26" type="text" value="" id="Afecha_alta" name="DATA[fecha_alta]" readonly  required data-index="16">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
                            </div> 
                            </div>       
                        </div>
                        <div class="row">
                            <label class="control-label col-xs-4"><?php echo _('Fecha vencimiento'); ?>&nbsp;</label>
                            <div class="col-xs-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input placeholder="<?php echo _('Fecha vencimiento'); ?>" class="form-control input-sm" size="26" type="text" value="" id="fecha_vencimiento" name="DATA[fecha_vencimiento]" readonly  required data-index="17">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>&nbsp;</span>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <fieldset class="scheduler-border">
                                <div class="row">
                                    <label class="control-label col-xs-4"><?php echo _('Precio&nbsp;ticket'); ?></label>                    
                                    <div class="col-xs-8">
                                        <select name="DATA[precio_ticket]" id="Aprecio_ticket" class="form-control" data-index="18">
                                            <option value="0">No</option>
                                            <option value="1" selected="selected">Si</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="row">
                                    <label class="control-label col-xs-4"><?php echo _('Modificar&nbsp;ticket'); ?>&nbsp;</label>            
                                    <div class="col-xs-8">
                                        <select name="DATA[modificar_ticket]" id="amodificar_ticket" class="form-control" data-index="19">
                                            <option value="0">No</option>
                                            <option value="1" selected="selected">Si</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="control-label col-xs-4"><?php echo _('Observaciones'); ?>&nbsp;</label>
                                    <div class="col-xs-8">
                                    <textarea name="DATA[observaciones]" cols="35" rows="2" id="Wobservaciones" class="form-control" data-index="20"></textarea>
                                    </div>
                                </div>
                            </fieldset> 
                        </div>


                    </div>

                    <!-- ////////////////////////////////  -->
                    <div class="form-group">

                                        
                    </div>     
                    <!-- ////////////////////////////////  -->


                            <!-- ////////////////////////////////  -->            
                </div>
    </div>
</div> <!--Fin 1b  *****************************************************************-->

<div class="tab-pane" id="2b">
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-12">
            
                <div class="row">
                    <div class="col-xs-12">
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000" />

                        <div class="file-upload btn btn-primary"><span>Imagen&nbsp;[200x200], jpg/png</span> 
                        </div>	
                        <div class="custom-file mb-12">
                            <input type="file" class="custom-file-input" name="foto" id="foto" onchange="PreviewImage();">
                            <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <img id="uploadPreview" style="height: 170px; display: none;" />
                    <script type="text/javascript">
                    function PreviewImage() {
                        var oFReader = new FileReader();
                        oFReader.readAsDataURL(document.getElementById("foto").files[0]);
                
                        oFReader.onload = function (oFREvent) {
                            document.getElementById("uploadPreview").src = oFREvent.target.result;
                            $("#uploadPreview").show();
                        };
                    };
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="col-xs-8">
        <!-- ////////////////////////////////  -->
            <div class="row">
                <div class="col-xs-12">
                    <fieldset style="width: 100%;" >
                        <legend>Código contable</legend>
                        <label class="control-label col-xs-2">Compra</label>
                        <div class="col-xs-3">
                        <input type="hidden" id="plancuentac" name="DATA[plancuentac]" value="">

                        <input type="text" id="Wplancuentac" value="" autocomplete="off" class="form-control"  data-index="21"/>
                        </div>
                        <label class="control-label col-xs-2">Venta</label>
                        <div class="col-xs-3">
                            <input type="hidden" id="plancuentav" name="DATA[plancuentav]" value="" >
     
                            <input type="text" id="Wplancuentav" value="" autocomplete="off" class="form-control"  data-index="22"/>
                        </div>
                    </fieldset>
                </div>
            </div>        

            <div class="row">
                <div class="col-xs-12">
                    <fieldset style="width: 100%;" >
                        <legend>Precios</legend>
                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Moneda'); ?>&nbsp;</label>
                            <div class="col-xs-3">
                                <select name="DATA[moneda]" id="moneda" class="form-control"  data-index="23">
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
                                            if ($xmon==$paciente["moneda"]) {
                                                ?> <option value="<?php echo $xmon;?>" selected><?php echo $fila['descripcion'];?></option> <?php
                                            }else{
                                                ?> <option value="<?php echo $xmon;?>"><?php echo $fila['descripcion'];?></option> <?php
                                            }
                                            $xmon++;
                                        }	
                                    ?>
                                </select>
                            </div>        
                            <div class="col-xs-6">
                                <label class="control-label col-xs-2"><?php echo _('Comisión'); ?>&nbsp;</label>
                                <div class="col-xs-4">
                                <input type="text" class="form-control input-sm" id="comision" name="DATA[comision]" value="" placeholder="<?php echo _('Comision'); ?>" data-index="24"> 
                                </div>
                            </div>
                        </div>

                        <div class="row">         
                            <label class="control-label col-xs-2"><?php echo _('Precio de compra'); ?>&nbsp;</label>
                            <div class="col-xs-2">
                                <input name="DATA[precio_compra]" id="precio_compra" value="" type="text" class="form-control input-sm"  data-index="25"/>
                            </div>
                            <label class="control-label col-xs-2"><?php echo _('Precio almacen'); ?>&nbsp;</label>
                            <div class="col-xs-2">
                                <input type="text" name="DATA[precio_almacen]" id="precio_almacen" value="" class="form-control input-sm"  data-index="26">
                            </div>
                        </div>

                        <div class="row">
                            <label class="control-label col-xs-2"><?php echo _('Precio tienda'); ?>&nbsp;</label>
                            <div class="col-xs-2">
                                <input type="text" class="form-control input-sm" id="precio_tienda" name="DATA[precio_tienda]" value="" placeholder="<?php echo _('Precio tienda'); ?>" onblur="precioiva();" data-index="27">
                            </div>        
                            <label class="control-label col-xs-2"><?php echo _('Precio iva'); ?>&nbsp;</label>
                            <div class="col-xs-2">
                                <input type="text" class="form-control input-sm" id="precio_iva" name="DATA[precio_iva]" value="" placeholder="<?php echo _('Precio iva'); ?>" data-index="28"> 
                            </div> 
                        </div>

                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                <fieldset style="width: 100%;" >
                <legend>Ubicación</legend>
                    <div class="row">
                        <label class="control-label col-xs-2">Sector</label>
                        <div class="col-xs-3"><input name="DATA[sector]" type="text" class="form-control" id="sector" value="" data-index="29"></div>
                        <label class="control-label col-xs-2">Pasillo</label>
                        <div class="col-xs-3"><input name="DATA[pasillo]" type="text" class="form-control" id="pasillo" value="" data-index="30"></div>
                    </div>
                    <div class="row">
                        <label class="control-label col-xs-2">Módulo</label>
                        <div class="col-xs-3"><input name="DATA[modulo]" type="text" class="form-control" id="modulo" value="" data-index="31"></div>
                        <label class="control-label col-xs-2">Estante</label>
                        <div class="col-xs-3"><input name="DATA[estante]" type="text" class="form-control" id="Westante" value="" data-index="32"></div>
                    </div>
                </fieldset>
                </div>
            </div>    
    </div>

</div><!-- fin 2b **************************************************-->
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
        <button class="btn btn-primary left-margin btn-xs" onclick="event.preventDefault();validar(formulario,true);"><?php echo _('Guardar'); ?></button>
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
 
</script>
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<?php
include_once "../common/footer.php";

?>