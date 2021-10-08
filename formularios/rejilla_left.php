<?php
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
//header('Content-Type: text/html; charset=UTF-8'); 
include ("../common/funcionesvarias.php");
include "../feedback/parametros.php";

$codformularios=$_POST["codformulario"];
$descripcion=$_POST["descripcion"];
$cadena_busqueda=$_POST["cadena_busqueda"];

$where="1=1";
if ($codformularios <> "") { $where.=" AND codformularios='$codformularios'"; }
if ($descripcion <> "") { $where.=" AND descripcion like '%".$descripcion."%'"; }

$where.=" ORDER BY descripcion ASC";
$query_busqueda="SELECT count(*) as filas FROM formularios WHERE borrado=0 AND ".$where;
$rs_busqueda=mysqli_query($GLOBALS["___mysqli_ston"], $query_busqueda);
$filas=mysqli_result($rs_busqueda, 0, "filas");

?>
<html>
	<head>
		<title>Sectores</title>
	<link href="../css3/estilos.css" type="text/css" rel="stylesheet">
		<script src="../js3/jquery.min.js"></script>

		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js3/jquery.colorbox.js"></script>
		
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">	
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

<script type="text/javascript">
var estilo="background-color: #2d2d2d; border-bottom: 1px solid #000; color: #fff;";

var oldstyle='';
var idstyle='';

$(document).ready(function()
{

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
		parent.document.getElementById("selid").value=list;
		parent.document.getElementById("stylesel").value=oldstyle;			  
   }); 
});

</script>

<script language="javascript">
		var indiaux='';
		function ver_formularios(codformulario) {
			var url="../feedback/formulario_feedback.php?codformulario=" + codformulario+"&u=1";
			window.parent.OpenLeft(url,"100%","100%");			
		}
		
		function modificar_formularios(codformulario) {
			var url="modificar_formularios.php?codformulario=" + codformulario;
			window.parent.OpenLeft(url);			
		}
		
		function eliminar_formularios(codformulario) {
			var url="eliminar_formularios.php?codformulario=" + codformulario;
			window.parent.OpenLeft(url);			
		}

		function inicio() {
			var numfilas=document.getElementById("numfilas").value;
			var indi=parent.document.getElementById("iniciopagina").value;

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
			parent.document.form_busqueda.filas.value=numfilas;
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
				<div class="header">Listado de formularios </div>
			<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0>
			<thead>
						<tr class="cabeceraTabla">
							<td >Tipo</td>
							<td>Descripción </td>
							<td colspan="3" width="2%">ACCION&nbsp;</td>
						</tr>
			</thead>						
			<input type="hidden" name="numfilas" id="numfilas" value="<?php echo $filas?>">
				<?php				
				
				 $iniciopagina=@$_POST["iniciopagina"];
				if ($iniciopagina=='') { $iniciopagina=@$_GET["iniciopagina"]; } else { $iniciopagina=$iniciopagina-1;}
				if ($iniciopagina=='') { $iniciopagina=0; }
				if ($iniciopagina>$filas) { $iniciopagina=0; }
					if ($filas > 0) {
							$leer=verificopermisos('formularios', 'leer', $UserID);
							$escribir=verificopermisos('formularios', 'escribir', $UserID);
							$modificar=verificopermisos('formularios', 'modificar', $UserID);
							$eliminar=verificopermisos('formularios', 'eliminar', $UserID);
						 
							 $sel_resultado="SELECT * FROM formularios WHERE borrado=0 AND ".$where;
						   $sel_resultado=$sel_resultado."  limit ".$iniciopagina.",".$paginacion;
						   $res_resultado=mysqli_query($GLOBALS["___mysqli_ston"], $sel_resultado);
						   $contador=0;
						   while ($contador < mysqli_num_rows($res_resultado)) { 
								 if ($contador % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; }?>
						<tr id="<?php echo mysqli_result($res_resultado, $contador, "codformulario");?>"	 class="<?php echo $fondolinea?> trigger">
								<td><div ><?php echo $tipo[mysqli_result($res_resultado, $contador, "tipo")];?></div></td>
							<td><div ><?php echo mysqli_result($res_resultado, $contador, "descripcion");?></div></td>
<?php if ( $modificar=="true") { ?>		
							<td><div><a href="#"><img id="botonBusqueda" src="../img/modificar.png" width="16" height="16" border="0" onClick="modificar_formularios('<?php echo mysqli_result($res_resultado, $contador, "codformulario");?>')" title="Modificar"></a></div></td>
	<?php } else { ?>
							<td ><div></div></td>
	<?php } if ( $leer=="true") { ?>							
							<td><div ><a href="#">
							<img id="botonBusqueda" src="../img/ver.png" width="16" height="16" border="0" onClick="ver_formularios('<?php echo mysqli_result($res_resultado, $contador, "codformulario");?>')" title="Visualizar"></a></div></td>
	<?php } else { ?>
							<td ><div ></div></td>
	<?php } if ( $eliminar=="true") { ?>							
							<td><div ><a href="#">
							<img id="botonBusqueda" src="../img/eliminar.png" width="16" height="16" border="0" onClick="eliminar_formularios('<?php echo mysqli_result($res_resultado, $contador, "codformulario");?>')" title="Eliminar"></a></div></td>
	<?php } else { ?>
							<td ><div ></div></td>
	<?php } ?>									
							</tr>
						<?php $contador++;
							}
						?>			
					<?php } else { ?>
						<tr>
							<td width="100%" class="mensaje" colspan="5"><?php echo "No hay ninguna ubicaci&oacute;n que cumpla con los criterios de b&uacute;squeda";?></td>
					    </tr>
					<?php } ?>					
					</table>					
				</div>
			</div>
		  </div>			
		</div>
	</body>
</html>
