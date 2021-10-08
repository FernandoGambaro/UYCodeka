<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/verificopermisos.php';   


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
  $paleta=isset($s->data['paleta']) ? $s->data['paleta'] : 1;

  $codcliente=isset($_GET["codcliente"]) ? $_GET["codcliente"] : ''; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="../library/js/OpenWindow-rejilla.js?u=<?php echo time();?>" type="text/javascript"></script>

    <script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">


<script type="text/javascript">

function showToast(text, tipo){
  parent.showToast(text, tipo);
}

var codhoras='';
$(document).ready(function(){
  
    $('.trigger').click(function(e){

        e.preventDefault();
        codhoras= $(e.currentTarget).attr("data-codhoras"); 
        parent.$('#codhoras').val(codhoras);
    });//Finaliza trigger


    $("#Modifico").click(function(e){ 
        //var codhoras= $(e.currentTarget).attr("data-codhoras"); 
        if(codhoras > 0){
            var url = 'edit.php?codhoras='+codhoras;
                OpenWindow(url, form = '#frame_rejilla',w = '500',h = '400', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast("<?php echo _('Debe seleccionar item');?>", 'error'); 
            }

    });//Finaliza editar item
    $("#Nuevo").click(function(e){ 
        var codcliente=parent.$("#Acodcliente").val();
        var url = 'create.php?codcliente='+codcliente;
        OpenWindow(url, form = '#frame_rejilla',w = '500',h = '400', Close = false, Scroll = false, CloseButton = false);
    });//Finaliza nuevo

});

var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            if(codhoras > 0){
            var url = 'edit.php?codhoras='+codhoras;
                OpenWindow(url, form = '#frame_rejilla',w = '500',h = '400', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast("<?php echo _('Debe seleccionar paciente');?>", 'warning'); 
            }
        break;
        case 112:
            showToast("<?php echo _('Ayuda aún no disponible...');?>", 'info');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '500' ,h = '400', Close = false, Scroll = false, CloseButton = false);
        break;
        case 13:
			var $targ = $(e.target);
            if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                var focusNext = false;
                $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                    if (this === e.target) {
                        focusNext = true;
                    }
                    else if (focusNext){
                        $(this).focus();
                        return false;
                    }
                });

                return false;
            }
        break;
        
    }
});

function eliminar(cod){
    parent.eliminar(cod);
}
function ver(archivo){
	parent.ver(archivo);
}
</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->