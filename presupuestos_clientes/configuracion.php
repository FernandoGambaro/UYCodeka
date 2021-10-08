<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL);

////header('Cache-Control: no-cache');
////header('Pragma: no-cache'); 


include ("../conectar.php"); 
include ("../funciones/fechas.php");
include("encrypt_decrypt.php");

$mensaje='';
$accion='';
$accion=@$_POST["accion"];

//if (!isset($accion)) { $accion=$_GET["accion"]; }

if ($accion=="modificar") {

$coddatos=0;
//$_POST["coddatos"];
$nombre=$_POST["Anombre"];
$razonsocial=$_POST["Arazonsocial"];
$nif=$_POST["Anif"];
$direccion=$_POST["direccion"];
$telefono1=$_POST["telefono1"];
$telefono2=$_POST["telefono2"];
$fax=$_POST["fax"];
$web=$_POST["web"];
$mailv=$_POST["mailv"];
$maili=$_POST["maili"];
$descripcion=$_POST["descripcion"];
$fecha=$_POST["fecha"];
if ($fecha<>"") { $fecha=explota($fecha); } else { $fecha="0000-00-00"; }
$login=@$_POST["login"];
$cabezalmail=@$_POST["cabezalmail"];
$piemail=@$_POST["piemail"];
$emailname=@$_POST['emailname'];
$emailsend=$_POST['emailsend'];
$emailpass=encrypt_decrypt('encrypt', $_POST['emailpass']);
$emailhost=$_POST['emailhost'];
$emailssl=$_POST['emailssl'];
$emailpuerto=$_POST['emailpuerto'];

$emailbody=$_POST['message'];

if($emailpass!='') {
	$email="`emailpass` = '$emailpass',";
}

$papel=$_POST['papel'];

$facebook=$_POST['facebook'];
$twitter=$_POST['twitter'];

$fileerror="";
global $mensaje;
$foto_name="";

function savefile($name,$type,$tmp_name,$size,$valor) {
	   /*/ Extrae los contenidos de las fotos
	   # contenido de la foto original*/
	   $fp = fopen($tmp_name, "rb");
	   $tfoto = fread($fp, filesize($tmp_name));
	   $tfoto = addslashes($tfoto);
	   fclose($fp);
	   /*/ Borra archivos temporales si es que existen*/
	   @unlink($tmp_name);
	   /*/ Guardamos todo en la base de datos
	   #nombre de la foto*/
	   
	   if ($valor!=0) {
		   $sqlveri="SELECT * FROM `foto` where `oid`='$valor'";
		   $resver=mysqli_query($GLOBALS["___mysqli_ston"], $sqlveri);
	   }
	   if (mysqli_num_rows($resver)>0)
	   {
		   $query = "UPDATE `foto` SET `fotoname`='$name', `fotosize`='$size', `fototype`='$type', `fotocontent`='$tfoto' WHERE `oid` = ' $valor' ";
	   } else {
	      $query = "INSERT INTO foto (oid, fotoname, fotosize, fototype, fotocontent ) VALUES ('$valor', '$name', '$size', '$type', '$tfoto')";
      } 

		$res=mysqli_query($GLOBALS["___mysqli_ston"], $query);
		if($res!==false) {
			$mensaje.="El archivo ".$name." ha sido guardado con exito";
		} else {
			$mensaje.="Error el archivo ".$name." no ha sido guardado";
		}
}

	if ($_FILES["login"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["login"]["name"];
	  $type = $_FILES["login"]["type"];
	  $tmp_name = $_FILES["login"]["tmp_name"];
	  $size = $_FILES["login"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo Logo de login que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,11);
	  }
	}

	if (@$_FILES["cabezalmail"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["cabezalmail"]["name"];
	  $type = $_FILES["cabezalmail"]["type"];
	  $tmp_name = $_FILES["cabezalmail"]["tmp_name"];
	  $size = $_FILES["cabezalmail"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo para el Cabezal que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,12);
	  }
	}
	
	if (@$_FILES["piemail"]["name"]!='') {
	
	 $mimetypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  /*/ Variables de la foto*/
	  $name = $_FILES["piemail"]["name"];
	  $type = $_FILES["piemail"]["type"];
	  $tmp_name = $_FILES["piemail"]["tmp_name"];
	  $size = $_FILES["piemail"]["size"];
	  /*/ Verificamos si el archivo es una imagen válida*/
	/*echo $type."<br>";*/
	  if(!in_array($type, $mimetypes)) {
	    $mensaje.="El archivo que subiste no es una imagen válida";
	  } else {
		savefile($name,$type,$tmp_name,$size,13);
	  }
	}	
	
	$query_update="UPDATE `datos` SET `nombre` = '$nombre', `razonsocial` = '$razonsocial', `nif` = '$nif', `direccion` = '$direccion',
	 `telefono1` = '$telefono1', `telefono2` = '$telefono2', `fax` = '$fax', `web` = '$web', `mailv` = '$mailv',
	  `maili` = '$maili', `facebook`='$facebook', `twitter`='$twitter', `descripcion` = '$descripcion', `fecha` = '$fecha',
	   `login` = '$login', `cabezalmail` = '$cabezalmail', `piemail` = '$piemail',
	   `emailname` = '$emailname', `emailsend` = '$emailsend', $email `emailhost` = '$emailhost', 
	   `emailssl` = '$emailssl', `emailpuerto` = '$emailpuerto', `emailbody`='$emailbody', `papel`='$papel' WHERE `datos`.`coddatos` = 0";
	   
	
	$rs_query=mysqli_query($GLOBALS["___mysqli_ston"], $query_update);
	if ($rs_query) { $mensaje.="Los datos de la empresa han sido modificados correctamente"; } else { $mensaje.="Error los datos de la empresa no han sido modificados";}
	$cabecera1="Inicio >> Datos &gt;&gt; Modificar Datos ";
	$cabecera2="MODIFICAR DATOS ".$fileerror;

}

