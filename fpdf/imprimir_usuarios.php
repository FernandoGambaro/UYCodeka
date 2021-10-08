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
$header=array('Nombre','Estado','Tipo','Email','Teléfono','Usuario');
$w=$ww=array(50,15,25,35,25,35);

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
$pdf->MultiCell(290,6,"Listado de Usuarios",0,'C',0);

$pdf->Ln(15);    
	
//Restauracin de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',7);

//Buscamos y listamos las familias

$sel_usuarios="select * from usuarios where  usuarios.borrado=0 order by usuarios.estado asc";
$rs_usuarios=mysqli_query($GLOBALS["___mysqli_ston"], $sel_usuarios);
$contador=0;
$item=1;
$numero_usuarios=mysqli_num_rows($rs_usuarios);
		if ($numero_usuarios>0) {	
						$Tipox = array(
						        1=>"Usuario",
						        2=>"Administrador",
						        3=>"Vendedor",
						        4=>"Asistente",
						        5=>"Administrativo");

			$pdf->SetFillColor(200,200,200);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetLineWidth(.2);
			$pdf->SetFont('Arial','B',8);
			//Cabecera
			//for($i=0;$i<count($header);$i++)
			//	$pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
			//$pdf->Ln();
			$pdf->SetFont('Arial','',8);
			while ($contador < mysqli_num_rows($rs_usuarios)) {
				$pdf->Cell($w[0],5,mysqli_result($rs_usuarios, $contador, "nombre"). ' '.mysqli_result($rs_usuarios, $contador, "apellido"),'LRTB',0,'L');
				$pdf->Cell($w[1],5,mysqli_result($rs_usuarios, $contador, "estado"),'LRTB',0,'C');
				$pdf->Cell($w[2],5,$Tipox[mysqli_result($rs_usuarios, $contador, "tratamiento")],'LRTB',0,'L');
				$pdf->Cell($w[3],5,mysqli_result($rs_usuarios, $contador, "email"),'LRTB',0,'L');
				$pdf->Cell($w[4],5,mysqli_result($rs_usuarios, $contador, "telefono"),'LRTB',0,'C');
				$pdf->Cell($w[5],5,mysqli_result($rs_usuarios, $contador, "usuario"),'LRTB',0,'L');
				
				$pdf->Ln();
				$item++;
				$contador++;
			}
		};
		
	$pdf->Output();

?> 