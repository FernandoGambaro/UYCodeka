<html>
<title></title>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
<script>
function eliminar_linea(codpresupuestotmp,numlinea,importe)
{
	if (confirm(" Desea eliminar esta linea ? ")) {
		parent.document.formulario_lineas.baseimponible.value=parseFloat(parent.document.formulario_lineas.baseimponible.value) - parseFloat(importe);
		var original=parseFloat(parent.document.formulario_lineas.baseimponible.value);		
		var result=Math.round(original*100)/100 ;
		parent.document.formulario_lineas.baseimponible.value=result;

		parent.document.formulario_lineas.baseimpuestos.value=parseFloat(result * parseFloat(parent.document.formulario.iva.value / 100));
		var original1=parseFloat(parent.document.formulario_lineas.baseimpuestos.value);
		var result1=Math.round(original1*100)/100 ;
		parent.document.formulario_lineas.baseimpuestos.value=result1;
		var original2=parseFloat(result + result1);
		var result2=Math.round(original2*100)/100 ;
		parent.document.formulario_lineas.preciototal.value=result2;
		document.getElementById("frame_datos").src="eliminar_linea.php?codpresupuestotmp="+codpresupuestotmp+"&numlinea=" + numlinea;
		} 
}

</script>
<?php 
include ("../conectar.php");
$total_importe='';
$codpresupuestotmp=$_POST["codpresupuestotmp"];
$retorno=0;
$archivos='';
$modif=$_POST["modif"];
if ($modif!=1) {
		if (!isset($codpresupuestotmp)) { 
			$codpresupuestotmp=$_GET["codpresupuestotmp"]; 
			$retorno=1; }
		if ($retorno==0) {	
				$codfamilia=$_POST["codfamilia"];
				$codarticulo=$_POST["codarticulo"];
				$cantidad=$_POST["cantidad"];
				$moneda=$_POST["moneda"];
				$precio_compra=$_POST["precio_compra"];
				$precio=$_POST["precio"];
				$importe=$_POST["importe"];
				$descuento=$_POST["descuento"];
				$detalles=$_POST["detalles"];
				$codsector=$_POST['codsector'];

$archivos='';
$filen='';
if (!empty($_FILES['item_file'])) {
    for ($i = 0; $i < count($_FILES['item_file']['name']); $i++) {
        if (empty($_FILES['item_file']['name'][$i])) {
            continue;
        }
 //check if any file uploaded
		for($j=0; $j < count($_FILES["item_file"]['name']); $j++) { //loop the uploaded file array
			$filen = $_FILES["item_file"]['name'][$j];	
			// ingore empty input fields
			if ($filen!="")
			{
				// destination path - you can choose any file name here (e.g. random)
				$path = "../tmp/" . $filen; 
				if(move_uploaded_file($_FILES["item_file"]['tmp_name']["$j"],$path)) {
					if ($j==0){ 
						$archivos=$filen;
					} else {
						$archivos.="*".$filen;
					}
				}
			}	
		}
    }
}

				$sel_insert="INSERT INTO presulineatmp (codpresupuesto,numlinea,codigo,codfamilia,detalles,cantidad,moneda,precio_compra,precio,importe,dcto, archivos, codsector)
				VALUES ('$codpresupuestotmp','','$codarticulo','$codfamilia','$detalles','$cantidad','$moneda','$precio_compra','$precio','$importe','$descuento','$archivos', '$codsector')";
				$rs_insert=mysqli_query($GLOBALS["___mysqli_ston"], $sel_insert);
		}
}
?>
</head>
<body style="margin: 0px; padding: 0px;">

<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 ID="Table1">

<?php
 $sel_lineas="SELECT presulineatmp.*,articulos.*,familias.nombre as nombrefamilia FROM presulineatmp,articulos,familias WHERE presulineatmp.codpresupuesto='$codpresupuestotmp'
 AND presulineatmp.codigo=articulos.codarticulo AND presulineatmp.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY presulineatmp.numlinea ASC";
