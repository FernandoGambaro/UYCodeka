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
 
?><html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}
</script>
<script>
function eliminar_linea(codfacturatmp,numlinea,importe,codservice)
{
	if (confirm(" Desea eliminar esta linea ? "))
		parent.document.formulario_lineas.baseimponible.value=parseFloat(parent.document.formulario_lineas.baseimponible.value) - parseFloat(importe);
		var original=parseFloat(parent.document.formulario_lineas.baseimponible.value);
		var result=round(original,2) ;
		parent.document.formulario_lineas.baseimponible.value=result;

		parent.document.formulario_lineas.baseimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario.iva.value / 100));
		var original1=parseFloat(parent.document.formulario_lineas.baseimpuestos.value);
		var result1=round(original1,2) ;
		parent.document.formulario_lineas.baseimpuestos.value=result1;
		var original2=parseFloat(result + result1);
		var result2=round(original2,2) ;
		parent.document.formulario_lineas.preciototal.value=result2;
		parent.cambio();
		document.getElementById("frame_datos").src="eliminar_linea.php?codfacturatmp="+codfacturatmp+"&numlinea=" + numlinea+"&codservice=" + codservice;
}
</script>
</head>
<?php 
include ("../conectar.php");
$total_importe='';
$codcliente=$_POST["codcliente"];
$moneda=$_POST["moneda"];
?>
<body>
<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 id="Table1">
<?php
	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>"\$", 2=>"U\$S");
	echo $sel_facturas="SELECT facturas.codfactura,facturas.fecha,facturas.tipo,facturas.moneda,facturas.estado FROM facturas  
						 WHERE facturas.borrado=0 AND facturas.tipo=1 AND factura.estado=1 AND facturas.codcliente='".$codcliente."'";
$rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas);
for ($i = 0; $i < mysqli_num_rows($rs_facturas); $i++) {
	$codfactura=mysqli_result($rs_facturas, $i, "codfactura");
	$tipo=mysqli_result($rs_facturas, $i, "tipo");
	$moneda=mysqli_result($rs_facturas, $i, "moneda");
	$importe=mysqli_result($rs_facturas, $i, "importe");
	$tipoc=$tipo[mysqli_result($res_resultado, $contador, "tipo")];
	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="14%"><?php echo $codfactura;?></td>
				<td width="5%"><div align="center"><?php echo $tipoc;?></div></td>
				<td width="7%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?></div></td>
				<td width="8%" class="aCentro"><?php echo $importe;?></td>

				<td width="20px" ><a href="javascript:seleccionar_factura(<?php echo $codfactura;?>)">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
			
			</tr>
<?php }
 ?>
</table>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>