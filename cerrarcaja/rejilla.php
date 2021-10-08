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
include ("../conectar.php");
include ("../funciones/fechas.php");

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }

$cadena_busqueda=$_POST["cadena_busqueda"];

/*Dolares*/
//$sel_facturas="SELECT max(codfactura) as maximo, min(codfactura) as minimo, sum(totalfactura) as totalfac FROM facturas WHERE fecha='$fechainicio'";
$sel_facturas_dol="SELECT max(cobros.codfactura) as maximo, min(cobros.codfactura) as minimo, sum(totalfactura) as totalfac 
FROM cobros INNER JOIN facturas ON cobros.codfactura=facturas.codfactura WHERE fechacobro='$fechainicio' and moneda=2";
$rs_facturas_dol=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas_dol);

if ($rs_facturas_dol !== false) {
	if (mysqli_num_rows($rs_facturas_dol) > 0 ) {
		$minimo_dol=mysqli_result($rs_facturas_dol, 0, "minimo");
		$maximo_dol=mysqli_result($rs_facturas_dol, 0, "maximo");
		$total_dol=mysqli_result($rs_facturas_dol, 0, "totalfac");
	} else {
		$minimo_dol=0;
		$maximo_dol=0;
		$total_dol=0;
	}
}
/* Dolares Contado */
 $sel_fact_con_dol="SELECT  sum(totalfactura) as totalcontadofac 
FROM facturas  WHERE fecha='$fechainicio' and moneda=2 and tipo=0";
$rs_fact_con_dol=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact_con_dol);

if (mysqli_num_rows($rs_fact_con_dol) > 0 ) {
	$total_dol+=mysqli_result($rs_fact_con_dol, 0, "totalcontadofac");
}

$neto_dol=$total_dol/1.22;
$iva_dol=$total_dol-$neto_dol;

$sel_cobros_dol="SELECT sum(importe) as suma,codformapago FROM cobros WHERE fechacobro='$fechainicio' and moneda=2 GROUP BY codformapago ORDER BY codformapago ASC";

$rs_cobros_dol=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobros_dol);

if (mysqli_num_rows($rs_cobros_dol) > 0) { $contado_dol=mysqli_result($rs_cobros_dol, 0, "suma"); } else { $contado_dol=0; }
if (mysqli_num_rows($rs_cobros_dol) > 1) { $tarjeta_dol=mysqli_result($rs_cobros_dol, 1, "suma"); } else { $tarjeta_dol=0; }

/*Pesos*/
//$sel_facturas="SELECT max(codfactura) as maximo, min(codfactura) as minimo, sum(totalfactura) as totalfac FROM facturas WHERE fecha='$fechainicio'";
$sel_facturas_pes="SELECT max(cobros.codfactura) as maximo, min(cobros.codfactura) as minimo, sum(totalfactura) as totalfac 
FROM cobros INNER JOIN facturas ON cobros.codfactura=facturas.codfactura WHERE fechacobro='$fechainicio' and moneda=1";
$rs_facturas_pes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas_pes);

if ($rs_facturas_pes !== false) {
	if (mysqli_num_rows($rs_facturas_pes) > 0 ) {
		$minimo_pes=mysqli_result($rs_facturas_pes, 0, "minimo");
		$maximo_pes=mysqli_result($rs_facturas_pes, 0, "maximo");
		$total_pes=mysqli_result($rs_facturas_pes, 0, "totalfac");
	} else {
		$minimo_pes=0;
		$maximo_pes=0;
		$total_pes=0;
	}
}

/* Pesos Contado */
$sel_fact_con_pes="SELECT max(codfactura) as maximo, min(codfactura) as minimo, sum(totalfactura) as totalcontadofac 
FROM facturas  WHERE fecha='$fechainicio' and moneda=1 and tipo=0";
$rs_fact_con_pes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact_con_pes);

