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
require_once('../classes/class_session.php');
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
$Seleccionados=array();
$Seleccionados=@$s->data['Selected'];


include ("../conectar.php");
include ("../common/verificopermisos.php");
//header('Cache-Control: no-cache');
//header('Pragma: no-cache'); 
//header('Content-Type: text/html; charset=UTF-8'); 
include ("../common/funcionesvarias.php");
include ("../funciones/fechas.php");


$a="";
$data=array();
$a=isset($_GET['a']) ? $_GET['a'] : null ;
$a=array_recibe($a);

if(is_array($a) && !empty($a)){
	/*/echo "veo array()<br>";*/
	
	foreach ($a as $key => $value)
	{
		$temp = is_array($value) ? $value : trim($value);
	    $$key = $temp;
	    $data[$$key]=$temp;
	}
	$data=$a;
} else { 

$codcliente=$data['codcliente']=isset($_GET['codcliente']) ? $_GET['codcliente'] : $_POST["codcliente"] ;
$codrecibo=$data['codrecibo']=isset($_GET['codrecibo']) ? $_GET['codrecibo'] : $_POST["codrecibo"];
$fechainicio=$data['fechainicio']=isset($_GET['fechainicio']) ? $_GET['fechainicio'] : $_POST["fechainicio"] ;
$fechafin=$data['fechafin']=isset($_GET['fechafin']) ? $_GET['fechafin'] : $_POST["fechafin"] ;
$moneda=$data['moneda']=isset($_GET['moneda']) ? $_GET['moneda'] : $_POST["moneda"] ;

$cadena_busqueda=$data['cadena_busqueda']=isset($_GET['cadena_busqueda']) ? $_GET['cadena_busqueda'] : $_POST["cadena_busqueda"];
$iniciopagina=$data['iniciopagina']=isset($_GET['iniciopagina']) ? $_GET['iniciopagina'] : $_POST["iniciopagina"];
}

$data=array_envia($data); 

$ordenado=isset($_GET['ordenado']) ? $_GET['ordenado'] : 'recibos.fecha' ;
$orden=isset($_GET['orden']) ? $_GET['orden'] : 'DESC' ;

$ordeno=explode('-', $ordenado);
foreach($ordeno as $x){
	if($x!='') {
		$ordenado=$x;
		break;
	}
}
if($orden=="ASC"){
	$neworden="DESC";
} else {
	$neworden="ASC";
}
if ($fechainicio<>"") { $fechainicio=explota($fechainicio); }
if ($fechafin<>"") { $fechafin=explota($fechafin); }

$where="1=1";
if ($codcliente <> "") { $where.=" AND recibos.codcliente='$codcliente'"; }
if ($codrecibo <> "") { $where.=" AND recibos.codrecibo='$codrecibo'"; }
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
if ($moneda <> "") { $where.=" AND recibos.moneda='$moneda'"; }

$where.=" ORDER BY ".$ordenado." ".$orden." ";

$query_busqueda="SELECT count(*) as filas FROM recibos,clientes WHERE recibos.borrado=0 AND recibos.codcliente=clientes.codcliente AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

/*Calculo saldo por cobrar en Pesos*/
$queryFacturado="SELECT SUM( round(totalfactura,0) ) AS Total FROM facturas WHERE facturas.borrado=0 AND facturas.tipo<=1 AND facturas.moneda=1";
$rs_Facturado=mysqli_query($GLOBALS["___mysqli_ston"], $queryFacturado);
$TotalPesos=mysqli_result($rs_Facturado, 0, "Total");
    
$queryCobrado="SELECT SUM(round( importe ,0)) AS Cobrado FROM recibos WHERE recibos.moneda=1";
$rs_Cobrado=mysqli_query($GLOBALS["___mysqli_ston"], $queryCobrado);
$TotalPesos=number_format($TotalPesos-mysqli_result($rs_Cobrado, 0, "Cobrado") ,2,",",".");

/*Calculo saldo por cobrar en Dolares*/
$queryFacturado="SELECT SUM(round( totalfactura,0) ) AS Total FROM facturas WHERE facturas.borrado=0 AND facturas.tipo<=1 AND facturas.moneda=2";
$rs_Facturado=mysqli_query($GLOBALS["___mysqli_ston"], $queryFacturado);
$TotalDolar=mysqli_result($rs_Facturado, 0, "Total");
    
$queryCobrado="SELECT SUM( round(importe,0) ) AS Cobrado FROM recibos WHERE recibos.moneda=2";
$rs_Cobrado=mysqli_query($GLOBALS["___mysqli_ston"], $queryCobrado);
$TotalDolar=number_format($TotalDolar-mysqli_result($rs_Cobrado, 0, "Cobrado") ,2,",",".");