$query="SELECT * FROM datos WHERE coddatos='0'";
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
		<script type="text/javascript" src="js/MaskedPassword.js"></script>

		<script type="text/javascript" src="../funciones/validar.js"></script>

<script src="js/jquery-1.6.2.min.js"></script>
<script src="js/jquery-ui-1.8.custom.min.js"></script>
<script src="js/editor/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>		
<script type="text/javascript" src="js/editor/tinymce/jscripts/tiny_mce/themes/advanced/js/charmap.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".tabContents").hide(); // Hide all tab content divs by default
		$(".tabContents:first").show(); // Show the first div of tab content by default
		
		$(".tabContaier ul li a").click(function(){ //Fire the click event
			
			var activeTab = $(this).attr("href"); // Catch the click link
			$(".tabContaier ul li a").removeClass("active"); // Remove pre-highlighted link
			$(this).addClass("active"); // set clicked link to highlight state
			$(".tabContents").hide(); // hide currently visible tab content div
			$(activeTab).show(); // show the target tab content div by matching clicked link.
			
			return false; //prevent page scrolling on tab click
		});
	});
</script>

<!-- Tabs -->
<style type="text/css">
a{outline:none;}

.tabContaier{
	background: url("../img/headerTile.png") repeat-x scroll left top transparent;
	margin: 2px 0 0 0;
	padding:0;
	height:20px;	
	position: relative;
	width: 98%;
	top: 10px;
	border-bottom: 1px solid #666;
	
}
	.tabContaier ul{
		overflow:hidden;
		height:20px;
		position:absolute;
		z-index:100;
		margin:0;
		padding:0;
	}
	.tabContaier li{
		float:left;
		list-style:none;
		border-right:1px solid #fff;
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;		
	}
	.tabContaier li a{
		background: url("../img/headerTile.png") no-repeat-x left top transparent;
		border-right:0;
		color:#fff;
		cursor:pointer;
		display:block;
		height:20px;
		line-height:20px;
		padding:0 20px;
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
	}
	
	
	.tabContaier li a:hover{
		background:#fff;
		color:#000;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
	}
	.tabContaier li a.active{
		background:#fbfbfb;
		border:1px solid #fff;
		color:#333;
		border-top: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		text-decoration:none;
		text-transform:uppercase;
		z-index: 999;
	}
	.tabDetails{
		background:#ddd;
		border:1px solid #fff;
	}
	.tabContents{
		margin: 0;
		padding:0;		
		background:#fff;
		border-bottom: 1px solid #666;
		border-left: 1px solid #666;;
		border-right: 1px solid #666;		
		height: 500px;
		position: relative;
		width: 98%;
		top: 9px;
		left: 1px;
	}

#inner {
	position: relative;
	top: 20px;
	display: table;
	margin: 0 auto;
}

