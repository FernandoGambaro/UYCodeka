<?php
require('PDF_Label.php');

include ("../conectar.php"); 
include ("../funciones/fechas.php"); 


$codarticulo=$_GET["codarticulo"];
$etiqueta=$_GET['etiqueta'];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codigobarras=mysqli_result($rs_query, 0, "codigobarras");



function saveImage($texto, $width, $height, $file) {
	if (!file_exists($file))
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
}
/*------------------------------------------------
To create the object, 2 possibilities:
either pass a custom format via an array
or use a built-in AVERY name
------------------------------------------------*/

// Example of custom format
// $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

// Standard format

$texto="Código-".$codigobarras."-";
$texto.="Sector-".mysqli_result($rs_query, 0, "sector")."-Pasillo-".mysqli_result($rs_query, 0, "pasillo")."-Modulo-".mysqli_result($rs_query, 0, "modulo")."-Estante-".mysqli_result($rs_query, 0, "estante");
$width = $height = 150;
$file = "../tmp/qr".$codigobarras.".png";


$pdf = new PDF_Label($etiqueta);
$pdf->AddPage();
$count=0;

// Print labels
for($i=1;$i<=24;$i++) {

	saveImage($texto, $width, $height, $file);
	$pdf->Add_Label($texto,  $file);
}

	$pdf->AutoPrintToPrinter('192.168.1.209', 'Facturacion', false);
	$pdf->Output();

?>