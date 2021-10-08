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
 

date_default_timezone_set("America/Montevideo"); 
include ("../../conectar.php"); 

	$e=$_GET['e'];
	
$fechahoy=date("Y-m-d");
$sel_fact="INSERT INTO servicetmp (codservice,fecha) VALUE ('','$fechahoy')";
$rs_fact=mysqli_query($GLOBALS["___mysqli_ston"], $sel_fact);
$codservicetmp=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

$query="SELECT * FROM clientes WHERE codcliente='$e'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);

$desc="Service Horas";
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../../calendario/jscal2.js"></script>
    <script src="../../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../calendario/css/win2k/win2k.css" />		
		<script src="../js/jquery.min.js"></script>
		<link rel="stylesheet" href="../js/colorbox.css" />
		<script src="../js/jquery.colorbox.js"></script>
		<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../css3/css/font-awesome.min.css">		
		
<script type="text/javascript">
function pon_prefijo(pref,descripcion,service) {
	$("#codequipo").val(pref);
	$("#descripcion").val(descripcion);
	$("#service").val(service);
	$('idOfDomElement').colorbox.close();
}
</script>	


<script language="JavaScript">
function get_radio_value(theradio)
{
  var rads = document.getElementsByName(theradio);
  for(var rad in rads) {
    if(rads[rad].checked)
      return rads[rad].value;
  }
  return null;
}

</script>

<script language="javascript">
function suma(valor,id){
   var tot='total';
   var lor='horas';
	var Sum1=get_radio_value(lor);

/*if (Sum1>7) {
alert('"Pasaste casi todo el día concentrado en este cliente!!!\n¿Es lo que tenías previsto?"');
}
*/
   var Res= document.getElementById(tot);
   Res.value= eval(Sum1);
}
</script>	
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		

		function ventanaEquipo(){
			var e=document.getElementById("e").value;
			$.colorbox({
	   	href: "ver_equipo.php?e="+e, open:true,
			iframe:true, width:"99%", height:"99%"
			});			
		}
		

		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
	
		function validar() 
			{
				var mensaje="";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha\n";
				if (document.getElementById("solicito").value=="") mensaje+="  - Solicitado por\n";
				if (document.getElementById("realizado").value=="") mensaje+="  - Trabajo realizado\n";
				if (document.getElementById("total").value==0) mensaje+="  - Horas\n";
				if (mensaje!="") {
					alert("Atencion, se han detectado las siguientes incorrecciones:\n\n"+mensaje);
				} else {
				document.getElementById("formulario").submit();
				}
			
			}
			
	
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">DATOS DEL CLIENTE </div>
				<div id="frmBusqueda">
				
					<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
							<tr>
							<td width="15%">Nombre</td>
						    <td colspan="3"><input name="Anombre" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "nombre");?>"></td>
				        </tr>
						<tr>
							<td width="15%">Apellido</td>
						    <td colspan="3"><input name="aapellido" autocomplete="off" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="<?php echo mysqli_result($rs_query, 0, "apellido");?>"></td>
				        </tr>
						<tr>
						  <td>RUT</td>
						  <td ><input id="nif" type="text" autocomplete="off" class="cajaPequena" name="anif" maxlength="15" value="<?php echo mysqli_result($rs_query, 0, "nif");?>"></td>
							<td>Tipo</td>
							<td><select type=text size=1 class="comboMedio">
							<?php
								$tipo = array("Seleccione uno", "Cliente","MCC");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, "tipo")){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>

						
					</table></td>
					        <td rowspan="14" align="left" valign="top">
					        </td>
						<td>						
						<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>	
						<tr>
							<td>Tel&eacute;fono</td>
							<td><input id="telefono" name="atelefono" autocomplete="off" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "telefono")?>"></td>

							<td>M&oacute;vil</td>
							<td width="50%"><input id="movil" name="amovil" type="text" class="cajaPequena" maxlength="14" value="<?php echo mysqli_result($rs_query, 0, "movil");?>"></td>
					    </tr>
						<tr>
							<td>Correo&nbsp;electr&oacute;nico  </td>
							<td colspan="3"><input name="aemail" type="text" class="cajaGrande" id="email" size="35" maxlength="35" value="<?php echo mysqli_result($rs_query, 0, "email");?>"></td>
					    </tr>

						<tr>
							<td>Abonado/Service</td>
							<td colspan="3"><select type=text size=1 class="comboMedio">
							<?php
								$tipo = array("Seleccione un tipo", "Común","Abonado A", "Abonado B");
							$xx=0;
							foreach($tipo as $tpo) {
								if ($xx==mysqli_result($rs_query, 0, 'service')){
							      echo "<option value='$xx' selected>$tpo</option>";
								} else {
							      echo "<option value='$xx'>$tpo</option>";
								}
							$xx++;
							}
							?>
							</select></td>
						  
				      </tr>
					</table>
					</td></tr></table>				
			  </div>

			  <br>
			  <div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_service.php">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0><tr><td valign="top" width="100%">
				<div class="header">DATOS DEL EQUIPO </div>
				
				<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0>
				  <tr>
					<td >Equipo</td>
					<td><input name="acodequipo" type="text" class="cajaPequena" id="codequipo" size="15" maxlength="15" readonly> 
					<img id="botonBusqueda" style="float:right;" src="../../img/ver.png" width="16" height="16" onClick="ventanaEquipo()" onMouseOver="style.cursor=cursor" title="Buscar equipo/s cliente">
					</td><td>Descripción</td>
					<td colspan="3"><input name="descripcion" type="text" class="cajaGrande" id="descripcion" size="45"  readonly value="<?php echo $desc;?>"></td>
					<td>Tipo&nbsp;Service</td>
					<td><input name="service" type="text" class="cajaGrande" id="service" size="25"  readonly></td>
				  </tr>
				  </table>
				  
				  </td></tr>
				  <tr>
					  <td valign="top" width="100%">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
					  <tr>
							<td>Fecha&nbsp;de&nbsp;service</td>
							<td><input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" readonly> 
							<img src="../../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
					<td>Solicitado&nbsp;por</td>
					<td><input name="asolicito" type="text" class="cajaMedia" id="solicito" size="10" maxlength="10" ></td>
					<td>Tipo</td>
					<td><select size=1 name="atipo" id="tipo" class="comboMedio" >
			<?php
			$HistorialTipo = array( 0=>"Llamada", 1=>"Service", 2=>"Mantenimiento", 3=>"Consulta");
			$x=0;
			$NoEstado=0;
			foreach($HistorialTipo as $i) {
			  	if ( $x==$tipo)
				{
					echo "<option value=$x selected>$i</option>";
					$NoEstado=1;
				}
				else
				{
					echo "<option value=$x>$i</option>";
				}
				$x++;
			   }
			if ( $NoEstado!=1 or $tipo=="")
			{
			echo "<option value=\"\" selected>Selecione uno</option>";
			}
			?></select>
			</td>
					<td>Estado</td>
					<td>
					
					<select class="comboPequeno" size=1 name="aestado" id="estado">
					<?php
					$estadoarray = array(0=>"Pendiente", 1=>"Asignado", 2=>"Terminado");
					echo '<option value="" selected>Selecione uno</option>';

					$x=0;
					foreach($estadoarray as $i) {

							echo "<option value=$x>$i</option>";
						$x++;
					}
					?>
					</select>
					</td>
