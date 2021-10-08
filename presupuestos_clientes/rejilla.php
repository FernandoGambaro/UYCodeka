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

include ("../conectar.php");
include("../common/verificopermisos.php");
//include("../common/funcionesvarias.php");

include ("../funciones/fechas.php");

//header('Content-Type: text/html; charset=UTF-8'); 
header('Content-Type: text/html; charset=ISO-8859-1'); 

$codcliente=@$_POST["codcliente"];
$nombre=@$_POST["nombre"];
$numpresupuesto=@$_POST["numpresupuesto"];
$estado=@$_POST["cboEstados"];
$fechainicio=@$_POST["fechainicio"];
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
$fechafin=@$_POST["fechafin"];
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codcliente <> "") { $where.=" AND presupuestos.codcliente='$codcliente'"; }
if ($nombre <> "") { $where.=" AND clientes.nombre like '%".$nombre."%'"; }
if ($numpresupuesto <> "") { $where.=" AND codpresupuesto='$numpresupuesto'"; }
if ($estado > "0") { $where.=" AND estado='$estado'"; }
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

$where.=" ORDER BY codpresupuesto DESC";
$query_busqueda="SELECT count(*) as filas FROM presupuestos,clientes WHERE presupuestos.borrado=0 AND presupuestos.codcliente=clientes.codcliente AND ".$where;

