<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(nombre,nif){
	parent.pon_prefijo_verifica(nombre,nif);
	parent.$('idOfDomElement').colorbox.close();
}

function limpiar() {
	parent.limpiar();
	parent.$('idOfDomElement').colorbox.close();	
}


</script>
<?php include ("../conectar.php"); ?>
<body>
<?php
	$codproveedor=$_GET["codproveedor"];
	$consulta="SELECT * FROM proveedores WHERE codproveedor='$codproveedor' AND borrado=0";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	if (mysqli_num_rows($rs_tabla)>0) {
	$nombre= mysqli_result($rs_tabla, 0, 'nombre');
	$nif = mysqli_result($rs_tabla, 0, 'nif');
		?>
		<script languaje="javascript">
		pon_prefijo('<?php echo $nombre?>','<?php echo $nif?>');
		</script>
		<?php 
	} else { ?>
	<script>
	alert ("No existe ningun proveedor con ese codigo");
	limpiar();
	
	</script>
	<?php }
?>
</div>
</body>
</html>
