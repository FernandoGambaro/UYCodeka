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
function agregar_factura(codfactura,moneda)
{
//document.getElementById(codfactura).style.display = 'none';	
parent.agregar_factura(codfactura,moneda);

}
</script>
</head>
<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");
$nombre='';

$codcliente=$_POST["cdocliente"];
$moneda=$_POST["cmoneda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
if ($moneda <> "") { $where.=" AND facturas.moneda='$moneda'"; }

if ($codcliente <> "") {
$sql_cliente="SELECT nombre, apellido FROM clientes WHERE codcliente='$codcliente'";
$rs_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sql_cliente);
$nombre= mysqli_result($rs_cliente, 0, "nombre")."  ". mysqli_result($rs_cliente, 0, "apellido");
}
?>
<body>
<div id="pagina">
	<div id="zonaContenido">
	<div align="center">
	<div class="header" style="width:100%;position: fixed;">FACTURAS DEL CLIENTE <?php echo $nombre;?></div>
	<div class="fixed-table-container">
		<div class="header-background cabeceraTabla"> </div>      			
	<div class="fixed-table-container-inner">			
	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0>
			<thead>			
				<tr class="cabeceraTabla">
					<th><div class="th-inner">Nº</div></th>
					<th><div class="th-inner">FECHA</div></th>
					<th><div class="th-inner">MON.&nbsp;</div></th>
					<th><div class="th-inner">&nbsp;TOTAL</div> </th>							
					<th ><div class="th-inner">&nbsp;</div></th>
				</tr>
			</thead>
			<tbody>




<?php
	$moneda = array(1=>"\$", 2=>"U\$S");
	$sel_facturas="SELECT codcliente,codfactura,fecha,tipo,moneda,estado,totalfactura FROM facturas WHERE borrado=0 AND tipo=1 AND ".$where;
$rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas);

for ($i = 0; $i < mysqli_num_rows($rs_facturas); $i++) {
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>" id="<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>">
			
				<th class="aCentro"><?php echo mysqli_result($rs_facturas, $i, "codfactura");?></th>
				<th class="aCentro"><?php echo implota(mysqli_result($rs_facturas, $i, "fecha"));?></th>
				<th class="aCentro"><div align="center"><?php echo $moneda[mysqli_result($rs_facturas, $i, "moneda")];?></div></th>
				<th class="aCentro cajaTotales"><?php echo mysqli_result($rs_facturas, $i, "totalfactura");?></th>
				<?php if ($codcliente <> "") { ?>
				<th width="20px" ><div style=" background: url(../img/blank.png) no-repeat; ">
				<a href="javascript:agregar_factura(<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>,<?php echo mysqli_result($rs_facturas, $i, "moneda");?>)">
				<img id="botonBusqueda" src="../img/convertir.png" border="0"></a></div></th>
				<?php } else { ?>
				<th width="20px" ><div style="width:16px; height:16px; background: url(../img/blank.png) no-repeat; "><a href="#">
				</a></div></th>
				<?php } ?>
				
			
			</tr>
<?php }
 ?>
</table>
</div></div></div></div></div>

</body>
</html>