<?php
date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

$codpresupuesto=$_GET["codpresupuesto"];
$envio=$_GET['envio'];
$moneda='';
$impo=0;
$importe=0;
$obs='';
$obs[0]=$obs[1]=$obs[2]=$obs[3]=$obs[4]=$obs[5]=$obs[6]='';


$consulta = "Select * from presupuestos,clientes where presupuestos.codpresupuesto='$codpresupuesto' and presupuestos.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"];

if ($lafila["moneda"]==2){
$moneda="U".chr(36)."S";
} else {
$moneda="$";
}

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}


$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
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
	
   $pdf->Cell(138);
	$pdf->Cell(20,5,"Fecha",1,0,'C',1);	
	$pdf->Cell(30,5,"Nº Presupuesto",1,0,'C',1);
	$pdf->Ln();
	
	$pdf->Cell(138);
	$pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',6);
	$fecha = implota($lafila["fecha"]);
	$pdf->Cell(20,5,$fecha,1,0,'C',1);	
	$pdf->Cell(30,5,$codpresupuesto,1,0,'C',1);		
	$pdf->Ln();
		
	$pdf->Ln(8);

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);	
	
	$pdf->SetFont('Arial','B',7);   
	$pdf->Cell(27,5,"Fecha de entrega",1,0,'C',1);	
	
	$pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',6);
	$fecha = implota($lafila["fechaentrega"]);
	$pdf->Cell(20,5,$fecha,1,0,'C',1);	

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);	
	
	$pdf->SetFont('Arial','B',7);   
	$pdf->Cell(15,5,"Contacto",1,0,'C',1);	
	
	$pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',6);
	$solicitado = $lafila["solicitado"];
	$pdf->Cell(40,5,$solicitado,1,0,'L',1);

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.1);	

	$pdf->SetFont('Arial','B',7);   
	$pdf->Cell(15,5,"Lugar",1,0,'C',1);	
	
	$pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0);
   $pdf->SetDrawColor(0,0,0);
   $pdf->SetLineWidth(.1);
   $pdf->SetFont('Arial','',6);
	$pdf->Cell(70,5,$lafila["lugarentrega"],1,0,'L',1);	

	//ahora mostramos las lneas del albarn
	$pdf->Ln(10);		

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);	
$pdf->SetFont('Arial','B',8);
	
	  $pdf->Cell(25,4,'Código','LRTB',0,'L',1);
	  $pdf->Cell(14,4,'Cantidad','LRTB',0,'C',1);	
	  $pdf->Cell(114,4,'Descripción','LRTB',0,'L',1);
	  $pdf->Cell(15,4,'Unitario','LRTB',0,'R',1);
	  $pdf->Cell(20,4,'Precio','LRTB',0,'R',1);
	$pdf->Ln(4);
			
			
	$pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    $pdf->SetFont('Arial','',8);

/* --- Lineas de la orden de compra --- */
	
	$consulta32 = "Select * from presulinea where codpresupuesto='$codpresupuesto' order by numlinea";
   $resultado32 = mysqli_query( $conexion, $consulta32);
	$importe=0;
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
	  $pdf->Cell(25,4,mysqli_result($rs_articulos, 0, "referencia"),'LR',0,'L');
		} else {
	  $pdf->Cell(25,4,'','LR',0,'L');
	  }
	  
	  $pdf->Cell(14,4,mysqli_result($resultado32, $lineas, "cantidad"),'LR',0,'C');	

	  if(mysqli_num_rows($rs_articulos)>0) {
 		$detallesA=$detalles.=" ".mysqli_result($resultado32, $lineas, "detalles");
		} else {
 		$detallesA=$detalles.='';
		}
		$largo_ini=strlen($detalles);
		if($largo_ini>90) {
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

	  $pdf->Cell(114,4,$acotado,'LR',0,'L');

	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  if(mysqli_result($resultado32, $lineas, "cantidad")>0) {
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe")/mysqli_result($resultado32, $lineas, "cantidad"),2,",",".");
	  } else {
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");
	  }	 
	   
	  $pdf->Cell(15,4,$precio32,'LR',0,'R');
	  	  
	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  

	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado32, $lineas, "importe");
	  
	  $pdf->Cell(20,4,$importe32,'LR',0,'R');
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
	  		//$pdf->Cell(8);
			$pdf->Cell(25,4,' ','LR',0,'L');
			$pdf->Cell(14,4,' ','LR',0,'C');	
			$pdf->Cell(114,4,$acotado."-",'LR',0,'L');
	  		$pdf->Cell(15,4,' ','LR',0,'R');
	  		$pdf->Cell(20,4,' ','LR',0,'R');
			$pdf->Ln(4);
		  $lineas++;
		}

	  $lineas=$lineas + 1;	  
	};
	
	
	while ($lineas<45)
	{
	  $pdf->Cell(25,4,'','LR',0,'L');
	  $pdf->Cell(14,4,'','LR',0,'C');	
	  $pdf->Cell(114,4,'','LR',0,'L');
	  $pdf->Cell(15,4,'','LR',0,'R');
	  $pdf->Cell(20,4,'','LR',0,'R');
	  $pdf->Ln(4);	
	  $lineas=$lineas +1;
	}
	if($lineas>=44) {
	  $pdf->Cell(25,1,'','T',0,'L');
	  $pdf->Cell(14,1,'','T',0,'C');	
	  $pdf->Cell(114,1,'','T',0,'L');
	  $pdf->Cell(15,1,'','T',0,'R');
	  $pdf->Cell(20,1,'','T',0,'R');
	}

	//$pdf->Ln(8);		

/**/
    $wmax=188; 
	 $s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
/**/	
	$obs= explode("\n", wordwrap($s, $wmax));


	
	$pdf->Ln(1);			
	$pdf->Cell(90);
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.1);	
	$pdf->SetFont('Arial','B',8);
   $pdf->Cell(25,4,"SUB TOTAL",'LRTB',0,'C',1);
	$pdf->Cell(27,4,"DESCUENTO",'LRTB',0,'C',1);
   $pdf->Cell(26,4,"IVA",'LRTB',0,'C',1);
   $pdf->Cell(20,4,"TOTAL",'LRTB',0,'C',1);


	$pdf->Ln(4);			
	$pdf->Cell(90);
	
/* se imprime Sub-total*/   
?>