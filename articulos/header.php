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



<script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

 
<script  src="../library/js/jquery-ui.js"></script>

<script  src="validar.js?u=<?php echo time();?>"></script>


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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Listado Articulos');?>");


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
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = true, CloseButton = false);
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
        OpenWindow(url, form = '#frame_rejilla',w = '98%' ,h = '98%', Close = false, Scroll = true, CloseButton = false);
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
                          if(tabla=='articulos'){
                            $('#frame_rejilla').attr( 'src', function ( i, val ) { return val; });
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

function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp  = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

		function precioiva() {
      
			var tipoiva=$('#impuesto').find('option:selected').text();
			var valorimpuesto = tipoiva.split("~")[1];

			var precio_publico=$('#precio_tienda').val();
			var valor=parseFloat(precio_publico) + (parseFloat(precio_publico) * parseFloat(valorimpuesto) / 100);
			$("#precio_iva").val(round(valor,2));			
    }
    
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
			  e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
        if (index==20) {
          
          $('[data-index="' + (index + 1).toString() + '"]').focus();
          $('.nav-tabs a[href="#2b"]').tab('show');
          $('#Wplancuentac').focus();
          
        }
        $('[data-index="' + (index + 1).toString() + '"]').focus();
        	if (index==32) {
        		validar(formulario,true);
        	}
        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

$(document).ready(function(){
  
    $('.keyupsubmit').bind('keyup', function() { 
      $('#form').delay(200).submit();
    });
    $(".onchangesubmit").change(function() {
     this.form.submit();
    });
});
</script>	
<script languge="javascript">
		
function grafico() {
  var codarticulo=$("#Acodarticulo").val();
  var codusuario=$("#codusuarios").val();
  var fechafin=$("#fechafin").val();
  var fechaini=$("#fechaini").val();
  
  var url ='';
  var opcionesgrafico=$("#opcionesgrafico").val();
  if (opcionesgrafico==1) {
      if (codarticulo=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>','warning');
      } else {
        parent.showModal(1);
      var url="../reportes/horas/GraficoBase.php?codarticulo="+codarticulo+"&fechaini="+fechaini+"&fechafin="+fechafin;
      
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
      if (codarticulo=='' && codusuario=='') {
        showToast('<?php echo _('Debe seleccionar cliente y usuario.');?>','warning');
      }else if (codarticulo=='') {
        showToast('<?php echo _('Debe seleccionar cliente.');?>','warning');
      } else if (codusuario=='') {
        showToast('<?php echo _('Debe seleccionar usuario.');?>', 'warning');
      } else {
        parent.showModal(1);
      var url="../reportes/horas/GraficoBaseUsuarios.php?codusuario="+codusuario+"&codarticulo="+codarticulo+"&fechaini="+fechaini+"&fechafin="+fechafin;
        $.colorbox({href:url,
        iframe:true, width:"98%", height:"98%",
        });
      }
    }	    		
    if (opcionesgrafico==4) { 
      parent.showModal(1);
      var url="../reportes/horas/horasasignadas.php?codarticulo="+codarticulo+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
      $.colorbox({href:url,
      iframe:true, width:"98%", height:"98%",
      });	

	    parent.showModal(0);
				
    }							

    if(opcionesgrafico>=13 ){
    var url="../reportes/horas/GraficoProyectosMes.php?codarticulo="+codarticulo+"&codusuario="+codusuario+"&fechaini="+fechaini+"&fechafin="+fechafin;
    $.colorbox({href:url,
    iframe:true, width:"98%", height:"98%",
    });			
    } 
  

}

function imprimir() {
			var url ='';
			var codigobarras=document.getElementById("codigobarras").value;
			var referencia=document.getElementById("referencia").value;
			var descripcion=document.getElementById("descripcion").value;
			var proveedores=document.getElementById("codproveedor").value;			
			var familia=document.getElementById("familias").value;
			//var ubicacion=document.getElementById("cboUbicacion").value;
			var stock=document.getElementById("stock").value;
			var opcionesimpresion=document.getElementById("opcionesimpresion").value;
			
			if ($('#opcionesaexcel').is(":checked")){
				
				if (opcionesimpresion==1) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock;
				} 
				if (opcionesimpresion==2) {
					url = "../excel/ListaDePreciosGralExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock;
				}
				if (opcionesimpresion==3) {
					url = "../excel/ListaDePreciosExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&cat=1";
				}
				if (opcionesimpresion==4) {
					url = "../excel/ListaDePreciosExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&cat=1"+"&logo=1";
				}
				if (opcionesimpresion==5) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&comision=1";
				} 
				if (opcionesimpresion==6) {
					url = "../excel/ListaDeArticulosExcel.php?codigobarras="+codigobarras+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock+"&baja=1";
				} 
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
									showWarningToast('Se produjo un error, intentelo mas tarde');
							  		}
								});    											
							} else {
								if (result!="Cancelar") {
									showWarningToast('Nombre de archivo incorrecto.');
								}
							}					  														    	
						}
					});	
			} else {
				if (opcionesimpresion==1) {
					window.open("../fpdf/articulos.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}
				if (opcionesimpresion==7) {
					window.open("../fpdf/imprimir_bajo_minimos.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
				}
				if (opcionesimpresion==8) {
					window.open("../fpdf/imprimir_articulos_proveedor.php?codigobarras="+codigobarras+"&referencia="+referencia+"&descripcion="+descripcion+"&cboProveedores="+proveedores+"&cboFamilias="+familia+"&cboUbicacion="+ubicacion+"&stock="+stock);
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
         
    $("#Wplancuentac").autocomplete({
        source: '../common/busco_plandecuentas.php',
        minLength:1,
        autoFocus:true,
        select: function(event, ui) {

		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var nombre=thisValue.split("~")[1];

		$("#plancuentac").val(pref);
		$("#Wplancuentac").val(pref);
		}
	}).autocomplete("widget").addClass("fixed-height");

    $("#Wplancuentav").autocomplete({
        source: '../common/busco_plandecuentas.php',
        minLength:1,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
	
			$("#plancuentav").val(pref);
			$("#Wplancuentav").val(pref);

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

    var codarticulo = $("#Acodarticulo").val();
    var fecha = $("#fecha").val();
    if (codarticulo.length!=0) {
      
          $.ajax({ 
          type: "POST",
          url: "checkhoras.php",
          cache: false,
          data: { "codarticulo": codarticulo, "fecha" : fecha, "horas" : horas+":"+minutos},
          success: function(text){
              
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