<td>Fecha&nbsp;de&nbsp;facturado</td>
							<td><input name="facturado" type="text" class="cajaPequena" id="facturado" size="10" maxlength="10" readonly> 
							<img src="../../img/calendario.png" name="Image2" width="16" height="16" border="0" id="Image2" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">
						   Calendar.setup({
						     inputField : "facturado",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>							
						</td>			
					    </tr></table></td></tr>			    
					    <tr>
				  <td valign="top" width="100%" align="center">
				<table class="fuente8" cellspacing=0 cellpadding=0 border=0 >
				  <tr>
<?php
	$Tipo = array("0.5","1","1.5","2","2.5","3","3.5","4","4.5","5","5.5","6","6.5","7","7.5","8","8.5","9");
	$id=1;
	foreach ($Tipo as $t) {
		echo "<td align=\"center\" width=\"30px\">".$t."hr.</td>";
	}
		echo "<td align=\"center\" width=\"30px\">Total</td></tr><tr>";

        $defaultValue=0;

	foreach ($Tipo as $x) {

		echo "<td><input type=\"radio\" name=\"horas\" id=\"".$id."\" value=\"".$x."\" 
		onchange='suma(horas,".$id.");'></td>";

	}
?>
		<td><input type="text" id="total" value="<?php echo $defaultValue;?>" size='4' name='total' class="cajaPequena2"></td>
		</tr></table></td></tr><tr><td align="left" width="100%">
				<table class="fuente8" cellspacing=0 cellpadding=3 border=0 width="50%">
					<td valign="top" rowspan="3">Trabajo&nbsp;Realizado</td>
					<td rowspan="3">
					<textarea name="arealizado" cols="41" rows="4" id="realizado" class="areaTexto"></textarea>	</td>
					<td width="5%">Importe</td>
					<td width="11%"><input name="nimporte" type="text" class="cajaPequena2" id="importe" size="10" maxlength="10" value="0"></td>
				  </tr>
				</table></td></tr></table>
			  	<input type="hidden" id="e" name="e" value="<?php echo $e;?>">
				<input name="codcliente" type="hidden" id="codcliente" value="<?php echo $e;?>">			  
				<input name="codservice" type="hidden" id="codservice" value="<?php echo $codservicetmp;?>">			  
				<input name="detalles" type="hidden" id="detalles" value="">			  
				<input id="accion" name="accion" value="alta" type="hidden">
				</div>
			   <br style="line-height:5px">
				<div>
						<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
				</div>

			  </form>
			 </div>
		  </div>
		</div>
	</body>
</html>
