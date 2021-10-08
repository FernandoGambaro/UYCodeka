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

$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];


include ("../conectar.php");
include("../common/verificopermisos.php");
////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 
////header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");

$codcliente=$_POST["codcliente"];
$nombre=$_POST["nombre"];
$codncredito=$_POST["codncredito"];

$fechainicio=$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND ncreditos.codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND (clientes.nombre LIKE '%$nombre%' OR clientes.apellido LIKE '%$nombre%' OR clientes.empresa LIKE '%$nombre%' )"; }
if ($codncredito <> "") { $where.=" AND ncreditos.codncredito='$codncredito'"; }
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

$where.=" ORDER BY ncredito.codncredito DESC";
$query_busqueda="SELECT count(*) as filas FROM ncredito,clientes WHERE ncredito.borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");



?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
		
<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";
var idstyle='';
var indiaux='';
var inicio='';
		
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
      oidd='#n'+this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo);    
		parent.document.getElementById("selid").value=list;	
   });   
   
});

</script>	
	
		<script language="javascript">
		
		function modificar_ncredito(codncredito) {
				var url="modificar_ncredito.php?codncredito=" + codncredito;
				window.parent.OpenNote(url,"99%", "99%");
		}
			
		function eliminar_ncredito(codncredito) {
			var url="eliminar_ncredito.php?codncredito=" + codncredito + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='870px';
			var h='390px';
			window.parent.OpenNote(url,w,h);
		}	

		function enviar_ncredito(codncredito) {
		var windowObjectReference;
			windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=120,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_ncredito.php?codncredito="+codncredito+"&envio=1"}, 1000);					
		}		
				
		function inicio(){
			
			parent.document.getElementById("TotalPesos").value = document.getElementById("TotalPesos").value;
			parent.document.getElementById("TotalDolar").value = document.getElementById("TotalDolar").value;

			var list=parent.document.getElementById("selid").value;
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= <?php echo $paginacion;?>) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-<?php echo $paginacion;?>;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador+<?php echo $paginacion;?>;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+<?php echo $paginacion;?>-1)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+<?php echo $paginacion;?>-1);
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
					parent.document.getElementById("nextpagina").value = contador+<?php echo $paginacion;?>;

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
			idstyle=list;
			var el = document.getElementById(list);
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
			<div class="header" style="width:100%;position: fixed;">Listado de Notas de Crédito </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">			
			
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
					<thead>			
						<tr class="cabeceraTabla">
							<th width="3%"><div class="th-inner">&nbsp;Nº. Crédito</div></th>
							<th width="8%"><div class="th-inner">FECHA</div></th>							
							<th width="60%"><div class="th-inner">CLIENTE </div></th>							
							<th width="7%"><div class="th-inner">MONEDA</div></th>
							<th width="9%"><div class="th-inner">IMPORTE</div></th>
							<th colspan="3" width="1%"><div class="th-inner">&nbsp;</div></th>
						</tr>
					</thead>
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">


				<?php $iniciopagina=$_POST["iniciopagina"];
						$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
						$moneda = array(1=>"\$", 2=>"U\$S");
					$leer=verificopermisos('ventas', 'leer', $UserID);
					$escribir=verificopermisos('ventas', 'escribir', $UserID);
					$modificar=verificopermisos('ventas', 'modificar', $UserID);
					$eliminar=verificopermisos('ventas', 'eliminar', $UserID);
							
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php 
							$sel_resultado="SELECT * FROM ncredito,clientes WHERE ncredito.borrado=0 AND ncredito.codcliente=clientes.codcliente AND ".$where;
							$sel_resultado=$sel_resultado."  limit ".$iniciopagina.",". $paginacion;;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;					   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codncredito");?>"	 class="<?php echo $fondolinea?> trigger">
							<td width="8%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codncredito");?></div></td>
							<td class="aDerecha" width="10%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>

							<?php

							 if (mysqli_result($res_resultado, $contador, "empresa")!='') {?>
							<td width="30%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "empresa")?></div></td>
							<?php } elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {?>
							<td width="30%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?></div></td>
							<?php } else { ?>
							<td width="30%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?>
							 <?php echo mysqli_result($res_resultado, $contador, "apellido")?></div></td>
							<?php }
							$sql_clientes=''; ?>
							<td width="9%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?></div></td>
							<td width="9%" style="background-color: red;"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "total"),2,",",".");?></div></td>
							
							<?php if ( $modificar=="true") { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_ncredito(<?php echo mysqli_result($res_resultado, $contador, "codncredito")?>)" title="Modificar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>
							
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobre.png" width="16" height="16" border="0" onClick="enviar_ncredito(<?php echo mysqli_result($res_resultado, $contador, "codncredito")?>);" title="Enviar al cliente"></a></div></td>
							<?php if ( $eliminar=="true") { ?>
							<td ><div align="right"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_ncredito(<?php echo mysqli_result($res_resultado, $contador, "codncredito")?>);" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>	
							
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else {  ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje">No hay ninguna nota de crédito que cumpla con los criterios de b&uacute;squeda-</td>
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