if (mysqli_num_rows($rs_fact_con_pes) > 0 ) {
	$minimo_pes=mysqli_result($rs_fact_con_pes, 0, "minimo");
	$maximo_pes=mysqli_result($rs_fact_con_pes, 0, "maximo");
	$total_pes+=mysqli_result($rs_fact_con_pes, 0, "totalcontadofac");
}/* else {
	$minimo_con_pes=0;
	$maximo_con_pes=0;
	$total_con_pes=0;
}*/

$neto_pes=$total_pes/1.22;
$iva_pes=$total_pes-$neto_pes;

$sel_cobros_pes="SELECT sum(importe) as suma,codformapago FROM cobros WHERE fechacobro='$fechainicio' and moneda=1 GROUP BY codformapago ORDER BY codformapago ASC";

$rs_cobros_pes=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobros_pes);

if (mysqli_num_rows($rs_cobros_pes) > 0) { $contado_pes=mysqli_result($rs_cobros_pes, 0, "suma"); } else { $contado_pes=0; }
if (mysqli_num_rows($rs_cobros_pes) > 1) { $tarjeta_pes=mysqli_result($rs_cobros_pes, 1, "suma"); } else { $tarjeta_pes=0; }


	$moneda = array(1=>chr(32), 2=>"U".chr(32)."S");


?>
<html>
	<head>
		<title>Cierre Caja</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">	
		<script>
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		</script>
	
	</head>

	<body>	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
				<form id="formulario" name="formulario" method="post" action="rejilla.php">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
				<tr>
				<td colspan="2" width="100%" class="header">DETALLES CIERRE CAJA
				</td>
				</tr>					
				  <tr><td width="50%">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>					
					  <tr>
						  <td colspan="5">Pesos</td>
					  </tr>
					  <tr>
						  <td width="18%">Caja&nbsp;Fecha</td>
						  <td width="14%"><?php echo implota($fechainicio)?>	</td>
						  <td width="18%">&nbsp;</td>
						  <td width="40%">&nbsp;</td>
						  <td width="6%">&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Del&nbsp;la&nbsp;factura&nbsp;n&deg;</td>
						  <td><?php echo $minimo_pes?>	</td>
						  <td>al&nbsp;n&deg;</td>
						  <td><?php echo $maximo_pes?></td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Neto</td>
						  <td><?php echo number_format($neto_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>22%&nbsp;IVA</td>
						  <td><?php echo number_format($iva_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total</td>
						  <td><?php echo number_format($total_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total&nbsp;contado</td>
						  <td><?php echo number_format($contado_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total&nbsp;tarjetas</td>
						  <td><?php echo number_format($tarjeta_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total</td>
						  <td><?php echo number_format($total_pes,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table>

					  </td><td width="50%">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>					
					  <tr>
						  <td colspan="5">Dolares</td>
					  </tr>
					  <tr>
						  <td width="18%">Caja&nbsp;Fecha</td>
						  <td width="14%"><?php echo implota($fechainicio)?>	</td>
						  <td width="18%">&nbsp;</td>
						  <td width="40%">&nbsp;</td>
						  <td width="6%">&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Del&nbsp;la&nbsp;factura&nbsp;n&deg;</td>
						  <td><?php echo $minimo_dol?>	</td>
						  <td>al&nbsp;n&deg;</td>
						  <td><?php echo $maximo_dol?></td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Neto</td>
						  <td><?php echo number_format($neto_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>22%&nbsp;IVA</td>
						  <td><?php echo number_format($iva_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total</td>
						  <td><?php echo number_format($total_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total&nbsp;contado</td>
						  <td><?php echo number_format($contado_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total&nbsp;tarjetas</td>
						  <td><?php echo number_format($tarjeta_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					  <tr>
						  <td>Total</td>
						  <td><?php echo number_format($total_dol,2,",",".")?></td>
						  <td></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table>					  
					  </td>
					  </tr>
					</table>	
			  </div>

			</div>	
		</div>
	</body>
</html>
