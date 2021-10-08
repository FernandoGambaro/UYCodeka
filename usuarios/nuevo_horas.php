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
 

date_default_timezone_set('America/Montevideo');
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 
$codusuarios=$_GET['codusuarios'];
?>
<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
    <script src="../calendario/jscal2.js"></script>
    <script src="../calendario/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/win2k/win2k.css" />		
		<script type="text/javascript" src="../funciones/validar.js"></script>
		<!-- iconos para los botones -->       
		<script src="../js3/jquery.min.js"></script>
		
<link rel="stylesheet" href="../css3/css/font-awesome.min.css">		
<script type="text/javascript" >
$(document).ready(function () {

$("#horaini").blur(function () {
	if ($("#horaini").val()!="" && $("#horafin").val()!="") {
	restarHoras();
	}
})
$("#horafin").blur(function () {	
	if ($("#horaini").val()!="" && $("#horafin").val()!="") {
	restarHoras();
	}
});

});
</script>

		<script language="javascript">
		
		function cancelar() {
			event.preventDefault();
			parent.$('idOfDomElement').colorbox.close();
		}
		
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
<style type="text/css">
	.weekday-select > div {
  display: inline-block;
  vertical-align: top
}
.weekday-select > div > label {
  clear:both;
  display: block;
}
label span {
    height: 13px;
    width: 13px;
    display: inline-block;
    position: relative;
    top: 3px;
    border-width: 1px;
    border-style: solid;
    border-color: grey;
    border-image: initial;
    margin-right: 3px;
 float: right;
}
		</style>
	<style type="text/css">
