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
session_start();
require_once('../class/class_session.php');
/*
Instantiate a new session object. If session exists, it will be restored,
otherwise, a new session will be created--placing a sid cookie on the user's
computer.
*/
if (!$s = new session()) {
  /*
  There is a problem with the session! The class has a 'log' property that
  contains a log of events. This log is useful for testing and debugging.
  */
  echo "<h2>Ocurrió un error al iniciar session!</h2>";
  echo $s->log;
  exit();
}


if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
{
	/*/user is not logged in*/
	echo "<script>location.href='../index.php'; </script>";
   //header("Location:../index.php");	

} else {
   $loggedAt=$s->data['loggedAt'];
   $timeOut=$s->data['timeOut'];
   if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
   	$s->data['act']="timeout";
    	$s->save();  	
  		//header("Location:../index.php");	
		echo "<script>window.top.location.href='../index.php'; </script>";
	   exit;
   }
   $s->data['loggedAt']= time();
   $s->save();
}

$UserID=$s->data['UserID'];
$UserNom=$s->data['UserNom'];
$UserApe=$s->data['UserApe'];
$UserTpo=$s->data['UserTpo'];

$paginacion=$s->data['alto'];
if($paginacion<=0) {
	$paginacion=20;
}
 
include ("../conectar.php");
include ("../funciones/fechas.php");

$codproveedor=$_POST["codproveedor"];
$estado=$_POST["cboEstados"];
$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codproveedor <> "") { $where.=" AND facturasp.codproveedor='$codproveedor'"; }
if ($estado <> 0) { $where.=" AND estado='$estado'"; }
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

$where.=" ORDER BY facturasp.fecha DESC";
$query_busqueda="SELECT count(*) as filas FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor WHERE facturasp.borrado=0 AND facturasp.tipo>=1 AND ".$where;

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Proveedores</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>

<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var idstyle='';
var indiaux='';

$(document).ready(function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

$('.trigger').click(function(e){
  	if (idstyle!="") {	
		var el = document.getElementById(idstyle);
		el.setAttribute('style', '');    	
  	}
      list=this.id;
      idstyle=this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo);   
		parent.document.getElementById("selid").value=list;
   }); 
});

</script>		
		
		
		<script language="javascript">

		function ver_pagos(codfactura,codproveedor) {
			var url="ver_pagos.php?codfactura=" + codfactura + "&codproveedor=" + codproveedor;
			window.parent.OpenNote(url);
		}		
		
		function inicio() {
			var list=parent.document.getElementById("selid").value;
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			var indiaux=0;			
			
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= <?php echo $paginacion?>) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion?>;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion?>;
			numfilas=Math.abs(numfilas);
			while (contador<=parseInt(numfilas)) {
				if (parseInt(contador+<?php echo $paginacion?>-1)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+<?php echo $paginacion?>-1);
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
					parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion;?>;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion;?>;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+<?php echo $paginacion;?>;
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

	<body onload="inicio()";>	

		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">Listado de pago proveedores  </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">	
					<thead>
					  <tr class="cabeceraTabla">	
							<th width="11%" ><div class="th-inner">N. FACTURA</div></th>
							<th width="17%" ><div class="th-inner">PROVEEDOR</div> </th>							
							<th width="9%" ><div class="th-inner">MODENA</div></th>
							<th width="9%" ><div class="th-inner">IMPORTE</div></th>
							<th width="10%" ><div class="th-inner">PENDIENTE</div></th>
							<th width="10%" ><div class="th-inner">FECHA</div></th>
							<th width="10%" ><div class="th-inner">ESTADO</div></th>
							<th width="10%" ><div class="th-inner">FECHA PAGO</div></th>
							<th width="2%" ><div class="th-inner">&nbsp;</div></th>
						</tr>
					</thead>
				
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				$tipomon = array();
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
			
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT distinct facturasp.codfactura,facturasp.fecha,facturasp.moneda,totalfactura,estado,facturasp.fechapago,facturasp.moneda,proveedores.nombre 
						as nombre,proveedores.codproveedor FROM facturasp LEFT JOIN pagos ON facturasp.codfactura=pagos.codfactura 
						AND facturasp.codproveedor=pagos.codproveedor INNER JOIN proveedores ON facturasp.codproveedor=proveedores.codproveedor 
						WHERE facturasp.borrado=0 AND facturasp.tipo>=1 AND ".$where." limit ".$iniciopagina.",".$paginacion;
						
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;			

							/*Genero un array con los simbolos de las monedas*/
							$tipomon = array();
							$sel_monedas="SELECT * FROM monedas WHERE borrado=0 AND orden <3 ORDER BY orden ASC";
						   $res_monedas=mysqli_query($GLOBALS["___mysqli_ston"], $sel_monedas);
						   $con_monedas=0;
							$xmon=1;
						 while ($con_monedas < mysqli_num_rows($res_monedas)) {
						 	$descripcion=explode(" ", mysqli_result($res_monedas, $con_monedas, "simbolo"));
						 	 $tipomon[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }							   
						   		   
						   while ($contador < mysqli_num_rows($res_resultado)) {
						   		$moneda=$tipomon[mysqli_result($res_resultado, $contador, "moneda")];
						   		if (mysqli_result($res_resultado, $contador, "estado") == 1) { $estado="Sin pagar"; } else { $estado="Pagada"; } 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codfactura");?>"	 class="<?php echo $fondolinea?> trigger">
							<td width="12%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codfactura")?></div></td>
							<td width="17%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre")?></div></td>							
							<td width="9%"><div align="center"><?php echo $moneda;?></div></td>
							<td width="9%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "totalfactura"),2,",",".")?></div></td>
							<?php $sel_cobros="SELECT sum(importe) as aportaciones FROM pagos WHERE codfactura='".mysqli_result($res_resultado, $contador, "codfactura")."' AND codproveedor='".mysqli_result($res_resultado, $contador, "codproveedor")."'";
								$rs_cobros=mysqli_query($GLOBALS["___mysqli_ston"], $sel_cobros);
								$aportaciones=mysqli_result($rs_cobros, 0, "aportaciones"); 
								$pendiente=mysqli_result($res_resultado, $contador, "totalfactura") - $aportaciones; ?>
							<td class="aDerecha" width="10%"><div align="center"><?php echo number_format($pendiente,2,",",".")?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"))?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo $estado?></div></td>							
							<td width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fechapago"));?></div></td>
							<td width="5%"><div align="center"><a href="#">
							<img id="botonBusqueda" src="../img/dinero.jpg" width="16" height="16" border="0" 
							onClick="ver_pagos('<?php echo mysqli_result($res_resultado, $contador, "codfactura")?>',<?php echo mysqli_result($res_resultado, $contador, "codproveedor")?>)" 
							title="Ver Pagos"></a></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>	
					</form>				
				</div></div>
			</div>
		  </div>	
		  </div>		
	</body>
</html>