</style>


<script type="text/javascript">
var pass='';



window.onload = function () {

    document.getElementById('emailpass').onfocus = function () {
        if (this.defaultValue == this.value) {
            this.type = 'password';
            this.value = '';
        }
    }
}

</script>		
		<script language="javascript">
		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		function imprimir() {
			window.open("../fpdf/ejemplo.php");
		}		

		</script>
<script type="text/javascript">
tinyMCE.init({
			theme : 'advanced',
			mode : 'textareas',
			elements : 'parte1',
			//content_css : "css/tinymce.css",
			height: '350',
			relative_urls : false,
			remove_script_host : false,
			pdw_toggle_on : 1,
			pdw_toggle_toolbars : '2,3,4',
			plugins : 'pdw,safari,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
			// Theme options
			theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,formatselect,forecolor,backcolor,|,print,fullscreen,code,|,pdw_toggle',
			theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,sub,sup',
			theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl',
			theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,|,insertdate,inserttime,preview',
			theme_advanced_toolbar_location : 'top',
			theme_advanced_toolbar_align : 'left',
			theme_advanced_statusbar_location : 'bottom',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true
		});
tinyMCE.init({
			theme : 'advanced',
			mode : 'textareas',
			elements : 'parte2',
			//content_css : "css/tinymce.css",
			height: '350',
			relative_urls : false,
			remove_script_host : false,
			pdw_toggle_on : 1,
			pdw_toggle_toolbars : '2,3,4',
			plugins : 'pdw,safari,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
			// Theme options
			theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,formatselect,forecolor,backcolor,|,print,fullscreen,code,|,pdw_toggle',
			theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,sub,sup',
			theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl',
			theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,|,insertdate,inserttime,preview',
			theme_advanced_toolbar_location : 'top',
			theme_advanced_toolbar_align : 'left',
			theme_advanced_statusbar_location : 'bottom',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true
		});
tinyMCE.init({
			theme : 'advanced',
			mode : 'textareas',
			elements : 'parte3',
			//content_css : "css/tinymce.css",
			height: '350',
			relative_urls : false,
			remove_script_host : false,
			pdw_toggle_on : 1,
			pdw_toggle_toolbars : '2,3,4',
			plugins : 'pdw,safari,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
			// Theme options
			theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,formatselect,forecolor,backcolor,|,print,fullscreen,code,|,pdw_toggle',
			theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,sub,sup',
			theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl',
			theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,|,insertdate,inserttime,preview',
			theme_advanced_toolbar_location : 'top',
			theme_advanced_toolbar_align : 'left',
			theme_advanced_statusbar_location : 'bottom',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true
		});
tinyMCE.init({
			theme : 'advanced',
			mode : 'textareas',
			elements : 'parte4',
			//content_css : "css/tinymce.css",
			height: '350',
			relative_urls : false,
			remove_script_host : false,
			pdw_toggle_on : 1,
			pdw_toggle_toolbars : '2,3,4',
			plugins : 'pdw,safari,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
			// Theme options
			theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,formatselect,forecolor,backcolor,|,print,fullscreen,code,|,pdw_toggle',
			theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,sub,sup',
			theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl',
			theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,|,insertdate,inserttime,preview',
			theme_advanced_toolbar_location : 'top',
			theme_advanced_toolbar_align : 'left',
			theme_advanced_statusbar_location : 'bottom',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true
		});
</script>		
	</head>
	<body>

		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
			
<!-- -->

				<form id="formulario" name="formulario" method="post" action="index.php" enctype="multipart/form-data">
				<input id="accion" name="accion" value="modificar" type="hidden">

<div class="tabContaier">
	<ul>
    	<li><a class="active" href="#tab1">Caratula</a></li>
    	<li><a href="#tab2">Descripción</a></li>
    	<li><a href="#tab3">Propuesta</a></li>
    	<li><a href="#tab4">Condisiones Comerciales</a></li>
    </ul><!-- //Tab buttons -->