.time-input-container{position:relative;display:inline-block;}
.time-input-field{width:90px;height:18px;padding:4px 4px 4px 24px!important;border-radius:2px;border:1px solid #aaa;-webkit-box-sizing:border-box;box-sizing:border-box;height:27px;}
.time-input-icon{width:16px;height:16px;display:block;position:absolute;top:50%;left:5px;margin-top:-6px;cursor:pointer;color:#999;}
	</style>
	<script type="text/javascript" >

function restarHoras() {

  inicio = document.getElementById("horaini").value;
  fin = document.getElementById("horafin").value;
  
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

$("#horasminutos").val(horas+":"+minutos).change();

}

</script>
<style type="text/css">
.duration-input.hours-and-minutes>label{display:inline-block;}
.duration-input.hours-and-minutes>label>input{width:30px;height:27px;border:1px solid #aaa;padding:4px;border-radius:2px;}
.duration-input.hours-and-minutes>label>span{display:block;color:#aaa;margin-left:0;}

</style>		
	</head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header">Nuevo horario</div>
				<div id="frmBusqueda">
				<form id="formulario" name="formulario" method="post" action="guardar_horas.php">
				<input id="codhorarios" name="codhorarios" value="" type="hidden">
				<input id="codusuarios" name="codusuarios" value="<?php echo $codusuarios;?>" type="hidden">
								<table class="fuente8"><tr><td valign="top">
					<table class="fuente8" cellspacing=0 cellpadding=2 border=0>
						<tr>
							<td colspan="2">Vigencia
							&nbsp;<input name="vigencia" type="text" class="cajaPequena" id="fecha" size="10" maxlength="10" value="<?php echo date("d/m/Y");?>" readonly> 
							<img src="../img/calendario.png" name="Image1" width="16" height="16" border="0" id="Image1" onMouseOver="this.style.cursor='pointer'">
						<script type="text/javascript">
						   Calendar.setup({
						     inputField : "fecha",
						     trigger    : "Image1",
						     align		 : "Bl",
						     onSelect   : function() { this.hide() },
						     dateFormat : "%d/%m/%Y"
						   });
						</script>							
						</td>
						  <td colspan="2">Año&nbsp;
						  <select id="anio" name="anio" class="comboPequeno" >
						  <?php
						  for($x=2017; $x<=2050; $x++) {
						  	if($x==date("Y")) {
						  		?>
						  		<option value="<?php echo $x;?>" selected><?php echo $x;?></option>	
						  		<?php 
						  } else {
						  		?>
						  		<option value="<?php echo $x;?>"><?php echo $x;?></option>	
						  		<?php
						  	}
						  	}
						  ?>
												  
						  </select>
</td>
				      </tr>
				      <tr>
				      <td colspan="2">Hora&nbsp;ingreso
			<span style="display:inline-block;" class="time-input-container">
	<input class="time-input-field time-input is-timeEntry Sel_hora time cajaPequena"  type="text" name="horaingreso" id="horaini"   value=""  placeholder="00:00:00">
	<span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click" ></span>
</span></td>
<td>
&nbsp;Hora&nbsp;salida&nbsp;
	<span style="display:inline-block;" class="time-input-container">
	<input class="time-input-field time-input is-timeEntry  Sel_hora time cajaPequena"  type="text" name="horasalida" id="horafin" value="" placeholder="00:00:00" >
	<span class="time-input-icon fa fa-clock-o fa-lg tipped-delegate show-on-click"></span>
</span></td>
</tr><tr>
<td colspan="2">Min. tot. de puente/descanso</td>
<td align="center">
						<select name="descanso" id="descanso" class="cajaPequena2">
						<?php
						//$horas;
						$h='';
						$h1='';
						for ($i=0; $i<12; $i++)
						{
							if(strlen($i)==1){
								$h.="0".$i;
							} else {
								$h.=$i;
							}
								
							for ($x=0; $x<=11; $x++) {
								if ($x==0){
								$h1.=":00";
								} elseif($x==1) {
								$h1.=":0". $x*5;
								}else {
								$h1.=":". $x*5;
								}
								
								?>
								<option value="<?php echo $h.$h1; ?>" ><?php echo $h.$h1; ?></option>
								<?php
								$h1='';
							}
							$h='';
						}
						?>
						</select>				      
</td>

				      </tr>
					    </table></td><td>						
						
				</td></tr></table>				
<div class="reset-this">
<fieldset style="width: 250px;"><legend>Días asignados</legend>

<div class="weekday-select" data-name="days" id="days">
  <div class="week-parts" style="width: 100px;">

    <label>
       <input class="checkbox1"  type="checkbox" data-values="1,2,3,4,5,6,7" data-tag="week-parts" >Todos los días<span></span>
    </label>
  
    <label>
      <input  class="checkbox1"  type="checkbox" data-values="6,7" data-tag="week-parts">Fin de semana<span></span>
    </label>
  
    <label>
      <input  class="checkbox1" type="checkbox" data-values="1,2,3,4,5" data-tag="week-parts">Entre semana<span></span>
    </label>
  </div>
  <div class="days" style="width: 100px;">
      
  <?php
  $checked='';
  $dias=array(1=>'Lunes', 2=>'Martes', 3=>'Miércoles', 4=>'Jueves', 5=>'Viernes', 6=>'Sábado',7=>'Domingo' );
  
    for($i=1; $i <=7; $i++)
    {  
		
		?><label>
	      <input class="checkbox1"  type="checkbox" value="<?php echo $i;?>" name="days[]" data-tag="days"> <?php echo $dias[$i];?><span></span>
	      </label>
		<?php

}
  ?>
      
  </div>
</div>	
</fieldset>			
			  </div>
			  <p>
				<div>
						<button class="boletin" onClick="validar(formulario,true);" onMouseOver="style.cursor=cursor"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Guardar</button>
						<button class="boletin" onClick="cancelar();" onMouseOver="style.cursor=cursor"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Salir</button>
					<input id="accion" name="accion" value="alta" type="hidden">
			  </div>
			  </form>
		  </div>
		  </div>
		</div>
				
<script type="text/javascript" >
function isChecked(element) {
  return element.checked;
}

function getValue(element) {
  return element.value;
}

function WeekdayWidget(element) {
  var parts = Array.apply(null, element.querySelectorAll('.week-parts [type=checkbox]'));
  var days = Array.apply(null, element.querySelectorAll('.days [type=checkbox]'));

  function value() {
    return days.filter(isChecked).map(getValue);
  }

  this.value = value;

  function updateParts(selected) {

    function notSelected(val) {
      return selected.indexOf(val) === -1;
    }

    parts.forEach(function(part) {
      var partDays = part.dataset.values.split(',');
      var notSelectedParts = partDays.filter(notSelected);
      if (notSelectedParts.length === 0) {
        part.checked = true;
        part.indeterminate = false;
      } else if (notSelectedParts.length === partDays.length) {
        part.checked = false;
        part.indeterminate = false;
      } else {
        part.indeterminate = true;
      }
      // if (partDays.length === partDays.filter(notSelected).length)
      // part.checked = partDays.filter(notSelected).length === 0;
    });
  }

  function updateDays(values, checked) {
    days.forEach(function(ele) {
      if (values.indexOf(ele.value) > -1) {
        ele.checked = checked;
      }
    });
  }

  element.addEventListener('change', function(event) {
    if (event.target.tagName === 'INPUT') {
      if (event.target.attributes.getNamedItem('data-tag').value === element.dataset.name) {
        updateParts(value());
      } else {
        updateDays(event.target.dataset.values.split(','), event.target.checked);
        updateParts(value());
      }
    }
  });
}
var widget = new WeekdayWidget(document.getElementById('days'));
</script>	
	<!-- Bootstrap stylesheet -->
<!--link rel="stylesheet" type="text/css" href="../js3/assets/css/bootstrap.min.css"-->

<!-- ClockPicker Stylesheet -->
<link rel="stylesheet" type="text/css" href="../js3/dist/bootstrap-clockpicker.min.css">

<!-- jQuery and Bootstrap scripts -->
<!--script type="text/javascript" src="../js3/assets/js/jquery.min.js"></script-->
<script type="text/javascript" src="../js3/assets/js/bootstrap.min.js"></script>

<!-- ClockPicker script -->
<script type="text/javascript" src="../js3/dist/bootstrap-clockpicker.min.js"></script>

<script type="text/javascript">
$('.clockpicker').clockpicker().find('input').change(function(){
		// TODO: time changed
		console.log(this.value);
	});
$('.Sel_hora').clockpicker({
	autoclose: true,
	donetext:"Seleccionar",
	
});
		</script>		
	</body>
</html>