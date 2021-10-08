<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

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

    <script src="../../library/js/OpenWindow-rejilla.js" type="text/javascript"></script>

    <script type="text/javascript" src="../../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../../library/estilos/customCSS.css" rel="stylesheet">



<script type="text/javascript">
function showToast(text, tipo){
  parent.showToast(text, tipo);
}

var codbiometric='';
$(document).ready(function(){
  
$('.trigger').click(function(e){

    e.preventDefault();
    codbiometric= $(e.currentTarget).attr("data-codbiometric"); 

});//Finaliza trigger

$('.triggerRejilla').click(function(e){
//e.preventDefault();
codbiometric = $(e.currentTarget).attr("data-codbiometric"); 
    if(codbiometric!=''){
        parent.$("codbiometric").val(codbiometric);
        //document.getElementById("codbiometric").value=codbiometric;
    }

});
/////////////////////////////
$('#actividad').click(function(e){
  var  codbiometrictmp=$("#codbiometric").val();
  if (codbiometrictmp != '') {
              OpenWindow('../logs/index.php?codbiometric='+codbiometrictmp, '#frame_rejilla','95%','95%', true, true, true);
        } else {
            showToast('<?php echo _('Seleccione uno');?>', "warning");
        }
}); 
/////////////////////////////
$('#modifica').click(function(e){
  var  codbiometrictmp=$("#codbiometric").val();
  if (codbiometrictmp != '') {
              OpenWindow('edit.php?codbiometric='+codbiometrictmp, '#frame_rejilla','95%','95%', true, true);
        } else {
            showToast('<?php echo _('Seleccione uno');?>', "warning");
        }
}); 

/////////////////////////////
$('#Elimina').click(function(e){
  var  codbiometrictmp=$("#codbiometric").val();

    if (codbiometrictmp != '') {
        eliminar(codbiometrictmp);				
    } else {
        showToast('<?php echo _('Seleccione uno');?>', "warning");
    }
}); 
/////////////////////////////
$('#nuevo').click(function(e){
  OpenWindow('create.php', '#frame_rejilla','95%','95%', true, true);
}); 
/////////////////////////////

});//Finaliza editar informe

var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            if(codbiometric > 0){
            var url = 'edit.php?codbiometric='+codbiometric;
                OpenWindow(url, form = '#frame_rejilla',w = '99%',h = '99%', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar paciente');?>', "warning"); 
            }
        break;
        case 112:
            showToast('<?php echo _('Ayuda aún no disponible...');?>','warning');
        break;
        case 45:
            var url = 'create.php';
            OpenWindow(url, form = '#frame_rejilla',w = '90%',h = '90%', Close = false, Scroll = false, CloseButton = false);
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
function eliminar(cod, tabla){
    parent.eliminar(cod, tabla);
}

        function showModal(action) {
			parent.showModal(action);
		}

	
		function descargar_info(codbiometric) {
			showModal(1);
				jQuery.ajax({
				 type: "POST",
				 url: "descargar_info.php",
				 data: {codbiometric:codbiometric },
				 async: true,
				 cache: false,
					beforeSend: function () {
		           window.top.toastr.info('Procesando');
					},				 
				 success: function(respuesta) { 
				parent.showModal(0);
					if (respuesta.estado==-1) {
						window.top.toastr.error('Error - equipo inaccesible');
					}else {
						window.top.toastr.success('Procesados '+respuesta.procesados+' registros');
					}
				},
				  error: function() {
					showModal(0);
				   window.top.toastr.error("Error al descarga datos desde el lector de huellas");
				}
				}); 			
	}
	
	function AllUserToDevice(codbiometric) {
        showModal(1);
        
		jQuery.ajax({
		 type: "POST",
		 url: "add_datosusuarios.php",
		 data: {codbiometric:codbiometric },
		 async: true,
		 cache: false,
			beforeSend: function () {
            window.top.toastr.info('Procesando');
			},			 
    		success: function(respuesta) { 
                console.log(respuesta[0]);
                showModal(0);
                if(respuesta[0].agregados!=0 && respuesta[0].agregados!='undefined'){
                    window.top.toastr.success('Agregados: '+respuesta[0].agregados);
                }
                if(respuesta[0].quitados!=0){
                    window.top.toastr.success('Quitados: '+respuesta[0].quitados);
                }
                if(respuesta[0].actualizados!=0){
                    window.top.toastr.success('Actualizados: '+respuesta[0].actualizados);
                }
                if(respuesta[0].fallos!=0){
                    window.top.toastr.success('Fallos: '+respuesta[0].fallos);
                }
	    	},
		    error: function(respuesta) { 
                console.log(respuesta);
			showModal(0);
		    window.top.toastr.error("Error - No se pueden actualizar los datos");
		    }
		}); 			
	}
		function act_device(codbiometric) {
			top.parent.showModal(1);
		jQuery.ajax({
		 type: "POST",
		 url: "act_device_datosusuarios.php",
		 data: {codbiometric:codbiometric },
		 async: true,
		 cache: false,
		 success: function(data) { 
		    top.parent.showModal(0);
            if (data==-1) {
                window.top.toastr.error("Error - equipo inaccesible");
            }
		},
		  error: function() {
			showModal(0);
		   window.top.toastr.error("Error");
		}
		}); 			
	}	
    function descargar_info(codbiometric) {
        top.parent.showModal(1);
        jQuery.ajax({
            type: "POST",
            url: "descargar_info.php",
            data: {codbiometric:codbiometric },
            async: true,
            cache: false,
            beforeSend: function () {
            window.top.toastr.info('Procesando');
            },				 
            success: function(respuesta) { 
                //console.log(respuesta[0]);

                top.parent.showModal(0);
                if (respuesta[0].estado==-1) {
                    window.top.toastr.error('Error - '+respuesta[0].detalle);
                }else {
                    window.top.toastr.success('Procesados: '+respuesta[0].procesados+', nuevos: '+respuesta[0].nuevos);
                }
            },
            error: function(respuesta) {
            top.parent.showModal(0);
            window.top.toastr.error('Error - '+respuesta[0].detalle);
            }
        }); 			
	}

</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->