$tipomon = array( 0=>"&nbsp;", 1=>"Pesos", 2=>"U\$S");

$file='';
$total_costo=0;

$rs_lineas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_lineas);
for ($i = 0; $i < mysqli_num_rows($rs_lineas); $i++) {
	$numlinea=mysqli_result($rs_lineas, $i, "numlinea");
	$codfamilia=mysqli_result($rs_lineas, $i, "codfamilia");
	$detalles=mysqli_result($rs_lineas, $i, "detalles");
	$nombrefamilia=mysqli_result($rs_lineas, $i, "nombrefamilia");
	$codarticulo=mysqli_result($rs_lineas, $i, "codarticulo");
	$descripcion=mysqli_result($rs_lineas, $i, "descripcion");
	$archivos=mysqli_result($rs_lineas, $i, "archivos");
	if($archivos!='') {
		$file=explode('*', $archivos);
	} else {
		$file='';
	}
	$cantidad=mysqli_result($rs_lineas, $i, "cantidad");
	$referencia=mysqli_result($rs_lineas, $i, "referencia");
	$precio_compra=mysqli_result($rs_lineas, $i, "precio_compra");
	$precio=mysqli_result($rs_lineas, $i, "precio");
	$moneda=mysqli_result($rs_lineas, $i, "moneda");
	$importe=mysqli_result($rs_lineas, $i, "importe");
	$total_importe=$total_importe+$importe;
	$total_costo=$total_costo+$precio_compra*$cantidad;
	$descuento=mysqli_result($rs_lineas, $i, "dcto");
	$codsector=mysqli_result($rs_lineas, $i, "codsector");

			$sel_resultado="SELECT * FROM sector where codsector='".$codsector."'";
		   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   if ($contador < mysqli_num_rows($res_resultado)) {
		   	$color='style="background-color:#' . mysqli_result($res_resultado, $contador, "color").'"';
		   } else {
		   	$color='';
		   }

	
	if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
			<tr class="<?php echo $fondolinea?>">
				<td width="3%" <?php echo $color;?> >&nbsp;</td>
				<td width="8%" class="aCentro"><?php echo $referencia?></td>
				<td width="30%" class="aIzquierda"><?php echo $descripcion; if ($detalles!="") echo " - ".$detalles;?></td>
				<td width="8%" class="aIzquierda">				
				<?php
					$tmp='';
					$nombre='';
					if(count($file)>0 and $file!='') {
					for($x=0;$x < count($file); $x++) {
						$tmp=$file[$x];
						$nombre=explode('.',$tmp);
						echo '<img id="botonBusqueda" src="../img/'.$nombre[1].'.png"  width="16" height="16" alt="'.$nombre[0].'" title="'.$nombre[0].".".$nombre[1].'">';
						$nombre='';
					}
					
					} else {
						echo "&nbsp;";
					}
				?>
				</td>
				<td width="8%" class="aCentro"><?php echo $cantidad;?></td>
				<td width="8%" class="aCentro"><?php echo $precio_compra;?></td>
				<td width="8%" class="aCentro"><?php echo $precio;?></td>
				<td width="7%" class="aCentro"><?php echo $descuento;?></td>
				<td width="7%" class="aCentro"><?php echo $tipomon[$moneda];?></td>
				<td width="8%" class="aCentro"><?php echo $importe;?></td>
				<td width="8%" class="aCentro"><?php echo $importe-($precio_compra*$cantidad);?></td>
				<td width="40px" class="aCentro"><a href="javascript:eliminar_linea(<?php echo $codpresupuestotmp?>,<?php echo $numlinea?>,<?php echo $importe ?>);">
				<img id="botonBusqueda" src="../img/eliminar.png" border="0"></a></td>
			</tr>
<?php }

 ?>
</table>
<script type="text/javascript">
parent.pon_costo(<?php echo $total_costo;?>,<?php echo $total_importe;?>);
//parent.pon_baseimponible(<?php echo $total_importe;?>);
</script>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
</body>
</html>
