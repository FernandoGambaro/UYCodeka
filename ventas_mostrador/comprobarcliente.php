<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">


function pon_prefijo(pref,nombre,nif) {
	parent.pon_prefijo_b(pref,nombre,nif);
}

function limpiar() {
	parent.pon_prefijo_b('','','');
}

</script>
<?php include ("../conectar.php"); ?>
<body>
<?php
	$codcliente=$_GET["codcliente"];
	$consulta="SELECT * FROM clientes WHERE codcliente='$codcliente' AND borrado=0";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	if (mysqli_num_rows($rs_tabla)>0) {
		?>
		<script languaje="javascript">
		pon_prefijo("<?php echo $codcliente; ?>","<?php echo mysqli_result($rs_tabla, 0, empresa). ' - '.mysqli_result($rs_tabla, 0, nombre).' '.mysqli_result($rs_tabla, 0, apellido); ?>","<?php echo mysqli_result($rs_tabla, 0, nif); ?>");
		parent.$('idOfDomElement').colorbox.close();
		</script>
		<?php 
	} else { ?>
	<script>
	alert ("No existe ningun cliente con ese codigo");
	limpiar();
	parent.$('idOfDomElement').colorbox.close();
	</script>
	<?php }
?>
</div>
</body>
</html>
