<?php
/**
 * UYCODEKA
 * Copyright (C) MCC (http://www.mcc.com.uy)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

include ("../conectar.php");
include("../common/verificopermisos.php");
//header('Content-Type: text/html; charset=UTF-8'); 

include ("../funciones/fechas.php"); 

date_default_timezone_set("America/Montevideo"); 
$oid=0;

?>

<html>
<head>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script type="text/javascript" src="../funciones/validar.js"></script>
	
		<script src="../js3/jquery.min.js"></script>
		<link rel="stylesheet" href="../js3/colorbox.css" />
		<script src="../js3/jquery.colorbox.js"></script>
		<script src="../js3/jquery.msgBox.js" type="text/javascript"></script>
		<link href="../js3/msgBoxLight.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="../js3/jquery.toastmessage.css" type="text/css">
		<script src="../js3/jquery.toastmessage.js" type="text/javascript"></script>
		<script src="../js3/message.js" type="text/javascript"></script>
<script type="text/javascript" src="../js3/MaskedPassword.js"></script>		
<!-- iconos para los botones -->       
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">

		<script language="javascript">

		var cursor;
		if (document.all) {
		/*/ Está utilizando EXPLORER*/
		cursor='hand';
		} else {
		/*/ Está utilizando MOZILLA/NETSCAPE*/
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("formulario").reset();
		}
		
		</script>
<script type="text/javascript">
window.onload = function () {

    document.getElementById('aContrasenia').onfocus = function () {
        if (this.defaultValue == this.value) {
            this.type = 'password';
            this.value = '';
        }
    }
}

</script>
<script type="text/javascript">
function OpenNote(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"90%", height:"80%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});

}
function OpenList(noteId){

	$.colorbox({
	   	href: noteId, open:true,
			iframe:true, width:"99%", height:"99%",
			onCleanup:function(){ document.getElementById("form_busqueda").submit(); }
	});
}


</script>	

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


$(document).ready(function()
{
	$('input').each(function(){
    initials[this.id] = $(this).attr('value');
	});

  $('.trigger').click(function(e){
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
      var x = offset.left - this.offsetLeft + 20 ;
      var y = offset.top - this.offsetTop + 10;
      document.getElementById(newItemShow).style.left = x ;
      document.getElementById(newItemShow).style.top = y + 10;

      $(newItemShowN).show(); $(newItemHideN).hide();
      
		/*/$(newItemInputId).caretToStart();*/	   
	   /*/$(newItemInputId).focus().select();*/
	   
      e.preventDefault();
   });//Finaliza trigger

  $('.triggerClose').click(function(e){

      if (newItemShowN!='') {
      $(newItemShowN).hide();
      $(newItemHideN).show();
		$('#suggestions').hide();
		$('#suggestionslong').hide();
      }
      trigerclass='';
      e.preventDefault();
   });/*/Finaliza triggerClose*/
   
   	$('.triggerinput').click(function(e) {
   		trigerclass=1;
   		/*/alert(trigerclass);*/
   	});

  $('.ComboBox').click(function(e){
/*alert(this.id);*/
      newItemDel='#'+this.id;
      $(newItemShowN).hide(); $(newItemHideN).show();
      e.preventDefault();
   });

	$('#formulario').submit(function() {
		$('*').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});

$('.simpleinput').click(function(e){
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
      e.preventDefault();
   }
newItemInput='#'+selected.id;

/*/alert('newItemInput -> '+newItemInput);*/

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
});

$('input').blur(function(){
  if($(this).attr('value')==''){
    $(this).attr('value', initials[this.id]);
  }
  /*/ do other stuff*/
});

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
		//alert(newItemComboN + ' -- ' +item0+ "  --  "+newItemInput + " -- "+item1+ " -- "+item2);
		
		$(newItemInput).val('');
      $(newItemMCombo).append($('<option style="background-color:'+item2+'; color: #'+ invertHex(item2)+';"></option>').attr('value', item0).text(item1));
            
      newItemHide=''; newItemShow=''; newItemHideN=''; newItemShowN=''; newItemInput=''; newItemCombo='';
	}/*/End fill*/	

	
</script>
<script type="text/javascript">

