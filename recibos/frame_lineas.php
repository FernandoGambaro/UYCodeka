<html>
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
$codfacturatmp=$_POST["codfacturatmp"];
$retorno=0;
$modif=@$_POST["modif"];
if ($modif!=1) {
		if (!isset($codfacturatmp)) { 
			$codfacturatmp=$_GET["codfacturatmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$detalles=$_POST["detalles"];
				$descuento=$_POST["descuento"];
				$descuentopp=$_POST["descuentopp"];
				$moneda=$_POST["moneda"];
				if ($_POST["codservice"]=='' or $_POST["codservice"]==null  ) {
				$codservice=	"'".mysqli_real_escape_string($GLOBALS["___mysqli_ston"],$_POST["codservice"])."'";
				} else {
				$codservice="NULL";
				}				
				$comision=$_POST['comision'];
				
				$sel_insert="INSERT INTO factulineatmp (codfactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe,dcto,dctopp,comision) 
				VALUES ('$codfacturatmp',null,'$codfamilia','$codarticulo',$codservice,'$detalles','$cantidad','$moneda','$precio','$importe','$descuento','$descuentopp','$comision')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);

		$sel_actualiza="UPDATE service SET factura='$codfacturatmp' WHERE codservice='$codservice'";
		$rs_actualiza = mysqli_query($GLOBALS["___mysqli_ston"], $sel_actualiza);
		}
}
?>
<body>
<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 id="Table1">
<?php
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
$total_importe='';
$sel_lineas="SELECT factulineatmp.*,articulos.*,familias.nombre as nombrefamilia FROM factulineatmp,articulos,familias 
WHERE factulineatmp.codfactura='$codfacturatmp' AND factulineatmp.codigo=articulos.codarticulo AND factulineatmp.codfamilia=articulos.codfamilia 
AND articulos.codfamilia=familias.codfamilia ORDER BY factulineatmp.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$codservice=mysqli_result($rs_lineas, $i, "codservice");
	$moneda=$tipomon[mysqli_result($rs_lineas, $i, "moneda")];
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$total_importe=$total_importe+$importe;
	$descuento=mysqli_result($rs_lineas, $i, "dcto");
	$descuentopp=mysqli_result($rs_lineas, $i, "dctopp");
	$comision=mysqli_result($rs_lineas, $i, "comision");
	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="3%"><?php echo $i+1?></td>
				<?php if (trim($detalles)==''){ ?>
				<td width="60%" colspan="2"><?php echo $descripcion;?></td>
				<?php } else { ?>
				<td width="14%"><?php echo $descripcion;?></td>
				<td width="46%"><?php echo $detalles;?></td>
				<?php } ?>
				<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="8%" class="aCentro"><?php echo $precio;?></td>
				<td width="4%" class="aCentro"><?php echo $descuento;?></td>
				<td width="4%" class="aCentro"><?php echo $descuentopp;?></td>
				<td width="5%" class="aCentro"><?php echo $moneda;?></td>
				<td width="75px" class="aCentro cajaTotales" ><?php echo $importe;?>&nbsp;</td>
				<td width="20px" ><a href="javascript:eliminar_linea(<?php echo $codfacturatmp;?>,<?php echo $numlinea;?>,<?php echo $importe; ?>,<?php echo $codservice; ?>)">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
				<td width="3%" class="aCentro"><?php echo $comision;?></td>
				
			</tr>
<?php }
 ?>
</table>
<script type="text/javascript">
parent.pon_baseimponible(<?php echo $total_importe;?>);
</script>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>