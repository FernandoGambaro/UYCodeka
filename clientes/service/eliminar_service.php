<?php
include ("../../conectar.php"); 
include ("../../funciones/fechas.php"); 

$codservice=$_GET['codservice'];

	$query_mostrar="SELECT * FROM service WHERE codservice='$codservice'";
	$rs_mostrar=mysqli_query($GLOBALS["___mysqli_ston"], $query_mostrar);
	
	$codequipo=mysqli_result($rs_mostrar, 0, "codequipo");
	$codcliente=mysqli_result($rs_mostrar, 0, "codcliente");
	$fecha=mysqli_result($rs_mostrar, 0, "fecha");
	$fecha=implota($fecha);
	$tiposervice=mysqli_result($rs_mostrar, 0, "tipo");
	$solicito=mysqli_result($rs_mostrar, 0, "solicito");
	$horas=mysqli_result($rs_mostrar, 0, "horas");
	$estado=mysqli_result($rs_mostrar, 0, "estado");
	$detalles=mysqli_result($rs_mostrar, 0, "detalles");
	$realizado=mysqli_result($rs_mostrar, 0, "realizado");
	$importe=mysqli_result($rs_mostrar, 0, "importe");

	$tipos = array("Sin&nbsp;definir", "Sin&nbsp;Servicio","Con&nbsp;Mantenimiento", "Mantenimiento&nbsp;y&nbsp;Respaldos");

	$consulta="SELECT * FROM equipos WHERE borrado=0 AND `codequipo`='".$codequipo."'";
	$rs_tabla = mysqli_query($GLOBALS["___mysqli_ston"], $consulta);
	$service=$tipos[mysqli_result($rs_tabla, 0, "service")];
	$desc=mysqli_result($rs_tabla, 0, "alias")." - ".mysqli_result($rs_tabla, 0, "descripcion");

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		
		function aceptar(codservice) {
			location.href="guardar_service.php?codservice=" + codservice + "&accion=baja";
		}
		
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">ELIMINAR SERVICE </div>
				<div id="frmBusqueda">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="100%">
				<div class="header">DATOS DEL EQUIPO </div>
				
				<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0>
				  <tr>
					<td >Equipo</td>
					<td><input name="acodequipo" type="text" class="cajaPequena" id="codequipo" size="15" maxlength="15" readonly value="<?php echo $codequipo;?>"> 
					<img id="botonBusqueda" style="float:right;" src="../../img/ver.png" width="16" height="16" onClick="ventanaEquipo()" onMouseOver="style.cursor=cursor" title="Buscar articulos">
					</td><td>Descripción</td>
					<td colspan="3"><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45" readonly value="<?php echo $desc;?>"></td>
					<td>Tipo&nbsp;Service</td>
					<td><input name="service" type="text" class="cajaGrande" id="service" size="25" readonly value="<?php echo $service;?>"></td>
				  </tr>
				  </table>
				  
				  </td></tr>
				  <tr>
					  <td valign="top" width="100%">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
					  <tr>
							<td>Fecha&nbsp;de&nbsp;service</td>
							<td><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly value="<?php echo $fecha;?>"> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fecha",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script></td>
					<td>Solicitado&nbsp;por</td>
					<td><input NAME="asolicito" type="text" class="cajaMedia" id="solicito" size="10" maxlength="10" value="<?php echo $solicito;?>" ></td>
					<td>Tipo</td>
					<td><SELECT size=1 name="atipo" id="tipo" class="comboMedio" >
			<?php
			$HistorialTipo = array( 0=>"Llamada", 1=>"Service", 2=>"Mantenimiento", 3=>"Consulta");
			$x=0;
			$NoEstado=0;
			foreach($HistorialTipo as $i) {
			  	if ( $x==$tiposervice)
				{
					echo "<OPTION value=$x selected>$i</option>";
					$NoEstado=1;
				}
				else
				{
					echo "<OPTION value=$x>$i</option>";
				}
				$x++;
			   }
			if ( $NoEstado!=1 or $tiposervice=="")
			{
			echo "<OPTION value=\"\" selected>Selecione uno</option>";
			}
			?></select>
			</td>
					<td>Estado</td>
					<td>
					
					<SELECT class="comboPequeno" size=1 name="aestado" id="estado">
					<?php
						$estadoarray = array(0=>"Pendiente", 1=>"Asignado", 2=>"Terminado");
					if ($estado==" ")
					{
					echo '<OPTION value="" selected>Selecione uno</option>';
					}
					$x=0;
					$NoEstado=0;
					foreach($estadoarray as $i) {
					  	if ( $x==$solicito) {
							echo "<OPTION value=$x selected>$i</option>";
							$NoEstado=1;
						} else {
							echo "<OPTION value=$x>$i</option>";
						}
						$x++;
					}
					?>
					</select>
					</td>
		
					    </tr></table></td></tr>			    
					    <tr>
				  <td valign="top" width="100%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
				  <tr>
<?php if ($horas==''){ ?>
					<td valign="top" rowspan="3">Detalle&nbsp;solicitud</td>
					<td rowspan="3">
					<textarea name="Adetalles" cols="41" rows="4" id="detalles" class="areaTexto"><?php echo $detalles;?></textarea>	</td>
					<td valign="top" rowspan="3">Trabajo&nbsp;Realizado</td>
					<td rowspan="3">
					<textarea name="arealizado" cols="41" rows="4" id="realizado" class="areaTexto"><?php echo $realizado;?></textarea>	</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="nimporte" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0" value="<?php echo $fecha;?>"></td>
<?php } else {
	
	$Tipo = array("0.5","1","1.5","2","2.5","3","3.5","4","4.5","5","5.5","6","6.5","7","7.5","8","8.5","9");
	$id=1;
	foreach ($Tipo as $t) {
		echo "<td align=\"center\" width=\"30px\">".$t."hr.</td>";
	}
		echo "<td align=\"center\" width=\"30px\">Total</td></tr><tr>";

        $defaultValue=0;

	foreach ($Tipo as $x) {

		if ( $x==$horas)
		{
		echo "<td ><input type=\"radio\" name=\"horas\" id=\"".$id."\" value=\"".$x."\"
		 checked onchange='suma(horas,".$id.");'></td>";
       $defaultValue=$x;
		} else {
		echo "<td><input type=\"radio\" name=\"horas\" id=\"".$id."\" value=\"".$x."\" 
		onchange='suma(horas,".$id.");'></td>";
		}
	}
?>
		<td><input type="text" id="total" value="<?php echo $defaultValue;?>" size='4' name='total' class="cajaPequena2"></td>
		</tr></table></td></tr><tr><td align="left" width="100%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0 width="50%">
					<td valign="top" rowspan="3">Trabajo&nbsp;Realizado</td>
					<td rowspan="3">
					<textarea name="arealizado" cols="41" rows="4" id="realizado" class="areaTexto"><?php echo $realizado;?></textarea>	</td>
					<td width="5%">Importe</td>
					<td width="11%"><input NAME="nimporte" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0"></td>

<?php } ?>				  </tr>
				</table></td></tr></table>			

			  </div>
				<div>
					<img id="botonBusqueda" src="../../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar(<?php echo $codservice;?>)" border="1" onMouseOver="style.cursor=cursor">
					<img id="botonBusqueda" src="../../img/botoncancelar.jpg" width="85" height="22" onClick="cancelar()" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			  </div>
		  </div>
		</div>
	</body>
</html>
