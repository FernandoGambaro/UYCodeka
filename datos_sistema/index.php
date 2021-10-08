<?php
ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors
require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/verificopermisos.php';   
require_once __DIR__ .'/../common/funcionesvarias.php';   

require_once __DIR__ .'/../common/fechas.php';

require_once __DIR__ . '/../classes/Encryption.php';


if (!$s = new session()) {
	  echo "<h2>Ocurrió un error al iniciar session!</h2>";
	  echo $s->log;
	  exit();
  }

  if((!$s->data['isLoggedIn']) || !($s->data['isLoggedIn']))
  {
	  //*user is not logged in*/
	  //echo "<script>window.top.location.href='../index.php'; </script>";  
  } else {
	 $loggedAt=$s->data['loggedAt'];
	 $timeOut=$s->data['timeOut'];
	 if(isset($loggedAt) && (time()-$loggedAt >$timeOut)){
		 $s->data['act']="timeout";
		  $s->save();  	
			//header("Location:../index.php");	
		  //echo "<script>window.top.location.href='../index.php'; </script>";
		 exit;
	 }
	 $s->data['loggedAt']= time();
	 $s->save();
  }
  
  $UserID=$s->data['UserID'];
  $UserNom=$s->data['UserNom'];
  $UserApe=$s->data['UserApe'];
  $UserTpo=$s->data['UserTpo'];

$page_title='Datos del sistema';
$mensaje='';
$accion='';
$xx=0;
$query_update='';
$accion=@$_POST["accion"];

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('datos');



function savefile($name,$type,$tmp_name,$size,$valor) {
	/*/ Extrae los contenidos de las fotos
	# contenido de la foto original*/
	$mensaje='';
	$fp = fopen($tmp_name, "rb");
	$tfoto = fread($fp, filesize($tmp_name));
	$tfoto = addslashes($tfoto);
	fclose($fp);
	/*/ Borra archivos temporales si es que existen*/
	@unlink($tmp_name);
	/*/ Guardamos todo en la base de datos #nombre de la foto*/

	if ($valor!=0) {

	$obj = new Consultas('foto');
	$obj->Select();
	$obj->Where('oid', trim($valor)); 
	//var_dump($obj);
	$resultado = $obj->Ejecutar();
	}
	$nombres[]='fotoname';
	$valores[]=$name;
	$nombres[]='fotosize';
	$valores[]=$size;
	$nombres[]='fototype';
	$valores[]=$type;
	$nombres[]='fotocontent';
	$valores[]=$tfoto;
 
	if ($resultado['numfilas']>0)
	{
	 $obj = new Consultas('foto');
	 $obj->Update($nombres, $valores);
	 $obj->Where('oid', trim($valor)); 
	 $resultado = $obj->Ejecutar();
	} else {
	 $obj = new Consultas('foto');
	 $obj->Insert($nombres, $valores);
	 $resultado = $obj->Ejecutar();
	 } 
 if($resultado["estado"]=="ok"){
	 $mensaje.="El archivo ".$name." ha sido guardado con exito";
 } else {
	 $mensaje.="Error el archivo ".$name." no ha sido guardado";
 }
}


if($_POST)
{
    $nombres = array();
    $valores = array();
    $attr = '';
    $valor = '';
    $randomhash = RandomString(24);

    $DATA=$_POST['DATA'];
    $xpos=0;
    foreach ($DATA as $key => $item)
    {
		//echo "<br>". $key;
        if (!is_array($item)) {
            if($xpos==0){
              $attr = trim($key);
              $valor = trim($item); 
              $xpos++; 
            } else {
				if(strlen($item)>0){
					
					if(strpos($key, 'fecha')!==false){
						$valores[] = explota($item);
					} else {
						if(strpos($key,'pass')!==false){
							$converter = new Encryption;
							$converter = $converter->encode($item);							
						$valores[] = $converter;			
						}elseif(strpos($key,'bancos')!==false){
							$valores[] = ''; //addslashes($item);
							/* */													
						}elseif(strpos($key,'emailbody')!==false){
							/* Codifico el mensaje para poder guardarlo en la DB */
							require_once __DIR__ .'/../common/tiny-html-minifier.php';
							$valores[] = TinyMinify::html($item);
						}else{
							$valores[] = $item;
						}
					}
					if(strpos($key,'bancos')!==false){
						$nombres[] = str_replace('[', '', $key);
						/* */													
					}else{
						$nombres[] = $key;
					}
                }
            }
		}else{
			//echo "algo <br>".$key."<br>";
			if(strpos($key,'bancos')!==false){
				$nombres[] = $key;													
				/* Sección para agregar bancos */
				$banco=$item;
				$bancos='';
				$x=0;
				if (isset($banco) && is_array($banco)){
						foreach($banco as $valu) {
							if($x>0) {
								$bancos.="#";
							}
						$x++;
							$bancos.=$valu;
						}
				} else {
					$bancos.=$banco;
				}
				$valores[] = $bancos;
				/* */													
				}
				/*
			for ( $i=0; $i < count($DATA[$key]); $i++ )
			{
				if($xpos==0){
					$attr = trim($key);
					$valor = trim($item); 
					$xpos++; 
				} else {
					if ( !empty($DATA[$key][$i]) )  {
						if($item!=''){
						$nombres[] = $key;
							if(strpos($item, '/')>0){
								$valores[] = explota($item);
							} else {
								$valores[] = $item;
							}
						}
					}
				}
			}
			if($item!='') {
			$nombres[] = $key;
			if(strpos($item, '/')>0){
				$valores[] = explota($item);
			} else {
				$valores[] = $item;
			}
				}
				*/
		}
				
    }

    $obj->Update($nombres, $valores);
    $obj->Where('coddatos', '0');
    //var_dump($valores);
		$datos_sistema = $obj->Ejecutar();
		//echo $datos_sistema['consulta'];
		
		$fileerror="";
		global $mensaje;
		$foto_name="";
		
		
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


    if($datos_sistema["estado"]=="ok"){
 
        //echo "<script>parent.$('idOfDomElement').colorbox.close();</script>";
/*
        $obj = new Consultas('contactos');
        $obj->Select();
        $obj->Where(trim($attr), trim($valor));
        $datos_sistema = $obj->Ejecutar();
        $datos_sistema = $datos_sistema["datos"][0];
        
        $dpto = new Consultas('departamentos');
        $dpto->Select();
        $departamento = $dpto->Ejecutar();
        $departamento = $departamento["datos"];   
        */  
    } else{
        echo "<div class=\"alert alert-danger alert-dismissable\">";
            echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                        &times;
                  </button>";
            echo "Error! No se pudieron guardar los cambios.";
        echo "</div>";
    }
	
		
		$obj = new Consultas('datos');

    $obj->Select();
    $obj->Where('coddatos', '0');
    $datos_sistema = $obj->Ejecutar();
    $datos_sistema = $datos_sistema["datos"][0];

	$objpaises = new Consultas('paises');
    $objpaises->Select();
    $paises = $objpaises->Ejecutar();
    $paises = $paises["datos"];

	$objentidades = new Consultas('entidades');
		$objentidades->Select();
    $objentidades->Where('borrado', '0'); 
    $entidades = $objentidades->Ejecutar();
    $entidades = $entidades["datos"];

    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
		$departamento = $departamento["datos"];
				
$oidestudio = '';
$oidpaciente = '';
$hace = _('Modifica datos del sistema ').$UserNom.' '.$UserApe;

logger($UserID, $oidestudio, $oidpaciente, $hace);

    
} else {

	$oidestudio = '';
	$oidpaciente = '';
	$hace = _('Ver datos del sistema ').$UserNom.' '.$UserApe;
	
	logger($UserID, $oidestudio, $oidpaciente, $hace);
	
    $obj->Select();
    $obj->Where('coddatos', '0');
    $datos_sistema = $obj->Ejecutar();
    $datos_sistema = $datos_sistema["datos"]['0'];

	$objpaises = new Consultas('paises');
    $objpaises->Select();
    $paises = $objpaises->Ejecutar();
    $paises = $paises["datos"];

	$objentidades = new Consultas('entidades');
		$objentidades->Select();
    $objentidades->Where('borrado', '0'); 
    $entidades = $objentidades->Ejecutar();
    $entidades = $entidades["datos"];

    $dpto = new Consultas('departamentos');
    $dpto->Select();
    $departamento = $dpto->Ejecutar();
    $departamento = $departamento["datos"];

}



