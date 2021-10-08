<?php
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 

$nombre=$_POST["nombre"];
$where="1=1";

if ($nombre<>'') { $where.=" AND (nombre like '%$nombre%'or apellido like '%$nombre%' or empresa like '%$nombre%')"; }


?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(pref,nombre,nif) {
	parent.pon_prefijo(pref,nombre,nif);
}


</script>
<?php include ("../conectar.php"); ?>
<body>
<?php
	
	$consulta="SELECT * FROM clientes WHERE ".$where." AND borrado=0 ORDER BY codcliente ASC";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$nrs=mysqli_num_rows($rs_tabla);
?>
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			
			<div class="header" style="width:100%;position: fixed;">	BÚSQUEDA de CLIENTES  </div>

<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">
<form id="form1" name="form1">

<?php if ($nrs>0) { ?>
		<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
		<thead>
		  <tr>
			<th width="10%"><div class="th-inner"><b>Codigo</b></div></th>
			<th width="60%"><div class="th-inner"><b>Cliente</b></div></th>
			<th width="20%"><div class="th-inner"><b>NIF/CIF</b></div></th>
			<th width="10%"><div class="th-inner"></th>
		  </tr>
		</thead>
		<?php
			for ($i = 0; $i < mysqli_num_rows($rs_tabla); $i++) {
				$codcliente=mysqli_result($rs_tabla, $i, "codcliente");
				$nombre=mysqli_result($rs_tabla, $i, "nombre");
				$nif=mysqli_result($rs_tabla, $i, "nif");
				$nombre=mysqli_result($rs_tabla, $i, "nombre");
				$apellido=mysqli_result($rs_tabla, $i, "apellido");
				$empresa=mysqli_result($rs_tabla, $i, "empresa");
				 if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
					<td>
        <div align="center"><?php echo $codcliente;?></div></td>
				<?php if (!empty($empresa)) {?>
				<td><div align="left"><?php echo $empresa; 
				$nombre=$empresa; ?></div></td>
				<?php } elseif (empty($apellido)) {?>
				<td><div align="left"><?php echo $nombre;?></div></td>
				<?php } else { ?>
				<td><div align="left"><?php echo $nombre;?>
				 <?php echo $apellido; $nombre=$nombre.' '.$apellido; ?></div></td>
				<?php } ?>					
					<td><div align="center"><?php echo $nif;?></div></td>
					<td align="center"><div align="center"><a href="javascript:pon_prefijo(<?php echo $codcliente?>,'<?php echo $nombre?>','<?php echo $nif?>')">
					<img id="botonBusqueda" src="../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
				</tr>
			<?php }
		?>
  </table>
		<?php 
		}  ?>
<iframe id="frame_datos" name="frame_datos" width="0%" height="0" frameborder="0">
	<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
</iframe>
<input type="hidden" id="accion" name="accion">
</form>
</div></div></div></div></div>
</body>
</html>
