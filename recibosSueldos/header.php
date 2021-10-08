<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

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
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Recibos de sueldo');?>");


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
            var codhoras=$('#codhoras').val();
            if(codhoras>=0){
                var url = 'edit.php?codhoras='+codhoras;
                OpenWindow(url, form = '#frame_rejilla',w = '500',h = '400', Close = false, Scroll = false, CloseButton = false);
                $('#codhoras').val();
            }else{
              showToast('<?php echo _('Debe seleccionar registro');?>', 'error'); 
            }
        break;
        case 112:
          showToast('<?php echo _('Ayuda aún no disponible...');?>', 'warning');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '500' ,h = '400', Close = false, Scroll = false, CloseButton = false);
        break;
        case 13:

        break;
        
    }
});

</script>

<script language="javascript">
jQuery(document).ready(function() {
	var printto = '<button class="btn btn-primary btn-xs" onClick="PrintMe(\'cboxLoadedContent\');"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Imprimir</button></div>';
	jQuery("#cboxContent").append('<div id="cboxSocials" style="text-align:center">'+printto+'</div>');
});


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
   docprint.document.write('<head><title>My Title</title>');
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

function ver(file){

var archivo="data/"+file;


var check = window.open(archivo,'_blank');
if(check != null) {
	jQuery.ajax({
		 type: "POST",
		 url: "actualizoVisto.php",
		 data: {archivo:file },
		 async: true,
		 	 cache: false,
		 success: function(data){ 
			console.log(data);
		}
	});
}
//alert(archivo);

}



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
              var datasend={"codrecibo":cod};	
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
		
function grafico() {
  event.preventDefault();
  var codcliente=$("#Acodcliente").val();
  var codusuario=$("#codusuarios").val();
  var fechafin=$("#fechafin").val();
  var fechaini=$("#fechaini").val();
  
  var url ='';
  var opcionesgrafico=$("#opcionesgrafico").val();
  if (opcionesgrafico==1) {
      if (codcliente=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>', 'error');
      } else {
        if ($.isFunction(window.top.parent.showModal)) {
        window.top.parent.showModal(1);
        }
        
      var url="../reportes/horas/GraficoBase.php?codcliente="+codcliente+"&fechaini="+fechaini+"&fechafin="+fechafin;
      
        $.colorbox({href:url,
        iframe:true, width:"100%", height:"100%",iframe:true,
        });
      }
    }
    if (opcionesgrafico==2) {
      if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>','warning');
      } else {
        window.top.parent.showModal(1);
      var url="../reportes/horas/GraficoBaseUsuarios.php?codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"100%", height:"100%",
        });
      }
    }	
    if (opcionesgrafico==3) {
      if (codcliente=='' && codusuario=='') {
        showToast('<?php _('Debe seleccionar cliente y usuario.');?>', 'warning');
      }else if (codcliente=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>', 'warning');
      } else if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>', 'warning');
      } else {
        window.top.parent.showModal(1);
      var url="../reportes/horas/GraficoBaseUsuarios.php?codusuario="+codusuario+"&codcliente="+codcliente+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"100%", height:"100%",
        });
      }
    }	    		
    if (opcionesgrafico==4) { 
      if ($.isFunction(parent.showModal)) {
        window.top.parent.showModal(1);
        }      
      var url="../reportes/horas/horasasignadas.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
      $.colorbox({href:url,
      iframe:true, width:"100%", height:"100%",
      onComplete: function(){
        setTimeout(function(){
          if ($.isFunction(parent.showModal)) {
            window.top.parent.showModal(0);
            } 
          }, 2000);
      }      
      });	
				
    }							

    if(opcionesgrafico>=13 ){
    var url="../reportes/horas/GraficoProyectosMes.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
    $.colorbox({href:url,
    iframe:true, width:"100%", height:"100%",
    onComplete: function(){
      setTimeout(window.top.parent.showModal(0), 2000);
      }
    });			
    } 
  

}