//if (!isset($accion)) { $accion=$_GET["accion"]; }

if ($accion=="modificar") {





}

$obj = new Consultas('datos');


	$modeloselected=$datos_sistema['modelo'];
	if($modeloselected!='') {
	$img_modelo="../library/images/".$modeloselected.".png";
	} else {
	$img_modelo="../library/images/blank.png";
	}
?>
<!DOCTYPE html>
    <html lang="en">
		<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $page_title; ?></title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

	<link rel="stylesheet" href="../library/colorbox/colorbox.css" />
	<script src="../library/colorbox/jquery.colorbox.js"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../library/toastmessage/message.js" type="text/javascript"></script>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

  <script src="../library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>
  <link href="../library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet">
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>  

<script src="../library/js/colorpicker.js"></script>

	
<style type="text/css">
#cboxSocials {
	position: relative;
 width:79px;
   margin-top:1px;
   height: 22px;
 float:right;
 margin-right: 50px;
}

.cb_social_elem {
	position: relative;
 float:right;
 margin-right: 10px;
 width:79px;
 height: 22px;
}

/* change border radius for the tab , apply corners on top*/

#exTab3 .nav-pills > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  /*color : white;*/
  /*background-color: #333;*/
  padding : 2px 5px;
	border-width: 1px;
  border-style: solid;
  border-color: #337ab7;

}

.btn-file {
        position: relative;
        overflow: hidden;
				background: #337ab7;
				color: white;

    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: #337ab7;
        cursor: inherit;
        display: block;
    }

		.seleccione { background:#FFF;margin:0 7px 0 7px;
		border-left:1px solid #EFEFEF;border-right:1px solid #EFEFEF;
		border-bottom:1px solid #EFEFEF;padding:5px 5px 5px 5px;
 border-right:1px solid #C43303; border-bottom:1px solid #C43303;
  border-left:1px solid #C43303; border-top:1px solid #C43303; border-radius:5px;
}

</style>
		
<script type="text/javascript">

parent.document.getElementById("msganio").innerHTML="<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp;  Datos del sistema";

jQuery(document).ready(function() {
	var printto = '<div class="cb_social_elem"><button class="boletin" onClick="PrintMe(\'cboxLoadedContent\');" onMouseOver="style.cursor=cursor;" style=" background-color:yellow; width:100px;"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button></div>';
	jQuery("#cboxContent").append('<div id="cboxSocials">'+printto+'</div>');
});
</script>

<script language="javascript">
function PrintMe(DivID) {
var disp_setting="toolbar=yes,location=no,";
disp_setting+="directories=yes,menubar=yes,";
disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";
   var content_vlue = document.getElementById(DivID).innerHTML;
   var docprint=window.open("","",disp_setting);
   docprint.document.open();
   docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
   docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
   docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');
   docprint.document.write('<head><title>Imprimir modelo seleccionado</title>');
   docprint.document.write('<style type="text/css">body{ margin:0px;');
   docprint.document.write('font-family:verdana,Arial;color:#000;');
   docprint.document.write('font-family:Verdana, Geneva, sans-serif; font-size:12px;}');
   docprint.document.write('a{color:#000;text-decoration:none;} </style>');
   docprint.document.write('</head><body onLoad="self.print()"><center>');
   docprint.document.write(content_vlue);
   docprint.document.write('</center></body></html>');
   docprint.document.close();
   docprint.focus();
}
</script>

<script type="text/javascript">
function ver(){
event.preventDefault();
	var archivo = $("#container img").attr('src');
	if (archivo !='../library/images/blank.png') {
		
	$.colorbox({
	   	href: archivo, open:true,
			scalePhotos: false,
	     maxWidth: '95%',
	     		onComplete: function () {
				var alto=$(".cboxPhoto").height();
				$("#cboxSocials").css("marginTop", "-"+alto+"px");
			}			
	});
	}

}
</script>

<script type="text/javascript">

var id='';
var oldid='';

	$(document).ready(function(){

		  	$('#<?php echo $modeloselected;?>s').show();
		  	$('#<?php echo $modeloselected;?>').hide();
				oldid='<?php echo $modeloselected;?>';
				id='<?php echo $modeloselected;?>';
		
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
                     
                    $('#id_radio1').click(function () {
                    	  $('#id_radio2').show();
                       $('#div2').hide();
                       $('#id_radio1').hide();
                       $('#div1').show();
                });
                $('#id_radio2').click(function () {
                	    $('#id_radio1').show();
                	    $('#id_radio2').hide();
                      $('#div1').hide();
                      $('#div2').show();
                 });

$(".basico").click(function(e){
e.preventDefault();
id = $(this).attr("id");
$('#modelo').val(id);
$('#'+id).hide();
$("#"+id).attr('checked', true); 

  if (oldid!='') {
	$("#"+oldid).attr('checked', false); 
	$('#'+oldid).show();
   $('#'+oldid+'s').hide();
  }
  $('#'+id+'s').show();
oldid=id;  

//$('input[type="radio"][id='+id+']').prop('checked', true)
  
if($('#logofactura').is(":checked")){
$.post("modelofactura.php", {"id":id }, function(response){ 
if (response==1) {
	d = new Date();
		$("#containerimg").attr("src", "../tmp/modelofactura.png?"+d.getTime());
		$("#containerimg").show();
} else {
	   $("#container img").attr('src', "../library/images/"+ id +".png");
}
});
} else {
	$("#container img").attr('src', "../library/images/"+ id +".png");
}	
fadeIn($("#container img"));
});
	

});
</script>
<script type="text/javascript" >
function prever(){
if (id!='') {
	if($('#logofactura').is(":checked")){
	$.post("modelofactura.php", {"id":id }, function(response){ 
	if (response==1) {
		d = new Date();
			$("#containerimg").attr("src", "../tmp/modelofactura.png?"+d.getTime());
			$("#containerimg").show();
	} else {
		   $("#container img").attr('src', "../library/images/"+ id +".png");
	}
	});
} else {
	$("#container img").attr('src', "../library/images/"+ id +".png");
}	
fadeIn($("#container img"));
}
}
function fadeIn(obj) {
    $(obj).fadeIn(1000);
}

