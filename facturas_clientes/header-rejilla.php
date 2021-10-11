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

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />

    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="../library/js/OpenWindow-rejilla.js" type="text/javascript"></script>

    <script type="text/javascript" src="../library/js/jquery.keyz.js"></script>

<!-- Cargo custom CSS-->
<link rel='stylesheet' type='text/css' href='../library/estilos/style-theme.php?col=<?php echo $paleta;?>' />
<link href="../library/estilos/customCSS.css" rel="stylesheet">
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../library/estilos/font-awesome.min.css">

<style>
.edit{
    width: 50px;
    height: 25px;
}

.txtedit{
    display: none;
    width: 50px;
    height: 25px;
}

</style>

<script type="text/javascript">

function showToast(text, tipo){
  parent.showToast(text, tipo);
}

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

var codarticulo='';
var valorAnt=0;
$(document).ready(function(){
  
    
    $('.trigger').click(function(e){

        e.preventDefault();
        codarticulo= $(e.currentTarget).attr("data-codarticulo"); 
        parent.$('#codarticulo').val(codarticulo);
    });//Finaliza trigger


    $("#Modifico").click(function(e){ 
        //var codarticulo= $(e.currentTarget).attr("data-codarticulo"); 
        if(codarticulo > 0){
            var url = 'edit.php?codarticulo='+codarticulo;
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = true, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar item');?>','warning'); 
            }

    });//Finaliza editar item
    $("#Nuevo").click(function(e){ 
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = true, CloseButton = false);
    });//Finaliza nuevo

    // Show Input element
    $('.edit').click(function(){
        $('.txtedit').hide();
        valorAnt = $(this).next('.txtedit').val();
        console.log("valorAnt: "+valorAnt);
        $(this).next('.txtedit').show().focus();
        $(this).hide();
    });

    // Save data
    $(".txtedit").on('focusout',function(){
        
        // Get edit id, field name and value
        var id = this.id;
        var split_id = id.split("_");
        var field_name = split_id[0];
        var nro_factura = split_id[1];
        var linea = split_id[2];
        var value = $(this).val();
        
        console.log("nombre del campo: "+field_name+" nro factura: "+ nro_factura + " Nº línea: "+ linea + " Valor: "+value);
        // Hide Input element
        $(this).hide();

        // Hide and Change Text of the container with input elmeent
        $(this).prev('.edit').show();
        $(this).prev('.edit').text(value);

        // Sending AJAX request
        $.ajax({
            url: 'update.php',
            type: 'post',
            data: { field:field_name, value:value, codfactura:nro_factura, numlinea:linea },
            success:function(response){
                if(response == 1){ 
                    if(field_name=='cantidad'){
                        var idprecio="#precio_"+nro_factura+"_"+linea;
                        var precio = $(idprecio).val();
                        var total = value * precio;
                        var idtotal = "#total_"+linea;
                        var idtotalAux = "#totalAux_"+linea;
                        $(idtotal).val(total);

                        $(idtotalAux).text(total); 
                        

                    showToast('<?php echo _('Actualizando factura');?>','success'); 
                    var baseimponible = parent.$("#baseimponible").val();
                    
                    parent.$("#baseimponible").val(baseimponible-precio+total);

                    var tipoiva=parent.$('#Aiva').find('option:selected').text();
			        var valorimpuesto = tipoiva.split("~")[1];

                    console.log("valorimpuesto "+valorimpuesto+ " valor :"+valorimpuesto);
 
                    var original1=parseFloat((baseimponible-precio+total) * valorimpuesto / 100);
                    var result1=round(original1 , 2) ;
                    parent.$("#baseimpuestos").val(result1);
                    
                    var original2=parseFloat((baseimponible-precio+total) + result1);
                    var result2=round(original2 , 2) ;

                    parent.$("#preciototal").val(result2);
                    parent.$("#totalfactura").val(result2);

                    }else if (field_name=='precio'){
                        console.log("id "+idtotal+ " valor :"+total);
                    }
                    
                    
                }else{ 
                    console.log("Not saved."); 
                    
                }  
            }
        });
    
    });

});

var del='';
var accion='';

$(document).unbind('keypress');
$(document).keydown(function(e) {
//alert(e.keyCode);
    switch(e.keyCode) { 
        case 117:
            if(codarticulo > 0){
            var url = 'edit.php?codarticulo='+codarticulo;
                OpenWindow(url, form = '#frame_rejilla',w = '98%',h = '98%', Close = false, Scroll = false, CloseButton = false);
            }else{
                showToast('<?php echo _('Debe seleccionar paciente');?>','warning'); 
            }
        break;
        case 112:
            showToast('<?php echo _('Ayuda aún no disponible...');?>','warning');
        break;
        case 45:
        var url = 'create.php';
        OpenWindow(url, form = '#frame_rejilla',w = '98%' ,h = '98%', Close = false, Scroll = true, CloseButton = false);
        break;
       
    }
});

function eliminar(cod, codcliente, tabla, emitida){
    parent.eliminar(cod, codcliente, tabla, emitida);
}

function eliminar_linea(cod, linea){
    var datasend={"cod":cod, "linea":linea};
    var showone=0;
    $.ajax({ 
    type: "POST",
    url: "delete_linea.php",
    cache: false,
    data: datasend,
    success: function(text){
            if (text != " "){
            var n = text.includes("Fallo");
            if(n>0){
                tipo="error";
            }else{
                tipo="success";
                parent.$('#frame_lineas').attr( 'src', function ( i, val ) { return val; });
            }

            }
        }
    });
}

function enviar_factura(codfactura, envio) {
    parent.enviar_factura(codfactura, envio);
}

function imprimir(codfactura, emitir, enviar) {
    parent.imprimir(codfactura, emitir, enviar);
}

</script>

    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->