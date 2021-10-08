<?php
use setasign\Fpdi\Fpdi;

require_once __DIR__ .'/../../fpdf/fpdf.php';   
require_once __DIR__ .'/../../library/fpdi/src/autoload.php';   

$referencia=isset($_REQUEST['referencia']) ? $_REQUEST['referencia'] : '';
$tipo=isset($_REQUEST['tipo']) ? $_REQUEST['tipo'] : '';


include_once "header_procesar.php";

function split_pdf($filename, $end_directory = false)
{

	$pdf = new FPDI();
	$pagecount = $pdf->setSourceFile($filename); // How many pages?
	// Split each page into a new PDF
	for ($i = 1; $i <= $pagecount; $i++) {
		$new_pdf = new FPDI();
		$new_pdf->AddPage();
		$new_pdf->setSourceFile($filename);
		$new_pdf->useTemplate($new_pdf->importPage($i));
		
		try {
			$new_filename = $end_directory.str_replace('.pdf', '', basename($filename, '.pdf')).'_'.$i.".pdf";
            $new_pdf->Output($new_filename, "F");
            
            ?>    
            <div class="col-xs-2">
                <div class="custom-control custom-checkbox" id="box-<?php echo basename($new_filename);?>" >
                <input type="checkbox" class="custom-control-input" id="file-<?php echo basename($new_filename);?>">
                <label class="custom-control-label" for="file-<?php echo basename($new_filename);?>"><?php echo basename($new_filename);?></label>
                </div>
            </div>
            <?php 
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
    }
    unlink($filename);
}


function _create_preview_images($file) {
    // Strip document extension
    $file_name = str_replace(' ' , '', basename($file, '.pdf'));
    // Convert this document
    // Each page to single image
    $img = new imagick();
    $img->setResolution(300,300);
    $img->readImage('procesar/'.basename($file));

    $img->setImageBackgroundColor('#ffffff');
    $img->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
    $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
    $img->resizeImage(1240,1754, Imagick::FILTER_LANCZOS, 0.95, true);
    $num_pages = $img->getNumberImages();

    // Compress Image Quality
    $img->setImageCompressionQuality(90);

    // Convert PDF pages to images
    for($i = 0;$i < $num_pages; $i++) {         
        // Set iterator postion
        $img->setIteratorIndex($i);
        // Set image format
        $img->setImageFormat('png');
        // Write Images to temp 'upload' folder    
        $img->resampleImage(300, 300, \Imagick::FILTER_LANCZOS, 1); 
        $img->writeImage('procesar/'.$file_name.'-'.$i.'.png');
        ?>    
        <div class="col-xs-2">
            <div class="custom-control custom-checkbox" id="box-<?php echo $file_name.'-'.$i.'.png';?>" >
            <input type="checkbox" class="custom-control-input" id="file-<?php echo $file_name.'-'.$i.'.png';?>">
            <label class="custom-control-label" for="file-<?php echo $file_name.'-'.$i.'.png';?>"><?php echo $file_name.'-'.$i.'.png';?></label>
            </div>
        </div>
        <?php 

    }

    $img->destroy();
    //unlink($file);
}

?>
<script type="text/javascript">
    var continuar=1;
    var optionTexts = [];
    var start = 0;
    var errores = 0;
    var exito = 0;
    var referencia = '<?php echo $referencia;?>';
    var tipo = '<?php echo $tipo;?>';

function extraigo(){
    $("label").each(function() { optionTexts.push($(this).text()) });
    $("#statusModal").modal('show');
    //$("#statusModal").appendTo("body");
    if(continuar==1){
        setTimeout(function() { var adelante = doRequest(); }, 1000);

    }else{
        if(errores>0){
            $('p').html('<strong>Errores encontrados: ' + errores+ '</strong><br>');
        }
        $('p').append('Archivos procesados con exito: ' + exito + '<br>');
        $('#botonCerar').hide();
        setTimeout(function(){ $('#statusModal').modal('hide'); window.parent.closeModal(); }, 1000);
        
    }
}

function doRequest(){ 
    var valor= optionTexts[start];
    $('p').append('<br>Procesando archivo: <strong>' + optionTexts[start] + '</strong><br>');
    var extension='';
    if(optionTexts[start].search("pdf")>0){
        extension='pdf';    
    }
    //console.log(valor);

    $.ajax({
        url: "process.php",
        dataType: "json",
        data: { file: valor, referencia: referencia, tipo: tipo, extension:extension },
        success: function (data, status, xhr) {
            $('p').html('Archivo: <strong>' + data.file + '</strong><br>');
            $('p').append('Estado: ' + data.statusTxt + '<br>');
            //console.warn(xhr.responseText);
                    //console.log(data);
                if(data.status==0){
                    document.getElementById("box-"+data.file).style.color = 'red';
                    ++errores;
                    window.parent.$('p').append( data.ci + ', ');

                }else{
                    document.getElementById("box-"+data.file).style.color = 'green';
                    document.getElementById("file-"+data.file).checked = true; 
                    ++exito;
                }
        },
        error: function (jqXhr, textStatus, errorMessage) {
                $('p').html('<p>' + errorMessage + '<br></p>');
        },
        complete: function(data, status) {
            if (++start < optionTexts.length) {
                if(continuar==1){
                    setTimeout(function() { var adelante = doRequest(); }, 1000);
                }else{
                    if(errores>0){
                        $('p').html('<strong>Errores encontrados: ' + errores+ '</strong><br>');
                        window.parent.$('#errores').show();
                    }
                    $('p').append('Archivos procesados con exito: ' + exito + '<br>');
                    $('#botonCerar').hide();
                    setTimeout(function(){ $('#statusModal').modal('hide'); window.parent.closeModal() }, 1000);
                }
            }else{
                if(errores>0){
                    $('p').html('<strong>Errores encontrados: ' + errores+ '</strong><br>');
                }
                $('p').append('Archivos procesados con exito: ' + exito + '<br>');
                $('#botonCerar').hide();
                setTimeout(function(){ $('#statusModal').modal('hide');window.parent.closeModal() }, 1000);
            }
        },        
    });
}
      

</script>
<div class="container my-4">

<?php
$fileList = glob("procesar/*.{jpg,png,pdf}",GLOB_BRACE);
 
if (count($fileList)>0){
    foreach($fileList as $filename){
    ?>
    <div class="col-xs-2">
        <div class="custom-control custom-checkbox" id="box-<?php echo basename($filename);?>">
           <input type="checkbox" class="custom-control-input" id="file-<?php echo basename($filename);?>">
           <label class="custom-control-label" for="file-<?php echo basename($filename);?>"><?php echo basename($filename);?></label>
        </div>
    </div>
    <?php
    }
    ?>
    <script>
        extraigo();
    </script>
    <?php
}else{
    $fileToProcess= $_REQUEST['file'];
    if(file_exists(realpath('.') . "/php/files/".$fileToProcess)){

        $exts = array('rar', 'zip'); //Me fijo si es un archivo ZIP o RAR
        if(in_array(end(explode('.', realpath('.'). "/php/files/".$fileToProcess)), $exts)){
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type
               $mime= finfo_file($finfo, realpath('.') ."/php/files/".$fileToProcess) . "<br>";
            finfo_close($finfo);
            if(strpos($mime, 'rar') >0 ){ //Para el archivo RAR, extraigo el contenido
                $rar = rar_open(realpath('.') . "/php/files/".$fileToProcess) or die('ERROR: Could not open archive!');
                $entries = rar_list($rar);
                foreach ($entries as $entry) {
                    $entry->extract(realpath('.') .'/procesar'); 
                    $exts = array('pdf'); //Analizo si es el archivo a extraer es un PDF o una imagen jpg
                    if(in_array(end(explode('.', realpath('.') .'/procesar/'.$entry->getName())), $exts)){
                        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type
                            $mime= finfo_file($finfo, realpath('.') .'/procesar/'.$entry->getName()) . "<br>";
                        finfo_close($finfo);
                        if(strpos($mime, 'pdf') >0 ){
                            $filename=split_pdf('procesar/'.$entry->getName(), 'procesar/');
                        }
                    } else { //No es PDF, listo los archivos imágenes.
                    ?>
                    <div class="col-xs-2">
                        <div class="custom-control custom-checkbox" id="box-<?php echo $entry->getName();?>" >
                        <input type="checkbox" class="custom-control-input" id="file-<?php echo $entry->getName();?>">
                        <label class="custom-control-label" for="file-<?php echo $entry->getName();?>"><?php echo $entry->getName();?></label>
                        </div>
                    </div>
                    <?php  
                    }
            
                }
                rar_close($rar);
                //unlink(realpath('.') . "/php/files/".$fileToProcess);
            }elseif(strpos($mime, 'zip') >0 ){ //Si es un archivo ZIP, extraigo el contenido
                $zip = new ZipArchive;
                if ($zip->open(realpath('.') . "/php/files/".$fileToProcess) === TRUE) {

                    for($i = 0; $i < $zip->numFiles; $i++) { //Para cada archivo que extraigo, me fijo si es una imágne o un PDF
                        $filename = $zip->getNameIndex($i);
                        $zip->extractTo(realpath('.') .'/procesar/', array($zip->getNameIndex($i)));

                        $exts = array('pdf'); 
                        if(in_array(end(explode('.', realpath('.') .'/procesar/'.$zip->getNameIndex($i))), $exts)){
                            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type
                                $mime= finfo_file($finfo, realpath('.') .'/procesar/'.$zip->getNameIndex($i)) . "<br>";
                            finfo_close($finfo);
                            if(strpos($mime, 'pdf') >0 ){
                                $filename=split_pdf('procesar/'.$zip->getNameIndex($i),'procesar/');
                            }
                        } else {
                            ?>
                            <div class="col-xs-2">
                                <div class="custom-control custom-checkbox" id="box-<?php echo $zip->getNameIndex($i);?>" >
                                <input type="checkbox" class="custom-control-input" id="file-<?php echo $zip->getNameIndex($i);?>">
                                <label class="custom-control-label" for="file-<?php echo $zip->getNameIndex($i);?>"><?php echo $zip->getNameIndex($i);?></label>
                                </div>
                            </div>
                            <?php 
                        }

                    } 
                    //$zip->extractTo(realpath('.') .'/procesar/');
                    $zip->close();
                } else {
                    echo 'failed';
                }
            }
        }



    ?>
    <script>
        extraigo();
    </script>
    <?php
    }else{
    ?>
    <div class="col-xs-4">
        <div class="custom-control">
        <label class="custom-control-label" >No se pudo procesar el archivo, intente más tarde</label>
        </div>
    </div>
    <?php
    }
}
?>
</div>
