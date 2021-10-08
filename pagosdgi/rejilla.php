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
if($paginacion==0) {
	$paginacion=20;
}
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php");

$fecha=@$_POST["fecha"];
$mes=@$_POST["mes"];
$anio=@$_POST["anio"];
$cadena_busqueda=@$_POST["cadena_busqueda"];


$where="1=1";
if ($fecha <> "") { $where.=" AND fecha='$fecha'"; }
if ($anio <> "") { $where.=" AND anio='$anio'"; }
if ($mes <> "") { $where.=" AND mes='$mes'"; }

$where.=" ORDER BY anio DESC, mes DESC";
$query_busqueda="SELECT count(*) as filas FROM pagodgi WHERE ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Pago DGI</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
		<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="js/message.js" type="text/javascript"></script>

    <script src="js/jquery.msgBox.js" type="text/javascript"></script>
    <link href="js/msgBoxLight.css" rel="stylesheet" type="text/css">		
<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var idstyle='';

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
		
		function ver_pagodgi(codpagodgi) {
			var url="ver_pago.php?codpagodgi=" + codpagodgi + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url,600,300);
		}
		
		function modificar_pagodgi(codpagodgi) {
			var url="modificar_pago.php?codpagodgi=" + codpagodgi + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url,600,300);
		}
		
		function eliminar_pagodgi(codpagodgi) {
			var url="eliminar_entidad.php?codpagodgi=" + codpagodgi + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url);
		}

		function inicio(){		
			var list=parent.document.getElementById("selid").value;
			var numfilas=parseInt(document.getElementById("numfilas").value);
			var paginacion=parseInt(<?php echo $paginacion;?>);
			var indi=parseInt(parent.document.getElementById("iniciopagina").value);
			var contador=1;
			var indice=0;
			var indiaux=0;			
			
			if (indi>(numfilas)) { 
				indi=1; 
			}
			if ((numfilas) <= paginacion) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-paginacion;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + paginacion;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (contador+paginacion-1>numfilas) {
					
				}
				texto=contador + " al " + (contador+paginacion-1);
				if (indi==contador) {
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

					if (indiaux == (indice-1) ) {
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
			<div class="header" style="width:100%;position: fixed;">Listado de Pagos a DGI </div>
			<div class="fixed-table-container" >
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>
						<tr class="cabeceraTabla">
							<th><div class="th-inner">Fecha Pago</div></th>
							<th><div class="th-inner">MES</div></th>
							<th><div class="th-inner">AÑO</div></th>
							<th><div class="th-inner">IRAE - Anticipo</div></th>
							<th><div class="th-inner">IMP. AL PAT.</div></th>
							<th><div class="th-inner">IVA - No CEDE</div></th>
							<th><div class="th-inner">ICOSA</div></th>
							<th><div class="th-inner">TOTAL</div></th>
							<th colspan="3" width="2%"><div class="th-inner">ACCIÓN&nbsp;</div></th>
						</tr>
					</thead>			
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM pagodgi WHERE ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codpagodgi");?>"	 class="<?php echo $fondolinea?> trigger">
							<td><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td><div><?php echo genMonth_Text(mysqli_result($res_resultado, $contador, "mes"));?></div></td>
							<td><div align="center"><?php echo mysqli_result($res_resultado, $contador, "anio");?></div></td>
							<td><div align="right"><?php echo mysqli_result($res_resultado, $contador, "f108");?></div></td>
							<td><div align="right"><?php echo mysqli_result($res_resultado, $contador, "f328");?></div></td>
							<td><div align="right"><?php echo mysqli_result($res_resultado, $contador, "f546");?></div></td>
							<td><div align="right"><?php echo mysqli_result($res_resultado, $contador, "f606");?></div></td>
							<td><div align="right"><?php echo (mysqli_result($res_resultado, $contador, "f606")+mysqli_result($res_resultado, $contador, "f546")+mysqli_result($res_resultado, $contador, "f328")+mysqli_result($res_resultado, $contador, "f108"));?></div></td>
							<td><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_pagodgi(<?php echo mysqli_result($res_resultado, $contador, "codpagodgi")?>)" title="Modificar"></a></td>
							<td><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_pagodgi(<?php echo mysqli_result($res_resultado, $contador, "codpagodgi")?>)" title="Visualizar"></a></td>
							<td><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_pagodgi(<?php echo mysqli_result($res_resultado, $contador, "codpagodgi")?>)" title="Eliminar"></a></td>
						</tr>
						<?php $contador++;
							}
						?>			
					</table>
					<?php } else { ?>
					<table class="fuente8" width="50%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="100%" class="mensaje"><?php echo "No hay ninguna entidad bancaria que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>			
		</div>
		</div>
	</body>
</html>
<?php
((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
?>