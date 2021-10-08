<?php
############ Configuration ##############
$config["generate_image_file"]			= true;
$config["generate_thumbnails"]			= true;
$config["image_max_size"] 				= 500; //Maximum image size (height and width)
$config["thumbnail_size"]  				= 200; //Thumbnails will be cropped to 200x200 pixels
$config["thumbnail_prefix"]				= "thumb_"; //Normal thumb Prefix
$config["destination_folder"]			= '/uycodeka/data/upload/'; //upload directory ends with / (slash)
$config["thumbnail_destination_folder"]	= '/uycodeca/data/upload/'; //upload directory ends with / (slash)
$config["upload_url"] 					= "./data/upload/"; 
$config["quality"] 						= 90; //jpeg quality
$config["random_file_name"]				= true; //randomize each file name


if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	exit;  //try detect AJAX request, simply exist if no Ajax
}

if (""!=@$_FILES["__files"]["name"]) {
	if($_POST['mediopago']!='') {
		$mediopago=$_POST['mediopago'];
		} else {
			$mediopago='1';
		}
	if($_POST['cta']!='') {
		$cta=$_POST['cta'];
	} else {
		$cta='0';
	}
	$accion=$_POST['accion'];

	$mimetypes = array('', 'application/vnd.rar','application/x-rar','application/zip','application/octet-stream');
	// Variables del archivo

	$name = $_FILES["__files"]["name"][0];
	$type = $_FILES["__files"]["type"][0];
	$tmp_name = $_FILES["__files"]["tmp_name"][0];
	$size = $_FILES["__files"]["size"][0];
	$archivo=$_FILES["__files"]["name"][0];
	// Verificamos si el archivo es una imagen válida
	
	if(!in_array($type, $mimetypes)) {
	    die('<table width="100%" height="100%"><tr><td width="100%" height="100%" align="center" valign="middle">$accion</td></tr></table>
<script>parent.$(\'idOfDomElement\').colorbox.close(); </script>
	<script>setTimeout(function(){ location.href ="index.php"; }, 1000);</script>	
    
	');
	} else {
		
			move_uploaded_file($_FILES["__files"]["tmp_name"][0], $config["destination_folder"] . $_FILES["__files"]["name"][0]);
	   // Borra archivos temporales si es que existen
	   @unlink($tmp_name);

   
		//$script=proc_open("TERM=linux /usr/bin/sh ./uycodeka/data/upload/pasodatos.sh ".$archivo." ".$mediopago." ".$cta." --foo=1 &", $fd, $pipes, NULL);

		if (file_exists('./uycodeka/data/upload/'.$archivo)) {
			//$script=proc_open("./uycodeka/data/upload/pasodatos.sh ".$archivo." ".$mediopago." ".$cta." --foo=1 &", $fd, $pipes, NULL);
	$fd = array(
	0 => array("pipe", "r"),  // stdin
	1 => array("pipe", "w"),  // stdout
	2 => array("file", "error.txt", "a") // stderr
	);
	
	$pipes = array();	
		$cwd = './uycodeka/data/upload/';	
		$env = NULL;
		$options = array('bypass_shell' => true);
		$cwd = "./uycodeka/data/upload";
		//$cmd='TERM=linux /usr/bin/bash ./uycodeka/data/upload/pasodatos.sh '.$archivo.' '.$mediopago.' '.$cta.' --foo=1 &';
		if($accion==1) {
		$cmd='TERM=linux ./uycodeka/data/upload/./pasodatos.sh '.$archivo.' '.$mediopago.' '.$cta.' --foo=1 &';
		} else {
		$cmd='TERM=linux ./uycodeka/data/upload/./pasodatosirpf.sh '.$archivo.' --foo=1 &';
		}
		$process = proc_open($cmd, $fd, $pipes, $cwd, $env);


	 /*
		if (is_resource($process)) {
			echo stream_get_contents($pipes[1]);
			fclose($pipes[1]);
			// It is important that you close any pipes before calling
			// proc_close in order to avoid a deadlock
			$return_value = proc_close($process);
			echo "command returned $return_value\n";
		}
		*/
		
		}
		
	}
	
}


?>
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
      			.css("background-color","#9b9b9b");
      			
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
