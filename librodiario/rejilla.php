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
 
include ("../conectar.php");
include ("../funciones/fechas.php");

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if (($fechainicio<>"") and ($fechafin<>"")) {
	$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
} else {
	if ($fechainicio<>"") {
		$where.=" and fecha>='".$fechainicio."'";
	} else {
		if ($fechafin<>"") {
			$where.=" and fecha<='".$fechafin."'";
		}
	}
}

$where.=" ORDER BY fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM librodiario WHERE ".$where;

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">

		function ver_cobros(codfactura) {
			parent.location.href="ver_cobros.php?codfactura=" + codfactura + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
		}
		
		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= 10) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.formulario.filas.value=numfilas;
			parent.document.formulario.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-10;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + 10;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+9)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+9);
				if (parseInt(indi)==parseInt(contador)) {
					if (indi==1) {
					parent.document.getElementById("first").style.display = 'none';
					parent.document.getElementById("prev").style.display = 'none';
					parent.document.getElementById("firstdisab").style.display = 'block';
					parent.document.getElementById("prevdisab").style.display = 'block';
					} else {
					parent.document.getElementById("first").style.display = 'block';
					parent.document.getElementById("prev").style.display = 'block';
					parent.document.getElementById("firstdisab").style.display = 'none';
					parent.document.getElementById("prevdisab").style.display = 'none';
					}
					parent.document.getElementById("prevpagina").value = contador-10;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + 10;

					parent.document.formulario.paginas.options[indice]=new Option (texto,contador);
					parent.document.formulario.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.formulario.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+10;
			}	

					if (parseInt(indiaux) == parseInt(indice)-1 ) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
					} else {
					parent.document.getElementById("nextdisab").style.display = 'none';
					parent.document.getElementById("lastdisab").style.display = 'none';
					parent.document.getElementById("last").style.display = 'block';
					parent.document.getElementById("next").style.display = 'block';
					}
		}
		</script>
	</head>

	<body onload="inicio();">
<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header">	Relaci&oacute;n de MOVIMIENTOS </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<td width="10%">FECHA</td>
							<td width="10%">C/V</td>							
							<td width="10%">FACTURA</td>
							<td width="20%">COMERCIAL</td>
							<td width="20%">FORMA PAGO</td>
							<td width="15%">NUM. DOC.</td>
							<td width="10%">MONEDA</td>
							<td width="10%">IMPORTE</td>
						</tr>			
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
				<?php	
				$iniciopagina=$_POST["iniciopagina"];	
						$moneda = array(1=>"\$", 2=>"U\$S");
				
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }							
				if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT librodiario.*,formapago.nombrefp FROM librodiario LEFT JOIN formapago ON librodiario.codformapago=formapago.codformapago WHERE ".$where;
						$sel_resultado=$sel_resultado." limit ".$iniciopagina.",10";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;					   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr class="<?php echo $fondolinea?>">
						  <?php if (mysqli_result($res_resultado, $contador, "tipodocumento")==1) { 
						  		$codproveedor=mysqli_result($res_resultado, $contador, "codcomercial");
								$sel_proveedor="SELECT nombre FROM proveedores WHERE codproveedor='$codproveedor'";
								$rs_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $sel_proveedor);
								$nombrecomercial=mysqli_result($rs_proveedor, 0, "nombre");
								$movimiento="Compra"; 
							} else { 
								$codcliente=mysqli_result($res_resultado, $contador, "codcomercial");
								$sel_proveedor="SELECT nombre FROM clientes WHERE codcliente='$codcliente'";
								$rs_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $sel_proveedor);
								$nombrecomercial=mysqli_result($rs_proveedor, 0, "nombre"); 
								$movimiento="Venta";
							}  ?>
							<td class="aCentro" width="5%"><?php echo $contador+1;?></td>
							<td width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"))?></div></td>
							<td width="10%"><div align="center"><?php echo $movimiento?></div></td>							
							<td width="10%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "coddocumento")?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><?php echo $nombrecomercial?></div></td>
							<td class="aDerecha" width="20%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "nombrefp")?></div></td>
							<td class="aDerecha" width="15%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "numpago")?></div></td>							
							<td width="7%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?></div></td>
							<td width="10%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "total"),2,",",".");?></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n movimiento que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>	
					</form>				
			</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
