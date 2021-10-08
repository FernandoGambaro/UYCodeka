<?php

include ("../conectar.php");
include ("../funciones/fechas.php");

$tiporpt = @$_POST['tiporpt'];

$busqueda='';
$deudaanterior='';

$where="";
$fecharecibos="";

$codusuarios = isset($_POST["codusuarios"]) ? $_POST["codusuarios"] : $_POST["codusuarios"];
$codprovincia = isset($_POST["cboProvincias"]) ? $_POST["cboProvincias"] : $_POST["cboProvincias"];
$localidad = isset($_POST["localidad"]) ? $_POST["localidad"] : $_POST["localidad"];
$fechainicio = isset($_POST["fechainicio"]) ? explota($_POST["fechainicio"]) : explota($_POST["fechainicio"]);
$fechafin = isset($_POST["fechafin"]) ? explota($_POST["fechafin"]) : explota($_POST["fechafin"]);
$codcliente = isset($_POST["codcliente"]) ? $_POST["codcliente"] : $_POST["codcliente"];
$moneda = isset($_POST["amoneda"]) ? $_POST["amoneda"] : $_POST["amoneda"];

if ($codcliente <> "") { $where.=" AND codcliente='$codcliente'"; }
if ($codusuarios <> "" and $codusuarios != 0) { $where.=" AND codusuarios='$codusuarios'";  }
if ($codprovincia <> "" and $codprovincia !=0) { $where.=" AND codprovincia = '$codprovincia'"; }
if ($localidad <> "") { $where.=" AND localidad like '%".$localidad."%'"; }
if ($codcliente > "") { $where.=" AND codcliente='$codcliente'"; }


	if (($fechainicio<>"") and ($fechafin<>"")) {
		$where.=" AND fecha between '".$fechainicio."' AND '".$fechafin."'";
	} else {
		if ($fechainicio<>"") {
			$where.=" AND fecha>='".$fechainicio."'";
		} else {
			if ($fechafin<>"") {
				$where.=" AND fecha<='".$fechafin."'";
			}
		}
	}

$Reporte='';

if($tiporpt==1) {
	$Reporte="Deudores por ventas";
}
if($tiporpt==2) {
	$Reporte="Estado de cuenta";	
}

if ($moneda <> "" and $moneda !=0) { $where.=" AND facturas.moneda='$moneda'"; }


$startTime =data_first_month_day(explota($_POST['fechainicio'])); 
$endTime = data_last_month_day(explota($_POST['fechafin'])); 
$sTime=$startTime;

//$whereCobros.=" ORDER BY codcliente";  

	$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");

	$tipomoneda = array();

							/*Genero un array con los simbolos de las monedas*/
							$tipomoneda[0]="Ambas";
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $tipomoneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }		

$sql_datos="SELECT * FROM `datos` where `coddatos` = 0";
$rs_datos=mysqli_query($GLOBALS["___mysqli_ston"], $sql_datos);
$razonsocial=mysqli_result($rs_datos, 0, "razonsocial");
$direccion=mysqli_result($rs_datos, 0, "direccion");
$telefono=mysqli_result($rs_datos, 0, "telefono1");
$fax=mysqli_result($rs_datos, 0, "fax");
$email=mysqli_result($rs_datos, 0, "mailv");
$web=mysqli_result($rs_datos, 0, "web");

 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title></title>
	<meta name="generator" content="Bluefish 2.2.10" >
	<meta name="created" content="20140722;2825872280191">
	<meta name="changed" content="20140722;231034824935761">
	
	<style type="text/css"><!-- 
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Liberation Sans"; font-size:x-small }
		 -->
	</style>
	
</head>

<body text="#000000">
<table cellspacing="0" border="0">
	<colgroup span="2" width="38"></colgroup>
	<colgroup width="270"></colgroup>
	<colgroup width="27"></colgroup>
	<colgroup width="71"></colgroup>
	<colgroup width="30"></colgroup>
	<colgroup width="74"></colgroup>
	<colgroup width="30"></colgroup>
	<colgroup width="67"></colgroup>
	<tr>
		<td colspan=3 height="22" align="left" valign=middle><b><i><font face="Tahoma" size=3>Liquidación de comisiones</font></i></b></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td colspan=5 align="right" valign=middle><b><font face="Tahoma" size=3><?php echo $razonsocial;?></font></b></td>
		</tr>
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td colspan="2" align="left"><font face="Tahoma" size=2><?php echo $Reporte;?></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td colspan=5 align="right" valign=middle><font face="Tahoma"><?php echo $direccion;?></font></td>
		</tr>
	<tr>
		<td height="17" align="center" valign=middle><font face="Tahoma"><br></font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">
 <?php echo $busqueda;?>
		
		</font></td>
		<td colspan=2 align="left" valign=middle><font face="Tahoma">Moneda <?php echo $tipomoneda[$moneda] ;?></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<?php 
	/*Fin de cabezal sin titulos */
	?>
	<tr>
		<td height="17" align="left"><font face="Tahoma">Fecha</font></td>
		<td align="left"><font face="Tahoma">Nº Factura</font></td>
		<td align="left"><font face="Tahoma">Descripción</font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="right"><font face="Tahoma"></font></td>
		<td align="left"><font face="Tahoma">Cantidad</font></td>
		<td align="right"><font face="Tahoma">Precio</font></td>
		<td align="left"><font face="Tahoma">Com %</font></td>
		<td align="right"><font face="Tahoma">Liquidación</font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left" bgcolor="#0000CC"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<tr style="line-height:5px">
		<td height="6" align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
		<td align="left"><font face="Tahoma"><br style="line-height:5px"></font></td>
	</tr>
	<?php