$(document).unbind('keypress');
$(document).keydown(function(e) {
/*//alert(e.keyCode);*/
    switch(e.keyCode) { 
        case 46:
/*/alert(newItemDel);*/
         if(newItemDel!='') {
         $(newItemDel).find('option:selected').remove();
         newItemDel="";
         }
        break;
        case 112:
            alert('Ayuda aún no disponible...');
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
</style>
<script type="text/javascript">
$(document).ready(function(){
   	  $('.checkbox1').each(function() {
            $(this).attr('checked',!$(this).attr('checked'));
	});
});
</script>
<style type="text/css">
@font-face
    {
    font-family:'dotsfont';
    src:url('dotsfont.eot');
    src:url('dotsfont.eot?#iefix')  format('embedded-opentype'),
        url('dotsfont.svg#font')    format('svg'),
        url('dotsfont.woff')        format('woff'),
        url('dotsfont.ttf')         format('truetype');
    font-weight:normal;
    font-style:normal;
}
</style>

<title>Usuario</title>
</head>
<body >

				<form  id="formulario" name="formulario" action="guardar_usuario.php" method="POST">

					<table class="fuente8" cellspacing=0 cellpadding=2 border=0 >
						<thead><tr><td colspan="4"><center><h2>Datos usuario</h2></center></td></tr>
						</thead>
						<tr><td>&nbsp;Tratamiento&nbsp;</td><td>
						<input  name="Atratamiento" id="Atratamiento" value="2" type="hidden" />
						<select  type="text" size="1"  id="tratamiento" class="comboGrande"  onchange="document.getElementById('Atratamiento').value =this.value;">					
						<?php
						$Tipox = array(
						        2=>"Administrador");
						$xx=2;
						foreach($Tipox as $ii) {
								echo "<option value=$xx>$ii</option>";
							$xx++;
						   }
						
						?>
						</select>
						</td>
						<td width="100px">&nbsp;Estado</td>
						<td><label>
						<input type="checkbox" name="estado" value="0" checked> Activo<span></span></label>
						
						</td>
						
						</tr><tr>
						<td width="100px">&nbsp;Nombre</td>
						<td><input type="text" size="30" name="Anombre" id="aNombre"  class="cajaGrande"></input>
						</td><td width="100px" >&nbsp;Apellido</td>
						<td><input type="text" size="30" name="aapellido" id="aApellido" class="cajaGrande"></input>
						</td></tr>
						
						<tr><td>&nbsp;Tel&eacute;fono</td>
						<td><input  type="text" size="30" name="atelefono" id="telefono" class="cajaGrande" ></input>
						</td><td width="100px" >&nbsp;Celular</td>
						<td><input  type="text" size="30" name="acelular" id="celular"  class="cajaGrande"></input>
						</td></tr>
						
						<tr>
						<td width="100px">&nbsp;eMail</td>
						<td><input  type="text" size="30" name="aemail" id="email" class="cajaGrande"></input>
						</td>
						<td rowspan="7" colspan="2" align="center" valign="top" style="height: 160px;">
						<div style="height: 160px; width:335px; overflow:disable; top:-35px;">
						<table width="320px" border=0 ><tr><td valign="top">
						<div class="header" style="width:335px; top:-33px; padding: 4px 0 4px 0;">PERMISOS</div>
						<div class="fixed-table-container" style="position: relative;top:-20px; width:335px; ">
						      <div class="header-background cabeceraTabla"> </div>      			
						<div class="fixed-table-container-inner">
						<div style="height: 160px; width:335px; overflow:auto;">
							<table class="fuente8" width="100%" cellspacing=0 cellpadding=0 border=0>
								<thead>
									<tr>
									<th>&nbsp;</th>
									<th width="30px" ><div class="th-inner">Ver</div></th>
									<th width="30px"><div class="th-inner">Crear</div></th>
									<th width="30px"><div class="th-inner">Mod.</div></th>
									<th width="30px"><div class="th-inner">Elim.</div></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$sectores=array('proveedores','clientes','equipos cliente','servicios cliente','respaldos cliente',
									'familias de articulos','articulos','embalaje','ventas','presupuestos','orden de pedido','compras',
									'cobros rapidos', 'cobros','reportes', 'mantenimiento','usuarios','copias seguridad','dgi');
									foreach($sectores as $xsector) {
									?>
									<tr>
									<td><div align="left"><?php echo ucwords($xsector);?></div></td>
									<?php
									if(strpos($xsector, ' ')!==false){
										$xsector=str_replace(' ', '', $xsector);
									}
									
									?>
									<td><input type="hidden" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_0">
									<label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_v]" value="<?php echo $xsector;?>_v">	<span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_c]" value="<?php echo $xsector;?>_c"><span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_m]" value="<?php echo $xsector;?>_m" ><span></span></label></td>
									<td><label><input class="checkbox1" type="checkbox" name="PERMISOS[<?php echo $xsector;?>_e]" value="<?php echo $xsector;?>_e" ><span></span></label></td>
									</tr>
									<?php } ?>			
									</tbody>
									</table>	
						</div></div></div>
						</td></tr></table></div>
						</td></tr><tr>
						<td>&nbsp;Dirección</td>
						<td><input type="text" size="30" name="adireccion" id="direccion" class="cajaGrande" ></input>
						</td></tr><tr>
						<td>&nbsp;Usuario</td>
						<td><input type="text" size="30" name="Eusuario" id="aUsuario" class="cajaGrande" ></input>
						</td></tr><tr>
						<td>&nbsp;Contraseña</td>
						<td><input type="password" name="Acontrasenia" id="aContrasenia"  size="30" class="cajaGrande" placeholder="Escriba contraseña"  ></input>
						</td></tr>
						<tr><td>Pregunta</td>
						<td>
						<input name="secQ" id="secQ" value="" type="hidden" />
						<select id="Pregunta"  type="text" class="comboGrande"  onchange="document.getElementById('secQ').value =this.value;">
						<?php
							$questions = array();
							$questions[0] = "Seleccione uno";
							$questions[1] = "¿En que ciudad nació?";
							$questions[2] = "¿Cúal es su color favorito?";
							$questions[3] = "¿En qué año se graduo de la facultad?";
							$questions[4] = "¿Cual es el segundo nombre de su novio/novia/marido/esposa?";
							$questions[5] = "¿Cúal es su auto favorito?";
							$questions[6] = "¿Cúal es el nombre de su madre?";
						$xx=1;
						 
						foreach($questions as $pregunta) {
						      echo "<option value='$xx'>$pregunta</option>";
						$xx++;
						}
						?>
						</select>
						</td></tr><tr>
						<td>Respuesta</td>
						<td>
						<input type="text" size="26" name="AsecA" id="aRespuesta"  class="cajaGrande"></input>
						</td></tr>
						<tr><td valign="top">Sectores/Sección</td><td valign="top">
				   	<div id="newItemMHide" style="display:;">
				   	   <img id="newItemM" src="../img/plus.png" width="16" height="16" vspace="0" hspace="0" align="right" border="0" style="cursor:pointer;" class="trigger">
					   </div>
				      <div id="newItemMShow" style="display: none; position:absolute; border: 0px solid #000; z-index:10;">
				         <div class="seleccione">Seleccione uno<div style="position:absolute; right:10px; top:3px"> <img id="newItemM" src="../img/minus.png" width="16" height="16" vspace="0" hspace="0" align="left" border="0" style="cursor:pointer;" class="triggerClose"></div>
				         <div>
				         <input type="text" size="25" id="newItemMInput" onkeyup="lookup('newItemMInput',this.value,'4');" onblur="fill('newItemMInput',);" autocomplete="off"  class="boxinput"/>
				         </div></div>
				      </div>
						<select name="sector[]" class="ComboBox" id="newItemMCombo" style="width: 250px; height:50px" multiple="multiple" size="2">

						</select>				      						
						</td></tr>
						
						</table>

			  <br style="line-height:5px">
			<div  align="center">
						<button class="boletin" onClick="event.preventDefault();selectAll('newItemMCombo',true); validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Guardar</button>
					<input id="accion" name="accion" value="alta" type="hidden">
					<input id="id" name="id" value="" type="hidden">
			      </div>
				</form>

		
<div id="ErrorBusqueda" class="fuente8">
 <ul id="lista-errores" style="display:none; 
	clear: both; 
	max-height: 75%; 
	overflow: auto; 
	position:relative; 
	top: 85px; 
	margin-left: 30px; 
	z-index:999; 
	padding-top: 10px; 
	background: #FFFFFF; 
	width: 585px; 
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px#888;
 	box-shadow: 0 0 5px 5px #888; 
 	bottom: 10px;"></ul>	
 
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
	
<script type="text/javascript">
	
		//apply masking to the demo-field
		//pass the field reference, masking symbol, and character limit
		new MaskedPassword(document.getElementById("aContrasenia"), '\u25CF');
	</script>	
</body></html>