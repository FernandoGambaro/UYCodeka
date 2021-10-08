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

<script>

function pon_prefijo(el_id,codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,codservice,detalles,comision,cantidad,codfactura) {

   var row = document.getElementById(el_id);
	if (row.style.display == '')  row.style.display = 'none';
	else row.style.display = '';           
	parent.pon_prefijo_Fb(codfamilia,referencia,codigobarras,nombre,precio,codarticulo,moneda,'',detalles,comision,cantidad,codfactura);

}
</script>
</head>
<?php 
include ("../conectar.php");
$total_importe='';
$codfacturatmp=@$_POST["codfacturatmp"];
$retorno=0;

if (!isset($codfacturatmp)) { 
	$codfacturatmp=$_GET["codfacturatmp"]; 
	$retorno=1; }
if ($retorno==0 and ($codfacturatmp!=0 or $codfacturatmp!='')) {	

	$sel_lineas="SELECT * FROM factulinea WHERE codfactura='$codfacturatmp' ORDER BY numlinea ASC";
	$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
	
	$sel_borrar = "DELETE FROM factulineatmp WHERE codfactura='$codfacturatmp'";
	$rs_borrar = mysqli_query($GLOBALS["___mysqli_ston"], $sel_borrar);
	
	
	$contador=0;
	//echo mysql_num_rows($rs_lineas);
	while ($contador < mysqli_num_rows($rs_lineas)) {
		$codfamilia=mysqli_result($rs_lineas, $contador, "codfamilia");
		$codigo=mysqli_result($rs_lineas, $contador, "codigo");
		$codservice=mysqli_result($rs_lineas, $contador, "codservice");
		$cantidad=mysqli_result($rs_lineas, $contador, "cantidad");
		$detallestmp=mysqli_result($rs_lineas, $contador, "detalles");
		$precio=mysqli_result($rs_lineas, $contador, "precio");
		$importe=mysqli_result($rs_lineas, $contador, "importe");
		$moneda=mysqli_result($rs_lineas, $contador, "moneda");
		$dcto=mysqli_result($rs_lineas, $contador, "dcto");
	
		$descuentopp=mysqli_result($rs_lineas, $contador, "dctopp");
		$comision=mysqli_result($rs_lineas, $contador, "comision");
		
		$sel_tmp="INSERT INTO factulineatmp (codfactura,numlinea,codfamilia,codigo,codservice,detalles,cantidad,moneda,precio,importe,dcto,dctopp,comision) 
					VALUES ('$codfacturatmp','','$codfamilia','$codigo','$codservice','$detallestmp','$cantidad','$moneda','$precio','$importe','$dcto','$descuentopp','$comision')";	
		
		$rs_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
		$contador++;
	}
}

?>
<body>
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">

			<div class="header" style="width:100%;position: fixed;">	DETALLES DE LA FACTURA </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 id="Table1">
					<thead>
					  <tr class="cabeceraTabla">	
							<th width="14%" align="left"><div class="th-inner">Nº FACTURA</div></th>
							<th width="14%" align="left"><div class="th-inner">REFERENCIA</div></th>
							<th width="46%" align="left"><div class="th-inner">DESCRIPCION/DETALLES</div> </th>
							<th width="8%" align="left"><div class="th-inner">CANT.</div></th>
							<th width="8%" align="left"><div class="th-inner">P.</div></th>
							<th width="5%" align="left"><div class="th-inner">MON.</div></th>
							<th width="5%" align="left"><div class="th-inner">Des.</div></th>
							<th width="5%" align="left"><div class="th-inner">DPP.</div></th>
							<th width="5%" align="left"><div class="th-inner">Com.</div></th>
							<th width="105px" align="left"><div class="th-inner">IMP.</div></th>
							<th width="20px"><div class="th-inner"></div></th>
						</tr>
					</thead>
					<tbody>
<?php
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
$total_importe='';
$sel_lineas="SELECT factulineatmp.*, factulineatmp.comision as comi,articulos.*,familias.nombre as nombrefamilia FROM factulineatmp,articulos,familias 
WHERE factulineatmp.codfactura='$codfacturatmp' AND factulineatmp.codigo=articulos.codarticulo AND factulineatmp.codfamilia=articulos.codfamilia 
AND articulos.codfamilia=familias.codfamilia ORDER BY factulineatmp.numlinea ASC";
$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$codigo=mysqli_result($rs_lineas, $i, "codigo");
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
	$comision=mysqli_result($rs_lineas, $i, "comi");
	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>" id="m<?php echo $i;?>" >
				<?php if (trim($detalles)==''){ ?>
				<td width="14%">&nbsp;<?php echo $codfacturatmp;?></td>
				<td width="14%">&nbsp;<?php echo $referencia;?></td>
				<td width="46%"><?php echo $descripcion;?></td>
				<?php } else { ?>
				<td width="14%">&nbsp;<?php echo $codfacturatmp;?></td>
				<td width="14%">&nbsp;<?php echo $referencia;?></td>
				<td width="46%"><?php echo $detalles;?></td>
				<?php } ?>
				<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="8%" class="aCentro"><?php echo $precio;?></td>
				<td width="5%" class="aCentro"><?php echo $moneda;?></td>
				<td width="5%" class="aCentro"><?php echo $descuento;?></td>
				<td width="5%" class="aCentro"><?php echo $descuentopp;?></td>
				<td width="5%" class="aCentro"><?php echo $comision;?></td>
				<td width="105px" class="aCentro cajaTotales" ><?php echo $importe;?>&nbsp;</td>
				<td align="center"><div align="center">
				<a href="javascript:pon_prefijo('m<?php echo $i;?>',
				'<?php echo $codfamilia;?>',
				'<?php echo $referencia;?>',
				'<?php echo $codigo;?>',
				'<?php echo addslashes(str_replace('"','&quot;',$descripcion));?>',
				'<?php echo $precio;?>',
				'<?php echo $codarticulo;?>',
				'<?php echo mysqli_result($rs_lineas, $i, "moneda");?>',
				'',
				'<?php echo addslashes(str_replace('"','&quot;',$descripcion));?>',
				'<?php echo $comision;?>',
				'<?php echo $cantidad;?>',
				'<?php echo $codfacturatmp;?>');">
				<img id="botonBusqueda" src="../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
			</tr>
			
<?php 
}
 ?>
 </tbody>
</table>
</div></div></div></div></div>

<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>
<?php
//mysql_close($descriptor);
?>