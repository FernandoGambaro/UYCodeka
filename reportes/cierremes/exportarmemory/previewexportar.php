<?php
setlocale('LC_ALL', 'es_ES');
date_default_timezone_set("America/Montevideo"); 
include ("../../conectar.php");
include ("../../funciones/fechas.php");

$startTime =explota($_POST['fechainicio']); 
$endTime = explota($_POST['fechafin']); 

$sTime=$startTime;
 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
	<head>
		<title>Facturas</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">
	
	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">

<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">

<table class="fuente8" cellspacing=0 cellpadding=3 border=0>
	<colgroup width="34"></colgroup>
	<colgroup width="55"></colgroup>
	<colgroup width="40"></colgroup>
	<colgroup width="31"></colgroup>
	<colgroup width="221"></colgroup>
	<colgroup width="53"></colgroup>
	<colgroup width="20"></colgroup>
	<colgroup width="67"></colgroup>
	<colgroup width="10"></colgroup>
	<colgroup width="49"></colgroup>
	<colgroup width="76"></colgroup>
	<colgroup width="60"></colgroup>
	<colgroup width="61"></colgroup>
<thead>	
	<tr>
		<th height="17" align="right"><div  class="th-inner">Dia</div></th>
		<th align="right"><div class="th-inner">Debe</div></th>
		<th align="right"><div class="th-inner">Habrer</div></th>
		<th align="right"><div class="th-inner">S</div></th>
		<th align="right"><div class="th-inner">Concepto</div></th>
		<th align="right"><div class="th-inner">Moneda</div></th>
		<th align="right"><div class="th-inner">Total</div></th>
		<th align="right"><div class="th-inner">CodigoIVA</div></th>
		<th align="right"><div class="th-inner">IVA</div></th>
		<th align="right"><div class="th-inner">N</div></th>
		<th align="right"><div class="th-inner">Impuesto</div></th>
		<th align="right"><div class="th-inner">Cotización</div></th>
		<th align="right"><div class="th-inner">L</div></th>
		<th align="right"><div class="th-inner">O</div></th>
	</tr>
</thead>	
<tbody>
<?php
	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
	$moneda = array(1=>"\$", 2=>"U\$S");
	
