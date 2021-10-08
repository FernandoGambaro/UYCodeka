<?php
date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

$codalbaran=@$_GET["codalbaran"];
$envio=@$_GET['envio'];
$moneda='';
$impo=0;
$importe=0;
$obs='';
$obs[0]=$obs[1]=$obs[2]=$obs[3]=$obs[4]=$obs[5]=$obs[6]='';
$pos='';

$consulta = "Select * from albaranes,clientes where albaranes.codalbaran='$codalbaran' and albaranes.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"];

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$ww=array(80,25,90,20);
/*/Ttulos de las columnas*/
$header=array('Nombre','RUT','Dirección','Teléfono');

$codcliente=$_GET["codcliente"];

$subtitle="Detalles equipos";

/* Defino datos del cabezal */
$title="Listado de Equipos";
/*/Ancho y Titulos de las columnas*/
$ww=array(40,79,15,35);
$header=array('Alias','Detalle','Nº','Service');


$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();



$tipo = array("Sin definir", "Sin Servicio","Con Mantenimiento", "Mantenimiento y Respaldos");
$queryc="SELECT * FROM equipos WHERE borrado=0 AND codcliente='$codcliente' order by fecha ASC";
$rs_queryc=mysqli_query($GLOBALS["___mysqli_ston"], $queryc);

$pdf->SetFont('Arial','',6);
$contador=0;
while ($contador < mysqli_num_rows($rs_queryc)) {
//	$pdf->Cell($ww[0],4,implota(mysql_result($rs_queryc,$contador,"fecha")),'L',0,'L');
	$pdf->Cell($ww[0],4,mysqli_result($rs_queryc, $contador, "alias"). ' - '. mysqli_result($rs_queryc, $contador, "descripcion"),'LT',0,'L');
	
	
	$detalles=$detallesA=$acotado='';
if(mysqli_result($rs_queryc, $contador, "detalles")!='') {
 		$detallesA=$detalles.=" ". mysqli_result($rs_queryc, $contador, "detalles");
		$largo_ini=strlen($detalles);
		if($largo_ini>76) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 76);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, 76);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 76);
		}
} 
		
	$pdf->Cell($ww[1],4,$acotado,'LT',0,'L');
	$pdf->Cell($ww[2],4,mysqli_result($rs_queryc, $contador, "numero"),'LT',0,'L');
	$pdf->Cell($ww[3],4,$tipo[mysqli_result($rs_queryc, $contador, "service")],'LTR',0,'L');
	$pdf->Ln(4);	
		
	$ini=$pos;
		$renglon=1;
		while ($largo==1 and $renglon<2){
			$largo_ini=strlen($resta);
			if($largo_ini>90) {
				$largo=1;
				$texto_corto = substr($resta, 0, 76);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 76);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, 76);
			}
			$ini=$ini+$pos;	

			$pdf->Cell($ww[0],4,' ','L',0,'L');
			$pdf->Cell($ww[1],4,$acotado,'L',0,'L');
			$pdf->Cell($ww[2],4,' ','L',0,'C');	
	  		$pdf->Cell($ww[3],4,' ','LR',0,'R');
			$pdf->Ln(4);

		  $renglon++;
		}
		/*
			$pdf->Cell($ww[0],4,' ','LT',0,'L');
			$pdf->Cell($ww[1],4,$acotado,'LT',0,'L');
			$pdf->Cell($ww[2],4,' ','LT',0,'C');	
	  		$pdf->Cell($ww[3],4,' ','LT',0,'R');
			$pdf->Ln(4);
			*/
	$detallesA=$acotado='';

	$contador++;
}
			$pdf->Cell($ww[0],4,'','T',0,'L');
			$pdf->Cell($ww[1],4,'','T',0,'L');
			$pdf->Cell($ww[2],4,'','T',0,'C');	
	  		$pdf->Cell($ww[3],4,'','T',0,'R');
$nombre='../copias/equipos.pdf';	
	
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
