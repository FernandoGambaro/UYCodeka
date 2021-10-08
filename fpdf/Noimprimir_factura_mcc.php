<?php
error_reporting(E_ALL);
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

define('FPDF_FONTPATH','font/');
//require('mysql_table.php');
//include("comunes_factura.php");
include ("../conectar.php");
include ("../funciones/fechas.php"); 
require('fpdf.php');
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
	if($server=='') {
		$script .= 'pp.printerName="'.$printer.'";';
	} else {
		$script .= "pp.printerName='\\\\\\\\".$server."\\\\".$printer."';";
	} 
	$script .="print(pp);";
	$script .="closeDoc(pp);";
	$script .=" doc.Close();";
   $this->IncludeJS($script);
}
}

function forceRoundUp($value, $decimals)
{
    $ord = pow(10, $decimals);
    return ceil($value * $ord) / $ord;
}

$codfactura=@$_GET["codfactura"];
$envio=@$_GET['envio'];  

$consumo='';
$lineas='';
$impo='';
$importe='';
$largoDescripcion=95;

//$pdf=new FPDF('L','mm',array(210,145));

$pdf=new PDF_AutoPrint('L','mm',array(210,145));

$pdf->Open();
$pdf->SetMargins(0, 3 , 4);

$pdf->AddPage('L');

if ($envio==1) {
	$pdf->Image('../img/basicoA5.png', 0, 0, 219, 155);
}
$pdf->AddFont('MyriadPro-LightSemiCn','','myriadpro-lightsemicn.php');
$pdf->AddFont('Euro','','Eurocl15.php');

$pdf->SetAutoPageBreak('auto' ,3);
  
$consulta = "Select *,facturas.codformapago as codformapago, facturas.tipo as facturatipo from facturas,clientes where facturas.codfactura='$codfactura' and facturas.codcliente=clientes.codcliente";
$resultado = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
$lafila=mysqli_fetch_array($resultado);

$codformapago=$lafila["codformapago"];
$codcliente=$lafila["codcliente"];
if ($lafila["moneda"]>2){
$moneda="E";
}elseif ($lafila["moneda"]==2){
$moneda="U".chr(36)."S";
} else {
$moneda="$";
}

    $pdf->Ln(20);					

/* Establezco el color de las celdas y tipo de letra */
   $pdf->Cell(110); /* (109) ubicación inicial de la celdas */


   $pdf->SetFont('MyriadPro-LightSemiCn','',11);
   $fecha = implota($lafila["fecha"]);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(25,4,$fecha,0,0,'C',0);	

	$tipo = array( 0=>"Contado", 1=>"Crédito", 2=>"Nota Crédito");
	$num=$lafila['facturatipo'];
	$tipof=$tipo[$num];

	$pdf->Cell(32,4,$tipof,0,0,'C',0);
	$pdf->Cell(11);
	$pdf->Cell(15,4,$codfactura,0,0,'C',0);		
	$pdf->Ln(8);
    $pdf->Cell(118); /* ubicación inicial de la celdas*/
	$pdf->Cell(20,4,$lafila["codcliente"],0,0,'',0);
   $pdf->Cell(14); /* ubicación inicial de la celdas*/

