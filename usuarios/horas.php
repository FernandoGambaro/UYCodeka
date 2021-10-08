<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
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

//header('Content-Type: text/html; charset=UTF-8'); 


$codusuarios=$_POST["usuarios"];

$where="1=1";
if ($codusuarios <> "") { $where.=" AND codusuarios='$codusuarios'"; }

$query_busqueda="SELECT count(*) as filas FROM horariousuario WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");
$where.=" ORDER BY vigencia DESC , anio DESC , diasemana ASC";

?>
<html>
	<head>
		<title>Listado de horas</title>
		<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var oldstyle='';
var idstyle='';
var indiaux='';

$(document).ready(function()
{
$("form:not(.filter) :input:visible:enabled:first").focus();

$('.trigger').click(function(e){

  	if (idstyle!="") {	
		var el = document.getElementById(idstyle);
		el.setAttribute('style', oldstyle);    	
  	}
      list=this.id;
		oldstyle = $(this).prop('style');
      idstyle=this.id;
		var el = document.getElementById(list);
		el.setAttribute('style', estilo); 
		parent.document.getElementById("selid_sec").value=list;
		parent.document.getElementById("stylesel_sec").value=oldstyle;		     
   }); 
});

</script>		
		
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
		var el = document.getElementById(list);
		el.setAttribute('style', estilo); 
		parent.document.getElementById("selid_sec").value=list;     
   }); 
});

</script>		
		
		<script language="javascript">

		function inicio() {
			var list=parent.document.getElementById("selid_sec").value;
			var paginacion=<?php echo $paginacion;?>;
			var numfilas=document.getElementById("numfilas_sec").value;
			var indi=parent.document.getElementById("iniciopagina_sec").value;
			var contador=1;
			var indice=0;
			var indiaux=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= paginacion) {
					parent.document.getElementById("nextdisab_sec").style.display = 'block';
					parent.document.getElementById("lastdisab_sec").style.display = 'block';
					parent.document.getElementById("last_sec").style.display = 'none';
					parent.document.getElementById("next_sec").style.display = 'none';
			}
			$('#filas_sec', window.parent.document).val(numfilas);
			$('#paginas', window.parent.document).html('');
			//parent.document.filas_sec.value=numfilas;
			//parent.document.paginas.innerHTML="";
			$('#prevpagina_sec', window.parent.document).val(contador-paginacion);
			//parent.document.getElementById("prevpagina_sec").value = contador-paginacion;
			parent.document.getElementById("currentpage_sec").value = indice+1;
			parent.document.getElementById("nextpagina_sec").value = contador + paginacion;
			numfilas=Math.abs(numfilas);
			while (contador<=numfilas) {
				if (parseInt(contador+paginacion-1)>numfilas) {
					texto=contador + " al " + parseInt(numfilas);
				} else {
					texto=contador + " al " + parseInt(contador+paginacion-1);
				}
				if (parseInt(indi)==parseInt(contador)) {
					if (indi==1) {
					parent.document.getElementById("first_sec").style.display = 'none';
					parent.document.getElementById("prev_sec").style.display = 'none';
					parent.document.getElementById("firstdisab_sec").style.display = 'block';
					parent.document.getElementById("prevdisab_sec").style.display = 'block';
					} else {
					parent.document.getElementById("first_sec").style.display = 'block';
					parent.document.getElementById("prev_sec").style.display = 'block';
					parent.document.getElementById("firstdisab_sec").style.display = 'none';
					parent.document.getElementById("prevdisab_sec").style.display = 'none';
					}
					parent.document.getElementById("prevpagina_sec").value = contador-paginacion;
					parent.document.getElementById("currentpage_sec").value = indice + 1;
					parent.document.getElementById("nextpagina_sec").value = contador + paginacion;

					parent.document.form_horas.paginas_sec.options[indice]=new Option (texto,contador);
					parent.document.form_horas.paginas_sec.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_horas.paginas_sec.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina_sec").value = contador;
				}
				indice++;
				contador=Math.abs(contador+paginacion);
			}	

					if (parseInt(indiaux) == parseInt(indice)-1 ) {
					parent.document.getElementById("nextdisab_sec").style.display = 'block';
					parent.document.getElementById("lastdisab_sec").style.display = 'block';
					parent.document.getElementById("last_sec").style.display = 'none';
					parent.document.getElementById("next_sec").style.display = 'none';
					} else {
					parent.document.getElementById("nextdisab_sec").style.display = 'none';
					parent.document.getElementById("lastdisab_sec").style.display = 'none';
					parent.document.getElementById("last_sec").style.display = 'block';
					parent.document.getElementById("next_sec").style.display = 'block';
					}
			
			list=parent.document.getElementById("selid_sec").value;
			idstyle=list;
			var el = document.getElementById(list);
			if (!document.getElementById(list)) {
			idstyle='';
			oldstyle='';
			} else {
			el.setAttribute('style', estilo);   
			oldstyle=parent.document.getElementById("stylesel_sec").value;
			}
		}	
		</script>
