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
function eliminar_linea(codrecibopago,codrecibo)
{
	if (confirm(" Desea eliminar esta linea ? "))
		document.getElementById("frame_datos").src="eliminar_lineapago.php?codrecibopago="+codrecibopago+"&codrecibo=" + codrecibo;
}
</script>
</head>
<?php 
include ("../conectar.php");
include ("../funciones/fechas.php"); 

$Cmoneda=@$_POST['Cmoneda'];
$codrecibo=@$_POST["bcodrecibo"];
$retorno=0;
$modif=@$_POST["modifpago"];
if ($modif<1) {
		if (!isset($codrecibo)) { 
			$codrecibo=$_GET["codrecibo"]; 
			$retorno=1; }
		if ($retorno==0) {	
			$codrecibo=$_POST["bcodrecibo"];
			$tipo=$_POST["Atipo"];
			$fechapago=explota($_POST["fechapago"]);
			$numeroserie=$_POST["anumeroserie"];
			$numero=$_POST["anumero"];
			$codentidad=$_POST["cboBanco"];
			$monedapago=$_POST["Amonedapago"];
			$tipocambio=$_POST["tipocambio"];
			
			$importedoc=$_POST["Rimportedoc"];
	if($Cmoneda==2 and $monedapago==1) {
		$importe=$importedoc/$tipocambio;
	} elseif($Cmoneda==1 and $monedapago==2) {
		$importe=$importedoc*$tipocambio;
	} else {
		$importe=$importedoc;
		$tipocambio=1;
	}			
			$observaciones=$_POST["observaciones"];
			
		$sel_tmp="INSERT INTO recibospagotmp (codrecibopago,codrecibo,tipo,codentidad,numeroserie,numero,monedapago,tipocambio,importedoc,importe,fechapago,observaciones) 
				VALUES (NULL,'$codrecibo', '$tipo', '$codentidad', '$numeroserie', '$numero', '$monedapago', '$tipocambio', '$importedoc', '$importe', '$fechapago', '$observaciones')";
			$res_tmp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_tmp);
		}
}
?>
<body>
<div id="pagina">
	<div id="zonaContenido">
	<div align="center">
	<div class="header" style="width:100%;position: fixed;">PAGOS EN EL RECIBO <?php echo $codrecibo;?> </div>
	<div class="fixed-table-container">
		<div class="header-background cabeceraTabla"> </div>      			
	<div class="fixed-table-container-inner">			
	
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0>
			<thead>			
				<tr class="cabeceraTabla">
					<th><div class="th-inner">TIPO</div></th>
					<th><div class="th-inner">BANCO</div></th>
					<th><div class="th-inner">SERIE</div></th>
					<th><div class="th-inner">Nº</div></th>
					<th><div class="th-inner">MON.</div> </th>							
					<th width="75px"><div class="th-inner">IMPORTE</div></th>
					<th><div class="th-inner">T/C</div> </th>							
					<th width="75px"><div class="th-inner">TOTAL</div></th>
				<?php if ($modif!=2){ ?>
					<th width="20px" ><div class="th-inner">&nbsp;</div></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
<?php
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");
$tipopago= array( 0=>"Seleccione uno", 1=>"Contado", 2=>"Cheque",3=>"Giro Bancario", 4=>"Giro RED cobranza", 5=>"Resguardo");
//array( 1=>"Contado", 2=>"Cheque", 3=>"Resguardo");
$total_importe=0;
$sel_lineas="SELECT * FROM recibospagotmp WHERE codrecibo='$codrecibo' ORDER BY codrecibopago ASC";

$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);

for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$codrecibopago=mysqli_result($rs_lineas, $i, "codrecibopago");
	$tipo=mysqli_result($rs_lineas, $i, "tipo");
	$codentidad=mysqli_result($rs_lineas, $i, "codentidad");
	$numeroserie=mysqli_result($rs_lineas, $i, "numeroserie");
	$numero=mysqli_result($rs_lineas, $i, "numero");
	$monedapago=mysqli_result($rs_lineas, $i, "monedapago");
	$tipocambio=mysqli_result($rs_lineas, $i, "tipocambio");
	$importedoc=mysqli_result($rs_lineas, $i, "importedoc");
	if($Cmoneda==2 and $monedapago==1 and mysqli_result($rs_lineas, $i, "tipocambio")>0) {
		$total=mysqli_result($rs_lineas, $i, "importedoc")/mysqli_result($rs_lineas, $i, "tipocambio");
	} elseif($Cmoneda==1 and $monedapago==2 and mysqli_result($rs_lineas, $i, "tipocambio")>0) {
		$total=mysqli_result($rs_lineas, $i, "importedoc")*mysqli_result($rs_lineas, $i, "tipocambio");
	} else {
		$total=mysqli_result($rs_lineas, $i, "importedoc");
		$tipocambio=1;
	}
	$total_importe=$total_importe+$total;
	
	$fechapago=mysqli_result($rs_lineas, $i, "fechapago");
	$observaciones=mysqli_result($rs_lineas, $i, "observaciones");

	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">

				<th class="aIzquierda"><?php echo $tipopago[$tipo];?></th>
				<th width="105px" class="aIzquierda"><?php
				if ($codentidad!=0) {
						$query_entidades="SELECT * FROM entidades WHERE codentidad=".$codentidad;
						$res_entidades=mysqli_query($GLOBALS["___mysqli_ston"], $query_entidades);
				 echo mysqli_result($res_entidades, 0, "nombreentidad");
				 } else {
				 	echo "&nbsp;&nbsp;";
				 }?></th>
				<th width="50px" class="aIzquierda"><?php if ($numeroserie!=0) { echo $numeroserie;} else { echo "&nbsp;&nbsp;";}?></th>
				<th width="105px" class="aIzquierda"><?php if ($numero!=0) { echo $numero;} else { echo "&nbsp;&nbsp;";}?></th>
				<th width="60px" class="aIzquierda"><?php echo $tipomon[$monedapago];?></th>
				<th width="95px" class="aCentro cajaTotales" ><?php echo number_format($importedoc,2,",",".");?>&nbsp;</th>
				<th width="95px" class="aCentro cajaTotales" ><?php echo number_format($tipocambio,3,",",".");?>&nbsp;</th>
				<th width="95px" class="aCentro cajaTotales" ><?php echo number_format($total,2,",",".");?>&nbsp;</th>
				<?php if ($modif!=2){ ?>
				<th width="20px" ><a href="javascript:eliminar_linea(<?php echo $codrecibopago;?>,<?php echo $codrecibo;?>)">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></th>
				<?php } ?>
				
			</tr>
<?php }
 ?>
</table>
<script type="text/javascript">
parent.pon_importe(<?php echo $total_importe;?>);
</script>
</div></div></div></div></div>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>