?>
<html>
	<head>
		<title>Clientes</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>

    <script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
    <link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

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
		
		function ver_recibo(codrecibo) {
			var url="ver_recibo.php?codrecibo=" + codrecibo;
			var w='950';
			var h='500';
			window.parent.OpenNote(url,w,h);
		}		
		
		function modificar_recibo(codrecibo,emitido) {
			if (emitido!=1) {
				var url="modificar_recibo.php?codrecibo=" + codrecibo;
				var w='99%';
				var h='99%';
				window.parent.OpenNote(url,w,h);
			} else {
				$.msgBox({
				    title: "Alerta",
				    content: "Quiere modificar recibo emitido?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});									    	
    										if (v=="1234") {
												var url="modificar_recibo.php?codrecibo=" + codrecibo;
												var w='99%';
												var h='99%';
												window.parent.OpenNote(url,w,h);
											} else {
												showWarningToast('Contraseña erronea');
											}
										}
								});					        	
				        }
				    }
				});	
			}
		}		
		
			
		function eliminar_recibo(codrecibo) {
			var url="eliminar_recibo.php?codrecibo=" + codrecibo + "&cadena_busqueda=<?php echo $cadena_busqueda?>";
			var w='870px';
			var h='390px';
			window.parent.OpenNote(url,w,h);
		}	

		function enviar_recibo(codrecibo,estado) {
		var windowObjectReference;
			if (estado!=1) {
			windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=120,right=300,top=200");
			setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_recibo.php?codrecibo="+codrecibo+"&envio=1"}, 1000);
			} else {
					$.msgBox({
				    title: "Alerta",
				    content: "Quiere reenviar recibo?",
				    type: "confirm",
				    buttons: [{ value: "Si" }, { value: "Cancelar"}],
				    success: function (result) {
				        if (result == "Si") {
								$.msgBox({ type: "prompt",
								    title: "Autorización",
								    inputs: [
								    { header: "Contraseña", type: "password", name: "password" }],
								    buttons: [
								    { value: "Aceptar" }, { value:"Cancelar" }],
								    success: function (result, values) {
											$(values).each(function (index, input) {
                     					v =  input.value ;
                 						});									    	
    										if (v=="1234") {
												windowObjectReference = window.open('../enviomail/envia.php',"EnvioMail", "resizable,scrollbars,status, width=300,height=120,right=300,top=200");			
												setTimeout(function() {windowObjectReference.location.href="../fpdf/imprimir_recibo.php?codrecibo="+codrecibo+"&envio=1"}, 1000);
											} else {
												showWarningToast('Contraseña erronea');
											}
										}
								});					        	
				        }
				    }
				});
			}					
		}		
				
		function inicio(){
			
			parent.document.getElementById("TotalPesos").innerHTML = document.getElementById("TotalPesos").value;
			parent.document.getElementById("TotalDolar").innerHTML = document.getElementById("TotalDolar").value;

			var list=parent.document.getElementById("selid").value;
			var numfilas=document.getElementById("numfilas").value;
			var paginacion=<?php echo $paginacion;?>;
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
			parent.document.form_busqueda.filas.value=numfilas;
			parent.document.form_busqueda.paginas.innerHTML="";

			parent.document.getElementById("prevpagina").value = contador-paginacion;
			parent.document.getElementById("currentpage").value = indice+1;
			parent.document.getElementById("nextpagina").value = contador+paginacion;
			numfilas=Math.abs(numfilas);
			while (contador<=parseInt(numfilas)) {

				texto=contador + " al " + parseInt(contador+paginacion-1);
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
					parent.document.getElementById("nextpagina").value = contador+paginacion;
					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.form_busqueda.paginas.options[indice].selected=true;
					indiaux=	indice;				
				} else {
					parent.document.form_busqueda.paginas.options[indice]=new Option (texto,contador);
					parent.document.getElementById("lastpagina").value = contador;
				}
				indice++;
				contador=contador+paginacion;
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
			<div class="header" style="width:100%;position: fixed; font-size: 140%;">Listado de RECIBOS </div>
			<div class="fixed-table-container">
      		<div class="header-background cabeceraTabla"> </div>      			
			<div class="fixed-table-container-inner">			
			
					<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 style="font-size: 130%;">
					<thead>			
						<tr class="cabeceraTabla">
							<th width="8%"><div class="th-inner">FECHA
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=recibos.fecha&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC" and $ordenado=="recibos.fecha") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>							</div></th>
							<th width="5%"><div class="th-inner">Nº.&nbsp;
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=recibos.codrecibo&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC" and $ordenado=="recibos.codrecibo") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>							</div></th>							
							</div></th>							
							<th width="48%"><div class="th-inner">CLIENTE 
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=recibos.codcliente&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC" and $ordenado=="recibos.codcliente") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>							</div></th>
							</div></th>							
							<th width="8%"><div class="th-inner">MON.
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?a=<?php echo $data;?>&ordenado=recibos.moneda&orden=<?php echo $neworden;?>">
							<?php if($orden=="ASC" and $ordenado=="recibos.moneda") { ?>
							<i class="fa fa-caret-down" aria-hidden="true"></i>
							<?php } else { ?>
							<i class="fa fa-caret-up" aria-hidden="true"></i>
							<?php } ?>
							</a>							</div></th>
							<th width="9%"><div class="th-inner">IMPORTE</div></th>
							<th colspan="3"><div class="th-inner">&nbsp;</div></th>
						</tr>
					</thead>
			<form name="form1" id="form1">
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas;?>">
			<input type="hidden" name="TotalPesos" id="TotalPesos" value="<?php echo $TotalPesos;?>">
			<input type="hidden" name="TotalDolar" id="TotalDolar" value="<?php echo $TotalDolar;?>">

				<?php 
						$tipo = array( 0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
						$moneda = array();
					$leer=verificopermisos('tesoreria', 'leer', $UserID);
					$escribir=verificopermisos('tesoreria', 'escribir', $UserID);
					$modificar=verificopermisos('tesoreria', 'modificar', $UserID);
					$eliminar=verificopermisos('tesoreria', 'eliminar', $UserID);
							
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) { ?>
						<?php $sel_resultado="SELECT * FROM recibos,clientes WHERE recibos.borrado=0 AND recibos.codcliente=clientes.codcliente AND ".$where;
							$sel_resultado=$sel_resultado."  limit ".$iniciopagina.",". $paginacion;
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
						 	 $moneda[$xmon]= $descripcion[0];
						 	 $con_monedas++;
						 	 $xmon++;
						 }						   
						   			   
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }
								?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codrecibo");?>"	 class="<?php echo $fondolinea?> trigger">
							<td class="aDerecha" width="8%"><div align="center"><?php echo implota(mysqli_result($res_resultado, $contador, "fecha"));?></div></td>
							<td width="10%"><div align="center"><?php echo mysqli_result($res_resultado, $contador, "codrecibo");?></div></td>
							<?php
							 if (mysqli_result($res_resultado, $contador, "empresa")!='') {?>
							<td width="58%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "empresa")?></div></td>
							<?php } elseif (mysqli_result($res_resultado, $contador, "apellido")=='') {?>
							<td width="58%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?></div></td>
							<?php } else { ?>
							<td width="58%"><div align="left"><?php echo mysqli_result($res_resultado, $contador, "nombre");?>
							 <?php echo mysqli_result($res_resultado, $contador, "apellido")?></div></td>
							<?php }
							$sql_clientes=''; ?>
							<td width="8%"><div align="center"><?php echo $moneda[mysqli_result($res_resultado, $contador, "moneda")];?></div></td>
							<td width="8%"><div align="center"><?php echo number_format(mysqli_result($res_resultado, $contador, "importe"),2,",",".");?></div></td>
							
							<?php if ( $modificar=="true") { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_recibo('<?php echo mysqli_result($res_resultado, $contador, "codrecibo")?>','<?php echo mysqli_result($res_resultado, $contador, "emitido");?>')" title="Modificar"></a></div></td>
							<?php } else { ?>
							<td>&nbsp;</td>
							<?php } if ( $leer=="true") { ?>								
							<td ><div align="center"><a href="#"><img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_recibo(<?php echo mysqli_result($res_resultado, $contador, "codrecibo")?>)" title="Ver"></a></div></td>
							<?php } else { ?>
							<td>&nbsp;</td>
							<?php }
							if (mysqli_result($res_resultado, $contador, "enviado")!=1) { ?>								
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobre.png" width="16" height="16" border="0" onClick="enviar_recibo('<?php echo mysqli_result($res_resultado, $contador, "codrecibo")?>','0');" title="Enviar al cliente"></a></div></td>
							<?php } else { ?>
							<td><div align="center"><a href="#"><img id="botonBusqueda" src="../img/sobreok.png" width="16" height="16" border="0" onClick="enviar_recibo('<?php echo mysqli_result($res_resultado, $contador, "codrecibo")?>','1');" title="Enviado al cliente"></a></div></td>
							<?php } if ( $eliminar=="true") { ?>
							<td ><div align="right"><a href="#"><img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_recibo(<?php echo mysqli_result($res_resultado, $contador, "codrecibo")?>);" title="Eliminar"></a></div></td>
							<?php } else { ?>
							<td>&nbsp;</td>
							<?php } ?>	
							
						</tr>
						<?php $contador++;
							}
						 } else {  ?>

							<td colspan="10" width="100%" class="mensaje"><?php echo "No hay ninguna factura que cumpla con los criterios de b&uacute;squeda-";?></td>
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
