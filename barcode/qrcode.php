<?php

$texto=$_REQUEST['texto'];
$width=$_REQUEST['width'];
$height=$_REQUEST['height'];
$file=$_REQUEST['file'];


	if (!file_exists($file) or $_REQUEST['accion']=="U")
	{
 		$texto= urlencode($texto);
		$qr  = file_get_contents("http://chart.googleapis.com/chart?chs={$width}x{$height}&cht=qr&chl=$texto");
		$imgIn  = imagecreatefromstring($qr);
		$imgOut = imagecreate($width, $height);
		imagecopy($imgOut, $imgIn, 0,0, 0,0, $width,$height);
		imagepng($imgOut, $file, 9);
		imagedestroy($imgIn);
		imagedestroy($imgOut);
	}

header( "Content-type: image/png" );
echo file_get_contents($file);

	
?>