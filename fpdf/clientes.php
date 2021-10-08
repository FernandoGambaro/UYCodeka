<?php
date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$ww=array(80,25,70,20);
/*/Ttulos de las columnas*/
$header=array('Nombre','RUT','Dirección','Teléfono');


$title="Listado de Clientes";
$subtitle="Detalles ";

$codcliente=@$_GET["codcliente"];
$nombre=@$_GET["nombre"];

$nif=@$_GET["nif"];
$codprovincia=@$_GET["provincia"];
$localidad=@$_GET["localidad"];
$telefono=@$_GET["telefono"];
$cadena_busqueda=@$_GET["cadena_busqueda"];

$where="1=1";

if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND(empresa like '%".$nombre."%' or nombre like '%".$nombre."%' or apellido like '%".$nombre."%' )"; }
if ($nif <> "") { $where.=" AND nif like '%".$nif."%'"; }
if ($codprovincia > "0") { $where.=" AND codprovincia='$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($telefono <> "") { $where.=" AND telefono like '%".$telefono."%'"; }

$where.=" ORDER BY empresa ASC , apellido ASC , nombre ASC";

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','',7);

$sel_resultado="SELECT * FROM clientes WHERE borrado=0 AND ".$where;
$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);


$contador=0;
while ($contador < mysqli_num_rows($res_resultado)) {
	if (mysqli_result($res_resultado, $contador, "empresa")!='') {
	$pdf->Cell($ww[0],5,mysqli_result($res_resultado, $contador, "empresa"),'LRTB',0,'L');
	} else {
	$pdf->Cell($ww[0],5,mysqli_result($res_resultado, $contador, "nombre").' '.mysqli_result($res_resultado, $contador, "apellido"),'LRTB',0,'L');
	}
	$pdf->Cell($ww[1],5,mysqli_result($res_resultado, $contador, "nif"),'LRTB',0,'C');
	$pdf->Cell($ww[2],5,mysqli_result($res_resultado, $contador, "direccion"),'LRTB',0,'L');
	$pdf->Cell($ww[3],5,mysqli_result($res_resultado, $contador, "telefono"),'LRTB',0,'C');
	$pdf->Ln();
	$contador++;
};

$nombre='../copias/clientes.pdf';	
		
$pdf->Output($nombre,'I');
?> 

			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="css/SearchBox.css" media="screen"/>
			<link rel="stylesheet" type="text/css" href="css/login1.css" title="default" media="screen" />
			<link rel="stylesheet" type="text/css" href="css/table.css" media="screen" />
			
			<link href="css/impresora.css" media="print" type="text/css" rel="stylesheet">
			
			<style media="screen,projection" type="text/css">
			    /* backslash hack hides from IEmac \*/
				    @import url(css/base.css);
			    /* end hack */
			</style>
			<STYLE type="text/css"> 
			
			A:link {text-decoration:none;color:#0000cc;} 
			A:visited {text-decoration:none;color:#ffcc33;} 
			A:active {text-decoration:none;color:#ff0000;} 
			A:hover {text-decoration:underline;color:#999999;} 
			</STYLE> 
			
			    <script type="text/javascript" src="js/pdfobject.js"></script>
			    <script type="text/javascript">
			      window.onload = function (){
			        var success = new PDFObject({ url: "<?php echo $nombre;?>" }).embed();
			      };
			    </script>
			<title>Estudios</title>
			</head>
<body marginwidth="0" topmargin="0" leftmargin="0">
	<center>
	<div style="position: relative; width: 300px; height: 50px; background-color: #E63C1E; color: #000; padding: 15px;z-index: 2;">
	<p>Por lo visto no tiene instalado Adobe Reader o soporte para PDF en su navegador web. <br>
	<a href="<?php echo $nombre;?>">Click para descargar el PDF</a></p>  
	</div>
	</center>
			
</body></html>