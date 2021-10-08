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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo 'Evaluaciones';?>");

function nuevo() {
  $("#seleccionar").toggle();
  
}

function nuevo_feedback() {
			$("#seleccionar").toggle();
			var codformulario=$("#Atipoformulario").val();
			if (codformulario!='') {
      var url='feedback.php?codformulario='+codformulario;
      OpenWindow(url, '#frame_rejilla','99%','99%');
			}else {
        showToast('<?php echo 'Debe seleccionar formulario';?>', 'error'); 
				return false;
			}
		}

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
              showToast('<?php echo 'Debe seleccionar registro';?>', 'error'); 
            }
        break;
        case 112:
          showToast('<?php echo 'Ayuda aún no disponible...';?>', 'warning');
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
              var datasend={"codhoras":cod};	
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
                showToast('<?php echo 'Contraseña erronea';?>', 'error');

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

<script language="javascript">
		
		function guia() {
				var url="guia.php";
				$.colorbox({href:url,
				iframe:true, width:"100%", height:"100%",
				});
		}
		
		function devolucion() {
			var devolucion=$('#frame_rejilla').contents().find('#seleccionados').val();
			//alert(devolucion.split(",").length);
			//if (devolucion.split(",").length<2) {
				//showToast('Seleccione evaluacion/es para hacer la devolución .', 'warning');
			//}else {
			$("#devo").val(devolucion);
			$("#devolucion").toggle();
			//}
		}

		function nuevo_devolucion() {
			$("#devolucion").toggle();
			var devolucion=$("#devo").val();
			if (devolucion.split(",").length>=1) {
			var codformulario=$("#Atipodevolucion").val();
      var nempleado=$("nempleado").val();
      var url='formulario_devolucion.php?devolucion='+devolucion+'&codformulario='+codformulario+'&nempleado='+nempleado;
        $.colorbox({href:url,
				iframe:true, width:"100%", height:"100%",
				});
			}else {
				showToast('Seleccione evaluacion/es para hacer la devolución.', 'warning');
				return false;
			}
		}

		function imprimir() {
			var url ='';
			var imprimir=0;
			var opcionesreporte=document.getElementById("opcionesreporte").value;
					var fechaform=document.getElementById("fechaform").value;
					var colaboradorform=document.getElementById("colaboradorform").value;
					var codfeedback=$("#codfeedback").val();
			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesreporte==1) {
					if (fechaform!='' && colaboradorform!='' && codfeedback!='' ) {
					url = "../reportes/feedback/EvaluacionExcel.php?fechaform=" + fechaform+"&colaboradorform="+colaboradorform;
					imprimir=1;
					}else {
						showToast('Selecciones evaluación de la lista.', 'warning');
						return false;
					}
				} 
				if (opcionesreporte==2) {
			var codusuario=document.getElementById("codusuario").value;
					if (codusuario!='') {
					url = "../reportes/feedback/EvaluacionUsuarioExcel.php?codusuario=" + codusuario;
					imprimir=1;
					} else {
						showToast('Selecciones colaborador.', 'warning');
						return false;
					}

				} 	
				if (imprimir==1){		
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
						      window.parent.progressExcelBar(1,v);
									$.get(url+"&file="+v, function(data, status) {
									if(status == 'success'){	
									$('#downloadFrame').remove(); // This shouldn't fail if frame doesn't exist
			   					$('body').append('<iframe id="downloadFrame" style="display:none"></iframe>');
			   					$('#downloadFrame').attr('src','../tmp/'+v+'.xlsx');	
										return false;
							  		}else{					
									showToast('Se produjo un error, intentelo mas tarde', 'warning');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showToast('Nombre de archivo incorrecto.', 'warning');
								}
							}					  														    	
						}
					});	
					} else {
						showToast('Selecciones colaborador.', 'warning');
						return false;
					}
			} else {
				if (opcionesreporte==1) {
					//window.open("../fpdf/?fechaform=" + fechaform+"&colaboradorform="+colaboradorform);
				}
			}
			
		}
	
		function limpiar() {
			document.getElementById("form_busqueda").reset();
			document.getElementById("nempleado").value='';
			document.getElementById("fechaform").value='';	
			document.getElementById("iniciopagina").value=1;
			document.getElementById("codusuario").value='';
			document.getElementById("form_busqueda").submit();
		}			
					
		function grafico() {
			var nempleado=document.getElementById("nempleado").value;
			var fechaform=document.getElementById("fechaform").value;
			var codusuario=document.getElementById("codusuario").value;
			if (codusuario!='') {
				var url="../reportes/feedback/GraficoEvaluacionBase.php?codusuario="+codusuario+"&fechaform="+fechaform+"&nempleado="+nempleado;
				$.colorbox({href:url,
				iframe:true, width:"100%", height:"100%",
				});
			} else {
				showToast('Debe seleccionar colaborador.', 'warning');
				return false;
			}
		}			
