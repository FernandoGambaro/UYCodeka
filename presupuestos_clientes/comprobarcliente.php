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
	parent.pon_prefijo(pref,nombre,nif);
	parent.$('idOfDomElement').colorbox.close();
}
</script>
		
<?php include ("../conectar.php"); ?>
<body>
<?php
	$codcliente=$_GET["codcliente"];
	$consulta="SELECT * FROM clientes WHERE codcliente='$codcliente' AND borrado=0";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
?>

		<?php if (mysqli_num_rows($rs_tabla) > 0) { 
				$codcliente=mysqli_result($rs_tabla, $i, "codcliente");
				$nombre=mysqli_result($rs_tabla, $i, "nombre")." ".mysqli_result($rs_tabla, $i, "apellido")." - ".mysqli_result($rs_tabla, $i, "empresa");
				$nif=mysqli_result($rs_tabla, $i, "nif"); ?>


<script language="javascript">
pon_prefijo('<?php echo $codcliente;?>','<?php echo $nombre;?>','<?php echo $nif;?>');

</script>
										

<?php
} else {
?>
<br>
<div class="header">
No Existe ese código de cliente
</span>
<script>setTimeout(function() {parent.$('#aCliente').focus(); parent.$('idOfDomElement').colorbox.close();}, 1000);</script>
<?php
}
?>
</body>
</html>