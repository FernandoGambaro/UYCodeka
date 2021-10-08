<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../../classes/class_session.php';
require_once __DIR__ .'/../../common/languages.php';
require_once __DIR__ .'/../../common/verificopermisos.php';   


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

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $page_title; ?></title>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

    <link href="../../library/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="../../library/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>


	<link rel="stylesheet" href="../../library/colorbox/colorbox.css?u=<?php echo time();?>" />
	<script src="../../library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

    <script src="../../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

<link href="../../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
<script src="../../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
<script src="../../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>

<link rel="stylesheet" href="../../library/js/msgBoxLight.css?u=<?php echo time();?>" type="text/css">
<script type="text/javascript" src="../../library/js/jquery.msgBox.js"></script>

<script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

 
<script  src="../../library/js/jquery-ui.js"></script>


<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">



<style>
.modal-dialog {
    margin: 20vh auto 0px auto
}
</style>
<script>
function cambio(){
  if(continuar==1){
  continuar = 0;
  $('p').append('<p><span><h1 style="color: #FF0000;">Finalizando tarea </h1></span><br></p>');
  }
}
</script>
    </head>

    <body >


<!-- Modal -->
<div class="modal fade" id="statusModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Procesando archivos</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="cambio();" id="botonCerar">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>

        <!-- container -->
        <div class="container">