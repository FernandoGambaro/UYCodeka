<?php
include ("../conectar.php");
include ("../common/funcionesvarias.php");
include ("../funciones/fechas.php"); 

$tipo='';
$moneda='';
$codcliente='';
$up_id='';

$fechahoy=date("Y-m-d");
$sel_presupuesto="INSERT INTO presupuestostmp (codpresupuesto,fecha) VALUE ('','$fechahoy')";
$rs_presupuesto=mysqli_query($GLOBALS["___mysqli_ston"], $sel_presupuesto);
$codpresupuestotmp=((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

$sel_imp="select * from `impuestos` where `fecha` <= '$fechahoy' and `borrado` = 0 ORDER BY `fecha` DESC limit 1";
$rs_imp=mysqli_query($GLOBALS["___mysqli_ston"], $sel_imp);
if ($rowimp=mysqli_fetch_row($rs_imp)){
$iva=$rowimp[3];
}
?>
<html>
	<head>
		<title>Principal</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />	
    	
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="js/colorbox.css" />
		<script src="js/jquery.colorbox.js"></script>

		<script type="text/javascript" src="../funciones/validar.js"></script>
		<script type="text/javascript" src="js/MaskedPassword.js"></script>


<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="js/jquery.toastmessage.css" type="text/css">
<script src="js/jquery.toastmessage.js" type="text/javascript"></script>
<script src="js/message.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/colorbox.css" />
<script src="js/jquery.colorbox.js"></script>


<script type="text/javascript">

$(document).keydown(function(e) {
    switch(e.keyCode) { 
        case 46:
/*/alert(newItemDel);*/
         if(newItemDel!='') {
	      var tmp=$(newItemDel).find('option:selected').val();
         $(newItemDel).find('option:selected').remove();
         newItemDel="";

			dropdownElement = $("#ItemMCombo");
			dropdownElement.find('option[value='+tmp+']').remove();
        
         }
        break;    
        case 13:
			e.preventDefault();
        var $this = $(e.target);
        var index = parseFloat($this.attr('data-index'));
	        if (index==1) {
	        	var codigo=document.getElementById("aCliente").value;
	        		if (codigo=='') {
	        			abreVentana();
		        	} else {
		        		validarcliente();
		        	}
		     }
			  if (index==14) {
		     		var moneda=document.getElementById("Amoneda").value;
					if (moneda=='0') {
						showWarningToast("Debe seleccionar moneda");
						$("#Amoneda").focus();
						return false;
					} 			  	
		     		var codigo=document.getElementById("codbarras").value;
		      	if (codigo=='') {
		        		ventanaArticulos();
		     		} else {
				     			validarArticulo();
				  }
		     }
			  if (index==20) {
		        		validar();
		        		$('#codbarras').focus();
		     }		     
		     		$('[data-index="' + (index + 1).toString() + '"]').focus();
		     		

        break;
        case 112:
            showWarningToast('Ayuda aún no disponible...');
        break;
       
	 }
});

</script>		

<!--///////////////////////////////////////////////////////-->

<script type="text/javascript">
var newItemHide='';
var newItemShow='';
var newItemHideN='';
var newItemShowN='';
var newItemInput='';
var newItemInputId='';
var newItemInputValue='';
var newItemCombo='';
var newItemComboN='';
var trigerclass='';

var initials ={};

$(document).ready( function(){
	
$("form:not(.filter) :input:visible:enabled:first").focus();

	$('input').each(function(){
    initials[this.id] = $(this).attr('value');
	});
		
$( ".frmClose" ).click(function() {
	$( ".frmhead" ).toggle( "slow", function() {
	// Animation complete.
	});
	$(".frmOpen").toggle();
	$("#frmItemMHide").toggle();
});
$( ".frmOpen" ).click(function() {
	$( ".frmhead" ).toggle( "slow", function() {
	// Animation complete.
	});
	$(".frmOpen").toggle();
	$("#frmItemMHide").toggle();
});		


  $('.trigger').click( function(e){
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
      }
		trigerclass='';
      newItemHide=this.id+'Hide';
      newItemShow=this.id+'Show';
      newItemHideN='#'+this.id+'Hide';
      newItemShowN='#'+this.id+'Show';
      newItemInput=this.id+'Input';
      newItemInputId='#'+this.id+'Input';
      newItemInputValue=this.id+'Value';
      newItemCombo=this.id+'Combo';
      newItemComboN='#'+this.id+'Combo';

      p = $(this);
      var offset = p.offset();
      var x = offset.left - this.offsetLeft - 290 ;
      if (newItemShow=='newItemFShow') {
      var x = offset.left - this.offsetLeft - 290 ;
      var y = offset.top - this.offsetTop - 150;
      } else {
      var y = offset.top - this.offsetTop - 10;
      }
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y - 10;

      $(newItemShowN).show(); $(newItemHideN).hide();
      	   
      e.preventDefault();
   } );//Finaliza trigger

  $('.triggerClose').click( function(e){

      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      }
      trigerclass='';
      e.preventDefault();
   } );/*/Finaliza triggerClose*/
   
  $('.ComboBox').click( function(e){
      newItemDel='#'+this.id;
      $(newItemShowN).hide(); $(newItemHideN).show();
      e.preventDefault();
   } );

	$('#formulario').submit(function() {
		$('*').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});

$('.simpleinput').click( function(e){
	$(newItemShowN).hide(); $(newItemHideN).show();
	$('#suggestions').hide();
	$('#suggestionslong').hide();
});

/*/veo cual es el campo en el que se esta escribiendo;*/
$('input').focus(function(e){
	$('#suggestions').hide();
	$('#suggestionslong').hide();
   var selected = document.activeElement;
   if (selected.id) {
      var offset = $(this).offset();
      var x = offset.left - this.offsetLeft ;
      var y = offset.top - this.offsetTop + 15;
      document.getElementById("suggestions").style.left = x + 10;
      document.getElementById("suggestions").style.top = y + 21;
     // e.preventDefault();
   }
newItemInput='#'+selected.id;

    if (!document.activeElement) {
        this.each(function() {
            var $this = $(this).data('hasFocus', false);
            $this.focus(function(event) {
                $this.data('hasFocus', true);
            });
            $this.blur(function(event) {
                $this.data('hasFocus', false);
            });
        });
    }
    
    });

//
//show the progress bar only if a file field was clicked
	var show_bar = 0;
    $('input[type="file"]').click(function(){
		show_bar = 1;
    });
//show iframe on form submit
    $("#upload-form").submit(function(){
		if (show_bar === 1) { 
			$('#progress-frame').show();
			function set () {
				$('#progress-frame').attr('src','progress-frame.php?up_id=<?php echo $up_id; ?>');
			}
			setTimeout(set);
		}
    });
//

});
</script>
<script type="text/javascript">
	function lookup(id,inputString) {
		newItemInputValue=id+'Value';
		newItemInput='#'+id;
 		var offset = $(newItemInput).offset();
      var x = offset.left;
      var y = offset.top + 7;
      document.getElementById("suggestionslong").style.left = x + 10;
      document.getElementById("suggestionslong").style.top = y + 5;
      		
		if(inputString.length == 0) {
			/*/ Hide the suggestion box.*/
			$('#suggestionslong').hide();
		} else {
			$.post("buscar.php?otro="+id, {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionslong').show();
					$('#autoSuggestionsListlong').html(data);
				}
			});
		}
	} /*/ lookup
	*/

	function fill(thisValue) {
      $( "#suggestions" ).hide( );
      $( "#suggestionslong" ).hide( );
      $(newItemShowN).hide(); $(newItemHideN).show();
      var item0=thisValue.split("-")[0];
      var item1=thisValue.split("-")[1];
      var item2=thisValue.split("-")[2];      
		/*alert(newItemComboN + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1+ " -- "+item2);*/
		
		$(newItemInput).val('');
      $(newItemComboN).append($('<option style="background-color:'+item2+'; color: #'+ invertHex(item2)+';"></option>').attr('value', item0).text(item1));
      addItem(item0,item1,item2);
      newItemHide=''; newItemShow=''; newItemHideN=''; newItemShowN=''; newItemInput=''; newItemCombo='';
	}/*/End fill*/	

	
