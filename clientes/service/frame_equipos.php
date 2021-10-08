<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
	$e=$_POST['e'];

?>
<html>
<head>
<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
</head>


<script language="javascript">
function pon_prefijo(pref,descripcion,service) {
parent.pon_prefijo(pref,descripcion,service);
}
</script>
<?php include ("../../conectar.php");
include ("../../funciones/fechas.php"); 
 ?>
<body>
<?php
$where="1=1";
if ($e <> "") { $where.=" AND codcliente='$e'"; }

$where.=" ORDER BY fecha DESC";

	$consulta="SELECT * FROM equipos WHERE borrado=0 AND ".$where;
	$res_resultado=$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$nrs=mysqli_num_rows($rs_tabla);
?>
<div id="tituloForm2" class="header">
<div align="center">
<form id="form1" name="form1">
<?php if ($nrs>0)
 { 
$tipo = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");
$contador=0;?>
		<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
		  <tr>
			<td width="8%">Nº</td>
			<td width="8%" align="left">FECHA</td>
			<td width="16%" align="left">ALIAS</td>
			<td width="56%" align="left">EQUIPO</td>
			<td width="10%" align="left">&nbsp;Nº</td>
			<td width="19%" align="left">&nbsp;SERVICE</td>
			<td><div align="center"></td>
		  </tr>
		<?php
		   while ($contador < mysqli_num_rows($res_resultado)) { 
				 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
							<td class="aCentro" width="8%"><?php echo $contador+1;?></td>
							<td width="8%"><div align="left"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td width="16%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "alias");?></div></td>
							<td width="56%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "descripcion");?></div></td>
							<td align="left" width="10%"><div ><?php echo mysqli_result($res_resultado, $contador, "numero");?></div></td>
							<td align="left" width="19%"><div>
							<?php
echo $tipo[mysqli_result($res_resultado, $contador, "service")];
$service=$tipo[mysqli_result($res_resultado, $contador, "service")];
$codequipo=mysqli_result($res_resultado, $contador, "codequipo");
$descripcion=mysqli_result($res_resultado, $contador, "alias")." - ".mysqli_result($res_resultado, $contador, "descripcion");
?>
							</div></td>
					<td align="center"><div align="center"><a href="javascript:pon_prefijo(<?php echo $codequipo;?>,'<?php echo $descripcion;?>','<?php echo $service;?>')">
					<img id="botonBusqueda" src="../../img/convertir.png" border="0" title="Seleccionar"></a></div></td>					
				</tr>
			<?php 
			$contador++;
			}
		?>
  </table>
		<?php 
		}  ?>

<input type="hidden" id="accion" name="accion">
</form>
</div>
</div>
</body>
</html>
