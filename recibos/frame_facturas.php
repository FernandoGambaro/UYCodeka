<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="../js3/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

});
</script>
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
function agregar_factura(codfactura,moneda, codcliente, nombre, nif, totalfactura)
{
	   	var estado = 4;
		$.post('pagoparcial.php', { codfactura: codfactura, estado:estado }, function(data){
        	});
document.getElementById(codfactura).style.display = 'none';	
parent.agregar_factura(codfactura,moneda, codcliente, nombre,nif, totalfactura);

}
</script>
</head>
<?php 
ini_set('memory_limit','512M');
ini_set('display_errors',true);
error_reporting(-1);

include ("../conectar.php");
include ("../funciones/fechas.php");

			$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			$moneda1=mysqli_result($res_resultado,0, "simbolo");
			$moneda2=mysqli_result($res_resultado, 1, "simbolo");

$codcliente=$_POST["cdocliente"];
$moneda=$_POST["moneda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND facturas.codcliente='$codcliente'"; }
if ($moneda <> "") { $where.=" AND facturas.moneda='$moneda'"; }
?>
<body>
<div id="pagina">
	<div id="zonaContenido">
	<div align="center">
	<div class="header" style="width:100%;position: fixed;">FACTURAS PENDIENTES DE COBRO</div>
	<div class="fixed-table-container">
		<div class="header-background cabeceraTabla"> </div>      			
	<div class="fixed-table-container-inner">			
	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0>
			<thead>			
				<tr class="cabeceraTabla">
					<th><div class="th-inner">&nbsp;</div></th>
					<th><div class="th-inner">Nº</div></th>
					<th><div class="th-inner">FECHA</div></th>
					<th><div class="th-inner">MON.&nbsp;</div></th>
					<th><div class="th-inner">&nbsp;TOTAL</div> </th>							
					<th><div class="th-inner">&nbsp;SALDO</div> </th>							
					<th ><div class="th-inner">&nbsp;</div></th>
				</tr>
			</thead>
			<tbody>

<?php
	$moneda = array(1=>$moneda1, 2=>$moneda2);


	$sel_facturas="SELECT clientes.codcliente as cliente, clientes.nombre, clientes.apellido, clientes.empresa, clientes.nif,codfactura,facturas.fecha,facturas.tipo,facturas.moneda,facturas.estado,
	facturas.totalfactura FROM facturas INNER JOIN clientes on clientes.codcliente=facturas.codcliente WHERE facturas.borrado=0 AND facturas.totalfactura>=1 
	AND facturas.tipo=1 AND (facturas.estado<=1 or facturas.estado=4) AND ".$where;
$rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas);

for ($i = 0; $i < mysqli_num_rows($rs_facturas); $i++) {
$checked='';	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>" id="<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>">
				<?php
					if(mysqli_result($rs_facturas, $i, "estado")=='3' or mysqli_result($rs_facturas, $i, "estado")=='4'){
					 $checked="checked='checked'";
					 $sql_pago="SELECT pago FROM recibosfactura WHERE codfactura= '". mysqli_result($rs_facturas, $i, "codfactura") . "'";
					 $res_pago=mysqli_query($GLOBALS["___mysqli_ston"], $sql_pago);
					 $con_pago=0;
					 $tot_pago=0;
					  while ($con_pago < mysqli_num_rows($res_pago)) {
					  	$tot_pago+=mysqli_result($res_pago, $con_pago, "pago");
					  	$con_pago++;
					  	}
					  	$saldo=mysqli_result($rs_facturas, $i, "totalfactura")-$tot_pago;
					} else {
					  	$saldo=mysqli_result($rs_facturas, $i, "totalfactura");
					}
	

					 ?>
				<th class="aCentro">
				<label><input type="checkbox"  disabled readonly  <?php echo $checked;?> style=" margin-top: 2px;">
				<span></span></label></th>	
				<?php  ?>			
				<th class="aCentro"><?php echo mysqli_result($rs_facturas, $i, "codfactura");?></th>
				<th class="aCentro"><?php echo implota(mysqli_result($rs_facturas, $i, "fecha"));?></th>
				<th class="aCentro"><div align="center"><?php echo $moneda[mysqli_result($rs_facturas, $i, "moneda")];?></div>&nbsp;</th>
				<th class="aCentro">&nbsp;<?php echo mysqli_result($rs_facturas, $i, "totalfactura");?></th>
				<th class="aCentro"><?php echo $saldo; ?></th>
				<th width="20px" ><div style=" background: url(../img/blank.png) no-repeat; ">
				<a href="javascript:agregar_factura('<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>',
				'<?php echo mysqli_result($rs_facturas, $i, "moneda");?>',
				'<?php echo mysqli_result($rs_facturas, $i, "cliente");?>',
				'<?php echo mysqli_result($rs_facturas, $i, "nombre")." ".mysqli_result($rs_facturas, $i, "apellido")." ".mysqli_result($rs_facturas, $i, "empresa");?>',
				'<?php echo mysqli_result($rs_facturas, $i, "nif");?>',
				'<?php echo mysqli_result($rs_facturas, $i, "totalfactura");?>')">
				<img id="botonBusqueda" src="../img/convertir.png" border="0"></a></div></th>
			</tr>
<?php }
 ?>
</table>
</div></div></div></div></div>

</body>
</html>