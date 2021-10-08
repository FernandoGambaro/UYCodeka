<?php
$post_max_size = return_bytes(ini_get('post_max_size'));

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

?>
<!DOCTYPE html>
<html>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">

<head>
    <title>Cargar archivo recibos</title>
	<link href="style.css" rel="stylesheet" type="text/css">
<style>
#myProgress {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: grey;
}
#myBar {
    position: absolute;
    width: 1%;
    height: 98%;
    background-color: green;
}
</style>	
</head>
<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Subir archivo</div>
				<div id="frmBusqueda">


<div class="form-wrap">
<div id="check" style="display: none;">
Se está procesando un archivo.

<div style="border: 1px solid #0099CC;padding: 1px;position: relative;border-radius: 3px; margin: 10px; text-align: left; background: #fff; box-shadow: inset 1px 3px 6px rgba(0, 0, 0, 0.12);">
  <div id="myBar" ></div><br>
</div>
Este proceso podría demorar minutos hasta horas.<br>Para subir otro, intente más tarde.
<div id="cancel">
 <button> Cancelar</button>
</div>
</div>

<div id="check2" style="display: block;">

<h3>Subir archivo/s</h3>
    <form action="process.php" method="post" enctype="multipart/form-data" id="upload_form">
		<table class="fuente8" border="0">
<tr><td>Tipo</td>
<td><select name="accion" id="accion" class="comboMedio">
<option value="1">Recibos</option>
<option value="2">Resumen IRPF</option>
</select></td>
</tr>		
		<tr>
		<td>Ref#:</td><td> <input id="mediopago" name="mediopago" type="text" class="cajaMediana"  /></td></tr>
		 <input id="cta" name="cta" type="hidden" value="0" />
      <td colspan="2">
      <input name="__files[]" type="file"  multiple="true" />
      </td></tr>
      <tr><td colspan="2" align="left">
        <input name="__submit__" type="submit" value="Procesar"/></td></tr>
      </table>
    </form>
    <div id="progress-wrp"><div class="progress-bar"></div><div class="status">0%</div></div>
    <div id="output"><!-- error or success results --></div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">    
//configuration
//En caso de falla al subir y que no muestre error, chequear en php.ini el tamaño máximo de archivo para upload
var max_file_size 			= <?php echo $post_max_size;?>; //allowed file size. (1 MB = 1048576)
var allowed_file_types 		= ['','image/png', 'image/gif', 'image/jpeg', 'image/pjpeg', 'application/x-rar', 'application/vnd.rar', 'application/zip']; //allowed file types
var result_output 			= '#output'; //ID of an element for response output
var my_form_id 				= '#upload_form'; //ID of an element for response output
var progress_bar_id 		= '#progress-wrp'; //ID of an element for response output
var total_files_allowed 	= 3; //Number files allowed to upload



