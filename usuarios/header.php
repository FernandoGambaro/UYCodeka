<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors


require_once __DIR__ .'/../common/funcionesvarias.php';
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


$page_title="Listado de usuarios";

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

<script src="../library/js/jquery.msgBox.js" type="text/javascript"></script>
	<link href="../library/js/msgBoxLight.css" rel="stylesheet" type="text/css">


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
function showModal(action) {
	parent.showModal(action);
}

$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Usuarios');?>");

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


function ExisteUsuario(codusuarios){
    $("#mi-modal").modal('show');
    modalConfirm(function(confirm){
        if(confirm){
            if(codusuarios!=''){

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
            var codusuarios=$('#codusuarios').val();
            if(codusuarios>=0){
                var url = 'edit.php?codusuarios='+codusuarios;
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
$('#Elimina').click(function(e){
  var  codusuariostmp=$("#codusuarios").val();

  if (codusuariostmp != '') {
              var NowMoment = moment().format('YYYY-MM-DD');
              var Date =  fecha;
              var dias = moment().diff(moment(Date), 'days');
              if ( parseInt(estado) == 0 || parseInt(dias) <3 ) {
                  eliminar(codusuariostmp);				
              } else {
                showToast('<?php echo _('Error Informe cerrado');?>', 'error');
                }
         } else {
            showToast('<?php echo _('Seleccione uno');?>', 'warning');
        }
}); 
$('#opcionesimpresion').click(function(e){
  if($(this).val()==2){
    $('#opcionesaexcel').prop("checked", true);
    $('#mesBox').show();
    $('#anioBox').show();
  }else{
    $('#opcionesaexcel').prop("checked", false);
    $('#mesBox').hide();
    $('#anioBox').hide();
  }
});
/////////////////////////////
$('#nuevo').click(function(e){
  OpenWindow('create.php', '#frame_rejilla','95%','95%', true, true);
}); 
/////////////////////////////
});

</script>

<script type="text/javascript">

function imprimir() {
			
    var url ='';
    var nombre=document.getElementById("nombre").value;
    var apellido=document.getElementById("apellido").value;
    var telefono=document.getElementById("telefono").value;
    var tratamiento=document.getElementById("tratamiento").value;
    var estado=document.getElementById("estado").value;
    var mes=document.getElementById("mes").value;
    var anio=document.getElementById('anio').value;
    var opcionesimpresion=document.getElementById("opcionesimpresion").value;

    if ($('#opcionesaexcel').is(":checked")){
      
      if (opcionesimpresion==1) {
        url = "reportes/vistaActual.php?nombre="+nombre+"&apellido="+apellido+"&telefono="+telefono+"&tratamiento="+tratamiento+"&estado"+estado;
      }
      
      if (opcionesimpresion==2) {
        url = "../excel/AtendanceHorasCompleto.php?mes="+mes+"&anio="+anio;
        
      } 
      //console.log(url);
      event.preventDefault();
      $.msgBox({ type: "prompt",
          title: "Ingrese el nombre del archivo sin extención",
          inputs: [
          { header: "Nombre de Archivo", type: "text", name: "nombre" }],
          buttons: [
          { value: "Aceptar" }, { value:"Cancelar" }],
          success: function (result, values) {
                    $(values).each(function (index, input) {
                    v =  input.value ;
                      });	
            if (v!="") {
              $.get("../excel/preparo.php?file="+v,function (data,status) { });
              top.parent.showModal(1);
                
                $.get(url+"&file="+v, function(data, status) {
                if(status == 'success'){	
                $('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
                $('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
                $('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');
                top.parent.showModal(0);	
                  return false;
                  }else{
                    top.parent.showModal(0);					
                    showToast('<?php echo _('Error, intente mas tarde:');?>', 'info');
                  }
              });    											
            } else {
              if (result!="Cancelar") {
                top.parent.showModal(0);
                showToast('<?php echo _('Error, intente mas tarde:');?>', 'info');
              }
            }					  														    	
          }
        });	
      } else {				
        if (opcionesimpresion=='1') {

          url = "reportes/vistaActual.php?nombre="+nombre+"&apellido="+apellido+"&telefono="+telefono+"&tratamiento="+tratamiento+"&estado"+estado;
          window.open(url,"reporte");
        }
        if (url!='') {
        window.open(url,"reporte");										
          return false;		
        }	
    }		
	}

function eliminar(cod, dir){
  $("#Sendcod").val(cod);
  var Url=dir+'/delete.php?cod='+cod;
  var Frame='frame_'+dir;
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
              //var cod=$("#Sendcod").val();
              var datasend={"cod":cod};	
              $.ajax({ 
              type: "POST",
              url: Url,
              cache: false,
              data: datasend,
              success: function(text){
                  if (text != "0"){
                    var n = text.includes("Fallo");
                    if(n>0){
                      tipo="error";
                    }else{
                      tipo="success";
                      $('#'+Frame).attr( 'src', function ( i, val ) { return val; });
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

  function guardohorario(){
    var vigencia=$("#vigencia").val();
    var horaingreso=$("#horaingreso").val();
    var horasalida=$("#horasalida").val();
    var descanso=$("#descanso").val();

    var codusuarios=$("#codusuarios").val();

    if(horaingreso==''){
      showToast('<?php echo _('Seleccione hora Ingreso');?>', 'error');
      return false
    }
    if(horasalida==''){
      showToast('<?php echo _('Seleccione hora Salida');?>', 'error');
      return false
    }
    if (!$('input.checkbox_check').is(':checked')) {
      showToast('<?php echo _('Seleccione día/s');?>', 'error');
      return false
    }

    var checkboxes_value = []; 
        var inputval=$(".checkbox_check").val();//getting value of input field
       $('.checkbox_check').each(function(){  
            //if($(this).is(":checked")) { 
            if(this.checked) {              
                 checkboxes_value.push($(this).val());                                                                               
            }  
       });                              
    var diasemana = checkboxes_value.toString(); 


    var datasend={"vigencia" : vigencia, "horaingreso": horaingreso, "horasalida": horasalida, "descanso": descanso, "diasemana":diasemana, "codusuarios": codusuarios};	
    $.ajax({ 
    type: "POST",
    url: "horario/guardohorario.php",
    cache: false,
    data: datasend,
    success: function(text){
            if (text != "0"){
              var n = text.includes("Fallo");
              if(n>0){
                tipo="error";
              }else{
                tipo="success";
                $('#frame_horario').attr( 'src', function ( i, val ) { return val; });
              }
                showToast(text,tipo);
            }
        }
    });            
  }
  function limpiarhorario(){
    $("#horaingreso").val('');
    $("#horasalida").val('');
    $("#descanso").val('');
    $("#diasemana_horario").val('');
    $("#vigencia").val();
  }

  function guardolicencia(){
    var desde=$("#desde").val();
    var hasta=$("#hasta").val();

    var codusuarios=$("#codusuarios").val();

    if(desde==''){
      showToast('<?php echo _('Seleccione fecha desde');?>', 'error');
      return false
    }
    if(hasta==''){
      showToast('<?php echo _('Seleccione fecha hasta');?>', 'error');
      return false
    }


    var datasend={"desde" : desde, "hasta": hasta, "codlicencia": '',  "codusuarios": codusuarios};	
    $.ajax({ 
    type: "POST",
    url: "licencia/guardolicencia.php",
    cache: false,
    data: datasend,
    success: function(text){
            if (text != "0"){
              var n = text.includes("Fallo");
              if(n>0){
                tipo="error";
              }else{
                tipo="success";
                $('#frame_licencia').attr( 'src', function ( i, val ) { return val; });
              }
                showToast(text,tipo);
            }
        }
    });            
  }

  function limpiarlicencia(){
    $("#desde").val('');
    $("#hasta").val('');
  }

</script>

<script language="javascript">

var alto=window.parent.$("#alto").val()-60;

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
<style>
#navbar {
background-color: orange;
}
#navbar a {
color: blue ;
text-align: center;
padding: 2px 5px;
text-decoration: none;
font-size: 12px;
}
.contento {
padding: 5px;
}
.stickyHeader {
position: relative;
width: 100%;
}
</style>
       
    </head>

    <body >

        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->