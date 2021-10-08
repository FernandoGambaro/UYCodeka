<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors

require_once __DIR__ .'/../classes/class_session.php';
require_once __DIR__ .'/../common/languages.php';
require_once __DIR__ .'/../common/verificopermisos.php'; 

require_once __DIR__ .'/../library/conector/consultas.php';
use App\Consultas;


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

  $objMon = new Consultas('monedas');
  $objMon->Select();
  $objMon->Where('orden', '3', '<');
  $objMon->Where('borrado', '0');
  $objMon->Orden('orden', 'ASC');
  $selMon=$objMon->Ejecutar();
  $filasMon=$selMon['datos'];

  $xmon=1;
  foreach($filasMon as $fila){
    $moneda='moneda'.$xmon;
         $$moneda = $fila['simbolo'];
      $xmon++;
  }

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
$("#msganio",top.document).html("<i class='fa fa-long-arrow-right' aria-hidden='true'></i>&nbsp; <?php echo _('Listado factura compras');?>");


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
function eliminar(cod, codproveedor, codtabla){
  $("#Sendcod").val(cod);
  console.log(codtabla);
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
              var datasend={"cod":cod, "codproveedor": codproveedor, "tabla":codtabla};
              console.log(datasend);
              $.ajax({ 
              type: "POST",
              url: "delete.php",
              cache: false,
              data: datasend,
              success: function(text){
                console.log(text);
                      if (text != " "){
                        var n = text.includes("Fallo");
                        if(n>0){
                          tipo="error";
                        }else{
                          tipo="success";
                          if(codtabla=='facturasp'){
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
			var valor=Number(precio_publico) + (Number(precio_publico) * Number(valorimpuesto) / 100);
			$("#precio_iva").val(round(valor,2));			
    }
    
$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 13:
          case 13:
        e.preventDefault();
        var $this = $(e.target);
        var index = Number($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=$("#Acodproveedor").val();
	        		if (codigo=='') {
                showToast('<?php echo _('Debe introducir antes el proveedor');?>', 'error');
	        			$('[data-index="1"]').focus();
	        			return false;
		        	}
		     }
	        if (index==2) {
	        	var cfactura=$("#Acodfactura").val();
	        		if (cfactura=='') {
                showToast('<?php echo _('Debe introducir número factura');?>', 'error');
	        			$('[data-index="2"]').focus();
	        			return false;
	        		}
		     }		  
		     if (index==4) {
          var tipo=$('#Atipo').find('option:selected').val();
					if (tipo==0) {
            showToast('<?php echo _('Seleccione tipo documento');?>', 'error');
  					$("#tipo").focus();	
	  				return false;
					}
		     }		        
		     if (index==5) {
          var Amoneda=$('#Amoneda').find('option:selected').val();
					if (Amoneda==0) {
            showToast('<?php echo _('Seleccione moneda');?>', 'error');
  					$("#Amoneda").focus();	
					  return false;
					}
		     }
			  if (index==9) {
          var Amoneda=$('#Aiva').find('option:selected').val();
					  if (Amoneda==0) { 
              showToast('<?php echo _('Seleccione IVA');?>', 'error');
  					  $("#Aiva").focus();
					  return false;
					}
		     }
			  if (index==14) {
		        		validar();
		     }	
		     	$('[data-index="' + (index + 1).toString() + '"]').focus();


        break;
        case 112:
          showToast('Ayuda aún no disponible...', 'info');
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

    $('#Nuevo').click(function(e){
      var url = 'create.php';
      OpenWindow(url, form = '#frame_rejilla',w = '98%' ,h = '98%', Close = true, Scroll = true, CloseButton = true);
    });
});

</script>	
<script languge="javascript">

  function busco_tipocambio() {
    var fecha=$("#Afecha").val();
      $.post("../common/busco_tipocambio.php?fecha="+fecha,  function(data){
      $("#tipocambio").val(data);
    });	 		
	}	

  function cambio_iva() {

  var original = parseFloat($("#baseimponible").val());

  var tipoiva=$('#Aiva').find('option:selected').text();
  var valorimpuesto = tipoiva.split("~")[1];
  var codimpuesto = tipoiva.split("~")[0];

  $("#impuesto").val(codimpuesto);

  //$("#baseimpuestos").val(parseFloat(original * valorimpuesto / 100));
  var original1=parseFloat(original * valorimpuesto / 100);
  var result1=round(original1 , 2) ;
  $("#baseimpuestos").val(result1);

  var original2=parseFloat(original + result1);
  var result2=round(original2 , 2) ;

  $("#preciototal").val(result2);
  $("#totalfactura").val(result2);
  }	
      
  function validar() 	{
				var mensaje="";
				var entero=0;
				var enteroo=0;
        var codfactura=$('#Acodfactura').val();
        var Index=$('#Amoneda').find('option:selected').val();

        var Acodproveedor=$('#codproveedor').val();

				event.preventDefault();

        if(Acodproveedor==""){
          showToast('Falta seleccionar proveedor', 'error');
          $('#Acodproveedor').focus();
          return false;
        }
        if(codfactura==""){
          showToast('Falta númeo de factura', 'error');
          $('#Acodfactura').focus();
          return false;
        }
        if(Index==-1){
          showToast('Falta seleccionar moneda', 'error');
          $('#Amoneda').focus();
          return false;
        }

				if ($("#codbarras").val()=="") mensaje="  - Falta Artículo<br>";
				if ($("#descripcion").val()=="") mensaje+="  - Descripcion<br>";
				if ($("#precio").val()=="") { 
							mensaje+="  - Falta el precio<br>"; 
				} else {
					if (isNaN($("#precio").val())==true) {
						mensaje+="  - El precio debe ser numerico<br>";
					}
				}
				if ($("#cantidad").val()=="") 
						{ 
						mensaje+="  - Falta la cantidad<br>";
						} else {
							enteroo=+($("#cantidad").val());
							if (isNaN(enteroo)===true) {
								mensaje+="  - La cantidad debe ser numerica<br>";
							} else {
									$("#cantidad").val(enteroo);
								}
						}
				if ($("#descuento").val()=="") 
						{ 
						$("#descuento").val(0);
						} else {
							entero=+($("#descuento").val());
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento debe ser numerico<br>";
							} else {
								$("#descuento").val(entero);
							}
						}
				if ($("#importe").val()=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showToast("Errores detectados:<br>"+mensaje, 'error');
				} else {
					$("#baseimponible").val(+($("#baseimponible").val()) + +($("#importe").val()));	
					cambio_iva();
          
          //$("#formulario_lineas").submit();



          $("#form_lineas").submit();

					limpiar();										
				}
				$('#articulos').focus();
			}
    
      function pon_baseimponible(baseimponible) {
        $("#baseimponible").val(parseFloat(baseimponible));
        
			cambio_iva();
    }
 
		function validar_cabecera()
			{
				//event.preventDefault();
				var mensaje="";
				if ($("#codproveedor").val()=="") mensaje+="  - Nombre<br>";
				if ($("#Afecha").val()=="") mensaje+="  - Fecha<br>";
				if ($("#Acodfactura").val()=="") mensaje+="  - Cod. Factura<br>";
				if ($("#Atipo").val()=="") mensaje+="  - Seleccione tipo<br>";			
				if (mensaje!="") {
					showToast("Errores detectados:<br>"+mensaje, 'error');
				} else {
					$("#formulario").submit();
				}
			}

		function actualizar_importe() {
        /*Si la factura es en peso y el articulo esta en dolares aplico el tipo de cambio*/
        var tipocambiofactura=$('#Amoneda').find('option:selected').val();
				
        var tipocambioarcticulo=$("#moneda").val();
        var tipocambio =$("#tipocambio").val(); 

        var precio=$("#precio").val();

				if (tipocambiofactura==1 && tipocambioarcticulo == 2){
					precio=Number(precio) * Number(tipocambio);
				}
				if (tipocambiofactura==2 && tipocambioarcticulo == 1){
					precio=Number(precio) / Number(tipocambio);
				}
				if ((tipocambiofactura==1 && tipocambioarcticulo == 1) || (tipocambiofactura==2 && tipocambioarcticulo == 2)){
				  precio=Number(precio);
				}
			
				var cantidad=+$("#cantidad").val();
				var descuento=+$("#descuento").val();
        var total=precio*cantidad;
        if(descuento!='NaN' && descuento !=''){
          descuento=descuento/100;
  				descuento=total*descuento;
          total=total-descuento;
        }
				var original=Number(total);
        var result=Math.round(original*100)/100 ;

				$("#importe").val(result);
			} 

  function limpiar() {

      $("#codbarras").val('');
      $("#articulos").val('');
      $("#detalles").val('');
      $("#descripcion").val('');
      $("#precio").val( '');
      $("#cantidad").val('1');
      $("#moneda").val('');
      $("#monedaShow").val('');
      $("#importe").val('');
      $("#descuento").val('0');	
      $("#comision").val('0');
      $("#descuentopp").val('0');
      event.preventDefault();
  }	

  var tipoaux='';

  function cambio() {
    var Index=$('#Amoneda').find('option:selected').val();

			var monArray = new Array();
			monArray[1]="<?php echo $moneda1;?>";
			monArray[2]="<?php echo $moneda2;?>";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

			var moneda=$("#moneda").val();
			var cantidad=$("#cantidad").val();
			var descuento=$("#descuento").val();

				if (moneda==1 && Index == 2){
					precio= $("#precio").val() / Number($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=Number(total);
					var result=round(original, 2);
					$("#importe").val(result);
				}
				if (moneda==2 && Index == 1){
					precio= $("#precio").val() * Number($("#tipocambio").val());
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=Number(total);
					var result=round(original, 2);
					$("#importe").val(result);
				}
				if (moneda== Index){
					precio= $("#precio").val();
					descuento=descuento/100;
					total=precio*cantidad;
					descuento=total*descuento;
					total=total-descuento;
					var original=Number(total);
					var result=Math.round(original*100)/100  ;
					$("#importe").val(result);
				}

				if (tipoaux==1 && Index == 2){
          console.log('Otro 1'+ $("#baseimponible").val());
					$("#baseimponible").val(round(( $("#baseimponible").val() / Number($("#tipocambio").val())) * 100, 2) / 100);
					$("#baseimpuestos").val(round(( $("#baseimpuestos").val() / Number($("#tipocambio").val())) * 100, 2) / 100);
					$("#preciototal").val(round(($("#preciototal").val() / Number($("#tipocambio").val())) * 100, 2) / 100);
				}
				if (tipoaux==2 && Index == 1){
          console.log('Otro 2'+ $("#baseimponible").val());
					$("#baseimponible").val(round(($("#baseimponible").val() * Number($("#tipocambio").val())) * 10, 2) / 10);
					$("#baseimpuestos").val(round(($("#baseimpuestos").val() * Number($("#tipocambio").val())) * 10, 2) / 10);
					$("#preciototal").val(round(($("#preciototal").val() * Number($("#tipocambio").val())) * 10, 2) / 10);
        }
    $("#preciototal").val($("#preciototal").val());
        
			tipoaux=Index;
		}

function compruebonumerofactura(){
  var codproveedor = $('#codproveedor').val();
  var codfactura = $('#Acodfactura').val();
  var tabla="facturasp";
  if(codfactura!=''){
    var datasend={"cod":codproveedor, "codfactura":codfactura, "tabla":tabla};
    $.ajax({ 
      type: "POST",
      url: "../common/comprobarnumerofactura.php",
      cache: false,
      data: datasend,
      success: function(text){
          if (text == '1'){
            showToast("La factura ya fue ingresada", 'error');
            $('#Acodfactura').focus();
          }
        }
    });
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
         
    var monArray = new Array();
    monArray[1]="<?php echo $moneda1;?>";
		monArray[2]="<?php echo $moneda2;?>";


    $("#articulos").autocomplete({
        source: '../common/busco_articulo.php',
        minLength:1,
        autoFocus:true,
        select: function(event, ui) {
		var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var referencia=thisValue.split("~")[1];
		var nombre=thisValue.split("~")[2];
    var detalles=thisValue.split("~")[3];
    var precio=thisValue.split("~")[4];
    var moneda=thisValue.split("~")[5];
    var codfamilia=thisValue.split("~")[6];
    
		$("#codarticulo").val(pref);
    $("#articulos").val(referencia);
    $("#descripcion").val(nombre);
    $("#detalles").val(detalles);
    $("#precio").val(precio);
    $("#moneda").val(moneda);
    $("#codfamilia").val(codfamilia);
    
		$("#monedaShow").val(monArray[moneda]);
    actualizar_importe()

		}
  }).autocomplete("widget").addClass("fixed-height");
  
  $("#descripcion").autocomplete({
    source: '../common/busco_articulo.php',
    minLength:1,
    autoFocus:true,
    select: function(event, ui) {
//console.log(ui);

    var name = ui.item.value;
		var thisValue = ui.item.data;
		var pref=thisValue.split("~")[0];
		var referencia=thisValue.split("~")[1];
		var nombre=thisValue.split("~")[2];
    var detalles=thisValue.split("~")[3];
    var precio=thisValue.split("~")[4];
    var moneda=thisValue.split("~")[5];
    var codfamilia=thisValue.split("~")[6];
    
		$("#codarticulo").val(pref);
    $("#articulos").val(referencia);
    $("#descripcion").val(nombre);
    $("#detalles").val(detalles);
    $("#precio").val(precio);
    $("#moneda").val(moneda);
    $("#codfamilia").val(codfamilia);

    $("#monedaShow").val(monArray[moneda]);
    actualizar_importe()

		}
	}).autocomplete("widget").addClass("fixed-height");


    $("#Acodproveedor").autocomplete({
        source: '../common/busco_proveedor.php',
        minLength:1,
        autoFocus:true,
        select: function(event, ui) {
	
			var name = ui.item.value;
			var thisValue = ui.item.data;
			var pref=thisValue.split("~")[0];
			var nombre=thisValue.split("~")[1];
			var nif=thisValue.split("~")[2];
       
			$("#codproveedor").val(pref);
			$("#Acodproveedor").val(nombre);
			$("#Bcodproveedor").val(pref);
      $("#nrut").val(nif);

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

        <!-- container -->
        <div class="container">


         <!-- For the following code look at footer.php -->