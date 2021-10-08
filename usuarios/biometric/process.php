<?php
$path=realpath(dirname(__FILE__));
$largo=strlen($path)-19;
$conexionPath=substr($path,0,$largo);
include($conexionPath."/conectar.php");

function curPageURL() {
  if(isset($_SERVER["HTTPS"]) && !empty($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] != 'on' )) {
        $url = 'https://'.$_SERVER["SERVER_NAME"];//https url
  }  else {
    $url =  'http://'.$_SERVER["SERVER_NAME"];//http url
  }
  if(( $_SERVER["SERVER_PORT"] != 80 )) {
     $url .= $_SERVER["SERVER_PORT"];
  }
  $url .= $_SERVER["REQUEST_URI"];
  return $url;
}


############ Configuration ##############
$config["generate_image_file"]			= true;
$config["generate_thumbnails"]			= true;
$config["image_max_size"] 				= 500; //Maximum image size (height and width)
$config["thumbnail_size"]  				= 200; //Thumbnails will be cropped to 200x200 pixels
$config["thumbnail_prefix"]				= "thumb_"; //Normal thumb Prefix
$config["destination_folder"]			= $conexionPath.'/tmp/'; //upload directory ends with / (slash)
$config["thumbnail_destination_folder"]	= $conexionPath.'/tmp/'; //upload directory ends with / (slash)
$config["upload_url"] 					= curPageURL()."/tmp/"; 
$config["quality"] 						= 90; //jpeg quality
$config["random_file_name"]				= true; //randomize each file name


if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	exit;  //try detect AJAX request, simply exist if no Ajax
}

if (""!=@$_FILES["__files"]["name"]) {

	$mimetypes = array('','application/vnd.rar','application/x-rar','application/zip','application/octet-stream');
	// Variables del archivo

	$name = $_FILES["__files"]["name"][0];
	$type = $_FILES["__files"]["type"][0];
	$tmp_name = $_FILES["__files"]["tmp_name"][0];
	$size = $_FILES["__files"]["size"][0];
	$archivo=$_FILES["__files"]["name"][0];
	// Verificamos si el archivo es una imagen válida
	
	if(!in_array($type, $mimetypes)) {
	    die('<table width="100%" height="100%"><tr><td width="100%" height="100%" align="center" valign="middle">$accion</td></tr></table>
   
	');
	} else {
		
			move_uploaded_file($_FILES["__files"]["tmp_name"][0], $config["destination_folder"] . $_FILES["__files"]["name"][0]);
	   // Borra archivos temporales si es que existen
	   @unlink($tmp_name);



		if (file_exists($conexionPath.'/tmp/'.$archivo)) {

$codbiometric=$_POST['codbiometric'];

$file = new SplFileObject($conexionPath.'/tmp/'.$archivo);
//$file->setFlags(SplFileObject::DROP_NEW_LINE); 
// Loop until we reach the end of the file.
while (!$file->eof()) {
    // Echo one line from the file.
    $linea=explode('_',preg_replace('/\s+/', '_', ltrim($file->fgets())));
    //echo "<br>";$codbiometric
$query_busqueda="SELECT count(*) as filas FROM `biometriclog` WHERE `codbiometric`='".$codbiometric."' 
AND `codusuarios`='".$linea[0]."'	AND `datetime`='".$linea[1].' '.$linea[2]."'";
			$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
			$filas=mysqli_result($rs_busqueda, 0, "filas");
			if($filas==0) {
			echo $sql="INSERT INTO `biometriclog` (`codlog`, `codbiometric`, `codusuarios`, `pin`, `status`, `datetime`) 
			VALUES (NULL, '".$codbiometric."',  '".$linea[0]."', '".$linea[0]."', '".$linea[3]."', '". $linea[1]." ". ' '.$linea[2] ."');";
			$rs_operacion=mysqli_query($GLOBALS["___mysqli_ston"], $sql);    
			} 
}
// Unset the file to call __destruct(), closing the file handle.
$file = null;
		}
	}	
}
?>
<script>parent.$('idOfDomElement').colorbox.close(); </script>
	<script>setTimeout(function(){ location.href ="index.php"; }, 1000);</script>	