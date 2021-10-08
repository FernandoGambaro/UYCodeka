<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../../classes/class_session.php';
require_once __DIR__ .'/../../common/languages.php';
require_once __DIR__ .'/../../common/verificopermisos.php';   


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
    <link rel="stylesheet" href="../../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />

<link href="../../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>


    <script src="../../library/js/OpenWindow-rejilla.js?u=<?php echo time();?>" type="text/javascript"></script>

    <script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">

<link href="../../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>


<style type="text/css">
.time-input-container{position:relative;display:inline-block;}
.time-input-field{width:90px;height:18px;padding:4px 4px 4px 24px!important;border-radius:2px;border:1px solid #aaa;-webkit-box-sizing:border-box;box-sizing:border-box;height:27px;}
.time-input-icon{width:16px;height:16px;display:block;position:absolute;top:50%;left:5px;margin-top:-6px;cursor:pointer;color:#999;}

.duration-input.hours-and-minutes>label{display:inline-block;}
.duration-input.hours-and-minutes>label>input{width:30px;height:27px;border:1px solid #aaa;padding:4px;border-radius:2px;}
.duration-input.hours-and-minutes>label>span{display:block;color:#aaa;margin-left:0;}

</style>

<script type="text/javascript">

function showToast(text, tipo){
  parent.showToast(text, tipo);
}

var codproyectos='';
$(document).ready(function(){
  
    $('.trigger').click(function(e){

        e.preventDefault();
        codproyectos= $(e.currentTarget).attr("data-codproyectos"); 
        parent.$('#codproyectos').val(codproyectos);
    });//Finaliza trigger


    $("#Modifico").click(function(e){ 
        //var codproyectos= $(e.currentTarget).attr("data-codproyectos"); 
        if(codproyectos > 0){
            var url = 'edit.php?codproyectos='+codproyectos;
                OpenWindow(url, form = 'form',w = '98%',h = '98%', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar item');?>', 'warning'); 
            }

    });//Finaliza editar item
    $("#Nuevo").click(function(e){ 
        var url = 'create.php';
        OpenWindow(url, form = 'form',w = '98%',h = '98%', Close = false, Scroll = false, CloseButton = false);
    });//Finaliza nuevo

});

var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            if(codproyectos > 0){
            var url = 'edit.php?codproyectos='+codproyectos;
                OpenWindow(url, form = 'form',w = '98%',h = '98&', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar proyecto');?>', 'warning'); 
            }
        break;
        case 112:
        showToast('<?php echo _('Ayuda aún no disponible...');?>', 'info');
        break;
        
    }
});

function eliminar(cod,dir='horario'){
    parent.eliminar(cod,dir);
}
       
</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->