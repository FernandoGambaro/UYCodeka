<?php
$page_title="Subir recibos";

include_once "header.php";


?>
<fieldset class="scheduler-border">
  <legend class="scheduler-border"> Subir archivo/s </legend>
<div>
<div id="check2" style="display: block;">
	<form id="fileupload" action="#" method="POST" enctype="multipart/form-data">	
	<div class="row" >
		<div class="col-xs-1">Tipo</div>
		<div class="col-xs-2">
			<select name="tipo" id="tipo" class="form-control input-sm">
            <?php 
            $arrayTipo=array(1=>"Recibos Sueldo", 2=>"Salario vacacional", 3=>"Aguinaldo", 4=> "Resumen IRPF");
            foreach($arrayTipo as $key=>$valor){
                echo '<option value="'.$key.'">'.$valor.'</option>';
            }
            ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-1">
			Ref#:
		</div>
		<div class="col-xs-3">
			<input id="referencia" name="referencia" type="text" class="form-control input-sm" />
        </div>
        <div class="col-xs-8">
            <button onclick="verErrores();" id="errores" style="display:none;">Ver Errores</button>
        </div>
	</div>
	<div class="row">
		<div class="col-xs-2">
			<input id="cta" name="cta" type="hidden" value="0" />
		</div>
	</div>

        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
          <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button" id="botonsubir">
              <i class="glyphicon glyphicon-plus"></i>
              <span>Seleccionar archivo...</span>
              <input type="file" name="files[]" multiple />
            </span>
          </div>
          <!-- The global progress state -->
          <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="display:none;">
              <div
                class="progress-bar progress-bar-success"
                style="width:0%;"
              ></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
          </div>
		</div>

        <div class="row">
            <div class="col-xs-10">
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped">
          <tbody class="files"></tbody>
        </table>
        <div id="files_list"></div>
        <input type="hidden" name="file_ids" id="file_ids" value="">
            </div>
        </div>
	</form>
	</div>
</div>


<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-upload fade">
              <td>
                  <span class="preview"></span>
              </td>
              <td>
                  {% if (window.innerWidth > 480 || !o.options.loadImageFileTypes.test(file.type)) { %}
                      <p class="name">{%=file.name%}</p>
                  {% } %}
                  <strong class="error text-danger"></strong>
              </td>
              <td width="50%">
                  <p class="size">Procesando...</p>
                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                      <div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
              </td>
              <td align="right">
                  {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                    <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                        <i class="glyphicon glyphicon-edit"></i>
                        <span>Cambiar</span>
                    </button>
                  {% } %}
                  {% if (!i && !o.options.autoUpload) { %}
                      <button class="btn btn-primary start" disabled>
                          <i class="glyphicon glyphicon-upload"></i>
                          <span>Subir</span>
                      </button>
                  {% } %}
                  {% if (!i) { %}
                      <button class="btn btn-warning cancel">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                          <span>Cancelar</span>
                      </button>
                  {% } %}
              </td>
          </tr>
      {% } %}
    </script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-download fade">
              <td>
                  <span class="preview">
                      {% if (file.thumbnailUrl) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                      {% } %}
                  </span>
              </td>
              <td>
                  {% if (window.innerWidth > 480 || !file.thumbnailUrl) { %}
                      <p class="name">
                          {% if (file.url) { %}
                              <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                          {% } else { %}
                              <span>{%=file.name%}</span>
                          {% } %}
                      </p>
                  {% } %}
                  {% if (file.error) { %}
                      <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                  {% } %}
              </td>
              <td>
                  <span class="size">{%=o.formatFileSize(file.size)%}</span>
              </td>
              <td>
                  {% if (file.deleteUrl) { %}
                  <!-- button class"btn btn-primary" ><i class="glyphicon glyphicon-fire"></i><span>Procesar</span></button -->
                      <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                          <i class="glyphicon glyphicon-trash"></i>
                          <span>Quitar</span>
					  </button>
                      <!-- input type="checkbox" name="delete" value="1" class="toggle" -->
                  {% } else { %}
                      <button class="btn btn-warning cancel">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                          <span>Cancelar</span>
                      </button>
                  {% } %}
              </td>
          </tr>
      {% } %}
    </script>

    </fieldset>




    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="../../library/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->

    <!-- blueimp Gallery script -->
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-process.js"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-image.js"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-audio.js"></script>
    <!-- The File Upload video preview plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-video.js"></script>
    <!-- The File Upload validation plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-validate.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="../../library/jQuery-File-Upload-master/js/jquery.fileupload-ui.js"></script>
    <!-- The main application script -->

<script>
$(function () {
    $('#fileupload').fileupload({
		url: 'php/',
		// Enable image resizing, except for Android and Opera,
		// which actually support image resizing, but fail to
		// send Blob objects via XHR requests:
		disableImageResize: /Android(?!.*Chrome)|Opera/.test(
			window.navigator.userAgent
		),
		maxFileSize:  50 * 1024 * 1024,
        maxNumberOfFiles: 1,
		acceptFileTypes: /(\.|\/)(rar|zip|jpe?g|png|pdf)$/i,
        dataType: 'json',
        fail: function (data) {
            console.log("Fallo!");
        },
		progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
		},
        done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    Procesar(file.name, data.formData);
                    //$('<p/>').html(file.name + ' (' + file.size + ' KB)').appendTo($('#files_list'));
                    if ($('#file_ids').val() != '') {
                        $('#file_ids').val($('#file_ids').val() + ',');
                    }
                    $('#file_ids').val($('#file_ids').val() + file.fileID);
                });
                $('#loading').text('');
        },
		progressServerRate: 0.3,
		progressServerDecayExp: 2

	});

	$('#fileupload').bind('fileuploadsubmit', function (e, data) {
		// The example input, doesn't have to be part of the upload form:
		var referencia = $('#referencia');
        var tipo =$('#tipo');
		data.formData = {referencia: referencia.val(), tipo: tipo.val()};
		if (!data.formData.referencia) {
		data.context.find('button').prop('disabled', false);
		referencia.focus();
		return false;
		}
		if (!data.formData.tipo) {
		data.context.find('button').prop('disabled', false);
		tipo.focus();
		return false;
		}

	}).bind('fileuploaddone', function (e, data) {
        //console.log('Processing done.');
        $.each(data.files, function (index, file) {
            //console.log('fileuploaddone: ' + file.name);
        });
        
    }).bind('fileuploadfinished', function (e, data) {
        $.each(data.files, function (index, file) {
            //console.log('fileuploadfinished: ' + file.name);
        });
        $(".files tr").remove(); 
    }).bind('fileuploadadd', function(e, data) {
    $(".files tr").remove();
    });	 

    window.closeModal = function(){
        //console.log('cierro');
    $('#rejillaModal').modal('hide');
    $('#errores').show();
    $(".files tr").remove();
    };

});

function Procesar(file, data){
    $('#rejillaModal').modal({show:true});
    //console.log('data: '+data.referencia + ' - '+data.tipo);
    $("#rejilla-body").attr("src", 'procesar.php?file='+file+'&referencia='+data.referencia+'&tipo='+data.tipo);
    return false;
}
function verErrores(){
    event.preventDefault();
    $('#ErrorModal').modal('show');
    return false;
}
</script>

</body>

</html>