</script>

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
			window.open("../reportes/EjemploImpresion.php");
		}		
		</script>
		
<script src="../library/tinymce/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea#message' ,
		 language: 'es',
		 height : 350,
		 max_height: 500,
		 menubar: 'file edit insert view format table tools',
		 menubar: false,
		 resize: false,
		 statusbar: false,
		theme: 'modern',
		  plugins: [
    "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
  ],

	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
 	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
 	toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

 	menubar: false,
 	toolbar_items_size: 'small',

 	style_formats: [{
    title: 'Bold text',
    inline: 'b'
 	}, {
    title: 'Red text',
    inline: 'span',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Red header',
    block: 'h1',
    styles: {
      color: '#ff0000'
    }
 	}, {
    title: 'Example 1',
    inline: 'span',
    classes: 'example1'
 	}, {
    title: 'Example 2',
    inline: 'span',
    classes: 'example2'
 	}, {
    title: 'Table styles'
 	}, {
    title: 'Table row 1',
    selector: 'tr',
    classes: 'tablerow1'
  	}],
});

</script>
		 	
<script type="text/javascript">

var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';
var item0=0;
var initials ={};

$(document).ready( function()
{
  $('.trigger').click( function(e){
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';

      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft - 70 ;
      var y = offset.top - this.offsetTop + 10;
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y;
      $(newItemShowN).show(); $(newItemHideN).hide();
      e.preventDefault();
   } );//Finaliza trigger

  $('.triggerClose').click( function(e){

      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
      trigerclass='';
      e.preventDefault();
   } );/*/Finaliza triggerClose*/
   
   	$('.triggerinput').click( function(e) {
   		trigerclass=1;
   		/*/alert(trigerclass);*/
   	});


  $('.ComboBox').click(function(e){
/*//alert(this.id);*/
      newItemDel='#Vbancos';
      $(newItemShowN).hide(); $(newItemHideN).show();
      e.preventDefault();
   });

	$('#formulario').submit(function() {
		$('*').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});

$('.simpleinput').click( function(e){
	$(newItemShowN).hide(); $(newItemHideN).show();
});

/*/veo cual es el campo en el que se esta escribiendo;*/
$('input').focus(function(e){
   var selected = document.activeElement;

   if (selected.id && selected.id!='basicoA4' && selected.id!='basicoA5' ) {
      var offset = $(this).offset();
      var x = offset.left - this.offsetLeft ;
      var y = offset.top - this.offsetTop + 15;
//      document.getElementById("suggestions").style.left = x + 10;
//      document.getElementById("suggestions").style.top = y + 21;
      e.preventDefault();
   }
newItemInput='#'+selected.id;
});
/*
    if (!document.activeElement) {
        this.each(function() {
            var $this = $(this).data('hasFocus', false);
            $this.focus(function(event) {
                $this.data('hasFocus', true);
            });
            $this.blur(function(event) {
                $this.data('hasFocus', false);
            });
        });
    }
*/
	$('input').blur(function(){
	  if($(this).attr('value')==''){
	    $(this).attr('value', initials[this.id]);
	  }
	});

});
function agregarbanco() {
	event.preventDefault();
		var numcuenta=$("#numcuenta").val();
		var Amoneda=$("#Amoneda option:selected").text();
		var cboBanco=$("#cboBanco option:selected").text();
		var cboBancoVal=$("#cboBanco").val();
		if($('#incluir').is(":checked")){
		var incluir="Si";
		}else {
		var incluir="No";	
		}
		
		//console.log('num cuenta '+numcuenta+' moneda' + Amoneda + ' cboBanco ' + cboBancoVal);

		if (numcuenta!='' && Amoneda!='' && eval(cboBancoVal)>0) {
		  $(newItemShowN).hide(); $(newItemHideN).show();
   	   var item1=cboBanco+"-"+Amoneda+"-Nº cuenta:"+numcuenta+"-"+incluir;
               $("#Vbancos").append($('<option></option>').attr('value', item1).text(item1));
               $("#numcuenta").val('');
               $("#Amoneda").val('');
               $("#cboBanco").val('');
               $("#incluir").val("");
      } else {
 			alert('Ingrese datos');
 			return false;
		}       	
}
</script>		 	
<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

<script type="text/javascript">

