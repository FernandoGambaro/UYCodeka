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

include("comunes.php");

$header=array('Referencia','Descripcion','P. Venta','P. Compra','Stock');
$ww=array(30,80,15,15,15);

$codproveedor=@$_GET['cboProveedores'];
$codigobarras=@$_GET['codigobarras'];
$referencia=@$_GET['referencia'];
$descripcion=@$_GET['descripcion'];
$stock=@$_GET['stock'];

$whereA="";
if ($codigobarras <> "") { $whereA.=" AND codigobarras='$codigobarras'"; }
if ($referencia <> "") { $whereA.=" AND referencia='$referencia'"; }
if ($descripcion <> "") { $whereA.=" AND descripcion LIKE'%$descripcion%'"; }
if ($stock <> "") { $whereA.=" AND stock>='$stock'"; }


$where="1=1";
if ($codproveedor <> "") { $where.=" AND codproveedor='$codproveedor'"; }
$where.=" ORDER BY  nombre ASC";

$consulta="select codproveedor,nombre,direccion from proveedores where borrado=0 AND ".$where;
$query = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);

  
if($codproveedor>0) {
$title='Listado de Articulos, Proveedor '.mysqli_result($query, 0, "nombre");
} else {
	$title='Listado de Articulos por Proveedor';
}

$pdf=new PDF();
$pdf->Open();
$pdf->AddPage();

//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);

		$linea=0;
$consulta="select codproveedor,nombre,direccion from proveedores where borrado=0 AND ".$where;
$query = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);

//Buscamos y listamos las familias
while ($row = mysqli_fetch_array($query))    {
		$sel_articulos="select * from articulos where borrado=0 and (codproveedor1=".$row["codproveedor"]." or codproveedor2=".$row["codproveedor"].") " .$whereA;
		$rs_articulos=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulos);
		$contador=0;
		$numero_articulos=mysqli_num_rows($rs_articulos);
				if ($numero_articulos>0) {	
					if($codproveedor==0 or $codproveedor=='') {
					if($linea>0) {
						$pdf->Ln();
					}
					$linea++;
					$pdf->SetFont('Arial','',8);
					$pdf->MultiCell(220,6,$row["nombre"]." - ".$row["direccion"],0,'L',0);
					$pdf->SetFont('Arial','',8);
					}
					while ($contador < mysqli_num_rows($rs_articulos)) {
						$pdf->Cell($ww[0],5,mysqli_result($rs_articulos, $contador, "referencia"),'LRTB',0,'C');
						$pdf->Cell($ww[1],5,substr(mysqli_result($rs_articulos, $contador, "descripcion"), 0, 50),'LRTB',0,'L');
						$pdf->Cell($ww[2],5,mysqli_result($rs_articulos, $contador, "precio_tienda"),'LRTB',0,'C');
						$pdf->Cell($ww[3],5,mysqli_result($rs_articulos, $contador, "precio_compra"),'LRTB',0,'C');
						$pdf->Cell($ww[4],5,mysqli_result($rs_articulos, $contador, "stock"),'LRTB',0,'C');
						if(($contador+1) < mysqli_num_rows($rs_articulos)) {
						$pdf->Ln();
						}
						$contador++;
					}
			};
};
			
$pdf->Output();
?>