</script>

<style type="text/css">
	.suggestionsBox {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 150px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
		.suggestionsBoxLong {
      position:absolute;
		overflow:hidden; 
		visibility:visible; z-index:20;
		left:auto;
		margin: 6px 0px 0px 0px;
		width: 350px;
		background-color: #212427;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border: 2px solid #000;	
		color: #fff;
                font-size:10pt;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}

	.suggestionListLong {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionListLong li {
		
		margin: 0px 0px 2px 0px;
		padding: 2px;
		cursor: pointer;
	}
	
	.suggestionListLong li:hover {
		background-color: #659CD8;
	}
        .seleccione { background:#FFF;margin:0 7px 0 7px;border-left:1px solid #EFEFEF;border-right:1px solid #EFEFEF;border-bottom:1px solid #EFEFEF;padding:5px 5px 5px 5px;
 border-right:1px solid #C43303; border-bottom:1px solid #C43303; border-left:1px solid #C43303; border-top:1px solid #C43303; border-radius:5px;
}

input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}

</style>

<script type="text/javascript">
function OpenNote(noteId,w,h){
	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:w, height:h,
			scrolling: false,
	});
}

function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}

function pon_prefijo(pref,nombre,nif) {
	$("#aCliente").val(pref);
	$("#nombre").val(nombre);
	$("#nif").val(nif);
	$('idOfDomElement').colorbox.close();
}

function pon_prefijo_Fb (codfamilia,pref,referencia,nombre,precio_compra,precio,codarticulo,moneda) {
	var monArray = new Array();
	monArray[0]="Selecione uno";
	monArray[1]="Pesos";
	monArray[2]="U\$S";
	$("#codfamilia").val(codfamilia);
	$("#codbarras").val(pref);
	$("#referencia").val(referencia);
	$("#descripcion").val(nombre);
	$("#precio_compra").val(precio_compra);
	$("#precio").val(precio);
	$("#moneda").val(moneda);
	$("#monedaShow").val(monArray[moneda]);
	$("#importe").val(precio);
	$("#codarticulo").val(codarticulo);
	$('idOfDomElement').colorbox.close();
	actualizar_importe();
}