$(document).unbind('keypress');
$(document).keydown(function(e) {
/*//alert(e.keyCode);*/
    switch(e.keyCode) { 
        case 46:
         if(newItemDel!='') {
         $(newItemDel).find('option:selected').remove();
         newItemDel="";
         }
        break;
        case 112:
            alert('Ayuda aún no disponible...');
        break;
        case 13:

        break;
        
    }
});
</script>
<script LANGUAGE="JavaScript">
function selectAll(selectBox,selectAll) { 
    /*/ have we been passed an ID */
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    /*/ is the select box a multiple select box? */
    if (selectBox.type == "select-multiple") { 
        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
}
</script>		 		
	</head>
	<body class="">
				<form id="formulario" name="formulario" method="post" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="DATA[coddatos]" value="0">
		<div id="exTab3" class="container">	

				<ul class="nav nav-tabs nav-pills">
				<li class="active"><a href="#1b" data-toggle="tab">Datos Generales</a></li>
				<li><a href="#2b" data-toggle="tab">Reportes</a></li>
				<li><a href="#3b" data-toggle="tab">Configuración Mail</a></li>
				<li><a href="#4b" data-toggle="tab">Facturación</a></li>
				<li><a href="#5b" data-toggle="tab">EFactura</a></li>
				<li><a href="#6b" data-toggle="tab">Miscelaneo</a></li>
				</ul>

	<div class="tab-content col-xs-12 ">
	<div class="tab-pane fade in active" id="1b">
	<?php if ($mensaje!=""){ ?>
		<div id="tituloForm" class="header"><?php echo $mensaje;?></div>
	<?php } ?>
		<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">

						<div class="row">
						<label class="control-label col-xs-1">Nombre</label>
					      <div class="col-xs-3"><input name="DATA[nombre]" id="nombre" value="<?php echo $datos_sistema['nombre'];?>" maxlength="50" class="form-control input-sm" type="text" ></div>
						<label class="control-label col-xs-1">Razón&nbsp;Social</label>
					      <div class="col-xs-3"><input name="DATA[razonsocial]" id="razonsocial" value="<?php echo $datos_sistema['razonsocial'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						<label class="control-label col-xs-1">RUT</label>
					      <div class="col-xs-3"><input name="DATA[nif]" id="nif" value="<?php echo $datos_sistema['nif'];?>" maxlength="20" class="form-control input-sm" type="text"></div>
						</div>
						<div class="row">
						<label class="control-label col-xs-1">Direccion</label>
					      <div class="col-xs-3" ><input name="DATA[direccion]" id="direccion" value="<?php echo $datos_sistema['direccion'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						<label class="control-label col-xs-1">Teléfono&nbsp;</label>
					      <div class="col-xs-3" ><input name="DATA[telefono1]" id="telefono1" value="<?php echo $datos_sistema['telefono1'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						<label class="control-label col-xs-1">Teléfono&nbsp;</label>
					      <div class="col-xs-3" ><input name="DATA[telefono2]" id="telefono2" value="<?php echo $datos_sistema['telefono2'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						</div>
						<div class="row">
						<label class="control-label col-xs-1">Fax</label>
					      <div class="col-xs-3" ><input name="DATA[fax]" id="fax" value="<?php echo $datos_sistema['fax'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						<label class="control-label col-xs-1">web</label>
					      <div class="col-xs-3" ><input name="DATA[web]" id="web" value="<?php echo $datos_sistema['web'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						<label class="control-label col-xs-1">Mail&nbsp;ventas</label>
					      <div class="col-xs-3" ><input name="DATA[mailv]" id="mailv" value="<?php echo $datos_sistema['mailv'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
						</div>
						<div class="row">
						<label class="control-label col-xs-1">Mail&nbsp;info</label>
					      <div class="col-xs-3" ><input name="DATA[maili]" id="maili" value="<?php echo $datos_sistema['maili'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
							<label class="control-label col-xs-1">Descripci&oacute;n</label>
						    <div class="col-xs-3"><textarea name="DATA[descripcion]" cols="38" rows="2" id="descripcion" class="areaTexto"><?php echo $datos_sistema['descripcion'];?></textarea></div>
							<label class="control-label col-xs-1">Fecha&nbsp;de&nbsp;alta</label>
							<div class="col-xs-3">
							<div class="input-group date form_date" data-date="" data-date-format="dd/MM/yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
								<input placeholder="Fecha de alta" class="form-control input-sm" size="26" type="text" name="DATA[fecha]" id="fecha" readonly  required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							</div>	
							</div>
					  </div>

			</div>
				<div class="col-xs-12">

						<div class="row">
							<label class="control-label col-xs-1">Facebook</label>
							<div class="col-xs-3"><input name="DATA[facebook]" id="facebook" value="<?php echo $datos_sistema['facebook'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
							<label class="control-label col-xs-1">twitter</label>
							<div class="col-xs-3"><input name="DATA[twitter]" id="twitter" value="<?php echo $datos_sistema['twitter'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
							<label class="control-label col-xs-1">País</label>
							<div class="col-xs-3">
							<input type="hidden" name="DATA[pais]" id="codpais" value="<?php echo $datos_sistema['pais'];?>"/>
							<select class="form-control input-sm" id="bonusPa" onchange="$('#codpais').val($('#bonusPa option:selected').val());">							
							<option value="0">Seleccione una pais</option>
								<?php
								foreach ($paises as $key) {
									
									if ($key["codpais"] == $datos_sistema['pais']) {
										echo "<option value='".$key["codpais"]."' selected>".$key["nombre"]."</option>";
									} else {
										echo "<option value='".$key["codpais"]."'>".$key["nombre"]."</option>";
									}
									$xx++;
								}
								?>
								</select>
							</div>
						</div>	
						<div class="row">
							<label class="control-label col-xs-1">Departamento</label>
							<div class="col-xs-3">
							<input type="hidden" name="DATA[provincia]" id="codprovincia" value="<?php echo $datos_sistema['provincia'];?>"/>

							<select class="form-control input-sm" id="bonusProv" onchange="$('#codprovincia').val($('#bonusProv option:selected').val());">	>
                <?php
                foreach ($departamento as $key) {
                    
                    if ($key["departamentosid"] == $datos_sistema['provincia']) {
                        echo "<option value='".$key["departamentosid"]."' selected>".$key["departamentosdesc"]."</option>";
                    } else {
                        echo "<option value='".$key["departamentosid"]."'>".$key["departamentosdesc"]."</option>";
                    }
                    $xx++;
                }
                ?>            
                </select>
							</div>
							</div>
						<div class="row">
						  <div class="col-xs-2">Cambiar imagen de login
						  <br>[Formato&nbsp;jpg]&nbsp;[336x173]
						  <br>

						  <input type="file" name="login" id="login" class="form-control input-sm" accept="image/jpg" onchange="Preview('login');" style="z-index:9999;" />
						  </div>
						  <div class="col-xs-5">
							<img src="loadimage.php?id=11&default=1" width="336" style=" float: right;" id="uploadlogin" >
						  </div>
						  <div class="col-xs-5">

						  
							<div class="container">
							<h5>Cambiar color del menú</h5>
							
								<a class="btn btn-default" id="change-color"><div id="showtext">Cambiar</div></a>
								<a class="btn btn-default" id="reset-color">Reset</a>
								
							</div>
					    </div>
					</div>
				</div>
			</div>
		</div>
	</div> <!--Fin 1b  *****************************************************************-->
	<div class="tab-pane" id="2b">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				  <div id="tituloForm" class="header" style="width:100%;">Selección de cabezal y pie de página para la impresión de reportes y listas </div>
						  </div>
						  <div class="row">
						  <div class="col-xs-3">
							Cabezal&nbsp;Imagen&nbsp;[Formato&nbsp;jpg&nbsp;png]&nbsp;[977x75]</div>
						</div>
						  <div class="row">
						  <div class="col-xs-3">	

		<label class="btn btn-default btn-file">
        Seleccione archivo <input type="file" name="cabezalmail" id="cabezalmail" class="form-control input-sm" accept="image/jpg" onchange="Preview('cabezalmail');" />
    </label>


						  	  
						  	  </div><div class="col-xs-9">
								<img src="loadimage.php?id=12&default=2" style="width:750px; height:70px; float: left;" id="uploadcabezalmail" >
						  </div>
				      </div>
				      <div class="row">
							<div class="col-xs-12"></div>
							</div>
					  <div class="row">
							<div class="col-xs-12">Pie&nbsp;Imagen&nbsp;[Formato&nbsp;jpg&nbsp;png]&nbsp;[977x60]</div>
						</div>
						  <div class="row">
						  <div class="col-xs-3">
							<label class="btn btn-default btn-file">
							Seleccione archivo 
								<input type="file" name="piemail" id="piemail" class="form-control input-sm" accept="image/jpg" onchange="Preview('piemail');" />
							</label>
						  </div><div class="col-xs-9">
									<img src="loadimage.php?id=13&default=3" style=" width:750px; height:70px; float: left;" id="uploadpiemail" >
						  </div>
				      </div>
				      <div class="row">
								<div class="col-xs-3">Tamaño&nbsp;de&nbsp;papel</div>
								<div class="col-xs-2">
						<input name="DATA[papel]" id="papel" value="<?php echo $datos_sistema['papel'];?>" type="hidden" />
		                <select type="text" onchange="document.getElementById('papel').value=this.value;" class="form-control input-sm" >
							<?php
								$tipo = array("0"=>"Seleccione uno", "297x420xP"=>"A3 - Vertical","210x297xP"=>"A4 - Vertical","148x210xL"=>"A5 - Horizontal","279,4x210xP"=>"Carta - Vertical","355,6x210xP"=>"Legal - Vertical");
							foreach($tipo as $key => $tpo) {
								if ($key==$datos_sistema['papel']){
							      echo "<option value='$key' selected>$tpo</option>";
								} else {
							      echo "<option value='$key'>$tpo</option>";
								}

							}
							?>
							</select>				      
							</div>
						</div>
				      
				      <div class="row">
						  <div class="col-xs-3">Servidor&nbsp;y&nbsp;nombre&nbsp;impresora&nbsp;Reportes</div>
				      <div class="col-xs-5">
				      &nbsp;<input name="DATA[servidorreporte]" id="servidorreporte" size="20" class="form-control input-sm" value="<?php echo $datos_sistema['servidorreporte'];?>" placeholder="Servidor impresión">
				      &nbsp;<input name="DATA[impresorareporte]" id="impresorareporte" size="20" class="form-control input-sm" value="<?php echo $datos_sistema['impresorareporte'];?>" placeholder="Nombre impresora">
				      &nbsp;<span style="font-size: 10px;">Nombre de la impresora del sistema donde imprimir reporte.</span>
							</div>
						</div>
				      <div class="row">
								<div class="col-xs-3">&nbsp;</div>
				      <div class="col-xs-5">
				<?php
				$reportes="";
					if ($datos_sistema['reporte']==1) {
						$reportes="checked";
					}
				?>	
						<input type="hidden" name="DATA[reporte]" value="0">
						<label><input class="checkbox1" type="checkbox" name="DATA[reporte]" value="1" <?php echo $reportes;?> ><span></span></label>
				      &nbsp;<span style="font-size: 10px;">Envia la impresión directamente a la impresora sin cuadro de diálogo.</span>
							</div>
						</div>				      
				      <div class="row">
								<div class="col-xs-3" >
				      <button class="boletin" onClick="imprimir();;" onMouseOver="style.cursor=cursor;"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir muestra</button>
				      </div></div>
					</div>  
				<script type="text/javascript">
				
				    function Preview(item) {
				        var oFReader = new FileReader();
				        oFReader.readAsDataURL(document.getElementById(item).files[0]);
							var upload="upload"+item;
							var numupload="#upload"+item;
							
				        oFReader.onload = function (oFREvent) {
				            document.getElementById(upload).src = oFREvent.target.result;
				            $(numupload).show();
				        };
				    };
				
				</script>
		</div> 
	</div> <!-- fin 2b **************************************************-->
	<div class="tab-pane" id="3b">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">Configurar cuenta de mail</div>
				</div>
				<div class="row">
				<label class="control-label col-xs-1">Nombre:</label>
				<div class="col-xs-2"><input name="DATA[emailname]" id="emailname" value="<?php echo $datos_sistema['emailname'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-1">Email:</label>
				<div class="col-xs-3"><input name="DATA[emailsend]" id="emailsend" value="<?php echo $datos_sistema['emailsend'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-1">Responder&nbsp;a:</label>
				<div class="col-xs-3"><input name="DATA[emailreply]" id="emailreply" value="<?php echo $datos_sistema['emailreply'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				</div>
				<div class="row">
				<label class="control-label col-xs-1">Password:</label>
				<div class="col-xs-2"><input type="password" name="DATA[emailpass]" id="emailpass" size="30" class="form-control input-sm" placeholder="Escriba contraseña"  ></input>
			</div>
				<label class="control-label col-xs-2">Servidor/Host&nbsp;recepción:</label>
				<div class="col-xs-2"><input name="DATA[emailhost]" id="emailhost" value="<?php echo $datos_sistema['emailhost'];?>" maxlength="50" class="form-control input-sm" type="text">
			</div>
				<label class="control-label col-xs-2">Utilizar&nbsp;ssl&nbsp;recepción:</label>
				<div class="col-xs-1">
					<input type="hidden" name="DATA[emailssl]" id="emailssl" value="<?php echo $datos_sistema['emailssl'];?>" >

				<select class="form-control input-sm" id="bonusssl" onchange="$('#emailssl').val($('#bonusssl option:selected').val());">
				<?php $tipossl = array(0=>"ssl", 1=>"tls", 2=>'No');
					if ($datos_sistema['emailssl']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipossl as $key=>$i) {
					  	if ( $i==$datos_sistema['emailssl']) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</div>
			</div>
				<div class="row">
					<label class="control-label col-xs-2">Puerto&nbsp;recepción:</label>
					<div class="col-xs-1">
						<input type="hidden"  name="DATA[emailpuerto]" id="emailpuerto" value="<?php echo $datos_sistema['emailpuerto'];?>" />
				<select class="form-control input-sm" id="bonus"  onchange="$('#emailpuerto').val($('#bonus option:selected').val());">
				<?php $tipopuerto = array(0=>"110", 1=>"995", 2=>"993", 3=>'143');
					if ($datos_sistema['emailpuerto']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipopuerto as $key=>$i) {
					  	if ( $i==$datos_sistema['emailpuerto']) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</div>
				<label class="control-label col-xs-2">Servidor/Host&nbsp;envio:</label>
				<div class="col-xs-2"><input name="DATA[emailhostenvio]" id="emailhostenvio" value="<?php echo $datos_sistema['emailhostenvio'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-2">Utilizar&nbsp;ssl&nbsp;envio:</label>
				<div class="col-xs-1"><input type="hidden" name="DATA[emailsslenvio]" id="emailsslenvio" value="<?php echo $datos_sistema['emailsslenvio'];?>" >
				<select class="form-control input-sm" id="bonussslenvio"  onchange="$('#emailsslenvio').val($('#bonussslenvio option:selected').val());">
				<?php 
					if ($datos_sistema['emailsslenvio']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipossl as $key=>$i) {
					  	if ( $i==$datos_sistema['emailsslenvio']) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</div>
			</div>
			<div class="row">
					<label class="control-label col-xs-2">Puerto&nbsp;envio:</label>
				<div class="col-xs-1">
					<input type="hidden"  name="DATA[emailpuertoenvio]" id="emailpuertoenvio" value="<?php echo $datos_sistema['emailpuertoenvio'];?>" />
				<select class="form-control input-sm" id="bonusenvio"  onchange="$('#emailpuertoenvio').val($('#bonusenvio option:selected').val());">
				<?php $tipopuerto = array(0=>"26", 1=>"465", 2=>"587", 3=>'25');
					if ($datos_sistema['emailpuertoenvio']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipopuerto as $key=>$i) {
					  	if ( $i==$datos_sistema['emailpuertoenvio']) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</div>
				</div>
				<div class="row">				
				<div class="col-xs-9">					
					<div id="compose" style="width: 806px; height: 450px;">
				<textarea cols="64" rows="15" id="message" name="DATA[emailbody]">
				<?php
				$emailbody=$datos_sistema['emailbody'];
				if(trim($emailbody) != '')	{ 
					echo $emailbody;
				} else {				
				?>
						<div class="conteiner">
						<div class="row">
						<div class="col-xs-12"><img src="../library/images/logo.jpg" border="0" alt="" width="71" height="52" /></div>
						</div>
						
						</div>
						<div class="container">
						<div class="row">
							<div class="col-xs-2">
							<p>&nbsp;</p>
							<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
							<span style="color: #1f1f1f; line-height: 12px; text-align: center;">*empresa*</span></span></p>
							<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
							<span style="color: #1f1f1f; line-height: 12px; text-align: center;">&nbsp;Estimado: *nombre* *apellido*</span></span></p>
							<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">
							<span style="color: #1f1f1f; line-height: 12px; text-align: center;">&nbsp;</span></span>
							<span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">Seg&uacute;n lo solicitado tenemos el agrado de adjuntarle los detalles de *documento* en un archivo PDF.
							</span></p>
							<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;">&nbsp;</span>
							<span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">Aguardamos sus comentarios</span></p>
							<p><span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">&nbsp;</span>
							<span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">Saludos</span></p>
							<p><span style="color: #1f1f1f; font-family: tahoma, arial, helvetica, sans-serif; line-height: 24px; text-align: center; font-size: small;">&nbsp;</span>
							<span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: small;"><strong><span style="color: #1f1f1f; line-height: 24px; text-align: center;">*usuario*</span>
							</strong></span></p>
							<p>MCC - Soporte t&eacute;cnico <br />Mobile : (+598) (0) 96-261570 <br />Montevideo: (+598) 2486.3046 &nbsp;&nbsp;</p>
							<p style="font-size: 0pt; line-height: 0pt; height: 30px;"><span style="color: #1f1f1f; font-family: Tahoma; font-size: 20px; font-weight: bold; line-height: 24px; text-align: center;">
							<br /></span></p>
							
							<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">Enviado desde UYCODEKA Facturaci&oacute;n WEB<br />
							Con UYCODEKA obtenga r&aacute;pidamente informaci&oacute;n sobre el estado de su empresa</div>
							<div class="text-center" style="color: #868686; font-family: Tahoma; font-size: 14px; line-height: 18px; text-align: center;">&nbsp;</div>
						</div>
					</div>
				</div>
						<div class="container">
						<div class="row">
						<div class="col-xs-12">
						 <address class="footer" style="color: #a9aaa9; font-family: Arial; font-size: 11px; line-height: 20px; text-align: center;">
						 <span style="color: #ffffff;">Juan Ram&oacute;n G&oacute;mez 2671 apto 8  MCC Soporte T&eacute;cnico soporte@mcc.com.uy</span><br />
						 <span style="color: #ffffff;"> Copyright &copy;  MCC Soporte T&eacute;cnico.</span></address>
						 <address class="footer" style="color: #a9aaa9; font-family: Arial; font-size: 11px; line-height: 20px; text-align: center;">
						 <span style="color: #ffffff;"><br /></span></address> <address><span style="color: #ffffff; font-size: xx-small; font-family: tahoma, arial, helvetica, sans-serif;">
						 Por favor considere el medio ambiente y no imprima este correo a menos que lo necesite. </span></address><address>
						 <span style="color: #ffffff; font-size: xx-small; font-family: tahoma, arial, helvetica, sans-serif;"> El presente correo electr&oacute;nico y cualquier posible archivo adjunto 
						 est&aacute; dirigido  								&uacute;nicamente al destinatario del mismo y contiene informaci&oacute;n que puede ser confidencial.  								
						 Si Ud. no es el destinatario correcto por favor notifique al remitente respondiendo  								este mensaje y elimine inmediatamente de su sistema, el correo
						  electr&oacute;nico y los posibles  								archivos adjuntos al mismo. Est&aacute; prohibida cualquier utilizaci&oacute;n, difusi&oacute;n o copia de  
						  								este correo electr&oacute;nico por cualquier persona o entidad que no sean las espec&iacute;ficas  								
						  								destinatarias del mensaje. MCC - Soporte T&eacute;cnico no acepta ninguna responsabilidad  								
						  								con respecto a cualquier comunicaci&oacute;n que haya sido emitida incumpliendo lo previsto  								
															en la Ley 18.331 de Protecci&oacute;n de Datos Personales.</span> </address>
						</div>
						</div>
						
						</div>
						<?php }
						?>
						</textarea>
			</div><!-- #compose -->
									
				</div>		

			<div class="col-xs-3">
				Puede incluir estos campos que serán sustituidos al enviar el mail<p>
				Datos del Cliente:<p>
				Empresa: *empresa*<br>
				Contacto: *nombre* *apellido*<br>
				<p>Firma, nombre y apellido del usuario que inicia el sistema y envia el mail<p>
				Usuario: *usuario*<br>
				<p>
				Puede indicar el tipo de documento que esta enviando<p>
				Tipo: *documento*<br>
				(la factura), (la orden de compra), (el resumen de cuenta)
				</p>
		</div>
		</div>
		</div>
	</div><!-- fin 3b ********************************************-->

	<div class="tab-pane" id="4b">

	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
	
			<div class="row">
				<div class="col-xs-4">Servidor&nbsp;y&nbsp;nombre&nbsp;de&nbsp;la&nbsp;impresora&nbsp;de&nbsp;Facturación</div>
				<div class="col-xs-4"><input name="DATA[servidorfactura]" id="servidorfactura" size="20" class="form-control input-sm" value="<?php echo $datos_sistema['servidorfactura'];?>" placeholder="Servidor impresión"></div>
			</div>		      			
			<div class="row">
				<div class="col-xs-4"><span>Nombre de la impresora del sistema donde imprimir las facturas.</span></div>
				<div class="col-xs-4"><input name="DATA[impresorafactura]" id="impresorafactura" size="20" class="form-control input-sm" value="<?php echo $datos_sistema['impresorafactura'];?>" placeholder="Nombre impresora"></div>
			</div>
			<div class="row">
			<div class="col-xs-12" >
					<div class="row">
						<div class="col-xs-7">
						Lugar donede aparece impreso el símbolo de la moneda en uso
						<?php
							if ($datos_sistema['lugarmon']==1) {
								$izquierda="checked";
								$derecha="";
							} elseif ($datos_sistema['lugarmon']==2) {
								$derecha="checked";
								$izquierda="";
							} else {
								$izquierda="";
								$derecha="";
						}
						?>
						</div>
						<div class="col-xs-4">
						<label class="control-label col-xs-1">
						<input type="hidden" name="DATA[lugarmon]" value="<?php echo $datos_sistema['lugarmon'];?>">
						<input type="radio" name="DATA[lugarmon]" value="1" id="id_radio1" <?php echo $izquierda;?> >
						<div id="div1" style="display: none;">$</div>
						</label>
						<div class="col-xs-3">10000.11</div>
						<label class="control-label col-xs-1"></label>
							<div id="div2" style="display: none;">$</div>
							<input  type="radio" name="DATA[lugarmon]" value="2" id="id_radio2" <?php echo $derecha;?>>
						</div>
					</div>
					</div>
			</div>	
			<div class="row">
					<div class="col-xs-12">
					<div style="text-align:center">Seleccione modelo de factura a utilizar como predeterminado, 
					<br>&nbsp;para el caso de la facuración electrónica el sistema determinará cual utilizar&nbsp; </div>
					</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
				<div class="row">	
					<?php
					$logofactura='';
						if ($datos_sistema['logofactura']==1) {
							$logofactura="checked";
						}
					?>
				<div class="col-xs-4">Incluir&nbsp;logo&nbsp;y&nbsp;datos&nbsp;
							<input type="hidden" name="DATA[logofactura]" value="0">
							<label>
								<input class="checkbox1" type="checkbox" name="DATA[logofactura]" id="logofactura" value="1" onclick="prever();" <?php echo $logofactura;?>><span></span>
							</label>			
				</div>
				</div>
				<div class="row">
				<div class="col-xs-5">
					<?php
						if ($datos_sistema['modelo']==1) {
							$basicoA4="checked";
						} elseif ($datos_sistema['modelo']==2) {
							$basicoA5="checked";
						} else {
							$basicoA4="";
							$basicoA5="";
					}

					?>
					<input type="hidden" name="DATA[modelo]" id="modelo" value="<?php echo $modeloselected;?>">
						Modelo&nbsp;Básico&nbsp;DINA4
					</div>
					<div class="col-xs-1">
					<input type="radio" name="DATA[modelo]" value="1" class="basico"  id="basicoA4" style="position:relative;top:-5px; z-index: 999;" <?php echo $basicoA4;?>>
					<i class="fa fa-check" style="display: none; z-index: 99; position: relative;" id="basicoA4s"></i>
					</div>
				</div>
				<div class="row">
				<div class="col-xs-5">
						Modelo&nbsp;Básico&nbsp;DINA5
				</div>
				<div class="col-xs-1">
					<input type="radio" name="DATA[modelo]" value="2" class="basico" id="basicoA5"  style="position:relative;top:-5px; z-index: 999;" <?php echo $basicoA5;?>>
					<i class="fa fa-check" style="display: none; z-index: 99; position: relative;" id="basicoA5s"></i>
				</div>
				</div>
				<div class="row">
				<div class="col-xs-2">
					<button class="boletin" onClick="ver();" onMouseOver="style.cursor=cursor">
					<i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir&nbsp;prueba</button>
				</div>
				</div>

				</div>

					<div class="col-xs-8">
					<input type="hidden" id="archivo" name="DATA[archivo]">
					<div id="container" style="height:300px;  max-width:600px;  overflow:auto; background:#fff;">
							<img  id="containerimg" src="<?php echo $img_modelo;?>" style=" max-width:600px; " />
					</div>
					</div>
			</div>
			</div>
			</div>
			</div>
	</div><!-- fin 4b ********************************************-->
	<div class="tab-pane" id="5b">
	  <div class="container">	
				<div class="row">
				<div class="col-xs-2" >Configurar mail eFactura</div>
				</div><div class="row">
				<label class="control-label col-xs-1">Nombre:</label>
				<div class="col-xs-2"><input name="DATA[efemailname]" id="efemailname" value="<?php echo $datos_sistema['efemailname'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-1">Email:</label>
				<div class="col-xs-2"><input name="DATA[efemailsend]" id="efemailsend" value="<?php echo $datos_sistema['efemailsend'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-1">Password:</label>
				<div class="col-xs-2"><input type="password" name="DATA[efemailpass]" id="efemailpass" size="30" class="form-control input-sm" placeholder="Escriba contraseña"  ></input></div>
				</div><div class="row">
				<label class="control-label col-xs-1">Servidor/Host:</label>
				<div class="col-xs-2"><input name="DATA[efemailhost]" id="efemailhost" value="<?php echo $datos_sistema['efemailhost'];?>" maxlength="50" class="form-control input-sm" type="text"></div>
				<label class="control-label col-xs-1">Utilizar ssl</label>
				<div class="col-xs-2">
				<?php
				$ssl="";
					if ($datos_sistema['efemailssl']==1) {
						$ssl="checked";
					}
				?>	
				<label><input class="checkbox1" type="checkbox" name="DATA[efemailssl]" value="1" <?php echo $ssl;?> ><span></span></label>	
				&nbsp;
				Puerto:&nbsp;
				<input type="hidden" name="DATA[efemailpuerto]" id="efemailpuerto"/>
				<select class="form-control input-sm" id="bonus2" onchange="$('#efemailpuerto').val($('#bonus2 option:selected').val());">				
				<?php $tipopuerto= array(0=>"25", 1=>"465", 2=>"993");
					if ($datos_sistema['efemailpuerto']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipopuerto as $i) {
					  	if ( $i==$datos_sistema['efemailpuerto']) {
					  		?>
							<option value="<?php echo $i;?>" selected><?php echo $i;?></option>
							<?php
						} else {
							?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php
						}
					}
					?>				
				</select>
				</div>
				
				</div>
				<div class="row">
				<label class="control-label col-xs-1">Autenticación</label>
				<div class="col-xs-2">
				<?php
				$aut="";
					if ($datos_sistema['efemailaut']==1) {
						$aut="checked";
					}
				?>	
				<label><input class="checkbox1" type="checkbox" name="DATA[efemailaut]" value="1" > <span></span></label>	
				</div>
				
				</div>	
				<div class="row">
					<div class="col-xs-2">Archivos enviados por e-mail</div>
					<div class="col-xs-2">
						<select class="form-control input-sm" name="DATA[efemailtipo]">
				<?php $tipof = array(0=>"XML Entre Empresas", 1=>"XML Entre Empresas y Versión Impresa", 2=>"Versión impresa", 3=>"No enviar");
					if ($datos_sistema['efemailtipo']==" ")
					{
					echo '<option value="" selected>Selecione uno</option>';
					}
					foreach($tipof as $i=>$desc) {
					  	if ( $i==$datos_sistema['efemailtipo']) {
							echo "<option value=$i selected>$desc</option>";
						} else {
							echo "<option value=$i>$desc</option>";
						}
					}
					?>				
				</select></div></div>				
		</div>
	</div><!-- fin 5b **************************************-->
	<div class="tab-pane" id="6b">
			<div class="row">
				<div class="col-xs-6">
				<img src="../library/images/miscelaneo.png" width="563" height="224" alt="">
				</div>
			<div class="col-xs-4">
			<p>Para habilitar el envió de SMS es necesario tener instalado en el servidor donde se instalo UYCODEKA, Gammu Gateway SMS con acceso a la base de UYCODEKA en mysql</p>

			<p>http://nuurwahidanshary.wordpress.com/2012/02/10/configure-and-manage-gammu-sms-gateway-with-mysql-on-ubuntu-platform/</p>
			</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
					<div align="left">SMS - Aviso service terminado</div>
				</div>
				<?php
					if ($datos_sistema['smsservice']==1) {
						$leer="checked";
					}
				?>
				<div class="col-xs-1">
				<input type="hidden" name="smsservice" value="1">
				<input class="checkbox1" type="checkbox" name="DATA[smsservice]" value="<?php echo $datos_sistema['smsservice'];?>" >
			</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
					<div align="left">SMS - Aviso envío factura por mail</div>
				</div>
				<?php
					if ($datos_sistema['smsfactura']==1) {
						$leer="checked";
					}
				?>
				<div class="col-xs-1">
				<input type="hidden" name="DATA[smsfactura]" value="1">
				<input class="checkbox1" type="checkbox" name="DATA[smsfactura]" value="<?php echo $datos_sistema['smsfactura'];?>" >
				</div>
					<div class="col-xs-7">
					<div class="row">

				<div  class="col-xs-4">Banco/Moneda/Cuenta/Incluir&nbsp;en&nbsp;Factura:
				<div id="newItemMHide" style="display:block;">
					<img id="newItemM" src="../library/images/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="trigger">
				</div>
					<div id="newItemMShow" style="width: 400px;display: none; position:absolute; border: 0px solid #000; z-index:10;">
						<div class="seleccione">
							<div style="position:absolute; right:10px; top:3px;"> 
							<img id="newItemM" src="../library/images/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose">
							</div>
							<div>
							<div style="position:relative; top: 15px;">
							<div class="container">
							<div class="row">
							<div class="col-xs-2">Banco</div>
							<div class="col-xs-2"> 
							<select id="cboBanco" class="form-control input-sm">
							<option value="0" selected="">Seleccione&nbsp;una&nbsp;Entidad&nbsp;Bancaria</option>
							<?php
							foreach ($entidades as $key) {

							if (@$key["codentidad"] == @$datos_sistema["codentidad"]) {
							echo "<option value='".$key["codentidad"]."' selected>".$key["nombreentidad"]."</option>";
							} else {
							echo "<option value='".$key["codentidad"]."'>".$key["nombreentidad"]."</option>";
							}
							$xx++;
							}
							?>            
							</select>
							</div>
							</div>

							<div class="row"><div class="col-xs-2">Moneda</div><div class="col-xs-2">
							<select id="Amoneda" class="form-control input-sm">
							<?php $tipofa = array(  1=>"Pesos", 2=>"U\$S");
							if (@$moneda==" ")
							{
							echo '<option value="" selected>Selecione uno</option>';
							}
							foreach ($tipofa as $key => $i ) {
							if ( @$moneda==$key ) {
							echo "<option value=$key selected>$i</option>";
							} else {
							echo "<option value=$key>$i</option>";
							}
							}
							?>
							</select>
							</div>
							</div>
							<div class="row">
							<div class="col-xs-2">Nº&nbsp;Cuenta</div>
							<div class="col-xs-2">
							<input id="numcuenta" class="cajaPequena"/> </input>
							</div></div><div class="row"><div class="col-xs-2">
							<label> Incluir&nbsp;en&nbsp;Factura
							<?php
							$est='';
							$banco=$datos_sistema['bancos'];
							if(!empty($banco)) {
							$est=explode('#',$banco);
							}
							?>
							</label>		
							</div><div class="col-xs-2">
							<label>&nbsp;
								<input type="checkbox" id="incluir" value="0">
							<span></span>
							</label>
							</div></div></div>
							</div>         

							<div style="text-align: right;">
							<button class='btn left-margin btn-xs' data-dismiss="modal" onclick="agregarbanco();">
							Agregar
							</button>
							</div>
							</div>
						</div>
					</div>
				</div>
						</div>
				<div class="row">
					<div class="col-xs-3">      
					<select name="DATA[bancos][]" id="Vbancos" style="width: 300px; height:50px;" multiple="true" size="2" class="form-control input-sm ComboBox">
					<?php
					if (is_array($est)) {
					foreach ($est as $x)
						{
							echo "<option value='$x' >".$x."</option>";
						}
					} else {
						if (!empty($banco)) {
						echo "<option value='$banco' >".$banco."</option>";
					}
					}
					?>
					</select>
					</div>						
				</div>
			</div>
			</div>					
		<div class="row">
				<div class="col-xs-6" >Servidor auxiliar.
				<p> Este servidor es donde se aloja las imágenes de los articulos.
				</div>
			</div>
			<div class="row">
				<div class="col-xs-2">Server:</div>
				<div class="col-xs-2">
					<input name="DATA[serveraux]" id="serveraux" value="<?php echo $datos_sistema['serveraux'];?>" maxlength="50" class="form-control input-sm" type="text">
				</div>
			</div>			

	</div><!-- fin 6b -->
			</div>			
	</div><!-- Fin  -->			



<br style="line-height:15px">

	<div style="position: fixed; width: 100%; margin:0 auto; ">
		<div align="center">
			<button class="boletin" onClick="guardar();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
		</div>
	</div>

	</form>

<script>
function guardar() {
	   selectAll('Vbancos',true)	  
    document.getElementById("formulario").submit();
}
</script>
<script type="text/javascript">
		//apply masking to the demo-field
		//pass the field reference, masking symbol, and character limit
	//new MaskedPassword(document.getElementById("emailpass"), '\u25CF');
</script> 	

</body>
</html>			