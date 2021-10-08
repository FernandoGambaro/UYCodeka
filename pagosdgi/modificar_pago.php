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
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 

$codpagodgi=$_GET["codpagodgi"];
$cadena_busqueda=$_GET['cadena_busqueda'];

$query="SELECT * FROM pagodgi WHERE codpagodgi='$codpagodgi'";
$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query);
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />
		<script type="text/javascript" src="../funciones/validar.js"></script>
    		
			<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">
		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}

		
		</script>
<script type="text/javascript">
function  actualizar() {
	var f108=document.getElementById('f108').value;
	var f328=document.getElementById('f328').value;
	var f546=document.getElementById('f546').value;
	var f606=document.getElementById('f606').value;
	document.getElementById('total').value=parseFloat(f108)+parseFloat(f328)+parseFloat(f546)+parseFloat(f606);
	
}
</script>		
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">MODIFICAR PAGO DGI </div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_pago.php">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td>Fecha</td><td>
							<?php $hoy=implota(date("Y-m-d"));?>
						    <input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10"  value="<?php echo implota(mysqli_result($rs_query, 0, "fecha"));?>" readonly> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
						<td>&nbsp;</td>
						</tr>					
						<tr>
							<td>Año</td>
						    <td><select name="anio" id="anio" class="cajaPequena2">
						    <?php 
						    for ($x=2010; $x<=2020; $x++){
						    	if($x==mysqli_result($rs_query, 0, "anio")) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo $x;?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo $x;?></option>
								<?php	
								}					    
						    }
						    ?>
						    </select>
						   </td>
						   <td>Mes&nbsp;<select name="mes" id="mes" class="cajaPequena">
						    <?php 
						    for ($x=1; $x<=12; $x++){
						    	if ($x== mysqli_result($rs_query, 0, "mes")) {
								?>
								<option value="<?php echo $x;?>" selected><?php echo  $x." - ". genMonth_Text($x);?></option>
								<?php	
						    	} else {
								?>
								<option value="<?php echo $x;?>"><?php echo  $x." - ". genMonth_Text($x);?></option>
								<?php
								}						    
						    }
						    ?>
						    </select>						   
						   </td>
						</tr>	
						<tr><td>Código&nbsp;Imp.</td><td>Concepto                     </td><td>Importe</td></tr>
						<tr><td>108        </td><td>IRAE - Anticipo                  </td><td>
						<input name="f108" type="text" class="cajaTotales" id="f108" value="<?php echo mysqli_result($rs_query, 0, "f108");?>" onchange="actualizar();"></td></tr>
						<tr><td>328        </td><td>IMPUESTO AL PATRIMONIO - Anticipo</td><td>
						<input name="f328" type="text" class="cajaTotales" id="f328" value="<?php echo mysqli_result($rs_query, 0, "f328");?>" onchange="actualizar();"></td></tr>
						<tr><td>546        </td><td>IVA - Contribuyente No CEDE      </td><td>
						<input name="f546" type="text" class="cajaTotales" id="f546" value="<?php echo mysqli_result($rs_query, 0, "f546");?>" onchange="actualizar();"></td></tr>
						<tr><td>606        </td><td>ICOSA Anticipo                   </td><td>
						<input name="f606" type="text" class="cajaTotales" id="f606" value="<?php echo mysqli_result($rs_query, 0, "f606");?>" onchange="actualizar();"></td></tr>
						<tr><td>           </td><td><div align="right">Total</div>   </td><td>
						<input type="text" class="cajaTotales" id="total" value="<?php echo mysqli_result($rs_query, 0, "f606")+mysqli_result($rs_query, 0, "f546")+mysqli_result($rs_query, 0, "f328")+mysqli_result($rs_query, 0, "f108");?>" readonly></td></tr>
											
					</table>
			  </div>
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
					<input id="accion" name="accion" value="modificar" type="hidden">
					<input id="codpagodgi" name="codpagodgi" value="<?php echo mysqli_result($rs_query, 0, "codpagodgi");?>" type="hidden">
					<input id="id" name="id" value="" type="hidden">
			  </div>
			  </form>
			 </div>
		  </div>
		</div>
		<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
 	</div>
 	<script type="text/javascript">
 	actualizar();
 	</script>		
	</body>
</html>
