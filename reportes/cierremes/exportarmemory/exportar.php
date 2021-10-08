<?php
setlocale('LC_ALL', 'es_ES');
date_default_timezone_set("America/Montevideo"); 
include ("../../conectar.php");
include ("../../funciones/fechas.php");

	
$startTime =explota($_GET['fechainicio']); 
$endTime =explota($_GET['fechafin']); 

$sTime=$startTime;

      $anio = date("y", strtotime($startTime));
      $mes = date("m", strtotime($startTime));

$nombrearchivo="IM".$anio.$mes."00.TXT";
$filename="../../tmp/IM".$anio.$mes."00.TXT";
 
  $ar=fopen($filename,"w") or die("Problemas en la creacion");
  fputs($ar,",,,,,,,,,");
  fputs($ar,"\n");
  fputs($ar,"Dia, Debe, Haber, Concepto, Moneda,  Total, CodigoIVA, IVA, Cotizacion, Libro");
  fputs($ar,"\n");
 
  
  
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

  fputs($ar,$day.",".$num.",".$plancuentav.",".$Concepto.",".$moneda.",".number_format($importe,2,".","").",,".number_format($importeiva,2,".","").",,V,");
  fputs($ar,"\n");
		   		
		   		
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

  fputs($ar,$day.",".$num.",".$plancuentac.",".$Concepto_compra.",".$moneda_compras.",".number_format($importe_compra,2,".","").",,".number_format($importeiva_compra,2,".","").",,V,");
  fputs($ar,"\n");
		   		

				$importe_compra=0;
				$importeiva_compra=0;				
				$conlinea++;
				}
		$nombre='';
		$concompras++;	
		}		   
		   
		   		
	$startTime = date ("Y-m-d", strtotime("+1 day", strtotime($startTime)));
	}
  fputs($ar,"\n");
  fclose($ar);

?>	
<html>
	<head>
		<title>Exportar a Memory</title>
		<link href="../../estilos/estilos.css" type="text/css" rel="stylesheet">

	</head>
	<body onLoad="inicio()">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Exportar a Memory </div>
			
			<p>El archivo se genero con exito</p>
			<br><?php echo $nombrearchivo;?>
			<p>El nombre del archivo tiene el siguiente formato</p>
			<p>IM seguido del año seguido del mes seguido de 00.TXT</p>
			<a href="<?php echo $filename;?>">Boton derecho guardar como para descargar</a></p>  
		</div>
	</div>
	</div>
			
			
</body></html>