<script type="text/javascript" >
		function modificar_horas(codhorarios) {
			var url="modificar_horas.php?codhorarios=" + codhorarios;
			window.parent.OpenNote(url);
		}
		function eliminar_horas(codhorarios) {
			var url="eliminar_horas.php?codhorarios=" + codhorarios;
			window.parent.OpenNote(url);
		}
		function nuevo_horas(codhorarios) {
			var url="nuevo_horas.php?codusuarios=" + <?php echo $codusuarios;?>;
			window.parent.OpenNote(url);
		}				
</script>
	</head>

	<body onload="inicio();">	
		<div id="pagina">
			<div id="zonaContenido">
			<div align="center">
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">	LISTADO DE horas </div>
				<div class="fixed-table-container">
					<div class="header-background cabeceraTabla"> </div>      			
					<div class="fixed-table-container-inner">
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=2 border=0 id="navigate" style="font-size: 100%;">
					<thead>
					  <tr>			
							<th align="left"><div class="th-inner">&nbsp;Año</div></th>
							<th align="left"><div class="th-inner">Día semana</div></th>
							<th align="center"><div class="th-inner">Hora ingreso</div></th>
							<th align="center"><div class="th-inner">Hora salida</div></th>
							<th  width="23%" align="center"><div class="th-inner">Min. Tot. puente/descanso</div></th>
							<th align="center"><div class="th-inner">Vigencia</div></th>
							<th colspan="3" align="center"><div class="th-inner" >&nbsp;ACCIÓN</div></th>
						</tr>
					</thead>
		<tbody>
			<input type="hidden" name="numfilas_sec" id="numfilas_sec" value="<?php echo $filas;?>">
				<?php 
				$dias=array(1=>'Lun', 2=>'Mar', 3=>'Mié', 4=>'Jue', 5=>'Vie', 6=>'Sáb',7=>'Dom' );
				$iniciopagina=isset($_POST['iniciopagina_sec']) ? $_POST['iniciopagina_sec'] : null ;

				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina_sec"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php
							$sel_resultado="SELECT * FROM horariousuario WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",". $paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								 ?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codhorarios");?>"	 class="<?php echo $fondolinea?> trigger">
							<td class="aDerecha" width="13%"><div align="center" ><?php echo mysqli_result($res_resultado, $contador, "anio");?></div></td>
							<td class="aDerecha" width="13%"><div align="center">
							<?php
							$diasemana=explode('-',mysqli_result($res_resultado, $contador, "diasemana"));
							$mostrardia='';
							    for($i=1; $i <=7; $i++)
    								{  
									if(in_array($i, $diasemana)) {
										$mostrardia.= $dias[$i]. " ";
									}
								}
							 echo $mostrardia;?>
							</div></td>
							<td class="aDerecha"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "horaingreso");?></div></td>
							<td class="aDerecha"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "horasalida");?></td>
							<td class="aDerecha"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "descanso");?></td>
							<td class="aDerecha"><div align="center" ><?php echo implota(mysqli_result($res_resultado, $contador, "vigencia"));?></div></td>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_horas(<?php echo mysqli_result($res_resultado, $contador, "codhorarios")?>)" title="Modificar"></a></div></td>
							<td><div align="center"><a href="#">	<img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_horas(<?php echo mysqli_result($res_resultado, $contador, "codhorarios")?>)" title="Eliminar"></a></div></td>
							<?php if($contador==0) { ?>
							<td><div align="center"><a href="#">
							<img id="botonBusqueda" src="../img/plus.png" width="16" height="16" border="0" onClick="nuevo_horas();" title="Nuevo horas"></a></div></td>
							<?php  }else { ?>
							<td><div align="center"></div></td>						
							<?php } ?>
						</tr>
						<?php $contador++;
							}
						?>	
						</tbody>								
					</table>
					<?php } else { ?>
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
						<tr class="itemParTabla">
							<td class="aDerecha" width="13%"></div></td>
							<td class="aDerecha" width="13%"><div align="center"></div></td>
							<td class="aDerecha" width="19%"><div align="center"></td>
							<td class="aDerecha" width="19%"><div align="center" ></div></td>
							<td><div align="center"></div></td>
							<td><div align="center"></div></td>
							<td><div align="center"><a href="#">
							<img id="botonBusqueda" src="../img/plus.png" width="16" height="16" border="0" onClick="nuevo_horas()" title="Nuevo horas"></a></div></td>
						</tr>
					</table>					
					<?php } ?>					
				</div>
			</div>
		  </div>	</div></div>		
		</div>
	</body>
</html>