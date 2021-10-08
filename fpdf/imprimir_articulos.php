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


$envio=$_GET['envio'];
$moneda='';
$impo=0;
$importe=0;
$obs='';
$obs[0]=$obs[1]=$obs[2]=$obs[3]=$obs[4]=$obs[5]=$obs[6]='';


$consulta = "Select * from albaranes,clientes where albaranes.codalbaran='$codalbaran' and albaranes.codcliente=clientes.codcliente";
$resultado = mysqli_query( $conexion, $consulta);
$lafila=mysqli_fetch_array($resultado);

$codcliente=$lafila["codcliente"];

$sql_datos="SELECT papel FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$wxh=explode("x", mysqli_result($rs_datos, 0, "papel"));

$ancho=(float)$wxh[1];
$largo=(float)$wxh[0];
$orientacion=$wxh[2];
if ($orientacion==''){
$orientacion='P';
}

//Ttulos de las columnas
$header=array('Item','Familia','Cod. Articulo','Referencia','Descripcion','P. Tienda','P. Compra','Stock');
$ww=array(10,40,70,30,80,15,15,15);


$pdf=new PDF($orientacion,'mm',array($ancho,$largo));
$pdf->Open();
$pdf->SetMargins(10, 10 , 10, true);
$pdf->AliasNbPages();
$pdf->AddPage();


//Nombre del Listado
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',16);
$pdf->SetY(20);
$pdf->SetX(0);
$pdf->MultiCell(290,6,"Listado de Articulos",0,'C',0);

$pdf->Ln();    
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);

//Buscamos y listamos las familias

$sel_articulos="select articulos.*,familias.nombre from articulos,familias where articulos.codfamilia=familias.codfamilia and articulos.borrado=0
 order by familias.codfamilia asc, articulos.codarticulo asc";
$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
$contador=0;
$item=1;
$numero_articulos=mysqli_num_rows($rs_articulos);
		if ($numero_articulos>0) {		
		//	$pdf->SetFont('Arial','',8);
		//	$pdf->MultiCell(220,6,$row["nombre"],0,L,0);
			$w=array(10,40,70,30,80,15,15,15);


			
			//Colores, ancho de lnea y fuente en negrita
			$pdf->SetFillColor(200,200,200);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','B',8);
				
			//Cabecera
			for($i=0;$i<count($header);$i++)
				$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
			$pdf->Ln();
			$pdf->SetFont('Arial','',8);
			while ($contador < mysqli_num_rows($rs_articulos)) {
				$pdf->Cell($w[0],5,$item,'LRTB',0,'C');
				$pdf->Cell($w[1],5,mysqli_result($rs_articulos, $contador, "nombre"),'LRTB',0,'C');
				$pdf->Cell($w[2],5,mysqli_result($rs_articulos, $contador, "codarticulo"),'LRTB',0,'C');
				$pdf->Cell($w[3],5,mysqli_result($rs_articulos, $contador, "referencia"),'LRTB',0,'C');
				$pdf->Cell($w[4],5,mysqli_result($rs_articulos, $contador, "descripcion"),'LRTB',0,'L');
				$pdf->Cell($w[5],5,mysqli_result($rs_articulos, $contador, "precio_tienda"),'LRTB',0,'C');
				$pdf->Cell($w[6],5,mysqli_result($rs_articulos, $contador, "precio_compra"),'LRTB',0,'C');
				$pdf->Cell($w[7],5,mysqli_result($rs_articulos, $contador, "stock"),'LRTB',0,'C');
				
				$pdf->Ln();
				$item++;
				$contador++;
			}
		};
		
if ($envio==1) {
	$filename='OrdenDeCompra-'.$codcliente."-".date("Gi").date("dmY").".pdf";
	$file='../tmp/'.$filename;
	$pdf->Output($file, 'F');
	header("Location: ../enviomail/enviomail.php?codcliente=".$codcliente."&file=".$filename);
	
} else {
	$pdf->Output();
}
?> 