if($lafila["empresa"]!='') {
	$nombre=$lafila["empresa"];
} else {
	$nombre=$lafila["nombre"]." ".$lafila["apellido"];
}

	$pdf->Cell(42,4,$nombre,0,0,'',0);
	$pdf->Ln(4);
    $pdf->Cell(40); /* ubicación inicial de la celdas*/

   if (empty($lafila["nif"])){
   	$consumo="X";
   	} else {
   	$nif=$lafila["nif"];
   }
   $pdf->Cell(20,4,$nif,0,0,'L',0);
   
	$pdf->Cell(37);   
	$pdf->Cell(10,4,$consumo,0,0,'C',0);

	$pdf->Cell(19);

	$pdf->Cell(50,4,$lafila["direccion"],'0',0,'L',0);

	//ahora mostramos las líneas de la factura
	$pdf->Ln(10);		
	
	 $consulta32 = "Select * from factulinea where codfactura='$codfactura' GROUP BY numlinea order by numlinea";
    $resultado32 = mysqli_query($GLOBALS["___mysqli_ston"], $consulta32);
    
	$lineas=0;
	$contador=0;
	while ($lineas < mysqli_num_rows($resultado32)) { 
	//while ($row=mysql_fetch_array($resultado32))
	//{
	$pos=0;

	  $pdf->Cell(4); /* Cell(11) ubicación inicial de la celdas*/
	  //$contador++;
	  $codarticulo=mysqli_result($resultado32, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado32, $lineas, "codfamilia");
	  $detallesA=$detalles=mysqli_result($resultado32, $lineas, "detalles");
	  
	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	  
	  $pdf->Cell(25,4,mysqli_result($rs_articulos, 0, "referencia"),0,0,'L');
	  
	  $pdf->Cell(7,4,mysqli_result($resultado32, $lineas, "cantidad"),0,0,'R');	
	  $pdf->Cell(2); /* Cell(11) ubicación inicial de la celdas*/

/*	  if(mysql_result($rs_articulos,0,"descripcion")!='') {
 		$detallesA=$detalles.=" ".mysql_result($rs_articulos,0,"descripcion");
	  }
*/	   
		$largo_ini=strlen($detalles);
		if($largo_ini>$largoDescripcion) {
			$largo=1;
			$texto_corto = substr($detalles, 0, $largoDescripcion);
			$pos = strripos($texto_corto,' ');
			if ($pos !== false) { 
    			$acotado = substr($detallesA, 0, $pos);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
    			$pos=$pos+1;
			} else {	
				$acotado = substr($detallesA, 0, $largoDescripcion);
    			$resta=substr($detallesA, $pos, $largo_ini-$pos );
			}
		} else {
			$largo=0;
			$acotado = substr($detallesA, 0, $largoDescripcion);
		}

	  $pdf->Cell(132,4,$acotado,0,0,'L'); /* 122 */
	  
	if(mysqli_result($resultado32, $lineas, "importe")!=0) {
	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  
	  $precio32= number_format(mysqli_result($resultado32, $lineas, "importe")/mysqli_result($resultado32, $lineas, "cantidad"),2,",",".");	 
	   
	  $pdf->Cell(18,4,$precio32,0,0,'R');
	  	  
	  $importe32= number_format(mysqli_result($resultado32, $lineas, "importe"),2,",",".");	  
	  
	  $pdf->Cell(15,4,$importe32,0,0,'R');
	} else {
	  $pdf->Cell(18,4,'',0,0,'R');
	  $pdf->Cell(15,4,'',0,0,'R');
	}	  
	  
	  $pdf->Ln(4);
	  	
		$ini=$pos;
		$renglon=1;
		while ($largo==1 and $renglon<2){
			$largo_ini=strlen($resta);
			if($largo_ini>$largoDescripcion) {
				$largo=1;
				$texto_corto = substr($resta, 0, $largoDescripcion);
				$pos = strripos($texto_corto,' ');
				if ($pos !== false) { 
	    			$acotado = substr($detallesA, $ini, $pos);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
	    			$pos=$pos+1;
				} else {	
					$acotado = substr($detallesA, $ini, $largoDescripcion);
	    			$resta=substr($detallesA, $ini+$pos, $largo_ini-$pos );
				}
			} else {
				$largo=0;
				$acotado = substr($detallesA, $ini, $largoDescripcion);
			}
			$ini=$ini+$pos;	
		   $pdf->Cell(1);
	  		$pdf->Cell(5);
			$pdf->Cell(25,4,' ',0,0,'L');
			$pdf->Cell(9,4,' ',0,0,'C');	
			$pdf->Cell(132,4,$acotado,0,0,'L');
	  		$pdf->Cell(15,4,' ',0,0,'R');
	  		$pdf->Cell(20,4,' ',0,0,'R');
			$pdf->Ln(4);
		  $contador++;
		  $renglon++;
		}
	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado32, $lineas, "importe");
	  $contador++;
	  $lineas=$lineas + 1;	  
	};
	
	
	while ($contador<11)
	{
	  $pdf->Cell(1);
      $pdf->Cell(20,4,"",0,0,'C');
      $pdf->Cell(131,4,"",0,0,'C');
	  $pdf->Cell(15,4,"",0,0,'C');	
	  $pdf->Cell(15,4,"",0,0,'C');
	  $pdf->Cell(20,4,"",0,0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}

	$pdf->Ln(4);		

	//$pdf->Ln(8);		

/**/
    $wmax=50; 
	 $s=str_replace("\r",'',utf8_decode( $lafila["observacion"])); 
/**/	
	if($s!='') {
	$obs= explode("\n", wordwrap($s, $wmax));
	} else {
	$obs=array(0=>'', 1=>'', 2=>'', 3=>'');
	}
	$pdf->SetX(0);
	$pdf->SetY(107); /* 110 - Fuerzo el puntero a una posición luego de las líneas de detalles*/
	//$pdf->Ln(1);	
	
	$pdf->Ln(3);			
	$pdf->Cell(7); /* Cell(11) ubicación inicial de la celdas*/
	if($codformapago=="" or $codformapago==0) {
	$forma="Contado";
	$dias=0;
	} else {
	$sql_pago="SELECT * FROM formapago WHERE codformapago='".$codformapago."'";
	$rs_pago=mysqli_query($GLOBALS["___mysqli_ston"], $sql_pago);
	$forma=mysqli_result($rs_pago, 0, "nombrefp");
	$dias=mysqli_result($rs_pago, 0, "dias");
	}

	
	
$vence = strtotime ( '+'.$dias.' day' , strtotime ( $lafila["fecha"] ) ) ;
$vence = implota(date ( 'Y-m-j' , $vence ));

   $pdf->Cell(51,4,$forma,0,0,'L',0);
   $pdf->Cell(15,4,$vence,0,0,'L',0);

	$pdf->Cell(45);
/*Sub-total*/   
   $importe4=number_format($importe,2,",",".");	
   if ($lafila["moneda"]>2){
   $pdf->SetFont('Euro','',11);
   }
   $pdf->Cell(3,4,$moneda,0,0,'R',0);
   $pdf->Cell(2);   
   $pdf->SetFont('MyriadPro-LightSemiCn','',11);
   $pdf->Cell(10,4,$importe4,0,0,'R',0);
   $pdf->Cell(1);
   
/*Descuento*/
	$descuento=$lafila["descuento"];
	$impodescuento=$impodesc=$importe*(1-$descuento/100);
	$impodesc=sprintf("%01.2f", $impodesc); 
	$impodesc=number_format($impodesc,2,",",".");
	
if ($descuento!=0)	{
   $pdf->Cell(5,4,$descuento."%",0,0,'R',0);
} else {
   $pdf->Cell(5,4,"",0,0,'R',0);
}

	$impo=(int)$impo;
	$impo=number_format($impo,2,'',".");
if ($descuento==0) {	
	$pdf->Cell(10,4,"",0,0,'R',0);
} else {
	if ($lafila["moneda"]>2){
	 $pdf->SetFont('Euro','',11);
	 }
	$pdf->Cell(3,4,$moneda,0,0,'R',0);
   $pdf->Cell(2);	
   $pdf->SetFont('MyriadPro-LightSemiCn','',11);
	$pdf->Cell(9,4,$impodesc,0,0,'R',0);
	$pdf->Cell(1);
}  
/*IVA*/
		  	$query_iva="SELECT * FROM impuestos WHERE codimpuesto=".$lafila["iva"];
			$res_iva=mysqli_query($GLOBALS["___mysqli_ston"], $query_iva);
			$ivai=mysqli_result($res_iva, 0, "valor");
			$ivatxt=mysqli_result($res_iva, 0, "nombre");

	$pdf->Cell(16,4,mysqli_result($res_iva, 0, "nombre"),0,0,'L',0);
	$pdf->Cell(1);	
/*muestro iporte de iva*/
//	$pdf->Cell(15,4,$moneda." 444".$impoiva,0,0,'R',0); 

//	$ivai=$lafila["iva"];
	$impoiva=$importeiva=$impodescuento*($ivai/100);
	$impoiva=sprintf("%01.2f", $impoiva); 
	$impoiva=number_format($impoiva,2,",",".");
	if ($lafila["moneda"]>2){
	$pdf->SetFont('Euro','',11);
	}
	$pdf->Cell(3,4,$moneda,0,0,'R',0);
	$pdf->Cell(2);
	$pdf->SetFont('MyriadPro-LightSemiCn','',11); 
	$pdf->Cell(10,4,$impoiva,0,0,'R',0);
	$pdf->Cell(1);

//	$impo=$impo*($ivai/100);
	$total=round($impodescuento+$importeiva,2); 
   $total=sprintf("%01.2f", $total);
   $total=round(forceRoundUp($total, 3));
	$total2= number_format($total,2,",",".");	
	if ($lafila["moneda"]>2){
	$pdf->SetFont('Euro','',11);
	}
	$pdf->Cell(5);	
	$pdf->Cell(3,4,$moneda,0,0,'R',0);
   $pdf->Cell(2);
   $pdf->SetFont('MyriadPro-LightSemiCn','',11);
	$pdf->Cell(11,4,$total2,0,0,'R',0);
	

	$pdf->Ln(9);
	$pdf->Cell(6);
   $pdf->Cell(55,4,@$obs[0],0,0,'L',0);

	$pdf->Ln(3);
	$pdf->Cell(6);
   $pdf->Cell(55,4,@$obs[1],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(6);
   $pdf->Cell(55,4,@$obs[2],0,0,'L',0);
	$pdf->Ln(3);
	$pdf->Cell(6);
   $pdf->Cell(55,4,@$obs[3],0,0,'L',0);


if ($envio==1) {
	$pdf->Ln(5);
	$pdf->Cell(45);
   $pdf->SetTextColor(255);
   $pdf->Cell(40,4,"",0,0,'L',0);
	$pdf->Cell(45);
   $pdf->Cell(30,4,"Original Cliente",0,0,'L',0);

} else {
	$pdf->Ln(5);
	$pdf->Cell(130);
   $pdf->SetTextColor(255);
	$pdf->Cell(30,4,"Original Cliente",0,0,'L',0);

}
      @((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
      @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	  @((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	  @((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);	

if ($envio==1) {
	/*Hago efectivo el envío por mail*/
	$filename='Factura'.$codfactura.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
	//header("Location: ../enviomail/enviomail.php?codcliente=".$codcliente."&file=".$filename."&documento=de la factura nº ".$codfactura."&codfactura=".$codfactura);
	header("Location: ../enviomail/enviomail.php?codcliente=".$codcliente."&tipo=F&cod=".$codfactura."&file=".$filename."&documento=de la factura nº ".$codfactura);
} else {
	/*Lo imprimo directamente*/
$sql_datos="SELECT servidorfactura,impresorafactura,web,reporte  FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$server=mysqli_result($rs_datos, 0, "servidorfactura");
$printer=mysqli_result($rs_datos, 0, "impresorafactura");
$reporte=mysqli_result($rs_datos, 0, "reporte");
	
	if($reporte==1) {	
	$filename='Factura'.$codfactura.".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
		if (file_exists($file) and $printer!='') {
			/*Si existe el archivo lo envío directamente a la impresora*/
		 $comando = escapeshellcmd('lp  -d '.$printer.' -n 2 -o media=Custom.210x145mm -o fit-to-page '.$file);
		$output = shell_exec($comando); /*Envío la impresión por sistema*/
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
}
	@((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
   @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	@((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	@((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);	
	$query="UPDATE facturas SET `emitida`=1 WHERE codfactura='$codfactura'";
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
?>