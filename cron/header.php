<?php

//ini_set('display_errors', 0); // see an error when they pop up
//error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
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

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $page_title; ?></title>


        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.2/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>
<link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.css" rel="stylesheet"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

    <link href="../library/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="../library/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>


	<link rel="stylesheet" href="../library/colorbox/colorbox.css?u=<?php echo time();?>" />
	<script src="../library/colorbox/jquery.colorbox.js?u=<?php echo time();?>"></script>

    <script src="../library/js/cargadatos.js" type="text/javascript"></script>
    <script src="../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

<link href="../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
<script src="../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>

<script src="validar.js" type="text/javascript"></script>

<link rel="stylesheet" href="../library/js/msgBoxLight.css?u=<?php echo time();?>" type="text/css">
<script type="text/javascript" src="../library/js/jquery.msgBox.js"></script>

<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>


<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">

<script type="text/javascript">
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Tareas programadas');?>");


</script>

<script language="javascript">

var alto=window.parent.$("#alto").val()-160;

function setIframeHeight(id) {
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
    ifrm.style.visibility = 'hidden';
    ifrm.style.height = "10px"; // reset to minimal height ...
    // IE opt. for bing/msn needs a bit added or scrollbar appears
    ifrm.style.height = alto + "px";
    ifrm.style.visibility = 'visible';
}
</script>		

  
  <link href="../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../library/jquery-ui/jquery-ui.js"></script>


<style type="text/css">
/* <![CDATA[ */
h3 {
	margin:0;
}
br {
	clear:both;
}
.box {
	width:200px;
	float:left;
	font:8pt helvetica;
}
/* ]]> */
</style>
<script src="jsl.js" type="text/javascript"></script>
<script src="common.js" type="text/javascript"></script>
	<script src="jquery.min.js"></script>
	<script src="jquery-ui.min.js"></script>	

<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function init() {
	JSL.dom(".chooser").click(function(e) {
		var for_element = this.name.replace(/_chooser/,"");

		JSL.dom(for_element).disabled = (this.value !== "1");
	});
	
	JSL.dom("crontab-form").on("submit", function(e) {
		JSL.event(e).stop();
		
		var minute, hour, day, month, weekday,tareas;
		
		minute	= getSelection('minute');
		hour	= getSelection('hour');
		day		= getSelection('day');
		month	= getSelection('month');
		weekday	= getSelection('weekday');
		tareas = $("#tareas").find('option:selected').val();
		
		var command = JSL.dom("command").value;
		command=command.trim() + tareas.trim();
		JSL.dom("cron").value = minute + "\t" + hour + "\t" + day + "\t" + month + "\t" + weekday + "\t" + command;
		
		var cron=$("#cron").val();

			jQuery.ajax({
				 type: "POST",
				 url: "addcron.php",
				 data: {cron:cron },
				 async: true,
				 cache: false,
				 success: function(data){
				 	 window.location.href='index.php';
					}
			}); 		
		
	});
}

function getSelection(name) {
	var chosen;
	if(JSL.dom(name + "_chooser_every").checked) {
		chosen = '*';
	} else {
		var all_selected = [];
		JSL.dom("#" + name+ " option").each(function(ele) {
			if(ele.selected)
				all_selected.push(ele.value);
		});
		if(all_selected.length)
			chosen = all_selected.join(",");
		else
			chosen = '*';
	}
	return chosen;
}

function eliminar(cron) {
				jQuery.ajax({
				 type: "POST",
				 url: "delcron.php",
				 data: {cron:cron },
				 async: true,
				 cache: false,
				 success: function(data){
				 	window.location.href='index.php';
					}
			});
}
/* ]]> */
</script>

    </head>

    <body >

        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->
