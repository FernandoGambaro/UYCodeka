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
@$paginacion=$s->data['alto'];

if($paginacion<=0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
include("../common/funcionesvarias.php");

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");date_default_timezone_set("America/Montevideo");
 
$cadena_busqueda=$_POST["cadena_busqueda"];

$moneda=$_POST["moneda"];

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$codigobarras=$_POST['codigobarras'];
$referencia=$_POST['referencia'];
$descripcion=$_POST['descripcion'];

$where=" 1=1";

if ($codigobarras <> "") { $where.=" AND `codigobarras` like'%".$codigobarras."%'"; }
if ($descripcion <> "") { $where.=" AND `descripcion` like '%".$descripcion."%'"; }
if ($referencia <> "") { $where.=" AND `referencia` LIKE '%".$referencia."%' "; }


if ($moneda <> 0 ) { $where.=" AND facturas.moneda = '".$moneda."'"; }

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



$where.=" GROUP BY articulos.codarticulo ORDER BY articulos.codarticulo ASC , fecha ASC ";

$sel_resultado="SELECT factulinea.codigo, factulinea.codfactura, factulinea.cantidad, factulinea.detalles, facturas.moneda, facturas.codfactura, facturas.fecha, 
articulos.codarticulo, articulos.descripcion, articulos.referencia, sum(factulinea.cantidad) as total FROM factulinea 
INNER JOIN facturas ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos ON factulinea.codigo=articulos.codarticulo  WHERE ".$where;
						
   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
   $filas=mysqli_num_rows($res_resultado);

?>
<html>
	<head>
		<title></title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var indiaux;
		function ver_proveedor(codproveedor) {
			var url="ver_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);
		}
		
		function modificar_proveedor(codproveedor) {
			var url="modificar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);
		}
		
		function eliminar_proveedor(codproveedor) {
			var url="eliminar_proveedor.php?codproveedor=" + codproveedor + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='880px';
			var h='400px';
			window.parent.OpenNote(url,w,h);			
		}

		function inicio() {
			var list=parent.document.getElementById("selid").value;
			var paginacion=<?php echo $paginacion;?>;
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= paginacion) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=' '+numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-paginacion;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + paginacion;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+paginacion-1)>numfilas) {
					texto=contador + " al " + parseInt(numfilas);
				} else {
					texto=contador + " al " + parseInt(contador+paginacion-1);
				}
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
					parent.document.getElementById("prevpagina").value = contador-paginacion;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + paginacion;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=Math.abs(contador+paginacion);
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
			
			idstyle=list;
			var el = document.getElementById(list);
			//if(jQuery("#"+list).length){
			//alert('pp')			;
			//}
			if (!document.getElementById(list)) {
			idstyle='';
			} else {
			el.setAttribute('style', estilo);   
			}
		}
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed;">Listado de Artículos Vendidos</div>
<div class="fixed-table-container">
      <div class="header-background cabeceraTabla"> </div>      			
<div class="fixed-table-container-inner">

					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					<thead>
					  <tr>
							<th width="6%"><div class="th-inner">CODIGO</div></th>
							<th width="48%"><div class="th-inner">DESCRIPCIÓN</div> </th>
							<th width="25%"><div class="th-inner">CANTIDAD</div></th>
						</tr>
			
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT factulinea.codigo, factulinea.codfactura, factulinea.cantidad, factulinea.detalles, facturas.moneda, facturas.codfactura, facturas.fecha,
						 articulos.codarticulo,articulos.codigobarras, articulos.descripcion, articulos.referencia, sum(factulinea.cantidad) as total FROM factulinea 
						 INNER JOIN facturas ON facturas.codfactura=factulinea.codfactura INNER JOIN articulos ON factulinea.codigo=articulos.codarticulo  WHERE ".$where;
						
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) {
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }		 
						?>								 
						<tr class="<?php echo $fondolinea?>">
						<?php if ($codigobarras!='') { ?> 
							<td width="6%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codigobarras");?></div></td>
						<?php } else { ?>
							<td width="6%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "referencia");?></div></td>
						<?php } ?>
							<td width="48%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "descripcion");
							 if(mysqli_result($res_resultado, $contador, "detalles")!='') { echo " - ". mysqli_result($res_resultado, $contador, "detalles");}?></div></td>
							<td class="aDerecha" width="25%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "total");?></div></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n proveedor que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>				
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
