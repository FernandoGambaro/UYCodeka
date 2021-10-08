<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="../js3/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

$('.trigger').on('change', function(){ // on change of state
    	var codfactura=this.id;

   if ($(this).prop('checked')) // if changed state is "CHECKED"
   {
   	var estado = 4;
		$.post('pagoparcial.php', { codfactura: codfactura, estado:estado }, function(data){
        	});
   } else {
   	var estado=5;
		$.post('pagoparcial.php', { codfactura: codfactura, estado:estado }, function(data){
        	});
 	}   	
});

$("form:not(.filter) :input:visible:enabled:first").focus();

		
$('.trigger2').bind("change paste", function() {
   //alert($(this).val()); 
});

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
function eliminar_linea(codfactura,codrecibo,estado)
{
	if (confirm(" Desea eliminar esta linea ? "))
		document.getElementById("frame_datos").src="eliminar_lineafactura.php?codfactura="+codfactura+"&codrecibo=" + codrecibo+"&estado="+estado;
}
</script>
</head>
<?php 
include ("../conectar.php");
include ("../funciones/fechas.php");


$codrecibo=@$_POST["acodrecibo"];

$retorno=0;
$modif=@$_POST["modiffactu"];
if ($modif<1) {
		if (!isset($codrecibo)) { 
			$codrecibo=$_GET["codrecibo"]; 
			$retorno=1; }
		if ($retorno==0) {
			$codrecibo=$_POST["acodrecibo"];
			$codfactura=$_POST["codfactura"];
			if($_POST["totalfactura"]!='') { 
				$totalfactura=$_POST["totalfactura"];
			} else {
				$totalfactura=0;
			}
	 			$sel_insert="INSERT INTO recibosfacturatmp (codrecibo, codfactura, totalfactura) VALUES ('$codrecibo', '$codfactura', '$totalfactura')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);

 		$con_factura="SELECT estado FROM facturas WHERE codfactura='$codfactura'";
		$rs_con=mysqli_query($GLOBALS["___mysqli_ston"], $con_factura);
		$estado='5';	
		if(mysqli_result($rs_con, 0, "estado")=='3') {	
			$estado='3';
		}
		$act_factura="UPDATE facturas SET estado='$estado' WHERE codfactura='$codfactura'";
		$rs_act=mysqli_query($GLOBALS["___mysqli_ston"], $act_factura);	
			
		}
}
?>
<body>
<div id="pagina">
	<div id="zonaContenido">
	<div align="center">
	<div class="header" style="width:100%;position: fixed;">FACTURAS EN EL RECIBO</div>
	<div class="fixed-table-container">
		<div class="header-background cabeceraTabla"> </div>      			
	<div class="fixed-table-container-inner">			
	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
			<thead>			
					<tr class="cabeceraTabla">
					<?php if ($modif!=2){ ?>
					<th><div class="th-inner">&nbsp;</div></th>
					<?php } ?>
					<th><div class="th-inner">FECHA</div></th>
					<th><div class="th-inner">Nº</div></th>
					<th><div class="th-inner">MON.</div></th>
					<th><div class="th-inner">&nbsp;SALDO</div> </th>	
					<th><div class="th-inner">&nbsp;Paga</div> </th>	
					<?php if ($modif!=2){ ?>
					<th ><div class="th-inner">&nbsp;</div></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>

<?php
			$sel_resultado="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
			$moneda1=mysqli_result($res_resultado,0, "simbolo");
			$moneda2=mysqli_result($res_resultado, 1, "simbolo");

	$moneda = array(1=>$moneda1, 2=>$moneda2);
	$sel_facturas="SELECT recibosfacturatmp.codrecibo,recibosfacturatmp.pago,recibosfacturatmp.codfactura as codfactura, 
	facturas.fecha as fecha ,facturas.tipo as tipo,facturas.moneda,facturas.estado,facturas.totalfactura as totalfactura
	 FROM recibosfacturatmp INNER JOIN facturas ON facturas.codfactura=recibosfacturatmp.codfactura  
	 WHERE facturas.borrado=0 AND facturas.tipo=1 AND recibosfacturatmp.codrecibo=".$codrecibo;
	 $rs_facturas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_facturas);
	$cant=mysqli_num_rows($rs_facturas);
	 
	$total_importe=0;			
	$pago=0;	 
for ($i = 0; $i < mysqli_num_rows($rs_facturas); $i++) {
	@$tipoc=$tipo[mysqli_result($rs_facturas, $i, "tipo")];
		$saldo=0;
		$checked='';
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<?php if ($modif!=2){
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
		$total_importe=$total_importe+$saldo;
					
					 ?>
				<th class="aCentro">
				<label><input type="checkbox" id='<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>' name="checkbox" <?php echo $checked;?> class="trigger" style=" margin-top: 2px;">
				<span></span></label></th>	
				<?php } ?>
						
				<th><?php echo implota(mysqli_result($rs_facturas, $i, "fecha"));?></th>
				<th><?php echo mysqli_result($rs_facturas, $i, "codfactura");?></th>
				<th><div align="center"><?php echo $moneda[mysqli_result($rs_facturas, $i, "moneda")];?></div></th>
				<th class="aCentro cajaTotales" ><?php echo number_format($saldo,2,",",".");?></th>
				<th class="aCentro cajaTotales" >
				<input name="p<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>" 
				id="p<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>" 
				value="<?php echo number_format($pago,2,",",".");?>"  class="aCentro cajaTotales trigger2"></th>
				<?php if ($modif!=2){ ?>
				<th width="20px" ><a href="javascript:eliminar_linea('<?php echo mysqli_result($rs_facturas, $i, "codfactura");?>','<?php echo $codrecibo;?>','<?php echo mysqli_result($rs_facturas, $i, "estado");?>')">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></th>
				<?php } ?>
			
			</tr>
<?php }
 ?>
</table>
<script type="text/javascript">
parent.pon_apagar(<?php echo $total_importe;?>);
parent.pon_cantidad(<?php echo $cant;?>);
</script>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>