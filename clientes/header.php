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

<link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
<script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
<script src="../library/toastmessage/message.js" type="text/javascript"></script>

<script src="validar.js" type="text/javascript"></script>


<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>


<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">
<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">

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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Listado Clientes');?>");


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
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = false, CloseButton = false);
                $('#codhoras').val();
            }else{
              showToast('<?php echo _('Debe seleccionar uno');?>', 'error'); 
            }
        break;
        case 112:
        showToast('<?php echo _('Ayuda aún no disponible...');?>','info');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '98%' ,h = '98%', Close = false, Scroll = false, CloseButton = false);
        break;
        case 13:

        break;
        
    }
});

</script>
               

<script type="text/javascript">
function eliminar(cod, tabla){
  $("#Sendcod").val(cod);
  $("#Sendtabla").val(tabla);
  
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
              var tabla=$("#Sendtabla").val();
              var datasend={"cod":cod, "tabla":tabla};
              console.log(datasend);	
              $.ajax({ 
              type: "POST",
              url: "delete.php",
              cache: false,
              data: datasend,
              success: function(text){
                      if (text != " "){
                        var n = text.includes("Fallo");
                        if(n>0){
                          tipo="error";
                        }else{
                          tipo="success";
                          if(tabla=='clientes'){
                            $('#frame_rejilla').attr( 'src', function ( i, val ) { return val; });
                          }else{
                            $('#frame_proyectos').attr( 'src', function ( i, val ) { return val; });
                          }                          
                        }
                        if(showone==0){
                          showone=1;
                          showToast(text,tipo); 
                        $("#form").submit();
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

</script>

<script languge="javascript">
function GuardarProyecto(){

  var fechaini_proyecto = $('#fechaini_proyecto').val();
  var fechafin_proyecto = $('#fechafin_proyecto').val();
  var descripcion_proyecto = $('#descripcion_proyecto').val();
  var codcliente = $('#codcliente').val();
  var text='Algo';

  if (fechaini_proyecto=='' && descripcion_proyecto=='') {
    showToast('<?php echo _('Debe ingresar fecha inicio y descripción.');?>','warning');
  }else{
    $.ajax({ 
    type: "POST",
    url: "guardarproyecto.php",
    cache: false,
    data: { codcliente:codcliente,fechaini:fechaini_proyecto,fechafin:fechafin_proyecto,descripcion:descripcion_proyecto },
    success: function(resp){
      event.preventDefault;
        console.log("----"+ resp );
              if(resp != 1){
                tipo="warning";
                text="<?php echo _('No se pudo agregar proyecto');?>";
              }else{
                tipo="success";
                text="<?php echo _('Proyecto agregado con éxito');?>";
                $('#fechaini_proyecto').val();
                $('#fechafin_proyecto').val();
                $('#descripcion_proyecto').val();
              }
              showToast(text,tipo);

            $('#frame_proyectos').attr( 'src', function ( i, val ) { return val; });
        }
    });
  }

}

function GuardarEquipos(){

var fecha_equipos = $('#fecha_equipos').val();
var descripcion_equipos = $('#descripcion_equipos').val();
var alias_equipos = $('#alias_equipos').val();
var numero_equipos = $('#numero_equipos').val();
var service_equipos = $('#service_equipos').val();
var detalles_equipos = $('#detalles_equipos').val();
var diasemana_equipos = $('#diasemana_equipos').val();
var codcliente = $('#codcliente').val();
var text='Algo';

if (fecha_equipos=='' && descripcion_equipos=='') {
  showToast('<?php echo _('Debe ingresar fecha y descripción.');?>','warning');
}else{
  $.ajax({ 
  type: "POST",
  url: "guardarequipo.php",
  cache: false,
  data: { codcliente:codcliente,fecha:fecha_equipos,descripcion:descripcion_equipos,alias:alias_equipos,numero:numero_equipos,service:service_equipos,detalles:detalles_equipos,diasrespaldo:diasemana_equipos },
  success: function(resp){
    event.preventDefault;
      console.log("----"+ resp );
            if(resp != 1){
              tipo="warning";
              text="<?php echo _('No se pudo agregar equipo');?>";
            }else{
              tipo="success";
              text="<?php echo _('Datos agregado con éxito');?>";
                $('#fecha_equipos').val();
                $('#descripcion_equipos').val();
                $('#alias_equipos').val();
                $('#numero_equipos').val();
                $('#service_equipos').val();
                $('#detalles_equipos').val();
                $('#diasemana_equipos').val();
            }
            showToast(text,tipo);

          $('#frame_proyectos').attr( 'src', function ( i, val ) { return val; });
      }
  });
}

}

</script>
		

<script languge="javascript">
		
function grafico() {
  var codcliente=$("#Acodcliente").val();
  var codusuario=$("#codusuarios").val();
  var fechafin=$("#fechafin").val();
  var fechaini=$("#fechaini").val();
  
  var url ='';
  var opcionesgrafico=$("#opcionesgrafico").val();
  if (opcionesgrafico==1) {
      if (codcliente=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>','warning');
      } else {
        parent.showModal(1);
      var url="../reportes/horas/GraficoBase.php?codcliente="+codcliente+"&fechaini="+fechaini+"&fechafin="+fechafin;
      
        $.colorbox({href:url,
        iframe:true, width:"98%", height:"98%",iframe:true,
        });
      }
    }
    if (opcionesgrafico==2) {
      if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>', 'warning');
      } else {
        parent.showModal(1);
      var url="../reportes/horas/GraficoBaseUsuarios.php?codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"98%", height:"98%",
        });
      }
    }	
    if (opcionesgrafico==3) {
      if (codcliente=='' && codusuario=='') {
        showToast('<?php echo _('Debe seleccionar cliente y usuario.');?>','warning');
      }else if (codcliente=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>','warning');
      } else if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>', 'warning');
      } else {
        parent.showModal(1);
      var url="../reportes/horas/GraficoBaseUsuarios.php?codusuario="+codusuario+"&codcliente="+codcliente+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"98%", height:"98%",
        });
      }
    }	    		
    if (opcionesgrafico==4) { 
      parent.showModal(1);
      var url="../reportes/horas/horasasignadas.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
      $.colorbox({href:url,
      iframe:true, width:"98%", height:"98%",
      });	

	    parent.showModal(0);
				
    }							

    if(opcionesgrafico>=13 ){
    var url="../reportes/horas/GraficoProyectosMes.php?codcliente="+codcliente+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
    $.colorbox({href:url,
    iframe:true, width:"98%", height:"98%",
    });			
    } 
  

}