$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Presupuestos Clientes</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 		
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
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

		function ver_presupuesto(codpresupuesto) {
			var url="ver_presupuesto.php?codpresupuesto=" + codpresupuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var url="/fpdf/imprimir_presupuesto.php?codpresupuesto="+codpresupuesto;
			window.parent.OpenNote(url, "99%", "99%");
		}


		function imprimir_etiquetas(codpresupuesto) {
				window.open("../fpdf/codigocontinuo.php?codpresupuesto="+codpresupuesto);
		}

		function modificar_presupuesto(codpresupuesto,marcaestado) {
			if (marcaestado!=4) {
				var url="modificar_presupuesto.php?codpresupuesto=" + codpresupuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url,"99%", "99%");
			} else {
				alert ("No puede modificar un presupuesto emitido");
			}
		}
		function presentar_presupuesto(codpresupuesto,marcaestado) {
			if (marcaestado!=4) {
				var url="presentacion/?codpresupuesto=" + codpresupuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
				window.parent.OpenNote(url,"99%", "99%", true);
			} else {
				alert ("No puede modificar un presupuesto emitido");
			}
		}		
		
		function convertir_presupuesto(codpresupuesto,marcaestado) {
		  $.msgBox({
			 title: "Nueva factura",
		    type    : "prompt",
		    inputs  : [
		      {type: "text", maxlength: 30, name:"num", header: "Nº factura:", size: 10, required: true},
		      {type: "text", name: "fecha",	header: "Fecha:", maxlength: 15, size: 15, required: true}],
		    buttons : [
		      {value: "OK"}, {value: "Cancel"}],
		    success: function (result, values) {
					if (result=="OK") {
						var n='';
						var f='';
						$(values).each(function (index, input) {
               	 	if (input.name == "fecha") {
            	    	 f=input.value;
         	       	}         
      	          	if (input.name == "num") {
   	             	 n=input.value;
	                	}         
            		});
            		if (n !='' && f != ''){
            			var verifico='';
								$.post("verifico.php?num="+n, function(data){
								verifico=data;

								if (verifico=="false") {
	            				if (marcaestado < 5) {
	            					window.parent.OpenNote("convertir_presupuesto.php?codpresupuesto=" + codpresupuesto + "&cadena_busqueda=<?php echo $cadena_busqueda;?>&num="+n+"&fecha="+f,"99%","99%");
	            				} else {
										showWarningToast("No se puede facturar un presupuesto ya facturado");
									}
								} else {
										showWarningToast("No se puede facturar exite factura con ese número");
								}								
							});	
																
            			
						} else {
							showWarningToast('Falta un dato');	
						}
					}
				}
			});
		}		
		

		function eliminar_presupuesto(codpresupuesto) {
			var url="eliminar_presupuesto.php?codpresupuesto=" + codpresupuesto + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			window.parent.OpenNote(url,"99%", "99%");			
		}
		
		var windowObjectReference;
		function enviar_presupuesto(codpresupuesto) {
			windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=100,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_presupuesto.php?codpresupuesto="+codpresupuesto+"&envio=1"}, 1000);					
		}		

		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;
			var contador=1;
			var indice=0;
			var indiaux=0;
			if (parseInt(indi)>parseInt(numfilas)) { 
				indi=1; 
			}
			if (parseInt(numfilas) <= 10) {
					parent.document.getElementById("nextdisab").style.display = 'block';
					parent.document.getElementById("lastdisab").style.display = 'block';
					parent.document.getElementById("last").style.display = 'none';
					parent.document.getElementById("next").style.display = 'none';
			}
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-10;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador + 10;
			numfilas=Math.abs(numfilas);
			while (contador<=parseInt(numfilas)) {
				if (parseInt(contador+9)>numfilas) {
					
				}
				texto=contador + " al " + parseInt(contador+9);
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
					parent.document.getElementById("prevpagina").value = contador-10;
					parent.document.getElementById("currentpage").value = indice + 1;
					parent.document.getElementById("nextpagina").value = contador + 10;

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
					
				} else {

					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+10;
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
			
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">		Listado de Presupuestos </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">				

			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th width="40%"><div class="th-inner">CLIENTE</div></th>
							<th ><div class="th-inner">MONEDA</div></th>
							<th><div class="th-inner">IMPORTE</div></th>
							<th><div class="th-inner">FECHA</div></th>
							<th><div class="th-inner">ESTADO</div></th>
							<th colspan="6" align="center"><div class="th-inner">ACCIÓN</div></th>
						</tr>
					</thead>						
		
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php $iniciopagina=$_POST["iniciopagina"];
				$estadotipo = array( 0=>"&nbsp;", 1=>"Iniciado", 2=>"Enviado", 3=>"Rechazado", 4=>"Aprovado", 5=>"Facturado");
				$tipofa = array( 0=>"",   1=>"Pesos", 2=>"U\$S");
				if (empty($iniciopagina)) { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if (empty($iniciopagina)) { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php
						
							$leer=verificopermisos('presupuestos', 'leer', $UserID);
							$escribir=verificopermisos('presupuestos', 'escribir', $UserID);
							$modificar=verificopermisos('presupuestos', 'modificar', $UserID);
							$eliminar=verificopermisos('presupuestos', 'eliminar', $UserID);
						
						 $sel_resultado="SELECT codpresupuesto, clientes.nombre as nombre,presupuestos.fecha as fecha,totalpresupuesto,estado,presupuestos.moneda FROM presupuestos,clientes WHERE presupuestos.borrado=0 AND presupuestos.codcliente=clientes.codcliente AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",50";
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   $marcaestado=0;
						   while ($contador < mysqli_num_rows($res_resultado)) {
						   		$marcaestado=mysqli_result($res_resultado, $contador, "estado");
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
									$monedashow=$tipofa[mysqli_result($res_resultado, $contador, "moneda")];						
								?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto");?>"	 class="<?php echo $fondolinea?> trigger">
							<td><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?></div></td>
							<td><div align="right"><?php echo $monedashow;?></div></td>
							<td><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "totalpresupuesto"),2,",",".");?></div></td>
							<td class="aDerecha"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td><div align="center"><?php echo $estadotipo[mysqli_result($res_resultado, $contador, "estado")];?></div></td>
							<?php if ( $modificar=="true") { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>,<?php echo $marcaestado?>)" title="Modificar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>						
							<?php } ?>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/commercial.png" width="16" height="16" border="0" onClick="presentar_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>,<?php echo $marcaestado?>)" title="Presentar"></a></div></td>
							<?php if ( $leer=="true") { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>)" title="Visualizar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>							
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobre.png" width="16" height="16" border="0" onClick="enviar_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>)" title="Enviar al cliente"></a></div></td>
							<?php if ( $eliminar=="true") { ?>							
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>,<?php echo $marcaestado?>)" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td width="20px">&nbsp;</td>
							<?php } ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/convertir.png" width="16" height="16" border="0" onClick="convertir_presupuesto(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>,<?php echo $marcaestado?>)" title="Albaranar"></a></div></td>
<!--							<td width="5%"><div align="center"><a href="#"><img src="../img/imprimir.png" width="16" height="16" border="0" onClick="imprimir_etiquetas(<?php echo mysqli_result($res_resultado, $contador, "codpresupuesto")?>)" title="Imprimir etiquetas"></a></div></td> -->
						</tr>
						<?php $contador++;
							}
						?>
					</table>
					<?php } else { ?>

							<td colspan="11" width="100%" class="mensaje"><?php echo "No hay ning&uacute;n presupuesto que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					</table>
					<?php } ?>
				</div>
			</div></div>
		  </div>
		</div>
	</body>
</html>