$Iva_Compras=0;
$Iva_Ventas=0;
$Total_Compras=0;
$Total_Ventas=0;	
$Cant_Ventas=0;
$Cant_Compras=0;	

	while (strtotime($startTime) <= strtotime($endTime)) {
      $day = date("d", strtotime($startTime));
		
			$sel_resultado="SELECT * FROM facturas WHERE fecha ='".$startTime."' order by codfactura";
		
			$res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
		   $contador=0;
		   $marcaestado=0;						   
		   while ($contador < mysqli_num_rows($res_resultado)) {
		   	/*Saco los datos del cliente*/
		   	$sel_cliente="select nombre,apellido,empresa from clientes where codcliente='". mysqli_result($res_resultado, $contador, "codcliente")."'";
		   	$res_cliente=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cliente);
		   	$concliente=0;
		   	while($concliente < mysqli_num_rows($res_cliente)) {
		   	if (mysqli_result($res_cliente, $concliente, "empresa")!='') {
						$nombre= mysqli_result($res_cliente, $concliente, "empresa");
					} elseif (mysqli_result($res_cliente, $concliente, "apellido")=='') {
						$nombre= mysqli_result($res_cliente, $concliente, "nombre");
					} else {
						$nombre= mysqli_result($res_cliente, $concliente, "nombre"). ' ' . mysqli_result($res_cliente, $concliente, "apellido");
					}
				$concliente++;
				} 
		   	/*Fin de datos cliente*/

				$Concepto=$tipo[mysqli_result($res_resultado, $contador, "tipo")]. " Nº ". mysqli_result($res_resultado, $contador, "codfactura"). " " .$nombre;
				$Concepto=str_replace(" ", "&nbsp;", $Concepto);
				$iva=mysqli_result($res_resultado, $contador, "iva");
				if(mysqli_result($res_resultado, $contador, "moneda")==1) {
					$moneda=0;
					$num="11111";
				} elseif(mysqli_result($res_resultado, $contador, "moneda")==2) {
					$moneda=1;
					$num="11112";
				}

		   	/*Obtengo datos lineas de factura*/
		   	$sel_linea="SELECT * FROM factulinea WHERE codfactura='". mysqli_result($res_resultado, $contador, "codfactura")."'";
		   	$res_linea=mysqli_query($GLOBALS["___mysqli_ston"], $sel_linea);
		   	$conlinea=0;
		   	while($conlinea < mysqli_num_rows($res_linea)) {
		   		/*Obtengo nº plan de cuenta artículo*/
		   		$sel_articulo="SELECT * FROM articulos WHERE codarticulo='". mysqli_result($res_linea, $conlinea, "codigo")."'";
		   		$res_articulo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulo);
		   		$conarticulo=0;
		   		while($conarticulo < mysqli_num_rows($res_articulo)) {
		   		$plancuentav=mysqli_result($res_articulo, $conarticulo, "plancuentav");
		   		$conarticulo++;	
		   		}
		   		/*Fin plan de cuenta artículo*/
		   		$importe=mysqli_result($res_linea, $conlinea, "importe")*(1+$iva/100);
		   		$importeiva=$importe*$iva/100;
		   		
?>	
	<tr>
		<td height="17" align="right" sdval="10" sdnum="3082;"><?php echo $day;?></td>
		<td align="right" sdval="11111" sdnum="3082;"><?php echo $num;?></td>
		<td align="right" sdval="41503" sdnum="3082;"><?php echo $plancuentav;?></td>
		<td align="right"><br></td>
		<td align="right"><?php echo $Concepto;?></td>
		<td align="right" sdval="0" sdnum="3082;"><?php echo $moneda;?></td>
		<td align="right" sdval="" sdnum="3082;0;0,00"><?php echo number_format($importe,2,",",".");?></td>
		<td align="right"><br></td>
		<td align="right" sdval="" sdnum="3082;0;0,00"><?php echo number_format($importeiva,2,",",".");?></td>
		<td align="right"><br></td>
		<td align="right" sdval="0" sdnum="3082;">0</td>
		<td align="right" sdval="0" sdnum="3082;">0</td>
		<td align="right">V</td>
		<td align="right"><br></td>
	</tr>
	<?php		   		
		   	$importe=0;
		   	$importeiva=0;
		   	$plancuentav='';
		   	$conlinea++;
		   	}
		   
		   $contador++;
		   }
		   
		$sel_compras="select * from facturasp where fecha ='".$startTime."' order by codfactura";
		$res_compras=mysqli_query($GLOBALS["___mysqli_ston"], $sel_compras);
		$concompras=0;
		while($concompras < mysqli_num_rows($res_compras)) {
	   	/*Saco los datos del proveedor*/
	   	$sel_proveedor="select nombre,plancuenta from proveedores where codproveedor='". mysqli_result($res_compras, $concompras, "codproveedor")."'";
	   	$res_proveedor=mysqli_query($GLOBALS["___mysqli_ston"], $sel_proveedor);
	   	$conproveedor=0;
	   	while($conproveedor < mysqli_num_rows($res_proveedor)) {
					$nombre = mysqli_result($res_proveedor, $conproveedor, "nombre");
			$conproveedor++;
			} 
	   	/*Fin de datos proveedor*/
				$Concepto_compra=" Nº ". mysqli_result($res_proveedor, $conproveedor, "codfactura"). " " .$nombre;
				$Concepto_compra=str_replace(" ", "&nbsp;", $Concepto_compra);
	   	
				$iva_compras=mysqli_result($res_compras, $concompras, "iva");
				if(mysqli_result($res_compras, $concompras, "moneda")==1) {
					$moneda_compras=0;
					$num="11111";
				} elseif(mysqli_result($res_compras, $concompras, "moneda")==2) {
					$moneda_compras=1;
					$num="11112";
				}
				
		   	/*Obtengo datos lineas de factura*/
		   	$sel_linea="SELECT * FROM factulineap WHERE codfactura='". mysqli_result($res_compras, $concompras, "codproveedor")."'";
		   	$res_linea=mysqli_query($GLOBALS["___mysqli_ston"], $sel_linea);
		   	$conlinea=0;
		   	while($conlinea < mysqli_num_rows($res_linea)) {
		   		/*Obtengo nº plan de cuenta artículo*/
		   		$sel_articulo="SELECT * FROM articulos WHERE codarticulo='". mysqli_result($res_compras, $concompras, "codproveedor")."'";
		   		$res_articulo=mysqli_query($GLOBALS["___mysqli_ston"], $sel_articulo);
		   		$conarticulo=0;
		   		while($conarticulo < mysqli_num_rows($res_articulo)) {
		   		$plancuentac=mysqli_result($res_articulo, $conarticulo, "plancuentac");
		   		$conarticulo++;	
		   		}
		   		/*Fin plan de cuenta artículo*/				
		   		$importe_compra=mysqli_result($res_linea, $conlinea, "importe")*(1+$iva/100);
		   		$importeiva_compra=$importe_compras*$iva_compras/100;

?>	
	<tr>
		<td height="17" align="right" sdval="10" sdnum="3082;"><?php echo $day;?></td>
		<td align="right" sdval="11111" sdnum="3082;"><?php echo $num;?></td>
		<td align="right" sdval="41503" sdnum="3082;"><?php echo $plancuentac;?></td>
		<td align="right"><br></td>
		<td align="right"><?php echo $Concepto_compra;?></td>
		<td align="right" sdval="0" sdnum="3082;"><?php echo $moneda_compras;?></td>
		<td align="right" sdval="" sdnum="3082;0;0,00"><?php echo number_format($importe_compra,2,",",".");?></td>
		<td align="right"><br></td>
		<td align="right" sdval="" sdnum="3082;0;0,00"><?php echo number_format($importeiva_compra,2,",",".");?></td>
		<td align="right"><br></td>
		<td align="right" sdval="0" sdnum="3082;">0</td>
		<td align="right" sdval="0" sdnum="3082;">0</td>
		<td align="right">C</td>
		<td align="right"><br></td>
	</tr>
<?php	
				$importe_compra=0;
				$importeiva_compra=0;				
				$conlinea++;
				}
		$nombre='';
		$concompras++;	
		}		   
		   
		   		
	$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
	}
	?>	
	</tbody>
</table>
<!-- ************************************************************************** -->
				</div></div></div></div>
</body>

</html>