function imprimir() {
  var codcliente=$("#Acodcliente").val();
  var codusuario=$("#codusuarios").val();
  var fechaini=$("#fechaini").val();
  var fechafin=$("#fechafin").val();
event.preventDefault();
  var url ='';
  var opcionesprint=$("#opcionesprint").val();

  if ($('#opcionesaexcel').is(":checked") ){
    if (opcionesprint==4) {
      if (codusuario=='') {
        var titulo="<?php echo _('Detalles horas realizadas por usuario y por cliente entre ');?>";
      }else{
        var titulo="<?php echo _('Detalles horas realizadas por proyecto entre');?> ";
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
              //alert(v);
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


<script>
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
  var re = new RegExp($.trim(this.term.toLowerCase()));
  var t = item.label.replace(re, "<span >" + $.trim(this.term.toLowerCase()) +
    "</span>");
  return $("<li></li>")
    .data("item.autocomplete", item)
    .append("<a>" + t + "</a>")
    .appendTo(ul);
};

 $(document).ready(function () {
     
$('#opcionesaexcel').prop( "checked", false );
$('#opcionesaexcel').attr("disabled", true);
    
    $("#Ausuarios").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
	
			$("#nempleado").val(pref);
			$("#Ausuarios").val(nombre);

			}
	}).autocomplete("widget").addClass("fixed-height");
	
  $("#proyectos").autocomplete({
    		source: function(request, response) {
            $.ajax({
                url: "../common/busco_proyectos.php",
                dataType: "json",
                data: {
                    term: request.term,
                    codcliente: $("#Acodcliente").val(), 
                },
                success: function(data) {
							var codigo=$("#Acodcliente").val();
							if (codigo=="") {
								alert("Debe introducir cliente");
								return false;
							}                	
                    response(data);
                }
            });
        },
    	  minLength:1,
        autoFocus:true,
        select: function(event, ui) {
           	
         var name = ui.item.value;
         var thisValue = ui.item.data;
			var codproyectos=thisValue.split("~")[0];
			var proyecto=thisValue.split("~")[1];

			$("#codproyectos").val(codproyectos);
			$("#proyectos").val(proyecto);

		}
	}).autocomplete("widget").addClass("fixed-height");


});
</script>		
	<script type="text/javascript" >

function restarHoras() {
event.preventDefault();
  inicio = document.getElementById("horaini").value;
  fin = document.getElementById("horafin").value;
 
  if(inicio!='' && fin!=''){
    setTimeout(function(){
    inicioMinutos = parseInt(inicio.substr(3,2));
    inicioHoras = parseInt(inicio.substr(0,2));
    
    finMinutos = parseInt(fin.substr(3,2));
    finHoras = parseInt(fin.substr(0,2));

  var Hini=inicioHoras*60 + inicioMinutos;
  var Hfin=finHoras*60 + finMinutos;


if(Hini-Hfin>0){
  var text="La hora de inicio es menor a la de finalización ";
      parent.showToast(text, 'error');
      
}else{

    transcurridoMinutos = finMinutos - inicioMinutos;
    transcurridoHoras = finHoras - inicioHoras;
    

    if (transcurridoMinutos < 0) {
      transcurridoHoras--;
      transcurridoMinutos = 60 + transcurridoMinutos;
    }
    
    horas = transcurridoHoras.toString();
    minutos = transcurridoMinutos.toString();
    
    if (horas.length < 2) {
      horas = "0"+horas;
    }
    
    if (minutos.length < 2) {
      minutos = "0"+minutos;
    }
    if(horas>=12){
      var text="<?php echo _('Metistes horas hoy');?><br>"+horas+":"+minutos;
      parent.showToast(text, 'info');
    }

      if(horas>0 || transcurridoMinutos>0 ){
      $("#combohorasminutos").val(horas+":"+minutos).change();
      $("#horasminutos").val(horas+":"+minutos);
      //console.log(horas );

      var codcliente = $("#Acodcliente").val();
      var fecha = $("#fecha").val();
      if (codcliente.length!=0) {
        //
          $.ajax({ 
          type: "POST",
          url: "checkhoras.php",
          cache: false,
          data: { "codcliente": codcliente, "fecha" : fecha, "horas" : horas+":"+minutos},
          success: function(text){
              //console.log( text );
                if (text.length>0){
                  var n = text.toLowerCase().indexOf("pasados");
                  if(n>=0){
                    tipo="warning";
                  }else{
                    tipo="success";
                  }
                  parent.showToast(text, tipo);
                }else{
                  parent.showToast(text, 'info');
                }
              }
          });
        }
      }else{
        parent.showToast("<?php echo _('Horas registradas no válidas');?>", 'error');        
      }

    }
  }, 20);
  }
}

</script>
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