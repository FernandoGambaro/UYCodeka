<?php
require('PDF_Label.php');

include ("../conectar.php"); 
include ("../common/funcionesvarias.php"); 

//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 
setlocale(LC_CTYPE, 'es_ES');
header("Content-Type: text/html; charset=iso-8859-1 ");

$codarticulo=$_GET["codarticulo"];
$etiqueta=$_GET['etiqueta'];

					$_Avery_Labels = array(5160=>array(0=>'5160',1=>'24') ,5161=>array(0=>'5161', 1=>'20') ,5162=>array(0=>'5162', 1=>'14'), 5163=>array(0=>'5163', 1=>'10'),
					 5164=>array(0=>'5164', 1=>'6'), 8600=>array(0=>'8600', 1=>'24'), 'L7163'=>array(0=>'L7163', 1=>'14'), 3422=>array(0=>'3422',1=>'24'));					


$TotalEtiquetas=$_Avery_Labels[$etiqueta][1];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
//mysql_query("SET NAMES 'utf8'");
((bool)mysqli_set_charset($GLOBALS["___mysqli_ston"], "utf8"));
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

$texto=sprintf("%s\n%s\n%s\n%s\n%s", "Código:".$codigobarras,
"Sector:".mysqli_result($rs_query, 0, "sector"),"Pasillo:".mysqli_result($rs_query, 0, "pasillo"),
 "Módulo:".mysqli_result($rs_query, 0, "modulo"),"Estante:".mysqli_result($rs_query, 0, "estante"));

$width = $height = 150;
$file = "../tmp/qr".$codigobarras.".PNG";
		

$pdf = new PDF_Label($etiqueta);
$pdf->AddPage();
$count=0;

// Print labels
for($i=1;$i<=$TotalEtiquetas;$i++) {
	saveImage($texto, $width, $height, $file);
	$pdf->Add_Label($texto,  $file);
}

	/*Lo imprimo directamente*/
$sql_datos="SELECT servidorfactura,impresorafactura,web,reporte  FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$server=mysqli_result($rs_datos, 0, "servidorfactura");
$printer=mysqli_result($rs_datos, 0, "impresorafactura");
$reporte=mysqli_result($rs_datos, 0, "reporte");
	
	if($reporte!=1) {	
	$filename='etiqueta'.$etiqueta.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
		if (file_exists($file) and $printer!='') {
			/*Si existe el archivo lo envío directamente a la impresora*/ 
		$output = shell_exec('lp  -d'.$printer.'  -o fit-to-page '.$file); /*Envío la impresión por sistema*/
		preg_match('/\d+/', $output, $match);
		$printid =$match[0];
		} else {	
		$pdf->AutoPrintToPrinter($server, $printer, false);
		$pdf->Output($file,'F');
		$pdf->Close();
		}
		?>
		<script type="text/javascript" src="../js3/jquery.min.js"></script>
		<script type="text/javascript" >
				var xx=1;
				var numDoc=<?php echo $printid;?>;
		      window.opener.callGpsDiag(xx,numDoc);
		      setTimeout(window.close(),30000);
		</script>	
		<?php	
	} else {
		/*Muestra la impresión en pantalla*/
			$pdf->Output();
	}

?>