//on form submit
$(my_form_id).on( "submit", function(event) { 
	event.preventDefault();
	var proceed = true; //set proceed flag
	var error = [];	//errors
	var total_files_size = 0;
	
//	if ($("#mediopago").val()=='' && $("#accion").find('option:selected').val()!=2) {
//		alert('Ingrese medio de pago');
//		return false;
//	}
	
	//if ($("#cta").val()=='') {
	//	alert('Ingrese número de cuenta');
	//	return false;
	//}	
	//reset progressbar
	$(progress_bar_id +" .progress-bar").css("width", "0%");
	$(progress_bar_id + " .status").text("0%");
							
	if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
		error.push("Su navegador no soporta esta funcionalidad."); //push error text
	}else{
		var total_selected_files = this.elements['__files[]'].files.length; //number of files
		
		//limit number of files allowed
		if(total_selected_files > total_files_allowed){
			error.push( "Ud selecciono "+total_selected_files+" file(s), " + total_files_allowed +" como máximo!"); //push error text
			proceed = false; //set proceed flag to false
		}
		 //iterate files in file input field
		$(this.elements['__files[]'].files).each(function(i, ifile){
			if(ifile.value !== ""){ //continue only if file(s) are selected
			//alert(ifile.type);
				if(allowed_file_types.indexOf(ifile.type) === -1){ //check unsupported file
					error.push( "<b>"+ ifile.name + "</b> tipo de archivo no soportado!"); //push error text
					proceed = false; //set proceed flag to false
				}

				total_files_size = total_files_size + ifile.size; //add file size to total size
			}
		});
		
		//if total file size is greater than max file size
		if(total_files_size > max_file_size){ 
			error.push( "Ud tiene "+total_selected_files+" file(s) con un tamaño total de "+total_files_size+", Lo permitido es " + max_file_size +", mas chico!"); //push error text
			proceed = false; //set proceed flag to false
		}
		
		var submit_btn  = $(this).find("input[type=submit]"); //form submit button	
		
		//if everything looks good, proceed with jQuery Ajax
		if(proceed){
			//submit_btn.val("Please Wait...").prop( "disabled", true); //disable submit button
			var form_data = new FormData(this); //Creates new FormData object
			var post_url = $(this).attr("action"); //get action URL of form
			
			//jQuery Ajax to Post form data
$.ajax({
	url : post_url,
	type: "POST",
	data : form_data,
	contentType: false,
	cache: false,
	processData:false,
	xhr: function(){
		//upload Progress
		var xhr = $.ajaxSettings.xhr();
		if (xhr.upload) {
			xhr.upload.addEventListener('progress', function(event) {
				var percent = 0;
				var position = event.loaded || event.position;
				var total = event.total;
				if (event.lengthComputable) {
					percent = Math.ceil(position / total * 100);
				}
				//update progressbar
				$(progress_bar_id +" .progress-bar").css("width", + percent +"%");
				$(progress_bar_id + " .status").text(percent +"%");
			}, true);
		}
		return xhr;
	},
	mimeType:"multipart/form-data"
}).done(function(res){ //
	$(my_form_id)[0].reset(); //reset form
	$(result_output).html(res); //output response from server
	submit_btn.val("Upload").prop( "disabled", false); //enable submit button once ajax is done
});
			
		}
	}
	
	$(result_output).html(""); //reset output 
	$(error).each(function(i){ //output any error to output element
		$(result_output).append('<div class="error">'+error[i]+"</div>");
	});
		
});
</script>
</body>
<script>   

jQuery(document).ready(function () {
    jQuery.ajaxSetup({cache: false});       
		var xx=1;
        callGpsDiag(xx);             

    function callGpsDiag(xx){
	  jQuery.ajax({
	    type: "POST",
	    url: "monitoreo.php",
	    data: "execGpsDiag="+xx,
	    success: function(data){ 
	    //alert(data);
	    	if (data!="Fin") {
	        if (xx==1) {
	        	$("#check").toggle();
	        	$("#check2").toggle();
	        }
			move();
	        setTimeout(function(){ 
	        xx=xx+1;
	            callGpsDiag(xx);
	        },3000);
	        
	    	} else {
				var $p = $("#myBar");
    				$p.stop()
      			.css("background-color","green");
      			
      			if (xx>1) {
      				$("#check").toggle();
		        		$("#check2").toggle();
		        	}
	    	}
	   },
	     error: function() {
	        //alert('fail');
	     }
  	 });        
	}

$("#cancel").click(function() {
  	  jQuery.ajax({
	    type: "POST",
	    url: "monitoreo.php",
	    data: "execGpsDiag=0",
	    success: function(data){ 
	    	if (data=="Fin") {
	        	//$("#check").toggle();
	        	//$("#check2").toggle();
	        	callGpsDiag(0);
	        }
	   },
	     error: function() {
	        alert('fail');
	     }
  	 });
});
            
});  

function move() {
    var elem = document.getElementById("myBar"); 
    var width = 1;
    var id = setInterval(frame, 30);
    function frame() {
        if (width >= 100) {
            clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
            //document.getElementById("label").innerHTML = width * 1 + '%';
        }
    }
}
  
</script>
</html>