</div>
     	<div id="tab1" class="tabContents">
        	<div id="inner">
        	<?php echo $mensaje;?>
				<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tbody><tr><td valign="top" width="20%">

			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
			<tbody><tr>
			<td valign="top"> Puede incluir estos campos que serán sustituidos al enviar el mail<p>

			</td>
			<td align="center" valign="top" width="50%" >
					
			<div id="compose" style="width: 806px; height: 300px;">
			<textarea  cols="64" rows="15" id="parte1" name="parte1">
			<?php
			$emailbody=mysqli_result($rs_query, 0, "emailbody");
			if(trim($emailbody) != '')	{ 
				echo $emailbody;
			} else {				
			?>
				
			<?php }
			?>
			</textarea>
			</div><!-- #compose -->
									
						
					</td>
				</tr>

				<!-- END Footer -->
			</tbody>
		</table>
					</td>
			</tbody>
		</table>  			      
				      
				      
				</div>
				      
        </div><!-- //tab1 -->


    	<div id="tab2" class="tabContents">
        	<div id="inner">
        	<?php echo $mensaje;?>
				<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tbody><tr><td valign="top" width="20%">

			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
			<tbody><tr>
			<td valign="top"> Puede incluir estos campos que serán sustituidos al enviar el mail<p>

			</td>
			<td align="center" valign="top" width="50%" >
					
			<div id="compose" style="width: 806px; height: 300px;">
			<textarea  cols="64" rows="15" id="parte2" name="parte2">
			<?php
			$emailbody=mysqli_result($rs_query, 0, "emailbody");
			if(trim($emailbody) != '')	{ 
				echo $emailbody;
			} else {				
			?>
				
			<?php }
			?>
			</textarea>
			</div><!-- #compose -->
									
						
					</td>
				</tr>

				<!-- END Footer -->
			</tbody>
		</table>
					</td>
			</tbody>
		</table>  			      
				      
				      
				</div>
			</div> 
<!-- //tab2 -->
			<div id="tab3" class="tabContents">
        	<div id="inner">
        	<?php echo $mensaje;?>
				<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tbody><tr><td valign="top" width="20%">

			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
			<tbody><tr>
			<td valign="top"> Puede incluir estos campos que serán sustituidos al enviar el mail<p>

			</td>
			<td align="center" valign="top" width="50%" >
					
			<div id="compose" style="width: 806px; height: 300px;">
			<textarea  cols="64" rows="15" id="parte3" name="parte3">
			<?php
			$emailbody=mysqli_result($rs_query, 0, "emailbody");
			if(trim($emailbody) != '')	{ 
				echo $emailbody;
			} else {				
			?>
				
			<?php }
			?>
			</textarea>
			</div><!-- #compose -->
									
						
					</td>
				</tr>

				<!-- END Footer -->
			</tbody>
		</table>
					</td>
			</tbody>
		</table>  			      
				      
				      
				</div>

<!-- -->				
</div><!-- //Tab Container -->
<div id="tab4" class="tabContents">
        	<div id="inner">
        	<?php echo $mensaje;?>
				<table class="fuente8" width="100%" cellspacing=5 cellpadding=5 border=0 >
			<tbody><tr><td valign="top" width="20%">

			<table class="fuente8" width="100%" cellspacing=2 cellpadding=3 border=0 >
			<tbody><tr>
			<td valign="top"> Puede incluir estos campos que serán sustituidos al enviar el mail<p>

			</td>
			<td align="center" valign="top" width="50%" >
					
			<div id="compose" style="width: 806px; height: 300px;">
			<textarea  cols="64" rows="15" id="parte4" name="parte4">
			<?php
			$emailbody=mysqli_result($rs_query, 0, "emailbody");
			if(trim($emailbody) != '')	{ 
				echo $emailbody;
			} else {				
			?>
				
			<?php }
			?>
			</textarea>
			</div><!-- #compose -->
									
						
					</td>
				</tr>

				<!-- END Footer -->
			</tbody>
		</table>
					</td>
			</tbody>
		</table>  			      
				      
				      
				</div>
</div>

	<p>&nbsp;</p>
	<div style="position: fixed;top:555px; width: 100%; margin:0 auto; ">
				<img id="botonBusqueda" src="../img/botonaceptar.jpg" width="85" height="22" onClick="guardar();" border="1" onMouseOver="style.cursor=cursor">
	</div>
			  </div>
			  </form>
			 </div>				
		  </div>

<script>
function guardar() {
    document.getElementById("formulario").submit();
}

</script>
<script type="text/javascript">
		//apply masking to the demo-field
		//pass the field reference, masking symbol, and character limit
	new MaskedPassword(document.getElementById("emailpass"), '\u25CF');
</script> 	

	  
	</body>
</html>			