function imprimir() {
  var codcliente=$("#Acodcliente").val();
  var codusuario=$("#codusuarios").val();
  var fechaini=$("#fechaini").val();
  var fechafin=$("#fechafin").val();

  var url ='';
  var opcionesprint=$("#opcionesprint").val();

  if (opcionesprint==1) {  
    url="../reportes/clientes/Listado.php";
    $.colorbox({href:url,
    iframe:true, width:"98%", height:"98%",
    });

  }
  if (opcionesprint==2) {  
    url="../reportes/clientes/Listado.php?service=2";
    $.colorbox({href:url,
    iframe:true, width:"98%", height:"98%",
    });

  }
  if (opcionesprint==3) { 
    var codcliente = $('#codcliente').val();
    if (codcliente=='') {
      event.preventDefault();
      showToast('<?php echo _('Debe seleccionar cliente.');?>','warning');
    }else {
    url="../reportes/clientes/Detalles.php?codcliente="+codcliente;
    $.colorbox({href:url,
    iframe:true, width:"98%", height:"98%",
    });
    }

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
     
    
    $("#Acliente").autocomplete({
        source: '../common/busco_clientes.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];

		$("#Acodcliente").val(pref);
		$("#Acliente").val(nombre);
		}
	}).autocomplete("widget").addClass("fixed-height");

    $("#Ausuarios").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
	
			$("#codusuarios").val(pref);
			$("#Ausuarios").val(nombre);

			}
	}).autocomplete("widget").addClass("fixed-height");

});
</script>		
	<script type="text/javascript" >

function restarHoras() {

  inicio = document.getElementById("horaini").value;
  fin = document.getElementById("horafin").value;
 
  if(inicio!='' && fin!=''){
    setTimeout(function(){
    inicioMinutos = parseInt(inicio.substr(3,2));
    inicioHoras = parseInt(inicio.substr(0,2));
    
    finMinutos = parseInt(fin.substr(3,2));
    finHoras = parseInt(fin.substr(0,2));

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
    if(horas>12){
      showToast("<?php echo _('Metistes horas hoy');?><br>"+horas+":"+minutos, 'info');
    }
    if(horas>0){
    $("#combohorasminutos").val(horas+":"+minutos).change();
    $("#horasminutos").val(horas+":"+minutos);

    var codcliente = $("#Acodcliente").val();
    var fecha = $("#fecha").val();
    if (codcliente.length!=0) {
      //console.log(codcliente );;
          $.ajax({ 
          type: "POST",
          url: "checkhoras.php",
          cache: false,
          data: { "codcliente": codcliente, "fecha" : fecha, "horas" : horas+":"+minutos},
          success: function(text){
              //console.log("----"+ data );;
                if (text != "0"){
                  var n = text.indexOf("pasados");
                  if(n>0){
                    tipo="notice";
                  }else{
                    tipo="success";
                  }
                  showToast(text,tipo);
                }
              }
          });
        }
      }else{

        showToast("Horas registradas no válidas","warning");        
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