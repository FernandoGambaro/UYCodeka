<?php
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

date_default_timezone_set("America/Montevideo"); 

include ("../conectar.php");  
include ("../funciones/fechas.php");

define('FPDF_FONTPATH','font/');
require('mysql_table.php');

include("comunes_cliente.php");

	$moneda = array();
	
							/*Genero un array con los simbolos de las monedas*/
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }	


$pdf=new PDF();
$pdf->Open();
$pdf->AddPage();


$codfactura=$_GET["codfactura"];
$codproveedor=$_GET["codproveedor"];
  
$consulta = "Select * from facturasp,proveedores where facturasp.codfactura='$codfactura' and facturasp.codproveedor='$codproveedor' and facturasp.codproveedor=proveedores.codproveedor";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

	$mon=$lafila["moneda"];

    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);	
	
    $pdf->Cell(40,65,'NO VÁLIDO COMO FACTURA');
	$pdf->SetX(10);	

    $pdf->Cell(95);
    $pdf->Cell(80,4,"",'LRT',0,'L',1);
    $pdf->Ln(4);
	
    $pdf->Cell(95);
    $pdf->Cell(80,4,$lafila["nombre"],'LR',0,'L',1);
    $pdf->Ln(4);

    $pdf->Cell(95);
    $pdf->Cell(80,4,$lafila["direccion"],'LR',0,'L',1);
    $pdf->Ln(4);
	
	//Calculamos la provincia
	$codigoprovincia=$lafila["codprovincia"];
	$consulta="select * from provincias where codprovincia='$codigoprovincia'";
	$query=mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$row=mysqli_fetch_array($query);

	$pdf->Cell(95);
    $pdf->Cell(80,4,$lafila["codpostal"] . "  " . $lafila["localidad"] . "  (" . $row["nombreprovincia"] . ")",'LR',0,'L',1);
    $pdf->Ln(4);		
	
    $pdf->Cell(95);
    $pdf->Cell(80,4,"Télefono: " . $lafila["telefono"] . "  " . "Movil: " . $lafila["movil"],'LR',0,'L',1);
    $pdf->Ln(4);
	
    $pdf->Cell(95);
    $pdf->Cell(80,4,"",'LRB',0,'L',1);
    $pdf->Ln(10);					

    $pdf->SetFillColor(255,191,116);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
    $pdf->Cell(80);
    $pdf->Cell(30,4,"RUT/CI",1,0,'C',1);
	$pdf->Cell(30,4,"Cod. Proveedor",1,0,'C',1);
	$pdf->Cell(30,4,"Fecha",1,0,'C',1);	
	$pdf->Cell(20,4,"Nº Factura",1,0,'C',1);
	$pdf->Ln(4);
	
	$pdf->Cell(80);
	$pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);
	
	$fecha = implota($lafila["fecha"]);
	
    $pdf->Cell(30,4,$lafila["nif"],1,0,'C',1);
	$pdf->Cell(30,4,$lafila["codproveedor"],1,0,'C',1);
	$pdf->Cell(30,4,$fecha,1,0,'C',1);	
	$pdf->Cell(20,4,$codfactura,1,0,'C',1);		
	
	
	//ahora mostramos las lneas del albarn
	$pdf->Ln(10);		
	$pdf->Cell(1);
	
	$pdf->SetFillColor(255,191,116);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
    $pdf->Cell(40,4,"Referencia",1,0,'C',1);
	$pdf->Cell(80,4,"Descripcion",1,0,'C',1);
	$pdf->Cell(20,4,"Cantidad",1,0,'C',1);	
	$pdf->Cell(15,4,"Precio",1,0,'C',1);
	$pdf->Cell(15,4,"% Desc.",1,0,'C',1);	
	$pdf->Cell(20,4,"Importe",1,0,'C',1);
	$pdf->Ln(4);
			
			
	$pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);

	
	$consulta2 = "Select * from factulineap where codfactura='$codfactura' and codproveedor='$codproveedor' order by numlinea";
    $resultado2 = mysqli_query( $conexion, $consulta2);
    $lineas=0;
	$contador=1;
	$importe=0;
	while ($row=mysqli_fetch_array($resultado2))
	{
	  $pdf->Cell(1);
	  $contador++;
	  $codarticulo=mysqli_result($resultado2, $lineas, "codigo");
	  $codfamilia=mysqli_result($resultado2, $lineas, "codfamilia");
	  $sel_articulos="SELECT * FROM articulos WHERE codarticulo='$codarticulo' AND codfamilia='$codfamilia'";
	  $rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
	  $pdf->Cell(40,4,mysqli_result($rs_articulos, 0, "referencia"),'LR',0,'L');
	  
	  $acotado = substr(mysqli_result($rs_articulos, 0, "descripcion"), 0, 45);
	  $pdf->Cell(80,4,$acotado,'LR',0,'L');
	  
	  $pdf->Cell(20,4,mysqli_result($resultado2, $lineas, "cantidad"),'LR',0,'C');	
	  
	  $precio2= number_format(mysqli_result($resultado2, $lineas, "precio"),2,",",".");	  
	  $pdf->Cell(15,4,$precio2,'LR',0,'R');
	  
	  if (mysqli_result($resultado2, $lineas, "dcto")==0) 
	  {
	  $pdf->Cell(15,4,"",'LR',0,'C');
	  } 
	  else 
	   { 
		$pdf->Cell(15,4,mysqli_result($resultado2, $lineas, "dcto") . " %",'LR',0,'C');
	   }
	  $importe2= number_format(mysqli_result($resultado2, $lineas, "importe"),2,",",".");	  
	  $pdf->Cell(20,4,$importe2,'LR',0,'R');
	  $pdf->Ln(4);	

	  //vamos acumulando el importe
	  $importe=$importe + mysqli_result($resultado2, $lineas, "importe");
	  $contador=$contador + 1;
	  $lineas=$lineas + 1;
	  
};
	
	while ($contador<35)
	{
	  $pdf->Cell(1);
      $pdf->Cell(40,4,"",'LR',0,'C');
      $pdf->Cell(80,4,"",'LR',0,'C');
	  $pdf->Cell(20,4,"",'LR',0,'C');	
	  $pdf->Cell(15,4,"",'LR',0,'C');
	  $pdf->Cell(15,4,"",'LR',0,'C');
	  $pdf->Cell(20,4,"",'LR',0,'C');
	  $pdf->Ln(4);	
	  $contador=$contador +1;
	}

	  $pdf->Cell(1);
      $pdf->Cell(40,4,"",'LRB',0,'C');
      $pdf->Cell(80,4,"",'LRB',0,'C');
	  $pdf->Cell(20,4,"",'LRB',0,'C');	
	  $pdf->Cell(15,4,"",'LRB',0,'C');
	  $pdf->Cell(15,4,"",'LRB',0,'C');
	  $pdf->Cell(20,4,"",'LRB',0,'C');
	  $pdf->Ln(4);	


	//ahora mostramos el final de la factura
	$pdf->Ln(10);		
	$pdf->Cell(66);
	
	$pdf->SetFillColor(255,191,116);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
    $pdf->Cell(30,4,"Sub-total",1,0,'C',1);
	$pdf->Cell(30,4,"% IVA",1,0,'C',1);
	$pdf->Cell(30,4,"IVA",1,0,'C',1);	
	$pdf->Cell(35,4,"TOTAL",1,0,'C',1);
	$pdf->Ln(4);
	
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',8);
	
	$pdf->Cell(66);
    $importe4=number_format($importe,2,",",".");	
    $pdf->Cell(30,4,$importe4,1,0,'R',1);
	$pdf->Cell(30,4,$lafila["iva"] . "%",1,0,'C',1);
	
	$ivai=$lafila["iva"];
	$impo=$importe*($ivai/100);
	$impo=sprintf("%01.2f", $impo); 
	$total=$importe+$impo; 
	$total=sprintf("%01.2f", $total);

	$impo=number_format($impo,2,",",".");	
	$pdf->Cell(30,4,"$impo",1,0,'R',1);	
    $total=sprintf("%01.2f", $total);
	$total2= number_format($total,2,",",".");	

	$mone='';
	if($mon!='') {
		$mone=$moneda[$mon];
	}
	$pdf->Cell(35,4,$mone." ".$total2,1,0,'R',1);
	$pdf->Ln(4);

      @((mysqli_free_result($resultado) || (is_object($resultado) && (get_class($resultado) == "mysqli_result"))) ? true : false); 
      @((mysqli_free_result($query) || (is_object($query) && (get_class($query) == "mysqli_result"))) ? true : false);
	  @((mysqli_free_result($resultado2) || (is_object($resultado2) && (get_class($resultado2) == "mysqli_result"))) ? true : false); 
	  @((mysqli_free_result($query3) || (is_object($query3) && (get_class($query3) == "mysqli_result"))) ? true : false);

$pdf->Output();
?>