</script>
    

<script language="javascript">
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}


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
    

    $("#Anempleado").autocomplete({
        source: '../common/busco_usuarios.php',
        minLength:2,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];

		$("#nempleado").val(pref);
		$("#Anempleado").val(nombre);
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
	
			$("#codusuario").val(pref);
			$("#Ausuarios").val(nombre);

			}
	}).autocomplete("widget").addClass("fixed-height");


});
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
<?php
require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


$obj = new Consultas('formularios');
$obj->Select();
$obj->Where('borrado', '0');    
$obj->Where('tipo', '0');    
$obj->Orden("descripcion" , "ASC");
$paciente = $obj->Ejecutar();
$rows = $paciente["datos"];

$total_rows=$paciente["numfilas"];
?>
  <div style="display: none; z-index: 99; position: absolute; border-radius: 0 0 3px 3px; border-color: grey; box-shadow: 0px 2px 1px 0px rgba(0,0,0,0.4); 
  padding: 0 5px; top: 45px; border: 1px ;   left: 50%;  
  -webkit-transform: translate(-50%, -50%); 
  transform: translate(-50%, -50%); color: black; padding: 14px 28px; font-size: 12px; cursor: pointer; background-color: #333;"  id="seleccionar">
    <input id="Atipoformulario" value="" type="hidden" />
    <select size="1" id="tipoformulario" class="form-control input-sm" onchange="document.getElementById('Atipoformulario').value =this.value;">					
    <option value="">Seleccione un formulario</option>
      <?php 
      if($total_rows>=0){
        foreach($rows as $row){ ?>
              <option value="<?php echo $row['codformulario'];?>"><?php echo $row['descripcion'];?></option>
        <?php } 
      } ?>
    </select>	
    <p />	
  <button  class="btn btn-default" onClick="nuevo_feedback();"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Aceptar</button>
  </div>	

<div style="display: none; z-index: 99; position: absolute; border-radius: 0 0 3px 3px; border-color: grey; box-shadow: 0px 2px 1px 0px rgba(0,0,0,0.4);
 padding: 0 5px; top: 145px; border: 1px ;   left: 50%; -webkit-transform: translate(-50%, -50%); 
 transform: translate(-50%, -50%); color: white; padding: 14px 28px; font-size: 12px; cursor: pointer; background-color: #333;"
    id="devolucion">
<?php
$obj = new Consultas('formularios');
$obj->Select();
$obj->Where('borrado', '0');    
$obj->Where('tipo', '1');    
$obj->Orden("descripcion" , "ASC");
$paciente = $obj->Ejecutar();
$rows = $paciente["datos"];

$total_rows=$paciente["numfilas"];
?>
  <input id="Atipodevolucion" value="" type="hidden" />
  <div class="row">
  <label class="control-label col-xs-2" for="nempleado">Colaborador:</label>  
      <div class="col-xs-7">
      <input type="hidden" name="colaborador" id="nempleado" value="" />
        <input type="text" id="Anempleado"  class="form-control input-sm" />						
      </div>
  </div>
  <div class="row">
    <label class="control-label col-xs-2" for="tipoformulario">Tipo:</label>  
    <select size="1" id="tipoformulario" class="form-control input-sm"  onchange="document.getElementById('Atipodevolucion').value =this.value;">					
    <option value="">Seleccione un formulario</option>
          <?php 
    if($total_rows>=0){
      foreach($rows as $row){ ?>
            <option value="<?php echo $row['codformulario'];?>"><?php echo $row['descripcion'];?></option>
      <?php } 
    } ?>
    </select>	
  <p />	
  </div>
  <div class="row">
    <button class="btn btn-default" onClick="nuevo_devolucion();" ><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Aceptar</button>
    <input type="hidden" id="iniciopagina" name="iniciopagina">
    <input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
    <input type="hidden" id="selid" name="selid">
    <input type="hidden" id="stylesel" name="stylesel">
    <input type="hidden" id="devo" name="devo" />		
    <input type="hidden" id="codfeedback" name="codfeedback" >		
    <input type="hidden" id="fechaform" name="fechaform">
    <input type="hidden" id="colaboradorform" name="colaboradorform">
  </form>
  </div>
</div>	
        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->
