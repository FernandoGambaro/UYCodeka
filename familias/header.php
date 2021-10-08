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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Familias de artículos');?>");


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



var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            var codfamilia=$('#codfamilia').val();
            if(codfamilia>=0){
                var url = 'edit.php?codfamilia='+codfamilia;
                OpenWindow(url, form = '#frame_rejilla',w = '500',h = '200', Close = false, Scroll = false, CloseButton = false);
                $('#codfamilia').val();
            }else{
              showToast('<?php echo _('Debe seleccionar registro');?>', 'error'); 
            }
        break;
        case 112:
          showToast('<?php echo _('Ayuda aún no disponible...');?>', 'warning');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '500' ,h = '200', Close = false, Scroll = false, CloseButton = false);
        break;
        case 13:

        break;
        
    }
});

</script>

<script type="text/javascript">

function eliminar(cod){
if(cod.length>0){
  //Una forma de solucionar un problema entre bootrap Modal y ajax, pues al borrar el primer elemento funciona, pero el segundo, no
  $("#Sendcod").val(cod);

  $("#mi-modal").modal('show');
    modalConfirm(function(confirm){
        if(confirm){

        $('#loginModal').modal('show');

        $("#PassSubmit").on("click", function(){
            var password = $('#inputPassword').val();
            var showone=0;
        $("#mi-modal").modal('hide');
            if (password=="1234" && password.length>0) {
              $("#inputPassword").val("");
              var cod=$("#Sendcod").val();
              var datasend={"codfamilia":cod};	
              data=$(this).serialize() + "&" + $.param(datasend);
              $.ajax({
              type: "POST",
              url: "delete.php",
              dataType: "json",
              cache: false,
              async: "open",
              data: datasend,
              success: function(data, textStatus, jQxhr ){
                //console.log(textStatus);
                var text = data["msg"];
                    if (text != "0"){
                      var n = text.includes("Fallo");
                      if(n>0){
                        tipo="error";
                      }else{
                        tipo="success";
                        $('form#form').submit();
                      }
                      if(showone==0){
                        showone=1;
                        showToast(text,tipo);
                      
                      }
                    }
                  }, 
              error: function(){
                console.log("No se ha podido obtener la información");
              }
              })


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
      $(this).removeData('bs.modal');
        // and empty the modal-content element
        $('#loginModal #PassSubmit #inputPassword').empty();
      $('#inputPassword').val('');

    });
  }
}

$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});

</script>

<script languge="javascript">
		
function imprimir() {
  var codcliente=$("#Acodcliente").val();
  var codusuario=$("#codusuarios").val();
  var fechaini=$("#fechaini").val();
  var fechafin=$("#fechafin").val();
event.preventDefault();
  var url ='';
  var opcionesprint=$("#opcionesprint").val();

  if ($('#opcionesaexcel').is(":checked") ){
    if (opcionesprint==4 || opcionesprint==5) {
      if (codusuario=='' && opcionesprint==4) {
        var titulo="<?php echo _('Detalles horas realizadas por usuario y por cliente entre ');?>";
      }else{
        if(opcionesprint==5){
          var titulo="<?php echo _('Detalles horas realizadas suario al cliente entre ');?>";
        }else{
        var titulo="<?php echo _('Detalles horas realizadas por proyecto entre');?> ";
        }
      }
      if(fechaini.length>3){
      var fechaini = moment(fechaini, 'DD-MM-YYYY').format('YYYY-MM-DD');
      }
      if(fechafin.length>3){
      var fechafin = moment(fechafin, 'DD-MM-YYYY').format('YYYY-MM-DD');
      }

				$.msgBox({ type: "prompt",
					title: "<?php echo _('Ingrese el nombre del archivo');?>",
					inputs: [
					{ header: "<?php echo _('Nombre de Archivo');?>", type: "text", name: "nombre" }],
					buttons: [
					{ type: "submit", value: "Aceptar" }, { type: "close", value:"Cancelar" }],
					success: function (result, values) {
              $(values).each(function (index, input) {
              v =  input.value ;
              console.log(fechaini);
              console.log(fechafin);
              console.log(codusuario);
              console.log(codcliente);
                });		
              if (v!="" && result !="Cancelar") {
                window.top.parent.showModal(1);
                  $.ajax({ 
                  type: "POST",
                  url: "../reportes/horas/DetallesClientesUsuariosExcel2.php",
                  cache: false,
                  data: { "fechaini" : fechaini, "fechafin" : fechafin, "codusuario": codusuario, "codcliente": codcliente,  "titulo": titulo, "file":v },
                  success: function(data, status){
                  if(status == 'success'){	
                  $('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
                  $('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
                  $('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
                  window.top.parent.showModal(0);
                    return false;
                    }else{					
                      showToast("<?php echo _('Se produjo un error, intentelo mas tarde');?>", 'error');
                    }
                  }
                });    											
              } else {
                if (result!="Cancelar") {
                  showToast("<?php echo _('Nombre de archivo incorrecto.');?>", 'warning');
                }
              }
						}
					});	
          //Finaliza exportar a excel
        }      
  }else{

  if (opcionesprint==1) {  
    url="../reportes/horas/ReporteRegistrosPendientes.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
    $.colorbox({href:url,
    iframe:true, width:"70%", height:"100%",
    });

  }
  if (opcionesprint==2) { 
    if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>', 'error');
      } else {
        parent.showModal(1);
        url="../reportes/horas/TareasRealizadasUsuario.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"70%", height:"100%",
        });
      }

  }
  if (opcionesprint==3) { 
    if (codcliente=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>', 'error');
      } else {
        parent.showModal(1);
        url="../reportes/horas/TareasRealizadasCliente.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"70%", height:"100%",
        });
      }

  }     
  }
}
function cambioseleccion() {
	var opcionesprint=$("#opcionesprint").val();
	if (opcionesprint <=3) {
		if ($('#opcionesaexcel').is(":checked")){
		$('#opcionesaexcel').prop( "checked", false );
		$('#opcionesaexcel').attr("disabled", true);
    }
  } else {
    $('#opcionesaexcel').prop( "checked", true );
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

  
  <link href="../library/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="../library/jquery-ui/jquery-ui.js"></script>


<style type="text/css">
.time-input-container{position:relative;display:inline-block;}
.time-input-field{width:90px;height:18px;padding:4px 4px 4px 24px!important;border-radius:2px;border:1px solid #aaa;-webkit-box-sizing:border-box;box-sizing:border-box;height:27px;}
.time-input-icon{width:16px;height:16px;display:block;position:absolute;top:50%;left:5px;margin-top:-6px;cursor:pointer;color:#999;}

.duration-input.hours-and-minutes>label{display:inline-block;}
.duration-input.hours-and-minutes>label>input{width:30px;height:27px;border:1px solid #aaa;padding:4px;border-radius:2px;}
.duration-input.hours-and-minutes>label>span{display:block;color:#aaa;margin-left:0;}

</style>

    </head>

    <body >

        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->