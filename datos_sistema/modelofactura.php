<?php

$font="./arial.ttf";

function center_text($string, $font_size, $image_width ){
			global $font;
			$dimensions = imagettfbbox($font_size, 0, $font, $string);
			return ceil(($image_width - $dimensions[4]) / 2);				
}

function resize_imagepng($file, $w, $h) {
   list($width, $height) = getimagesize($file);
		if($w==0) {
			$aux=$h*100/$height;
			$w=$aux*$width/100;
		}
		if($h==0) {
			$aux=$w*100/$width;
			$h=$aux*$height/100;
		}
	if($w>0 AND $h>0)  {
   $src = imagecreatefrompng($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
   }
   
}

//Cargamos la imagen del logo de la empresa
include ("../conectar.php");
include ("../funciones/fechas.php"); 
include ("../common/funcionesvarias.php"); 
/*/ Configurar las dos lineas siguientes*/
$estado=0;
/*Compruebo que se paso un parametro válido y comienzo a recopilar datos*/
if(isset($_POST['id']) ) {

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$nombre=mysqli_result($rs_datos, 0, "nombre");
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
	$lugar='';
if(mysqli_result($rs_datos, 0, "provincia")!=' ') {
	$sql_prov="SELECT * FROM `provincias` where `codprovincia` =". mysqli_result($rs_datos, 0, "provincia");
	$rs_prov=mysqli_query($GLOBALS["___mysqli_ston"], $sql_prov);
	$lugar.=mysqli_result($rs_prov, 0, "nombreprovincia");
}
if(mysqli_result($rs_datos, 0, "pais")!=' ') {
	$sql_pais="SELECT * FROM `paises` where `codpais` =". mysqli_result($rs_datos, 0, "pais");
	$rs_pais=mysqli_query($GLOBALS["___mysqli_ston"], $sql_pais);
	$lugar.="  ". mysqli_result($rs_pais, 0, "nombre");
}
$localidad=mysqli_result($rs_datos, 0, "localidad");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
if(mysqli_result($rs_datos, 0, "telefono2")!=' ') {
	$telefono.=" - ".mysqli_result($rs_datos, 0, "telefono2");
}
$web=mysqli_result($rs_datos, 0, "web");

	$DATA = array(
		array(
			'name'=> $razonsocial, 
			'font-size'=>'13',
			'color'=>'grey'),
		array(
			'name'=> $nombre, 
			'font-size'=>'9',
			'color'=>'grey'),
		array(
			'name'=> $direccion, 
			'font-size'=>'9',
			'color'=>'grey'),
		array(
			'name'=> $lugar, 
			'font-size'=>'9',
			'color'=>'grey'),			
		array(
			'name'=> "Tel/s.:".$telefono, 
			'font-size'=>'9',
			'color'=>'grey'),
		array(
			'name'=> "http://".$web, 
			'font-size'=>'9',
			'color'=>'grey'),			
	);

		$query = "SELECT * FROM `foto` where `oid`='1'";
		$resulta = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Error");
		  if($result=mysqli_fetch_array($resulta)){		
		    $imageLogo= mysqli_result($resulta,0,'fotocontent');			
			}

/*Grabo la información blob a un archivo*/
if (file_exists("../tmp/tmpimage.png")){
	unlink("../tmp/tmpimage.png");
}
file_put_contents("../tmp/tmpimage.png", $imageLogo);

/*Fin cargar datos, comienzo a procesarlos*/

$baseimage=$_POST['id'].".png";

if($_POST['id']=="basicoA4") {
$img = resize_imagepng("../tmp/tmpimage.png", 200, 0);
imagepng($img, "../tmp/tmpimage.png");

//$arriba = imagecreatefrompng("../tmp/tmpimage.png");
$arriba = imagecreatefrompng("../tmp/tmpimage.png");
$abajo = imagecreatefrompng("../img/".$baseimage);

$width   =    imagesx ( $arriba );
$height  =    imagesy ( $arriba );

$cent_width   =    (330-$width)/2;
$cent_height  =   (140-$height)/2;
imagecopyresampled($abajo,$arriba,$cent_width,$cent_height,0,0,$width ,$height ,$width ,$height);

$color=imagecolorallocate($abajo, 0,0,0);
$y=$cent_height+$height+14;
$i=12;
		foreach ($DATA as $value){
			if(strlen($value['name'])>0) {
			// center the text in our image - returns the x value
			$x = center_text($value['name'], $value['font-size'], 330);	
			imagettftext($abajo, $value['font-size'], 0, $x, $y+$i, $color[$value['color']], $font,$value['name']);
			// add 17px to the line height for the next text block
			$i = $i+17;	
			}
		}
} else {
	$img = resize_imagepng("../tmp/tmpimage.png", 100, 0);
imagepng($img, "../tmp/tmpimage.png");

//$arriba = imagecreatefrompng("../tmp/tmpimage.png");
$arriba = imagecreatefrompng("../tmp/tmpimage.png");
$abajo = imagecreatefrompng("../img/".$baseimage);

$width   =    imagesx ( $arriba );
$height  =    imagesy ( $arriba );

$cent_width   =    (230-$width)/2;
$cent_height  =   (85-$height)/2;
imagecopyresampled($abajo,$arriba,$cent_width,$cent_height,0,0,$width ,$height ,$width ,$height);

$cent_width   =    (420-$width)/2;

$color=imagecolorallocate($abajo, 0,0,0);
$y=$cent_height+$height;
$i=12;
		foreach ($DATA as $value){
			if(strlen($value['name'])>0) {
			// center the text in our image - returns the x value
			$x = center_text($value['name'], $value['font-size'], 330);	
			imagettftext($abajo, $value['font-size'], 0, $x, $y+$i, $color[$value['color']], $font,$value['name']);
			// add 17px to the line height for the next text block
			$i = $i+17;	
			}
		}

}

imagepng($abajo, '../tmp/modelofactura.png');

$estado=1;
//imagedestroy($arriba);
imagedestroy($abajo);
}
echo $estado;
?>