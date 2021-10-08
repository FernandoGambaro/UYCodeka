<?php
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 

include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

include("../barcode/barcode.php");

$codarticulo=$_GET["codarticulo"];
$cadena_busqueda=$_GET["cadena_busqueda"];

$query="SELECT * FROM articulos WHERE codarticulo='$codarticulo'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
$codigobarras=mysqli_result($rs_query, 0, "codigobarras");

?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function aceptar() {
			parent.$('idOfDomElement').colorbox.close();
		}
		
		</script>
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0><tr>
					<td colspan="2" class="header">
							<?php echo $cabecera2?>
					</td></tr>
						<tr>
							<td colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
		
					<tr>					
					<td valign="top" width="50%">
					<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%">C&oacute;digo</td>
							<td width="58%"><?php echo $codarticulo?></td>

						</tr>
						<tr>
							<td width="15%">Referencia</td>
							<td width="58%"><?php echo mysqli_result($rs_query, 0, "referencia")?></td>
				        </tr>
						<?php
						$codfamilia=mysqli_result($rs_query, 0, "codfamilia");
					  	$query_familia="SELECT * FROM familias WHERE codfamilia='$codfamilia'";
						$res_familia=mysqli_query($GLOBALS["___mysqli_ston"], $query_familia);
						$nombrefamilia=mysqli_result($res_familia, 0, "nombre");
					  ?>
						<tr>
							<td width="15%">Familia</td>
							<td width="58%"><?php echo $nombrefamilia?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n</td>
						    <td width="58%"><?php echo mysqli_result($rs_query, 0, "descripcion")?></td>
				        </tr>
						<tr>
						  <td>Impuesto</td>
						  <td><?php echo mysqli_result($rs_query, 0, "impuesto")?> %</td>
				      </tr>
					  <?php
					  	$codproveedor1=mysqli_result($rs_query, 0, "codproveedor1");
					  	if ($codproveedor1<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor1'";
							$res_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedor);
							$nombreproveedor=mysqli_result($res_proveedor, 0, "nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor1</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
					<?php
						$codproveedor2=mysqli_result($rs_query, 0, "codproveedor2");
					  	if ($codproveedor2<>0) {
							$query_proveedor="SELECT * FROM proveedores WHERE codproveedor='$codproveedor2'";
							$res_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $query_proveedor);
							$nombreproveedor=mysqli_result($res_proveedor, 0, "nombre");
						} else {
							$nombreproveedor="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Proveedor2</td>
							<td width="58%"><?php echo $nombreproveedor?></td>
				        </tr>
						<tr>
							<td width="15%">Descripci&oacute;n&nbsp;corta</td>
						    <td width="58%"><?php echo mysqli_result($rs_query, 0, "descripcion_corta")?></td>
				        </tr>
						<?php
						$codubicacion=mysqli_result($rs_query, 0, "codubicacion");
					  	if ($codubicacion<>0) {
							$query_ubicacion="SELECT * FROM ubicaciones WHERE codubicacion='$codubicacion'";
							$res_ubicacion=mysqli_query($GLOBALS["___mysqli_ston"], $query_ubicacion);
							$nombreubicacion=mysqli_result($res_ubicacion, 0, "nombre");
						} else {
							$nombreubicacion="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Ubicaci&oacute;n</td>
							<td width="58%"><?php echo $nombreubicacion?></td>
				        </tr>
						<tr>
							<td>Stock</td>
							<td><?php echo mysqli_result($rs_query, 0, "stock")?> unidades</td>
					    </tr>
						<tr>
							<td>Stock&nbsp;minimo</td>
							<td><<?php echo mysqli_result($rs_query, 0, "stock_minimo")?> unidades</td>
					    </tr>
						<tr>
							<td>Aviso&nbsp;M&iacute;nimo</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "aviso_minimo")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td width="15%">Fecha&nbsp;de&nbsp;alta</td>
							<td colspan="2"><?php echo implota(mysqli_result($rs_query, 0, "fecha_alta"))?></td>
					    </tr>
						<tr>
							<td>Codigo&nbsp;de&nbsp;barras</td>
							<td colspan="2"><?php echo "<img src='../barcode/barcode.php?encode=EAN-13&bdata=".$codigobarras."&height=50&scale=2&bgcolor=%23FFFFEC&color=%23333366&type=jpg'>"; ?></td>
						</tr>

						</table></td><td width="500%" valign="top">
						<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
						
						<tr>
							<td width="15%">Datos&nbsp;del&nbsp;producto</td>
							<td colspan="2"><?php echo $datos?></td>
					    </tr>
						<?php
						$codembalaje=mysqli_result($rs_query, 0, "codembalaje");
					  	if ($codembalaje<>0) {
							$query_embalaje="SELECT * FROM embalajes WHERE codembalaje='$codembalaje'";
							$res_embalaje=mysqli_query($GLOBALS["___mysqli_ston"], $query_embalaje);
							$nombreembalaje=mysqli_result($res_embalaje, 0, "nombre");
						} else {
							$nombreembalaje="Sin determinar";
						}
					  ?>
						<tr>
							<td width="15%">Embalaje</td>
							<td colspan="2"><?php echo $nombreembalaje?></td>
					    </tr>
						<tr>
							<td>Unidades&nbsp;por&nbsp;caja</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "unidades_caja")?> unidades</td>
						</tr>
						<tr>
							<td>Preguntar&nbsp;precio&nbsp;ticket</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "precio_ticket")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Modificar&nbsp;descrip.&nbsp;ticket</td>
							<td colspan="2"><?php if (mysqli_result($rs_query, 0, "modificar_ticket")==0) { echo "No"; } else { echo "Si"; }?></td>
						</tr>
						<tr>
							<td>Observaciones</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "observaciones")?></td>
						</tr>
						<tr>
						<td>Moneda</td><td width="26%"> 
						<?php $tipof = array(  1=>"Pesos", 2=>"U\$S");
						echo $tipof[mysqli_result($rs_query, 0, "moneda")];
						
						?>
						</td>
						</tr>
						<tr>
							<td>Precio&nbsp;de&nbsp;compra</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_compra")?></td>
						</tr>
						<tr>
							<td>Precio&nbsp;almac&eacute;n</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_almacen")?></td>
						</tr>												
						<tr>
							<td>Precio&nbsp;en&nbsp;tienda</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_tienda")?></td>
						</tr>
						<!--<tr>
							<td>Pvp</td>
							<td colspan="2"><?php echo $pvp?> &#8364;</td>
						</tr>-->
						<tr>
							<td>Precio con iva</td>
							<td colspan="2"><?php echo mysqli_result($rs_query, 0, "precio_iva")?></td>
						</tr>
						<tr><td width="27%" rowspan="11" align="center" valign="top" colspan="2">
					        <img src="../fotos/<?php echo mysqli_result($rs_query, 0, "imagen")?>" width="160px" height="140px" border="1"></td></tr>
					</table>
					</td></table>
			  </div>
				<div id="botonBusqueda">
					<img src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar();" border="1" onMouseOver="style.cursor=cursor">
			  </div>
			 </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>		
	</body>
</html>




