<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors


require_once __DIR__ .'/../../common/funcionesvarias.php';
require_once __DIR__ .'/../../classes/class_session.php';
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


$page_title="Listado de equipos biometricos";

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

    <script src="../../library/js/cargadatos.js" type="text/javascript"></script>
    <script src="../../library/js/OpenWindow.js?u=<?php echo time();?>" type="text/javascript"></script>

<link href="../../library/bootstrap-date/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../../library/bootstrap-date/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="../../library/bootstrap-date/locales/bootstrap-datetimepicker.es.js" charset="UTF-8"></script>

<link rel="stylesheet" href="../../library/toastmessage/jquery.toastmessage.css?u=<?php echo time();?>" type="text/css">
<script src="../../library/toastmessage/jquery.toastmessage.js?u=<?php echo time();?>" type="text/javascript"></script>
<script src="../../library/toastmessage/message.js?u=<?php echo time();?>" type="text/javascript"></script>

<script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>
 
<script  src="../../library/js/jquery-ui.js"></script>

<!-- iconos para los botones -->       
<link rel="stylesheet" href="../../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">

<style>
.toggle-off.btn-xs {
    padding-left: 6px;
}
.btn, .input-group-addon {
    min-width: 27px;
}
.btn {
    padding: 0.275rem 0.55rem;
}
</style>



<script type="text/javascript">


$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Equipos biométricos');?>");

 var modalConfirm = function(callback){
  
  $("#modal-btn-si").on("click", function(){
    callback(true);
    $("#mi-modal").modal('hide');
  });
  
  $("#modal-btn-no").on("click", function(){
    callback(false);
    $("#mi-modal").modal('hide');
  });
};


function ExisteUsuario(codbiometric){
    $("#mi-modal").modal('show');
    modalConfirm(function(confirm){
        if(confirm){
            if(codbiometric!=''){

            }
        } 
        $('#loginModal').modal('hide');      
    });
}


var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            var codbiometric=$('#codbiometric').val();
            if(codbiometric>=0){
                var url = 'edit.php?codbiometric='+codbiometric;
                OpenWindow(url, form = '#frame_rejilla',w = '99%',h = '99%', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar uno');?>', 'warning'); 
            }
        break;
        case 112:
            showToast('<?php echo _('Ayuda aún no disponible...');?>', 'info');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '90%',h = '90%', Close = false, Scroll = false, CloseButton = false);
        break;
        case 13:

        break;
        
    }
});

</script>

<script type="text/javascript">

$(document).ready(function(){

    $(".tab-pane").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab-pane:first").show();

    $("ul.tabs li").click(function() {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tab-pane").hide();
        var activeTab = $(this).find("a").attr("href");
        $(activeTab).show();
        return false;
    });

    $(window.location.hash).click(); // super-simple, no? :)

/////////////////////////////
<?php
if($page_title != "modifico"){
?>
$('#Elimina').click(function(e){
  var  codbiometrictmp=$("#codbiometric").val();

  if (codbiometrictmp != '') {
        var NowMoment = moment().format('YYYY-MM-DD');
        var Date =  fecha;
        var dias = moment().diff(moment(Date), 'days');
        if ( parseInt(estado) == 0 || parseInt(dias) <3 ) {
            eliminar(codbiometrictmp);				
        } else {
          showToast('<?php echo _('Error Informe cerrado');?>', 'error');
          }
    } else {
      showToast('<?php echo _('Seleccione estudio');?>', 'warning');
  }
});
<?php } ?> 
/////////////////////////////
$('#nuevo').click(function(e){
  OpenWindow('create.php', '#frame_rejilla','95%','95%', true, true);
}); 
/////////////////////////////
});
function showModal(action) {
			top.parent.showModal(action);
		}


</script>

<script type="text/javascript">
<?php
if($page_title != "modifico"){
?>
function eliminar(cod){
  $("#Sendcod").val(cod);
    $("#mi-modal").modal('show');
    modalConfirm(function(confirm){
        if(confirm){

        $('#loginModal').modal('show');

        $("#PassSubmit").on("click", function(){
            var password = $('#inputPassword').val();
            var showone=0;
        $("#mi-modal").modal('hide');
            if (password == "1234" && password.length>0) {
              $('#inputPassword').val('');	
              var cod=$("#Sendcod").val();
              var datasend={"cod":cod};	
              $.ajax({ 
              type: "POST",
              url: "delete.php",
              cache: false,
              data: datasend,
              success: function(text){
                      if (text != "0"){
                        var n = text.includes("Fallo");
                        if(n>0){
                          tipo="error";
                        }else{
                          tipo="success";
                          $('#frame_rejilla').attr( 'src', function ( i, val ) { return val; });
                        }
                        if(showone==0){
                          showone=1;
                          showToast(text,tipo); 
                        }
                      }
                  }
              });

            } else {
              if(showone==0 && password.length>0){
                showone=1;
                showToast('<?php echo _('Contraseña erronea');?>', 'error');

              }            
            }
            $('#inputPassword').val('');
            $('#loginModal').modal('hide');        
        });
        $("#mi-modal").modal('hide');
        }
    });	

    $("#loginModal").on("hide.bs.modal", function () {
      $('#inputPassword').val('');
    });
}
<?php } ?>
function consultar() {
			var ip=$("#direccionip").val();
			var udp_port=$("#udpport").val();

console.log(ip);
			if ($("#direccionip").val().length>5 && $("#udpport").val()>0) {
				top.parent.showModal(1);
				jQuery.ajax({
				 type: "POST",
				 url: "consulto.php",
				 data: {ip:ip, udp_port:udp_port },
				 async: true,
				 cache: false,
				 success: function(data){ 
				var thisValue = data.replace(new RegExp('"', 'g'),"");;
				thisValue =thisValue.replace(new RegExp('u0000', 'g'),"");
				thisValue =thisValue.replace(/[~!@#$%^&()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
		
				var aplataform=thisValue.split("*")[0];
				var afirmware=thisValue.split("*")[1];
				var aserialnumber=thisValue.split("*")[2];
				var adevicename=thisValue.split("*")[3];
		
				$("#plataform").val(aplataform);
				$("#firmware").val(afirmware);
				$("#serialnumber").val(aserialnumber);		 
				$("#devicename").val(adevicename);		
				top.parent.showModal(0); 
				},
				  error: function() {
				   top.parent.showModal(0);
				   showToast("Error", 'error');
				}
				});
		} 	else {
			showToast("Ingrese dirección válida", 'info');
		}		
	}

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
    </head>

    <body >

        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->