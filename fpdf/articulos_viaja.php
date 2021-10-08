<?php
date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
//require('mysql_table.php');

require('pdf_js.php');

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
   $script = "var pp = getPrintParams();";
   if($dialog)
   	$script .= "pp.interactive = pp.constants.interactionLevel.full;";
   else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
 	$script .= "pp.colorOverride = pp.constants.colorOverrides.gray;";
	$script .="var fv = pp.constants.flagValues;";
	$script .= "pp.flags |= fv.setPageSize;";
	$script .= "pp.pageHandling=1;";
	$script .= 'pp.printerName="Facturacion";';
	$script .="print(pp);";
	$script .="closeDoc(pp);";
	$script .=" doc.Close();";
   $this->IncludeJS($script);
}
}

include("comunes_clientePrint.php");


$codartiviaja=$_GET["codartiviaja"];
$envio=@$_GET['envio'];


$obs='';
$obs[0]=$obs[1]=$obs[2]=$obs[3]=$obs[4]=$obs[5]=$obs[6]='';


$consulta = "Select * from artiviaja,clientes where artiviaja.codartiviaja='$codartiviaja' and artiviaja.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"];

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$impresorareporte=mysqli_result($rs_datos, 0, "impresorareporte");
$servidorimpresora=mysqli_result($rs_datos, 0, "servidorreporte");

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
//$pdf=new PDF_AutoPrint($orientacion,'mm',array($ancho,$largo));

$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();


$pdf->SetY(18);	
	
$pdf->SetFont('Arial','B',7);

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);	
	
   $pdf->Cell(140);
	$pdf->Cell(20,5,"Fecha envío",1,0,'C',1);	
	$pdf->Cell(28,5,"Nº Remito",1,0,'C',1);
	$pdf->Ln();
	
	$pdf->Cell(140);
	$pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',6);
	$fecha = implota($lafila["fechaenvio"]);
	$pdf->Cell(20,5,$fecha,1,0,'C',1);	
	$pdf->Cell(28,5,$codartiviaja,1,0,'C',1);		
	$pdf->Ln();
	
	$pdf->Ln(7);	
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',13);
		
	$pdf->Cell(188,10,"Documento no válido como factura",1,0,'C',1);	
		
		
	$pdf->Ln(12);



$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	
	  $pdf->Cell(20,4,'Código','LRTB',0,'L',1);
	  $pdf->Cell(145,4,'Descripción','LRTB',0,'L',1);
	  $pdf->Cell(23,4,'Cantidad','LRTB',0,'C',1);	
	$pdf->Ln(4);
			
			
	$pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    $pdf->SetFont('Arial','',8);

/* --- Lineas de la orden de compra --- */
	
	$consulta32 = "Select * from artiviajalinea where codartiviaja='$codartiviaja' order by numlinea";
    $resultado32 = mysqli_query( $conexion, $consulta32);

	$lineas=0;
	while ($row=mysqli_fetch_array($resultado32))
	{
	$pos=0;

	  //$contador++;
	  $codarticulo=mysqli_result($resultado32, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado32, $lineas, "codfamilia");

	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);

	  $detallesA=$detalles=mysqli_result($rs_articulos, 0, "descripcion")." ";
	  
	  if(mysqli_num_rows($rs_articulos)>0) {
	  $pdf->Cell(20,4,mysqli_result($rs_articulos, 0, "referencia"),'LR',0,'L');
		} else {
	  $pdf->Cell(20,4,'','LR',0,'L');
	  }

	  if(mysqli_num_rows($rs_articulos)>0) {
 		$detallesA=$detalles.=mysqli_result($resultado32, $lineas, "detalles");
		} else {
 		$detallesA=$detalles.='';
		}
		$largo_ini=strlen($detalles);
		if($largo_ini>100) {
			$largo=1;
			$texto_corto = substr($detalles, 0, 90);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, 90);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, 90);
		}

	  $pdf->Cell(145,4,$acotado,'LR',0,'L');
 
	   
  	  $pdf->Cell(23,4,mysqli_result($resultado32, $lineas, "cantidad"),'LR',0,'C');	

	  $pdf->Ln(4);
	  	
		$ini=$pos;
		while ($largo==1){
			$largo_ini=strlen($resta);
			if($largo_ini>90) {
				$largo=1;
				$texto_corto = substr($resta, 0, 90);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, 90);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, 90);
			}
			$ini=$ini+$pos;	
	  		$pdf->Cell(8);
			$pdf->Cell(145,4,$acotado,0,0,'L');
			$pdf->Cell(23,4,' ',0,0,'C');	
			$pdf->Ln(4);
		  $lineas++;
		}
	  //vamos acumulando el importe

	  $lineas=$lineas + 1;	  
	};
	
	
	while ($lineas<42)
	{
	  $pdf->Cell(20,4,'','LR',0,'L');
	  $pdf->Cell(145,4,'','LR',0,'L');
	  $pdf->Cell(23,4,'','LR',0,'C');	
	  $pdf->Ln(4);	
	  $lineas=$lineas +1;
	}
	if($lineas>=41) {
	  $pdf->Cell(20,1,'','T',0,'L');
	  $pdf->Cell(145,1,'','T',0,'L');
	  $pdf->Cell(23,1,'','T',0,'C');	
	}

$wmax=188; 
    
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	

$pdf->Ln(2);
$pdf->Cell($wmax,4,"Datos del transportista",'LRTB',0,'L',1);
    
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	
	$pdf->Ln(4);
   $pdf->Cell(30,4,"Empresa",'LTR',0,'L',0);
   $pdf->Cell($wmax-30,4,$lafila["transportista"],'LTR',0,'L',0);
	$pdf->Ln(4);
   $pdf->Cell(30,4,"Datos del Vehículo",'LR',0,'L',0);
   $pdf->Cell($wmax-30,4,$lafila["vehiculo"],'LR',0,'L',0);
	$pdf->Ln(4);
   $pdf->Cell(30,4,"Chofer",'LR',0,'L',0);
   $pdf->Cell($wmax-30,4,$lafila["chofer"],'LR',0,'L',0);
	$pdf->Ln(4);
   $pdf->Cell(30,4,"Lugar de entrega",'LRB',0,'L',0);
   $pdf->Cell($wmax-30,4,$lafila["destino"],'LRB',0,'L',0);
   
    
$s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
$obs= explode("\n", wordwrap($s, $wmax));


$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	

	$pdf->Ln(7);
   $pdf->Cell($wmax,4,"Observaciones",'LRTB',0,'L',1);


$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	
	$pdf->Ln(4);
   $pdf->Cell($wmax,4,$obs[0],'LTR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[1],'LR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[2],'LR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[3],'LR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[4],'LR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[5],'LR',0,'L',0);
	$pdf->Ln(3);
   $pdf->Cell($wmax,4,@$obs[6],'LRB',0,'L',0);


	$pdf->Ln(5);
	$pdf->Cell(165);
   $pdf->Cell(20,4,"Original Cliente",0,0,'L',0);

      @((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
      @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	  @((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	  @((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);

if ($envio==1) {
	$filename='RemitoMercaderia-'.$codcliente."-".date("Gi").date("dmY").".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
	header("Location: ../enviomail/enviomail.php?codcliente=".$codcliente."&file=".$filename."&documento=el remito de mercadería");
	
} else {
$nombre='../tmp/artiviaja.pdf';			
//$pdf->Output($nombre,'I');	

	$pdf->AutoPrintToPrinter($servidorimpresora, $impresorareporte, true);
	$pdf->Output('$nombre','I');
	$pdf->Close();

}	  	
?> 
