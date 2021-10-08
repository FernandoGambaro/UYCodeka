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
include("../common/verificopermisos.php");
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8'); 
include("../common/funcionesvarias.php");
include ("../funciones/fechas.php");

$codplan=$_POST["codplan"];
$nombre=$_POST["nombre"];

$i="";

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codplan <> "") { $where.=" AND codplan = '".$codplan."'"; }
if ($nombre <> "") { $where.=" AND nombre like '%".$nombre."%'"; }

//$where.=" ORDER BY codplan ASC";
$query_busqueda="SELECT count(*) as filas FROM plandecuentas WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
if($rs_busqueda!==FALSE) {
$filas=mysqli_result($rs_busqueda, 0, "filas");
}
?>
<html>
	<head>
		<title>Articulos</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
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
<script type="text/javascript">
$(document).ready( function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();



var headID = window.parent.document.getElementsByTagName("head")[0];         
var newScript = window.parent.document.createElement('script');
newScript.type = 'text/javascript';
newScript.src = '../js3/jquery.colorbox.js';
headID.appendChild(newScript);
});

</script>			
		<script language="javascript">
		
		function ver_plan(codplan) {
			var url="ver_plan.php?codplan=" + codplan;
			window.parent.OpenNote(url);
		}
		
		function modificar_plan(codplan) {
			var url="modificar_plan.php?codplan=" + codplan;
			window.parent.OpenNote(url);
		}
		
		function eliminar_plan(codplan) {
			var url="eliminar_plan.php?codplan=" + codplan;
			window.parent.OpenNote(url);
		}

		function inicio() {
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
			parent.document.getElementById("nextpagina").value = contador + <?php echo $paginacion;?>;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {

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
					
			list=parent.document.getElementById("selid").value;
			idstyle=list;
			var el = document.getElementById(list);
			if (!document.getElementById(list)) {
			idstyle='';
			oldstyle='';
			} else {
			el.setAttribute('style', estilo);   
			oldstyle=parent.document.getElementById("stylesel").value;
			}
		}
		</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
				<div class="header" style="width:100%;position: fixed; font-size: 140%;">Listado de Plan de Cuentas </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<td width="8%" align="left">CÓDIGO</td>
							<td width="55%" align="left">DESCRIPCIÓN </td>
							<td width="17%" align="left">M.</td>
							<td width="17%" align="left">Opera en</td>
							<td colspan="3">ACCIÓN</td>
						</tr>
				</thead>
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php  $sel_resultado="SELECT * FROM plandecuentas WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",". $paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
						   		if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla";	}?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codplan");?>"	 class="<?php echo $fondolinea?> trigger">
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "codplan");?></div></td>
							<td><div align="left"><?php 
							$largo = strlen(mysqli_result($res_resultado, $contador, "codplan"));
							$espacio='';
							for ($x=1;$x<=$largo;$x++){
								$espacio.='&#8212;&#8212;';
							}
							
							 echo $espacio.'&nbsp;'. mysqli_result($res_resultado, $contador, "nombre"); ?></div></td>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "m");?></div></td>
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "operaen");?></div></td>

							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_plan(<?php echo mysqli_result($res_resultado, $contador, "codplan")?>)" title="Modificar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_plan(<?php echo mysqli_result($res_resultado, $contador, "codplan")?>)" title="Visualizar"></a></div></td>
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_plan(<?php echo mysqli_result($res_resultado, $contador, "codplan")?>)" title="Eliminar"></a></div></td>

						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="87%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ning&uacute;n plan de cuenta que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