$sql_usuarios="SELECT * FROM `usuarios` where `borrado`=0 ORDER BY apellido";
$res_usuarios=mysqli_query($GLOBALS["___mysqli_ston"], $sql_usuarios);
$contador=0;

$importetotal=0;
$x=12;

while($contador < mysqli_num_rows($res_usuarios)) {
$codusuarios=mysqli_result($res_usuarios, $contador, "codusuarios");
	
	$sql_comision="select clientes.codcliente, clientes.nombre, clientes.apellido, clientes.empresa, clientes.codusuarios, clientes.codprovincia, clientes.localidad, facturas.codfactura, 
	facturas.fecha, facturas.moneda, factulinea.codfactura, factulinea.moneda, factulinea.cantidad, factulinea.precio, factulinea.comision, articulos.descripcion from clientes INNER JOIN facturas ON facturas.codcliente=clientes.codcliente 
	INNER JOIN factulinea ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos on articulos.codarticulo=factulinea.codigo WHERE (cantidad * precio) > 0 AND clientes.codusuarios='".$codusuarios."'
	".$where;
	
	$res_comision=mysqli_query($GLOBALS["___mysqli_ston"], $sql_comision);
	
	$contadorCom=0;
	$codclienteaux='';
	$show=1;
	while($contadorCom < mysqli_num_rows($res_comision)) {
if($show==1) {			
?>
	<tr>
		<td colspan=2 height="17" align="center" valign=middle><font face="Tahoma"><b>&nbsp;<?php echo mysqli_result($res_usuarios, $contador, "nombre")." ".mysqli_result($res_usuarios, $contador, "apellido"); ?></b></font></td>
		<td align="center" valign=middle>&nbsp;&nbsp;</td>
		<td align="center" valign=middle></td>
		<td align="right" ></td>
		<td align="left" ></td>
		<td align="right"></td>
		<td align="left" ></td>
		<td align="right"></td>		
	</tr>
<?php	
}
$show=0;			
			
		if($codclienteaux!=mysqli_result($res_comision, $contadorCom, "codcliente") ) {

?>			
	<tr>
		<td align="center" valign=middle>&nbsp;&nbsp;</td>
		<td colspan=4 height="17" align="center" valign=middle><font face="Tahoma"><b>&nbsp;<?php echo mysqli_result($res_comision, $contadorCom, "nombre")." ".mysqli_result($res_comision, $contadorCom, "apellido"). " - ".mysqli_result($res_comision, $contadorCom, "empresa"); ?></b></font></td>
		<td align="left" ></td>
		<td align="right"></td>
		<td align="left" ></td>
		<td align="right"></td>		
	</tr>
				
<?php		
		}
		

		$totalcomision=mysqli_result($res_comision, $contadorCom, "cantidad")*mysqli_result($res_comision, $contadorCom, "precio")*(mysqli_result($res_comision, $contadorCom, "comision")/100);
		$importetotal+=$totalcomision;
		if(mysqli_result($res_comision, $contadorCom, "comision")>0) {
?>
	<tr>
		<td height="17" align="left" valign=middle ><font face="Tahoma"><?php echo implota(mysqli_result($res_comision, $contadorCom, "fecha"));?></font></td>
		<td align="left" valign=middle><font face="Tahoma"><?php echo mysqli_result($res_comision, $contadorCom, "codfactura");?></font></td>
		<td colspan="3" align="left" valign=middle><font face="Tahoma"><?php echo mysqli_result($res_comision, $contadorCom, "descripcion");?></font></td>
		<td align="right"><font face="Tahoma"><?php echo mysqli_result($res_comision, $contadorCom, "cantidad");?></font></td>
		<td align="right" ><font face="Tahoma"><?php echo number_format(mysqli_result($res_comision, $contadorCom, "precio"),2,",",".");?></font></td>
		<td align="right"><font face="Tahoma"><?php echo mysqli_result($res_comision, $contadorCom, "comision");?>%</font></td>
		<td align="right" ><font face="Tahoma"><?php echo number_format($totalcomision,2,",",".");?></font></td>
	</tr>
<?php		
	}	
		
	$codclienteaux=mysqli_result($res_comision, $contadorCom, "codcliente");
	$contadorCom++;
	}
	
if($show==0) {
?>
	<tr>
		<td height="17" align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma"><br></font></td>
		<td align="left"><font face="Tahoma">Total</font></td>
		<td align="right" valign=middle ><font face="Tahoma"><?php  echo number_format($importetotal,2,",",".");?>&nbsp;</font></td>
	</tr>
<?php

}	
$importetotal=0;
$contador++;
}

?>
	<tr >
		<td colspan="9" style="line-height:5px; border: 0px solid #000000; border-top: 1px solid #000000;"><font face="Tahoma"><br>&nbsp;</font></td>
	</tr>



</table>
<!-- ************************************************************************** -->
</body>

</html>