function invertHex(hexnum){
  if(hexnum.length != 6) {
    console.error("Hex color must be six hex numbers in length.");
    return false;
  }

  hexnum = hexnum.toUpperCase();
  var splitnum = hexnum.split("");
  var resultnum = "";
  var simplenum = "FEDCBA9876".split("");
  var complexnum = new Array();
  complexnum.A = "5";
  complexnum.B = "4";
  complexnum.C = "3";
  complexnum.D = "2";
  complexnum.E = "1";
  complexnum.F = "0";

  for(i=0; i<6; i++){
    if(!isNaN(splitnum[i])) {
      resultnum += simplenum[splitnum[i]]; 
    } else if(complexnum[splitnum[i]]){
      resultnum += complexnum[splitnum[i]]; 
    } else {
      console.error("Hex colors must only include hex numbers 0-9, and A-F");
      return false;
    }
  }

  return resultnum;
}
</script>			
<script LANGUAGE="JavaScript">
function selectAll(selectBox,selectAll) { 
    /*/ have we been passed an ID */
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    /*/ is the select box a multiple select box? */
    if (selectBox.type == "select-multiple") { 
        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
}

</script>	
<script type="text/javascript">
function addItem(item0,item1,item2)  
{  
        var lst = document.getElementById('ItemMCombo');
      var newItem = item0;
      //prompt("Enter New Item","Enter Value Here").replace(" ","");
       //var newItem=document.getElementById('txt').value.replace(" ","");         
      var isnew=true;
        if(newItem=="")
        {  
         showWarningToast("Seleccione alguna opción.");  //
                return false;  
        }
          
        else 
        { 
              
 for(var i=0;i<lst.options.length;i++)
{
if(newItem.toLowerCase()==lst.options[i].text.toLowerCase())
 { isnew=false;
 showWarningToast("Ya existe sector ");
  break; }
}
if(isnew){ //lst.options[lst.length] = new Option(newItem,newItem,false,false);
        var newOption = document.createElement("option");
                newOption.value = newItem; // The value that this option will have
                newOption.innerHTML = item1;
                lst.appendChild(newOption);            
                //sort items in listbox in alpha order
                arrTexts = new Array();   
                for(var i=0; i<lst.length; i++)
                {
                    arrTexts[i] = lst.options[i].text;
                    arrValue[i] = lst.options[i].value;
                }                           
                arrTexts.sort();
                for(i=0; i<lst.length; i++)
                {
                    lst.options[i].text = arrTexts[i];
                    lst.options[i].value = arrValue[i];
                }
        } 
               return false;  
   }
}

</script>	

<script language="javascript">
		function cancelar() {
			parent.$('idOfDomElement').colorbox.close();
			/*location.href="index.php";*/
		}
	
		function abreVentana(){
			$.colorbox({
	   	href: "ventana_clientes.php", open:true,
			iframe:true, width:"600", height:"500",
			onCleanup:function() {
				$('#solicitado').focus();
				}
			});
			
		}		
		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}

		function inicio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);	
		}	
			
		
		function ventanaArticulos(){
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				 showWarningToast("Debe introducir el codigo del cliente");
			} else {

				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					showWarningToast("Debe seleccionar moneda");
					$("#Amoneda").focus();
				} else {
					$.colorbox({href:"ver_articulos.php",
					iframe:true, width:"95%", height:"95%",
					onCleanup:function() {
						$('#precio_compra').focus();
						}					
					});
				}
			}
		}
		function ventanaService(){
			var codigo=document.getElementById("aCliente").value;
			if (codigo=="") {
				showWarningToast("Debe introducir el codigo del cliente");
			} else {
				var moneda=document.getElementById("Amoneda").value;
				if (moneda=='0') {
					showWarningToast("Debe seleccionar moneda");
					$("#Amoneda").focus();
				} else {
					$.colorbox({href:"ver_service.php?codcliente="+codigo,
					iframe:true, width:"95%", height:"95%",
					});
				}
			}
		}		
		
		function validarcliente(){
			var codigo=document.getElementById("aCliente").value;
				$.colorbox({href:"comprobarcliente.php?codcliente="+codigo,
				iframe:true, width:"350", height:"100",
				
				});
		}	
		
		function validarArticulo() {
			var codbarras=document.getElementById("codbarras").value;
				if (codbarras!="") {
				$.colorbox({href:"comprobararticulos.php?codbarras="+codbarras,
				iframe:true, width:"350", height:"100",

				});
				}			
		}		
		
		function limpiarcaja() {
			document.getElementById("aCliente").value="";
			document.getElementById("nombre").value="";
			document.getElementById("nif").value="";
		}
		
		function actualizar_importe()
			{
				/*Si la factura es en pesos y el articulo esta en dolares aplico el tipo de cambio*/
				var tipocambiofactura=document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
				var tipocambioarcticulo=document.getElementById("moneda").value;
				if (tipocambiofactura==1 && tipocambioarcticulo == 2){
					var precio=document.getElementById("precio").value * parseFloat(document.getElementById("tipocambio").value);
				}
				if (tipocambiofactura==2 && tipocambioarcticulo == 1){
					var precio=document.getElementById("precio").value / parseFloat(document.getElementById("tipocambio").value);
				}
				if ((tipocambiofactura==1 && tipocambioarcticulo == 1) || (tipocambiofactura==2 && tipocambioarcticulo == 2)){
				var precio=document.getElementById("precio").value;
				}
				var cantidad=document.getElementById("cantidad").value;
				var descuento=document.getElementById("descuento").value;
				descuento=descuento/100;
				total=precio*cantidad;
				descuento=total*descuento;
				total=total-descuento;
				var original=parseFloat(total);
				var result=Math.round(original*100)/100 ;
				document.getElementById("importe").value=result;
				document.getElementById("ganancia").value=result - parseFloat(document.getElementById("precio_compra").value)*cantidad;
			}
			
		function validar_cabecera()
			{
				var mensaje="";
				if (document.getElementById("nombre").value=="") mensaje+="  - Nombre<br>";
				if (document.getElementById("fecha").value=="") mensaje+="  - Fecha<br>";
				if (mensaje!="") {
					showWarningToast("Atencion, se han detectado las siguientes incorrecciones:<br><br>"+mensaje);
				} else {
					document.getElementById("descuentogral").value=document.getElementById("descuentogralaux").value;
					document.getElementById("observacion").value=document.getElementById("observacionaux").value;
					document.getElementById("formulario").submit();
				}
			}	
		
		function validar() 
			{
				var mensaje="";
				var entero=0;
				var enteroo=0;
		
				if (document.getElementById("codbarras").value=="") mensaje="  - Codigo de barras<br>";
				if (document.getElementById("descripcion").value=="") mensaje+="  - Descripcion<br>";
				if (document.getElementById("precio").value=="") { 
							mensaje+="  - Falta el precio<br>"; 
						} else {
							if (isNaN(document.getElementById("precio").value)==true) {
								mensaje+="  - El precio debe ser numerico<br>";
							}
						}
				if (document.getElementById("cantidad").value=="") 
						{ 
						mensaje+="  - Falta la cantidad<br>";
						} else {
							enteroo=parseInt(document.getElementById("cantidad").value);
							if (isNaN(enteroo)==true) {
								mensaje+="  - La cantidad debe ser numerica<br>";
							} else {
									document.getElementById("cantidad").value=enteroo;
								}
						}
				if (document.getElementById("descuento").value=="") 
						{ 
						document.getElementById("descuento").value=0 
						} else {
							entero=parseInt(document.getElementById("descuento").value);
							if (isNaN(entero)==true) {
								mensaje+="  - El descuento debe ser numerico<br>";
							} else {
								document.getElementById("descuento").value=entero;
							}
						} 
				if (document.getElementById("importe").value=="") mensaje+="  - Falta el importe<br>";
				
				if (mensaje!="") {
					showWarningToast("Errores detectados:<br>"+mensaje);
				} else {
					var descuentogral=document.getElementById("descuentogralaux").value;
					document.getElementById("baseimponible").value=parseFloat(document.getElementById("baseimponible").value) + parseFloat(document.getElementById("importe").value);
					
					document.getElementById("baseimponibledescuento").value=	Math.round((parseFloat(document.getElementById("baseimponible").value) / (1+descuentogral/100))*100)/100;
					cambio_iva();
					document.getElementById("formulario_lineas").submit();
					document.getElementById("codbarras").value="";
					document.getElementById("detalles").value="";
					document.getElementById("descripcion").value="";
					document.getElementById("precio").value="";
					document.getElementById("cantidad").value=1;
					document.getElementById("moneda").value="";
					document.getElementById("monedaShow").value="";
					document.getElementById("importe").value="";
					document.getElementById("descuento").value=0;
					document.getElementById("precio_compra").value="";
					document.getElementById("ganancia").value='';
	
			
					var txt = "<label for=\"file-upload\" class=\"custom-file-upload\">1) Adjuntar Archivo</label><input id=\"file-upload\" type=\"file\" name=\"item_file[]\" onChange=\"checkExtension(this.value)\">";
					txt+='<div id="dvFileName0" style="float:right; top:0;"></div><div id="dvFile1"></div>';
					document.getElementById("dvFile0").innerHTML = txt;
					document.getElementById("FileCant").innerHTML = '';					
					document.getElementById("dvFile1" ).innerHTML = '';		
					document.getElementById("dvFileName0" ).innerHTML = '';	

					document.getElementById("file_exist").value='0';
				
//					$('#file-upload').html($('#file-upload').clone(true));

					next_id=0;
			      if (newItemShowN!='') {
			      $(newItemShowN).hide();
			      $(newItemHideN).show();
					$('#suggestions').hide();
					$('#suggestionslong').hide();
			      }
			      trigerclass='';
			      //e.preventDefault();
			      						
				}
				$('#codbarras').focus();
				
			}
			
			
		function cambio_iva() {
			var original=parseFloat(document.getElementById("baseimponible").value);

			var result=Math.round(original*100)/100 ;
			document.getElementById("baseimponible").value=result;

			var descuentogral=document.getElementById("descuentogralaux").value;
			if (descuentogral==0) {
			var original=parseFloat(document.getElementById("baseimponible").value);
			} else {
			var original=parseFloat(document.getElementById("baseimponibledescuento").value);
			}
			var result=Math.round(original*100)/100 ;
	
			document.getElementById("baseimpuestos").value=parseFloat(result * parseFloat(document.getElementById("iva").value / 100));
			var original1=parseFloat(document.getElementById("baseimpuestos").value);
			var result1=Math.round(original1*100)/100 ;
			document.getElementById("baseimpuestos").value=result1;
			var original2=parseFloat(result + result1);
			var result2=Math.round(original2*100)/100 ;
			document.getElementById("preciototal").value=result2;
			var total_ganancia=Math.round(($("#baseimponibledescuento").val() - parseFloat($("#total_costo").val())) * 10) / 10;
			$("#total_ganancia").val(total_ganancia);			
		}	

		function actualizo_descuento() {
				var descuentogral=parseFloat(document.getElementById("descuentogralaux").value);
				document.getElementById("baseimponibledescuento").value=	Math.round((parseFloat(document.getElementById("baseimponible").value) * (1-descuentogral/100))*100)/100;
				cambio_iva();
		}		
		
		function busco_tipocambio() {
			var fecha=$("#fecha").val();
				$.post("busco_tipocambio.php?fecha="+fecha,  function(data){
				$("#tipocambio").val(data);
			})(jQuery);			
	 		
		}		
		var tipoaux='';
		function cambio() {
			var Index = document.formulario.Amoneda.options[document.formulario.Amoneda.selectedIndex].value;
			var monArray = new Array();
			monArray[0]="";
			monArray[1]="Pesos";
			monArray[2]="U\$S";
			$("#monShow").val(monArray[Index]);
			$("#monSho").val(monArray[Index]);
			$("#monSh").val(monArray[Index]);

				if (tipoaux==1 && Index == 2){
					document.getElementById("baseimponible").value=Math.round(( $("#baseimponible").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("baseimpuestos").value=Math.round(( $("#baseimpuestos").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() / parseFloat($("#tipocambio").val())) * 100) / 100;
				}
				if (tipoaux==2 && Index == 1){
					document.getElementById("baseimponible").value=Math.round(($("#baseimponible").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("baseimpuestos").value=Math.round(($("#baseimpuestos").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
					document.getElementById("preciototal").value=Math.round(($("#preciototal").val() * parseFloat($("#tipocambio").val())) * 10) / 10;
				}
			tipoaux=Index;
		}	
function pon_costo(total_costo) {
	document.getElementById("total_costo").value=total_costo;
	actualizo_descuento();
	cambio_iva();	
}			
		</script>

<script language="JavaScript" type="text/javascript">

// allow all extensions
var exts = "";

// only allow specific extensions
// var exts = "jpg|jpeg|gif|png|bmp|tiff|pdf";

function checkExtension(value)
{

    if(value=="")return true;
    var re = new RegExp("^.+\.("+exts+")$","i");
    if(!re.test(value))
    {
		var tipo="<img src='../img/sin.png' alt='Tipo no definido' width='16' height='16'>";
		document.getElementById("dvFileName" + next_id ).innerHTML = tipo;
		document.getElementById("file_exist").value=1;
			//alert("Your file extension is not allowed: <br>" + value + "<br><br>Only the following extensions are allowed: "+exts.replace(/\|/g,',')+" <br><br>");
        //return false;
        return true;
    }
    var FileType=value.split('.');
    var tipo=FileType[0]+"&nbsp;<img src='../img/"+FileType[1]+".png' alt='"+FileType[0]+"' width='16' height='16'>";
	document.getElementById("dvFileName" + next_id ).innerHTML = tipo;
	document.getElementById("FileCant").innerHTML = next_id+1;

    return true;
}

var next_id=0;
var max_number =5;
	function _add_more() {
	
		if (next_id>=max_number)
		{
			alert("Nº máximo de archivos alcanzado!");
			return;
		}

		next_id=next_id+1;
		var next_div=next_id+1;
		var txt = "<label for=\"file-upload"+next_div+"\" class=\"custom-file-upload\">"+next_div+") Adjuntar Archivo</label><input id=\"file-upload"+next_div+"\" type=\"file\" name=\"item_file[]\" onChange=\"checkExtension(this.value)\">";
		txt+='<div id="dvFileName'+next_id+'" style="float:right; top:0;"></div><div id="dvFile'+next_div+'"></div>';
		document.getElementById("dvFile" + next_id ).innerHTML = txt;
	}


	function validate(f){
		var chkFlg = false;
		for(var i=0; i < f.length; i++) {
			if(f.elements[i].type=="file" && f.elements[i].value != "") {
				chkFlg = true;
			}
		}
		if(!chkFlg) {
			alert('Seleccione un archivo');
			return false;
		}
		f.pgaction.value='upload';
		return true;
	}
</script>		
	
	</head>

<!--///////////////////////////////////////////////////////-->

	<body onload="inicio();">
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Nuevo Presupuesto </div>
				<div id="frmBusqueda">
				<div class="frmhead">
				   	<div id="frmnewItemMHide" style="display:;">
				   	   <img id="frmnewItemM" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="frmClose">
					   </div>
				<form id="formulario" name="formulario" method="post" action="guardar_presupuesto.php">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
						<tr>
						<td width="100%">
							<table class="fuente8" width="98%" cellspacing=0 cellpadding=2 border=0>
							<tr>				
							<td >C&oacute;digo&nbsp;Cliente </td>
					      <td><input name="codcliente" type="text" class="cajaPequena" id="aCliente" size="6" maxlength="5" onClick="limpiarcaja();" value="<?php echo $codcliente?>" data-index="1">
					      <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" class="simpleinput" onClick="OpenNote('ventana_clientes.php',700,450);" title="Buscar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;">
					       <img id="botonBusqueda" src="../img/cliente.png" width="16" height="16" class="simpleinput" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor" style="vertical-align: middle; margin-top: -1px;"></td>
							<td>Nombre</td>
						    <td><input name="nombre" type="text" class="cajaGrande" id="nombre" size="45" maxlength="45" value="" readonly>
						    </td><td>
						    &nbsp;Solicitado&nbsp;por</td><td>
						    <input name="solicitado" type="text" class="cajaGrande simpleinput" id="solicitado" size="45" value="" maxlength="45" data-index="2"></td>
						    <td>Lugar&nbsp;de&nbsp;Entrega</td><td>
								
								<input name="lugar" type="text" class="cajaGrande simpleinput" id="lugar" size="25" maxlength="100" value="" data-index="3" ></span>
								</td>
						</tr></table></tr>
						<td>
							<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
							<tr>				
						<tr>
				            <td >RUT</td>
				            <td><input name="nif" type="text" class="cajaMedia" id="nif" size="20" maxlength="15" value="" readonly></td>
								<td>Tipo</td>
				            <td>
				            <select id="tipo" name="tipo" class="cajaPequena simpleinput" data-index="4">
									<?php $tipof = array(0=>"Contado", 1=>"Credito", 2=>"Nota Credito");
									if ($tipo==" ")
									{
									echo '<OPTION value="" selected>Selecione uno</option>';
									}
									$x=0;
									$NoEstado=0;
									foreach($tipof as $i) {
									  	if ( $x==$tipo) {
											echo "<OPTION value=$x selected>$i</option>";
											$NoEstado=1;
										} else {
											echo "<OPTION value=$x>$i</option>";
										}
										$x++;
									}
									?>
								</select></td>
				            <td>IVA</td>
				            <td><input name="iva" type="text" class="cajaMinima simpleinput" id="iva" size="5" maxlength="5" onChange="cambio_iva();" value="<?php echo $iva?>" data-index="5"> %</td>
								<td>Forma&nbsp;de&nbsp;pago&nbsp;
														<?php
					  	$query_fp="SELECT * FROM formapago WHERE borrado=0 ORDER BY nombrefp ASC";
						$res_fp=mysqli_query($GLOBALS["___mysqli_ston"], $query_fp);
						$contador=0;
					  ?>
								<select id="codformapago" name="codformapago" class="comboMedio simpleinput" data-index="6">
							
								<option value="0">Seleccione una</option>
								<?php
								while ($contador < mysqli_num_rows($res_fp)) { ?>
								<option value="<?php echo mysqli_result($res_fp, $contador, "codformapago");?>"><?php echo mysqli_result($res_fp, $contador, "nombrefp")?></option>
								<?php 	
								 $contador++;
								} ?>				
								</select>					  
								
								&nbsp;Moneda&nbsp;

						<select onchange="cambio();" name="amoneda" id="Amoneda" class="cajaPequena2 simpleinput" data-index="7">
						<?php $tipofa = array(  0=>"Seleccione uno",  1=>"Pesos", 2=>"U\$S");
						foreach ($tipofa as $key => $i ) {
						  	if ( $moneda==$key ) {
								echo "<OPTION value=$key selected>$i</option>";
							} else {
								echo "<OPTION value=$key>$i</option>";
							}
						}
						?>
						</select>						
						</td>
				            <td>Tipo&nbsp;cambio
								<label>U$S -> $&nbsp;</label><span>
								<input name="tipocambio" type="text" class="cajaPequena2 simpleinput" id="tipocambio" size="5" maxlength="5" value="1" data-index="8"></span>
								</td>
								<td><label>Estado</label><span>
								<select name="estado" id="estado" class="comboMedio simpleinput" data-index="9">
									<option value="0" selected>Seleccione una opción</option>
									<option value="1">Iniciado</option>
									<option value="2">Enviado al cliente</option>
									<option value="3">Rechasado</option>
									<option value="4">Aprovado</option>
									<option value="5">Facturado</option>
								</select>
								</span>
						</td>
						</tr></table></tr></td>
						<td >
							<table class="fuente8"  cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td valign="top">Fecha</td><td valign="top">
							<?php $hoy=date("d/m/Y"); ?>
						    <input name="fecha" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo $hoy;?>" readonly data-index="10"> 
						    <img src="../img/calendario.png" name="Image1" id="Image1" class="simpleinput" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
				            <td valign="top">Fecha Entrega</td>
				            <td valign="top" >							
						    <input name="fechaentrega" type="text" class="cajaPequena" id="fechaentrega" size="10" maxlength="10" value="<?php echo $hoy;?>" readonly data-index="11"> 
						    <img src="../img/calendario.png" name="Image2" id="Image2" class="simpleinput" width="16" height="16" border="0"  onMouseOver="this.style.cursor='pointer'" style="vertical-align: middle; margin-top: -1px;">
								<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fechaentrega",
						     trigger    : "Image2",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script></td>
				            <td valign="top">Sectores / Secciones</td><td valign="top">
						<select name="sector[]" class="comboGrande ComboBox simpleinput" id="newItemMCombo" style="width: 250px; height:50px" multiple="multiple" size="2" data-index="12">

						</select><td valign="top">
				   	<div id="newItemMHide" style="display:;">
				   	   <img id="newItemM" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="trigger">
					   </div>
				   	<div >
				   	   <img src="../img/blank.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" >
					   </div>
				      <div id="newItemMShow" style="display: none; position:absolute; border: 0px solid #000; z-index:10;">
				         <div class="seleccione">Seleccione uno<div style="position:absolute; right:10px; top:3px"> 
				         <img id="newItemM" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div>
				         <div>
				         <input type="text" size="25" id="newItemMInput" onkeyup="lookup('newItemMInput',this.value,'4');" onblur="fill('newItemMInput',);" autocomplete="off"  class="boxinput"/>
				         </div>
				         </div>
				      </div></td>				      						
						<td valign="top">Requerimientos</td><td valign="top"><textarea cols="50" rows="2" name="requerimiento" class="areaTexto simpleinput" data-index="13"></textarea></td>
						</tr>
						</table></td></tr>
					</table>
			  </div>			
				<div id="frmItemMHide" style="display:none; height:16px;">
				   <img id="frmItemM" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer; " class="frmOpen">
				</div>
				</div>
			  <input id="codbarras1" name="codbarras" value="<?php echo @$codbarras;?>" type="hidden">
			  <input id="codpresupuestotmp" name="codpresupuestotmp" value="<?php echo @$codpresupuestotmp;?>" type="hidden">
			  <input id="baseimpuestos2" name="baseimpuestos" value="<?php echo @$baseimpuestos;?>" type="hidden">
			  <input id="baseimponible2" name="baseimponible" value="<?php echo @$baseimponible;?>" type="hidden">
			  <input id="preciototal2" name="preciototal" value="<?php echo @$preciototal;?>" type="hidden">
			  <input id="descuentogral" name="descuentogral" value="<?php echo @$descuentogral;?>" type="hidden">
			  <input id="observacion" name="observacion" value="<?php echo @$observacion;?>" type="hidden">			  
			  
			  <input id="accion" name="accion" value="alta" type="hidden">

			  </form>
			  <br style="line-height:5px">
				<div id="frmBusqueda">
				<form id="formulario_lineas" name="formulario_lineas" method="post" action="frame_lineas.php" target="frame_lineas" enctype="multipart/form-data">
				<table class="fuente8" width="98%" cellspacing=0 cellpadding=0 border=0>
				  <tr>
					<td>
						<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>				  
					<td>Codigo </td>
					<td valign="middle"><input name="codbarras" type="text" class="cajaMedia" id="codbarras" size="15" maxlength="15" data-index="14">
					 <img id="botonBusqueda" src="../img/calculadora.jpg" border="1" align="absmiddle" class="simpleinput" onClick="validarArticulo();" onMouseOver="style.cursor=cursor" title="Validar codigo de barras" style="vertical-align: middle; margin-top: -1px;">
					 <img id="botonBusqueda" src="../img/ver.png" width="16" height="16" onClick="ventanaArticulos();" class="simpleinput" onMouseOver="style.cursor=cursor" title="Buscar articulo" style="vertical-align: middle; margin-top: -1px;">
					<td>Descripcion</td>
					<td ><input name="descripcion" type="text" class="cajaMedia simpleinput" id="descripcion" size="30" maxlength="30" readonly></td>
					 
					<td>Moneda </td>					 
					 <td>
					 <input name="monedaShow" type="text" class="cajaPequena2 simpleinput" id="monedaShow" size="10" maxlength="10" readonly>
					 <input name="moneda"  id="moneda" type="hidden" >
					 </td>
					 <td>Sector/Sección</td><td>
						<select name="codsector" class="comboGrande ComboBox simpleinput" id="ItemMCombo" style="width: 250px; height:25px"  data-index="15">
						<option value=''>Seleccione una opción</option>
						<?php
						/*
							$sel_resultado="SELECT * FROM sector ";
						   $res_resultado=mysql_query($sel_resultado);
							$contador=0;
							$marcaestado=0;
							   while ($contador < mysql_num_rows($res_resultado)) {
								   echo "<option value='". mysql_result($res_resultado,$contador,"codsector")."' style='background-color:#". mysql_result($res_resultado,$contador,"color")."; color:
								    ". color_inverse(mysql_result($res_resultado,$contador,"color")).";'><span style=\"background-color:#". mysql_result($res_resultado,$contador,"color").";
								     color: ". color_inverse( mysql_result($res_resultado,$contador,"color")).";\">". mysql_result($res_resultado,$contador,"descripcion")."</span></option>";
								     $contador++;
								   }
						
						*/
						?>
						</select></td>
						</tr></table></td><td colspan="2" width="100px" valign="top">
				    	<input type="hidden" value="0" name="file_exist"/>
						<div>Archivos:&nbsp;<span id="FileCant"></span>
						<div id="newItemFHide"  style="position:absolute; right:60px; top:3px;">
				   	   <img id="newItemF" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="trigger">
					   </div>
					   </div>
				   	<div >
				   	   <img src="../img/blank.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" >
					   </div>
				      <div id="newItemFShow" style="display: none; position:absolute; height: auto; border: 0px solid #000; z-index:10;">
				         <div class="seleccione">Seleccione uno<div style="position:absolute; right:10px; top:3px;"> 
				         <img id="newItemF" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div>
				         <div style="min-width:250px; position:relative;">
					        <!---->
						<div id="dvFile0">
						
						<label for="file-upload" class="custom-file-upload">
					  	<script type="text/javascript">
					  		document.write(next_id+1);
					  	</script>) Adjuntar Archivo
						</label>	
						<input id="file-upload" type="file" name="item_file[]" onChange="checkExtension(this.value)"><div id="dvFileName0" style="float:right; top:0;"></div><div id="dvFile1"></div>
					        
				      </div>
						<button class="boletin" onClick="javascript:_add_more(0);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Nuevo Archivo</button>				      

				      </div>
				      </div>
				      </div>				
					</td></tr><tr>
					<td>
						<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
				  <tr>
					<td valign="top">Detalles</td>
					<td ><textarea name="detalles" rows="2" cols="50" class="areaTexto" id="detalles" data-index="16"> </textarea>
					</td>
					<td>Costo</td>
					<td ><input name="precio_compra" type="text" class="cajaPequena2 simpleinput" id="precio_compra" size="10" maxlength="10" data-index="17"></td>
					<td>P. Venta</td>
					<td ><input name="precio" type="text" class="cajaPequena2 simpleinput" id="precio" size="10" maxlength="10" onChange="actualizar_importe();" data-index="18"></td>
					<td>Cantidad</td>
					<td><input name="cantidad" type="text" class="cajaMinima simpleinput" id="cantidad" size="10" maxlength="10" value="1" onChange="actualizar_importe();" data-index="19"></td>
					<td >Dcto.</td>
					<td><input name="descuento" type="text" class="cajaMinima simpleinput" id="descuento" size="10" maxlength="10" onChange="actualizar_importe();" data-index="20"> %</td>
					<td>Importe</td>
					<td><input name="importe" type="text" class="cajaPequena2 simpleinput" id="importe" size="10" maxlength="10" value="0" readonly></td>
					<td>Ganancia</td>
					<td><input name="ganancia" type="text" class="cajaPequena2 simpleinput" id="ganancia" size="10" maxlength="10" value="0" readonly></td>
					<td>
						<button class="boletin" onClick="validar();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Agregar articulo</button>					
					</td>
				  </tr>
					</table></td></tr>
				</table>
<!--//fin del form incio-->
				</div>
				<br style="line-height:5px">
				<input name="codarticulo" value="<?php echo @$codarticulo?>" type="hidden" id="codarticulo">

				<div id="frmBusqueda">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="3%">Sec.</td>
							<td width="8%">REFERENCIA</td>
							<td width="30%" class="aIzquierda">DESCRIPCION</td>
							<td width="8%" class="aIzquierda">ADJUNTO</td>
							<td width="8%">CANTIDAD</td>
							<td width="8%">COSTO</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="7%">MONEDA</td>
							<td width="8%">IMPORTE</td>
							<td width="8%">GANANCIA</td>
							<td width="40px">&nbsp;ACCION</td>
						</tr>
						<tr><td width="100%" colspan="12">
					<iframe width="100%" height="160" id="frame_lineas" name="frame_lineas" frameborder="0">
						<ilayer width="100%" height="160" id="frame_lineas" name="frame_lineas"></ilayer>
					</iframe>
				</td></tr>					
				</table>
			  </div>
			  <div id="frmBusqueda">
			<table width="100%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
			<tr>
			<td align="rigth" valign="top">
				<table border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
				<tr>
				<td valign="top">Observaciones</td>
				<td valign="top" rowspan="3"><textarea id="observacionaux" rows="4" cols="40"></textarea>
				</td>
				<td colspan="3"></td>
				<td>Ganancia</td><td><input name="total_costo" type="hidden" id="total_costo" value="0">
					<input class="cajaTotales" name="total_ganancia" type="text" id="total_ganancia" size="12" value=0 align="right" readonly>
				</td>
			    <td class="busqueda">Sub-total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monShow" readonly>
			      <input class="cajaTotales" name="baseimponible" type="text" id="baseimponible" size="12" value=0 align="right" readonly> 
		        </div></td>
				</tr>
				<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="busqueda" >Descuento</td>
				<td class="busqueda" ><input id="descuentogralaux" name="descuentogral" value="0" class="cajaMinima" onChange="actualizo_descuento();"></td>
				<td class="busqueda">%</td>
				<td>	
				<input class="cajaTotales" name="baseimponibledescuento" type="text" id="baseimponibledescuento" size="12" value=0 align="right" readonly> 
				</td>
				<td class="busqueda">IVA </td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSho" readonly>
			      <input class="cajaTotales" name="baseimpuestos" type="text" id="baseimpuestos" size="12" align="right" value=0 readonly>
		        </div></td>
 				</tr>
				<tr>
				<td></td>
				<td>
				<br style="line-height:5px">
					<div align="center">
						<button class="boletin" onClick="validar_cabecera();" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="event.preventDefault();parent.$('idOfDomElement').colorbox.close();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Cancelar</button>
				    	<input id="codfamilia" name="codfamilia" value="" type="hidden">
						<input id="preciototal2" name="preciototal" value="" type="hidden">
						<input id="modif" name="modif" value="0" type="hidden">				    
					</div>
				</td>
				<td colspan="4"></td>
				<td class="busqueda">Precio&nbsp;Total</td>
				<td align="left"><div align="left">
				 <input type="text" class="cajaPequena2" id="monSh" readonly>
			      <input class="cajaTotales" name="preciototal" type="text" id="preciototal" size="12" align="right" value=0 readonly>
				    <input id="codpresupuestotmp" name="codpresupuestotmp" value="<?php echo $codpresupuestotmp;?>" type="hidden">				    
			       
		        </div></td>				
				</tr> 				
				</table>
				</td><td >
				
			  </tr>
		</table>
			  </div>				
			  		<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
			  </form>
			 </div>
	<!--Include the progress bar frame-->
   	 <iframe style="position: relative; top: 5px;" id="progress-frame" name="progress-frame" border="0" src="" scrollbar="no" frameborder="0" scrolling="no"> </iframe>
	<!---->			 
		  </div>
		</div>
<div class="suggestionsBox" id="suggestions" style="display: none;">
      <div class="suggestionList" id="autoSuggestionsList" >
	       &nbsp;
      </div>
</div>

<div class="suggestionsBoxLong" id="suggestionslong" style="display: none;">
      <div class="suggestionListLong" id="autoSuggestionsListlong" >
	       &nbsp;
      </div>
</div> 
	 		
	</body>
</html>
