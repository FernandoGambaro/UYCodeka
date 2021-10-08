<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php';   


if (!$s = new session()) {
	  echo "<h2>"._('Ocurrió un error al iniciar session!')."</h2>";
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

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="../library/js/OpenWindow-rejilla.js" type="text/javascript"></script>

    <script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">


<script type="text/javascript">

function showToast(text, tipo){
  parent.showToast(text, tipo);
}

var codarticulo='';
$(document).ready(function(){
  
    
    $('.trigger').click(function(e){

        e.preventDefault();
        codarticulo= $(e.currentTarget).attr("data-codarticulo"); 
        parent.$('#codarticulo').val(codarticulo);
    });//Finaliza trigger


    $("#Modifico").click(function(e){ 
        //var codarticulo= $(e.currentTarget).attr("data-codarticulo"); 
        if(codarticulo > 0){
            var url = 'edit.php?codarticulo='+codarticulo;
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = true, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar item');?>','warning'); 
            }

    });//Finaliza editar item
    $("#Nuevo").click(function(e){ 
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = true, CloseButton = false);
    });//Finaliza nuevo

});

var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            if(codarticulo > 0){
            var url = 'edit.php?codarticulo='+codarticulo;
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar paciente');?>','warning'); 
            }
        break;
        case 112:
            showToast('<?php echo _('Ayuda aún no disponible...');?>','warning');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '98%' ,h = '98%', Close = false, Scroll = true, CloseButton = false);
        break;
       
    }
});

function eliminar(cod, tabla){
    parent.eliminar(cod, tabla);
}
</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->